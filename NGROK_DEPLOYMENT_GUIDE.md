# Laravel Ngrok Deployment Guide

## Overview
This guide provides instructions for securely exposing your Laravel application via ngrok for demos, testing, and webhook development.

## Prerequisites
- Ngrok installed and authenticated
- Laravel application running locally
- Composer dependencies installed

## Configuration Applied

### 1. Environment Settings (.env)
The following changes were made to your `.env` file:

```bash
APP_DEBUG=false                           # Security: Disables detailed error messages
APP_URL=https://your-ngrok-url.ngrok-free.app  # Update with your actual ngrok URL
SESSION_DOMAIN=.ngrok-free.app           # Allows sessions to work with ngrok domains
SESSION_SECURE_COOKIE=true               # Forces HTTPS-only cookies
SESSION_SAME_SITE=none                   # Required for cross-origin requests
```

### 2. TrustProxies Middleware
Created `app/Http/Middleware/TrustProxies.php` to handle proxy headers from ngrok, preventing CSRF token mismatches and ensuring proper HTTPS detection.

### 3. Middleware Configuration
Updated `bootstrap/app.php` to prioritize TrustProxies middleware, ensuring proxy headers are processed before other middleware.

### 4. Storage Links
Created symbolic link for storage access:
```bash
php artisan storage:link
```

## Deployment Steps

### 1. Start Laravel Server
```bash
# Clear all caches first
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Start the server (accessible externally)
php artisan serve --host=0.0.0.0 --port=8000
```

### 2. Start Ngrok (Separate Terminal)
```bash
# Expose port 8000 via ngrok
ngrok http 8000
```

### 3. Update Configuration
1. Copy the HTTPS URL from ngrok (e.g., `https://abcd1234.ngrok-free.app`)
2. Update your `.env` file:
   ```bash
   APP_URL=https://abcd1234.ngrok-free.app
   ```
3. Restart Laravel server:
   ```bash
   # Stop server (Ctrl+C) then restart
   php artisan serve --host=0.0.0.0 --port=8000
   ```
4. Clear caches:
   ```bash
   php artisan config:clear
   ```

## Security Considerations

### Critical Security Settings
- **APP_DEBUG=false**: Never expose debug mode publicly
- **SESSION_SECURE_COOKIE=true**: Ensures cookies only work over HTTPS
- **Rate Limiting**: Built-in Laravel throttling protects against abuse
- **CSRF Protection**: Properly configured to work with proxy headers

### What's Protected
- ✅ Debug information hidden from public view
- ✅ Sessions work securely over HTTPS
- ✅ CSRF tokens validated correctly
- ✅ Form submissions work properly
- ✅ File uploads function correctly

## Testing Checklist

Before sharing your ngrok URL, verify:

- [ ] Application loads without errors at ngrok URL
- [ ] Login/authentication works
- [ ] File uploads succeed
- [ ] Sessions persist between requests
- [ ] No 419 CSRF token mismatch errors
- [ ] No mixed-content warnings in browser console
- [ ] Assets (CSS/JS/images) load correctly
- [ ] Webhook endpoints are accessible (if applicable)

## Common Issues & Solutions

### 1. 419 CSRF Token Mismatch
**Cause**: Proxy headers not trusted
**Solution**: TrustProxies middleware handles this automatically

### 2. Mixed Content Warnings
**Cause**: Assets loading over HTTP instead of HTTPS
**Solution**: APP_URL uses HTTPS, assets will load correctly

### 3. Session Loss
**Cause**: Incorrect session domain configuration
**Solution**: SESSION_DOMAIN=.ngrok-free.app allows cross-subdomain sessions

### 4. Ngrok URL Changes
**Solution**: Update APP_URL in .env and restart server + clear caches

## Webhook Configuration

If your application receives webhooks:
- Webhook URLs will be: `https://your-ngrok-url.ngrok-free.app/webhook-endpoint`
- Ngrok provides a stable URL during your session
- Consider using ngrok's reserved domains for persistent URLs

## Best Practices

1. **Always use HTTPS**: Ngrok provides SSL termination automatically
2. **Monitor traffic**: Ngrok dashboard shows all requests
3. **Limit exposure time**: Only keep ngrok running when needed
4. **Update URLs promptly**: Refresh APP_URL when ngrok URL changes
5. **Test thoroughly**: Verify all functionality before sharing

## Emergency Procedures

If you encounter issues:
1. Stop both Laravel server and ngrok
2. Revert .env changes temporarily
3. Clear all caches
4. Restart with default localhost configuration
5. Reapply ngrok configuration step by step

## Support

For ngrok-specific issues:
- Visit https://ngrok.com/docs
- Check ngrok dashboard at http://localhost:4040
- Review ngrok logs in the terminal

For Laravel issues:
- Check Laravel logs in `storage/logs/laravel.log`
- Enable APP_DEBUG temporarily for local troubleshooting
- Review browser developer tools console/network tabs