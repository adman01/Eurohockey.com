<?php
header("Content-Type: text/xml"); 
require_once("inc/global.inc");
$input = new Input_filter();
$games = new games($con);
//get the q parameter from URL
$q=$_POST["q"];
$id_season=$_POST["id_season"];

echo '<livesearch id_league="'.$_POST["id"].'" stageBox="'.$_POST["stageBox"].'" stageID="'.$_POST["stageID"].'">';
if (strlen($q) > 0)
{
  $query="SELECT id,youth_league,youth_league_id FROM leagues WHERE name LIKE '%".$q."%' ORDER BY name";
  if ($con->GetQueryNum($query)>0){
 	$result = $con->SelectQuery($query);
  while($write = $result->fetch_array())
	{
	
	$name=$games->GetActualLeagueName($write['id'],$id_season);
	$name=$input->valid_text($name,true,true);
	if ($write['youth_league']==1){
    
    $query_sub="SELECT name FROM leagues_youth_list WHERE id=".$write['youth_league_id'];
    $result_sub = $con->SelectQuery($query_sub);
    $write_sub = $result_sub->fetch_array();
    $strYouth_league=" (".$write_sub['name'].")";
  }else{
    $strYouth_league="";
  }
	
	echo '<item id="'.$write["id"].'">'.$name.$strYouth_league.'</item>';
	
	}
	}
}
echo "</livesearch>\n";
?>