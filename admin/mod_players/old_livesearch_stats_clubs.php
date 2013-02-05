<?php
header("Content-Type: text/xml"); 
require_once("../inc/global.inc");
require_once("../inc/init.inc");
$input = new Input_filter();
$users->setUserRight($_POST['idPageRight'],1,0,0,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
//get the q parameter from URL
$q=$_POST["q"];

echo '<livesearch>';
if (strlen($q) > 0)
{
  If(!empty($ArSpecialRight[4])) $sqlRightWhereCountry="AND ".str_replace("id_country","id_country",$ArSpecialRight[4]); else $sqlRightWhereCountry="";
  if(!empty($ArSpecialRight[2])) $sqlRightWhereLeague="  AND (SELECT count(*) FROM clubs_leagues_items WHERE clubs_leagues_items.id_club=clubs.id AND (".str_replace("clubs_leagues_items.id_league","id_league",$ArSpecialRight[2])."))>0"; else $sqlRightWhereLeague="";
  if(!empty($ArSpecialRight[3])) $sqlRightWhereClub=" AND ".str_replace("id_club","id",$ArSpecialRight[3]); else $sqlRightWhereClub="";
  $query="SELECT name,id FROM clubs WHERE name LIKE '%".$q."%' ".$sqlRightWhereClub." ".$sqlRightWhereCountry." ".$sqlRightWhereLeague." ORDER BY name";
	$result = $con->SelectQuery($query);
  
  while($write = $result->fetch_array())
	{
	$name=$input->valid_text($write["name"],true,true);
	        echo '<item id="'.$write["id"].'">'.$name.'</item>';
  }
  }
echo "</livesearch>\n";
?>