<?php
namespace EDBBrugs\Test;

use EDBBrugs\Registration;
use EDBBrugs\Service;

class EDBBrugsTest extends \PHPUnit_Framework_TestCase
{
    protected $brugs;
    protected $username = 'brugernavn';
    protected $password = 'adgangskode';
    protected $school_code = '999999';

    function setUp()
    {
        $this->brugs = new Registration($this->username, $this->password, $this->school_code);
    }

    function tearDown()
    {
        unset($this->brugs);
    }

    function testConstructor()
    {
        $this->assertTrue(is_object($this->brugs));
    }

    function testGetRequest()
    {
        $expected = '<?xml version="1.0"?>
<Tilmeldinger><User><Username>' . $this->username . '</Username><Passw>' . $this->password . '</Passw><Skolekode>' . $this->school_code . '</Skolekode></User></Tilmeldinger>
';
        $this->assertEquals($expected, $this->brugs->getRequest());
    }

    function testGetRequestWithRegistrations()
    {
        $expected = '<?xml version="1.0"?>
<Tilmeldinger><User><Username>' . $this->username . '</Username><Passw>' . $this->password . '</Passw><Skolekode>' . $this->school_code . '</Skolekode></User><Tilmelding><Elev.Fornavn>Svend Aage</Elev.Fornavn><Elev.Efternavn>Thomsen</Elev.Efternavn></Tilmelding><Tilmelding><Elev.Fornavn>Elev</Elev.Fornavn><Elev.Efternavn>H&#xF8;jskole</Elev.Efternavn></Tilmelding></Tilmeldinger>
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
            $this->brugs->addRegistration($registration);
        }
        $this->assertEquals($expected, $this->brugs->getRequest());
    }
}
