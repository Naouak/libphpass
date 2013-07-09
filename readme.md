LibPHPAss
=========

A library to help doing stuff with ass subtitles in PHP.

Usage
-----

    <?php
    require_once("lib/AssFile.php");

    // Loading from a file
    $assFile = \LibPHPAss\AssFile::loadFromFile("myfile.ass");
    //Or loading from a string
    $assFile = \LibPHPAss\AssFile::loadFromString("Ass string ...");

If your Ass file is not correct, it will throw an exception with a dezscription of the problem in the message.