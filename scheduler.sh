#!/bin/bash

cd /home/u478565107/public_html
php artisan schedule:run >> storage/logs/scheduler.log 2>&1