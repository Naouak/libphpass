<?php
require_once("../lib/AssFile.php");

class AssTest extends PHPUnit_Framework_TestCase {
    public function testValidation(){
        try{
            $ass1 =  \LibPHPAss\AssFile::loadFromFile("test1.ass");
            return;
        } catch (Exception $e) {
            $this->fail("An exception was thrown");
        }
    }
}