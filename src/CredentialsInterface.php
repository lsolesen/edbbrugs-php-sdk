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

interface CredentialsInterface
{
    public function __construct($username, $password, $school_code);

    public function getUsername();

    public function getSchoolCode();

    public function getPassword();

    public function getArray();
}
