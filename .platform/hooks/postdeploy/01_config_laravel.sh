#! /bin/bash
chown -R ec2-user /var/app/current/
# cd /var/app/current/
mv .aws .env
chmod -R 0777 storage
chmod 777 storage/framework/cache
