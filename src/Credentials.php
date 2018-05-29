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

use EDBBrugs\CredentialsInterface;

class Credentials implements CredentialsInterface
{
    protected $username;
    protected $password;
    protected $school_code;

    public function __construct($username, $password, $school_code)
    {
        $this->username = $username;
        $this->password = $password;
        $this->school_code = $school_code;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSchoolCode()
    {
        return $this->school_code;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getArray()
    {
        return array(
            'Brugernavn' => $this->getUsername(),
            'SystemKode' => $this->getPassword(),
            'Skolekode' => $this->getSchoolCode());
    }
}
