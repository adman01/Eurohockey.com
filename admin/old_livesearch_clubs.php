<?php
header("Content-Type: text/xml"); 
require_once("inc/global.inc");
$input = new Input_filter();
$games = new games($con);
//get the q parameter from URL
$q=$_POST["q"];

echo '<livesearch id_club="'.$_POST["id"].'">';
if (strlen($q) > 0)
{
  $query="SELECT id FROM clubs WHERE name LIKE '%".$q."%' ORDER BY name";
  if ($con->GetQueryNum($query)>0){
 	$result = $con->SelectQuery($query);
  while($write = $result->fetch_array())
	{
	
	$name=$games->GetActualClubName($write['id'],ActSeason);
	$name=$input->valid_text($name,true,true);
	
	$query_sub="SELECT id_league,id_club FROM clubs_leagues_items WHERE id_club=".$write['id']." ORDER BY id";
	$numLeagues=$con->GetQueryNum($query_sub);
	if ($numLeagues>1){
	 $strNumLeagues=" + ".$numLeagues." other";  
	}else{
   $strNumLeagues=""; 
  }
  if ($numLeagues>0){
    $result_sub = $con->SelectQuery($query_sub);
	  $write_sub = $result_sub->fetch_array();
	  $name_league=$games->GetActualLeagueName($write_sub['id_league'],ActSeason);
    $name_league=" (".$input->valid_text($name_league,true,true).$strNumLeagues.")";
  }else{
    $name_league="";
  }
	echo '<item id="'.$write["id"].'">'.$name.''.$name_league.'</item>';
	}
	}
}
echo "</livesearch>\n";
?>