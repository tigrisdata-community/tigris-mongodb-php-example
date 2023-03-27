#!/bin/bash
set -a            
source .env
set +a

echo "Executing ... "
docker run --rm --env-file .env \
    -v "$(pwd)":/workspace/php \
    -w /workspace/php ghcr.io/tigrisdata-community/tigris-mongodb-php-example:latest \
    "php getstarted.php"
