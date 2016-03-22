<?php
namespace EDBBrugsen\Test;

use EDBBrugsen\Registration;
use EDBBrugsen\Service;

// Hide Soap implementation in another class
// Soap will dependency injected

class MockSoapClient
{
    public function NyTilmelding2()
    {
        $class = new \StdClass;
        $class->NyTilmelding2Result = 'Oprettelse Ok, nye tilmeldinger: 2';
        return $class;
    }
}

class EDBBrugsenSendTest extends \PHPUnit_Framework_TestCase
{
    protected $sender;
    protected $brugsen;
    protected $registrations;

    function setUp()
    {
        $this->brugsen = new Registration('brugernavn', 'adgangskode', '999999');
        $this->registrations = array(
            array(
                'Elev.Fornavn' => 'Svend Aage',
                'Elev.Efternavn' => 'Thomsen',
            ),
            array(
                'Elev.Fornavn' => 'Elev',
                'Elev.Efternavn' => 'Højskole',
            ),
        );
        foreach ($this->registrations as $registration) {
            $this->brugsen->addRegistration($registration);
        }
    }

    function testSoapAddNewRegistrationInteractingWithMockedOutWebservice()
    {
        $soap = new MockSoapClient(WSDL);
        $sender = new Service($soap);
        $this->assertEquals(count($this->registrations), $sender->addNewRegistration($this->brugsen));
    }

    /**
     * @group IntegrationTest
     */
    function testSoapAddNewRegistrationInteractingWithOnlineWebservice()
    {
        $soap = new \SoapClient(WSDL);
        $sender = new Service($soap);
        $brugsen = new Registration(USERNAME, PASSWORD, SKOLEKODE);
        foreach ($this->registrations as $registration) {
            $brugsen->addRegistration($registration);
        }
        $this->assertEquals(count($this->registrations), $sender->addNewRegistration($brugsen));
    }
}
