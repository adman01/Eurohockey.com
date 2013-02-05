<?php
header("Content-Type: text/xml"); 
require_once("inc/global.inc");
require_once("inc/init.inc");
$input = new Input_filter();
$games = new games($con);
$users->setUserRight($_POST['idPageRight'],1,0,0,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
//get the q parameter from URL
$q=$_POST["q"];
//$users->getSesid()
echo '<livesearch id_league="'.$_POST["id"].'">';
if (strlen($q) > 0)
{
  if(!empty($ArSpecialRight[2])) $sqlRightWhere=" AND ".str_replace("id_league","id",$ArSpecialRight[2]); else $sqlRightWhere="";
  if(!empty($ArSpecialRight[1])) $sqlRightWhereCountry="  AND (SELECT count(*) FROM leagues_countries_items WHERE leagues_countries_items.id_league=leagues.id AND (".str_replace("leagues_countries_items.id_country","id_country",$ArSpecialRight[1])."))>0"; else $sqlRightWhereCountry="";
  $query="SELECT id FROM leagues WHERE name LIKE '%".$q."%' ".$sqlRightWhere." ".$sqlRightWhereCountry." ORDER BY name";
  if ($con->GetQueryNum($query)>0){
 	$result = $con->SelectQuery($query);
  while($write = $result->fetch_array())
	{
	
	$name=$games->GetActualLeagueName($write['id'],ActSeason);
	$name=$input->valid_text($name,true,true);
	
	echo '<item id="'.$write["id"].'">'.$name.'</item>';
	}
	}
}
echo "</livesearch>\n";
?>