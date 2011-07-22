<?php
  /* Copyright (C) 2011 by iRail vzw/asbl 
   *
   * Author: Jan Vansteenlandt <jan aŧ iRail.be>
   * Author: Pieter Colpaert <pieter at iRail.be>
   * Prints the Json style output
   *
   *
   * @package output
   */

  /**
   * This file contains the Json printer.
   * @package The-Datatank/printer
   * @copyright (C) 2011 by iRail vzw/asbl
   * @license AGPLv3
   * @author Jan Vansteenlandt <jan@iRail.be>
   * @author Pieter Colpaert   <pieter@iRail.be>
   */
include_once("printer/Printer.php");

/**
 * This class inherits from the abstract Printer. It will return our resultobject into a
 * json datastructure.
 */
class Json extends Printer{
     
     public function __construct($rootname,$objectToPrint){
	  parent::__construct($rootname,$objectToPrint);
     }

     public function printHeader(){
	  header("Access-Control-Allow-Origin: *");
	  header("Content-Type: application/json;charset=UTF-8");	  
     }

     public function printBody(){
	  if(is_object($this->objectToPrint)){
	       $hash = get_object_vars($this->objectToPrint);
	  }
	  $hash['version'] = $this->version;
	  $hash['timestamp'] = time();
	  echo json_encode($hash);
     }
};
?>