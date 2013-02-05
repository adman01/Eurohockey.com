<?php
header("Content-Type: text/xml"); 
require_once("inc/global.inc");
$input = new Input_filter();
$games = new games($con);
//get the q parameter from URL
$q=$_REQUEST["q"];

echo '<livesearch id_game="'.$_REQUEST["id"].'">';
if (strlen($q) > 0)
{
  
  $regex="'\b(0?[1-9]|[12][0-9]|3[01])[- /.](0?[1-9]|1[012])[- /.](19|20)?[0-9]{2}\b'";
  if (preg_match($regex, $q)) {
    //date
    $query="SELECT id,id_club_home,id_club_visiting,date,home_score,visiting_score FROM games WHERE date='".date("Y-m-d",strtotime($q))."' ORDER BY time ASC, id_league ASC, id ASC";
    if ($con->GetQueryNum($query)>0){
    $result = $con->SelectQuery($query);
  
    while($write = $result->fetch_array())
	  {
	   $strDate=date("d.m.Y",strtotime($write['date']));
	   $strHome=$games->GetActualClubName($write['id_club_home'],ActSeason);
	   $strVisiting=$games->GetActualClubName($write['id_club_visiting'],ActSeason);
	   $strScore=$games->GetScore($write['id'],1);
	   echo '<item id="'.$write["id"].'">'.$strDate.' '.$strHome.' vs. '.$strVisiting.' ('.$strScore.')</item>';
     }
    }
    
  }else{
    
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
      
      $query="SELECT id,id_club_home,id_club_visiting,date,home_score,visiting_score FROM games WHERE (id_club_home=".$value." OR id_club_visiting=".$value.") AND date<=NOW() ORDER BY date DESC,time DESC, id_league ASC, id ASC LIMIT 5";
      if ($con->GetQueryNum($query)>0){
      $result = $con->SelectQuery($query);
  
      while($write = $result->fetch_array())
	   {
	     $strDate=date("d.m.Y",strtotime($write['date']));
	     $strHome=$games->GetActualClubName($write['id_club_home'],ActSeason);
	     $strVisiting=$games->GetActualClubName($write['id_club_visiting'],ActSeason);
	     $strScore=$games->GetScore($write['id'],1);
       $strScore=strip_tags($strScore);
	     echo '<item id="'.$write["id"].'">'.$value.' | '.$strDate.' '.$strHome.' vs. '.$strVisiting.' ('.$strScore.')</item>';
      }
    }
      
    }
  }
    
    
  }
  
  
 
  
	
	
  }
echo "</livesearch>\n";
?>