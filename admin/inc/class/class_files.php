<?php
//trida pro praci se soubory, složkami ard...
class files{
  public $file,$fileName,$fileSize,$fileType,$fileTemp;
  
  function getFileName(){return $this->fileName; }
  
  
  function cs_utf2ascii($s)
  {
    static $tbl = array("\xc3\xa1"=>"a","\xc3\xa4"=>"a","\xc4\x8d"=>"c","\xc4\x8f"=>"d","\xc3\xa9"=>"e","\xc4\x9b"=>"e","\xc3\xad"=>"i","\xc4\xbe"=>"l","\xc4\xba"=>"l","\xc5\x88"=>"n","\xc3\xb3"=>"o","\xc3\xb6"=>"o","\xc5\x91"=>"o","\xc3\xb4"=>"o","\xc5\x99"=>"r","\xc5\x95"=>"r","\xc5\xa1"=>"s","\xc5\xa5"=>"t","\xc3\xba"=>"u","\xc5\xaf"=>"u","\xc3\xbc"=>"u","\xc5\xb1"=>"u","\xc3\xbd"=>"y","\xc5\xbe"=>"z","\xc3\x81"=>"A","\xc3\x84"=>"A","\xc4\x8c"=>"C","\xc4\x8e"=>"D","\xc3\x89"=>"E","\xc4\x9a"=>"E","\xc3\x8d"=>"I","\xc4\xbd"=>"L","\xc4\xb9"=>"L","\xc5\x87"=>"N","\xc3\x93"=>"O","\xc3\x96"=>"O","\xc5\x90"=>"O","\xc3\x94"=>"O","\xc5\x98"=>"R","\xc5\x94"=>"R","\xc5\xa0"=>"S","\xc5\xa4"=>"T","\xc3\x9a"=>"U","\xc5\xae"=>"U","\xc3\x9c"=>"U","\xc5\xb0"=>"U","\xc3\x9d"=>"Y","\xc5\xbd"=>"Z");
    return strtr($s, $tbl);
  }
  
  //zjisti zda slozka existuje
  function checkFolder($path){
    if (is_dir($path)) {return true;} else {return false;}
  }
  
  //zjisti zda soubor existuje
  function checkFile($path){
    if (file_exists($path)) {return true;} else {return false;}
  }
  
  //nastavi nove jmeno souboru
  function setFileName($name){
    $this->fileName=$this->getCorrectName($name);
  }
  
  //prevede nazev na retezec vhodny k pouziti v souborovem systemu
  function getCorrectName($name){
    $name=strtolower($name);
	  $name=$this->cs_utf2ascii($name);
	  $name=trim($name);
	  $name = preg_replace("/[^a-zA-Z0-9s.]/", "-", $name);
	  $name = strtolower($name);
    $name = str_replace("--","-",$name);
    $name = str_replace("_","-",$name);
    $name = str_replace("---","-",$name);
    $name = str_replace("----","-",$name);
    $name = str_replace("-----","-",$name);
	  return $name;
  }
  
  //nastavi promenou s FILE pro upload
  function setFile($file,$number){
    $this->file=$file;
    $this->fileName=$this->getCorrectName($this->file["name"][$number]);
    $this->fileSize=$this->file["size"][$number];
    $this->fileType=$this->file["type"][$number];
    $this->fileTemp=$this->file["tmp_name"][$number];
  }
  
  
  //provede upload souboru z stanovenych podminek
  function uploadFile($FolderPath,$MaxSize,$AlowedType){
    
    if ($this->checkFolder($FolderPath)) {
      
      $FolderPath=$FolderPath."/".$this->getFileName();
      //echo $FolderPath; 
      if (!$this->checkFile($FolderPath)) {
        if (empty($MaxSize)) {$MaxSize=10000000000;}
        if ($this->fileSize<=$MaxSize){
          $check=false;
          if (!empty($AlowedType)){
            foreach($AlowedType as $key => $value)
            {  
              if ($value==$this->fileType){$check=true;}
            } 
          }
          else{$check=true;}
          
          if ($check==true){
            if(move_uploaded_file($this->fileTemp,$FolderPath)){
                //upload OK
                return 1; 
            }else{
              //upload not OK
              return 2;
            }
          }
          else{
            //spatny typ souboru
            return 4;
          }
        }
        else{
          // prilis velky soubor
          return 3;
        }
      }
      else
      {
        //soubor jiz existuje
        return 6;
      }
    }
    else
    {
    return 5;
    }  
  }
  
  //nastavi prava na disku u souboru
  function setFileRules($FolderPath,$Rule){
    $FolderPath=$FolderPath."/".$this->getFileName();
    chmod ($FolderPath,$Rule); 
  }
  
  //smaze soubor
  function deleteFile($FolderPath,$FileName){
    if (empty($FileName)){return false;}
    else{
    $FileDelete=$FolderPath."/".$FileName;
    if ($this->checkFile($FolderPath)) {
      if (unlink($FileDelete)){
        return true;
      }
      else{
        return false;
      }
    }
    else{return false;}
    }
  }
  
  //smaze slozku
  function deleteFolder($FolderPath){
    if ($this->checkFolder($FolderPath)) {
      if (count(glob("$FolderPath/*")) === 0) {
      if (rmdir($FolderPath)){
        return true;
      }
      else{
        return false;
      }
      }
      else{
        return false;
      }
    }
    
  }
  
  //presune soubor
  function moveFile($FolderOldPath,$FolderNewPath,$FileOldName,$FileNewName){
    
    if (empty($FileNewName) or empty($FileOldName)){return false;}
    else{
      if ($this->checkFolder($FolderOldPath)  and $this->checkFolder($FolderNewPath) ) {
        //echo $this->checkFile($FolderOldPath."/".$FileOldName).'<br />';
        //echo $FolderNewPath."/".$FileNewName;
        //if (($this->checkFile($FolderOldPath."/".$FileOldName) and (!$this->checkFile($FolderNewPath."/".$FileNewName)))){
          $FolderOldPath=$FolderOldPath."/".$FileOldName;
          $FolderNewPath=$FolderNewPath."/".$FileNewName;
          
          if (rename($FolderOldPath,$FolderNewPath)){
            return true;
          }
        //}
        //else{
          //return false;
        //}
        }
        else{
          return false;
        }
      }
  }
  
}
?>