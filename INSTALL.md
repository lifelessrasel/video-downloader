# Video Downloader Installation Guide (Ubuntu/Linux)

This guide details how to deploy the Video Downloader application on a clean Ubuntu server (20.04/22.04/24.04).

## 1. System Requirements

Ensure your server matches these requirements:
- **OS**: Ubuntu 20.04 or higher
- **Web Server**: Nginx (preferred) or Apache
- **PHP**: 8.2 or higher
- **Database**: MySQL 8.0+ or MariaDB 10.6+
- **Cache/Queue**: Redis (Critical for performance and job handling)
- **Tools**: FFmpeg, Python 3, yt-dlp, aria2c

## 2. Install Dependencies

Run the following commands as `root` or using `sudo`:

```bash
# Update System
sudo apt update && sudo apt upgrade -y

# Install Basic Tools
sudo apt install -y git curl zip unzip git supervisor python3-pip aria2 ffmpeg

# Install Nginx
sudo apt install -y nginx

# Install PHP 8.2/8.3 and extensions
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-common php8.2-mysql php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath php8.2-redis php8.2-intl

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js (for frontend assets)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Install yt-dlp (Latest Version is Required)
sudo curl -L https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp -o /usr/local/bin/yt-dlp
sudo chmod a+rx /usr/local/bin/yt-dlp
sudo yt-dlp -U # Update to make sure
```

## 3. Install Redis

Redis is required for the download queue and analytics.

```bash
sudo apt install -y redis-server
sudo systemctl enable redis-server
sudo systemctl start redis-server
```

## 4. Application Setup

Navigate to your web directory (e.g., `/var/www/html`):

```bash
cd /var/www/html
git clone https://github.com/lifelessrasel/video-downloader.git
cd video-downloader
```

### Configure Permissions
```bash
sudo chown -R www-data:www-data /var/www/html/video-downloader
sudo chmod -R 775 /var/www/html/video-downloader/storage
sudo chmod -R 775 /var/www/html/video-downloader/bootstrap/cache
```

### Install Dependencies
```bash
# PHP Dependencies
composer install --optimize-autoloader --no-dev

# Frontend Dependencies
npm install
npm run build
```

### Environment Configuration
```bash
cp .env.example .env
nano .env
```

Update the following in `.env`:
- `APP_URL=https://your-domain.com`
- `DB_...` details
- `QUEUE_CONNECTION=redis` (IMPORTANT)
- `CACHE_STORE=redis`
- `SESSION_DRIVER=redis`

### Database Setup
```bash
php artisan key:generate
php artisan migrate --seed
```

## 5. Queue Worker Setup (Supervisor)

The application uses a background queue to process downloads. This **must** be kept running.

Create a Supervisor config:
`sudo nano /etc/supervisor/conf.d/video-downloader.conf`

Paste content:
```ini
[program:video-downloader-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/video-downloader/artisan queue:listen --timeout=600 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/html/video-downloader/storage/logs/worker.log
stopwaitsecs=3600
```
*Note: We use `queue:listen` and `--timeout=600` (10 minutes) to allow large file processing.*

Enable and Start:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start video-downloader-worker:*
```

## 6. Nginx Configuration

Create a config file:
`sudo nano /etc/nginx/sites-available/video-downloader`

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/html/video-downloader/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
    
    # Increase timeout for long downloads serving
    fastcgi_read_timeout 600;
    client_max_body_size 100M;
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/video-downloader /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## 7. Cron Jobs (Optional but Recommended)

For cleaning up old files:

```bash
crontab -e
```
Add:
```
* * * * * cd /var/www/html/video-downloader && php artisan schedule:run >> /dev/null 2>&1
```

## Done!
Access your site at `http://your-domain.com`.
Admin Panel at `/login`.
