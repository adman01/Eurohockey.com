<?php                                                 
require_once("inc/global.inc");
require_once("inc/ads.inc");

$id=$_GET['id'];
$id=$input->valid_text($id,true,true);
$id=get_ID_from_url($id);


if (!empty($id)){

  //leagues
  $intLeague=$_GET['league'];
  $intLeague=$input->valid_text($intLeague,true,true);
  if (empty($intLeague)) {$intLeague=(756);}
  $NameLeague=$games->GetActualLeagueName($intLeague,$intSeason);
  
  $intSeason=$_GET['season'];
  $intSeason=$input->check_number($intSeason);
  if (empty($intSeason)) {$intSeason=ActSeasonWeb;}
  $strSeason=($intSeason-1)."-".$intSeason;
  
  $StrClubName=$games->GetActualClubName($id,$intSeason);
  if (!empty($StrClubName)){
    $boolClub=true;
    $strHeader=$strSeason." club season stats for ".$StrClubName."";
    $strHeaderDescription=$strSeason." club season stats for ".$StrClubName." players";
    $strHeaderKeywords=HeaderKeywords.",statistics,national,".$StrClubName.",".$strSeason;
  }else{
    $boolClub=false;
    $strHeader="National team not found";
    $strHeaderDescription="National team not found, or selected";
  }
}else{
  $boolClub=false;
  $strHeader="National team not found";
  $strHeaderDescription="National team not found, or selected";
}

require_once("inc/head.inc");
echo $head->setTitle($strHeader." - ".strProjectName);
echo $head->setEndHead();
?>
<body>

<div id="ads_top"><?php show_ads("top_main"); ?></div>
<div id="layout" class="longer">
  
  <div id="logo"><?php require_once("inc/logo.inc"); ?></div>
  <div id="menu"><?php require_once("inc/menu.inc"); ?></div>
  <div id="main">
      
      <div id="text">
  
         <!-- main text -->
         <?php
          if ($boolClub){
          ?>
         
         <div id="navigation">
            <div class="toleft">
                <div class="corner_nav_left">&nbsp;</div>
                <div class="corner_nav_box"><a href="/countries.html" title="Nationals">Nationals</a></div>
                <div class="corner_nav_middle">&nbsp;</div>
                <div class="corner_nav_box"><?php echo '<a href="/league/'.get_url_text($NameLeague,$intLeague).'?season='.$intSeason.'" title="'.$NameLeague.'">'.$NameLeague.'</a>'; ?></div>
                <div class="corner_nav_middle">&nbsp;</div>
                <div class="corner_nav_box"><?php echo '<a href="/club/'.get_url_text($StrClubName,$id).'?season='.$intSeason.'&amp;league='.$intLeague.'" title="'.$StrClubName.'">'.$StrClubName.'</a>'; ?></div>
                <div class="corner_nav_right">&nbsp;</div>
                
            </div>
         </div>
         
         <div class="space">&nbsp;</div>
         
         <h1><?php echo $strHeader ?></h1>
         <?php
         echo '<div class="box normal bold" style="padding:10px">Tournament: <a href="/league/'.get_url_text($NameLeague,$intLeague).'?season='.$intSeason.'" title="'.$NameLeague.'">'.$NameLeague.' '.$intSeason.'</a></div>';
         ?>
         
          <?php
          function showTable($intPosition){
          
          if ($intPosition==1){ 
            $strWhere= "AND id_position=1";
            $intColspan=2;
          } 
          else{ 
            $strWhere= "AND id_position<>1";
            $intColspan=0;
          }
          
          global $con,$games,$intLeague,$id,$intSeason;
          $query="SELECT DISTINCT players.id as id_player,id_position,players.name,players.surname,nationality,birth_date,weight,height,id_shoot from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$intLeague." AND id_club=".$id." AND id_season=".$intSeason."  AND id_stats_type=1 ".$strWhere." ORDER BY players.id_position ASC,players.surname ASC";
          //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '<table class="basic stats" style="margin-top:0px">';
                  echo '
                  <thead>
                  <tr>
                    <th class="position" valign="top">Pos</th>
                    <th class="" valign="top">Team</th>
                    <th class="" valign="top">League</th>
                    ';
                    if ($intPosition==1){
                    echo'
                    <th class="right" valign="top">GP</th>
                    <th class="right" valign="top">MIN</th>
                    <th class="right" valign="top">AVG</th>
                    <th class="right" valign="top">PER</th>
                    <th class="right" valign="top">SO</th>
                    <th class="right" valign="top">G</th>
                    <th class="right" valign="top">A</th>
                    <th class="right" valign="top">PIM</th>
                    ';
                    }else{
                    echo'
                    <th class="right" valign="top">GP</th>
                    <th class="right" valign="top">G</th>
                    <th class="right" valign="top">A</th>
                    <th class="right" valign="top">P</th>
                    <th class="right" valign="top">PIM</th>
                    <th class="right" valign="top">+/-</th>
                     ';}
                  echo '
                  </tr>
                  </thead>
                  <tbody>
                  ';
                  if ($intPosition==1) $strUrlGoalies="&amp;position=1";
                  $result_player = $con->SelectQuery($query);
                  $counter=0;
                  while($write_player = $result_player->fetch_array()){
                    $counter++;   
                    echo '<tr class="dark">';
                      $StrPosition=$con->GetSQLSingleResult("SELECT shortcut as item FROM players_positions_list WHERE id=".$write_player['id_position']);
                      if (empty($StrPosition)) $StrPosition="-";
                      echo '<td class="position" valign="top" style="width:25px">'.$StrPosition.'</td>';
                      $player_name=$write_player['name'].' '.$write_player['surname'];
  		                echo '<td colspan="'.(8+$intColspan).'" class="player" valign="top" style="font-size:1.1em"><a href="/player/'.get_url_text($player_name,$write_player['id_player']).'" title="Show player profile: '.$player_name.' '.$player_surname.'">'.$write_player['name'].' <b>'.$write_player['surname'].'</b></a>';
                      echo '</td>';
                    echo '</tr>';
                    
                    $query_stats="SELECT id_season,games,goals,assist,penalty,plusminus,id_club,id_league,id_season_type,minutes,AVG,PCE,shotouts,(goals+assist) as points from stats INNER JOIN clubs ON stats.id_club=clubs.id WHERE id_player=".$write_player['id_player']." AND id_season=".$intSeason." AND is_national_team<>2 ORDER BY id_season DESC, id_season_type DESC, stats.id DESC";
                    if ($con->GetQueryNum($query_stats)>0){
                    
                      $result = $con->SelectQuery($query_stats);
                      while($write = $result->fetch_array()){
                       echo '<tr>';
                       echo '<td>&nbsp;</td>';
                       $StrClubName=$games->GetActualClubName($write['id_club'],$write['id_season']);
                       $StrLeagueName=$games->GetActualLeagueName($write['id_league'],$write['id_season']);
                       $StrClubCountry=$con->GetSQLSingleResult("SELECT id_country as item FROM clubs WHERE id=".$write['id_club']);
                       $StrLeagueNameYouth=$games->GetYouthLeague($write['id_league']);
                       $strUrlSeasonType="?type=".$write['id_season_type']."&amp;league=".$write['id_league'];
                       if ($write['id_season_type']>1){
                        $strSeasonType=$con->GetSQLSingleResult("SELECT name as item FROM games_stages WHERE id=".$write['id_season_type']);
                       }else {$strSeasonType="";}
                       
                       echo '<td class="club" valign="top">';
                       echo get_flag(strtolower($StrClubCountry),15,11);
                       echo '<a href="/stats/club/'.$write['id_season'].'/'.get_url_text($StrClubName,$write['id_club']).$strUrlSeasonType.$strUrlGoalies.'" title="Show stats in selected season: '.$StrClubName.'">'.$StrClubName.'</a>';
                       echo '</td>';
                       echo '<td>';
                       echo '<a href="/stats/league/'.$write['id_season'].'/'.get_url_text($StrLeagueName,$write['id_league']).$strUrlSeasonType.$strUrlGoalies.'" title="Show stats in selected season: '.$StrLeagueName.'">'.$StrLeagueName.'</a>';
                       if (!empty($StrLeagueNameYouth) or !empty($strSeasonType)) echo ' <small>(';
                       if (!empty($StrLeagueNameYouth)) echo "".$StrLeagueNameYouth."";
                       if (!empty($strSeasonType)) {
                         if (!empty($StrLeagueNameYouth)) echo ', ';
                         echo "".$strSeasonType."";
                       }  
                       if (!empty($StrLeagueNameYouth) or !empty($strSeasonType)) echo ')</small>';  
                       echo '</td>';
                       if ($intPosition==1){
                       echo '<td class="right" valign="top">'.$write['games'].'</td>';
                       echo '<td class="right" valign="top">'.$write['minutes'].'</td>';
                       echo '<td class="right" valign="top">'.$write['AVG'].'</td>';
                       if ($write['PCE']==1) $strPercent="100.0"; else $strPercent=$write['PCE'];
                       echo '<td class="right" valign="top">'.$strPercent.'</td>';
                       echo '<td class="right" valign="top">'.$write['shotouts'].'</td>';
                       echo '<td class="right" valign="top">'.$write['goals'].'</td>';
                       echo '<td class="right" valign="top">'.$write['assist'].'</td>';
                       echo '<td class="right" valign="top">'.$write['penalty'].'</td>';
                       
                       }else{
                       echo '<td class="right" valign="top">'.$write['games'].'</td>';
                       echo '<td class="right" valign="top">'.$write['goals'].'</td>';
                       echo '<td class="right" valign="top">'.$write['assist'].'</td>';
                       echo '<td class="bold right" valign="top">'.$write['points'].'</td>';
                       echo '<td class="right" valign="top">'.$write['penalty'].'</td>';
                       echo '<td class="right" valign="top">'.$write['plusminus'].'</td>';
                       echo '</tr>';
                       }
                      }
                     }else{
                       echo '<tr><td>&nbsp;</td><td class="" colspan="8">No club stats found for season '.$strSeason.'</td></tr>';
                    }
                    
                  }
                  echo '</tbody></table>';
              }
            }
            
            
            echo '<h3>Goalie stats</h3>';
            showTable(1);
            echo '<h3>Players stats</h3>';
            showTable(2);
            
            $query="SELECT DISTINCT players.id as id_player,id_position,players.name,players.surname from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$intLeague." AND id_club=".$id." AND id_season=".$intSeason."  AND id_stats_type=2 ".$strWhere." ORDER BY players.id_position ASC,players.surname ASC";
          //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '<h3>Coach stats</h3>';
                  echo '<table class="basic stats" style="margin-top:0px">';
                  echo '
                  <thead>
                  <tr>
                    <th class="" valign="top">Coach name</th>
                    <th class="" valign="top">Team</th>
                    <th class="" valign="top">League</th>
                    <th class="" valign="top">Pos</th>
                    <th class="" valign="top">LPos</th>
                    <th class="" valign="top">W</th>
                    <th class="" valign="top">L</th>
                    <th class="" valign="top">D</th>
                    ';
                    
                  echo '
                  </tr>
                  </thead>
                  <tbody>
                  ';
                  $result_player = $con->SelectQuery($query);
                  $counter=0;
                  while($write_player = $result_player->fetch_array()){
                    $counter++;
                    $player_name=$write_player['name'].' '.$write_player['surname'];
                    $query_stats="SELECT id_season,games,goals,assist,penalty,plusminus,id_club,id_league,id_season_type,minutes,AVG,PCE,shotouts,(goals+assist) as points from stats INNER JOIN clubs ON stats.id_club=clubs.id WHERE id_player=".$write_player['id_player']." AND id_season=".$intSeason." AND is_national_team<>2 ORDER BY id_season DESC, id_season_type DESC, stats.id DESC";
                    if ($counter%2) $style=""; else $style="dark";
                    if ($con->GetQueryNum($query_stats)>0){
                      
                      $result = $con->SelectQuery($query_stats);
                      while($write = $result->fetch_array()){
                       echo '<tr class="'.$style.'">';
                       echo '<td class="player" valign="top" style="font-size:1.1em"><a href="/player/'.get_url_text($player_name,$write_player['id_player']).'" title="Show player profile: '.$player_name.' '.$player_surname.'">'.$write_player['name'].' <b>'.$write_player['surname'].'</b></a>';
                       $StrClubName=$games->GetActualClubName($write['id_club'],$write['id_season']);
                       $StrLeagueName=$games->GetActualLeagueName($write['id_league'],$write['id_season']);
                       $StrClubCountry=$con->GetSQLSingleResult("SELECT id_country as item FROM clubs WHERE id=".$write['id_club']);
                       $StrLeagueNameYouth=$games->GetYouthLeague($write['id_league']);
                       $strUrlSeasonType="?type=".$write['id_season_type']."&amp;league=".$write['id_league'];
                       if ($write['id_season_type']>1){
                        $strSeasonType=$con->GetSQLSingleResult("SELECT name as item FROM games_stages WHERE id=".$write['id_season_type']);
                       }else {$strSeasonType="";}
                       
                       echo '<td class="club" valign="top">';
                       echo get_flag(strtolower($StrClubCountry),15,11);
                       echo '<a href="/stats/club/'.$write['id_season'].'/'.get_url_text($StrClubName,$write['id_club']).$strUrlSeasonType.$strUrlGoalies.'" title="Show stats in selected season: '.$StrClubName.'">'.$StrClubName.'</a>';
                       echo '</td>';
                       echo '<td>';
                       echo '<a href="/stats/league/'.$write['id_season'].'/'.get_url_text($StrLeagueName,$write['id_league']).$strUrlSeasonType.$strUrlGoalies.'" title="Show stats in selected season: '.$StrLeagueName.'">'.$StrLeagueName.'</a>';
                       if (!empty($StrLeagueNameYouth) or !empty($strSeasonType)) echo ' <small>(';
                       if (!empty($StrLeagueNameYouth)) echo "".$StrLeagueNameYouth."";
                       if (!empty($strSeasonType)) {
                         if (!empty($StrLeagueNameYouth)) echo ', ';
                         echo "".$strSeasonType."";
                       }  
                       if (!empty($StrLeagueNameYouth) or !empty($strSeasonType)) echo ')</small>';  
                       echo '</td>';
                       $StrCoachPOs=$con->GetSQLSingleResult("SELECT name as item FROM players_coach_positions_list WHERE id=".$write['penalty']);
                       echo '<td class="" valign="top">'.$StrCoachPOs.'</td>';
                       if (!empty($write['minutes'])) $intMinutes=$write['minutes']."."; else $intMinutes="";
                       echo '<td class="center" valign="top">'.$intMinutes.'</td>';
                       echo '<td class="center" valign="top">'.$write['wins'].'</td>';
                       echo '<td class="center" valign="top">'.$write['losts'].'</td>';
                       echo '<td class="center" valign="top">'.$write['draws'].'</td>';
                      }
                     }else{
                      echo '<tr class="'.$style.'">';
                       echo '<td class="player" valign="top" style="font-size:1.1em"><a href="/player/'.get_url_text($player_name,$write_player['id_player']).'" title="Show player profile: '.$player_name.' '.$player_surname.'">'.$write_player['name'].' <b>'.$write_player['surname'].'</b></a></td>';
                       echo '<td class="left" colspan="9">No club stats found for season '.$strSeason.'</td></tr>';
                    }
                    
                  }
                  echo '</tbody></table>';
                 }
            
            
            
            
          
          }else{
            echo '<p>Please select club in <a href="/clubs.html">clubs list</a></p>';
          } 
         ?>  
         
         
        
         
         <!-- main text end -->
      </div>
      
      <div id="col_right">
        <?php require_once("inc/col_right_default.inc"); ?>
      </div>
      
      <div class="clear">&nbsp;</div>
      <div id="text_space">&nbsp;</div>
      <div id="headlines_box"><?php require_once("inc/text_bottom_headlines.inc"); ?></div>
      
  </div>
  <?php if ($BoolBottomInfo){ ?>
  <!-- info box bottom -->
  <div class="corners top"><div class="toleft corner">&nbsp;</div><div class="toright corner">&nbsp;</div></div>
  <div id="bottom_info"><?php require_once("inc/bottom_info.inc"); ?></div>
  <div class="corners bottom"><div class="toleft corner">&nbsp;</div><div class="toright corner">&nbsp;</div></div>
  <!-- info box bottom end -->
  <?php } ?>
  
  <!-- bottom -->
  <div id="bottom_links"><?php require_once("inc/bottom_links.inc"); ?></div>
  <div id="bottom"><?php require_once("inc/bottom.inc"); ?></div>
  
</div>

</body>
</html>