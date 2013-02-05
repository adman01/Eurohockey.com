<?php
header("Content-Type: text/xml"); 
require_once("../inc/global.inc");
require_once("../inc/init.inc");
$input = new Input_filter();
$users->setUserRight($_REQUEST['idPageRight'],1,0,0,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
//get the q parameter from URL
$q=$_REQUEST["q"];

echo '<livesearch id_club="'.$_POST["id"].'">';
if (strlen($q) > 0)
{
  $counter=0;
  If(!empty($ArSpecialRight[4])) $sqlRightWhereCountry="AND ".str_replace("id_country","id_country",$ArSpecialRight[4]); else $sqlRightWhereCountry="";
  if(!empty($ArSpecialRight[2])) $sqlRightWhereLeague="  AND (SELECT count(*) FROM clubs_leagues_items WHERE clubs_leagues_items.id_club=clubs.id AND (".str_replace("clubs_leagues_items.id_league","id_league",$ArSpecialRight[2])."))>0"; else $sqlRightWhereLeague="";
  if(!empty($ArSpecialRight[3])) $sqlRightWhereClub=" AND ".str_replace("id_club","id",$ArSpecialRight[3]); else $sqlRightWhereClub="";
  $query="SELECT name,id FROM clubs WHERE name LIKE '%".$q."%' ".$sqlRightWhereClub." ".$sqlRightWhereCountry." ".$sqlRightWhereLeague." ORDER BY name";
  if ($con->GetQueryNum($query)>0){
 	  $result = $con->SelectQuery($query);
    while($write = $result->fetch_array())
	   {
       $strIdArray[$counter]=$write['id'];
       $counter++;
     }
  }
  
  $query="SELECT id_club as id,name FROM clubs_names WHERE  name LIKE '%".$q."%' ".$sqlRightWhereClub." ".$sqlRightWhereCountry." ".$sqlRightWhereLeague." ORDER BY name";
  //echo $query; 
  if ($con->GetQueryNum($query)>0){
 	  $result = $con->SelectQuery($query);
    while($write = $result->fetch_array())
	   {
       $strIdArray[$counter]=$write['id'];
       $counter++;
     }
  }
  if ($counter>0){
  
  $strIdArray=array_unique($strIdArray);
  sort($strIdArray);
  
  foreach ($strIdArray as &$value) {
 
  
  $counter_name=0;
  $strNameArray="";
  $query="SELECT name FROM clubs WHERE id=".$value." ORDER BY name";
  if ($con->GetQueryNum($query)>0){
 	  $result = $con->SelectQuery($query);
    $write = $result->fetch_array();
       
       $intMainNameDuplicate=$con->GetSQLSingleResult("SELECT count(*) as item FROM clubs_names WHERE lcase(name)=lcase('".$write['name']."') AND id_club=".$value);
       if ($intMainNameDuplicate==0){
	       $strNameArray[$counter_name]=$write['name'];
         $counter_name++;
       }
       
       
  }
  
  $query="SELECT name FROM clubs_names WHERE id_club=".$value." ORDER BY int_year DESC, name ASC";
  if ($con->GetQueryNum($query)>0){
 	  $result = $con->SelectQuery($query);
    while($write = $result->fetch_array())
	   {
       $strNameArray[$counter_name]=$write['name'];
       $counter_name++;
     }
  }
  
  if ($counter_name>0){
    foreach ($strNameArray as &$valueName) {
        $valueName=$input->valid_text($valueName,true,true);
        echo '<item id="'.$value.'">'.$value.' | '.$valueName.'</item>';
    }
  
  }
  
	}
  
  }
  
  }

echo "</livesearch>\n";

?>