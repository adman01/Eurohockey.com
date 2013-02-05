<?php
$callback = $_REQUEST['callback'];

header('Content-Type: text/javascript');
require_once("../inc/global.inc");
$id=$_GET['id'];
$id=$input->check_number($id);

$strEmpty=$callback.'(
  {
  })
';


if ($id==0){
  echo $strEmpty;
}else{
    
    $query="SELECT * from games WHERE id=".$id;
    $result = $con->SelectQuery($query);
    if ($con->GetQueryNum($query)>0){
      $write = $result->fetch_array();
      $bollIsGame=true;
      $intID_season=$write['id_season'];
      $intGame_ID=$write['id'];
      $strDate=$write['date'];
      $strTime=$write['time'];
      $intID_club_home=$write['id_club_home'];
      $strName_club_home=$games->GetActualClubName($intID_club_home,$intID_season);
      $intID_club_visiting=$write['id_club_visiting'];
      $intName_club_visiting=$games->GetActualClubName($intID_club_visiting,$intID_season);
      $strName_season=($write['id_season']-1).'-'.$write['id_season'];
      $intID_league=$write['id_league'];
      $strName_league=$games->GetActualLeagueName($intID_league,$intID_season);
      $strRound=$write['round'];
      
      $intID_stage=$write['id_stage'];
      $intIDstageDefault=$con->GetSQLSingleResult("SELECT id_detault as item FROM games_stages WHERE id=".$intID_stage);
      if (!empty($intIDstageDefault)) {$intID_stage=$intIDstageDefault;}
      
      
    }
    if ($bollIsGame){ 
    echo $callback.'(
    {"game_stats" : 
	{"game_data" : [
		{
		"game_ID" : "'.$intGame_ID.'",
        "date" : "'.$strDate.'",
        "time" : "'.$strTime.'",
        "id_club_home" : "'.$intID_club_home.'",
        "name_club_home" : "'.$strName_club_home.'",
        "id_club_visiting" : "'.$intID_club_visiting.'",
        "name_club_visiting" : "'.$intName_club_visiting.'",
        "id_season" : "'.$intID_season.'",
        "name_season" : "'.$strName_season.'",
        "id_league" : "'.$intID_league.'",
        "name_league" : "'.$strName_league.'",
        "round" : "'.$strRound.'"}],
        "list_of_players_club_home" : [
        ';
         $count=1;
          $query="SELECT DISTINCT players.id as id_player,name,surname,id_position from stats INNER JOIN players ON stats.id_player=players.id WHERE 
            id_league=".$intID_league." AND id_club=".$intID_club_home." AND id_season_type=".$intID_stage." AND id_season=".$intID_season." AND id_stats_type=1 ORDER BY players.surname ASC,players.name ASC";
          //echo $query;
          $intCount=$con->GetQueryNum($query); 
          if ($intCount>0){
            $result = $con->SelectQuery($query);
            while($write = $result->fetch_array()){
               $strPlayerPos=$con->GetSQLSingleResult("SELECT shortcut as item FROM players_positions_list WHERE id=".$write['id_position']);
               echo '
                {
                "ID" : "'.$write['id_player'].'",
                "position" : "'.$strPlayerPos.'",
                "name" : "'.$write['name'].'",
                "surname" : "'.$write['surname'].'"
                }
                ';
              if ($count<$intCount) echo ',';
              $count++;
            }
          }
        echo '
        ],
        "list_of_players_club_visiting" : [
        ';
         $count=1;
          $query="SELECT DISTINCT players.id as id_player,name,surname,id_position from stats INNER JOIN players ON stats.id_player=players.id WHERE 
            id_league=".$intID_league." AND id_club=".$intID_club_visiting." AND id_season_type=".$intID_stage." AND id_season=".$intID_season." AND id_stats_type=1 ORDER BY players.surname ASC,players.name ASC";
          //echo $query;
          $intCount=$con->GetQueryNum($query); 
          if ($intCount>0){
            $result = $con->SelectQuery($query);
            while($write = $result->fetch_array()){
               $strPlayerPos=$con->GetSQLSingleResult("SELECT shortcut as item FROM players_positions_list WHERE id=".$write['id_position']);
               echo '
                {
                "ID" : "'.$write['id_player'].'",
                "position" : "'.$strPlayerPos.'",
                "name" : "'.$write['name'].'",
                "surname" : "'.$write['surname'].'"
                }
                ';
              if ($count<$intCount) echo ',';
              $count++;
            }
          }
        echo '
        ]

    }

	}
	)
    
    ';
    }else{
      echo $strEmpty;
    }
} 
?>