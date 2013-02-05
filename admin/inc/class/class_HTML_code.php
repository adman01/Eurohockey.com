<?php
//trida pro vypis hlavicky HTML stranky
class HTMLHead{
 
public function __construct(){
}
  
  //vrati zacatek hlavicky
  function setStartHead(){
    return '<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/" xml:lang="en" lang="en">
<head>
  <meta http-equiv="content-language" content="en" />
  <meta name="robots" content="index,follow" />
';     
  }
  
  //nastavi znakovou sadu
  function setCharset($set){
     if (isset($set)) return "  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$set."\" />\n";
  }
  //nastavi jednotlive hlavicky
  function setLanguage($set){
     if (isset($set)) return "  <meta http-equiv=\"Content-language\" content=\"".$set."\" />\n";
  }
  function setProject($set){
     if (isset($set)) return "  <meta name=\"author\" content=\"Project: ".$set."\" />\n";
  }
  function setGraphic($set){
     if (isset($set)) return "  <meta name=\"author\" content=\"Graphic: ".$set."\" />\n";
  }
  function setCoding($set){
     if (isset($set)) return "  <meta name=\"author\" content=\"Coding: ".$set."\" />\n";
  }
  function setPrograming($set){
     if (isset($set)) return "  <meta name=\"author\" content=\"Programing: ".$set."\" />\n";
  }
  function setCopyright($set){
     if (isset($set)) return "  <meta name=\"copyright\" content=\"".$set."\" />\n";
  }
  function setKeywords($set){
     if (isset($set)) return "  <meta name=\"keywords\" content=\"".$set."\" />\n";
  }
  function setDescription($set){
     if (isset($set)) return "  <meta name=\"description\" content=\"".$set."\" />\n";
  }
  
  //prida do hlavicky JS soubor
  function setJavascriptExtendFile($set){
     if (file_exists($set)) return "  <script src=\"".$set."\" type=\"text/javascript\"></script>\n";
  }
  
  //prida do hlavicky CSS soubor /1-screen /2-print
  function setCSSExtendFile($set,$type){
     if ($type==1){$type='screen, projection';}
     if ($type==2){$type='print';}
     if ((file_exists($set)) and ($type<=2)) return "  <link rel=\"stylesheet\" href=\"".$set."\" type=\"text/css\" media=\"".$type."\" />\n";
  }
  //prida do hlavicky nejaky jiny radek
  function setExtendline($set){
     if (isset($set)) return "".$set."\n";
  }
  //prida do hlavicky RSS zdroj
  function setRSSFile($set,$name){
     if (file_exists($set)) return "  <link rel=\"alternate\" type=\"application/rss+xml\" title=\"".$name."\" href=\"".$set."\" />\n";
  }
  //prida do favicon image
  function setFavicon($set){
     if (file_exists($set)) return "  <link rel=\"shortcut icon\" href=\"".$set."\" type=\"image/x-icon\" />\n";
  }
  //prida do title
  function setTitle($set){
    if (isset($set)) return "  <title>".strip_tags($set)."</title>\n";
  }
  //ukonci hlavicku
  function setEndHead(){
    return "</head>\n";
  }
  
  
}
?>