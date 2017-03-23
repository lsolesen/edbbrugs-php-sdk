<?php
namespace EDBBrugs\Test;

use EDBBrugs\RegistrationRepository;
use EDBBrugs\Request;
use EDBBrugs\Service;

// Hide Soap implementation in another class
// Soap will dependency injected

class MockSoapClient extends \SoapClient
{
    public function __construct()
    {
        // Left empty on purpose.
    }

    public function NyTilmelding2()
    {
        $class = new \StdClass;
        $class->NyTilmelding2Result = 'Oprettelse Ok, nye tilmeldinger: 2';
        return $class;
    }
}

class EDBBrugsSendTest extends \PHPUnit_Framework_TestCase
{
    protected $sender;
    protected $request;
    protected $registrations;

    public function setUp()
    {
        $this->request = new Request('brugernavn', 'adgangskode', '999999');
        $this->registration = new RegistrationRepository($this->request);
        $this->registrations = array(
            array(
                'Kartotek' => 'T3',
                'Kursus' => 'Vinterkursus 18/19',
                // The following can be repeated for Mor, Far, Voksen
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
            ),
            array(
                'Kartotek' => 'T3',
                'Kursus' => 'Forårskursus 18/19',
                // The following can be repeated for Mor, Far, Voksen
                'Elev.Fornavn' => 'Ole',
                'Elev.Efternavn' => 'Damgård',
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
            ),
        );
        foreach ($this->registrations as $registration) {
            $this->registration->addRegistration($registration);
        }
    }

    public function testSoapAddNewRegistrationInteractingWithMockedOutWebservice()
    {
        $soap = new MockSoapClient(WSDL);
        $sender = new Service($soap);
        $this->assertEquals(count($this->registrations), $sender->addNewRegistration($this->request));
    }

    /**
     * @group IntegrationTest
     * @expectedException Exception
     * @expectedExceptionMessage Brugernavn / adgangskode er ikke korrekt
     */
    public function testSoapAddNewRegistrationInteractingWithOnlineWebserviceWithWrongCredentials()
    {
        $soap = new \SoapClient(WSDL);
        $sender = new Service($soap);
        $brugs = new Request('BRUGERNAVN', 'PASSWORD', 'SKOLEKODE');
        $registration = new RegistrationRepository($brugs);
        foreach ($this->registrations as $reg) {
            $registration->addRegistration($reg);
        }

        $this->assertEquals(count($this->registrations), $sender->addNewRegistration($brugs));
    }

    /**
     * @group IntegrationTest
     */
    public function testSoapAddNewRegistrationInteractingWithOnlineWebservice()
    {
        $soap = new \SoapClient(WSDL);
        $sender = new Service($soap);
        $brugs = new Request(USERNAME, PASSWORD, SKOLEKODE);
        $registration = new RegistrationRepository($brugs);
        foreach ($this->registrations as $reg) {
            $registration->addRegistration($reg);
        }
        $this->assertEquals(count($this->registrations), $sender->addNewRegistration($brugs));
    }
}
