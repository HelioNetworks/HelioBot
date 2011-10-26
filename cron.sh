#!/bin/sh
screen php -q /home1/jje/public_html/jjeadmin.co.cc/irc/index.php
cd /home1/jje/public_html/jjeadmin.co.cc/irc/
rm cron.sh
git reset --hard HEAD~1
git pull
chmod 0755 cron.sh