<?php
header("Content-Type: text/xml"); 
require_once("inc/global.inc");
$input = new Input_filter();
$games = new games($con);
//get the q parameter from URL
$q=$_POST["q"];

echo '<livesearch id_player="'.$_POST["id"].'">';
if (strlen($q) > 0)
{
  $query="SELECT surname,name,id,nationality,birth_date FROM players WHERE  nationality LIKE '%".$q."%'  or CONCAT_WS(\" \",name,surname) LIKE '%".$q."%' ORDER BY surname,name,nationality";
  if ($con->GetQueryNum($query)>0){
 	$result = $con->SelectQuery($query);
  while($write = $result->fetch_array())
	{
	
	$name=$input->valid_text($write["name"],true,true);
	$surname=$input->valid_text($write["surname"],true,true);
	$nationality=$input->valid_text($write["nationality"],true,true);
	$birth_date=$input->valid_text($write["birth_date"],true,true);
	echo '<item id="'.$write["id"].'">'.$name.' '.$surname.', '.$nationality.' ('.$birth_date.')</item>';
	}
	}
}
echo "</livesearch>\n";
?>