<?php
header("Content-Type: text/xml"); 
require_once("../inc/global.inc");
$input = new Input_filter();
$games = new games($con);
//get the q parameter from URL
$q=$_POST["q"];
$id=$_POST["id"];
$id_season=$_POST["id_season"];
 x
echo '<livesearch id="'.$id.'">';
if (strlen($q) > 0)
{
  $query="SELECT name,id FROM clubs WHERE name LIKE '%".$q."%' ORDER BY name";
	$result = $con->SelectQuery($query);
  
  while($write = $result->fetch_array())
	{
	$name=$games->GetActualClubName($write['id'],$id_season);
	$name=$input->valid_text($name,true,true);
	$name=$input->valid_text($name,true,true);
	
	$query_sub="SELECT id_league,id_club FROM clubs_leagues_items WHERE id_club=".$write['id']." ORDER BY id";
	if ($con->GetQueryNum($query_sub)>0){
	   $result_sub = $con->SelectQuery($query_sub);
	   while($write_sub = $result_sub->fetch_array()){
	     $name_league=$games->GetActualLeagueName($write_sub['id_league'],$id_season);
	     $name_league=" (".$input->valid_text($name_league,true,true).")";
	     echo '<item id_club="'.$write_sub["id_club"].'" id_league="'.$write_sub["id_league"].'">'.$name.''.$name_league.'</item>';
	  }
	  
	}
	
	        
  }
  }
echo "</livesearch>\n";
?>