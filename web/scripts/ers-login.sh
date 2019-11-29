#!/bin/bash

# Liegt auf dem PI unter /usr/local/bin/ers-login
# erfragt logindaten um das Internet freizuschalten
read -p 'Name: ' name
read -p 'Password: ' -s password

echo

curl -s 'https://xtm-ers.ers2003.local:4100/wgcgi.cgi' -H 'Connection: keep-alive' -H 'Cache-Control: max-age=0' -H 'Origin: https://xtm-ers.ers2003.local:4100' -H 'Upgrade-Insecure-Requests: 1' -H 'Content-Type: application/x-www-form-urlencoded' -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36 OPR/63.0.3368.35' -H 'Sec-Fetch-Mode: navigate' -H 'Sec-Fetch-User: ?1' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8' -H 'Sec-Fetch-Site: same-origin' -H 'Referer: https://xtm-ers.ers2003.local:4100/logon.shtml?redirect=http://www.google.de' -H 'Accept-Encoding: gzip, deflate, br' -H 'Accept-Language: de-DE,de;q=0.9,en-US;q=0.8,en;q=0.7' --data "fw_username=${name}&fw_password=${password}&fw_domain=ers2003.local&submit=Login&action=fw_logon&fw_logon_type=logon&redirect=http%3A%2F%2Fwww.google.de&lang=en-US" --compressed --insecure

echo
echo 'Your IP is:' $(curl -s v4.ident.me)

echo