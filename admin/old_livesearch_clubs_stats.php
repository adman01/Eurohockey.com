<?php
header("Content-Type: text/xml"); 
require_once("inc/global.inc");
$input = new Input_filter();
$games = new games($con);
//get the q parameter from URL
$q=$_POST["q"];
$id_season=$_POST["id_season"];

echo '<livesearch id_club="'.$_POST["id"].'">';
if (strlen($q) > 0)
{
  $query="SELECT id FROM clubs WHERE name LIKE '%".$q."%' ORDER BY name";
  if ($con->GetQueryNum($query)>0){
 	$result = $con->SelectQuery($query);
  while($write = $result->fetch_array())
	{
	
	$name=$games->GetActualClubName($write['id'],$id_season);
	$name=$input->valid_text($name,true,true);
	
	echo '<item id="'.$write["id"].'">'.$name.'</item>';
	}
	}
}
echo "</livesearch>\n";
?>