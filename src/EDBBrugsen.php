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
class EDBBrugsen
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
        $output = array();
        $output[] = '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>';
        $output[] = '<Tilmeldinger xmlns="http://edb-brugsen/tilmelding">';
        $output[] = '  <User>';
        $output[] = '    <Username>' . $this->username . '</Username>';
        $output[] = '    <Passw>' . $this->password . '</Passw>';
        $output[] = '    <Skolekode>' . $this->school_code . '</Skolekode>';
        $output[] = '  </User>';
        foreach ($this->registrations as $registration) {
            $output[] = '  <Tilmelding>';
            $output[] = '    <Fornavne>' . $registration['fornavne'] . '</Fornavne>';
            $output[] = '    <Memo>' . $registration['memo'] . '</Memo>';
            $output[] = '    <Kursus>' . $registration['kursus'] . '</Kursus>';
            $output[] = '  </Tilmelding>';
        }
        $output[] = '</Tilmeldinger>';
        return implode($output, "\n");
    }

    public function send()
    {
        return $this->getRequest();
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
    protected $response;
    protected $endpoint;
    
    function __construct($endpoint)
    {
        $this->endpoint = $endpoint;
    }
    
    function send(EDBBrugsen $request)
    {
        $this->response = $request->getRequest();
    }
    
    function isOk()
    {
        print $this->response;
    }
}
