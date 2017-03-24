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
     * @param ClientInterface $client Client object
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Adds registration
     *
     * @param array $registrations Array with several registrations
     *
     * @return ResponseInterface
     */
    public function addRegistrations(array $registrations)
    {
        return $this->client->createNewRegistrations($registrations);
    }

    /**
     * Gets new registrations
     *
     * @return ResponseInterface
     */
    public function getNewRegistrations()
    {
        return $this->client->getNewRegistrations();
    }

    /**
     * Gets handled registrations
     *
     * @return ResponseInterface
     */
    public function getHandledRegistrations()
    {
        return $this->client->getHandledRegistrations();
    }

    /**
     * Deletes registration
     *
     * @throws Exception
     * @return void
     */
    public function delete()
    {
        throw new \Exception('It is not possible to delete registrations using the SOAP webservice');
    }
}
