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

if (!empty($id)){

//leagues
if (empty($_GET['league'])) {
     $query="SELECT id_league from clubs_leagues_items WHERE (SELECT count(*) FROM leagues WHERE youth_league=0 AND  league_status=1 AND leagues.id=clubs_leagues_items.id_league)>0 AND id_club=".$id;  
}else {
    $intLeague=$_GET['league'];
    $intLeague=$input->valid_text($intLeague,true,true);
    $query="SELECT id as id_league from leagues WHERE id=".$intLeague;
}
$intCount=$con->GetQueryNum($query);
$counter=0;
if ($intCount>0){
   $result = $con->SelectQuery($query);
    $strLeaguesHeader.='<div class="corner_nav_middle">&nbsp;</div>';
    $strLeaguesHeader.='<div class="corner_nav_box">';
    $write = $result->fetch_array();
       
       $id_league=$write['id_league'];
       
       if (empty($_GET['season'])) {
        $query="SELECT DISTINCT id_season FROM stats WHERE id_club=".$id." AND id_league=".$id_league." AND id_stats_type=1 ORDER BY id_season DESC LIMIT 1";
        if ($con->GetQueryNum($query)>0){
          $result = $con->SelectQuery($query);
          $write = $result->fetch_array();
          $intSeason=$write['id_season'];
        }else{
          $intSeason=ActSeasonWeb;
        }
      }else{
        $intSeason=$_GET['season'];
      }
      $intSeason=$input->check_number($intSeason);
      $strSeason=($intSeason-1)."-".$intSeason;
      
       
       $NameLeague=$games->GetActualLeagueName($id_league,$intSeason);
       $strLeaguesHeader.='<a href="/league/'.get_url_text($NameLeague,$id_league).'?season='.$intSeason.'" title="'.$NameLeague.'">'.$NameLeague.'</a>';
       $counter++;
       
  $strLeaguesHeader.='</div>';
}


  $StrClubName=$games->GetActualClubName($id,$intSeason);
  if (!empty($StrClubName)){
    $boolClub=true;
    $strHeader="Statistics ".$StrClubName." ".$strSeason;
    $strHeaderDescription="Statistics for club ".$StrClubName." ".$strSeason;
    $strHeaderKeywords=HeaderKeywords.",statistics,".$StrClubName.",".$strSeason;
  }else{
    $boolClub=false;
    $strHeader="Club not found";
    $strHeaderDescription="Club not found, or selected";
  }
}else{
  $boolClub=false;
  $strHeader="Club not found";
  $strHeaderDescription="Club not found, or selected";
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
          if ($boolClub){
          ?>
          <div id="navigation">
            <div class="toleft">
                <div class="corner_nav_left">&nbsp;</div>
                <div class="corner_nav_box"><a href="/clubs.html" title="Clubs">Clubs</a></div>
                <?php
                //countries
                $query="SELECT id from countries WHERE shortcut='".$strCountry."'";
                if ($con->GetQueryNum($query)>0){
                    $result = $con->SelectQuery($query);
                    $write = $result->fetch_array();
                    $NameCountry=$games->GetActualCountryName($write['id'],$intSeason);
                    echo '<div class="corner_nav_middle">&nbsp;</div>';
                    echo '<div class="corner_nav_box"><a href="/country/'.get_url_text($NameCountry,$write['id']).'" title="'.$NameCountry.'">'.$NameCountry.'</a></div>';
                }else{
                  $LeagueIDMain=$con->GetSQLSingleResult("SELECT id_league as item FROM clubs_leagues_items WHERE id_club=".$id." ORDER by id DESC");
                  $query="SELECT (SELECT id FROM countries WHERE leagues_countries_items.id_country=countries.id LIMIT 1) as item FROM leagues_countries_items WHERE id_league=".$LeagueIDMain." ORDER by item";
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
                      if ($counter<$intCount){ echo ' / ';}
                    }
                    echo '</div>';
                  }
                }
                
                echo $strLeaguesHeader;
                ?>
                <div class="corner_nav_middle">&nbsp;</div>
                <div class="corner_nav_box"><?php echo '<a href="/club/'.get_url_text($StrClubName,$id).'?season='.$intSeason.'" title="'.$StrClubName.'">'.$StrClubName; ?></a></div>
                <div class="corner_nav_right">&nbsp;</div>
                
            </div>
            <div class="toright link bold"><span class="link"><a href="/clubs.html" title="List of clubs">select another club</a></span></div>
         </div>
         
         <div class="space">&nbsp;</div>
         
         <h1><?php echo $strHeader ?></h1>
         <?php
         $NameLeague=$games->GetActualLeagueName($id_league,$intSeason);
         echo '<h3 style="padding-top:0px">League: <a href="/stats/league/'.$intSeason.'/'.get_url_text($NameLeague,$id_league).'?type='.$intType.'" title="'.$NameLeague.'">'.$NameLeague.'</a></h3>';
         ?>
         
         <div class="header box_normal">&nbsp;</div>
         <div class="box normal">
              <b>This club in  <?php echo $strSeason;  ?>:</b>
               <?php 
                //leagues
                $query="SELECT DISTINCT id_league,name from stats INNER JOIN leagues ON leagues.id=stats.id_league WHERE stats.id_club=".$id." AND stats.id_season=".$intSeason." AND id_stats_type=1 ORDER by id_order ASC, name ASC";
                $intCount=$con->GetQueryNum($query);
                $counter=0;
                if ($intCount>0){
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    
                    $query_seasons="SELECT DISTINCT id_season FROM stats WHERE id_league=".$write['id_league']." AND id_club=".$id." AND id_stats_type=1 ORDER BY id_season DESC LIMIT 1";
                    $counter++;
                    if ($con->GetQueryNum($query_seasons)>0){
                      $result_seasons = $con->SelectQuery($query_seasons);
                      $write_seasons = $result_seasons->fetch_array();
                      $NameLeague=$games->GetActualLeagueName($write['id_league'],$write_seasons['id_season']);
                      echo '<a href="/stats/club/'.$intSeason.'/'.get_url_text($StrClubName,$id).'?league='.$write['id_league'].'">';
                      if ($write['id_league']==$id_league) echo '<span class="link bold">';
                      echo $NameLeague;
                      if ($write['id_league']==$id_league) echo '</span>';
                      echo '</a>';
                      if ($counter<$intCount) echo " | ";
                    }
                    }
                  }
                ?>
         </div>
         
         
          <div class="header box_normal">&nbsp;</div>
           <div class="box normal" id="stats_search_form">
              <form action="" method="get">
              
              <div class="toleft">
                <b>Year:</b>
                <select name="season" class="season">
                 <?php 
                 //seasons
                $query="SELECT DISTINCT id_season FROM stats WHERE id_league=".$id_league." AND id_club=".$id." AND id_stats_type=1 ORDER BY id_season DESC";
                $intCount=$con->GetQueryNum($query);
                if ($intCount>0){
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    echo '<option value="'.$write['id_season'].'" '.write_select($write['id_season'],$intSeason).'>'.($write['id_season']-1).'-'.$write['id_season'].'</option>';
                  }
                }
                ?>
              </select> 
              
              <b>Stage:</b>
                <select name="type" class="type">
                 <?php 
                 //type
                $query="SELECT DISTINCT id_season_type FROM stats WHERE id_league=".$id_league." AND id_club=".$id." AND id_stats_type=1 ORDER BY id_season_type ASC";
                $intCount=$con->GetQueryNum($query);
                if ($intCount>0){
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    $strStageName=$con->GetSQLSingleResult("SELECT name as item FROM games_stages WHERE id=".$write['id_season_type']);
                    if (!empty($strStageName)) echo '<option value="'.$write['id_season_type'].'" '.write_select($write['id_season_type'],$intType).'>'.$strStageName.'</option>';
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
              <span class="stats_submit toright"><input type="submit" class="submit" value="" /></span>
              <input type="hidden" name="league" value="<?php echo $id_league;?>" />
              <div class="clear">&nbsp;</div>
              </form>
           </div>
              
         <?
          $intOrderDir=$_GET['dir'];
             switch ($intOrderDir){
              case 1:
                $strOrderDir="ASC";
                if ($_GET['is_order']==1) $intOrderDirChange=2; 
              break;
              case 2:
                $strOrderDir="DESC";
                if ($_GET['is_order']==1) $intOrderDirChange=1;
              break;
            }
          
          $intOrder=$_GET['order'];
          
          if ($intPosition<>1){
            //players
            switch ($intOrder){
              case 1:
                if (empty($intOrderDir)) {$strOrderDir="ASC"; $intOrderDir=1; $intOrderDirChange=2;}
                $strOrder="surname ".$strOrderDir."";
              break;
              case 2:
                if (empty($intOrderDir)) {$strOrderDir="ASC"; $intOrderDir=1;  $intOrderDirChange=2;}
                $strOrder="id_position ".$strOrderDir."";
              break;
              case 3:
                if (empty($intOrderDir)) {$strOrderDir="ASC"; $intOrderDir=1;  $intOrderDirChange=2;}
                $strOrder="id_club ".$strOrderDir."";
              break;
              case 4:
                if (empty($intOrderDir)) {$strOrderDir="DESC"; $intOrderDir=2;  $intOrderDirChange=1;}
                $strOrder="if(games IS NULL,1,0), games ".$strOrderDir."";
              break;
              case 5:
                if (empty($intOrderDir)) {$strOrderDir="DESC"; $intOrderDir=2;  $intOrderDirChange=1;}
                $strOrder="if(goals IS NULL,1,0), goals ".$strOrderDir.",goals ".$strOrderDir.",games ".$strOrderDir."";
              break;
              case 6:
                if (empty($intOrderDir)) {$strOrderDir="DESC"; $intOrderDir=2;  $intOrderDirChange=1;}
                $strOrder="if(assist IS NULL,1,0), assist ".$strOrderDir."";
              break;
              case 7:
                if (empty($intOrderDir)) {$strOrderDir="DESC"; $intOrderDir=2;  $intOrderDirChange=1;}
                $strOrder="if(goals IS NULL,1,0),if(assist IS NULL,1,0),(stats.goals+stats.assist) ".$strOrderDir."";
              break;
              case 8:
                if (empty($intOrderDir)) {$strOrderDir="DESC"; $intOrderDir=2;  $intOrderDirChange=1;}
                $strOrder="if(penalty IS NULL,1,0), penalty ".$strOrderDir."";
              break;
              case 9:
                if (empty($intOrderDir)) {$strOrderDir="DESC"; $intOrderDir=2;  $intOrderDirChange=1;}
                $strOrder="if(plusminus IS NULL,1,0), sum(plusminus) ".$strOrderDir."";
                
              break;
              
              default:
                if (empty($intOrderDir)) {$strOrderDir="DESC"; $intOrderDir=2;  $intOrderDirChange=1;}
                $strOrder="(stats.goals+stats.assist) ".$strOrderDir.",goals DESC,games DESC";
                $intOrder=7;
            }
          }else{
            //goalies
            switch ($intOrder){
              case 1:
                if (empty($intOrderDir)) {$strOrderDir="ASC"; $intOrderDir=1; $intOrderDirChange=2;}
                $strOrder="surname ".$strOrderDir."";
              break;
              case 2:
                if (empty($intOrderDir)) {$strOrderDir="ASC"; $intOrderDir=1;  $intOrderDirChange=2;}
                $strOrder="id_position ".$strOrderDir."";
              break;
              case 3:
                if (empty($intOrderDir)) {$strOrderDir="ASC"; $intOrderDir=1;  $intOrderDirChange=2;}
                $strOrder="id_club ".$strOrderDir."";
              break;
              case 4:
                if (empty($intOrderDir)) {$strOrderDir="DESC"; $intOrderDir=2;  $intOrderDirChange=1;}
                $strOrder="if(games IS NULL,1,0),games ".$strOrderDir."";
              break;
              case 5:
                if (empty($intOrderDir)) {$strOrderDir="DESC"; $intOrderDir=2;  $intOrderDirChange=1;}
                $strOrder="if(games_dressed IS NULL,1,0),games_dressed ".$strOrderDir."";
              break;
               case 6:
                if (empty($intOrderDir)) {$strOrderDir="DESC"; $intOrderDir=2;  $intOrderDirChange=1;}
                $strOrder="if(minutes IS NULL,1,0),minutes ".$strOrderDir."";
              break;
               case 7:
                if (empty($intOrderDir)) {$strOrderDir="DESC"; $intOrderDir=2;  $intOrderDirChange=1;}
                $strOrder="if(penalty IS NULL,1,0),penalty ".$strOrderDir."";
              break;
               case 8:
                if (empty($intOrderDir)) {$strOrderDir="ASC"; $intOrderDir=1;  $intOrderDirChange=2;}
                $strOrder=" if(AVG IS NULL,1,0), AVG ".$strOrderDir."";
              break;
               case 9:
                if (empty($intOrderDir)) {$strOrderDir="DESC"; $intOrderDir=2;  $intOrderDirChange=1;}
                $strOrder=" if(PCE IS NULL,1,0), PCE ".$strOrderDir."";
              break;
               case 10:
                if (empty($intOrderDir)) {$strOrderDir="DESC"; $intOrderDir=2;  $intOrderDirChange=1;}
                $strOrder="if(shotouts IS NULL,1,0),shotouts ".$strOrderDir."";
              break;
              case 11:
                if (empty($intOrderDir)) {$strOrderDir="DESC"; $intOrderDir=2;  $intOrderDirChange=1;}
                $strOrder="if(goals IS NULL,1,0),goals ".$strOrderDir."";
              break;
              case 12:
                if (empty($intOrderDir)) {$strOrderDir="DESC"; $intOrderDir=2;  $intOrderDirChange=1;}
                $strOrder="if(assist IS NULL,1,0),assist ".$strOrderDir."";
              break;
              default:
                if (empty($intOrderDir)) {$strOrderDir="DESC"; $intOrderDir=2;  $intOrderDirChange=1;}
                $strOrder=" if(PCE IS NULL,1,0), PCE ".$strOrderDir.", if(AVG IS NULL,1,0),AVG ASC";
                $intOrder=9;
            } 
          }
            
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
          if (!empty($intType)){
            $sqlWhere.=" AND stats.id_season_type=".$intType;
             $strVariables.='type='.$intType.'&amp;';
          }else{
            $sqlWhere.=" AND stats.id_season_type=1";
          }
          
          if ($intPosition<>1){
            $sqlWhere.=" AND  players.id_position<>1";
          }else{
            $sqlWhere.=" AND  players.id_position=1";
          }
          
          $query="SELECT DISTINCT players.id as id_player,id_position,games,goals,assist,penalty,plusminus,id_club,games_dressed,minutes,AVG,PCE,shotouts,(goals+assist) as points from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id_league." AND id_club=".$id." AND id_season=".$intSeason." AND id_stats_type=1 ".$sqlWhere." ORDER BY ".$strOrder;
              if ($con->GetQueryNum($query)>0){
                  
                  $strVariables.='list_number='.$_GET['list_number'].'&amp;season='.$intSeason.'&amp;league='.$id_league.'&amp;';
                  echo '<table class="basic stats_main">';
                  echo '
                  <thead>
                  <tr>
                    <th class="" valign="top">#</th>
                    <th class="" valign="top"><a href="/stats/club/'.$intSeason.'/'.get_url_text($StrClubName,$id).'?is_order=1&amp;order=1&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by player name">Player name</a></th>
                    ';
                    if ($intPosition<>1){
                    //players
                    echo'
                    <th class="" valign="top"><a href="/stats/club/'.$intSeason.'/'.get_url_text($StrClubName,$id).'?is_order=1&amp;order=2&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by position">Pos</a></th>
                    <th class="" valign="top"><a href="/stats/club/'.$intSeason.'/'.get_url_text($StrClubName,$id).'?is_order=1&amp;order=4&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by games">GP</a></th>
                    <th class="" valign="top"><a href="/stats/club/'.$intSeason.'/'.get_url_text($StrClubName,$id).'?is_order=1&amp;order=5&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by goals">G</a></th>
                    <th class="" valign="top"><a href="/stats/club/'.$intSeason.'/'.get_url_text($StrClubName,$id).'?is_order=1&amp;order=6&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by assits">A</a></th>
                    <th class="" valign="top"><a href="/stats/club/'.$intSeason.'/'.get_url_text($StrClubName,$id).'?is_order=1&amp;order=7&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by points">P</a></th>
                    <th class="" valign="top"><a href="/stats/club/'.$intSeason.'/'.get_url_text($StrClubName,$id).'?is_order=1&amp;order=8&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by penalty minutes">PIM</a></th>
                    <th class="" valign="top"><a href="/stats/club/'.$intSeason.'/'.get_url_text($StrClubName,$id).'?is_order=1&amp;order=9&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by plus minus">+/-</a></th>
                    ';
                    }else{
                    //goalies
                    echo'
                    <th class="" valign="top"><a href="/stats/club/'.$intSeason.'/'.get_url_text($StrClubName,$id).'?is_order=1&amp;order=4&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by games">GP</a></th>
                    <th class="" valign="top"><a href="/stats/club/'.$intSeason.'/'.get_url_text($StrClubName,$id).'?is_order=1&amp;order=6&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by minutes">MIN</a></th>
                    <th class="" valign="top"><a href="/stats/club/'.$intSeason.'/'.get_url_text($StrClubName,$id).'?is_order=1&amp;order=8&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by AVG">AVG</a></th>
                    <th class="" valign="top"><a href="/stats/club/'.$intSeason.'/'.get_url_text($StrClubName,$id).'?is_order=1&amp;order=9&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by PER">PER</a></th>
                    <th class="" valign="top"><a href="/stats/club/'.$intSeason.'/'.get_url_text($StrClubName,$id).'?is_order=1&amp;order=10&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by shotouts">SO</a></th>
                    <th class="" valign="top"><a href="/stats/club/'.$intSeason.'/'.get_url_text($StrClubName,$id).'?is_order=1&amp;order=11&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by goals">G</a></th>
                    <th class="" valign="top"><a href="/stats/club/'.$intSeason.'/'.get_url_text($StrClubName,$id).'?is_order=1&amp;order=12&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by assits">A</a></th>
                    <th class="" valign="top"><a href="/stats/club/'.$intSeason.'/'.get_url_text($StrClubName,$id).'?is_order=1&amp;order=7&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by penalty minutes">PIM</a></th>
                    
                    ';
                    }
                  echo '
                  </tr>
                  </thead>
                  <tbody>
                  ';
                  
                  $strVariables.='order='.$intOrder.'&amp;dir='.$intOrderDir.'&amp;';
                  $listovani = new listing($con,"?".$strVariables."&amp;",100,$query,3,"",$_GET['list_number']);
                  $query=$listovani->updateQuery();
          
                  $result = $con->SelectQuery($query);
                  $counter=$_GET['list_number'];
                  if ($counter==-1) $counter++; 
                  while($write = $result->fetch_array()){
                    $counter++;
                    if ($counter%2) $style=""; else $style="dark";
  		              echo '<tr class="'.$style.'">';
  		                echo '<td class="left counter" valign="top">'.$counter.'.</td>';
                      echo '<td class="player" valign="top">';
                      $player_nationality=$con->GetSQLSingleResult("SELECT nationality as item FROM players WHERE id=".$write['id_player']);
                      echo get_flag(strtolower($player_nationality),15,11);
                      $player_name=$con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$write['id_player']);
                      echo '<a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player statistics: '.$player_name.'">'.$player_name.'</a>';
                      echo '</td>';
                      $StrPosition=$con->GetSQLSingleResult("SELECT shortcut as item FROM players_positions_list WHERE id=".$write['id_position']);
                      if (empty($StrPosition)) $StrPosition="-";
                       if ($intPosition<>1){
                       //players
                       echo '<td class="position" valign="top">'.$StrPosition.'</td>';                   
                       echo '<td class="" valign="top">'.$write['games'].'</td>';
                       echo '<td class="" valign="top">'.$write['goals'].'</td>';
                       echo '<td class="" valign="top">'.$write['assist'].'</td>';
                       echo '<td class="bold" valign="top">'.($write['points']).'</td>';
                       echo '<td class="" valign="top">'.$write['penalty'].'</td>';
                       echo '<td class="" valign="top">'.$write['plusminus'].'</td>';
                       }else{
                       //goalies
                       echo '<td class="" valign="top">'.$write['games'].'</td>';
                       echo '<td class="" valign="top">'.$write['minutes'].'</td>';
                       echo '<td class="" valign="top">'.$write['AVG'].'</td>';
                       if ($write['PCE']==1 or $write['PCE']==100) $strPercent="1.000"; else $strPercent=$write['PCE'];
                       echo '<td class="" valign="top">'.$strPercent.'</td>';
                       echo '<td class="" valign="top">'.$write['shotouts'].'</td>';
                       echo '<td class="" valign="top">'.$write['goals'].'</td>';
                       echo '<td class="" valign="top">'.$write['assist'].'</td>';
                       echo '<td class="" valign="top">'.$write['penalty'].'</td>';
                       
                       }
                      
                    echo '</tr>';
                  }
                  echo '</tbody>';
                  echo '<tfoot>';
	                 //listovani
                    $listovani->show_list();
                  echo '</tfoot>';
                  echo '</table>';
                  
              }else{
                echo '<p class="center">No club stats found for specified criteria</p>';
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