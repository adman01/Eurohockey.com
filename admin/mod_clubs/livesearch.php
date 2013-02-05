<?php
header("Content-Type: text/xml"); 
require_once("../inc/global.inc");
$input = new Input_filter();
//get the q parameter from URL
$q=$_POST["q"];

echo '<livesearch>';
if (strlen($q) > 0)
{
	$query="SELECT name,id FROM clubs WHERE name LIKE '%".$q."%' ORDER BY name";
	$result = $con->SelectQuery($query);
  
  while($write = $result->fetch_array())
	{
	$name=$input->valid_text($write["name"],true,true);
	        echo '<item>'.$name.'</item>';
  }
  }
echo "</livesearch>\n";
?>