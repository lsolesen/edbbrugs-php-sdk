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
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Adds registration
     *
     * @param array $registration Array with registration options
     *
     * @return void
     */
    public function addRegistration(array $registration)
    {
        $reg = $this->request->getRequest()->addChild('Tilmelding');
        foreach ($registration as $key => $value) {
            $reg->addChild($key, $value);
        }
    }
}
