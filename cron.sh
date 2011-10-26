#!/bin/sh
cd /home1/jje/public_html/jjeadmin.co.cc/irc/
git pull
chmod 0755 cron.sh
screen php -q /home1/jje/public_html/jjeadmin.co.cc/irc/