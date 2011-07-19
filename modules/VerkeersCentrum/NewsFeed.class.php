<?php
/* Copyright (C) 2011 by iRail vzw/asbl
 *
 * Author: Pieter Colpaert <pieter aŧ iRail.be>
 * License: AGPLv3
 *
 * This method of Verkeerscentrum will get the newsfeed of Belgian traffic jams, accidents and works
 */


include_once("modules/AMethod.php");

class NewsFeed extends AMethod{

     private $lang;

     public function __construct(){
	  parent::__construct("NewsFeed");
     }

     public static function getParameters(){
	  return array("lang" => "Language in which the newsfeed should be returned");
     }

     public static function getRequiredParameters(){
	  return array();
     }

     public function setParameter($key,$val){
	  if($key == "lang"){
	       $this->lang = $val;
	  }
     }

     public function call(){
	  return new Feed();
     }

     public static function getAllowedPrintMethods(){
	  return array("json","xml", "jsonp", "php");
     }

     public static function getDoc(){
	  return "This is a function which will return all the latest news";
     }
}

class Feed{
     public function __construct(){
	  $data = $this->getData();
	  $this->parseData($data);
	  
     }

     private function getData(){
	  $scrapeUrl = "http://www.verkeerscentrum.be/verkeersinfo/tekstoverzicht_actueel?lastFunction=info&sortCriterionString=TYPE&sortAscending=true&autoUpdate=&cbxFILE=CHECKED&cbxINC=CHECKED&cbxRMT=CHECKED&cbxINF=CHECKED&cbxVlaanderen=CHECKED&cbxWallonie=CHECKED&cbxBrussel=CHECKED&searchString=&searchStringExactMatch=true";
	  return utf8_encode(TDT::HttpRequest($scrapeUrl)->data);
     }
     
     private function parseData($data){
	  
	  preg_match_all('/<tr>.*?<td width="2" bgcolor="#EAF0BF"><\/td>.*?<td width="68" height="31" style="width:68px; height=31px" bgcolor="#EAF0BF" align="center" valign="middle"><img border="0" src="images\/(.*?).gif" alt="" width="31" height="31" \/>.*?<\/td>.*? class="Tekst_bericht">(.*?)<\/span>.*?class="Tekst_bericht">(.*?)\s*<\/span>.*?class="Tekst_bericht">(.*?)<\/span>/smi', $data, $matches, PREG_SET_ORDER);
	  //1 = soort
	  //2 = location
	  //3 = message
	  //4 = time
	  $i = 0;
	  foreach($matches as $match){
	       $cat = $match[1];
	       $cat = str_ireplace("ongeval_driehoek","accident",$cat);
	       $cat = str_ireplace("file_driehoek","traffic jam",$cat);
	       $cat = str_ireplace("i_bol","info",$cat);
	       $cat = str_ireplace("werkman","works",$cat);

	       $this->item[$i] = new Item();
	       $this->item[$i]->category = trim(str_replace("\s\s+","",strip_tags($cat)));
	       $this->item[$i]->location = trim(str_replace("\s\s+","",strip_tags($match[2])));
	       $this->item[$i]->message =trim(str_replace("\s\s+","",strip_tags($match[3])));
	       $this->item[$i]->time = trim(str_replace("\s\s+","",strip_tags($match[4])));
	       $i++;
	  }
     }
     
}
Class Item{
}

?>