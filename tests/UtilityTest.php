<?php
namespace EDBBrugs\Test;

use EDBBrugs\Utility;

class UtilityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getMunicipalityCode()
     */
    public function testGetCountryCodeThatIsFound()
    {
        $utility = new Utility();
        $this->assertEquals(100, $utility->getCountryCode("Danmark"));
        $this->assertEquals(100, $utility->getCountryCode("DK"));
    }

    /**
     * Test getMunicipalityCode()
     */
    public function testGetCountryCodeThatIsNotFound()
    {
        $utility = new Utility();
        $this->assertEquals(null, $utility->getCountryCode("Unknown"));
    }

    /**
     * Test getMunicipalityCode()
     */
    public function testGetMunicipalityCodeThatIsFound()
    {
        $utility = new Utility();
        $this->assertEquals(630, $utility->getMunicipalityCode("Vejle"));
        $this->assertEquals(813, $utility->getMunicipalityCode("Frederikshavn"));
    }

    /**
     * Test getMunicipalityCode()
     */
    public function testGetMunicipalityCodeThatIsNotFound()
    {
        $utility = new Utility();
        $this->assertEquals(null, $utility->getMunicipalityCode("NotFound"));
    }
}
