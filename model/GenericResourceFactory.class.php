<?php
/**
 * This will get a resource description from the databank and add the right strategy to process the call to the GenericResource class
 *
 * @package The-Datatank/model
 * @copyright (C) 2011 by iRail vzw/asbl
 * @license AGPLv3
 * @author Pieter Colpaert
 * @author Jan Vansteenlandt
 */

include_once("model/resources/GenericResource.class.php");

class GenericResourceFactory extends AResourceFactory{

    public function __construct(){
        
    }

    public function createCreator($package,$resource, $parameters){

    }
    
    public function createReader($package,$resource, $parameters){
        
    }
    
    public function createUpdater($package,$resource, $parameters){
        
    }
    
    public function createDeleter($package,$resource){
        
    }
    
    public function makeDoc($doc){

    }
    
}

?>
