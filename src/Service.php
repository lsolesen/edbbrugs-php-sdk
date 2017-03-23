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

/**
 * Service Communicator with EDB-Brugs
 *
 * @category EDBBrugs
 * @package  EDBBrugs
 * @author   Lars Olesen <lars@intraface.dk>
 * @license  MIT Open Source License https://opensource.org/licenses/MIT
 * @version  GIT: <git_id>
 */
class Service
{
    protected $soap;
    protected $response;

    /**
     * Constructor
     *
     * @param object $soap Soap Client
     */
    public function __construct($soap)
    {
        $this->soap = $soap;
    }

    /**
     * Add new registration to EDBBrugs
     *
     * @param object $request The XML request to use when adding a new registration
     *
     * @return mixed (number of successful registrations) or throws Exception
     */
    public function addNewRegistration(Request $request)
    {
        $this->response = $this->soap->NyTilmelding2(
            array(
                'XmlData' => new \SoapVar($request->getRequest()->asXml(), XSD_STRING)
            )
        );
        if (!$this->isOk()) {
            throw new \Exception($this->response->NyTilmelding2Result);
        }
        return str_replace(
            'Oprettelse Ok, nye tilmeldinger: ',
            '',
            $this->response->NyTilmelding2Result
        );
    }

    /**
     * Checks whether the communication is OK
     *
     * @return boolean
     */
    protected function isOk()
    {
        $string = 'Oprettelse Ok, nye tilmeldinger';
        $result = strpos($this->response->NyTilmelding2Result, $string);
        return ($result !== false);
    }
}
