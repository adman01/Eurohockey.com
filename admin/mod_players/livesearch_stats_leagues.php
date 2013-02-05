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
  If(!empty($ArSpecialRight[4])) $sqlRightWhereCountry=" AND (SELECT count(*) FROM leagues_countries_items WHERE (".str_replace("id_country","leagues_countries_items.id_country",$ArSpecialRight[1]).") AND leagues_countries_items.id_league=leagues.id)>0"; else $sqlRightWhereCountry="";
  if(!empty($ArSpecialRight[2])) $sqlRightWhereLeague=" AND ".str_replace("id_league","id",$ArSpecialRight[2]); else $sqlRightWhereLeague="";
  $query="SELECT name,id FROM leagues WHERE name LIKE '%".$q."%' ".$sqlRightWhereClub." ".$sqlRightWhereCountry." ".$sqlRightWhereLeague." ORDER BY name";
	$result = $con->SelectQuery($query);
  
  while($write = $result->fetch_array())
	{
	$name=$input->valid_text($write["name"],true,true);
	        echo '<item id="'.$write["id"].'">'.$name.'</item>';
  }
  }
echo "</livesearch>\n";
?>