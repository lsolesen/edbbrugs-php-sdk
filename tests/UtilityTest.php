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
        $this->assertEquals('Danmark', $utility->getCountryCode("Danmark"));
        $this->assertEquals('Danmark', $utility->getCountryCode("Denmark"));
        $this->assertEquals('Danmark', $utility->getCountryCode("DK"));
        $this->assertEquals('Island', $utility->getCountryCode("Island"));
    }

    /**
     * Test getMunicipalityCode()
     */
    public function testGetCountryCodeThatIsNotFound()
    {
        $utility = new Utility();
        $this->assertEquals(0, $utility->getCountryCode("Unknown"));
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
        $this->assertEquals(0, $utility->getMunicipalityCode("NotFound"));
    }
}
