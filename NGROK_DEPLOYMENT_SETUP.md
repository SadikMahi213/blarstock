# Ngrok Deployment Setup Guide for Laravel Application

## 1. Install Ngrok

### Option 1: Download Ngrok manually
1. Visit https://ngrok.com/download
2. Download the Windows version
3. Extract the ngrok.zip file
4. Place ngrok.exe in a convenient location (e.g., C:\ngrok\)
5. Add the ngrok directory to your system PATH:
   - Press Win + R, type "sysdm.cpl", press Enter
   - Click "Environment Variables"
   - Under "System Variables", find and select "Path", click "Edit"
   - Click "New" and add your ngrok directory (e.g., C:\ngrok\)
   - Click "OK" to save

### Option 2: Install via Chocolatey (if available)
```cmd
choco install ngrok
```

## 2. Prepare Your Laravel Application for External Access

### Generate Application Key (if not already done)
```bash
php artisan key:generate
```

### Update .env file for external access
Your .env file should be properly configured. Here's the recommended configuration for external access:

```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=your-generated-app-key
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost
APP_HOST=localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blarstu2_stock20
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120

CACHE_STORE=database

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## 3. Start Your Laravel Application

First, make sure your Laravel application is running locally:

```bash
php artisan serve
```

By default, Laravel serves on http://127.0.0.1:8000 or http://localhost:8000

## 4. Configure Ngrok

### Basic Ngrok Usage
Open a new terminal/command prompt window and run:

```bash
ngrok http 8000
```

This will expose your local Laravel application running on port 8000 to the internet.

### Ngrok with Custom Domain (if you have a paid account)
```bash
ngrok http -subdomain=your-subdomain 8000
```

### Ngrok with Custom Configuration File
Create a configuration file at ~/.ngrok2/ngrok.yml (or C:\Users\[username]\.ngrok2\ngrok.yml on Windows):

```yaml
authtoken: YOUR_AUTHTOKEN_HERE
region: us  # Choose your region: us, eu, au, ap, sa, jp, in

tunnels:
  laravel-app:
    proto: http
    addr: 8000
    inspect: true
    schemes:
      - https
```

Then start with:
```bash
ngrok start laravel-app
```

## 5. Alternative Method: Direct Port Forwarding

If you prefer to run ngrok and Laravel in one command, you can use:

```bash
# Start Laravel in background and ngrok together
start "Laravel Server" cmd /c "php artisan serve"
timeout 5
ngrok http 8000
```

## 6. Access Your Deployed Application

Once ngrok is running, you'll see output similar to:
```
Session Status                online
Account                       your-email@example.com
Version                       3.x.x
Region                        United States (us)
Forwarding                    https://xxxx-xx-xxx-xxx-xxx.ngrok.io -> http://localhost:8000
Forwarding                    http://xxxx-xx-xxx-xxx-xxx.ngrok.io -> http://localhost:8000
```

Your application will be accessible at the provided ngrok URLs.

## 7. Troubleshooting Common Issues

### Database Connection Issues
If you're having database issues when accessing via ngrok:
- Make sure your database server is running locally
- Ensure your DB_HOST is set correctly in .env
- Verify your database credentials are correct

### Asset Loading Issues
If CSS/JS files don't load properly:
- Check if your APP_URL is set correctly
- Laravel Mix may need to be recompiled: `npm run dev` or `npm run prod`

### Session/Cookie Issues
- Make sure SESSION_DRIVER is set appropriately
- Some browsers may block third-party cookies from ngrok domains

## 8. Security Considerations

⚠️ **Important Security Notes:**
- Ngrok URLs are publicly accessible - anyone with the URL can access your application
- Don't expose sensitive data or admin panels unnecessarily
- Use `APP_DEBUG=true` only for development purposes
- Consider adding basic authentication for sensitive deployments
- Ngrok free accounts show ads and may have rate limits

## 9. Stopping the Tunnel

To stop ngrok, press `Ctrl+C` in the terminal where ngrok is running.

## 10. Advanced Configuration

### Using Ngrok with Different Ports
If you run Laravel on a different port:
```bash
php artisan serve --port=8080
ngrok http 8080
```

### Using Ngrok with HTTPS Only
```bash
ngrok http -bind-tls=true 8000
```

## Quick Start Command Sequence:

1. Ensure your Laravel app is working locally:
   ```bash
   php artisan serve
   ```

2. In a new terminal, start ngrok:
   ```bash
   ngrok http 8000
   ```

3. Use the HTTPS URL provided by ngrok to share your application!

## Example Complete Setup Script:

Create a batch file `deploy-ngrok.bat`:
```batch
@echo off
echo Starting Laravel development server...
start "Laravel Server" cmd /c "php artisan serve --host=127.0.0.1 --port=8000"
echo Waiting for Laravel to start...
timeout /t 10 /nobreak >nul
echo Starting Ngrok tunnel...
ngrok http 8000
pause
```

Remember to close ngrok when you're done by pressing `Ctrl+C` in the ngrok terminal window.