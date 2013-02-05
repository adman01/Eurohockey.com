<?php
require_once("inc/global.inc");

if (empty($filename)) $filename='img/logos/flags/'.$_GET["filename"];
$filename=$input->valid_text($filename,true,true);
$filename=str_replace("_","-",$filename);

if (empty($width)) $width=$_GET["width"];
$width=$input->check_number($width);
if (empty($height)) $height=$_GET["height"];
$height=$input->check_number($height);

if (empty($ratio)) $ratio=$_GET["ratio"];
if ($ratio<>1) $ratio=0;
if (empty($tempFolder)) $tempFolder="temp/";

function ErrorImage($string)
{
  global $width;
  //header ("Content-type: image/png");
  //$im = imagecreate ($width, $width);
  //$background_color = imagecolorallocate ($im, 200, 200, 200);
  //$text_color = imagecolorallocate ($im, 233, 14, 91);
  //imagestring ($im, 5, 5, 5, "ERROR: ".$string, $text_color);
  //imagepng ($im);
  exit();
}

//echo $filename.'<br />';
if(file_exists($filename)){

  $size=getimagesize($filename);

  if($width<1)  ErrorImage("bad width"); 
  if($height<1) ErrorImage("bad height");

  /* pridelenie hlavicky */
  switch($size[2])
  {
  case 1:
    $type="gif";
    header("Content-type: image/gif");
    break;
  case 2:
    $type="jpeg";
    header("Content-type: image/jpeg");
    break;
  case 3:
    $type="png";
    header("Content-type: image/png");
    break;

/* ak je to iný formát obrázku alebo to nie je obrázok, vykreslenie error obrázku */
  default:
    ErrorImage("Bad image type");
    
}

/* výpocet novej šírky a výšky */
if ($ratio==1){
  $scale_width=$width/$size[0];
  $scale_height=$height/$size[1];
  $scale=($scale_width <= $scale_height ? $scale_width : $scale_height);
  $new_width=ceil($scale*$size[0]);
  $new_height=ceil($scale*$size[1]);
}else{
  $new_width=ceil($width);
  $new_height=ceil($height);
}




$new_filename=md5($filename.$width.$height).".".$type;
$file_location=$tempFolder.$new_filename;

if(file_exists($file_location)){
  readfile ($file_location);
}
else{
  /* vytvorenie cielového a zdrojového obrázku */
  $im = imagecreatetruecolor($new_width,$new_height);
  switch($type)
  {
  case "gif":
    $res_im=imagecreatefromgif($filename);
    break;
  case "jpeg":
    $res_im=imagecreatefromjpeg($filename);
    break;
  case "png":
    $res_im=imagecreatefrompng($filename);
    break;
  }
  
  $image_new = imagecreatetruecolor($new_width, $new_height);
  imagecopyresampled($image_new, $res_im, 0, 0, 0, 0, $new_width+1, $new_height+1, $size[0], $size[1]);
  
  /* výstup náhladu do browsera */
  switch($type)
  {
  case "jpeg":
    imagejpeg($image_new, $file_location, 100);
    break;
  case "gif":
    imagegif($image_new, $file_location, 100);
    break;
  case "png":
    imagepng($image_new, $file_location, 100);
    break;
  }
 
  ImageDestroy($image_new);
  Imagedestroy($res_im); 
	readfile ($file_location);
} 

}
else{
  ErrorImage("file not fount");
}
?>