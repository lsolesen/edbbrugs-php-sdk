# PHP SDK for EDB-Brugs

[![Build Status](https://travis-ci.org/lsolesen/edbbrugs-php-sdk.png?branch=master)](https://travis-ci.org/lsolesen/edbbrugs-php-sdk) [![Code Coverage](https://scrutinizer-ci.com/g/lsolesen/edbbrugs-php-sdk/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/lsolesen/edbbrugs-php-sdk/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lsolesen/edbbrugs-php-sdk/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lsolesen/edbbrugs-php-sdk/?branch=master) [![Latest Stable Version](https://poser.pugx.org/lsolesen/edbbrugs-php-sdk/v/stable)](https://packagist.org/packages/lsolesen/edbbrugs-php-sdk) [![Total Downloads](https://poser.pugx.org/lsolesen/edbbrugs-php-sdk/downloads)](https://packagist.org/packages/lsolesen/edbbrugs-php-sdk) [![License](https://poser.pugx.org/lsolesen/edbbrugs-php-sdk/license)](https://packagist.org/packages/lsolesen/edbbrugs-php-sdk)

PHP 5 SDK to communicate with [De frie skolers EDB-BRUGS](http://edb-brugs.dk).

## Requirements

- Access to the programs from EDB-BRUGS.
- License for "Webtilmeldinger" (contact EDB-BRUGS support)
- EDB-BRUGS uses the new "stamdata" system

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
        "lsolesen/edbbrugs-php-sdk": "dev-master"
    }
}
```

After running `composer install`, you can take advantage of Composer's autoloader in `vendor/autoload.php`.

## Usage

```php5
<?php
use EDBBrugs\Client;
use EDBBrugs\Credentials;
use EDBBrugs\RegistrationRepository;

$soap = new \SoapClient('https://www.webtilmeldinger.dk/TilmeldingsFormularV2Ws/Service.asmx?wsdl', array('trace' => 1));
$credentials = new Credentials($username, $password, $school_code);
$client = new Client($credentials, $soap);
$registration_repository = new RegistrationRepository($client);

// Add registrations.
$registrations = array(
    array(
        'Kartotek' => 'XX',
        'Kursus' => 'Vinterkursus 18/19',
        // The following is available for Elev, Mor, Far, Voksen
        'Elev.Fornavn' => 'Svend Aage',
        'Elev.Efternavn' => 'Thomsen',
        'Elev.Adresse' => 'Ørnebjergvej 28',
        'Elev.Lokalby' => 'Grejs',
        'Elev.Postnr' => '7100',
        'Elev.Bynavn' => 'Vejle',
        'Elev.CprNr' => '010119421942',
        'Elev.Fastnet' => '75820811',
        'Elev.FastnetBeskyttet' => 0, // 0 = No, 1 = Yes
        'Elev.Mobil' => '75820811',
        'Elev.MobilBeskyttet' => 0, // 0 = No, 1 = Yes
        'Elev.Email' => 'kontor@vih.dk',
        'Elev.Land' => 'Danmark',
        'Elev.Notat' => 'Svend Aage Thomsen er skolens grundlægger',
        // Specific for student
        'Elev.Linje' => 'Fodbold',
        'Voksen.Fornavn' => 'Svend Aage',
        'Voksen.Efternavn' => 'Thomsen',
        'Voksen.Adresse' => 'Ørnebjergvej 28',
        'Voksen.Lokalby' => 'Grejs',
        'Voksen.Postnr' => '7100',
        'Voksen.Bynavn' => 'Vejle',
        'Voksen.Fastnet' => '75820811',
        'Voksen.FastnetBeskyttet' => 0, // 0 = No, 1 = Yes
        'Voksen.Mobil' => '75820811',
        'Voksen.MobilBeskyttet' => 0, // 0 = No, 1 = Yes
        'Voksen.Email' => 'kontor@vih.dk',
        'Voksen.Land' => 'Danmark',
    )
);

$registration_repository->addRegistrations($registrations);

// Get new registrations.
$registration_repository->getNewRegistrations();

// Get handled registrations.
$registration_repository->getHandledRegistrations();
?>
```

## Testing

You can test the methods on the commandline, by running:

    php vendor/bin/phpunit --exclude-group=IntegrationTest tests

If you want to test the integration, please create a ´phpunit.xml´ based on ´phpunit.dist.xml´ with the correct information for accesssing the service.

    php vendor/bin/phpunit tests

During testing you need to manually delete the web registrations using the Windows EDB-BRUGS program. *There is no way to delete using the SOAP webservice*.
