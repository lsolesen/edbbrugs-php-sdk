<?php
require_once dirname(__FILE__) . '/../src/EDBBrugsen.php';

class EDBBrugsenSendTest extends PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->sender = new EDBBrugsen_Service('http://endpoint.dk');
    }
    
    function testSend()
    {
        $this->sender->send(new EDBBrugsen('brugernavn', 'adgangskode', '999999'));
    }
}
