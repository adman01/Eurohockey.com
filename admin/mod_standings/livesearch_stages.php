<?php
header("Content-Type: text/xml"); 
require_once("../inc/global.inc");
//require_once("../inc/init.inc");
$input = new Input_filter();
//get the q parameter from URL
$q=$_POST["q"];
$q=$input->check_number($q);

echo '<livesearch>';
if (strlen($q) > 0)
{
  $query="SELECT name,id FROM games_stages WHERE id_league = ".$q." ORDER by id";
	$result = $con->SelectQuery($query);
  
  while($write = $result->fetch_array())
	{
	        echo '<item id="'.$write["id"].'">'.$write["name"].'</item>';
  }
  }
echo "</livesearch>\n";
?>