<?php
namespace EDBBrugs\Test;

use EDBBrugs\RegistrationRepository;
use EDBBrugs\RegistrationsCreateResponse;
use EDBBrugs\ClientInterface;
use EDBBrugs\Credentials;
use EDBBrugs\Client;
use EDBBrugs\Response;

class MockClient implements ClientInterface
{
    public function createNewRegistrations(array $registrations)
    {
        $class = new \StdClass;
        $class->NyTilmelding2Result = 'Oprettelse Ok, nye tilmeldinger: 2';
        return new RegistrationsCreateResponse($class);
    }

    public function getNewRegistrations()
    {
        $class = new \StdClass;
        $class->HentNyeTilmeldingerV2Result = '<NewDataSet />';
        return new Response($class);
    }

    public function getHandledRegistrations()
    {
        $class = new \StdClass;
        $class->HentBehandledeTilmeldingerV2Result = '<NewDataSet />';
        return new Response($class);
    }
}

class RegistrationRepositoryTest extends \PHPUnit_Framework_TestCase
{
    protected $soap;
    protected $registrations;

    public function setUp()
    {
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
                'Elev.Fastnet' => '+46 70 716 31 39',
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
                'Elev.Adresse' => 'Ørnebjergvej 28, Ørnebjergvej 28, Ørnebjergvej 28',
                'Elev.Lokalby' => 'Grejs',
                'Elev.Postnr' => '7100',
                'Elev.Bynavn' => 'Vejle',
                'Elev.Kommune' => 'Vejle',
                'Elev.CprNr' => '010119421942',
                'Elev.Fastnet' => '+45 75 82 08 11',
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
                'Voksen.Fastnet' => '+45 75 82 08 11',
                'Voksen.FastnetBeskyttet' => 0, // 0 = No, 1 = Yes
                'Voksen.Mobil' => '75820811',
                'Voksen.MobilBeskyttet' => 0, // 0 = No, 1 = Yes
                'Voksen.Email' => 'kontor@vih.dk',
                'Voksen.Land' => 'Danmark',
            ),
        );
    }

    public function testSoapAddNewRegistrationInteractingWithMockedOutWebservice()
    {
        $client = new MockClient();
        $repository = new RegistrationRepository($client);
        $this->assertEquals(count($this->registrations), $repository->addRegistrations($this->registrations)->getCount());
    }

    /**
     * @group IntegrationTest
     * @expectedException Exception
     * @expectedExceptionMessage Brugernavn / adgangskode er ikke korrekt
     */
    public function testSoapAddNewRegistrationInteractingWithOnlineWebserviceWithWrongCredentials()
    {
        $this->soap = new \SoapClient(WSDL, array('trace' => 1));
        $credentials = new Credentials('BRUGERNAVN', 'PASSWORD', 'SKOLEKODE');
        $client = new Client($credentials, $this->soap);

        $repository = new RegistrationRepository($client);
        $repository->addRegistrations($this->registrations)->getBody();
    }

    /**
     * @group IntegrationTest
     */
    public function testSoapAddNewRegistrationInteractingWithOnlineWebservice()
    {
        $this->soap = new \SoapClient(WSDL, array('trace' => 1));
        $credentials = new Credentials(USERNAME, PASSWORD, SKOLEKODE);
        $client = new Client($credentials, $this->soap);

        $repository = new RegistrationRepository($client);

        $this->assertEquals(count($this->registrations), $repository->addRegistrations($this->registrations)->getCount());
    }

    /**
     * @group IntegrationTest
     */
    public function testSoapGetNewRegistrationsInteractingWithOnlineWebservice()
    {
        $this->soap = new \SoapClient(WSDL, array('trace' => 1));
        $credentials = new Credentials(USERNAME, PASSWORD, SKOLEKODE);
        $client = new Client($credentials, $this->soap);

        $repository = new RegistrationRepository($client);

        try {
            $response = $repository->getNewRegistrations()->getBody();
            $this->assertTrue(is_string($response));
        } catch (Exception $e) {
            print $this->soap->__getLastRequest();
            print $this->soap->__getLastResponse();
        }
    }

    /**
     * It is not possible to delete using the webservice.
     * Just testing whether we get expected response.
     *
     * @group IntegrationTest
     * @expectedException Exception
     * @expectedExceptionMessage It is not possible to delete registrations using the SOAP webservice
     */
    public function testSoapDeleteRegistrationsInteractingWithOnlineWebservice()
    {
        $this->soap = new \SoapClient(WSDL, array('trace' => 1));
        $credentials = new Credentials(USERNAME, PASSWORD, SKOLEKODE);
        $client = new Client($credentials, $this->soap);

        $repository = new RegistrationRepository($client);

        $weblist_id = 11111;
        $response = $repository->delete($weblist_id)->getBody();
    }
}
