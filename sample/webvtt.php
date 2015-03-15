<?php
require_once("../lib/AssFile.php");
require_once("../lib/WebVTT.php");

$assFile = \LibPHPAss\AssFile::loadFromFile("sample.ass");
$webvtt = new \LibPHPAss\WebVTT($assFile->getEvents());

echo $webvtt->toString();