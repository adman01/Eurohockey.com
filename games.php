<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");

$id=$_GET['id'];
$id=$input->valid_text($id,true,true);
$id=get_ID_from_url($id);
if (empty($id)){header("Location: /text/404-error.html");}

$query="SELECT * from games WHERE id=".$id;
if ($con->GetQueryNum($query)>0){
    $result = $con->SelectQuery($query);
    $write = $result->fetch_array();                                                                              
    
    
    $id_season=$write["id_season"];
    $intIDclub_home=$write["id_club_home"];
    $intIDclub_visiting=$write["id_club_visiting"];
    $strClub_home=$games->GetActualClubName($intIDclub_home,$id_season);
    $strClub_visiting=$games->GetActualClubName($intIDclub_visiting,$id_season);
    $strDate=date("d M Y",strtotime($write["date"]));
    $strTime=date("H:i",strtotime($write["time"]));
    $id_league=$write["id_league"];
    $strLeague=$games->GetActualLeagueName($id_league,$id_season);
    $id_stage=$write["id_stage"];
    $strStage=$con->GetSQLSingleResult("SELECT name as item FROM games_stages WHERE id=".$id_stage);
    
    $intIDstageDefault=$con->GetSQLSingleResult("SELECT id_detault as item FROM games_stages WHERE id=".$id_stage);
    if (!empty($intIDstageDefault)) {$id_stage=$intIDstageDefault;} 
    
    $intIDarena=$write["id_arena"];
    $strArena=$games->GetActualArenaName($intIDarena,$id_season);
    $strRound=$write["round"];
    $intHome_score=trim($write["home_score"]);
    if (is_null($intHome_score) or $intHome_score=="") $intHome_score="";
    $intVisiting_score=trim($write["visiting_score"]);
    if (is_null($intVisiting_score) or $intVisiting_score=="") $intVisiting_score="";
    $intGames_status=$write["games_status"];
    $strGames_statusShort=$con->GetSQLSingleResult("SELECT shortcut as item FROM games_status_list WHERE id=".$intGames_status);
    $strReferees=$write["referees"];
    if (empty($strReferees)) $strReferees="-";
    $intSpectators=$write["spectators"];
    if (empty($intSpectators)) $intSpectators="-";
    
    $id_country=$con->GetSQLSingleResult("SELECT id_country as item FROM leagues_countries_items WHERE id_league=".$id_league);
    $strCountry=$games->GetActualCountryName($id_country,$id_season);;
    
    $StrName=$strClub_home.' - '.$strClub_visiting;
    
    $url='/game/detail/'.get_url_text($name,$write['id']);
     	  	
	  
}else{
    header("Location: /text/404-error.html");
}

$strHeaderKeywords='game details, '.$strDate.', '.$StrName.', '.$strLeague.', '.$strStage.'';
$strHeaderDescription='Game detail: '.$strDate.', '.$StrName.'';
require_once("inc/head.inc");
echo $head->setTitle($strHeaderDescription." - ".strProjectName);
?>
<script type="text/javascript" src="/inc/jcarousel/jquery.jcarousel.min.js"></script>
<link rel="stylesheet" type="text/css" href="/inc/jcarousel/skins/tango/skin_longer.css" />
<script type="text/javascript">
$(function () {
    $('#mycarousel').jcarousel();
});
</script>
<?php
require_once("inc/header_pirobox.inc");
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
         
         <div id="navigation">
            <div class="toleft">
                <div class="corner_nav_left">&nbsp;</div>
                <div class="corner_nav_box"><a href="/games.html?id_season=<?php echo $id_season; ?>" title="Season"><?php echo ($id_season-1).'-'.$id_season; ?></a></div>
                <div class="corner_nav_middle">&nbsp;</div>
                <div class="corner_nav_box"><a href="/games.html?id_season=<?php echo $id_season; ?>&amp;id_country=<?php echo $id_country; ?>" title="Country"><?php echo $strCountry; ?></a></div>
                <div class="corner_nav_middle">&nbsp;</div>
                <div class="corner_nav_box"><a href="/games.html?id_season=<?php echo $id_season; ?>&amp;id_country=<?php echo $id_country; ?>&amp;id_league=<?php echo get_url_text($strLeague,$id_league); ?>" title="League"><?php echo $strLeague; ?></a></div>
                <div class="corner_nav_middle">&nbsp;</div>
                <div class="corner_nav_box">
                  <a href="/games.html?id_season=<?php echo $id_season; ?>&amp;id_country=<?php echo $id_country; ?>&amp;id_league=<?php echo get_url_text($strLeague,$id_league); ?>&amp;id_club=<?php echo get_url_text($strClub_home,$intIDclub_home); ?>" title="Club"><?php echo $strClub_home; ?></a>
                  &nbsp;-&nbsp; 
                  <a href="/games.html?id_season=<?php echo $id_season; ?>&amp;id_country=<?php echo $id_country; ?>&amp;id_league=<?php echo get_url_text($strLeague,$id_league); ?>&amp;id_club=<?php echo get_url_text($strClub_visiting,$intIDclub_visiting); ?>" title="Club"><?php echo $strClub_visiting; ?></a>
                </div>
                <div class="corner_nav_right">&nbsp;</div>
                
            </div>
         </div>
         
         <div class="space">&nbsp;</div>
         <?php
         
         //-------------SCORE INFO
         echo '
         <table class="basic game_score">
              <tr class="first noborder">
                <td colspan="3" class="center bold"><a href="/games.html?id_season='.$id_season.'&amp;id_country='.$id_country.'&amp;id_league='.get_url_text($strLeague,$id_league).'&amp;id_league='.get_url_text($strLeague,$id_league).'&amp;date_from='.$strDate.'&amp;date_to='.$strDate.'&amp;id_show=2" title="Games for date '.$strDate.'">'.$strDate.'</a>';
                if ($strTime<>"00:00") echo ', '.$strTime;
                if (!empty($strStage)) echo "<b>&nbsp;&nbsp;|&nbsp;&nbsp;</b>".$strStage;
                if (!empty($strRound)) echo "<b>&nbsp;&nbsp;|&nbsp;&nbsp;</b>".$strRound;
                if (!empty($strArena)) echo '<b>&nbsp;&nbsp;|&nbsp;&nbsp;</b><a href="/arena/'.get_url_text($strArena,$intIDarena).'" title="Show arena details">'.$strArena.'</a>';
                
                
                echo '</td>
              </tr>
              <tr class="first noborder dark">
                <td class="team right">'.$strClub_home.'</td><td class="score">'.$intHome_score.'-'.$intVisiting_score.' '.$strGames_statusShort.'</td><td class="team">'.$strClub_visiting.'</td>
              </tr>
              <tr class="first noborder">
                <td colspan="3">';
                $query="SELECT * FROM games_periods WHERE id_game=".$id." ORDER by periods_order ASC, id ASC";
                $intPocet=$con->GetQueryNum($query);
                if ($intPocet==0){
                  //echo  '<div class="center">No periods found.</div>';
                }else{
                  $result = $con->SelectQuery($query);
                  echo '<table class="periods" style="width:'.(($intPocet+1)*70).'px"><tr>';
                  echo '<td class="label right"><b>Periods:</b><br /><b>Shots:</b></td>';
                  $strShootsHomeTotal=0;
                  $strShootsVisitingTotal=0;
                  while($write = $result->fetch_array())
	                {
	                   echo '<td class="center">';
                     
                     if (is_null($write['score_home'])) $strScoreHome=""; else $strScoreHome=$write['score_home'];
	                   if (is_null($write['score_visiting'])) $strScoreVisiting=""; else $strScoreVisiting=$write['score_visiting'];
	                   if (is_null($write['shoots_home'])) $strShootsHome=""; else {
                        $strShootsHome=$write['shoots_home'];
                        $strShootsHomeTotal=$strShootsHomeTotal+$write['shoots_home'];
                     }
	                   if (is_null($write['shoots_visiting'])) $strShootsVisiting=""; else {
                        $strShootsVisiting=$write['shoots_visiting'];
                        $strShootsVisitingTotal=$strShootsVisitingTotal+$write['shoots_visiting'];
                     }
	                   if ($strScoreHome=="" and $strScoreVisiting=="") echo '-'; else  echo $strScoreHome.'-'.$strScoreVisiting;
	                   echo '<br />';
	                   if ($strShootsHome=="" and $strShootsVisiting=="") echo '( - )'; else  echo '('.$strShootsHome.'-'.$strShootsVisiting.')';
                     
                     echo '</td>';
                  } 
                  if (!empty($strShootsHomeTotal) and !empty($strShootsVisitingTotal)) echo '<td class="center nowrap">&nbsp;<br /><b>Total:</b> '.$strShootsHomeTotal.'-'.$strShootsVisitingTotal.'</td>';
                  echo '</tr>';
                   
                  
                  echo '
                  </table>';
                } 
                echo'
                
                
                </td>
              </tr>
              <tr class="first noborder dark">
                <td colspan="3" class="center"><b>Referees:</b> '.$strReferees.'&nbsp&nbsp|&nbsp&nbsp<b>Spectators</b>: '.$intSpectators.'</td>
              </tr>
              <tr class="first noborder">
                <td colspan="3" class="center clear">&nbsp</td>
              </tr>
         </table>
         ';
         
         
         
         
         //-------------GOALS INFO
         $query="SELECT * FROM games_goals WHERE id_game=".$id." ORDER by goal_time_min ASC,goal_time_sec ASC, id ASC";
          //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '<div class="header goals_and_assists"><span>Goals and assists</span></div>';
                  echo '<table class="tablesorter basic score_board">
                  <tbody>
                  ';
                  $result = $con->SelectQuery($query);
                  $counter=0;
                  $intHome=0;
                  $intAway=0;
                  while($write = $result->fetch_array()){
                    $counter++;
                    //if ($counter%2) $style=""; else $style="dark";
  		              echo '<tr>';
  		                if ($write['goal_time_min']<10) $strMinGoal='0'.$write['goal_time_min']; else $strMinGoal=$write['goal_time_min'];
  		                if ($write['goal_time_sec']<10) $strSecGoal='0'.$write['goal_time_sec']; else $strSecGoal=$write['goal_time_sec'];
                      echo '<td class="time" valign="top">'.$strMinGoal.':'.$strSecGoal.'</td>';
                      echo '<td class="slash" valign="top">|</td>';   
                      if ($write['id_club']==$intIDclub_home) {
                        $strTeam="home";
                        $intHome++;
                      } else {
                        $strTeam="away";
                        $intAway++;
                      }
                      echo '<td class="team" valign="top">'.$strTeam.'</td>';
                      echo '<td class="slash" valign="top">|</td>';
                      $strGoalType=$con->GetSQLSingleResult("SELECT shortcut as item FROM games_goals_types WHERE id=".$write['id_goal_type']);
                      echo '<td class="type" valign="top">'.$strGoalType.'</td>';
                      echo '<td class="slash" valign="top">|</td>';   
                      echo '<td class="score" valign="top">'.$intHome.'-'.$intAway.'</td>';
                      echo '<td class="slash" valign="top">|</td>';                      
                      echo '<td class="goal" valign="top">';
                      $player_name=$con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$write['scorer']);
                      $player_country=$con->GetSQLSingleResult("SELECT nationality  as item FROM players WHERE id=".$write['scorer']);
                      echo get_flag(strtolower($player_country),15,11);
                      echo '<a href="/player/'.get_url_text($player_name,$write['scorer']).'" title="Show player profile: '.$player_name.'">'.$player_name.'</a>';
                      if (!empty($write['assist_1']) or !empty($write['assist_2'])){
                          echo ' (';
                          if (!empty($write['assist_1'])) {
                              $player_name=$con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$write['assist_1']);
                              echo '<a href="/player/'.get_url_text($player_name,$write['assist_1']).'" title="Show player profile: '.$player_name.'">'.$player_name.'</a>';
                          }
                          if (!empty($write['assist_2'])) {
                              if (!empty($write['assist_1'])) echo ', ';
                              $player_name=$con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$write['assist_2']);
                              echo '<a href="/player/'.get_url_text($player_name,$write['assist_2']).'" title="Show player profile: '.$player_name.'">'.$player_name.'</a>';
                          }
                          echo ')';     	
                      }
                      echo '</td>';
                      
                     
                    echo '</tr>';
                  }
                  echo '</tbody></table>';
              }
              
              
          //-------------Players - detailed stats
          
          
          function show_player_stats($query,$player_type){
                      global $con;
                      echo '<table class="basic players_detail_stats">';
                      echo'<thead>';
                      echo'<tr>';
                          if($player_type==0){
                          echo'
                            <th>Name</th>
                            <th class="slash"></th>
                            <th class="center">G</th>
                            <th class="slash"></th>
                            <th class="center">A</th>
                            <th class="slash"></th>
                            <th class="center">PIM</th>
                            <th class="slash"></th>
                            <th class="center">+/-</th>
                          ';
                          }else{
                          echo'
                            <th>Name</th>
                            <th class="slash"></th>
                            <th class="center">SA</th>
                            <th class="slash"></th>
                            <th class="center">GA</th>
                            <th class="slash"></th>
                            <th class="center">MIN</th>
                            <th class="slash"></th>
                            <th class="center">%</th>
                          ';
                          }
                      echo'</tr>';
                      echo'</thead>';
                      echo'<tbody>';
                      
                      $result = $con->SelectQuery($query);
                      $counter=0;
                      if ($con->GetQueryNum($query)>0){
                      while($write = $result->fetch_array()){
                        $counter++;
                        if ($counter%2) $style=""; else $style="dark";
  		                  echo '<tr class="'.$style.'">';
  		                      $player_name=$con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$write['id_player']);
                            $player_country=$con->GetSQLSingleResult("SELECT nationality  as item FROM players WHERE id=".$write['id_player']);
                            echo '<td class="name" valign="top">';
                            echo get_flag(strtolower($player_country),15,11);
                            echo '<a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player profile: '.$player_name.'">'.$player_name.'</a></td>';
                            echo '<td class="slash" valign="top">|</td>'; 
                            if($player_type==0){
                            echo '<td class="statistic" valign="top">'.$write['goals'].'</td>';
                            echo '<td class="slash" valign="top">|</td>'; 
                            echo '<td class="statistic" valign="top">'.$write['assist'].'</td>';
                            echo '<td class="slash" valign="top">|</td>';
                            echo '<td class="statistic" valign="top">'.$write['penalty'].'</td>';
                            echo '<td class="slash" valign="top">|</td>';
                            echo '<td class="statistic" valign="top">'.$write['plusminus'].'</td>';
                            }else{
                            echo '<td class="statistic" valign="top">'.$write['shoots_against'].'</td>';
                            echo '<td class="slash" valign="top">|</td>';
                            echo '<td class="statistic" valign="top">'.$write['goals_against'].'</td>';
                            echo '<td class="slash" valign="top">|</td>';
                            echo '<td class="statistic" valign="top">'.$write['minutes'].'</td>';
                            echo '<td class="slash" valign="top">|</td>';
                            if (!empty($write['shoots_against']) and !empty($write['goals_against'])){
                              if ($write['shoots_against']-$write['goals_against']>0){ 
                                $percent=100/($write['shoots_against']/($write['shoots_against']-$write['goals_against']));
                              }else{
                                $percent=0;
                              }
                              
                            }else{
                               if ($write['goals_against']==0 and $write['shoots_against']>0) {
                                    $percent="100.0";
                                } 
                                else {
                                     $percent=0;
                                }
                            }
                            echo '<td class="statistic" valign="top">';
                            if (!empty($percent)) echo number_format($percent, 2, '.', '');
                            echo '</td>';  
                            }
                        echo '</tr>';
                      }
                      }else{
                      echo  '<tr><td colspan="8" class="center">no stats found</td></tr>';
                      }            	 	
                      echo '</tbody></table>';
                    
                  }
          
         $query_home="SELECT DISTINCT players.id as id_player,games_stats.id as id,id_club,name,surname,id_position,goals,assist,plusminus,penalty,id_game from games_stats INNER JOIN players ON games_stats.id_player=players.id WHERE games_stats.id_game=".$id." AND games_stats.id_club=".$intIDclub_home." AND id_position<>1 ORDER BY players.surname ASC,players.name ASC";
         $intCountHome=$con->GetQueryNum($query_home);
         $query_away="SELECT DISTINCT players.id as id_player,games_stats.id as id,id_club,name,surname,id_position,goals,assist,plusminus,penalty,id_game from games_stats INNER JOIN players ON games_stats.id_player=players.id WHERE games_stats.id_game=".$id." AND games_stats.id_club=".$intIDclub_visiting." AND id_position<>1 ORDER BY players.surname ASC,players.name ASC";
         $intCountAway=$con->GetQueryNum($query_away);
          //echo $query; 
              if ($intCountHome>0 or $intCountAway>0){
                  echo '<div class="header player_statistics"><span>Player statistics</span></div>';
                  
                    echo '<div class="team_header toleft"><a href="/stats/club/'.$id_season.'/'.get_url_text($strClub_home,$intIDclub_home).'?type='.$id_stage.'&amp;league='.$id_league.'" title="Show season stats for club '.$strClub_home.'">'.$strClub_home.'</a></div>';
                    echo '<div class="team_header toright"><a href="/stats/club/'.$id_season.'/'.get_url_text($strClub_visiting,$intIDclub_visiting).'?type='.$id_stage.'&amp;league='.$id_league.'" title="Show season stats for club '.$strClub_visiting.'">'.$strClub_visiting.'</a></div>';
                    echo '<div class="clear">&nbsp;</div>';
                    echo '<div class="team_stats">';
                        
                        echo '<div class="team_stats_table toleft">';
                        show_player_stats($query_home,0);
                        echo '</div>';
                        
                        echo '<div class="team_stats_table toright">';
                        show_player_stats($query_away,0);
                        echo '</div>';
                        echo '<div class="clear">&nbsp;</div>';
                    echo '</div>';
               }
          $query_home="SELECT DISTINCT players.id as id_player,games_stats.id as id,id_club,name,surname,id_position,goals,assist,shoots_against,goals_against,penalty,minutes,games_dressed,id_game from games_stats INNER JOIN players ON games_stats.id_player=players.id WHERE games_stats.id_game=".$id." AND games_stats.id_club=".$intIDclub_home." AND id_position=1 ORDER BY players.surname ASC,players.name ASC";
          $intCountHome=$con->GetQueryNum($query_home);
          $query_away="SELECT DISTINCT players.id as id_player,games_stats.id as id,id_club,name,surname,id_position,goals,assist,shoots_against,goals_against,penalty,minutes,games_dressed,id_game from games_stats INNER JOIN players ON games_stats.id_player=players.id WHERE games_stats.id_game=".$id." AND games_stats.id_club=".$intIDclub_visiting." AND id_position=1 ORDER BY players.surname ASC,players.name ASC";
          $intCountAway=$con->GetQueryNum($query_away);
               if ($intCountHome>0 or $intCountAway>0){
                   echo '<div class="space">&nbsp;</div>';
                  echo '<div class="header goalie_statistics"><span>Goalie statistics</span></div>';
                  
                    echo '<div class="team_header toleft"><a href="/stats/club/'.$id_season.'/'.get_url_text($strClub_home,$intIDclub_home).'?type='.$id_stage.'&amp;position=1&amp;league='.$id_league.'" title="Show season stats for club '.$strClub_home.'">'.$strClub_home.'</a></div>';
                    echo '<div class="team_header toright"><a href="/stats/club/'.$id_season.'/'.get_url_text($strClub_visiting,$intIDclub_visiting).'?type='.$id_stage.'&amp;position=1&amp;league='.$id_league.'" title="Show season stats for club '.$strClub_visiting.'">'.$strClub_visiting.'</a></div>';
                    echo '<div class="clear">&nbsp;</div>';
                    echo '<div class="team_stats">';
                        
                        echo '<div class="team_stats_table toleft">';
                        show_player_stats($query_home,1);
                        echo '</div>';
                        
                        echo '<div class="team_stats_table toright">';
                        show_player_stats($query_away,1);
                        echo '</div>';
                        echo '<div class="clear">&nbsp;</div>';
                    echo '</div>';
               }
          
          
          
         $info=$con->GetSQLSingleResult("SELECT text as item FROM games_detail WHERE id_game=".$id); 
         if (!empty($info)){
         echo '<div class="space">&nbsp;</div>';
         echo '<div class="header game_recap"><span>Game recap</span></div>';
         echo $info;
         } 
         
         //photo
           $query="SELECT photo.id,photo.file_name,photo.description FROM photo_folder_assign INNER JOIN photo_folder ON photo_folder.id=photo_folder_assign.id_folder INNER JOIN photo ON photo_folder.id=photo.id_photo_folder
                   WHERE 
                     photo_folder_assign.id_item_type=2 AND photo_folder_assign.id_item=".$id."  AND photo_folder_assign.int_year =".$id_season."  
                   ORDER BY photo.id DESC";
              //echo $query;
            if ($con->GetQueryNum($query)>0){
              
               echo '<div class="space">&nbsp;</div>'; 
              echo '<div class="header_cufon blue_590"><span class="header_text blue">Photos</span></div>';
              echo '
               
               <div id="photo_slider" class="box">
               <ul id="mycarousel" class="jcarousel-skin-tango">';
                   $result = $con->SelectQuery($query);
                   while($write = $result->fetch_array()){
                      
                      $strPhotoPath=get_photo_name($write['id']);
	                     if (file_exists(PhotoFolder."/".$strPhotoPath)) {
	                 
	                       echo '<li><a href="/'.PhotoFolder."/".$strPhotoPath.'" title="'.$write['description'].'" class="pirobox_gall">
	                             <span style="background-image: url(/image/150-150-1-'.str_replace("-","_",$strPhotoPath).');" class="image">&nbsp;</span>
                          </a></li>';
                     
	                     }
                   }
                   
              echo '</ul>';
              echo '</div>';
              }
            
         
         ?>
         
         <?php echo socials(0); ?>
    
    <div class="space">&nbsp;</div>
         <?php
         
         
         
         
              $query="SELECT articles.id,articles.header,date_time FROM articles
                   INNER JOIN articles_items ON articles.id=articles_items.id_article  
                   WHERE 
                      articles.show_item=1 AND articles.pub_date<=NOW() AND articles.pub_date<=NOW() AND (expire_date>=NOW() OR expire_date='0000-00-00 00:00:00')
                      AND articles_items.id_item_type=5 AND articles_items.id_item=".$id."  
                   order by articles.date_time DESC  LIMIT 8";
              
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '
                  <div class="header relevant_articles"><span>Relevant articles</span></div>
                  <div id="article" class="box_recent_articles blue_boxes">
                  ';
                  echo '<ul>';
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    echo '<li>'; 
                      echo '<h2><a href="/article/'.get_url_text($write['header'],$write['id']).'" title="Read article: '.$write['header'].'">'.$write['header'].'</a></h2>';
                      
                  $query_related="SELECT * FROM articles_items WHERE id_article=".$write['id']." order by id_item_type ASC";
                  $intCountRelated=$con->GetQueryNum($query_related);
                  if ($intCountRelated>0){
                  $result_related = $con->SelectQuery($query_related);
                  $counter=0;
                   echo '<div class="clear">&nbsp;</div>'; 
                  echo '<div class="related_info_oval">';
                    while($write_related = $result_related->fetch_array()){
                      
                      switch ($write_related['id_item_type']){
                         case 1:
                            $name=$games->GetActualCountryName($write_related['id_item'],ActSeasonWeb);
                            $name='<a href="/country/'.get_url_text($name,$write_related['id_item']).'" title="Show country '.$name.'">'.$name.'</a>';
                         break;
                         case 2:
                            $name=$games->GetActualLeagueName($write_related['id_item'],ActSeasonWeb);
                            $name='<a href="/league/'.get_url_text($name,$write_related['id_item']).'" title="Show league '.$name.'">'.$name.'</a>';
                         break;
                         case 3:
                            $name=$games->GetActualClubName($write_related['id_item'],ActSeasonWeb);
                            $name='<a href="/club/'.get_url_text($name,$write_related['id_item']).'" title="Show club '.$name.'">'.$name.'</a>';
                         break;
                         case 4:
                            $query_sub="SELECT name,surname FROM players WHERE id>0 AND id=".$write_related['id_item'];
                            $result_sub = $con->SelectQuery($query_sub);
                            $write_sub = $result_sub->fetch_array();
                            $name=$write_sub['surname'].' '.$write_sub['name'];
                            $name='<a href="/player/'.get_url_text($name,$write_related['id_item']).'" title="Show player '.$name.'">'.$name.'</a>';
                        break;
                        case 5:
                            $query_sub="SELECT id,id_club_home,id_club_visiting,date,home_score,visiting_score FROM games WHERE id>0 AND id=".$write_related['id_item'];
                            $result_sub = $con->SelectQuery($query_sub);
                            $write_sub = $result_sub->fetch_array();
                            $strHome=$games->GetActualClubName($write_sub['id_club_home'],ActSeasonWeb);
	                          $strVisiting=$games->GetActualClubName($write_sub['id_club_visiting'],ActSeasonWeb);
	                          $strScore=$games->GetScore($write_sub['id'],1);
	                          $name=''.$strHome.' - '.$strVisiting.'';
                            $name='<a href="/game/detail/'.get_url_text($name,$write_related['id_item']).'" title="Show game detail '.$name.'">'.$name.' ('.$strScore.')</a>';
                        break;
                      }
                      
                      echo $name;
                      $counter++;
                      if ($counter<$intCountRelated) echo '&nbsp;/&nbsp;';
                    }
	                echo '</div>';
	                }
               
                  
                      
                      echo '<div class="clear">&nbsp;</div>';
                    echo '</li>';
                  }
                  echo '</ul>';
                  echo '</div>';
              }
              
                  
               
              
              ?>
         
         
         <!-- main text end -->
      </div>
      
      <div id="col_right">
        <!--column right -->
        
        
        
        <?php require_once("inc/col_right_default.inc"); ?>
        
        
        <!-- column right end -->
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