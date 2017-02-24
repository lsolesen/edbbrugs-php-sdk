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
 * Generate the XML
 *
 * @category EDBBrugs
 * @package  EDBBrugs
 * @author   Lars Olesen <lars@intraface.dk>
 * @license  MIT Open Source License https://opensource.org/licenses/MIT
 * @version  GIT: <git_id>
 */
class Registration
{
    protected $username;
    protected $password;
    protected $school_code;
    protected $registrations = array();

    /**
     * Constructor
     *
     * @param string $username    Username provided by EDBBrugs
     * @param string $password    Password provided by EDBBrugs
     * @param string $school_code School code provided by EDBBrugs
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
        $xml = new \SimpleXMLElement('<Tilmeldinger/>');
        $user = $xml->addChild('User');
        $user->addChild('Username', $this->username);
        $user->addChild('Passw', $this->password);
        $user->addChild('Skolekode', $this->school_code);
        foreach ($this->registrations as $r) {
            $registration = $xml->addChild('Tilmelding');
            foreach ($r as $k => $v) {
                $registration->addChild($k, $v);
            }
        }

        return $xml->asXml();
    }
}
