#!/bin/bash
cd /home/site/wwwroot

while true
do
  php artisan queue:work --sleep=3 --tries=3 --timeout=60
  echo "Worker crashed or stopped. Restarting..."
  sleep 1
done
