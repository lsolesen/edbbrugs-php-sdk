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
    protected $response;

    /**
     * Constructor
     *
     * @param object $response     Actual response from SOAP
     */
    public function __construct($response)
    {
        $this->response = $response;
    }

    /**
     * Gets the body from the response
     *
     * @return mixed Soap result or throws Exception
     */
    public function getBody()
    {
        if (!empty($this->response->HentBehandledeTilmeldingerV2Result)) {
            return $this->response->HentBehandledeTilmeldingerV2Result;
        } elseif (!empty($this->response->SletTilmeldingerV2Result)) {
            if ($this->response->SletTilmeldingerV2Result) {
                throw new \Exception($this->response->SletTilmeldingerV2Result);
            }
            return $this->response->SletTilmeldingerV2Result;
        } elseif (!empty($this->response->HentNyeTilmeldingerV2Result)) {
            return $this->response->HentNyeTilmeldingerV2Result;
        } elseif (!empty($this->response->NyTilmelding2Result)) {
            return $this->response->NyTilmelding2Result;
        }

        throw new \Exception('Not a known response type - You should write a Response class');
    }

    /**
     * Checks whether the communication is OK
     *
     * @return boolean
     */
    public function isOk()
    {
        return true;
    }

    /**
     * Count how many results are being returned
     *
     * @return int
     */
    public function getCount()
    {
        return 1;
    }
}
