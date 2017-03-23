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
     * @param array $registrations
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
                $reg->addChild($key, $value);
            }
        }

        $params = array();
        $params['XmlData'] = new \SoapVar($xml->asXml(), XSD_STRING);

        return new NewRegistrationsResponse($this->soap->NyTilmelding2($params));
    }

    public function getNewRegistrations()
    {
        $params = $this->credentials->getArray();
        return new Response('HentNyeTilmeldingerV2', $this->soap->HentNyeTilmeldingerV2($params));
    }

    public function getHandledRegistrations()
    {
        $params = $this->credentials->getArray();
        return new Response('HentBehandledeTilmeldingerV2', $this->soap->HentBehandledeTilmeldingerV2($params));
    }

    /**
     * @param string $weblist_id
     */
    public function deleteRegistrations($weblist_id)
    {
        $params = $this->credentials->getArray();
        $params['Id_WebListe'] = '72200' . '_' . 'T3';
        return new Response('SletTilmeldingerV2', $this->soap->SletTilmeldingerV2($params));
    }
}
