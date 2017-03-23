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

use EDBBrugs\ResponseInterface;

/**
 * Service Communicator with EDB-Brugs
 *
 * @category EDBBrugs
 * @package  EDBBrugs
 * @author   Lars Olesen <lars@intraface.dk>
 * @license  MIT Open Source License https://opensource.org/licenses/MIT
 * @version  GIT: <git_id>
 */
class Response implements ResponseInterface
{
    protected $responseType;
    protected $response;

    /**
     * Constructor
     *
     * @param object $responseType Response type
     * @param object $response     Actual response from SOAP
     */
    public function __construct($responseType, $response)
    {
        $this->responseType = $responseType;
        $this->response = $response;
    }

    public function getCount()
    {
        return str_replace(
            'Oprettelse Ok, nye tilmeldinger: ',
            '',
            $this->response->NyTilmelding2Result
        );
    }

    /**
     * Add new registration to EDBBrugs
     *
     * @return mixed (number of successful registrations) or throws Exception
     */
    public function getBody()
    {
        switch ($this->responseType) {
            case 'NyTilmelding2':
                if (!$this->isOk()) {
                    throw new \Exception($this->response->NyTilmelding2Result);
                }
                return str_replace(
                    'Oprettelse Ok, nye tilmeldinger: ',
                    '',
                    $this->response->NyTilmelding2Result
                );
            break;
            case 'SletTilmeldingerV2':
                if (!$this->isOk()) {
                    throw new \Exception($this->response->SletTilmeldingerV2Result);
                }
                return str_replace(
                    'Oprettelse Ok, nye tilmeldinger: ',
                    '',
                    $this->response->SletTilmeldingerV2Response
                );
            break;
            case 'HentNyeTilmeldingerV2':
                return $this->response->HentNyeTilmeldingerV2Result;
            break;
            default:
                throw new \Exception($this->responseType . ' is not a valid response type');
        }
    }

    /**
     * Checks whether the communication is OK
     *
     * @return boolean
     */
    public function isOk()
    {
        switch ($this->responseType) {
            case 'NyTilmelding2':
                $string = 'Oprettelse Ok, nye tilmeldinger';
                $result = strpos($this->response->NyTilmelding2Result, $string);
                return ($result !== false);
            case 'SletTilmeldingerV2':
                $string = 'Oprettelse Ok, nye tilmeldinger';
                $result = strpos($this->response->SletTilmeldingerV2Result, $string);
                return ($result !== false);
            case 'HentNyeTilmeldingerV2':
                $string = 'Oprettelse Ok, nye tilmeldinger';
                $result = strpos($this->response->HentNyeTilmeldingerV2Result, $string);
                return ($result !== false);
            default:
                throw new \Exception('Not a valid response type');
        }
    }
}
