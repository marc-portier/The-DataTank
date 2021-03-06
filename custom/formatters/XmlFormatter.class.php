<?php
/**
 * This file contains the Xml formatter.
 * @package The-Datatank/formatters
 * @copyright (C) 2011 by iRail vzw/asbl
 * @license AGPLv3
 * @author Jan Vansteenlandt <jan@iRail.be>
 * @author Pieter Colpaert   <pieter@iRail.be>
 */

/**
 * This class inherits from the abstract Formatter. It will our resultobject into an XML datastructure.
 */
class XmlFormatter extends AFormatter{
    //make a stack of array information, always work on the last one
    //for nested array support
    private $stack = array();
    private $arrayindices = array();
    private $currentarrayindex = -1;

    public function __construct($rootname,$objectToPrint){
        parent::__construct($rootname,$objectToPrint);
    }

    public function printHeader(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: text/xml; charset=UTF-8");
    }

    public function printBody(){
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";	  
        $this->printObject($this->rootname . " version=\"1.0\" timestamp=\"" . time() . "\"",$this->objectToPrint);
    }

    private function printObject($name,$object){
        //check on first character
        if(preg_match("/^[0-9]+.*/", $name)){
            $name = "i" . $name; // add an i
        }
        echo "<".$name.">";
        //If this is not an object, it must have been an empty result
        //thus, we'll be returning an empty tag
        if(is_object($object)){
            $hash = get_object_vars($object);
            foreach($hash as $key => $value){
                if(is_object($value)){
                    $this->printObject($key,$value);
                }elseif(is_array($value)){
                    $this->printArray($key,$value);
                }else{
                    $key = htmlspecialchars(str_replace(" ","",$key));
                    $val = htmlspecialchars($value);
                    echo "<".$key.">". $val ."</".$key.">";
                }
            }
        }

        // *****  Workaround for array id's:
        // if an array element has been passed then the name contains id=...
        // so we need to find the first part of the tag which only contains the name
        // i.e. $name =>  animal id='0', an explode(" ",$name)[0] should do the trick!
        $boom = explode(" ",$name);
        echo "</".$boom[0].">";
    }

    private function printArray($name,$array){
        //check on first character
        if(preg_match("/^[0-9]+.*/", $name)){
            $name = "i" . $name; // add an i
        }
        $index = 0;
        foreach($array as $key => $value){
            $nametag = $name;	       
            if(is_object($value)){
                $this->printObject($nametag,$value);
            }else if(is_array($value) && !$this->isHash($value)){
                echo "<".$name. ">";
                $this->printArray($nametag,$value);
                echo "</".$name.">";
            }else if(is_array($value) && $this->isHash($value)){
                echo "<".$name. ">";
                $this->printArray($key,$value);
                echo "</".$name.">";
            }else{// no array in arrays are allowed!!
                $name = htmlspecialchars(str_replace(" ","",$name));
                $value = htmlspecialchars($value);
                if($this->isHash($array)){ //if this is an associative array, don't print it by name of the parent
                    //check on first character
                    if(preg_match("/^[0-9]+.*/", $key)){
                        $key = "i" . $key; // add an i
                    }
                    echo "<".$key . ">" . $value . "</".$key.">";
                }else{
                    echo "<".$name. ">".$value."</".$name.">";
                }
                    
            }  
            $index++;
        }
    }

    // check if we have an hash or a normal 'numberice array ( php doesn't know the difference btw, it just doesn't care. )
    private function isHash($arr){
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public static function getDocumentation(){
        return "Prints plain old XML. Watch out for tags starting with an integer: an i will be added.";
    }


};
?>