<?php
require_once dirname(__FILE__) . '/../src/EDBBrugsen.php';

class EDBBrugsenTest extends PHPUnit_Framework_TestCase 
{
    protected $brugsen;
    
    function setUp()
    {
        $this->brugsen = new EDBBrugsen('brugernavn', 'adgangskode', 999999);
    }
    
    function tearDown()
    {
        unset($this->brugsen);
    }

    function testConstructor()
    {
        $this->assertTrue(is_object($this->brugsen));
    }
    
    function testGetRequest()
    {
        $expected = '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
<Tilmeldinger xmlns="http://edb-brugsen/tilmelding">
  <User>
    <Username>brugernavn</Username>
    <Passw>adgangskode</Passw>
    <Skolekode>999999</Skolekode>
  </User>
</Tilmeldinger>';
        $this->assertEquals($this->brugsen->getRequest(), $expected);
    }
    
    function testGetRequestWithRegistrations()
    {
        $expected = '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
<Tilmeldinger xmlns="http://edb-brugsen/tilmelding">
  <User>
    <Username>brugernavn</Username>
    <Passw>adgangskode</Passw>
    <Skolekode>999999</Skolekode>
  </User>
  <Tilmelding>
    <Fornavne>Hans</Fornavne>
    <Memo>Eksempel på få data</Memo>
    <Kursus>Golf</Kursus>
  </Tilmelding>
</Tilmeldinger>';
        $registration = array(
            'fornavne' => 'Hans',
            'memo'     => 'Eksempel på få data',
            'kursus'   => 'Golf', 
        );
        $this->brugsen->addRegistration($registration);
        $this->assertEquals($this->brugsen->getRequest(), $expected);
    }
}

