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
class Request
{
    protected $username;
    protected $password;
    protected $school_code;
    protected $xml;

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

        $this->populateRequest();
    }

    /**
     * Populate the XML request
     *
     * @return void
     */
    protected function populateRequest()
    {
        $this->xml = new \SimpleXMLElement('<Tilmeldinger/>');
        $user = $this->xml->addChild('User');
        $user->addChild('Username', $this->username);
        $user->addChild('Passw', $this->password);
        $user->addChild('Skolekode', $this->school_code);
    }

    /**
     * Returns the XML request
     *
     * @return string
     */
    public function getRequest()
    {
        return $this->xml;
    }
}
