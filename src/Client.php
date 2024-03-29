<?php
/**
 * SDK to communicate with EDBBrugs
 *
 * PHP Version 5
 *
 * @category EDBBrugs
 * @package  EDBBrugs
 * @author   Lars Olesen <lars@intraface.dk>
 * @license  MIT Open Source License https://opensource.org/licenses/MIT
 * @version  GIT: <git_id>
 */

namespace EDBBrugs;

use EDBBrugs\ClientInterface;
use EDBBrugs\UtilityInterface;

/**
 * Service Communicator with EDB-Brugs
 *
 * @category EDBBrugs
 * @package  EDBBrugs
 * @author   Lars Olesen <lars@intraface.dk>
 * @license  MIT Open Source License https://opensource.org/licenses/MIT
 * @version  GIT: <git_id>
 */
class Client implements ClientInterface
{
    protected $soap;
    protected $credentials;

    /**
     * Constructor
     *
     * @param object $soap Soap Client
     */
    public function __construct(CredentialsInterface $credentials, $soap)
    {
        $this->credentials = $credentials;
        $this->soap = $soap;
    }

    /**
     * Gets the utility class for
     */
    protected function getUtilityClass()
    {
        return new Utility;
    }

    /**
     * Create new registrations from array
     *
     * @param array $registrations
     *
     * @return RegistrationsCreateResponse
     */
    public function createNewRegistrations(array $registrations)
    {
        $xml = new \SimpleXMLElement('<Tilmeldinger/>');
        $user = $xml->addChild('User');
        $user->addChild('Username', $this->credentials->getUsername());
        $user->addChild('Passw', $this->credentials->getPassword());
        $user->addChild('Skolekode', $this->credentials->getSchoolCode());
        foreach ($registrations as $registration) {
            $reg = $xml->addChild('Tilmelding');
            foreach ($registration as $key => $value) {
                foreach (array('.Fastnet', '.Mobil', '.ArbejdeTlf') as $variable) {
                    if (strpos($key, $variable) !== false) {
                        $value = $this->getUtilityClass()->fixPhoneNumber($value);
                    }
                }
                foreach (array('Kursus', '.Email') as $variable) {
                    if (strpos($key, $variable) !== false) {
                        $value = substr($value, 0, 50);
                    }
                }
                foreach (array('.Fornavn', '.Efternavn', '.Adresse', '.Lokalby', '.Bynavn') as $variable) {
                    if (strpos($key, $variable) !== false) {
                        $value = substr($value, 0, 35);
                    }
                }
                foreach (array('.Linje') as $variable) {
                    if (strpos($key, $variable) !== false) {
                        $value = substr($value, 0, 30);
                    }
                }
                foreach (array('.CprNr') as $variable) {
                    if (strpos($key, $variable) !== false) {
                        $value = substr($value, 0, 20);
                    }
                }
                foreach (array('.Postnr', '.Fastnet', '.Mobil', 'ArbejdeTlf') as $variable) {
                    if (strpos($key, $variable) !== false) {
                        $value = substr($value, 0, 15);
                    }
                }
                if (strpos($key, '.Land') !== false) {
                    $value = $this->getUtilityClass()->getCountryCode($value);
                }
                if (strpos($key, '.Kommune') !== false) {
                    $value = $this->getUtilityClass()->getMunicipalityCode($value);
                }
                foreach (array('.TidlSkole') as $variable) {
                    if (strpos($key, $variable) !== false) {
                        $value = substr($value, 0, 48);
                    }
                }
                foreach (array('.Klasse') as $variable) {
                    if (strpos($key, $variable) !== false) {
                        $value = substr($value, 0, 2);
                        $value = $this->getUtilityClass()->fixClass($value);
                    }
                }
                $reg->addChild($key, htmlspecialchars($value));
            }
        }

        $params = array();
        $params['XmlData'] = new \SoapVar($xml->asXml(), XSD_STRING);

        return new RegistrationsCreateResponse($this->soap->NyTilmelding2($params));
    }

    /**
     * Gets new registrations
     *
     * @return Response
     */
    public function getNewRegistrations()
    {
        $params = $this->credentials->getArray();
        return new Response($this->soap->HentNyeTilmeldingerV2($params));
    }

    /**
     * Gets handled registrations
     *
     * @return Response
     */
    public function getHandledRegistrations()
    {
        $params = $this->credentials->getArray();
        return new Response($this->soap->HentBehandledeTilmeldingerV2($params));
    }

    /**
     * Delete registrations
     *
     * @throws \Exception
     * @return void
     */
    public function deleteRegistrations()
    {
        throw new \Exception('It is not possible to delete registrations using the SOAP webservice');
    }
}
