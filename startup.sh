cp /home/site/wwwroot/default /etc/nginx/sites-available/default

service nginx reload

php /home/site/wwwroot/artisan down --refresh=15 --secret="1630542a-246b-4b66-afa1-dd72a4c43515"

php /home/site/wwwroot/artisan migrate --force

php /home/site/wwwroot/artisan storage:link

# Optimize
php /home/site/wwwroot/artisan optimize

php /home/site/wwwroot/artisan up
