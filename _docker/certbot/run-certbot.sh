#!/bin/bash

letsencrypt certonly --webroot -w /var/www/letsencrypt -d "$CN" --agree-tos --email "$EMAIL" --non-interactive --text

dir=$(cut -d',' -f1 <<<"$CN")

cp /etc/letsencrypt/archive/"$dir"/cert1.pem /var/certs/cert1.pem
cp /etc/letsencrypt/archive/"$dir"/privkey1.pem /var/certs/privkey1.pem
