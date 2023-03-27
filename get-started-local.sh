#!/bin/bash

set -a            
source .env
set +a

echo "Executing ... "
docker run --rm --env-file .env \
    -v "$(pwd)":/workspace/php \
    -w /workspace/php tigris-mongodb-php-local \
    "php getstarted.php"
