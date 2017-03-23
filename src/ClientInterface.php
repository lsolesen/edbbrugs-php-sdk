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

interface ClientInterface
{
    public function getNewRegistrations();

    public function getHandledRegistrations();

    public function createNewRegistrations(array $registrations);

    public function deleteRegistrations($weblist_id);
}
