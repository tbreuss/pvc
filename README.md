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


### Middlewares

The example is using some middlewares.

For the BasicAuth middleware the Login details are:

    Username: user
    Password: pass 

You're ready to go!


## Testing & Code quality

We've integrated several scripts to ensure code quality.

    # Using PHPUnit
    $ composer phpunit

    # Using PHP-Codesniffer
    $ composer phpcs
    
    # Fixing Codesniffer issues
    $ composer phpcbf
    
    # Using both together
    $ composer test


## Continuous Integration

PVC is using [Travis](<https://travis-ci.org/tbreuss/pvc>) for it's Continuous Integration.


## License

[MIT License](https://github.com/tbreuss/pvc/blob/master/LICENSE)


## Issues

Any suggestions? Open an [issue](https://github.com/tbreuss/pvc/issues).
