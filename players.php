<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");

$id=$_GET['id'];
$id=$input->valid_text($id,true,true);
$id=get_ID_from_url($id);
if (empty($id)){header("Location: /text/404-error.html");}

$query="SELECT * from players WHERE id=".$id;
if ($con->GetQueryNum($query)>0){
    $result = $con->SelectQuery($query);
    $write = $result->fetch_array();
    $StrName=$write["name"].' '.$write["surname"];
    $StrNameCyrillic=$write["name_cyrrilic"];
    if (empty($StrNameCyrillic)) $StrNameCyrillic =translit($StrName,2);
    
    $url='/arena/'.get_url_text($name,$write['id']);
    
    //pokud neni v DB stats poslednich 10 sezon=retired
	  $id_status=$write['id_status'];
	  $countRetired=$con->GetSQLSingleResult("SELECT count(*) as item FROM stats WHERE id_player=".$id." AND id_season>=".(ActSeasonWeb-10));
	  if ($countRetired==0)  {$id_status=4;}
	  $strStatus=$con->GetSQLSingleResult("SELECT name as item FROM players_status_list WHERE id=".$id_status."");
	  if ($id_status==2)  {$strStatus="&nbsp;";}
    
    $strMaiden_name=$write["maiden_name"];
    $strGender=$write["gender"];
    $intPosition=$write["id_position"];
    $strPosition=$write["id_position"];
    
    $strBirth_date=$write["birth_date"];
    $birth_date=$strBirth_date;
    if ($birth_date=="????-??-??"){
	    $birth_date="-";
    }else{
	    $birth_date=explode("-",$birth_date);
      if (!empty($birth_date[2])) $date_bith_day=$birth_date[2]; else $date_bith_day='??';
      if (!empty($birth_date[1])) $date_bith_month=$birth_date[1]; else $date_bith_month='??';
      if (!empty($birth_date[0])) $date_bith_year=$birth_date[0]; else $date_bith_year='????';
      $birth_date=$date_bith_day.'.'.$date_bith_month.'.'.$date_bith_year;
    }
    $strBirth_date=$birth_date;               
        
    $strBirth_place=$write["birth_place"];
    $strNationality=$write["nationality"];
    $strNationality2=$write["nationality_2"];
    $strWeight=$write["weight"];
    $strHeight=$write["height"];
    $strShoot=$write["id_shoot"];
    $intPhotoFolder=$write["id_photo_folder"];
    $intPhotoStatus=$write["id_photo_status"];
    
    
    $StrDetail=$con->GetSQLSingleResult("SELECT detail as item FROM players_details WHERE id_player=".$id);
     	  	
	  
}else{
    header("Location: /text/404-error.html");
}

$strHeaderKeywords='player profile '.$StrName.' '.$StrNameCyrillic.'';
$strHeaderDescription=$StrName.' profile - '.$StrNameCyrillic.' Профиль';
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
  
  <div id="logo"><?php require_once("inc/logo.inc"); ?></div>
  <div id="menu"><?php require_once("inc/menu.inc"); ?></div>
  <div id="main">
      
      <div id="text">
         <!-- main text -->
         
         <div id="navigation">
            <div class="toleft">
                <div class="corner_nav_left">&nbsp;</div>
                <div class="corner_nav_box"><a href="/players.html" title="Players">Players</a></div>
                <?php
                //countries
                  $query="SELECT id as item FROM countries WHERE shortcut='".$strNationality."' ORDER by item";
                  if ($con->GetQueryNum($query)>0){
                    echo '<div class="corner_nav_middle">&nbsp;</div>';
                    echo '<div class="corner_nav_box">';
                    $result = $con->SelectQuery($query);
                    $write = $result->fetch_array();
                      $NameCountry=$games->GetActualCountryName($write['item'],$intSeason);
                      echo '<a href="/country/'.get_url_text($NameCountry,$write['item']).'" title="'.$NameCountry.'">'.$NameCountry.'</a>';
                      echo '</div>';
                  }
                ?>
                <div class="corner_nav_middle">&nbsp;</div>
                <div class="corner_nav_box"><?php echo $StrName; ?></div>
                <div class="corner_nav_right">&nbsp;</div>
                
            </div>
         </div>

         <?php
         if ($_GET['test']==1 OR $_GET['preview']==1){

          
          if ($_GET['preview']==1){
              echo '<div id="sponsorship_show_header">This profile is sponsored by:</div>
              <div id="sponsorship_show">';
              $id_ads=$input->check_number($_GET['id_ads']);
              $query="SELECT * from sponsorship_ads WHERE id=".$id_ads."";
              if ($con->GetQueryNum($query)>0){
                $result = $con->SelectQuery($query);
                $write = $result->fetch_array();
                $strAds="";
                      switch ($write['id_type']) {
                        case 2:
                          if (!empty($write['id_image'])){
                            $strImagePath=PhotoFolder."/sponsorship/sponsorship_".$write['id'].".jpg";
                            if (file_exists($strImagePath)) {
                               $strAds= '<img src="/'.$strImagePath.'" alt="'.$write['name'].'" />';
                            }
                          }
                        break;
                        case 1:
                          $strAds=$write['html_text'];
                        break;
                      }
                    if (!empty($write['url'])){ echo '<a onclick="return !window.open(this.href);" href="'.$input->check_external_link($write['url']).'">';}
                    echo $strAds;
                    if (!empty($write['url'])){ echo '</a>';}
              }
              echo '</div>';

          }else{
            $query="SELECT * from sponsorship WHERE id_type=3 AND id_item=".$id." AND ( (date_expire>=NOW() AND id_status=2) OR (id_status=1) )";
            if ($con->GetQueryNum($query)>0){

              $query="SELECT sponsorship_ads.* from sponsorship INNER JOIN sponsorship_ads ON sponsorship.id_ads=sponsorship_ads.id WHERE sponsorship.id_type=3 AND sponsorship.id_item=".$id." AND ( (sponsorship.date_expire>=NOW() AND sponsorship.id_status=2))";
              if ($con->GetQueryNum($query)>0){
                $result = $con->SelectQuery($query);
                $write = $result->fetch_array();
                $strAds="";
                 echo '<div id="sponsorship_show">';
                      switch ($write['id_type']) {
                        case 2:
                          if (!empty($write['id_image'])){
                            $strImagePath=PhotoFolder."/sponsorship/sponsorship_".$write['id'].".jpg";
                            if (file_exists($strImagePath)) {
                               $strAds= '<img src="/'.$strImagePath.'" alt="'.$write['name'].'" />';
                            }
                          }
                        break;
                        case 1:
                          $strAds=$write['html_text'];
                        break;
                      }
                    if (!empty($write['url'])){ echo '<a onclick="return !window.open(this.href);" href="'.$input->check_external_link($write['url']).'">';}
                    echo $strAds;
                    if (!empty($write['url'])){ echo '</a>';}
                 echo '</div>';                    
              }
              
            }else{
              echo '<div id="sponsorship_show">';
              echo '<div class="center bold">Want your name or logo seen here? <a href="/sponsorship/'.get_url_text($StrName,$id).'?id_type=3" title="Sponsor this profile">Sponsor this profile</a>.</div>';
              echo '</div>';                    
            }

          }
         
         }
         ?>
         
         <div class="space">&nbsp;</div>
         
          <?php
              echo '<div id="item_preview" class="nophoto">';
              echo '<h1>'.$StrName.'</h1>';
              echo'<div>'.$StrNameCyrillic.'</div>';
              
              $strAllPhotos="";
              $bollShowPhoto=true;
         
              if ($bollShowPhoto){
                if ($intPhotoStatus==1){
                  $query="SELECT id,description,keywords  FROM photo WHERE show_item=1 AND (keywords LIKE '%player_".$id."%') ORDER by date_time DESC LIMIT 5";  
                }else{
                  $query="SELECT id,description  FROM photo WHERE show_item=1 AND (keywords LIKE '%".$StrName."%') ORDER by date_time DESC LIMIT 1";
              }
              
              $counter=0; 
              if ($con->GetQueryNum($query)>0){
               
               $boolFotoSingle=false;
                $result = $con->SelectQuery($query);
                while($write = $result->fetch_array()){
                  
                  $showPhotoKeyword=false;
                  if ($intPhotoStatus==1){
                    $strKeyword=split(" ",$write['keywords']);
                    //foreach ($strKeyword as $key => $val) {
                      //  $strKeyword[$key]=$val."_x";
                    //}
                    //print_r($strKeyword);
                    $strPlyersSearchID=trim("player_".$id);
                    //echo array_search($strPlyersSearchID,$strKeyword,true);
                    //echo 'player_'.$id.'/'.$write['keywords'].'x<br />';
                    if (in_array($strPlyersSearchID,$strKeyword,true)>0){
                      //echo $strKeyword[0]; 
                      $showPhotoKeyword=true;
                      //echo 'player_'.$id.'/'.$write['keywords'].'<br />';
                      
                      
                    }
                    
                  }else{
                    $showPhotoKeyword=true;
                  }
                  
                  $strPhotoPath=get_photo_name($write['id']);
	                if (file_exists(PhotoFolder."/".$strPhotoPath) and $showPhotoKeyword and !$boolFotoSingle) {
	                   $strAllPhotos.='<img src="/image/140-140-1-'.str_replace("-","_",$strPhotoPath).'" class="toleft player border" />';
                     $boolFotoSingle=true;
	                }
                  
                  
	              }
                  
              }
               
            }
            
            
            
            if (!empty($intPhotoFolder) and empty($strAllPhotos)){
            $query="SELECT id,description FROM photo WHERE show_item=1 AND 	id_photo_folder=".$intPhotoFolder." ORDER by date_time DESC LIMIT 1";
              if ($con->GetQueryNum($query)>0){
               
                $result = $con->SelectQuery($query);
                while($write = $result->fetch_array()){
                  $strPhotoPath=get_photo_name($write['id']);
	                if (file_exists(PhotoFolder."/".$strPhotoPath)) {
	                 
	                   $strAllPhotos.='<img src="/image/140-140-1-'.str_replace("-","_",$strPhotoPath).'" class="toleft player border" />';
                     
	                }
                  
                }
               
              }
            }
            
            if (!empty($strAllPhotos)){
                echo  $strAllPhotos;
            }
            
              
                echo '<ul class="toleft player">';
                  
                  
                  if (!empty($strMaiden_name))echo '<li><b>Maiden name:</b> '.$strMaiden_name.'</li>';
                  
                  if (!empty($strPosition)){
                    $strPosition=$con->GetSQLSingleResult("SELECT name  as item FROM players_positions_list WHERE id=".$strPosition);
                    echo '<li><b>Position:</b> '.$strPosition.'</li>';
                  }
                  
                  if (!empty($strStatus)){ echo '<li><strong>Player status:</strong> '.$strStatus.'</li>';}
                  if (!empty($strGender)){
                    if ($strGender==1) $strGender="male"; else $strGender="female";  
                    echo '<li><b>Gender:</b> '.$strGender.'</li>';
                  }
                  
                  if (!empty($strNationality)){
                    $strNationality=$con->GetSQLSingleResult("SELECT name as item FROM countries WHERE shortcut='".$strNationality."'").' '.get_flag(strtolower($strNationality),15,11);
                    if (!empty($strNationality2)){
                      $strNationality.=', '.$con->GetSQLSingleResult("SELECT name as item FROM countries WHERE shortcut='".$strNationality2."'").' '.get_flag(strtolower($strNationality2),15,11);
                    }
                    echo '<li><b>Nationality:</b> '.$strNationality.'</li>';
                  }
                  
                  if (!empty($strBirth_date))echo '<li><b>Birth date:</b> '.$strBirth_date.'</li>';
                  if (!empty($strBirth_place))echo '<li><b>Birth place:</b> '.$strBirth_place.'</li>';
                  
               
                echo '</ul>';
                
                echo '<ul class="toleft player">';
                  
                  if (!empty($strHeight)){
                    $intHeight=(($strHeight)/30.48);
                    $intHeight1= substr($intHeight,0,1);
                    $intHeight2= ($intHeight-$intHeight1);
                    $intHeight2= number_format($intHeight2, "1", ".", ",");
                    $intHeight2=number_format($intHeight2*12, "0", ".", ",");
                    //echo $intHeight.'<br />'; 
                    echo '<li><b>Height:</b> '.$strHeight.' cm /  '.($intHeight1).'\' '.($intHeight2).'"</li>';
                  }
                  if (!empty($strWeight)){
                    echo '<li><b>Weight:</b> '.$strWeight.' kg /  '.round(($strWeight*2.20462)).' lbs</li>';
                  }
                  
                  
                  if (!empty($strShoot)){
                    $strShoot=$con->GetSQLSingleResult("SELECT name  as item FROM players_shoots_list WHERE id=".$strShoot);
                    if ($intPosition==1) $strShootName="Catch"; else $strShootName="Shoots";
                    echo '<li><b>'.$strShootName.':</b> '.$strShoot.'</li>';
                  }
                  
                  
                  
                  $query_sub="SELECT * FROM players_draft WHERE id_player=".$id." ORDER by id ASC";
                  $result_sub = $con->SelectQuery($query_sub);
                  $i=1;
                  $intCount=$con->GetQueryNum($query_sub);
                  if ($intCount>0){
                  while($write_sub = $result_sub->fetch_array()){
                      if (!empty($write_sub['team_name'])){
                        if ($intCount>1) $i_name=' '.$i;
                        echo '<li><b>Draft'.$i_name.':</b> '.$write_sub['team_name'];
                        if (!empty($write_sub['d_year'])) echo ', <b>year:</b> '.$write_sub['d_year'].'';
                        if (!empty($write_sub['d_round'])) echo ', <b>round:</b> '.$write_sub['d_round'].'';
                        if (!empty($write_sub['d_position'])) echo ', <b>position:</b> '.$write_sub['d_position'].'';
                        echo '<br /><a href="/player/draft/'.$write_sub['d_year'].'.html" title="Show players drafted in '.$write_sub['d_year'].'">Show players drafted in '.$write_sub['d_year'].'</a></li>';  
                      }
                      $i++;
                  }
                  }
               
                echo '</ul>
                <div class="clear">&nbsp;</div>
                <div class="space">&nbsp;</div>
                ';
                if (!empty($StrDetail)){
                    echo '<div class="line">&nbsp;</div>';
                    echo '<ul>';
                    echo '<li><b>Trivia:</b> '.$StrDetail.'</li>';
                    echo '</ul>';
                    
                }
              echo '</div>';
               
    ?>
    
    <?php echo socials(0); ?>
    
    <?php
          $query="SELECT is_national_team,id_player,penalty,minutes,id_season,wins,losts,draws,coach_level_reached,coach_hired,coach_left,reason_of_leaving,id_club,id_league,id_season_type from stats INNER JOIN clubs ON stats.id_club=clubs.id WHERE id_player=".$id." AND id_stats_type=2 ORDER BY id_season DESC, stats.id DESC, id_season_type DESC";
          //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '<div class="header player_coach_stats"><span>Coach stats</span></div>';
                  echo '<table class="basic stats coach">';
                  echo '
                  <thead>
                  <tr>
                    <th class="link" valign="top">Season</th>
                    <th class="" valign="top">Team</th>
                    <th class="" valign="top">League</th>
                    <th class="" valign="top">Pos</th>
                    <th class="" valign="top">LPos</th>
                    <th class="" valign="top">W</th>
                    <th class="" valign="top">L</th>
                    <th class="" valign="top">D</th>
                    ';
                    }
                  echo '  
                  </tr>
                  </thead>
                  <tbody>
                  ';
                  $result = $con->SelectQuery($query);
                  $counter=0;
                  
                  while($write = $result->fetch_array()){
                    $counter++;
                    if ($counter%2) $style=""; else $style="dark";
  		              echo '<tr class="first noborder '.$style.'">';
  		                echo '<td valign="top">';
                      echo ($write['id_season']-1).'-'.substr($write['id_season'],2,2);
                      echo '</td>';
                       $StrClubName=$games->GetActualClubName($write['id_club'],$write['id_season']);
                       $StrLeagueName=$games->GetActualLeagueName($write['id_league'],$write['id_season']);
                       $StrClubCountry=$con->GetSQLSingleResult("SELECT id_country as item FROM clubs WHERE id=".$write['id_club']);
                       $StrLeagueNameYouth=$games->GetYouthLeague($write['id_league']);
                       $strUrlSeasonType="?type=".$write['id_season_type']."&amp;league=".$write['id_league'];
                       if ($write['id_season_type']>1){
                        $strSeasonType=$con->GetSQLSingleResult("SELECT name as item FROM games_stages WHERE id=".$write['id_season_type']);
                       }else {$strSeasonType="";}
                       echo '<td class="club nowrap" valign="top">';
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
                       echo '<td class="right" valign="top">'.$intMinutes.'</td>';
                       echo '<td class="right" valign="top">'.$write['wins'].'</td>';
                       echo '<td class="right" valign="top">'.$write['losts'].'</td>';
                       echo '<td class="right" valign="top">'.$write['draws'].'</td>';
                                             
                    echo '</tr>';
                    
                    $strCoachNextLine="";
                    if (!empty($write['coach_level_reached'])) $strCoachNextLine.='<b>Leavel reached:</b> '.$write['coach_level_reached'];
                    if ($write['coach_hired']<>"0000-00-00" and !empty($write['coach_hired'])) {
                      if  (!empty($strCoachNextLine))  $strCoachNextLine.=' | ';
                        $coach_hired=explode("-",$write['coach_hired']);
                        $coach_hired=$coach_hired[2].'.'.$coach_hired[1].'.'.$coach_hired[0];
                        $strCoachNextLine.='<b>Hired:</b> '.$coach_hired.' ';
                    }
                    if ($write['coach_left']<>"0000-00-00" and !empty($write['coach_left'])) {
                      if  (!empty($strCoachNextLine))  $strCoachNextLine.=' | ';
                      
                        $coach_left=explode("-",$write['coach_left']);
                        $coach_left=$coach_left[2].'.'.$coach_left[1].'.'.$coach_left[0];
                        $strCoachNextLine.='<b>Left:</b> '.$coach_left.' ';
                    }
                    if (!empty($write['reason_of_leaving'])) {
                      if  (!empty($strCoachNextLine))  $strCoachNextLine.=' | ';
                      $strCoachNextLine.='<b>Reason of leaving:</b> '.$write['reason_of_leaving'].' ';
                    }
                    if  (!empty($strCoachNextLine))  {
                      echo '<tr class="second noborder '.$style.'"><td>&nbsp;</td><td colspan="8">'.$strCoachNextLine.'</td></tr>';
                    }
                    
                 }
                  echo '</tbody>';
                  echo '</table>';
                  echo '<div class="space">&nbsp;</div>';
                  
              
         ?>
        
         
         <?php
         //national stats
          $query="SELECT is_national_team,id_player,id_season,games,games_dressed,goals,assist,penalty,plusminus,id_club,id_league,id_season_type,minutes,AVG,PCE,shotouts,(goals+assist) as points from stats INNER JOIN clubs ON stats.id_club=clubs.id WHERE id_player=".$id." AND id_stats_type=1 and is_national_team=2  ORDER BY id_season DESC, stats.id DESC, id_season_type DESC";
          //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '<div class="header player_national_stats"><span>Player national stats</span></div>';
                  echo '<table class="tablesorter basic stats" id="myTable1">';
                  echo '
                  <thead>
                  <tr>
                    <th class="link" valign="top">Season</th>
                    <th class="" valign="top">Team</th>
                    <th class="" valign="top">League</th>
                    ';
                    if ($intPosition==1){
                    echo'
                    <th class="right" valign="top">GP</th>
                    <th class="right" valign="top">GD</th>
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
                    ';
                    
                    }
                  echo '  
                  </tr>
                  </thead>
                  <tbody>
                  ';
                  $result = $con->SelectQuery($query);
                  $counter=0;
                  if ($intPosition==1) $strUrlGoalies="&amp;position=1";
                  
                  while($write = $result->fetch_array()){
                    $counter++;
                    //if ($counter%2) $style=""; else $style="dark";
  		              echo '<tr>';
  		                echo '<td valign="top">';
                      echo ($write['id_season']-1).'-'.substr($write['id_season'],2,2);
                      echo '</td>';
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
                       echo '<td valign="top">';
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
                       echo '<td class="right" valign="top">'.$write['games_dressed'].'</td>';
                       echo '<td class="right" valign="top">'.$write['minutes'].'</td>';
                       echo '<td class="right" valign="top">'.$write['AVG'].'</td>';
                       if ($write['PCE']==1 or $write['PCE']==100) $strPercent="1.000"; else $strPercent=$write['PCE'];
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
                       }
                      
                    echo '</tr>';
                  }
                  echo '</tbody>';
                  echo '</table>';
                  echo '<div class="space">&nbsp;</div>';
                  
              }
         ?>
         
         <?php
         //club stats
          $query="SELECT is_national_team,id_player,id_season,games,games_dressed,goals,assist,penalty,plusminus,id_club,id_league,id_season_type,minutes,AVG,PCE,shotouts,(goals+assist) as points from stats INNER JOIN clubs ON stats.id_club=clubs.id WHERE id_player=".$id." AND id_stats_type=1 and is_national_team<>2    ORDER BY id_season DESC, stats.id DESC, id_season_type DESC";
          //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '<div class="header player_club_stats"><span>Player club stats</span></div>';
                  echo '<table class="tablesorter basic stats" id="myTable2">';
                  echo '
                  <thead>
                  <tr>
                    <th class="link" valign="top">Season</th>
                    <th class="" valign="top">Team</th>
                    <th class="" valign="top">League</th>
                    ';
                    if ($intPosition==1){
                    echo'
                    <th class="right" valign="top">GP</th>
                    <th class="right" valign="top">GD</th>
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
                    ';
                    
                    }
                  echo '  
                  </tr>
                  </thead>
                  <tbody>
                  ';
                  $result = $con->SelectQuery($query);
                  $counter=0;
                  if ($intPosition==1) $strUrlGoalies="&amp;position=1";
                  
                  while($write = $result->fetch_array()){
                    $counter++;
                    //if ($counter%2) $style=""; else $style="dark";
  		              echo '<tr>';
  		                echo '<td valign="top">';
                      echo ($write['id_season']-1).'-'.substr($write['id_season'],2,2);
                      echo '</td>';
                       $StrClubName=$games->GetActualClubName($write['id_club'],$write['id_season']);
                       $StrLeagueName=$games->GetActualLeagueShortcut($write['id_league'],$write['id_season']);
                       $StrLeagueNameAnch=$games->GetActualLeagueName($write['id_league'],$write['id_season']);
                       $StrClubCountry=$con->GetSQLSingleResult("SELECT id_country as item FROM clubs WHERE id=".$write['id_club']);
                       $StrLeagueNameYouth=$games->GetYouthLeague($write['id_league']);
                       $strUrlSeasonType="?type=".$write['id_season_type']."&amp;league=".$write['id_league'];
                       if ($write['id_season_type']>1){
                        $strSeasonType=$con->GetSQLSingleResult("SELECT name as item FROM games_stages WHERE id=".$write['id_season_type']);
                       }else {$strSeasonType="";}
                       
                       echo '<td class="club nowrap" valign="top">';
                       echo get_flag(strtolower($StrClubCountry),15,11);
                       echo '<a href="/stats/club/'.$write['id_season'].'/'.get_url_text($StrClubName,$write['id_club']).$strUrlSeasonType.$strUrlGoalies.'" title="Show stats in selected season: '.$StrClubName.'">'.$StrClubName.'</a>';
                       echo '</td>';
                       echo '<td valign="top">';
                       echo '<a href="/stats/league/'.$write['id_season'].'/'.get_url_text($StrLeagueName,$write['id_league']).$strUrlSeasonType.$strUrlGoalies.'" title="Show stats in selected season: '.$StrLeagueNameAnch.'">'.$StrLeagueName.'</a>';
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
                       echo '<td class="right" valign="top">'.$write['games_dressed'].'</td>';
                       echo '<td class="right" valign="top">'.$write['minutes'].'</td>';
                       echo '<td class="right" valign="top">'.$write['AVG'].'</td>';
                       if ($write['PCE']==1 or $write['PCE']==100) $strPercent="1.000"; else $strPercent=$write['PCE'];
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
                       }
                      
                    echo '</tr>';
                  }
                  echo '</tbody>';
                  echo '</table>';
                  echo '<div class="space">&nbsp;</div>';
                  
              }
         ?>
         
         <div class="space">&nbsp;</div>
         
         
         <?php
         $strAllPhotos="";
         $bollShowPhoto=true;
         
         if ($bollShowPhoto){
              if ($intPhotoStatus==1){
                $query="SELECT id,description,keywords FROM photo WHERE show_item=1 AND (keywords LIKE '%player_".$id."%') ORDER by date_time DESC";  
              }else{
                $query="SELECT id,description  FROM photo WHERE show_item=1 AND (keywords LIKE '%".$StrName."%') ORDER by date_time DESC";
              }
              
              
              $counter=0; 
              if ($con->GetQueryNum($query)>0){
               
                $result = $con->SelectQuery($query);
                while($write = $result->fetch_array()){
                  
                  $showPhotoKeyword=false;     
                  if ($intPhotoStatus==1){
                    $strKeyword=split(" ",$write['keywords']);
                    if (in_array("player_".$id,$strKeyword,true)>0){
                      $showPhotoKeyword=true;
                    }
                    
                  }else{
                    $showPhotoKeyword=true;
                  }
                  
                  $strPhotoPath=get_photo_name($write['id']);
                  if (file_exists(PhotoFolder."/".$strPhotoPath) and $showPhotoKeyword) {
	                 
	                   $strAllPhotos.='<li><a href="/'.PhotoFolder."/".$strPhotoPath.'" title="'.$write['description'].'" class="pirobox_gall">
	                  <span style="background-image: url(/image/150-150-1-'.str_replace("-","_",$strPhotoPath).');" class="image">&nbsp;</span>
                    </a></li>';
                     
	                }
	                else{
                    //$strAllPhotos.='<span style="background-image: url(/image/140-140-1-'.str_replace("-","_","default.gif").');" class="image">&nbsp;</span>';
                  }
                  
                  $counter++;
                  
                }
               
              }
            }
            
            
            if (!empty($intPhotoFolder)){
            $query="SELECT id,description, FROM photo WHERE show_item=1 AND 	id_photo_folder=".$intPhotoFolder." ORDER by date_time DESC";
              if ($con->GetQueryNum($query)>0){
               
                $result = $con->SelectQuery($query);
                while($write = $result->fetch_array()){
                  $strPhotoPath=get_photo_name($write['id']);
	                if (file_exists(PhotoFolder."/".$strPhotoPath)) {
	                 
	                   $strAllPhotos.='<li><a href="/'.PhotoFolder."/".$strPhotoPath.'" title="'.$write['description'].'" class="pirobox_gall">
	                  <span style="background-image: url(/image/150-150-1-'.str_replace("-","_",$strPhotoPath).');" class="image">&nbsp;</span>
                    </a></li>';
                     
	                }
	                else{
                    //$strAllPhotos.='<span style="background-image: url(/image/140-140-1-'.str_replace("-","_","default.gif").');" class="image">&nbsp;</span>';
                  }
                  
                  $counter++;
                }
               
              }
            }
            
            if (!empty($strAllPhotos)){
            
              echo '<div class="header_cufon blue_595"><span class="header_text blue">Player photos</span></div>';
              echo '
               
               <div id="photo_slider" class="box">
               <ul id="mycarousel" class="jcarousel-skin-tango">';
                   echo  $strAllPhotos;
                   
              echo '</ul>';
              echo '</div>';
              
                echo '<div class="space">&nbsp;</div>';
            
            }
            
         ?>
         
         <div class="line">&nbsp;</div>
         <p class="center">Did you find any incorrect or incomplete information? <a href="/feedback.html?link=<?php echo curPageURL(); ?>">Please, let us know</a>.</p>
         <div class="space">&nbsp;</div>
    
              
              <?php
              $query="SELECT articles.id,articles.header,date_time FROM articles
                   INNER JOIN articles_items ON articles.id=articles_items.id_article  
                   WHERE 
                      articles.show_item=1 AND articles.pub_date<=NOW() AND articles.pub_date<=NOW() AND (expire_date>=NOW() OR expire_date='0000-00-00 00:00:00')
                      AND articles_items.id_item_type=4 AND articles_items.id_item=".$id."  
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
	                }
                  
               echo '</div>';
                      
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
        
        
        <?php 
        
        show_ads("ads_col_right_player");
        
        require_once("inc/col_right_player_search.inc"); 
        
        show_ads("ads_col_right_player2");
        
            
              $query="SELECT date_time, id_player,id_position,id_club_from,id_club_to,id_country_from,id_country_to,id_retire_status,source,note FROM transfers WHERE id_player=".$id." ORDER BY date_time DESC  LIMIT 4";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '
                  <div class="header player_transfers"><span>Player carrer transfers</span><span class="toright"><a href="/transfers.html" title="Show all transfers">Show all&raquo;</a></span></div>
                  <div class="box_items">
                  ';
                  echo '<ol>';
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    echo '<li>';
                      echo '<span>
                              <strong>'.date("d M Y",strtotime($write['date_time'])).'</strong>
                            </span>
                            ';
                            echo '<div class="clear">&nbsp;</div>';
                            //from club/country
                            
                            echo '<span>';
                            if (empty($write['id_club_from']) and empty($write['id_country_from'])){
                              echo 'unknown';
                            }else
                            {
                            if (!empty($write['id_club_from'])){
                              $ItemName=$games->GetActualClubName($write['id_club_from'],ActSeasonWeb);
                              $CountryShortCut=$con->GetSQLSingleResult("SELECT id_country as item FROM clubs WHERE id=".$write['id_club_from']);
                              if (empty($CountryShortCut)){
                                
                                $LeagueID=$con->GetSQLSingleResult("SELECT id_league as item FROM clubs_leagues_items WHERE id_club=".$write['id_club_from']." ORDER by id DESC");
                                if (!empty($LeagueID)){
                                  $CountryShortCut=$con->GetSQLSingleResult("SELECT (SELECT shortcut FROM countries WHERE leagues_countries_items.id_country=countries.id LIMIT 1) as item FROM leagues_countries_items WHERE id_league=".$LeagueID." ORDER by id DESC LIMIT 1");
                                }
                              }
                              echo get_flag(strtolower($CountryShortCut),15,11);
                              echo '<a href="/club/'.get_url_text($ItemName,$write['id_club_from']).'" title="Show club: '.$ItemName.'">'.$ItemName.'</a>';
                              
                              
                            }else{
                              $ItemName=$games->GetActualCountryName($write['id_country_from'],ActSeasonWeb);
                              $CountryShortCut=$con->GetSQLSingleResult("SELECT shortcut as item FROM countries WHERE id=".$write['id_country_from']);
                              echo get_flag(strtolower($CountryShortCut),15,11);
                              echo '<a href="/country/'.get_url_text($ItemName,$write['id_country_from']).'" title="Show country: '.$ItemName.'">'.$ItemName.'</a>';
                              
                            }
                            }
                            echo '</span>';
                            
                            echo '<img src="/img/arrow_right.png" class="arrow_right" alt="arrow" height="9" width="12" />';
                            
                            //to club/country
                            echo '<span>';
                            if (empty($write['id_club_to']) and empty($write['id_country_to'])){
                              if ($write['id_retire_status']>1){
                                echo $con->GetSQLSingleResult("SELECT name as item FROM transfers_retire_list WHERE id=".$write['id_retire_status']); 
                              }else{
                                echo 'unknown';
                              } 	
                            }else
                            {
                            if (!empty($write['id_club_to'])){
                              $ItemName=$games->GetActualClubName($write['id_club_to'],ActSeasonWeb);
                              $CountryShortCut=$con->GetSQLSingleResult("SELECT id_country as item FROM clubs WHERE id=".$write['id_club_to']);
                              if (empty($CountryShortCut)){
                                
                                $LeagueID=$con->GetSQLSingleResult("SELECT id_league as item FROM clubs_leagues_items WHERE id_club=".$write['id_club_to']." ORDER by id DESC");
                                if (!empty($LeagueID)){
                                  $CountryShortCut=$con->GetSQLSingleResult("SELECT (SELECT shortcut FROM countries WHERE leagues_countries_items.id_country=countries.id LIMIT 1) as item FROM leagues_countries_items WHERE id_league=".$LeagueID." ORDER by id DESC LIMIT 1");
                                }
                              }
                              echo get_flag(strtolower($CountryShortCut),15,11);
                              echo '<a href="/club/'.get_url_text($ItemName,$write['id_club_to']).'" title="Show club: '.$ItemName.'">'.$ItemName.'</a>';
                              
                              
                            }else{
                              //
                              $ItemName=$games->GetActualCountryName($write['id_country_to'],ActSeasonWeb);
                              $CountryShortCut=$con->GetSQLSingleResult("SELECT shortcut as item FROM countries WHERE id=".$write['id_country_to']);
                              echo get_flag(strtolower($CountryShortCut),15,11);
                              echo '<a href="/country/'.get_url_text($ItemName,$write['id_country_to']).'" title="Show country: '.$ItemName.'">'.$ItemName.'</a>';
                              
                            }
                            }
                            echo '</span>';
                            echo '<span class="clear">&nbsp</span>';
                            
                            $strAllTransfer="";
                            if (!empty($write['source'])) {
                              if (!empty($strAllTransfer)) $strAllTransfer.=", ";
                              $strSource = preg_replace('#(http://|ftp://|(www\.))([\w\-]*\.[\w\-\.]*([/?][^\s]*)?)#e',"'<a onclick=\"return !window.open(this.href);\" href=\"'.('\\1'=='www.'?'http://':'\\1').'\\2\\3\">'.((strlen('\\2\\3')>23)?(substr('\\2\\3',0,20).'&hellip;'):'\\2\\3').'</a>'",$write['source']); 
                              $strAllTransfer.="<br /><b>Source:</b> ".$strSource;
                            }
                            if (!empty($write['note'])) {
                              if (!empty($strAllTransfer)) $strAllTransfer.=", ";
                              $strNote = preg_replace('#(http://|ftp://|(www\.))([\w\-]*\.[\w\-\.]*([/?][^\s]*)?)#e',"'<a onclick=\"return !window.open(this.href);\" href=\"'.('\\1'=='www.'?'http://':'\\1').'\\2\\3\">'.((strlen('\\2\\3')>23)?(substr('\\2\\3',0,20).'&hellip;'):'\\2\\3').'</a>'",$write['note']); 
                              $strAllTransfer.="<br /><b>Note:</b> ".$strNote;
                          
                           }
                           echo $strAllTransfer;
                            
                    echo '</li>';
                    
                  }
                  echo '</ol>
                  </div>
                 <div class="space">&nbsp;</div>
                ';
                  
              }
              
              ?>
            
         
        <?php require_once("inc/col_right_games_list.inc"); ?>
        
        <?php require_once("inc/col_right_leagues_list.inc"); ?>
        
        
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