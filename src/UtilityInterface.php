<?php
/**
 * Utility to get country and municipality codes with EDB-Brugs
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
 * Utility to get country and municipality codes with EDB-Brugs
 *
 * @category EDBBrugs
 * @package  EDBBrugs
 * @author   Lars Olesen <lars@intraface.dk>
 * @license  MIT Open Source License https://opensource.org/licenses/MIT
 * @version  GIT: <git_id>
 */
interface UtilityInterface
{
    /**
     * Gets country code
     */
    public function getCountryCode($country);

    /**
     * Gets municipality code
     */
    public function getMunicipalityCode($municipality);
}
