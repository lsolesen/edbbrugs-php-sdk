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

use EDBBrugs\Response;

/**
 * Service Communicator with EDB-Brugs
 *
 * @category EDBBrugs
 * @package  EDBBrugs
 * @author   Lars Olesen <lars@intraface.dk>
 * @license  MIT Open Source License https://opensource.org/licenses/MIT
 * @version  GIT: <git_id>
 */
class NewRegistrationsResponse implements ResponseInterface
{
    protected $response;

    /**
     * Constructor
     *
     * @param object $response Actual response from SOAP
     */
    public function __construct($response)
    {
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
        if (!$this->isOk()) {
            throw new \Exception($this->response->NyTilmelding2Result);
        }
        return $this->response->NyTilmelding2Result;
    }

    /**
     * Checks whether the communication is OK
     *
     * @return boolean
     */
    public function isOk()
    {
        $string = 'Oprettelse Ok, nye tilmeldinger';
        $result = strpos($this->response->NyTilmelding2Result, $string);
        return ($result !== false);

    }
}
