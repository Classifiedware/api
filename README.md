# What does it exactly do?

Headless API that provides an all-in-one solution for offering classifieds for every use case currently such as car dealer

# Installation

## Local Installation

### Docker

Add **classifiedware-api.test** to your systems `/etc/hosts`

`0.0.0.0 classifiedware-api.test`

After cloning the repository run

`docker-compose up -d`

this may take a while you can grab your favourite drink while it's running

Once the installation has finished connect to the container

`docker-compose exec php-fpm bash`

Now we need to install the composer dependencies

`composer install`