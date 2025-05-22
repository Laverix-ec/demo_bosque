#!/bin/bash
cd /home/site/wwwroot

while true
do
  php artisan pulse:check
  echo "Pulse command crashed or stopped. Restarting..."
  sleep 1
done
