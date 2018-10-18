# PVC – A Minimal P[HP] [M]VC Framework

[![Travis](https://img.shields.io/travis/tbreuss/pvc.svg)](https://travis-ci.org/tbreuss/pvc)
[![Packagist](https://img.shields.io/packagist/dt/tebe/pvc.svg)](https://packagist.org/packages/tebe/pvc)
[![GitHub (pre-)release](https://img.shields.io/github/release/tbreuss/pvc/all.svg)](https://github.com/tbreuss/pvc/releases)
[![License](https://img.shields.io/github/license/tbreuss/pvc.svg)](https://github.com/tbreuss/pvc/blob/master/LICENSE)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/tebe/pvc.svg)](https://packagist.org/packages/tebe/pvc)

A minimal **P**[HP] [M]**VC** framework using well-tried concepts in PHP.  


## Prerequisites

- PHP 7.2
- Composer


## Installation

Composer is your friend.

    $ composer create-project tebe/pvc myproject


## Running

    $ cd myproject/example/public
    $ php -S localhost:9999

Start your web browser and open <http://localhost:9999>


### Basic Auth Middleware

The example is using some middlewares.

For the Basic Auth middleware the Login details are:

    Username: user
    Password: pass 

You're ready to go!


## Scripts

#### Testing

Start PHPUnit useing the following composer script.

    $ composer phpunit

#### Codesniffer

Start PHP Codesniffer:

    $ composer phpcs
    
Fix Codesniffer issues:

    $ composer phpcbf
    
#### Both together

    $ composer test
    

## Continues Integration

PVC is using Travis (<https://travis-ci.org/tbreuss/pvc>) for Continuous Integration.


## License

[MIT License](https://github.com/tbreuss/pvc/blob/master/LICENSE)


## Issues

Any suggestions? Open an [issue](https://github.com/tbreuss/pvc/issues).
