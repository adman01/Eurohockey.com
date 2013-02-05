<?php
if (empty($filename)) $filename=$_GET["filename"];
if (empty($width)) $width=$_GET["width"];
if (empty($height)) $height=$_GET["height"];
if (empty($tempFolder)) $tempFolder="../../temp/";


function ErrorImage($string)
{
  header ("Content-type: image/png");
  $im = imagecreate (300, 50);
  $background_color = imagecolorallocate ($im, 200, 200, 200);
  $text_color = imagecolorallocate ($im, 233, 14, 91);
  imagestring ($im, 5, 5, 5, "ERROR: ".$string, $text_color);
  imagepng ($im);
  exit();
}

if(file_exists($filename)){
  $size=getimagesize($filename);

  if($width<1)  ErrorImage("Nekorektní šíøka"); 
  if($height<1) ErrorImage("Nekorektní šíøka");

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
    ErrorImage("Nekorektny typ suboru");
    
}

/* výpocet novej šírky a výšky */
$scale_width=$width/$size[0];
$scale_height=$height/$size[1];
$scale=($scale_width <= $scale_height ? $scale_width : $scale_height);
$new_width=ceil($scale*$size[0]);
$new_height=ceil($scale*$size[1]);

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
  ErrorImage("Soubor neexistuje");
}
?>