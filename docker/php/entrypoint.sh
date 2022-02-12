#!/usr/bin/env bash

echo "date.timezone = Europe/Moscow" > /usr/local/etc/php/conf.d/timezone.ini
chmod +x /app/bin/console

# start dev server
php -S 0.0.0.0:80 -t /app/public/
