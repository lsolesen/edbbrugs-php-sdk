# PHP SDK for EDB-Brugs

[![Build Status](https://travis-ci.org/lsolesen/edbbrugs-php-sdk.png?branch=master)](https://travis-ci.org/lsolesen/edbbrugs-php-sdk) [![Code Coverage](https://scrutinizer-ci.com/g/lsolesen/edbbrugs-php-sdk/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/lsolesen/edbbrugs-php-sdk/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lsolesen/edbbrugs-php-sdk/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lsolesen/edbbrugs-php-sdk/?branch=master) [![Latest Stable Version](https://poser.pugx.org/lsolesen/edbbrugs-php-sdk/v/stable)](https://packagist.org/packages/lsolesen/edbbrugs-php-sdk) [![Total Downloads](https://poser.pugx.org/lsolesen/edbbrugs-php-sdk/downloads)](https://packagist.org/packages/lsolesen/edbbrugs-php-sdk) [![License](https://poser.pugx.org/lsolesen/edbbrugs-php-sdk/license)](https://packagist.org/packages/lsolesen/edbbrugs-php-sdk)

PHP 5 SDK to communicate with [De frie skolers EDB-BRUGS](http://edb-brugs.dk).

## Getting started

The service communicates via SOAP to EDB-BRUGS. You are only able to create new registrations which has to manually manipulated and put into the correct courses using the EDB-BRUGS Windows program.

To get access to the service, you need to contact the [support at EDB-BRUGS](http://edb-brugs.dk). You need following information:

- WSDL for the SOAP-service (append ?wsdl to the WSDL-endpoint you recieve from EDB-BRUGS)
- Username
- Password
- School Code

## Installation

### Composer

Simply add a dependency on lsolesen/edbbrugs-php-sdk to your project's `composer.json` file if you use Composer to manage the dependencies of your project. Here is a minimal example of a composer.json file that just defines a dependency on Billy PHP SDK 2.1:

```
{
    "require": {
        "lsolesen/edbbrugs-php-sdk": "1.0.*"
    }
}
```

After running `composer install`, you can take advantage of Composer's autoloader in `vendor/autoload.php`.

## Testing

During testing you need to manually delete the web registrations using the Windows program.
