#!/usr/bin/env bash
set -e


FLAG_DIR="/var/www/html/flags"
mkdir -p "$FLAG_DIR"


# if a generic CTF_FLAG env is provided, write all challenge flags with same value suffix (CI-friendly)
if [ -n "${CTF_FLAG:-}" ]; then
echo "$CTF_FLAG" > "$FLAG_DIR/ctf-global-flag.txt"
fi


# ensure permissions
chown -R www-data:www-data /var/www/html/wp-content /var/www/html/flags || true


# start apache (the base image will call apache2-foreground)
exec apache2-foreground
