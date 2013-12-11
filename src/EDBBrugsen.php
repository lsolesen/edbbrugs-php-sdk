<?php
/**
 * SDK to communicate with EDBBrugsen
 *
 * PHP Version 5
 *
 * @package EDBBrugs
 * @author Lars Olesen <lars@intraface.dk>
 * @link http://edb-brugs.dk
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License (GPL)
 */
 
/**
 * Generate the XML
 *
 * @author Lars Olesen <lars@intraface.dk>
 */
class EDBBrugsen_Registration
{
    protected $username;
    protected $password;
    protected $school_code;
    protected $registrations = array();

    /**
     * Constructor
     * 
     * @param string $username
     * @param string $password
     * @param string $school_code
     *
     * @return void
     */
    public function __construct($username, $password, $school_code)
    {
        $this->username    = $username;
        $this->password    = $password;
        $this->school_code = $school_code;
    }

    /**
     * Adds registration
     * 
     * @param array $registrations Array with registrations
     *
     * @return void
     */
    public function addRegistration(array $registrations)
    {
        $this->registrations[] = $registrations;
    }

    /**
     * Returns the XML request
     *
     * @return string
     */
    public function getRequest()
    {
        $xml = new SimpleXMLElement('<Tilmeldinger/>');
        $user = $xml->addChild('User');
        $user->addChild('Username', $this->username);
        $user->addChild('Skolekode', $this->school_code);
        $user->addChild('Passw', $this->password);
        foreach ($this->registrations as $r) {
            $registration = $xml->addChild('Tilmelding');
            foreach ($r as $k => $v) {
                $registration->addChild($k, $v);
            }
        }
        
        return $xml->asXml();
    }
}

/**
 * Service Communicator with EDB-Brugs
 * 
 * @author Lars Olesen <lars@intraface.dk>
 */
class EDBBrugsen_Service
{
    protected $soap;
    protected $response;
    
    /**
     * Constructor
     * 
     * @param object $soap Soap Client
     *
     * @return void
     */
    public function __construct($soap)
    {
        $this->soap = $soap;
    }
    
    /**
     * Add new registration to EDBBrugsen
     * 
     * @param object $request The XML request to use when adding a new registration
     *
     * @return integer (successful registrations) or throws Exception
     */    
    public function addNewRegistration(EDBBrugsen_Registration $request)
    {
        $request->getRequest();
        $this->response = $this->soap->NyTilmelding2(array('XmlData' => new SoapVar($request->getRequest(), XSD_STRING)));
        if (!$this->isOk()) {
            throw new Exception($this->response->NyTilmelding2Result);
        }
        return $no_of_new_registrations = str_replace('Oprettelse Ok, nye tilmeldinger: ', '', $this->response->NyTilmelding2Result);
    }
    
    /**
     * Checks whether the communication is OK
     *
     * @return boolean
     */
    protected function isOk()
    {
        return (strpos($this->response->NyTilmelding2Result, 'Oprettelse Ok, nye tilmeldinger') !== false);
    }
}
