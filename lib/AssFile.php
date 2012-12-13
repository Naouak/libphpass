<?php
namespace LibPHPAss;

class AssFile {
    private $type = null;

    static private $headParamsAccepted = array(
        "Title",
        "Original Script",
        "Original Translation",
        "Original Editing",
        "Original Timing",
        "Synch Point",
        "Script Updated By",
        "Update Details",
        "ScriptType",
        "Collisions",
        "PlayResX",
        "PlayResY",
        "PlayDepth",
        "Timer",
        "WrapStyle"
    );

    private $head = array();

    /**
     * @param $content string Content of the ass file.
     */
    private function __construct($content){
        //File may contains a BOM so we need to detect it and remove it if necessary
        $bom = pack("CCC", 0xef, 0xbb, 0xbf);
        if (0 == strncmp($content, $bom, 3)) {
            $content = substr($content, 3);
        }


        //Remove any windows line returns
        $content = str_replace("\r\n","\n",$content);
        //Explode line by line as an ass file can be parsed line by line
        $lines = explode("\n",$content);

        //Now need to parse header
        if($lines[0] != "[Script Info]"){
            //@TODO define an object for this exception
            throw new \Exception("Invalid File Type");
        }

        $i = 1;
        $j = $i;
        //While in header
        while($i < sizeof($lines) && $lines[$i][0] != "["){
            $i++;
        }

        $header = array_slice($lines,$j,$i-$j);
        $this->parseHeader($header);

        //Styles (and ass or ssa determination)
        if(strtolower(trim($lines[$i])) == "[v4 styles]"){
            $this->type = "ssa";
        } else if(strtolower(trim($lines[$i])) == "[v4 styles+]" || strtolower(trim($lines[$i])) == "[v4+ styles]"){
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
        while($i < sizeof($lines) && strlen($lines[$i]) > 0 && $lines[$i][0] != "["){
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

    /**
     * @param $param String Name of the parameter to send
     * @param $value String Value of the parameter
     * @return bool
     */
    private function setHeaderInformation($param,$value){
        if(array_search($param,self::$headParamsAccepted) === false){
            return false;
        }

        //Let's manage parameters that have only a set of predefined values
        if($param == "ScriptType" && (strtolower($value) != "v4.00" && strtolower($value) != "v4.00+")){
            return false;
        } else if ($param == "Collisions" && ($value != "Normal" && $value != "Reverse")) {
            return false;
        } else if (($param == "PlayResX" || $param == "PlayResY" || $param == "PlayDepth" || $param == "Timer") && (!is_numeric($value))) {
            return false;
        } else if ($param == "WrapStyle" && (!is_numeric($value) || $value < 0 || $value > 3)) {
            return false;
        }

        $this->head[$param] = $value;
        return true;
    }

    /**
     * @param $header Array Strings that forms the header of the file.
     */
    private function parseHeader($header)
    {
        foreach ($header as $line) {
            $data = explode(":",$line,2);
            if(sizeof($data) == 2){
                $this->setHeaderInformation(trim($data[0]),trim($data[1]));
            }
        }

    }

    public function getHeaderInfo($info = null){
        if($info == null){
            return $this->head;
        }
        else if(isset($this->head[$info])){
            return $this->head[$info];
        }
        else{
            return null;
        }
    }
}
