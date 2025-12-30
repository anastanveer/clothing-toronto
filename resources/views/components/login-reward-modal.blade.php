<div class="glamer-reward-modal" data-login-reward-modal hidden>
    <div class="glamer-reward-modal__overlay" data-login-reward-action="dismiss"></div>
    <div class="glamer-reward-modal__panel" role="dialog" aria-modal="true" aria-labelledby="login-reward-title">
        <button type="button" class="glamer-reward-modal__close" data-login-reward-action="dismiss" aria-label="Close reward reminder">
            &times;
        </button>
        <div class="glamer-reward-modal__content">
            <span class="glamer-reward-modal__badge">Glamer Rewards</span>
            <h3 id="login-reward-title">Claim 50 reward points</h3>
            <p class="mb-0">Sign in before checkout to instantly collect 50 welcome points and track your orders. Prefer to stay guest? That’s fine&mdash;your bag is ready.</p>
        </div>
        <div class="glamer-reward-modal__actions">
            <a href="{{ route('login') }}" class="ul-btn ul-btn--primary" data-login-reward-action="login">Log in &amp; earn points</a>
            <button type="button" class="glamer-reward-modal__skip" data-login-reward-action="dismiss">Continue as guest</button>
        </div>
    </div>
</div>
