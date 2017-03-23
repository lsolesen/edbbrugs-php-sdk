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
class RegistrationRepository
{
    /**
     * Constructor
     *
     * @param string $request  Request object
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Adds registration
     *
     * @param array $registration Array with registration options
     *
     * @return Response
     */
    public function addRegistrations(array $registrations)
    {
        return $this->client->createNewRegistrations($registrations);
    }

    /**
     * Gets new registrations
     *
     * @return Response
     */
    public function getNewRegistrations()
    {
        return $this->client->getNewRegistrations();
    }

    /**
     * Gets handled registrations
     *
     * @return Response
     */
    public function getHandledRegistrations()
    {
        return $this->client->getHandledRegistrations();
    }

    /**
     * Deletes registrations
     *
     * @param string $weblist_id String with the id
     *
     * @return Response
     */
    public function delete($weblist_id)
    {
        return $this->client->deleteRegistrations($weblist_id);
    }
}
