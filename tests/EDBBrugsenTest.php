<?php
namespace EDBBrugsen\Test;

use EDBBrugsen\Registration;
use EDBBrugsen\Service;

class EDBBrugsenTest extends \PHPUnit_Framework_TestCase
{
    protected $brugsen;
    protected $username = 'brugernavn';
    protected $password = 'adgangskode';
    protected $school_code = '999999';

    function setUp()
    {
        $this->brugsen = new Registration($this->username, $this->password, $this->school_code);
    }

    function tearDown()
    {
        unset($this->brugsen);
    }

    function testConstructor()
    {
        $this->assertTrue(is_object($this->brugsen));
    }

    function testGetRequest()
    {
        $expected = '<?xml version="1.0"?>
<Tilmeldinger><User><Username>' . $this->username . '</Username><Skolekode>' . $this->school_code . '</Skolekode><Passw>' . $this->password . '</Passw></User></Tilmeldinger>
';
        $this->assertEquals($expected, $this->brugsen->getRequest());
    }

    function testGetRequestWithRegistrations()
    {
        $expected = '<?xml version="1.0"?>
<Tilmeldinger><User><Username>' . $this->username . '</Username><Skolekode>' . $this->school_code . '</Skolekode><Passw>' . $this->password . '</Passw></User><Tilmelding><Elev.Fornavn>Svend Aage</Elev.Fornavn><Elev.Efternavn>Thomsen</Elev.Efternavn></Tilmelding><Tilmelding><Elev.Fornavn>Elev</Elev.Fornavn><Elev.Efternavn>H&#xF8;jskole</Elev.Efternavn></Tilmelding></Tilmeldinger>
';
        $registrations[] = array(
            'Elev.Fornavn'   => 'Svend Aage',
            'Elev.Efternavn' => 'Thomsen',
        );
        $registrations[] = array(
            'Elev.Fornavn'   => 'Elev',
            'Elev.Efternavn' => 'Højskole',
        );
        foreach ($registrations as $registration) {
            $this->brugsen->addRegistration($registration);
        }
        $this->assertEquals($expected, $this->brugsen->getRequest());
    }
}
