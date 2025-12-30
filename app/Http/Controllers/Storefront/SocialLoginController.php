<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\CartOwnership;
use App\Support\CouponAllocator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class SocialLoginController extends Controller
{
    protected const SUPPORTED_PROVIDERS = ['google', 'facebook'];

    public function redirect(Request $request, string $provider): RedirectResponse
    {
        $provider = $this->normalizeProvider($provider);
        $config = $this->providerConfig($provider);

        if (! $config['client_id'] || ! $config['client_secret'] || ! $config['redirect']) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => ucfirst($provider) . ' login is not configured yet. Add the client credentials to enable it.',
                ]);
        }

        $state = Str::random(40);
        $request->session()->put("oauth_state_{$provider}", $state);

        $query = [
            'client_id' => $config['client_id'],
            'redirect_uri' => $config['redirect'],
            'response_type' => 'code',
            'state' => $state,
        ];

        if ($provider === 'google') {
            $query += [
                'scope' => 'openid email profile',
                'access_type' => 'offline',
                'prompt' => 'select_account',
            ];

            return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($query));
        }

        $query += [
            'scope' => 'email,public_profile',
            'display' => 'popup',
        ];

        return redirect()->away('https://www.facebook.com/v19.0/dialog/oauth?' . http_build_query($query));
    }

    public function callback(Request $request, string $provider): RedirectResponse
    {
        $provider = $this->normalizeProvider($provider);
        $config = $this->providerConfig($provider);

        if ($request->has('error')) {
            $message = $request->query('error_description') ?? 'We could not authorize with ' . ucfirst($provider) . '.';

            return redirect()->route('login')->withErrors(['email' => $message]);
        }

        $expectedState = $request->session()->pull("oauth_state_{$provider}");
        $incomingState = (string) $request->query('state', '');

        if (! $expectedState || ! hash_equals($expectedState, $incomingState)) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'We could not verify the ' . ucfirst($provider) . ' login attempt. Please try again.']);
        }

        $code = $request->query('code');

        if (! $code) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Missing authorization code from ' . ucfirst($provider) . '.']);
        }

        try {
            $profile = $provider === 'google'
                ? $this->completeGoogleLogin($config, $code)
                : $this->completeFacebookLogin($config, $code);
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('login')
                ->withErrors(['email' => 'We could not connect to ' . ucfirst($provider) . '. Please try again.']);
        }

        if (empty($profile['email'])) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => ucfirst($provider) . ' did not return an email address.']);
        }

        $user = $this->findOrCreateUser($provider, $profile);
        $sessionId = $request->session()->getId();

        Auth::login($user, true);
        $request->session()->regenerate();
        CartOwnership::migrateSessionCart($sessionId, $user);

        $user->forceFill([
            'last_login_at' => now(),
        ])->save();

        app(CouponAllocator::class)->assignWelcomeBundle($user);

        return redirect()->intended(route('account.dashboard'));
    }

    protected function normalizeProvider(string $provider): string
    {
        $normalized = strtolower($provider);

        abort_unless(in_array($normalized, self::SUPPORTED_PROVIDERS, true), 404);

        return $normalized;
    }

    protected function providerConfig(string $provider): array
    {
        return (array) config("services.{$provider}", []);
    }

    protected function completeGoogleLogin(array $config, string $code): array
    {
        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'redirect_uri' => $config['redirect'],
            'grant_type' => 'authorization_code',
            'code' => $code,
        ]);

        if (! $tokenResponse->ok()) {
            throw new \RuntimeException('Google token exchange failed.');
        }

        $token = $tokenResponse->json()['access_token'] ?? null;

        if (! $token) {
            throw new \RuntimeException('Google did not return an access token.');
        }

        $userResponse = Http::withToken($token)
            ->acceptJson()
            ->get('https://www.googleapis.com/oauth2/v3/userinfo');

        if (! $userResponse->ok()) {
            throw new \RuntimeException('Google user lookup failed.');
        }

        $data = $userResponse->json();

        return [
            'id' => $data['sub'] ?? null,
            'email' => $data['email'] ?? null,
            'name' => $data['name'] ?? trim(($data['given_name'] ?? '') . ' ' . ($data['family_name'] ?? '')),
            'avatar' => $data['picture'] ?? null,
        ];
    }

    protected function completeFacebookLogin(array $config, string $code): array
    {
        $tokenResponse = Http::get('https://graph.facebook.com/v19.0/oauth/access_token', [
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'redirect_uri' => $config['redirect'],
            'code' => $code,
        ]);

        if (! $tokenResponse->ok()) {
            throw new \RuntimeException('Facebook token exchange failed.');
        }

        $accessToken = $tokenResponse->json()['access_token'] ?? null;

        if (! $accessToken) {
            throw new \RuntimeException('Facebook did not return an access token.');
        }

        $userResponse = Http::withToken($accessToken)
            ->acceptJson()
            ->get('https://graph.facebook.com/v19.0/me', [
                'fields' => 'id,name,email,picture.width(400)',
            ]);

        if (! $userResponse->ok()) {
            throw new \RuntimeException('Facebook user lookup failed.');
        }

        $data = $userResponse->json();
        $picture = $data['picture']['data']['url'] ?? null;

        return [
            'id' => $data['id'] ?? null,
            'email' => $data['email'] ?? null,
            'name' => $data['name'] ?? null,
            'avatar' => $picture,
        ];
    }

    protected function findOrCreateUser(string $provider, array $profile): User
    {
        $providerColumn = $provider === 'google' ? 'google_id' : 'facebook_id';
        $user = null;

        if (! empty($profile['id'])) {
            $user = User::where($providerColumn, $profile['id'])->first();
        }

        if (! $user) {
            $user = User::where('email', $profile['email'])->first();
        }

        if (! $user) {
            $user = User::create([
                'name' => $profile['name'] ?: 'Glamer Shopper',
                'email' => $profile['email'],
                'password' => Hash::make(Str::random(32)),
                'is_admin' => false,
                'role' => 'customer',
            ]);
        }

        $user->forceFill([
            'name' => $user->name ?: ($profile['name'] ?: 'Glamer Shopper'),
            'provider_name' => $provider,
            'avatar_url' => $profile['avatar'] ?? $user->avatar_url,
            $providerColumn => $profile['id'],
        ])->save();

        return $user;
    }
}
