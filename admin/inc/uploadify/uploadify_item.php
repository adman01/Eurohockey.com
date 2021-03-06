<?php
/*
Uploadify v2.1.0
Release Date: August 24, 2009

Copyright (c) 2009 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

function getCorrectName($name){  
    $name = preg_replace('~[^\\pL0-9_]+~u', '-', $name);
    $name = trim($name, "-");
    $name = iconv("utf-8", "us-ascii//TRANSLIT", $name);
    $name = strtolower($name);
    $name = preg_replace('~[^-a-z0-9_]+~', '', $name);
    if (!empty($name)) $name = substr_replace($name, '.', strrpos($name,"-"), 1);
    return $name;
  }


if (!empty($_FILES)) {
  
  $intInputs=split("_datetype_",$_REQUEST['sesid']);
  $strUserSubFolder=$intInputs[0];
  $tempFile = $_FILES['Filedata']['tmp_name'];
  $targetPath = $_SERVER['DOCUMENT_ROOT'] ."/".$_REQUEST['folder'] . '/';
  
  if ($intInputs[1]=="photogalery"){
    
    mkdir($targetPath.$strUserSubFolder."");
    mkdir($targetPath.$strUserSubFolder."/photo");
    $targetFile =  str_replace('//','/',$targetPath.$strUserSubFolder.'/photo/') . getCorrectName($_FILES['Filedata']['name']);
  
  }else{
    
    mkdir($targetPath.$strUserSubFolder);
    $targetFile =  str_replace('//','/',$targetPath.$strUserSubFolder.'/') . $intInputs[1]."_". $intInputs[0].'.'.$intInputs[2];
  }
  
  
	
	// $fileTypes  = str_replace('*.','',$_REQUEST['fileext']);
	// $fileTypes  = str_replace(';','|',$fileTypes);
	// $typesArray = split('\|',$fileTypes);
	// $fileParts  = pathinfo($_FILES['Filedata']['name']);
	
	// if (in_array($fileParts['extension'],$typesArray)) {
		// Uncomment the following line if you want to make the directory if it doesn't exist
		// mkdir(str_replace('//','/',$targetPath), 0755, true);
		
		move_uploaded_file($tempFile,$targetFile);
		chmod($targetFile,0755);   
		echo "1";
	// } else {
	// 	echo 'Invalid file type.';
	// }
}
?>