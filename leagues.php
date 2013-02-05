<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");

$id=$_GET['id'];
$id=$input->valid_text($id,true,true);
$id=get_ID_from_url($id);
if (empty($id)){header("Location: /text/404-error.html");}

if (empty($_GET['season'])) {
$query="SELECT DISTINCT id_season FROM stats WHERE id_league=".$id." AND id_stats_type=1 ORDER BY id_season DESC LIMIT 1";
//echo $query; 
  //if ($con->GetQueryNum($query)>0){
       //$result = $con->SelectQuery($query);
       //$write = $result->fetch_array();
       //$intSeason=$write['id_season'];
  //}else{
    $intSeason=ActSeasonWeb;
  //}
}else{
  $intSeason=$_GET['season'];
}
$intSeason=$input->check_number($intSeason);

if (empty($_GET['standings'])) {

  $intStandings=$con->GetSQLSingleResult("SELECT standings.id as item FROM standings INNER JOIN games_stages ON standings.id_stage=games_stages.id WHERE standings.id_season=".$intSeason." AND standings.id_league=".$id." AND id_detault>0 ORDER BY id_detault ASC LIMIT 1");
  if (empty($intStandings)) {
    
    $intStandings=$con->GetSQLSingleResult("SELECT standings.id as item FROM standings INNER JOIN games_stages ON standings.id_stage=games_stages.id WHERE standings.id_season=".$intSeason." AND standings.id_league=".$id." AND id_detault=0 ORDER BY name ASC LIMIT 1");
  }
}else{
  $intStandings=$_GET['standings'];
} 
$intStandings=$input->check_number($intStandings);

$query="SELECT * from leagues WHERE id=".$id;
if ($con->GetQueryNum($query)>0){
    $result = $con->SelectQuery($query);
    $write = $result->fetch_array();
    $StrLeagueName=$games->GetActualLeagueName($write['id'],$intSeason);
    $id_status=$write['league_status'];
    $name=$write['name'];
    $short_name=$write['short_name'];
    $english_name=$write['english_name'];
    $id_image=$write['id_image'];
    $name_shorte=$write['name_short'];
    $administered_by=$write['administered_by'];
    $head_manager=$write['head_manager'];
    $league_format=$write['league_format'];
    $promotion=$write['promotion'];
    $year_of_start=$write['year_of_start'];
    $brief_history=$write['brief_history'];
    $youth_league=$write['youth_league'];
    $youth_league_id=$write['youth_league_id'];
    $link_1=$write['link_1'];
    $link_1_status=$write['link_1_status'];
    $link_2=$write['link_2'];
    $link_2_status=$write['link_2_status'];
    $link_3=$write['link_3'];
    $link_3_status=$write['link_3_status'];
    
    
}else{
    header("Location: /text/404-error.html");
}

$strHeaderKeywords='league details '.$StrLeagueName.'';
$strHeaderDescription=$StrLeagueName.' details';

?>


<?

require_once("inc/head.inc");

?>
<script type="text/javascript" src="/inc/jcarousel/jquery.jcarousel.min.js"></script>
<link rel="stylesheet" type="text/css" href="/inc/jcarousel/skins/tango/skin.css" />
<script type="text/javascript">
$(function () {
    $('#mycarousel').jcarousel();
});
</script>

<link rel="stylesheet" type="text/css" media="all" href="/inc/jScrollPane/jScrollPane.css" />
<script type="text/javascript" src="/inc/jScrollPane/jquery.mousewheel.js"></script>
<script type="text/javascript" src="/inc/jScrollPane/jScrollPane.js"></script>
<script type="text/javascript">
			
			$(function()
			{
				// this initialises the demo scollpanes on the page.
				$('.news_panel').jScrollPane({showArrows:true, scrollbarWidth: 22, arrowSize: 14,scrollbarMargin:10});
			});
</script>			
<?php

echo $head->setTitle($strHeaderDescription." - ".strProjectName);
?>
<script type="text/javascript">
$(function() {
     $( "#rotate" ).tabs({ fx: { opacity: 'toggle' } });
});

</script>
<link rel="stylesheet" href="/inc/ui.tabs.css" type="text/css" media="screen, projection" />
<?php
require_once("inc/header_pirobox.inc");
echo $head->setEndHead();
?>
<body>

<div id="ads_top"><?php show_ads("top_main"); ?></div>
<div id="layout" class="shorter">
  
  <?php require_once("inc/bar_login.inc"); ?>
  <div id="logo"><?php require_once("inc/logo.inc"); ?></div>
  <div id="menu"><?php require_once("inc/menu.inc"); ?></div>
  <div id="main">
      
      <div id="text" class="shorter">
  
         <!-- main text -->
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
                      $NameCountry=$games->GetActualCountryName($write['item'],$intSeason);
                      echo '<a href="/country/'.get_url_text($NameCountry,$write['item']).'" title="'.$NameCountry.'">'.$NameCountry.'</a>';
                      $counter++;
                      $intIdCountry=$write['item'];
                      if ($counter<$intCount){ echo ' / ';}
                    }
                    echo '</div>';
                  }
                ?>
                <div class="corner_nav_middle">&nbsp;</div>
                <div class="corner_nav_box"><?php echo $StrLeagueName; ?></div>
                <div class="corner_nav_right">&nbsp;</div>
                
                <?php
                //found default existing season
                if (empty($_GET['season'])){
                  //$query="SELECT DISTINCT id_season FROM stats WHERE id_league=".$id." AND id_stats_type=1 ORDER BY id_season DESC LIMIT 1";
                  //if ($con->GetQueryNum($query)>0){
                    //$result = $con->SelectQuery($query);
                    //$write = $result->fetch_array();
                    //$intSeason=$write['id_season'];
                  //}
                  $intSeason=ActSeasonWeb;
                }
                ?>
                
            </div>
            
            <div class="toright season">
            
            <form action="">
              <div>
                 <b>Season:</b> <select name="season" onchange="redirect_by_select_box(this,'season','<?php echo '/league/'.get_url_text($StrLeagueName,$id);?>',0)">
                 <option value="">- select season -</option>
                 <?php 
                  $query="SELECT DISTINCT id_season FROM stats WHERE id_league=".$id." AND id_stats_type=1 ORDER BY id_season DESC";
                  if ($con->GetQueryNum($query)>0){
                    $result = $con->SelectQuery($query);
                    while($write = $result->fetch_array()){
                      echo '<option value="'.$write['id_season'].'" '.write_select($write['id_season'],$intSeason).'>'.($write['id_season']-1).'-'.($write['id_season']).'</option>';
                    }
                  }
                ?>
              </select>
              </div>
              </form>
            
            
            </div>
            
            
            <div class="clear">&nbsp;</div>
         </div>
         <div class="clear">&nbsp;</div>

         <?php
         //sponsorship
         show_sponsorship($id,$StrLeagueName,1);
         ?>

         <div class="space">&nbsp;</div>
         
         <div id="item_logo">
          
          <?php
           if (!empty($id_image)){
	           $strPhotoPath=get_photo_name($id_image);
	           if (file_exists(PhotoFolder."/".$strPhotoPath)) {
              echo '<img src="/image/155-150-1-'.str_replace("-","_",$strPhotoPath).'" alt="'.$StrLeagueName.' logo" />';
             }else{
              echo '<img src="/img/default.jpg" width="190" alt="Logo not found" />';
             }
	         }else{echo '<img src="/img/default.jpg" width="190" alt="Logo not found" />';}
           ?>
         </div>
         
         <div id="item_preview">
            <h1><?php echo $StrLeagueName; ?></h1>
            <ul>
            <?php
              $strStatus=$con->GetSQLSingleResult("SELECT name as item FROM leagues_status_list WHERE id=".$id_status);
              echo '<li><strong>League status:</strong> '.$strStatus.'</li>';
              if ($youth_league>0){
                //youth_league_id
                if (!empty($youth_league_id)){
                  $strYouth=', '.$con->GetSQLSingleResult("SELECT name as item FROM leagues_youth_list WHERE id=".$youth_league_id."");
                }
                echo '<li><b>Youth league:</b> yes'.$strYouth.'</li>';
              }
              if (!empty($shortcut))echo '<li><b>Shortcut:</b> '.$shortcut.'</li>';
              if (!empty($english_name))echo '<li><b>English name:</b> '.$english_name.'</li>';
              
              
              if (!empty($administered_by))echo '<li><b>Administered by:</b> '.$administered_by.'</li>';
              if (!empty($head_manager)) echo '<li><strong>Head manager:</strong> '.$head_manager.'</li>'; 
              if (!empty($year_of_start)) echo '<li><strong>Year of start:</strong> '.$year_of_start.'</li>';
              if (!empty($link_1)) $link_1_sub='<a onclick="return !window.open(this.href);" href="'.$link_1.'">'.$link_1.'</a>';
              if (!empty($link_2)) $link_2_sub='<a onclick="return !window.open(this.href);" href="'.$link_2.'">'.$link_2.'</a>';
              if (!empty($link_3)) $link_3_sub='<a onclick="return !window.open(this.href);" href="'.$link_3.'">'.$link_3.'</a>';
              if ( !empty($link_2_sub)) $link_2_main=', '.$link_2_sub;
              if ( !empty($link_3_sub)) $link_3_main=', '.$link_3_sub;
              if (!empty($link_1_sub) or !empty($link_2_sub) or !empty($link_3_sub))  echo '<li><strong>WWW:</strong> '.$link_1_sub.''.$link_2_main.''.$link_3_main.'</li>';
            
            ?>
            </ul>
            
         </div>
         <div class="clear">&nbsp;</div>
         
         <?php echo socials(0);   ?>
         
         <p class="center">Did you find any incorrect or incomplete information? <a href="/feedback.html?link=<?php echo curPageURL(); ?>">Please, let us know</a>.</p>
         
          <?php
          //photo
           $query="SELECT photo.id,photo.file_name,photo.description FROM photo_folder_assign INNER JOIN photo_folder ON photo_folder.id=photo_folder_assign.id_folder INNER JOIN photo ON photo_folder.id=photo.id_photo_folder
                   WHERE 
                     photo_folder_assign.id_item_type=1 AND photo_folder_assign.id_item=".$id."  AND photo_folder_assign.int_year =".$intSeason."  
                   ORDER BY photo.id DESC";
              //echo $query;
            if ($con->GetQueryNum($query)>0){
              
              echo '<div class="header_cufon blue_490"><span class="header_text blue">Photos</span></div>';
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
            
          
          
          
              $query_old="SELECT date_time, id_player,id_position,id_club_from,id_club_to,id_country_from,id_country_to,clubs1.id_league FROM transfers WHERE  
               ((SELECT count(*) FROM clubs_leagues_items WHERE clubs_leagues_items.id_league=".$id." AND (clubs_leagues_items.id_club=transfers.id_club_from or clubs_leagues_items.id_club=transfers.id_club_to))>0)
                ORDER BY date_time DESC  LIMIT 4";
              
              
              $query="SELECT transfers.* 
                      FROM transfers  
                      WHERE 
                        transfers.id_source_note=1 AND (transfers.id_league_from=".$id." OR transfers.id_league_to=".$id.") AND transfers.date_time < '".$intSeason."-07-01 00:00:00'
                      ORDER BY transfers.date_time DESC  LIMIT 4";
              //echo $query;
              if ($con->GetQueryNum($query)>0){
                  echo '<div class="header_cufon blue_490"><span class="header_text blue">Recent transfers</span><span class="toright"><a href="/transfers.html?id_country='.$intIdCountry.'&id_league='.$_GET['id'].'.html" title="Show all transfers">Show all transfers&raquo;</a></span></div>';
                  echo '<table class="basic transfers nomargin">';
                  $result = $con->SelectQuery($query);
                  $counter=0;
                  while($write = $result->fetch_array()){
                    $counter++;
                    if ($counter%2) $style="dark"; else $style="";
  		              echo '<tr class="'.$style.'">';
                      
                      echo '<td class="date nowrap" valign="top">'.date("d M Y",strtotime($write['date_time'])).' | </td>';
                      echo '<td class="nowrap" valign="top">';
                      $player_name=$con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$write['id_player']);
                      $player_nationality=$con->GetSQLSingleResult("SELECT nationality as item FROM players WHERE id=".$write['id_player']);
                      echo get_flag(strtolower($player_nationality),15,11);
                      echo '<span><a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player profile: '.$player_name.'">'.$player_name.'</a> 
                            &nbsp;('.$con->GetSQLSingleResult("SELECT shortcut as item FROM transfers_position_list WHERE id=".$write['id_position']).')</span>
                      ';
                      echo '</td>';    
                      echo '<td valign="top">';
                           //from club/country
                            if (empty($write['id_club_from']) and empty($write['id_country_from'])){
                              echo 'unknown';
                            }else
                            {
                            if (!empty($write['id_club_from'])){
                              $ItemName=$games->GetActualClubName($write['id_club_from'],$intSeason);
                              $CountryShortCut=$con->GetSQLSingleResult("SELECT id_country as item FROM clubs WHERE id=".$write['id_club_from']);
                              if (empty($CountryShortCut)){
                                
                                $LeagueID=$con->GetSQLSingleResult("SELECT id_league as item FROM clubs_leagues_items WHERE id_club=".$write['id_club_from']." ORDER by id DESC");
                                if (!empty($LeagueID)){
                                  $CountryShortCut=$con->GetSQLSingleResult("SELECT (SELECT shortcut FROM countries WHERE leagues_countries_items.id_country=countries.id LIMIT 1) as item FROM leagues_countries_items WHERE id_league=".$LeagueID." ORDER by id DESC LIMIT 1");
                                }
                              }
                              echo get_flag(strtolower($CountryShortCut),15,11);
                              echo '<a href="/club/'.get_url_text($ItemName,$write['id_club_from']).'" title="Show club profile: '.$ItemName.'">'.$ItemName.'</a>';
                            }else{
                              //
                              $ItemName=$games->GetActualCountryName($write['id_country_from'],$intSeason);
                              $CountryShortCut=$con->GetSQLSingleResult("SELECT shortcut as item FROM countries WHERE id=".$write['id_country_from']);
                              echo get_flag(strtolower($CountryShortCut),15,11);
                              echo '<a href="/country/'.get_url_text($ItemName,$write['id_country_from']).'" title="Show country profile: '.$ItemName.'">'.$ItemName.'</a>';
                            }
                            }
                      echo '</td>';
                      echo '<td valign="top" class="arrow_padding"><img src="/img/arrow_right.png" class="arrow_right" alt="arrow" height="9" width="12" /></td>';
                      echo '<td valign="top">';
                            //to club/country
                            if (empty($write['id_club_to']) and empty($write['id_country_to'])){
                              if ($write['id_retire_status']>1){
                                echo $con->GetSQLSingleResult("SELECT name as item FROM transfers_retire_list WHERE id=".$write['id_retire_status']); 
                              }else{
                                echo 'unknown';
                              } 	
                            }else
                            {
                            if (!empty($write['id_club_to'])){
                              $ItemName=$games->GetActualClubName($write['id_club_to'],$intSeason);
                              $CountryShortCut=$con->GetSQLSingleResult("SELECT id_country as item FROM clubs WHERE id=".$write['id_club_to']);
                              if (empty($CountryShortCut)){
                                
                                $LeagueID=$con->GetSQLSingleResult("SELECT id_league as item FROM clubs_leagues_items WHERE id_club=".$write['id_club_to']." ORDER by id DESC");
                                if (!empty($LeagueID)){
                                  $CountryShortCut=$con->GetSQLSingleResult("SELECT (SELECT shortcut FROM countries WHERE leagues_countries_items.id_country=countries.id LIMIT 1) as item FROM leagues_countries_items WHERE id_league=".$LeagueID." ORDER by id DESC LIMIT 1");
                                }
                              }
                              echo get_flag(strtolower($CountryShortCut),15,11);
                              echo '<a href="/club/'.get_url_text($ItemName,$write['id_club_to']).'" title="Show club profile: '.$ItemName.'">'.$ItemName.'</a>';
                              
                              
                              
                            }else{
                              //
                              $ItemName=$games->GetActualCountryName($write['id_country_to'],$intSeason);
                              $CountryShortCut=$con->GetSQLSingleResult("SELECT shortcut as item FROM countries WHERE id=".$write['id_country_to']);
                              echo get_flag(strtolower($CountryShortCut),15,11);
                              echo '<a href="/country/'.get_url_text($ItemName,$write['id_country_to']).'" title="Show country profile: '.$ItemName.'">'.$ItemName.'</a>';
                            }
                            }
                      echo '</td>';
                    echo '</tr>';
                  }
                  echo '</table>';
              }
              
              ?>
         
         
         
         <?php
          //$query="SELECT DISTINCT players.id as id_player,players.nationality,id_position,games,goals,assist,penalty,plusminus,id_club from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id." AND id_season=".$intSeason." AND stats.id_season_type=1 AND id_stats_type=1 AND games is not NULL ORDER BY (stats.goals+stats.assist) DESC, goals DESC LIMIT 10";
          
          $query="SELECT
           (SELECT id_club FROM stats s WHERE s.id_league=".$id." AND s.id_season=".$intSeason." AND s.id_season_type=1 AND s.id_player=stats.id_player ORDER by s.id DESC LIMIT 1) as id_club,
           players.id as id_player,id_position,sum(games) as games,sum(goals) as goals,sum(assist) as assist,sum(penalty) as penalty,sum(plusminus) as plusminus,sum(minutes) as minutes,AVG,PCE,sum(shotouts) as shotouts,sum(goals+assist) as points
            FROM stats INNER JOIN players ON stats.id_player=players.id 
            WHERE id_league=".$id." AND id_season=".$intSeason." AND id_stats_type=1  AND id_season_type=1   AND games is not NULL GROUP BY id_player HAVING id_club
            ORDER BY sum(goals+assist) DESC,sum(goals) DESC,sum(games) DESC LIMIT 10";  
           
          
          //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '<div class="header_cufon blue_490"><span class="header_text blue">League leaders</span>
                  <span class="toright">|&nbsp;&nbsp;<a href="/stats/league/'.$intSeason.'/'.get_url_text($StrLeagueName,$id).'" title="Show all stats for season '.($intSeason-1).'-'.$intSeason.'">season '.($intSeason-1).'-'.$intSeason.'</a></span>
                  <span class="toright"><a href="/stats/league-all-time/'.get_url_text($StrLeagueName,$id).'" title="Show all-time stats for league '.$StrLeagueName.'">all time stats</a></span>
                  </div>';
                  echo '<table class="tablesorter basic stats nomargin" id="myTable">';
                  echo '
                  <thead>
                  <tr>
                    <th class="link" valign="top">#</th>
                    <th class="player" valign="top">Player name</th>
                    <th class="position" valign="top">Pos</th>
                    <th class="" valign="top">Team</th>
                    <th class="right" valign="top">GP</th>
                    <th class="right" valign="top">G</th>
                    <th class="right" valign="top">A</th>
                    <th class="right" valign="top">P</th>
                    <th class="right" valign="top">PIM</th>
                    <th class="right" valign="top">+/-</th>
                    
                  </tr>
                  </thead>
                  <tbody>
                  ';
                  $result = $con->SelectQuery($query);
                  $counter=0;
                  while($write = $result->fetch_array()){
                    $counter++;
                    if ($counter%2) $style=""; else $style="dark";
  		              echo '<tr class="'.$style.'">';
  		                echo '<td class="left" valign="top">'.$counter.'.</td>';
                      echo '<td class="player" valign="top">';
                      $player_name=$con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$write['id_player']);
                      echo get_flag(strtolower($write['nationality']),15,11);
                      echo '<a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player profile: '.$player_name.'">'.$player_name.'</a>';
                      echo '</td>';
                      $StrPosition=$con->GetSQLSingleResult("SELECT shortcut as item FROM players_positions_list WHERE id=".$write['id_position']);
                      if (empty($StrPosition)) $StrPosition="-";
                      echo '<td class="position" valign="top">'.$StrPosition.'</td>';                   
                       $StrClubName=$games->GetActualClubName($write['id_club'],$intSeason);
                       echo '<td class="club" valign="top"><a href="/stats/club/'.$intSeason.'/'.get_url_text($StrClubName,$write['id_club']).'?league='.$id.'" title="Show club profile: '.$StrClubName.'">'.$StrClubName.'</a></td>';
                       echo '<td class="right" valign="top">'.$write['games'].'</td>';
                       echo '<td class="right" valign="top">'.$write['goals'].'</td>';
                       echo '<td class="right" valign="top">'.$write['assist'].'</td>';
                       echo '<td class="right bold" valign="top">'.($write['goals']+$write['assist']).'</td>';
                       echo '<td class="right" valign="top">'.$write['penalty'].'</td>';
                       echo '<td class="right" valign="top">'.$write['plusminus'].'</td>';
                      
                    echo '</tr>';
                  }
                  echo '</tbody>';
                  echo '<tfoot><tr><td colspan="10" class="right"><span class="link bold"><a href="/stats/league/'.$intSeason.'/'.get_url_text($StrLeagueName,$id).'" title="Show all stats for season '.($intSeason-1).'-'.$intSeason.'">Show all stats for season '.($intSeason-1).'-'.$intSeason.'</a></span></td></tr></tfoot>';
                  echo '</table>';
                  
              }
         ?>
         
         <?php
         
         function get_standings_row($id_group,$id_type,$id_standings){
                    
                    global $con,$games,$id,$intSeason;
                    $query="SELECT id FROM standings_teams WHERE int_order=0 AND id_table_type=".$id_type." AND id_group=".$id_group." AND id_standings=".$id_standings;
                    $intPocet=$con->GetQueryNum($query);
                    if ($intPocet>0){
                        $strOrder=" (points+bonus_points) DESC, (score1-score2) DESC,score1 DESC, id DESC";
                    }else{
                        $strOrder=" int_order ASC";
                    }
                    
                    $array_standigs_pemisions=get_standings_row_permisions($id_group,$id_type,$id_standings);
                     
                    $query="SELECT * FROM  standings_teams WHERE id_group=".$id_group." AND id_table_type=".$id_type." AND id_standings=".$id_standings." ORDER BY ".$strOrder;
                    //echo $query; 
                    $result = $con->SelectQuery($query);
                    $i = 0;
                    $intPocet=$con->GetQueryNum($query);
                    if ($intPocet>0){
                      while($write = $result->fetch_array())
	                    {
	                      $i++;
	                      if (($i)%2) $style=""; else $style="dark";
	                      echo '<tr class="'.$style.'">';
	                         echo '<td class="right">'.($i).'</td>';
	                         $StrClubName=$games->GetActualClubName($write['id_club'],$intSeason);
                           echo '<td class="club" valign="top"><a href="/club/'.get_url_text($StrClubName,$write['id_club']).'?league='.$id.'&amp;season='.$intSeason.'" title="Show club profile: '.$StrClubName.'">'.$StrClubName.'</a></td>';
                           echo '<td class="right">'.$write['games'].'</td>';
	                         echo '<td class="right">'.$write['wins'].'</td>';
	                         if ($array_standigs_pemisions[1]) echo '<td class="center">'.$write['wins_ot'].'</td>';
	                         if ($array_standigs_pemisions[2]) echo '<td class="right">'.$write['draws'].'</td>';
	                         if ($array_standigs_pemisions[3]) echo '<td class="center">'.$write['losts_ot'].'</td>';
	                         echo '<td class="right">'.$write['losts'].'</td>';
	                         echo '<td class="center">'.$write['score1'].':'.$write['score2'].'</td>';
	                         echo '<td class="left">
                           <b>'.($write['points']+$write['bonus_points']).'</b>';
                           $bonus_points=$write['bonus_points'];
                           if (!empty($bonus_points)){
                              if ($bonus_points>0) $bonus_points='+'.$bonus_points; 
                              echo ' <small>('.$bonus_points.')</small>';
                           }
                           echo '</td>';
                   	     echo '</tr>';
                   	    
                         //line 
                 	      $query_sub="SELECT name,id FROM standings_lines WHERE id_group=".$id_group." AND position=".($i)." AND id_table_type=".$id_type." AND id_standings=".$id_standings;
                        $result_sub = $con->SelectQuery($query_sub);
                        if ($con->GetQueryNum($query_sub)>0){
                          $write_sub = $result_sub->fetch_array();
                            echo '<tr><td colspan="10" class="standings_line">'.$write_sub['name'].'</td></tr>';
                        }
                   	     
                      }
                      $strText=$con->GetSQLSingleResult("SELECT info as item FROM standings_info WHERE id_group=".$id_group." AND id_table_type=".$id_type." AND id_standings=".$id_standings); 
                      if (!empty($strText)) echo '<tr><td colspan="10">'.$strText.'</td></tr>';
                    }
                    
                  }
                  
                  function get_standings_row_permisions($id_group,$id_type,$id_standings){
                    global $con;
                    $query_sub="SELECT sum(wins_ot) as wins_ot,sum(draws) as draws,sum(losts_ot) as losts_ot  FROM standings_teams WHERE id_table_type=".$id_type." AND id_group=".$id_group." AND id_standings=".$id_standings;
                    if ($con->GetQueryNum($query_sub)>0){
                    $result_sub = $con->SelectQuery($query_sub);
                      $write_sub = $result_sub->fetch_array();
                      if ($write_sub['wins_ot']>0) $array_standigs_pemisions[1]=true; else $array_standigs_pemisions[1]=false;
                      if ($write_sub['draws']>0) $array_standigs_pemisions[2]=true; else $array_standigs_pemisions[2]=false;
                      if ($write_sub['losts_ot']>0) $array_standigs_pemisions[3]=true; else $array_standigs_pemisions[3]=false;
                    }
                    return $array_standigs_pemisions; 
	                }
         
          $query="SELECT * FROM standings WHERE id=".$intStandings."";
          //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  $result = $con->SelectQuery($query);
                  $write = $result->fetch_array();
                  echo '
                  <a name="standings" class="clear"></a>
                  <div class="header_cufon blue_490"><span class="header_text blue">League standings</span><span class="toright">  
                    stage: <select name="season" onchange="redirect_by_url(this,\'standings\',\'/league/'.get_url_text($StrLeagueName,$id).'\',\'&season='.$intSeason.'#standings\')">
                    
                    <option value="">- select stage -</option>';
                    $query_sub="SELECT id,name FROM standings WHERE id_season=".$intSeason." AND id_league=".$id." ORDER BY name ASC";
                    if ($con->GetQueryNum($query_sub)>0){
                    $result_sub = $con->SelectQuery($query_sub);
                    while($write_sub = $result_sub->fetch_array()){
                      echo '<option value="'.$write_sub['id'].'" '.write_select($write_sub['id'],$intStandings).'>'.$write_sub['name'].'</option>';
                    }
                  }
                echo '</select>
                  
                  </span></div>';
                  
                  
                  
                  $query_groups="SELECT * FROM standings_groups WHERE id_standings=".$intStandings." ORDER BY order_group ASC";
                  if ($con->GetQueryNum($query_groups)>0){
                    $result_groups = $con->SelectQuery($query_groups);
                    while($write_groups = $result_groups->fetch_array()){
                      
                      $array_standigs_pemisions=get_standings_row_permisions($write_groups['id'],$write['id_type'],$write['id']);
                      echo '<h3><b>GROUP:</b> '.$write_groups['name'].'</h3>';
                      echo '
                      <table class="basic standings nomargin">
                      <thead>
                      <tr>
                        <th class="right" valign="top">#</th>
                        <th class="left" valign="top">Club</th>
                        <th class="right" valign="top">G</th>
                        <th class="right" valign="top">W</th>
                        ';
                        if ($array_standigs_pemisions[1]) echo '<th class="center" valign="top">W-OT</th>';
                        if ($array_standigs_pemisions[2]) echo '<th class="right" valign="top">D</th>';
                        if ($array_standigs_pemisions[3]) echo '<th class="center" valign="top">L-OT</th>';
                        echo '<th class="right" valign="top">L</th>
                        <th class="center" valign="top">Score</th>
                        <th class="left" valign="top">P</th>
                    </tr>
                    </thead>
                      ';
                      echo '<tbody>';
                      get_standings_row($write_groups['id'],$write['id_type'],$write['id']);
                      echo '</tbody>';
                      echo '</table>';
                    }
                  }
                  else{
                      $array_standigs_pemisions=get_standings_row_permisions(0,$write['id_type'],$write['id']);
                      echo '
                      <table class="basic standings nomargin">
                      <thead>
                      <tr>
                        <th class="right" valign="top">#</th>
                        <th class="left" valign="top">Club</th>
                        <th class="right" valign="top">G</th>
                        <th class="right" valign="top">W</th>
                        ';
                        if ($array_standigs_pemisions[1]) echo '<th class="center" valign="top">W-OT</th>';
                        if ($array_standigs_pemisions[2]) echo '<th class="right" valign="top">D</th>';
                        if ($array_standigs_pemisions[3]) echo '<th class="center" valign="top">L-OT</th>';
                        echo '<th class="right" valign="top">L</th>
                        <th class="center" valign="top">Score</th>
                        <th class="left" valign="top">P</th>
                      </tr>
                    </thead>
                      ';
                      echo '<tbody>';
                      get_standings_row(0,$write['id_type'],$write['id']);
                    echo '</tbody>';
                    echo '</table>';
                  }
                  
                 
                    echo '<div class="small">Last updated: '.date("d M Y, H:i",strtotime($write['last_update'])).'</div>' ;
                  
              }
         ?>
         
         
         
         <!-- main text end -->
      </div>
      
      <div id="col_right" class="shorter">
        
        
        <div id="rotate">
           
            
                <?
                $query="SELECT DISTINCT id_club as id FROM stats INNER JOIN clubs ON stats.id_club=clubs.id WHERE stats.id_season=".$intSeason." AND stats.id_league=".$id." AND id_stats_type=1  AND id_season_type=1 ORDER by clubs.name ASC";
                //echo $query;
                $counter=0;
                $result = $con->SelectQuery($query);
                $IntCount=$con->GetQueryNum($query);
                if ($IntCount>0){
                  $strTab1='<div id="fragment-1" class="box tabs">';
                  while($write = $result->fetch_array())
	               { 
	                 $counter++;
                   $StrClubName=$games->GetActualClubName($write['id'],$intSeason);
                    $strTab1.= '<div class="list_clubs">';
	                   $strTab1.=  '<a href="/club/'.get_url_text($StrClubName,$write['id']).'?season='.$intSeason.'&amp;league='.$id.'" title="Show club profile: '.$StrClubName.'">';
	                   $StrClubImageId=$games->GetActualClubLogo($write['id'],$intSeason);
	                   if (!empty($StrClubImageId)){
	                     $strPhotoPath=get_photo_name($StrClubImageId);
	                     if (file_exists(PhotoFolder."/".$strPhotoPath)) {
	                       
                          $strTab1.=  '<img class="border" src="/image/82-62-1-'.str_replace("-","_",$strPhotoPath).'" alt="'.$StrClubName.' logo" />';
                         
                        }else {$strTab1.=  '<img src="/img/default.jpg" width="82" alt="Logo not found" />';}
	                     }else {$strTab1.=  '<img src="/img/default.jpg" width="82" alt="Logo not found" />';}
	                  $strTab1.=  '</a>';
	                  $strTab1.=  '<h3><a href="/club/'.get_url_text($StrClubName,$write['id']).'?season='.$intSeason.'&amp;league='.$id.'" title="Show club profile: '.$StrClubName.'">'.$StrClubName.'</a></h3>';
	                  $strTab1.=  '</div>';
                    if ($counter==4) {$strTab1.=  '<div class="clear">&nbsp;</div>';$counter=0;}
         
          	     }
          	     $strTab1.=  '<div class="clear">&nbsp;</div><div class="space">&nbsp;</div>';
                 $strTab1.= '</div>';
          	    }
                
                
               if (!empty($league_format) or !empty($promotion)){
                $strTab2='<div id="fragment-2" class="box tabs">';
                $strTab2.= $league_format;
                $strTab2.= $promotion;
                $strTab2.='</div>';
               }
               
            
                $query="SELECT * FROM leagues_names WHERE id_league=".$id." ORDER BY int_year ASC";
                //echo $query;
                $result = $con->SelectQuery($query);
                $IntCount=$con->GetQueryNum($query);
                $counter=0;
                if ($IntCount>0){
                $strTab3.= '<div id="fragment-3" class="box tabs">';
                $strTab3.= '<ul>'; 
                while($write = $result->fetch_array())
                 {
                  $name_year[$counter]=($write['int_year']-1);
                  $name_name[$counter]=$write['name'];
	                $counter++; 
          	     }
          	     for ($i=0;$i<=($counter-1);$i++){
          	       if ($i==($counter-1)) {$name_year_write="present";} else {$name_year_write=$name_year[($i+1)];}
          	       $strTab3.= '<li>from '.$name_year[$i].' to '.$name_year_write.': <b>'.$name_name[$i].'</b></li>';
          	     }
          	     $strTab3.= '</ul>';
          	     $strTab3.='</div>';
          	    }

               $query="SELECT id_club,int_year FROM leagues_past_winners WHERE id_league=".$id." ORDER BY int_year DESC";
                //echo $query;
                $result = $con->SelectQuery($query);
                if ($con->GetQueryNum($query)>0){
                  $strTab4.= '<div id="fragment-4" class="box tabs">';
                  $strTab4.= '<ul>';
                  while($write = $result->fetch_array())
	               {    
	                    $StrChampionName=$games->GetActualClubName($write['id_club'],($write['int_year']));
	                    $strTab4.= '<li>'.($write['int_year']-1).'-'.($write['int_year']).': <a href="/club/'.get_url_text($StrChampionName,$write['id_club']).'" title="Show club profile: '.$StrChampionName.'">'.$StrChampionName.'</a></li>';
                 }
                 $strTab4.= '<ul>';
                 $strTab4.= '</div>';
          	    }
                
          	
               if (!empty($brief_history)){
                $strTab5.= '<div id="fragment-5" class="box tabs">';
                $strTab5.= $brief_history;
                $strTab5.= '</div>';
               }
          	
              if (!empty($link_1) or !empty($link_2) or !empty($link_3)) {
                $strTab6.= '<div id="fragment-6" class="box tabs">';
                $strTab6.=  '<ul>';
                if (!empty($link_1))  $strTab6.=  '<li><a onclick="return !window.open(this.href);" href="'.$link_1.'">'.$link_1.'</a></li>';
                if (!empty($link_2))  $strTab6.=  '<li><a onclick="return !window.open(this.href);" href="'.$link_2.'">'.$link_2.'</a></li>';
                if (!empty($link_3))  $strTab6.=  '<li><a onclick="return !window.open(this.href);" href="'.$link_3.'">'.$link_3.'</a></li>';
                $strTab6.=  '</ul>';
                $strTab6.=  '</div>';
              }
   
              
             if (!empty($strTab1) OR !empty($strTab2) OR !empty($strTab3) OR !empty($strTab4) OR !empty($strTab5) OR !empty($strTab6)){
             echo '<ul>';
              if (!empty($strTab1)) {echo '<li><a href="#fragment-1"><span>Teams</span></a></li>';}
              if (!empty($strTab2)) {echo '<li><a href="#fragment-2"><span>Format</span></a></li>';}
              if (!empty($strTab3)) {echo '<li><a href="#fragment-3"><span>Name changes</span></a></li>';}
              if (!empty($strTab4)) {echo '<li><a href="#fragment-4"><span>Champions</span></a></li>';}
              if (!empty($strTab5)) {echo '<li><a href="#fragment-5"><span>History</span></a></li>';}
              if (!empty($strTab6)) {echo '<li><a href="#fragment-6"><span>Links</span></a></li>';}
             echo '</ul>'; 
             echo $strTab1;
             echo $strTab2;
             echo $strTab3;
             echo $strTab4;
             echo $strTab5;
             echo $strTab6;
            }
              
          ?>

        </div>
        
        <?php show_ads("ads_col_right_subpages"); ?>

      
        <?php
            $query="SELECT articles.id,articles.header,date_time FROM articles
                   INNER JOIN articles_items ON articles.id=articles_items.id_article  
                   WHERE 
                      articles.show_item=1 AND articles.pub_date<=NOW() AND articles.pub_date<=NOW() AND (expire_date>=NOW() OR expire_date='0000-00-00 00:00:00')
                      AND articles_items.id_item_type=2 AND articles_items.id_item=".$id."  
                   order by articles.date_time DESC  LIMIT 5";
           
           
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '                                                              
                  <div class="header_cufon"><span class="header_text">Recent articles</span><span class="toright"><a href="/articles.html" title="Show all articles">Show all articles&raquo;</a></span></div>
                  <div class="box_lined">
                  <ul>
                  ';
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    echo '<li>'; 
                      echo '<a href="/article/'.get_url_text($write['header'],$write['id']).'" title="Read article: '.$write['header'].'">'.$write['header'].'</a> <small>('.date("d M Y",strtotime($write['date_time'])).')</small>';
                    echo '</li>';
                  }
                  echo '</ul>       
                  </div>
                  ';
              }
              
              $query="SELECT * FROM news 
                      INNER JOIN news_items ON news.id=news_items.id_news
                      WHERE 
                        news.id<>".$id." AND show_item=1 AND pub_date<=NOW() AND pub_date<=NOW() AND (expire_date>=NOW() OR expire_date='0000-00-00 00:00:00') 
                        AND news_items.id_item_type=2 AND news_items.id_item=".$id." 
                      order by date_time DESC  LIMIT 10";
              //echo $query;
              $intCountAll=$con->GetQueryNum($query);
              $intCount=0; 
              if ($intCountAll>0){
                   echo '
                  <div class="header_cufon"><span class="header_text">Recent news</span><span class="toright"><a href="/news.html" title="Show all news">Show all news&raquo;</a></span></div>
                  <div id="news">
                  <div class="news_panel">
                  ';
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                  echo'
                      <p class="header">'.$write['header'].'</p>
                      <p>'.$write['text'].'</p>';
                      if ($write['is_link']==1) echo '<p><span><a href="http://'.$write['link'].'" onclick="return !window.open(this.href);">Related link&raquo;</a></span></p>';
                      echo '<span class="date small">('.date("d M Y",strtotime($write['date_time'])).')</span>';
                      $intCount++;
                      if ($intCount<$intCountAll)echo '<div class="line">&nbsp;</div>';
                    
                  }
                    echo'
                  </div>
                  </div>
                  ';
              }
          ?>
          
        <?php
         
         function show_game_line ($id_game){
          global $con,$games,$intSeason;
         
                      $query="SELECT * FROM games  WHERE id=".$id_game;
                      $result = $con->SelectQuery($query);
                      $write = $result->fetch_array();
                      $GameName="";
                      echo '<td class="date" valign="top">'.date("d M Y",strtotime($write['date'])).'</td>';
                      echo '<td valign="top" class="left">';
                              $ItemName=$games->GetActualClubName($write['id_club_home'],$intSeason);
                              $GameName.=$ItemName.' - ';
                              //$CountryShortCut=$con->GetSQLSingleResult("SELECT id_country as item FROM clubs WHERE id=".$write['id_club_home']);
                              //echo get_flag(strtolower($CountryShortCut),15,11);
                              echo '<a href="/club/'.get_url_text($ItemName,$write['id_club_home']).'?season='.$intSeason.'" title="Show club profile: '.$ItemName.'">'.$ItemName.'</a>';
                      echo '&nbsp;-&nbsp;';
                              $ItemName=$games->GetActualClubName($write['id_club_visiting'],$intSeason);
                              $GameName.=$ItemName;
                              //$CountryShortCut=$con->GetSQLSingleResult("SELECT id_country as item FROM clubs WHERE id=".$write['id_club_visiting']);
                              //echo get_flag(strtolower($CountryShortCut),15,11);
                              echo '<a href="/club/'.get_url_text($ItemName,$write['id_club_visiting']).'?season='.$intSeason.'" title="Show club profile: '.$ItemName.'">'.$ItemName.'</a>';
                      echo '</td>';
                      echo '<td class="score"><a href="/game/detail/'.get_url_text($GameName,$write['id']).'" title="Show game details: '.$GameName.'">'.$games->GetScore($write['id'],1).'</a></span></td>';
                      echo '<td class="link"><span class="link"><a href="/game/detail/'.get_url_text($GameName,$write['id']).'" title="Show game details: '.$GameName.'">details&raquo;</a></span></td>';
               }
         
              $strGameLastDate=$con->GetSQLSingleResult("SELECT date as item FROM games  WHERE id_league=".$id." AND id_season=".$intSeason." AND date<=date(NOW()) AND home_score is NOT null AND visiting_score is NOT null ORDER BY date DESC,time DESC LIMIT 1");
              $query="SELECT id FROM games  WHERE id_league=".$id." AND id_season=".$intSeason." AND date<=date(NOW()) AND home_score is NOT null AND visiting_score is NOT null AND date='".$strGameLastDate."' ORDER BY date DESC,time DESC";
              //echo $query;
              $intCountFuture=$con->GetQueryNum($query);  
              if ($intCountFuture>0){
                  $counter=0;
                  if ($counter==0){
                  echo '<div class="header_cufon"><span class="header_text">Game fixtures</span><span class="toright"><a href="/games.html?id_season='.$intSeason.'&amp;id_show=2&amp;id_league='.get_url_text($StrLeagueName,$id).'" title="Show all games from season '.($intSeason-1).'-'.$intSeason.'">Show all games from season '.($intSeason-1).'-'.$intSeason.'</a></span></div>';
                  echo '<table class="games" cellspacing="0" cellpadding="0">';
                  }
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                      $counter++;
                      if ($counter%2) $style="dark"; else $style="";
  		                echo '<tr class="'.$style.'">';
                      show_game_line ($write['id']);
                      echo '</tr>';
                  }
              }else{
                
              }
              if ($counter>0)echo '</table>';
              ?>
        
        
        
  
        <div class="header_cufon"><span class="header_text">Leagues statistic</span></div>
        <div class="box leagues_statistic">
          
          
            <div class="toleft"><strong>Past league results:</strong> </div>
            <div class="toright">
              <form action="">
              <div>
               <?php
	               echo '<select name="season" onchange="redirect_by_url(this,\'id_season\',\'/games.html\',\'&amp;id_show=2&amp;id_league='.get_url_text($StrLeagueName,$id).'\')">';
	               $strSeasonList="";
                 $query="SELECT DISTINCT id_season FROM games WHERE id_league=".$id." ORDER BY id_season DESC";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  $strSeasonList.='<option value="">- select season -</option>';
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    $strSeasonList.='<option value="'.$write['id_season'].'">'.($write['id_season']-1).'-'.$write['id_season'].'</option>';
                  }
              }else{
                $strSeasonList.='<option value="">no seasons found</option>';
              }
              echo $strSeasonList;
                  
                 ?>
               </select>
              </div>
              </form>
            </div>
            <div class="space">&nbsp;</div>
            
            <div class="toleft"><strong>League standings:</strong> </div>
            <div class="toright">
              <form action="">
              <div>
                 
	               <?php
	               echo '<select name="season" onchange="redirect_by_url(this,\'season\',\'/league/'.get_url_text($StrLeagueName,$id).'\',\'#standings\')">';
	               $strSeasonList="";
                 $query="SELECT DISTINCT id_season FROM standings WHERE id_league=".$id." ORDER BY id_season DESC";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  $strSeasonList.='<option value="">- select season -</option>';
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    $strSeasonList.='<option value="'.$write['id_season'].'" '.write_select($write['id_season'],$intSeason).'>'.($write['id_season']-1).'-'.$write['id_season'].'</option>';
                  }
              }else{
                $strSeasonList.='<option value="">no seasons found</option>';
              }
              echo $strSeasonList;
                  
                 ?>
              </select>
              </div>
              </form>
            </div>
            <div class="space">&nbsp;</div>
            
            <div class="toleft"><strong>Historic statistic:</strong> </div>
            <div class="toright">
              <form action="">
              <div>
              <select name="season" onchange="redirect_by_select_stats(this,'<?php echo '/stats/league/';?>','<?php echo '/'.get_url_text($StrLeagueName,$id);?>')">
                 <?php
                 $strSeasonList="";
              $query="SELECT DISTINCT id_season FROM stats WHERE id_league=".$id." AND id_stats_type=1 ORDER BY id_season DESC";
              
              if ($con->GetQueryNum($query)>0){
                  $strSeasonList.='<option value="">- select season -</option>';
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    $strSeasonList.='<option value="'.$write['id_season'].'">'.($write['id_season']-1).'-'.$write['id_season'].'</option>';
                  }
              }else{
                $strSeasonList.='<option value="">no seasons found</option>';
              }
               echo $strSeasonList; 
              
                  ?>
              </select>
              <?php //echo $query; ?>
              </div>
              </form>
            </div>
             <div class="space">&nbsp;</div>
            
            <div class="toleft"><strong>Individuals records:</strong> </div>
            <div class="toright">
              <form action="">
              <div>
              
                 <?php
                 echo '<select name="type" onchange="redirect_by_url(this,\'type\',\'/stats/league_records/'.get_url_text($StrLeagueName,$id).'\',\'\')">';
                 
                 $strSeasonList="";
              $query="SELECT DISTINCT id_season FROM stats WHERE id_league=".$id." AND id_stats_type=1 ORDER BY id_season DESC";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                    
                   $strSeasonList.= '<option value="">- select stats type -</option>';
                   $strSeasonList.= '<option value="1" '.write_select(1,$intType).'>Points leaders</option>';
                   $strSeasonList.= '<option value="2" '.write_select(2,$intType).'>Goals leaders</option>';
                   $strSeasonList.= '<option value="3" '.write_select(3,$intType).'>Assists leaders</option>';
                   $strSeasonList.= '<option value="4" '.write_select(4,$intType).'>Penalty leaders</option>';
                   $strSeasonList.= '<option value="5" '.write_select(5,$intType).'>+/- leaders</option>';
                   $strSeasonList.= '<option value="6" '.write_select(6,$intType).'>Minutes leaders</option>';
                   $strSeasonList.= '<option value="7" '.write_select(7,$intType).'>Goals againts average leaders</option>';
                   $strSeasonList.= '<option value="8" '.write_select(8,$intType).'>Save percentage</option>';
                   $strSeasonList.= '<option value="9" '.write_select(9,$intType).'>Shutouts leaders</option>';
              }else{
                $strSeasonList.='<option value="">no seasons found</option>';
              }
               echo $strSeasonList; 
              
                  ?>
              </select>
              </div>
              </form>
            </div>
            <div class="clear">&nbsp;</div>
            
        </div>
        
        <?
        $strFastFactsBox="";
          
          
          
          //nejmladsi hrac
          $query="SELECT DISTINCT players.id as id_player,birth_date,name,surname from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id." AND id_season=".$intSeason." AND birth_date NOT LIKE '%?%' AND birth_date <> ''  AND id_stats_type=1 AND id_season_type=1 ORDER BY birth_date DESC LIMIT 1";
          if ($con->GetQueryNum($query)>0){
            $result = $con->SelectQuery($query);
            $write = $result->fetch_array();
            $player_name=$write['name'].' '.$write['surname'];
            $player_age=floor((date("Ymd",strtotime(($intSeason)."-".date("m")."-".date("d"))) - date("Ymd", strtotime($write['birth_date']))) / 10000);
            $strFastFactsBox.='<li><strong>Youngest player:</strong> <a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player profile: '.$player_name.'">'.$player_name.'</a> ('.$player_age.' years)</li>';
          }
          //nejstarsi hrac
          $query="SELECT DISTINCT players.id as id_player,birth_date,name,surname from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id." AND id_season=".$intSeason." AND birth_date NOT LIKE '%?%'  AND birth_date <> '' AND id_stats_type=1 AND id_season_type=1 ORDER BY birth_date ASC LIMIT 1";
          if ($con->GetQueryNum($query)>0){
            $result = $con->SelectQuery($query);
            $write = $result->fetch_array();
            $player_name=$write['name'].' '.$write['surname'];
            $player_age=floor((date("Ymd",strtotime(($intSeason)."-".date("m")."-".date("d"))) - date("Ymd", strtotime($write['birth_date']))) / 10000);
            $strFastFactsBox.='<li><strong>Oldest player:</strong> <a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player profile: '.$player_name.'">'.$player_name.'</a> ('.$player_age.' years)</li>';
          }
          //nejmensi hrac
          $query="SELECT DISTINCT players.id as id_player,height,name,surname from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id." AND id_season=".$intSeason." AND height>0  AND id_stats_type=1 AND id_season_type=1 ORDER BY height ASC LIMIT 1";
          if ($con->GetQueryNum($query)>0){
            $result = $con->SelectQuery($query);
            $write = $result->fetch_array();
            $player_name=$write['name'].' '.$write['surname'];
            $strFastFactsBox.='<li><strong>Shortest player:</strong> <a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player profile: '.$player_name.'">'.$player_name.'</a> ('.$write['height'].' cm)</li>';
          }
          //nejvyssi hrac
          $query="SELECT DISTINCT players.id as id_player,height,name,surname from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id." AND id_season=".$intSeason." AND height>0  AND id_stats_type=1 AND id_season_type=1 ORDER BY height DESC LIMIT 1";
          if ($con->GetQueryNum($query)>0){
            $result = $con->SelectQuery($query);
            $write = $result->fetch_array();
            $player_name=$write['name'].' '.$write['surname'];
            $strFastFactsBox.='<li><strong>Tallest player:</strong> <a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player profile: '.$player_name.'">'.$player_name.'</a> ('.$write['height'].' cm)</li>';
          }
          //narodnosti
          $query="SELECT nationality from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id." AND id_season=".$intSeason." AND nationality<>'' AND id_stats_type=1 AND id_season_type=1 GROUP BY  nationality ORDER BY nationality ASC";
          $intCount=$con->GetQueryNum($query);
          $counter=0;
          if ($intCount>0){
            $strFastFactsBox.='<li><strong>Nationalities:</strong> ';
            $result = $con->SelectQuery($query);
            while($write = $result->fetch_array()){
              $nationality_name=$con->GetSQLSingleResult("SELECT name as item FROM countries WHERE shortcut='".$write['nationality']."'");
              $nationality_id=$con->GetSQLSingleResult("SELECT id as item FROM countries WHERE shortcut='".$write['nationality']."'");
              $sql="SELECT stats.id from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id." AND id_season=".$intSeason." AND nationality='".$write['nationality']."' AND id_stats_type=1 AND id_season_type=1 GROUP BY  stats.id_player";
              $nationality_number=$con->GetQueryNum($sql);
              $strFastFactsBox.= get_flag(strtolower($write['nationality']),15,11);
              $strFastFactsBox.='<a href="/stats/league/'.$intSeason.'/'.get_url_text($StrLeagueName,$id).'?nationality='.$write['nationality'].'" title="Show stats for player from '.$nationality_name.'">'.$nationality_name.' ('.$nationality_number.')</a>';
              $counter++;
              if ($counter<$intCount){$strFastFactsBox.=', ';}
            }
            $strFastFactsBox.='</li>';
          }
          
          $intIDstage=$con->GetSQLSingleResult("SELECT id as item FROM games_stages WHERE id_league=".$id." AND id_detault=1");
          
          //nejvyssi vyhra
          $query_home="SELECT * FROM games WHERE id_league=".$id." AND id_season=".$intSeason." AND home_score<>visiting_score and home_score>visiting_score AND id_stage=".$intIDstage." ORDER by (home_score-visiting_score) DESC";
          if ($con->GetQueryNum($query_home)>0){
            $result = $con->SelectQuery($query_home);
            $write = $result->fetch_array();
            $intScoreHome=$write['home_score']-$write['visiting_score'];
          }
          
          $query_visiting="SELECT * FROM games WHERE id_league=".$id." AND id_season=".$intSeason." AND home_score<>visiting_score and home_score<visiting_score AND id_stage=".$intIDstage." ORDER by (visiting_score-home_score) DESC";
          if ($con->GetQueryNum($query_visiting)>0){
            $result = $con->SelectQuery($query_visiting);
            $write = $result->fetch_array();
            $intVisitingHome=$write['visiting_score']-$write['home_score'];
          }
          if ($intScoreHome>$intVisitingHome){
            $query=$query_home;
          }else{
            $query=$query_visiting;
          }
          
          
          if ($con->GetQueryNum($query)>0){
            $result = $con->SelectQuery($query);
            $write = $result->fetch_array();
            $strClub=$games->GetActualClubName($write['id_club_home'],$intSeason);
            $strClubLink.=$strClub;
            $strGame='<a href="/club/'.get_url_text($strClub,$write['id_club_home']).'" title="Show club details: '.$strClub.'">'.$strClub.'</a>';
            $strGame.='&nbsp;-&nbsp;';
            $strClub=$games->GetActualClubName($write['id_club_visiting'],$intSeason);
            $strClubLink.='-'.$strClub;
            $strGame.='<a href="/club/'.get_url_text($strClub,$write['id_club_visiting']).'" title="Show club details: '.$strClub.'">'.$strClub.'</a>';
            $strGame.=' <span class="link">&nbsp;&nbsp;<a href="/game/detail/'.get_url_text($strClubLink,$write['id']).'" title="Show game details: '.$strClubLink.'">'.$games->GetScore($write['id'],1).'</a></span>';
            $strFastFactsBox.='<li><strong>Highest win:</strong> '.$strGame.'</li>';
          }
            
          
          //prumerna navstevnost
          $query="SELECT sum(spectators)/count(id) as average from games WHERE spectators>0 AND id_league=".$id." AND id_season=".$intSeason."  AND id_stage=".$intIDstage."";
          //echo $query;
          if ($con->GetQueryNum($query)>0){
            $result = $con->SelectQuery($query);
            $write = $result->fetch_array();
            if (!empty($write['average'])){
              $average=ceil($write['average']);
              $strFastFactsBox.='<li><strong>Season average attendance:</strong> '.$average.'</li>';
            }
            
          }
          
          
          if (!empty($strFastFactsBox)){
            echo '
            <div class="header_cufon"><span class="header_text">Fast facts</span><span class="toright">season '.($intSeason-1).'-'.$intSeason.'</span></div>
            <div class="box noflag">
              <ul>
              '.$strFastFactsBox.'
              </ul>
            </div>
            ';
          }
          
        ?>
        
         
        
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