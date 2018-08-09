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

use EDBBrugs\UtilityInterface;

/**
 * Utility to get country and municipality codes with EDB-Brugs
 *
 * @category EDBBrugs
 * @package  EDBBrugs
 * @author   Lars Olesen <lars@intraface.dk>
 * @license  MIT Open Source License https://opensource.org/licenses/MIT
 * @version  GIT: <git_id>
 */
class Utility implements UtilityInterface
{
    /**
     * Gets country code
     *
     * $return integer
     */
    public function getCountryCode($country)
    {
        $rows = $this->getCSV(DIRNAME(__FILE__) . "/data/Landekoder.csv", $country);
        $stored_in_column = 3;
        return $rows[$stored_in_column];
    }

    /**
     * Gets municipality code
     *
     * $return integer
     */
    public function getMunicipalityCode($municipality)
    {
        $rows = $this->getCSV(DIRNAME(__FILE__) . "/data/Kommuner.csv", $municipality);
        $stored_in_column = 2;
        return $rows[$stored_in_column];
    }

    /**
     * Loops through csv file and stops with search
     *
     * @param $file_name
     * @param $search
     * @return array
     */
    protected function getCSV($file_name, $search)
    {
        $ch = fopen($file_name, "r");
        $header_row = fgetcsv($ch);

        // This will loop through all the rows until it reaches the end
        while ($row = fgetcsv($ch, 0, ";")) {
            if ($this->substrInArray($search, $row)) {
                return $row;
            }
        }
        return 0;
    }

    /**
     * A version of in_array() that does a sub string match on $needle
     *
     * @param  mixed   $needle    The searched value
     * @param  array   $haystack  The array to search in
     * @return boolean
     */
    private function substrInArray($needle, array $haystack)
    {
        $filtered = array_filter($haystack, function ($item) use ($needle) {
            return false !== strpos($item, $needle);
        });

        return !empty($filtered);
    }
}
