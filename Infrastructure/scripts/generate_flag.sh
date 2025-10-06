#!/usr/bin/env bash
# simple random flag generator for local dev
FLAG_NAME=${1:-demo}
RAND=$(head -c8 /dev/urandom | base64 | tr -dc 'A-Za-z0-9' | cut -c1-8)
echo "FLAG{${FLAG_NAME}-${RAND}}" > flags/${FLAG_NAME}.txt
