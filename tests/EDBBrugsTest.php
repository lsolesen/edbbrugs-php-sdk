<?php
namespace EDBBrugs\Test;

use EDBBrugs\RegistrationRepository;
use EDBBrugs\Request;

class EDBBrugsTest extends \PHPUnit_Framework_TestCase
{
    protected $request;
    protected $username = 'brugernavn';
    protected $password = 'adgangskode';
    protected $school_code = '999999';

    public function setUp()
    {
        $this->request = new Request($this->username, $this->password, $this->school_code);
    }

    public function tearDown()
    {
        unset($this->request);
    }

    public function testConstructor()
    {
        $this->assertTrue(is_object($this->request));
    }

    public function testGetRequest()
    {
        $expected = '<?xml version="1.0"?>
<Tilmeldinger><User><Username>' . $this->username . '</Username><Passw>' . $this->password . '</Passw><Skolekode>' . $this->school_code . '</Skolekode></User></Tilmeldinger>
';
        $this->assertEquals($expected, $this->request->getRequest()->asXml());
    }

    public function testGetRequestWithRegistrations()
    {
        $expected = '<?xml version="1.0"?>
<Tilmeldinger><User><Username>' . $this->username . '</Username><Passw>' . $this->password . '</Passw><Skolekode>' . $this->school_code . '</Skolekode></User><Tilmelding><Elev.Fornavn>Svend Aage</Elev.Fornavn><Elev.Efternavn>Thomsen</Elev.Efternavn></Tilmelding><Tilmelding><Elev.Fornavn>Elev</Elev.Fornavn><Elev.Efternavn>H&#xF8;jskole</Elev.Efternavn></Tilmelding></Tilmeldinger>
';
        $registration = new RegistrationRepository($this->request);
        $registration->addRegistration(array(
            'Elev.Fornavn'   => 'Svend Aage',
            'Elev.Efternavn' => 'Thomsen',
        ));
        $registration->addRegistration(array(
            'Elev.Fornavn'   => 'Elev',
            'Elev.Efternavn' => 'Højskole',
        ));
        $this->assertEquals($expected, $this->request->getRequest()->asXml());
    }
}
