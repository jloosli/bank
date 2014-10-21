#!/bin/sh

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

echo "Deploying to server"
echo ""
echo "Setting composer to deploy"
cd ${DIR}
composer install --no-dev --optimize-autoloader
artisan optimize
echo "Copying base files"
rsync -azvh --exclude='*' --include='.env.php' --include='.htaccess' --include='artisan' ./ blue:www/jrbank
echo "Copying app path"
rsync -azv --exclude='.*' app blue:www/jrbank/app
echo "Copying public dev directory"
rsync -azv  --exclude='.*' public/dev/ blue:www/jrbank/public/dev
echo "Copying public app directory"
rsync -azv  --exclude='.*' public/app/ blue:www/jrbank/public/app
echo "copying vendor directory"
rsync -azv  --exclude='.*' vendor blue:www/jrbank/vendor
echo "Setting composer to regular development"
composer install
echo "Deployment completed."
