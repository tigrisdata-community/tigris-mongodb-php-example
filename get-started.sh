#!/bin/bash
set -a            
source .env
set +a

echo "Executing ... "
docker run --rm --env-file .env \
    -v "$(pwd)":/workspace/php \
    -w /workspace/php ghcr.io/mongodb-developer/get-started-php:0.1 \
    "php getstarted.php"
