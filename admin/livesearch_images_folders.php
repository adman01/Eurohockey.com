<?php
header("Content-Type: text/xml"); 
require_once("inc/global.inc");
$input = new Input_filter();
//get the q parameter from URL
$q=$_POST["q"];

echo '<livesearch id_image_folder="'.$_POST["id"].'">';
if (strlen($q) > 0)
{
	$query="SELECT id,name,(SELECT count(*) from photo WHERE photo.id_photo_folder=photo_folder.id) as pocet FROM photo_folder WHERE name LIKE '%".$q."%' or id LIKE '%".$q."%' or description LIKE '%".$q."%' ORDER BY name,id DESC";
	$result = $con->SelectQuery($query);
  
  while($write = $result->fetch_array())
	{
	$name=$input->valid_text($write["name"],true,true);
	        echo '<item id="'.$write["id"].'">'.$name.' ('.$write["pocet"].')</item>';
  }
  }
echo "</livesearch>\n";
?>