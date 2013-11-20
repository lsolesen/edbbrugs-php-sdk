<?php
/**
 * SDK to communicate with EDBBrugsen
 *
 * @link http://edb-brugs.dk
 */
 
/**
 * Generate the XML
 *
 * @author Lars Olesen <lars@intraface.dk>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License (GPL)
 * @package EDBBrugs
 */
class EDBBrugsen_Registration
{
    protected $username;
    protected $password;
    protected $school_code;
    protected $registrations = array();

    public function __construct($username, $password, $school_code)
    {
        $this->username    = $username;
        $this->password    = $password;
        $this->school_code = $school_code;
    }

    public function addRegistration(array $registration)
    {
        $this->registrations[] = $registration;
    }
    
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
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License (GPL)
 * @package EDBBrugs
 */
class EDBBrugsen_Service
{
    protected $soap;
    protected $response;
    
    function __construct($soap)
    {
        $this->soap = $soap;
    }
    
    function addNewRegistration(EDBBrugsen_Registration $request)
    {
        $request->getRequest();
        $this->response = $this->soap->NyTilmelding2(array('XmlData' => new SoapVar($request->getRequest(), XSD_STRING)));
        if (!$this->isOk()) {
            throw new Exception($this->response->NyTilmelding2Result);
        }
        return $no_of_new_registrations = str_replace('Oprettelse Ok, nye tilmeldinger: ', '', $this->response->NyTilmelding2Result);
    }
    
    function isOk()
    {
        return (strpos($this->response->NyTilmelding2Result, 'Oprettelse Ok, nye tilmeldinger') !== false);
    }
}
