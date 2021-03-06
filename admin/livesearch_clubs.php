<?php
header("Content-Type: text/xml"); 
require_once("inc/global.inc");
$input = new Input_filter();
$games = new games($con);
//get the q parameter from URL
$q=$_REQUEST["q"];


echo '<livesearch id_club="'.$_POST["id"].'">';
if (strlen($q) > 0)
{
  $counter=0;
  $query="SELECT id,name FROM clubs WHERE name LIKE '%".$q."%' ORDER BY name";
  if ($con->GetQueryNum($query)>0){
 	  $result = $con->SelectQuery($query);
    while($write = $result->fetch_array())
	   {
       $strIdArray[$counter]=$write['id'];
       $counter++;
     }
  }
  
  $query="SELECT id_club as id,name FROM clubs_names WHERE name LIKE '%".$q."%' ORDER BY name";
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
 
  
 	$query_sub="SELECT id_league,id_club FROM clubs_leagues_items WHERE id_club=".$value." ORDER BY id";
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
        $name_league=$input->valid_text($name_league,true,true);
        echo '<item id="'.$value.'">'.$value.' | '.$valueName.''.$name_league.'</item>';
    }
  
  }
  
	}
  
  }
  
  }

echo "</livesearch>\n";
?>