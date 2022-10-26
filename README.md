# Dunkomatic NEXT
Dunkomatic is a web application that enables basketball organizations to 
- organize leagues
- manage clubs and teams
- schedule games

All with self service and collaboration in mind.

The application is powered by laravel. 
The current version uses laravel blades, bootstrap v4 and plain javascript as frontend technologies.

It's been tested and runs in production on a docker-based environment provided by laradock.
It requires the following laradock services to be up and running:
- mariadb
- minio
- nginx
- redis
- laravel-echo-server
- php-fpm
- php-worker
- workspace
- certbot (opt)

## Installation
- Download laradock, configure and start above services with docker-compose.
- Download dunkomatic.
- Run migrations
- 


# Author
Jochen Kappel
