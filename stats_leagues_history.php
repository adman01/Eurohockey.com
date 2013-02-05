<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");

$id=$_GET['id'];
$id=$input->valid_text($id,true,true);
$id=get_ID_from_url($id);

if (!empty($_GET['position'])) {
  $intPosition=$input->check_number($_GET['position']);
}else{
  $intPosition=0;
}

if (!empty($_GET['type'])) {
  $intType=$input->check_number($_GET['type']);
}else{
  $intType=1;
}

if (!empty($_GET['stage'])) {
  $intStage=$input->check_number($_GET['stage']);
}else{
  $intStage=1;
}

switch ($intType){
  case 1:
    $strTypeName="Points leaders";
    $strOrder="sum(goals+assist) DESC, sum(goals) DESC";
    $strWhereNull=" AND (goals+assist)<>''";
  break;
  case 2:
    $strTypeName="Goals leaders";
    $strOrder="sum(goals) DESC,sum(games) DESC";
    $strWhereNull=" AND goals<>''";
  break;
  case 3:
    $strTypeName="Assists leaders";
    $strOrder="sum(assist) DESC";
    $strWhereNull=" AND assist<>''";
  break;
  case 4:
    $strTypeName="Penalty leaders";
    $strOrder="sum(penalty) DESC";
    $strWhereNull=" AND penalty<>''";
  break;
  case 5:
    $strTypeName="+/- leaders";
    $strOrder="sum(plusminus) DESC";
    $strWhereNull=" AND plusminus<>''";
  break;
  case 6:
    $strTypeName="Minutes leaders";
    $strOrder="sum(minutes) DESC";
    $strWhereNull=" AND minutes<>''";
  break;
  case 7:
    $strTypeName="Goals againts average leaders";
    $strOrder="AVG ASC";
    $strWhereNull=" AND AVG<>''";
  break;
  case 8:
    $strTypeName="Save percentage leaders";
    $strOrder="PCE DESC";
    $strWhereNull=" AND PCE<>''";
  break;
  case 9:
    $strTypeName="Shutouts leaders";
    $strOrder="shotouts DESC";
    $strWhereNull=" AND shotouts<>''";
  break;
}

if ($intType>=6) $intPosition=1; 


if (!empty($id)){

  $StrLeagueName=$games->GetActualLeagueName($id,ActSeasonWeb);
  if (!empty($StrLeagueName)){
    $boolLeague=true;
    $strHeader=$strTypeName." for league ".$StrLeagueName;
    $strHeaderDescription=$strTypeName." for league ".$StrLeagueName;
    $strHeaderKeywords=HeaderKeywords.",".$strTypeName.",individuals records,".$StrLeagueName.",".$strSeason;
  }else{
    $boolLeague=false;
    $strHeader="League not found";
    $strHeaderDescription="League not found, or selected";
  }
}else{
  $boolLeague=false;
  $strHeader="League not found";
  $strHeaderDescription="League not found, or selected";
}

require_once("inc/head.inc");
echo $head->setTitle($strHeader." - ".strProjectName);
echo $head->setEndHead();
?>
<body>

<div id="ads_top"><?php show_ads("top_main"); ?></div>
<div id="layout" class="longer">
  
  <?php require_once("inc/bar_login.inc"); ?>
  <div id="logo"><?php require_once("inc/logo.inc"); ?></div>
  <div id="menu"><?php require_once("inc/menu.inc"); ?></div>
  <div id="main">
      
      <div id="text">
  
         <!-- main text -->
         <?php
          if ($boolLeague){
          ?>
          <div id="navigation">
            <div class="toleft">
                <div class="corner_nav_left">&nbsp;</div>
                <div class="corner_nav_box"><a href="/leagues.html" title="Leagues">Leagues</a></div>
                <?php
                //countries
                  $query="SELECT id_country as item FROM leagues_countries_items WHERE id_league=".$id." ORDER by item";
                  $intCount=$con->GetQueryNum($query);
                  $counter=0;
                  if ($intCount>0){
                    echo '<div class="corner_nav_middle">&nbsp;</div>';
                    echo '<div class="corner_nav_box">';
                    $result = $con->SelectQuery($query);
                    while($write = $result->fetch_array()){
                      $NameCountry=$games->GetActualCountryName($write['item'],ActSeasonWeb);
                      echo '<a href="/country/'.get_url_text($NameCountry,$write['item']).'" title="'.$NameCountry.'">'.$NameCountry.'</a>';
                      $counter++;
                      if ($counter<$intCount){ echo ' / ';}
                    }
                    echo '</div>';
                  }
                ?>
                <div class="corner_nav_middle">&nbsp;</div>
                <div class="corner_nav_box"><?php echo '<a href="/league/'.get_url_text($StrLeagueName,$id).'" title="'.$StrLeagueName.'">'.$StrLeagueName.'</a>'; ?></div>
                <div class="corner_nav_right">&nbsp;</div>
                
            </div>
            <div class="toright link bold"><span class="link"><a href="/leagues.html" title="List of leagues">select another league</a></span></div>
         </div>
         
         <div class="space">&nbsp;</div>
         
         <h1>Individuals records</h1>
         <h2><?php echo $strHeader; ?></h2>
         
         <?php 
         $query="SELECT DISTINCT id_season FROM stats WHERE id_league=".$id." AND id_stats_type=1 ORDER BY id_season DESC";
         $intCount=$con->GetQueryNum($query);
         if ($intCount>0){
            $boolLeague=true;
         }else{
           $boolLeague=false;
         }
         if ($boolLeague==true){
         ?>
         
          <div class="header box_normal">&nbsp;</div>
           <div class="box normal" id="stats_search_form">
              <form action="" method="get">
              
              <div class="toleft">
                
              <b>Stats type:</b>
                <select name="type" class="type_history">
                 <?php 
                   echo '<option value="1" '.write_select(1,$intType).'>Points leaders</option>';
                   echo '<option value="2" '.write_select(2,$intType).'>Goals leaders</option>';
                   echo '<option value="3" '.write_select(3,$intType).'>Assists leaders</option>';
                   echo '<option value="4" '.write_select(4,$intType).'>Penalty leaders</option>';
                   echo '<option value="5" '.write_select(5,$intType).'>+/- leaders</option>';
                   echo '<option value="0">------------------------------------------</option>';
                   echo '<option value="6" '.write_select(6,$intType).'>Minutes leaders</option>';
                   echo '<option value="7" '.write_select(7,$intType).'>Goals againts average leaders</option>';
                   echo '<option value="8" '.write_select(8,$intType).'>Save percentage</option>';
                   echo '<option value="9" '.write_select(9,$intType).'>Shutouts leaders</option>';
                ?>
              </select>
              
             <b>Stage:</b>
                <select name="stage" class="type">
                 <?php 
                 //seasons
                $query="SELECT DISTINCT id_season_type FROM stats WHERE id_league=".$id." AND id_stats_type=1 ORDER BY id_season_type ASC";
                $intCount=$con->GetQueryNum($query);
                if ($intCount>0){
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    $strStageName=$con->GetSQLSingleResult("SELECT name as item FROM games_stages WHERE id=".$write['id_season_type']);
                    if (!empty($strStageName)) echo '<option value="'.$write['id_season_type'].'" '.write_select($write['id_season_type'],$intStage).'>'.$strStageName.'</option>';
                  }
                }
                ?>
              </select>
             
             <b>Position:</b>
                <select name="position" class="position">
                 <?php 
                 //seasons
                $query="SELECT name,id FROM players_positions_list WHERE id<>12 ORDER BY id ASC";
                $intCount=$con->GetQueryNum($query);
                if ($intCount>0){
                  echo '<option value="0" '.write_select($write['id'],$intPosition).'>all skaters</option>';
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    echo '<option value="'.$write['id'].'" '.write_select($write['id'],$intPosition).'>'.$write['name'].'</option>';
                    if ($write['id']==1)echo '<option value="0">---------------------</option>';
                  }
                }
                ?>
              </select>
                
              </div>
              <div class="clear">&nbsp;</div>
              <span class="stats_submit extra"><input type="submit" class="submit" value="" /></span>
              <div class="clear">&nbsp;</div>
              </form>
           </div>
              
         <?
         
          if (!empty($intPosition)){
            
            switch ($intPosition){
              case 4:
                $sqlWhere.=" AND (players.id_position=4 OR players.id_position=2 OR players.id_position=3 OR players.id_position=10)";
              break;
              case 9:
                $sqlWhere.=" AND (players.id_position=9 OR players.id_position=5 OR players.id_position=6 OR players.id_position=8 OR players.id_position=10)";
              break;
              default:
                $sqlWhere.=" AND players.id_position=".$intPosition;
              }
            
            $strVariables.='position='.$intPosition.'&amp;';
          }
          if (!empty($intStage)){
            $sqlWhere.=" AND stats.id_season_type=".$intStage;
             $strVariables.='type='.$intStage.'&amp;';
          }else{
            $sqlWhere.=" AND stats.id_season_type=1";
          }
          
          if ($intPosition<>1){
            $sqlWhere.=" AND  players.id_position<>1";
          }else{
            $sqlWhere.=" AND  players.id_position=1";
          }
          
          $query_season="SELECT id_season FROM stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id." AND id_stats_type=".$intStage." ".$sqlWhere." GROUP BY id_season ORDER BY id_season DESC";
          //echo $query_season; 
          if ($con->GetQueryNum($query_season)>0){
          
          echo '<table class="basic stats_main">';
                  echo '
                  <thead>
                  <tr>
                    <th class="" valign="top">Season</th>
                    <th class="" valign="top">Player name</th>
                    ';
                    if ($intPosition<>1){
                    //players
                    echo'
                    <th class="" valign="top">Pos</th>
                    <th class="" valign="top">Team</th>
                    <th class="" valign="top">GP</th>
                    <th class="" valign="top">G</th>
                    <th class="" valign="top">A</th>
                    <th class="" valign="top">P</th>
                    <th class="" valign="top">PIM</th>
                    <th class="" valign="top">+/-</th>
                    ';
                    }else{
                    //goalies
                    echo'
                    <th class="" valign="top">Team</th>
                    <th class="" valign="top">GP</th>
                    <th class="" valign="top">MIN</th>
                    <th class="" valign="top">AVG</th>
                    <th class="" valign="top">PER</th>
                    <th class="" valign="top">SO</th>
                    <th class="" valign="top">G</th>
                    <th class="" valign="top">A</th>
                    <th class="" valign="top">PIM</th>
                    ';
                    }
                  echo '
                  </tr>
                  </thead>
                  <tbody>
                  ';
                  
          $counter=0;
                  
          $result_season = $con->SelectQuery($query_season);
          while($write_season = $result_season->fetch_array()){
          
          $query="SELECT
          (SELECT id_club FROM stats s WHERE s.id_league=".$id." AND s.id_season=".$write_season['id_season']." AND s.id_season_type=".$intStage." AND s.id_player=stats.id_player ORDER by s.id DESC LIMIT 1) as id_club,
           players.id as id_player,id_position,sum(games) as games,sum(goals) as goals,sum(assist) as assist,sum(penalty) as penalty";
           if ($intPosition==1) {$query.=",sum(minutes) as minutes,AVG,PCE,sum(shotouts) as shotouts";}
           else{
            $query.=",sum(plusminus) as plusminus,(goals+assist) as points";
           }
           $query.=" FROM stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id." AND id_season=".$write_season['id_season']." AND id_stats_type=1 ".$sqlWhere." ".$strWhereNull." GROUP BY id_player HAVING id_club  ORDER BY ".$strOrder." LIMIT 1";
           //echo $query.'<br /><br />';
              if ($con->GetQueryNum($query)>0){
                      
                   
                  $result = $con->SelectQuery($query);
                  $write = $result->fetch_array();
                    $counter++;
                    if ($counter%2) $style=""; else $style="dark";
  		              echo '<tr class="'.$style.'">';
  		                echo '<td class="left" valign="top"><a href="/stats/league/'.$write_season['id_season'].'/'.get_url_text($StrLeagueName,$id).'?type='.$intStage.'&amp;position=1" title="Show league statistics: '.$StrLeagueName.'">'.($write_season['id_season']-1).'-'.($write_season['id_season']).'</a></td>';
                      echo '<td class="player" valign="top">';
                      $player_nationality=$con->GetSQLSingleResult("SELECT nationality as item FROM players WHERE id=".$write['id_player']);
                      echo get_flag(strtolower($player_nationality),15,11);
                      $player_name=$con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$write['id_player']);
                      echo '<a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player statistics: '.$player_name.'">'.$player_name.'</a>';
                      echo '</td>';
                       
                       if ($intPosition<>1){
                       //players
                       $StrPosition=$con->GetSQLSingleResult("SELECT shortcut as item FROM players_positions_list WHERE id=".$write['id_position']);
                      if (empty($StrPosition)) $StrPosition="-";
                      echo '<td class="position" valign="top">'.$StrPosition.'</td>';                   
                       $StrClubName=$games->GetActualClubName($write['id_club'],$write_season['id_season']);
                       echo '<td class="club" valign="top"><a href="/stats/club/'.$write_season['id_season'].'/'.get_url_text($StrClubName,$write['id_club']).'?type='.$intStage.'" title="Show club statistics: '.$StrClubName.'">'.$StrClubName.'</a></td>';
                       echo '<td class="" valign="top">'.$write['games'].'</td>';
                      
                       
                       echo '<td '; if($intType==2) echo 'class=" bold"'; echo' valign="top">'.$write['goals'].'</td>';
                       echo '<td '; if($intType==3) echo 'class=" bold"'; echo' valign="top">'.$write['assist'].'</td>';
                       echo '<td '; if($intType==1) echo 'class=" bold"'; echo' valign="top">'.($write['points']).'</td>';
                       echo '<td '; if($intType==4) echo 'class=" bold"'; echo' valign="top">'.$write['penalty'].'</td>';
                       echo '<td '; if($intType==5) echo 'class=" bold"'; echo' valign="top">'.$write['plusminus'].'</td>';
                       }else{
                       //goalies
                       $StrPosition=$con->GetSQLSingleResult("SELECT shortcut as item FROM players_positions_list WHERE id=".$write['id_position']);
                      if (empty($StrPosition)) $StrPosition="-";
                       $StrClubName=$games->GetActualClubName($write['id_club'],$write_season['id_season']);
                       echo '<td class="club" valign="top"><a href="/stats/club/'.$write_season['id_season'].'/'.get_url_text($StrClubName,$write['id_club']).'&amp;position='.$intStage.'" title="Show club statistics: '.$StrClubName.'">'.$StrClubName.'</a></td>';
                       echo '<td class="" valign="top">'.$write['games'].'</td>';
                      
                       echo '<td '; if($intType==6) echo 'class=" bold"'; echo' valign="top">'.$write['minutes'].'</td>';
                       echo '<td '; if($intType==7) echo 'class=" bold"'; echo' valign="top">'.$write['AVG'].'</td>';
                       if ($write['PCE']==1 or $write['PCE']==100) $strPercent="1.000"; else $strPercent=$write['PCE'];
                       echo '<td '; if($intType==8) echo 'class=" bold"'; echo' valign="top">'.$strPercent.'</td>';
                       echo '<td '; if($intType==9) echo 'class=" bold"'; echo' valign="top">'.$write['shotouts'].'</td>';
                       echo '<td '; if($intType==2) echo 'class=" bold"'; echo' valign="top">'.$write['goals'].'</td>';
                       echo '<td '; if($intType==3) echo 'class=" bold"'; echo' valign="top">'.$write['assist'].'</td>';
                       echo '<td '; if($intType==4) echo 'class=" bold"'; echo' valign="top">'.$write['penalty'].'</td>';
                       }
                      
                    echo '</tr>';
                  
                  
                  
              }
              
           
           }
           
           echo '</tbody>';
           echo '</table>';
           
           }else{
            echo '<p class="center">No stats found for specified criteria.</p>';
          }
          
          
          
           }else{
            echo '<p class="center">No stats found for specified criteria.</p>';
          } 
          
          }else{
            echo '<p class="center">Please select league in <a href="/leagues.html">leagues list</a></p>';
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