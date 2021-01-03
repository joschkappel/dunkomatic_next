#! /bin/bash
cd /var/app/staging/
sudo cp .env.aws .env
sudo chmod -R 0777 storage
sudo chmod 777 storage/framework/cache
