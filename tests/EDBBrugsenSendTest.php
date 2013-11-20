<?php
require_once dirname(__FILE__) . '/../src/EDBBrugsen.php';

// Hide Soap implementation in another class
// Soap will dependency injected

class MockSoapClient
{
    public function NyTilmelding2()
    {
        $class = new StdClass;
        $class->NyTilmelding2Result = 'Oprettelse Ok, nye tilmeldinger: 2';
        return $class;
    }
}

class EDBBrugsenSendTest extends PHPUnit_Framework_TestCase
{
    protected $sender;
    protected $brugsen;
    protected $registrations;

    function setUp()
    {
        $this->brugsen = new EDBBrugsen_Registration('brugernavn', 'adgangskode', '999999');
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
        $sender = new EDBBrugsen_Service($soap);
        $this->assertEquals(count($this->registrations), $sender->addNewRegistration($this->brugsen));
    }
    
    /**
     * @group IntegrationTest
     */
    function testSoapAddNewRegistrationInteractingWithOnlineWebservice()
    {
        $soap = new SoapClient(WSDL);
        $sender = new EDBBrugsen_Service($soap);
        $brugsen = new EDBBrugsen_Registration(USERNAME, PASSWORD, SKOLEKODE);
        foreach ($this->registrations as $registration) {
            $brugsen->addRegistration($registration);
        }
        $this->assertEquals(count($this->registrations), $sender->addNewRegistration($brugsen));
    }
}
