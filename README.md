# PVC

Minimal **P**[HP] [M]**VC** framework using well-tried concepts in PHP.  

[![Travis](https://img.shields.io/travis/tbreuss/pvc.svg)](https://travis-ci.org/tbreuss/pvc)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/tbreuss/pvc.svg)](https://scrutinizer-ci.com/g/tbreuss/pvc/)
[![Packagist](https://img.shields.io/packagist/dt/tebe/pvc.svg)](https://packagist.org/packages/tebe/pvc)
[![GitHub (pre-)release](https://img.shields.io/github/release/tbreuss/pvc/all.svg)](https://github.com/tbreuss/pvc/releases)
[![License](https://img.shields.io/github/license/tbreuss/pvc.svg)](https://github.com/tbreuss/pvc/blob/master/LICENSE)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/tebe/pvc.svg)](https://packagist.org/packages/tebe/pvc)


## Features

Beside controllers, an event dispatcher and a PHP two step view including view helpers and extensions PVC supports the following standards:

- PSR-7 HTTP message implementation
- PSR-15 HTTP server-side middlewares
- PSR-17 HTTP factories implementation 

Others are planned.


## Prerequisites

- PHP 7.2
- Composer
- [Docker]


## Installation

### Composer

It is recommended to install PVC with Composer.

    $ composer create-project tebe/pvc myproject


## Running

You can run the included example website with PHP's built-in server or Docker.


### PHP built-in server

Run the following command in terminal to start localhost web server:
 
    $ cd myproject/example/public
    $ php -S localhost:9999

Start your web browser and open <http://localhost:9999>

Stop the server by entering CTRL+C.


### Docker

Start the example application stack using Docker Compose:

    $ cd myproject
    $ docker-compose up -d

Start your web browser and open <http://localhost:9090>

Stop the application stack:

    $ cd myproject
    $ docker-compose down


### Middlewares

The example is using some middlewares.

For the BasicAuth middleware the Login details are:

    Username: user
    Password: pass 

You're ready to go!


## Testing & Code quality

We've integrated several Composer scripts to ensure code quality.

    # Using PHPUnit
    $ composer phpunit

    # Using PHP-Codesniffer
    $ composer phpcs
    
    # Fixing Codesniffer issues
    $ composer phpcbf
    
    # Using both together
    $ composer test


## Continuous Integration

PVC is using [Travis](<https://travis-ci.org/tbreuss/pvc>) for Continuous Integration.


## License

[MIT License](https://github.com/tbreuss/pvc/blob/master/LICENSE)


## Issues

Any suggestions? Open an [issue](https://github.com/tbreuss/pvc/issues).
