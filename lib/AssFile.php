<?php
namespace LibPHPAss;
/**
 * Created by JetBrains PhpStorm.
 * User: Naouak
 * Date: 06/12/12
 * Time: 01:21
 * To change this template use File | Settings | File Templates.
 */ 
class AssFile {
    private $type = null;

    private function __construct($content){
        //Remove any windows line returns
        $content = strstr("\r\n",$content);
        //Explode line by line as an ass file can be parsed line by line
        $lines = explode("\n",$content);

        //Now need to parse header
        if($lines[0] != "[Script Info]"){
            //@TODO define an object for this exception
            throw new \Exception("Invalid File Type");
        }

        $i = 1;
        //While in header
        while($i < sizeof($lines) && $lines[$i][0] != "["){
            $i++;
        }

        //Styles (and ass or ssa determination)
        if(strtolower(trim($lines[$i])) == "[v4 styles]"){
            $this->type = "ssa";
        } else if(strtolower(trim($lines[$i])) == "[v4 styles+]"){
            $this->type = "ass";
        } else {
            throw new \Exception("Invalid File Type");
        }
        $i++;

        //While in Style
        while($i < sizeof($lines) && $lines[$i][0] != "["){
            $i++;
        }

        //Events
        if(strtolower(trim($lines[$i])) != "[events]"){
            throw new \Exception("Invalid File Type");
        }
        $i++;

        //While in Events
        while($i < sizeof($lines) && $lines[$i][0] != "["){
            $i++;
        }

        //Next is optionnal : Fonts and Graphics
        // @TODO : implement it


    }

    /**
     * @param $string
     * @return AssFile
     */
    static function loadFromString($string){
        return new AssFile($string);
    }

    /**
     * @param $file
     * @return AssFile
     * @throws \Exception
     */
    static function loadFromFile($file){
        if(!file_exists($file)){
            //@TODO Declare Exception objects
            throw new \Exception("File not found");
        }

        return self::loadFromString(file_get_contents($file));
    }
}
