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
                'Elev.Fornavn' => 'Test Webtilmelding',
                'Elev.Efternavn' => 'Vinterkursus',
                'Elev.Adresse' => 'Ørnebjergvej 28',
                'Elev.Lokalby' => 'Grejs',
                'Elev.Kommune' => 'Vejle',
                'Elev.Postnr' => '7100',
                'Elev.Bynavn' => 'Vejle',
                'Elev.CprNr' => '0101421942',
                'Elev.Fastnet' => '+46 70 716 31 39',
                'Elev.FastnetBeskyttet' => 0, // 0 = No, 1 = Yes
                'Elev.Mobil' => '75820811',
                'Elev.MobilBeskyttet' => 0, // 0 = No, 1 = Yes
                'Elev.Email' => 'kontor@vih.dk',
                'Elev.TidlSkole' => 'Vejle Idrætshøjskole Ørnebjergvej 28 7100 Vejle Den Jyske Idrætsskole',
                'Elev.Land' => 'Danmark',
                'EgneFelter.EgetFelt1' => '[Fri132]Har ungdomsuddannelse',
                'EgneFelter.EgetFelt7' => '[Fri082]12.12.2018',
                'Elev.Notat' => 'Svend Aage Thomsen er skolens grundlægger',
                'Far.Fornavn' => 'Svend Aage',
                'Far.Efternavn' => 'Thomsen',
                'Far.Adresse' => 'Ørnebjergvej 28',
                'Far.Lokalby' => 'Grejs',
                'Far.Postnr' => '7100',
                'Far.Bynavn' => 'Vejle',
                'Far.Fastnet' => '75820811',
                // 'Far.FastnetBeskyttet' => 0, // 0 = No, 1 = Yes
                'Far.Mobil' => '75820811',
                //'Far.MobilBeskyttet' => 0, // 0 = No, 1 = Yes
                'Far.Email' => 'kontor@vih.dk',
                'Far.Land' => 'DK',
                'Mor.Fornavn' => 'Svend Aage',
                'Mor.Efternavn' => 'Thomsen',
                'Mor.Adresse' => 'Ørnebjergvej 28',
                'Mor.Lokalby' => 'Grejs',
                'Mor.Postnr' => '7100',
                'Mor.Bynavn' => 'Vejle',
                'Mor.Fastnet' => '75820811',
                //'Mor.FastnetBeskyttet' => 0, // 0 = No, 1 = Yes
                'Mor.Mobil' => '75820811',
                //'Mor.MobilBeskyttet' => 0, // 0 = No, 1 = Yes
                'Mor.Email' => 'kontor@vih.dk',
                'Mor.Land' => 'DK',
                'Voksen.Fornavn' => 'Svend Aage',
                'Voksen.Efternavn' => 'Thomsen',
                'Voksen.Adresse' => 'Ørnebjergvej 28',
                'Voksen.Lokalby' => 'Grejs',
                'Voksen.Postnr' => '7100',
                'Voksen.Bynavn' => 'Vejle',
                'Voksen.Fastnet' => '60652045/30374685',
                'Voksen.FastnetBeskyttet' => 0, // 0 = No, 1 = Yes
                'Voksen.Mobil' => '60652045/30374685',
                'Voksen.MobilBeskyttet' => 0, // 0 = No, 1 = Yes
                'Voksen.Email' => 'kontor@vih.dk',
                'Voksen.Land' => 'Danmark',
            ),
            array(
                'Kartotek' => 'T3',
                'Kursus' => 'Forårskursus 2019 (24 uger) - KUN ENKELTE PIGEPLADSER TILBAGE',
                // The following can be repeated for Mor, Far, Voksen
                'Elev.Fornavn' => 'Test Webtilmelding',
                'Elev.Efternavn' => 'Forårskursus',
                'Elev.Adresse' => 'Ørnebjergvej 28, Ørnebjergvej 28, Ørnebjergvej 28',
                'Elev.Lokalby' => 'Grejs',
                'Elev.Postnr' => '7100',
                'Elev.Bynavn' => 'Vejle',
                'Elev.Kommune' => 'Vejle',
                'Elev.CprNr' => '0101421942',
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
                'Voksen.Mobil' => '+45 75 82 08 11',
                'Voksen.MobilBeskyttet' => 0, // 0 = No, 1 = Yes
                'Voksen.Email' => 'kontor@vih.dk',
                'Voksen.Land' => 'Denmark',
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
     * @group IntegrationTest
     */
    public function testDateAllCourses()
    {
        $this->soap = new \SoapClient(WSDL, array('trace' => 1));
        $credentials = new Credentials(USERNAME, PASSWORD, SKOLEKODE);
        $client = new Client($credentials, $this->soap);

        $repository = new RegistrationRepository($client);

        $registration = array(
            array(
                'Kartotek' => 'T3',
                'Kursus' => 'Fitness 360, uge 30 2019',
                // The following can be repeated for Mor, Far, Voksen
                'Elev.Fornavn' => 'Test Webtilmelding',
                'Elev.Efternavn' => 'Uddannelsesfelt',
                'Elev.Adresse' => 'Ørnebjergvej 28',
                'Elev.Lokalby' => 'Grejs',
                'Elev.Kommune' => 'Vejle',
                'Elev.Postnr' => '7100',
                'Elev.Bynavn' => 'Vejle',
                'Elev.CprNr' => '0101421942',
                'Elev.Fastnet' => '+46 70 716 31 39',
                'Elev.FastnetBeskyttet' => 0, // 0 = No, 1 = Yes
                'Elev.Mobil' => '75820811',
                'Elev.MobilBeskyttet' => 0, // 0 = No, 1 = Yes
                'Elev.Email' => 'kontor@vih.dk',
                'Elev.Land' => 'Danmark',
                'EgneFelter.EgetFelt7' => '[Fri082]12.12.2018'
            ),
        );
        $this->assertEquals(count($registration), $repository->addRegistrations($registration)->getCount());
    }

    /**
     * @group IntegrationTest
     */
    public function testEducationLongCourses()
    {
        $this->soap = new \SoapClient(WSDL, array('trace' => 1));
        $credentials = new Credentials(USERNAME, PASSWORD, SKOLEKODE);
        $client = new Client($credentials, $this->soap);

        $repository = new RegistrationRepository($client);

        $registration = array(
            array(
                'Kartotek' => 'T3',
                'Kursus' => 'Vinterkursus 18/19',
                // The following can be repeated for Mor, Far, Voksen
                'Elev.Fornavn' => 'Test Webtilmelding',
                'Elev.Efternavn' => 'Uddannelsesfelt',
                'Elev.Adresse' => 'Ørnebjergvej 28',
                'Elev.Lokalby' => 'Grejs',
                'Elev.Kommune' => 'Vejle',
                'Elev.Postnr' => '7100',
                'Elev.Bynavn' => 'Vejle',
                'Elev.CprNr' => '0101421942',
                'Elev.Fastnet' => '+46 70 716 31 39',
                'Elev.FastnetBeskyttet' => 0, // 0 = No, 1 = Yes
                'Elev.Mobil' => '75820811',
                'Elev.MobilBeskyttet' => 0, // 0 = No, 1 = Yes
                'Elev.Email' => 'kontor@vih.dk',
                'Elev.Land' => 'Danmark',
                'EgneFelter.EgetFelt1' => '[Fri132]Har ungdomsuddannelse'
            ),
        );
        $this->assertEquals(count($registration), $repository->addRegistrations($registration)->getCount());
    }

    /**
     * @group IntegrationTest
     */
    public function testConcentGdprMarketingCourses()
    {
        $this->soap = new \SoapClient(WSDL, array('trace' => 1));
        $credentials = new Credentials(USERNAME, PASSWORD, SKOLEKODE);
        $client = new Client($credentials, $this->soap);

        $repository = new RegistrationRepository($client);

        $registration = array(
            array(
                'Kartotek' => 'T3',
                'Kursus' => 'Fitness 360, uge 30 2019',
                // The following can be repeated for Mor, Far, Voksen
                'Elev.Fornavn' => 'Test Webtilmelding',
                'Elev.Efternavn' => 'Samtykke',
                'Elev.Adresse' => 'Ørnebjergvej 28',
                'Elev.Lokalby' => 'Grejs',
                'Elev.Kommune' => 'Vejle',
                'Elev.Postnr' => '7100',
                'Elev.Bynavn' => 'Vejle',
                'Elev.CprNr' => '0101421942',
                'Elev.Fastnet' => '+46 70 716 31 39',
                'Elev.FastnetBeskyttet' => 0, // 0 = No, 1 = Yes
                'Elev.Mobil' => '75820811',
                'Elev.MobilBeskyttet' => 0, // 0 = No, 1 = Yes
                'Elev.Email' => 'kontor@vih.dk',
                'Elev.Land' => 'Danmark',
                'EgneFelter.EgetFelt30' => '[Fri084]Ja',
                'EgneFelter.EgetFelt29' => '[Forening4501]12.12.2018 Web Ja',
                'EgneFelter.EgetFelt28' => '[Forening4502]12.12.2018 Web Ja'
            ),
        );
        $this->assertEquals(count($registration), $repository->addRegistrations($registration)->getCount());
    }

    /**
     * @group IntegrationTest
     */
    public function testBirthdayOnlyCourses()
    {
        $this->soap = new \SoapClient(WSDL, array('trace' => 1));
        $credentials = new Credentials(USERNAME, PASSWORD, SKOLEKODE);
        $client = new Client($credentials, $this->soap);

        $repository = new RegistrationRepository($client);

        $registration = array(
            array(
                'Kartotek' => 'T3',
                'Kursus' => 'Fitness 360, uge 30 2019',
                // The following can be repeated for Mor, Far, Voksen
                'Elev.Fornavn' => 'Test Webtilmelding',
                'Elev.Efternavn' => 'Birthday Only',
                'Elev.Adresse' => 'Ørnebjergvej 28',
                'Elev.Lokalby' => 'Grejs',
                'Elev.Kommune' => 'Vejle',
                'Elev.Postnr' => '7100',
                'Elev.Bynavn' => 'Vejle',
                'Elev.CprNr' => '010142',
                'Elev.Fastnet' => '+46 70 716 31 39',
                'Elev.FastnetBeskyttet' => 0, // 0 = No, 1 = Yes
                'Elev.Mobil' => '75820811',
                'Elev.MobilBeskyttet' => 0, // 0 = No, 1 = Yes
                'Elev.Email' => 'kontor@vih.dk',
                'Elev.Land' => 'Danmark'
            ),
        );
        $this->assertEquals(count($registration), $repository->addRegistrations($registration)->getCount());
    }

    /**
     * @group IntegrationTest
     */
    public function testNameWithAndSign()
    {
        $this->soap = new \SoapClient(WSDL, array('trace' => 1));
        $credentials = new Credentials(USERNAME, PASSWORD, SKOLEKODE);
        $client = new Client($credentials, $this->soap);

        $repository = new RegistrationRepository($client);

        $registration = array(
            array(
                'Kartotek' => 'T3',
                'Kursus' => 'Body & Mind, uge 30 2019',
                // The following can be repeated for Mor, Far, Voksen
                'Elev.Fornavn' => 'Test Webtilmelding And Sign',
                'Elev.Efternavn' => 'And Sign',
                'Elev.Adresse' => 'Ørnebjergvej 28',
                'Elev.Lokalby' => 'Grejs',
                'Elev.Kommune' => 'Vejle',
                'Elev.Postnr' => '7100',
                'Elev.Bynavn' => 'Vejle',
                'Elev.CprNr' => '010142',
                'Elev.Fastnet' => '+46 70 716 31 39',
                'Elev.FastnetBeskyttet' => 0, // 0 = No, 1 = Yes
                'Elev.Mobil' => '75820811',
                'Elev.MobilBeskyttet' => 0, // 0 = No, 1 = Yes
                'Elev.Email' => 'kontor@vih.dk',
                'Elev.Land' => 'Danmark'
            ),
        );
        $this->assertEquals(count($registration), $repository->addRegistrations($registration)->getCount());
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
