#!/bin/bash

set -e

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

echo "Deploying to server"
echo ""
echo "Setting composer to deploy"
cd ${DIR}
composer install --no-dev --optimize-autoloader
./artisan optimize
echo "Building latest app"
cd "${DIR}/public/dev"
grunt build
cd ${DIR}
echo "Copying base files"
rsync -azv --exclude='*' --include='.env.php' --include='.htaccess' --include='artisan' ./ blue:www/jrbank/
echo "Copying app path"
rsync -azv --exclude='.*' --exclude='storage' app/ blue:www/jrbank/app/
echo "Copying public directory"
rsync -azvv  --exclude='.*' --exclude='*-spec.js' public/ blue:www/jrbank/public/
#echo "Copying public dev directory"
#rsync -azv  --exclude='.*' public/dev/ blue:www/jrbank/public/dev
#echo "Copying public app directory"
#rsync -azv  --exclude='.*' --exclude='*-spec.js' public/app/ blue:www/jrbank/public/app
echo "copying vendor directory"
rsync -azv  --exclude='.*' vendor/ blue:www/jrbank/vendor
echo "Setting composer to regular development"
composer install
echo "Deployment completed."
