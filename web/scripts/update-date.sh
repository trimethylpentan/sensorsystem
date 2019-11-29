#!/usr/bin/env bash

# Liegt auf dem PI unter /usr/local/bin/update-date
# Aktualisiert die Systemzeit auf die aktuelle Zeit
sudo date -s "$(curl -s http://worldclockapi.com/api/json/utc/now | jq -r '.currentDateTime' | sed -e 's/T/ /' | sed -e 's/Z/:00Z/')"
