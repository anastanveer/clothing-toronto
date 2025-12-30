### Meta / Facebook Login setup

**App credentials**
- App ID: `1561138268440489`
- App Domains: `torontobytes.com`
- Site URL: `https://torontobytes.com`
- Redirect URI: `https://torontobytes.com/auth/facebook/callback`
- App icon: `public/assets/img/fb.png` (1024×1024)

**Laravel configuration**
1. Copy `.env.example` to `.env` if needed.
2. Set the Facebook keys (already scaffolded at the bottom of the file):
   ```
   FACEBOOK_APP_ID=1561138268440489
   FACEBOOK_APP_SECRET=<paste the regenerated secret from Meta>
   FACEBOOK_REDIRECT_URI=https://torontobytes.com/auth/facebook/callback
   ```
3. Clear cached config when deploying: `php artisan config:clear && php artisan config:cache`.

**Remaining Meta requirements**
- Privacy Policy URL: publish the policy (for example `https://torontobytes.com/privacy-policy`) and add that URL in the Meta dashboard.
- User Data Deletion instructions: provide a short page/section explaining how users can request deletion (for example `https://torontobytes.com/data-deletion` or instructions on the contact page).
- Category: select an e-commerce/shopping category that best matches TorontoBytes.
- Confirm `torontobytes.com` is listed under App Domains and in `Valid OAuth Redirect URIs`.
