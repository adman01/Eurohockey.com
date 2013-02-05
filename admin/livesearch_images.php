<?php
header("Content-Type: text/xml"); 
require_once("inc/global.inc");
$input = new Input_filter();
//get the q parameter from URL
$q=$_POST["q"];

echo '<livesearch id_image="'.$_POST["id"].'">';
if (strlen($q) > 0)
{
	$query="SELECT * FROM photo WHERE file_name LIKE '%".$q."%' or id LIKE '%".$q."%' or description LIKE '%".$q."%'  or keywords LIKE '%".$q."%' ORDER BY description,id DESC";
	$result = $con->SelectQuery($query);
  
  while($write = $result->fetch_array())
	{
	$description=$input->valid_text($write["description"],true,true);
	$file_name=$input->valid_text($write["file_name"],true,true);
	        echo '<item id="'.$write["id"].'">'.$description.' ('.$file_name.')</item>';
  }
  }
echo "</livesearch>\n";
?>