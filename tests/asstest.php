<?php
require_once("../lib/AssFile.php");

class AssTest extends PHPUnit_Framework_TestCase {
    public function testValidation(){
        try{
            $ass1 =  \LibPHPAss\AssFile::loadFromFile("validsimple.ass");
            return $ass1;
        } catch (Exception $e) {
            $this->fail("An exception was thrown");
        }
        return null;
    }

    /**
     * @depends testValidation
     */
    public function testHeader(\LibPHPAss\AssFile $ass){
        $expectedheader = array(
            "Title" => "test file 1",
            "Original Script" => "Naouak",
            "Original Editing" => "Naouak",
            "ScriptType" => "v4.00+",
            "Collisions" => "Normal",
            "PlayResX" => "640",
            "PlayResY" => "480",
            "PlayDepth" => "0",
            "Timer" => "100.0000",
            "WrapStyle" => "0"
        );

        $header = $ass->getHeaderInfo();

        $this->assertInternalType("array",$header,"Not an array returned by header");

        if(sizeof($expectedheader) != sizeof($header)){
            $this->fail("Not equals amount of headers ".sizeof($header)." found, ".sizeof($expectedheader)." expected");
        }

        foreach ($expectedheader as $expectedKey => $expectedValue) {
            $this->assertTrue(isset($header[$expectedKey]));
            $this->assertEquals($expectedValue,$header[$expectedKey]);
        }

    }
}