<?php
namespace EDBBrugs\Test;

use EDBBrugs\Utility;

class UtilityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getMunicipalityCode()
     */
    public function testGetMunicipalityCode()
    {
        $utility = new Utility();
        $this->expectEquals(100, $utility->getCountryCode("Danmark"));
    }
}
