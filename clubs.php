<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");

$id=$_GET['id'];
$id=$input->valid_text($id,true,true);
$id=get_ID_from_url($id);
if (empty($id)){header("Location: /text/404-error.html");}

//leagues
if (empty($_GET['league'])) {
     $query="SELECT stats.id_league from stats  
              INNER JOIN leagues ON leagues.id=stats.id_league 
              WHERE stats.id_club=".$id."  ORDER by id_season DESC,id_season_type 	 DESC,leagues.id_order ASC,league_status ASC,leagues.youth_league ASC";
     //echo $query; 
       
}else {
    $intLeague=$_GET['league'];
    $intLeague=$input->valid_text($intLeague,true,true);
    $query="SELECT id as id_league from leagues WHERE id=".$intLeague;
}
$intCount=$con->GetQueryNum($query);
$counter=0;
if ($intCount>0){
   $result = $con->SelectQuery($query);
   $write = $result->fetch_array();
   $id_league=$write['id_league'];
    
}

if (empty($_GET['season'])){ 
  $query="SELECT DISTINCT id_season FROM stats WHERE id_league=".$id_league." AND id_club=".$id." AND id_stats_type=1 ORDER BY id_season DESC LIMIT 1";
  //echo $query; 
  if ($con->GetQueryNum($query)>0){
       $result = $con->SelectQuery($query);
       $write = $result->fetch_array();
       $intSeason=$write['id_season'];
  }else{
    $intSeason=ActSeasonWeb;
  }
}


else {$intSeason=$_GET['season'];}

$query="SELECT * from clubs WHERE id=".$id;
if ($con->GetQueryNum($query)>0){
    $result = $con->SelectQuery($query);
    $write = $result->fetch_array();
    $StrClubName=$games->GetActualClubName($write['id'],$intSeason);
    $strCountry=$write['id_country'];
    $id_status=$write['id_status'];
    $id_status_info=$write['id_status_info'];
    $is_national_team=$write['is_national_team'];
    $name=$write['name'];
    $short_name=$write['short_name'];
    $nickname=$write['nickname'];
    $name_original=$write['name_original'];
    $id_country=$write['id_country'];
    //$id_league=$write['id_league'];
    $year_founded=$write['year_founded'];
    $address=$write['address'];
    $city=$write['city'];
    $telephone=$write['telephone'];
    $fax=$write['fax'];
    $email_1=$write['email_1'];
    $email_2=$write['email_2'];
    $email_3=$write['email_3'];
    $email_1_note=$write['email_1_note'];
    $email_2_note=$write['email_2_note'];
    $email_3_note=$write['email_3_note'];
    $colours=$write['colours'];
    $brief_history=$write['brief_history'];
    $achievments=$write['achievments'];
    $team_management=$write['team_management'];
    
    
}else{
    header("Location: /text/404-error.html");
}

$strHeaderKeywords='club details '.$StrClubName.'';
$strHeaderDescription=$StrClubName.' details';
require_once("inc/head.inc");

?>
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
                  $query="SELECT countries.id FROM leagues_countries_items INNER JOIN countries ON leagues_countries_items.id_country=countries.id WHERE leagues_countries_items.id_league=".$LeagueIDMain." ORDER by leagues_countries_items.item";
                  $intCount=$con->GetQueryNum($query);
                  $counter=0;
                  if ($intCount>0){
                    echo '<div class="corner_nav_middle">&nbsp;</div>';
                    echo '<div class="corner_nav_box">';
                    $result = $con->SelectQuery($query);
                    while($write = $result->fetch_array()){
                      $NameCountry=$games->GetActualCountryName($write['item'],$intSeason);
                      echo '<a href="/league/'.get_url_text($NameCountry,$write['item']).'?season='.$intSeason.'" title="'.$NameCountry.'">'.$NameCountry.'</a>';
                      $counter++;
                      if ($counter<$intCount){ echo ' / ';}
                    }
                    echo '</div>';
                  }
                }
                
              $query="SELECT id as id_league from leagues WHERE id=".$id_league;
              $intCount=$con->GetQueryNum($query);
              $counter=0;
              if ($intCount>0){
                  $result = $con->SelectQuery($query);
                  $strLeaguesHeader.='<div class="corner_nav_middle">&nbsp;</div>';
                  $strLeaguesHeader.='<div class="corner_nav_box">';
                  while($write = $result->fetch_array()){
                   $NameLeague=$games->GetActualLeagueName($write['id_league'],$intSeason);
                   $strLeaguesHeader.='<a href="/league/'.get_url_text($NameLeague,$write['id_league']).'" title="'.$NameLeague.'">'.$NameLeague.'</a>';
                   $counter++;
                   $id_league=$write['id_league'];
                   if ($counter<$intCount){ $strLeaguesHeader.= ' / ';}
                  }
                $strLeaguesHeader.='</div>';
                }
                echo $strLeaguesHeader; 

                ?>
                <div class="corner_nav_middle">&nbsp;</div>
                <div class="corner_nav_box"><?php echo $StrClubName; ?></div>
                <div class="corner_nav_right">&nbsp;</div>
                
                
            </div>
            
            <div class="toright">
            
            </div>
            
            <div class="clear">&nbsp;</div>
         </div>

         <div class="clear">&nbsp;</div>

          <?php
         //sponsorship
         show_sponsorship($id,$StrClubName,2);
         ?>
         <div class="space">&nbsp;</div>
         
         
         
          
         
         <div id="item_logo">
          
          <?php 
           $intClubLogo=$games->GetActualClubLogo($id,$intSeason);
           
           if (!empty($intClubLogo)){
	           $strPhotoPath=get_photo_name($intClubLogo);
	           if (file_exists(PhotoFolder."/".$strPhotoPath)) {
              echo '<img src="/image/160-160-1-'.str_replace("-","_",$strPhotoPath).'" alt="'.$StrClubName.' logo" />';
             }else{
              echo '<img src="/img/default.jpg" width="190" alt="Logo not found" />';
             }
	         }else{echo '<img src="/img/default.jpg" width="190" alt="Logo not found" />';}
           ?>
         </div>
         
         <div id="item_preview">
            <h1><?php echo $StrClubName; ?></h1>
            <ul>
            <?php
              if (!empty($short_name))  echo '<li><strong>Short name:</strong> '.$short_name.'</li>';
              if (!empty($name_original)) echo '<li><strong>Name in original language:</strong> '.$name_original.'</li>';
              if (!empty($nickname)) echo '<li><strong>Nickname:</strong> '.$nickname.'</li>';
              if (!empty($year_founded)) echo '<li><strong>Year of foundation:</strong> '.$year_founded.'</li>';
              if (!empty($colours)) echo '<li><strong>Colours:</strong> '.$colours.'</li>';
              
              $strStatus=$con->GetSQLSingleResult("SELECT name as item FROM clubs_status_list WHERE id=".$id_status);
              if (!empty($id_status_info)) $strStatusInfo=" (".$id_status_info.")";
              echo '<li><strong>Club status:</strong> '.$strStatus.$strStatusInfo.'</li>';
              
              if (!empty($address)) echo '<li><strong>Address:</strong> '.nl2br($address).'</li>';
              if (!empty($telephone)) echo '<li><strong>Phone:</strong> '.$telephone.'</li>';
              if (!empty($fax)) echo '<li><strong>Fax:</strong> '.$fax.'</li>';
              
              if (!empty($email_1_note)) $StrEmail_1_note=" (".$email_1_note.")";
              if (!empty($email_1)) $email_1='<a href="mailto:'.$email_1.'">'.$email_1.'</a>'.$StrEmail_1_note;
              
              if (!empty($email_2_note)) $StrEmail_2_note=" (".$email_2_note.")";
              if (!empty($email_2)) $email_2=', <a href="mailto:'.$email_2.'">'.$email_2.'</a>'.$StrEmail_2_note;
              
              if (!empty($email_3_note)) $StrEmail_3_note=" (".$email_3_note.")";
              if (!empty($email_3)) $email_3=', <a href="mailto:'.$email_3.'">'.$email_3.'</a>'.$StrEmail_3_note;
              
              if (!empty($email_1) or !empty($email_2) or !empty($email_3)) echo '<li><strong>Email:</strong> '.$email_1.''.$email_2.''.$email_3.'</li>';
              
              $query_sub="SELECT * FROM clubs_links WHERE id_club=".$id." and id_type=1 ORDER by name";
              //echo $query; 
              $intPocetLinks=$con->GetQueryNum($query_sub);
              $counter=0;
              if ($intPocetLinks){
                  echo '<li><strong>WWW:</strong> ';
                  $result_sub = $con->SelectQuery($query_sub);
                  while($write_sub = $result_sub->fetch_array()){
                      if (empty($write_sub['link_name'])) $strLinkName=$write_sub['name']; else $strLinkName=$write_sub['link_name'];
                      echo '<a onclick="return !window.open(this.href);" href="'.$write_sub['name'].'">'.$strLinkName.'</a>';
                      $counter++;
                      if ($counter<$intPocetLinks) echo ', ';
                      
                  }
                  echo '</li>';
              }
              
            
            ?>
            </ul>
            
            
            
         </div>   
         <div class="clear">&nbsp;</div>
         
         <?php 
               $strRelatedLeagues="";
               // klub ma farmu
               $query="SELECT * FROM clubs_farm_items WHERE (id_club=".$id." AND	id_farm_statut=1) OR (id_farm=".$id." AND	id_farm_statut=2)";
               $intCount=$con->GetQueryNum($query);
                $counter=0;
                if ($intCount>0){
                  $strRelatedLeagues.='<b>'.$StrClubName.' is major league affiliate of:</b> ';
                  $result = $con->SelectQuery($query);
                   while($write = $result->fetch_array()){
                      if ($write['id_club']==$id) $id_farm=$write['id_farm']; else $id_farm=$write['id_club'];
                      $StrFarmClubName=$games->GetActualClubName($id_farm,$intSeason);
                      $strRelatedLeagues.='<a href="/club/'.get_url_text($StrFarmClubName,$id_farm).'?season='.$intSeason.'">'.$StrFarmClubName.'</a>';
                      $counter++;
                      $strRelatedLeagues.="<br />";
                   }
                }
                
                // klub je farma
               $query="SELECT * FROM clubs_farm_items WHERE (id_club=".$id." AND	id_farm_statut=2) OR (id_farm=".$id." AND	id_farm_statut=1)";
               $intCount=$con->GetQueryNum($query);
                $counter=0;
                if ($intCount>0){
                  $strRelatedLeagues.='<b>'.$StrClubName.' is minor league affiliate of:</b> ';
                  $result = $con->SelectQuery($query);
                   while($write = $result->fetch_array()){
                      if ($write['id_club']==$id) $id_farm=$write['id_farm']; else $id_farm=$write['id_club'];
                      $StrFarmClubName=$games->GetActualClubName($id_farm,$intSeason);
                      $strRelatedLeagues.='<a href="/club/'.get_url_text($StrFarmClubName,$id_farm).'?season='.$intSeason.'">'.$StrFarmClubName.'</a>';
                      $counter++;
                      $strRelatedLeagues.="<br />";
                   }
                }
                
                 // klub spolupracuje
               $query="SELECT * FROM clubs_farm_items WHERE (id_club=".$id." OR	id_farm=".$id.") AND	id_farm_statut=3";
               $intCount=$con->GetQueryNum($query);
                $counter=0;
                if ($intCount>0){
                  $strRelatedLeagues.='<b>'.$StrClubName.' is cooperating club with:</b> ';
                  $result = $con->SelectQuery($query);
                   while($write = $result->fetch_array()){
                      if ($write['id_club']==$id) $id_farm=$write['id_farm']; else $id_farm=$write['id_club'];
                      $StrFarmClubName=$games->GetActualClubName($id_farm,$intSeason);
                      $strRelatedLeagues.='<a href="/club/'.get_url_text($StrFarmClubName,$id_farm).'?season='.$intSeason.'">'.$StrFarmClubName.'</a>';
                      $counter++;
                      $strRelatedLeagues.="<br />";
                   }
                }
                if (!empty($strRelatedLeagues)) {
                echo '
                  <div class="box club_leagues">
                  '.$strRelatedLeagues.'
                 </div>
                ';
                }
                ?>
         
         <?php echo socials(0); ?>
         
         <p class="center">Did you find any incorrect or incomplete information? <a href="/feedback.html?link=<?php echo curPageURL(); ?>">Please, let us know</a>.</p>
         
          <?php
          
           //leagues
                //nove - vsechny ligy ve stats
                $query="SELECT DISTINCT id_league,name from stats INNER JOIN leagues ON leagues.id=stats.id_league WHERE stats.id_club=".$id." ORDER by id_order ASC, youth_league ASC,youth_league_id ASC,name ASC";
                //echo $query; 
                $intCount=$con->GetQueryNum($query);
                $counter=0;
                if ($intCount>0){
                  echo '<div class="header_cufon blue_490"><span class="header_text blue">Select league</span></div>
                  <div class="box">
                  <p><b>All time leagues:</b>
                  ';
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                      $NameLeague=$games->GetActualLeagueName($write['id_league'],$intSeason);
                      echo '<a href="/club/'.get_url_text($StrClubName,$id).'?league='.$write['id_league'].'">';
                      if ($write['id_league']==$id_league) echo '<span class="link bold">';
                      echo $NameLeague;
                      if ($write['id_league']==$id_league) echo '</span>';
                      echo '</a>';
                      $counter++;
                      if ($counter<$intCount) echo " | ";
                      
                  }
                  echo '</p></div>';
                  echo '<div class="space">&nbsp;</div>';
                }
          
          
          echo '<h3>Season '.($intSeason-1).'-'.$intSeason.'</h3>'; 
          
          //leagues
                //nove - ligy ve stats pro vybranou sezonu
                $query="SELECT DISTINCT id_league,name from stats INNER JOIN leagues ON leagues.id=stats.id_league WHERE stats.id_club=".$id." and stats.id_season=".$intSeason."  ORDER by id_order ASC, youth_league ASC,youth_league_id ASC,name ASC";
                //echo $query; 
                $intCount=$con->GetQueryNum($query);
                $counter=0;
                if ($intCount>0){
                  echo '<div class="header club_teams"><span>Club teams</span></div>
                  <div class="box">
                  <p><b>This club in '.($intSeason-1).'-'.$intSeason.':</b>
                  ';
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                      $NameLeague=$games->GetActualLeagueName($write['id_league'],$intSeason);
                      echo '<a href="/club/'.get_url_text($StrClubName,$id).'?league='.$write['id_league'].'&amp;season='.$intSeason.'">';
                      if ($write['id_league']==$id_league) echo '<span class="link bold">';
                      echo $NameLeague;
                      if ($write['id_league']==$id_league) echo '</span>';
                      echo '</a>';
                      $counter++;
                      if ($counter<$intCount) echo " | ";
                      
                  }
                  echo '</p></div>';
                  echo '<div class="space">&nbsp;</div>';
                }
          
          
              //$query="SELECT date_time, id_player,id_position,id_club_from,id_club_to,id_country_from,id_country_to,id_retire_status FROM transfers WHERE ((id_club_from=".$id." AND id_league_from=".$id_league.") OR (id_club_from=".$id." AND id_league_from=0)) OR ((id_club_to=".$id." AND id_league_to=".$id_league.") OR (id_club_to=".$id." AND id_league_to=0))  AND date(date_time)>=date('".($intSeason-1)."-06-01') AND date(date_time)<date('".$intSeason."-06-01') ORDER BY date_time DESC  LIMIT 4";
              $query="SELECT date_time, id_player,id_position,id_club_from,id_club_to,id_country_from,id_country_to,id_retire_status FROM transfers WHERE 
              
              id_source_note=1 AND transfers.date_time < '".$intSeason."-07-01 00:00:00' AND(
              
              ((id_club_from=".$id." AND id_league_from=".$id_league.") OR (id_club_from=".$id." AND id_league_from=0)) OR ((id_club_to=".$id." AND id_league_to=".$id_league.") OR (id_club_to=".$id." AND id_league_to=0)) 
              
              )
              
              ORDER BY date_time DESC  LIMIT 4";
              if ($con->GetQueryNum($query)>0){
                  echo '<div class="header recent_transfers_long"><span>Recent transfers</span><span class="toright"><a href="/transfers.html" title="Show all transfers">Show all transfers&raquo;</a></span></div>';
                  echo '<table class="basic transfers">';
                  $result = $con->SelectQuery($query);
                  $counter=0;
                  while($write = $result->fetch_array()){
                    $counter++;
                    if ($counter%2) $style="dark"; else $style="";
  		              echo '<tr class="'.$style.'">';
                      
                      echo '<td class="date" valign="top">'.date("M. d y",strtotime($write['date_time'])).' | </td>';
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
                              echo '<a href="/club/'.get_url_text($ItemName,$write['id_club_from']).'?season='.$intSeason.'" title="Show club profile: '.$ItemName.'">'.$ItemName.'</a>';
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
                              echo '<a href="/club/'.get_url_text($ItemName,$write['id_club_to']).'?season='.$intSeason.'" title="Show club profile: '.$ItemName.'">'.$ItemName.'</a>';
                              
                              
                              
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
         
         function show_game_line ($id_game){
          global $con,$games;
         
                      $query="SELECT * FROM games  WHERE id=".$id_game;
                      $result = $con->SelectQuery($query);
                      $write = $result->fetch_array();
                      $strDate=date("M. d y",strtotime($write['date']));
                      $GameName=$strDate;
                      if ($write['time']<>"00:00:00"){
                        $strTime=date("H:i",strtotime($write['time']));
                        $GameName.=', '.$strTime.' ';
                      }else{
                        $strTime="";
                      }
                      
                      echo '<td class="date" valign="top">'.$strDate;
                      if (!empty($strTime)) echo ' | '.$strTime;
                      echo '</td>';
                      echo '<td valign="top" class="right">';
                              $ItemName=$games->GetActualClubName($write['id_club_home'],$write['id_season']);
                              $GameName.=$ItemName.' - ';
                              //$CountryShortCut=$con->GetSQLSingleResult("SELECT id_country as item FROM clubs WHERE id=".$write['id_club_home']);
                              //echo get_flag(strtolower($CountryShortCut),15,11);
                              echo '<a href="/club/'.get_url_text($ItemName,$write['id_club_home']).'?season='.$write['id_season'].'" title="Show club profile: '.$ItemName.'">'.$ItemName.'</a>';
                      echo '</td>';
                      echo '<td valign="top" class="slash center">&nbsp;-&nbsp;</td>';
                      echo '<td valign="top">';        
                              $ItemName=$games->GetActualClubName($write['id_club_visiting'],$write['id_season']);
                              $GameName.=$ItemName;
                              //$CountryShortCut=$con->GetSQLSingleResult("SELECT id_country as item FROM clubs WHERE id=".$write['id_club_visiting']);
                              //echo get_flag(strtolower($CountryShortCut),15,11);
                              echo '<a href="/club/'.get_url_text($ItemName,$write['id_club_visiting']).'?season='.$write['id_season'].'" title="Show club profile: '.$ItemName.'">'.$ItemName.'</a>';
                      echo '</td>';
                      echo '<td class="score">';
                      if (!is_null($write['home_score']) and !is_null($write['visiting_score'])) echo '<span class="link"><a href="/game/detail/'.get_url_text($GameName,$write['id']).'" title="Show game details: '.$GameName.'">';
                      echo $games->GetScore($write['id'],1);
                      if (!is_null($write['home_score']) and !is_null($write['visiting_score'])) echo '</a>';
                      echo '</span></td>';
                      echo '<td class="link">';
                      if (!is_null($write['home_score']) and !is_null($write['visiting_score'])) echo '<span class="link"><a href="/game/detail/'.get_url_text($GameName,$write['id']).'" title="Show game details: '.$GameName.'">Show details&raquo;</a></span>';
                      echo '</td>';
         }
         
              $query="SELECT id FROM games  WHERE (id_club_home=".$id." OR	id_club_visiting=".$id.") AND id_season=".$intSeason." AND date<=date(NOW()) AND id_league=".$id_league." ORDER BY date DESC,	time DESC LIMIT 3";
              //echo $query;
              $intCountPast=$con->GetQueryNum($query);
              $counter=0;
              if ($intCountPast>0){
                  echo '<div class="header recent_games_long"><span>Recent games</span><span class="toright"><a href="/games.html?id_season='.$intSeason.'&amp;id_show=2&amp;id_league='.get_url_text($NameLeague,$id_league).'&amp;id_club='.get_url_text($StrClubName,$id).'" title="Show all games from season '.($intSeason-1).'-'.$intSeason.'">season '.($intSeason-1).'-'.$intSeason.'</a></span></div>';
                  
                  echo '<table class="basic games">';
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                      $GamesArray[$counter]=$write['id'];
                      $counter++;
                  }
                  
                  $counter=1;
                  $GamesArray=array_reverse($GamesArray);
                  foreach ($GamesArray as &$value) {
                    
                     if ($counter%2) $style="dark"; else $style="";
  		                echo '<tr class="'.$style.'">';
  		                show_game_line ($value);
                      echo '</tr>';
                    $counter++;  
                  } 
              }
              $intCountFuture=5-$intCountPast;
              $query="SELECT id FROM games  WHERE (id_club_home=".$id." OR	id_club_visiting=".$id.") AND id_season=".$intSeason." AND date>date(NOW()) AND id_league=".$id_league." ORDER BY date ASC,	time ASC LIMIT ".$intCountFuture;
              //echo $query; 
              $intCountFuture=$con->GetQueryNum($query);  
              if ($intCountFuture>0){
                  if ($counter==0){
                  echo '<div class="header recent_games_long"><span>Recent games</span><span class="toright"><a href="/games.html?id_season='.$intSeason.'&amp;id_show=2&amp;id_club='.get_url_text($StrClubName,$id).'" title="Show all games from season '.($intSeason-1).'-'.$intSeason.'">season '.($intSeason-1).'-'.$intSeason.'</a></span></div>';
                  echo '<table class="basic games">';
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
         
         
         
         <?php
          $query="SELECT DISTINCT players.id as id_player,id_position,players.name,players.surname,nationality,birth_date,weight,height,id_shoot from stats INNER JOIN players ON stats.id_player=players.id JOIN players_positions_list ON players.id_position=players_positions_list.id WHERE id_league=".$id_league." AND id_club=".$id." AND id_season=".$intSeason."  AND id_stats_type=1 ORDER BY players_positions_list.order ASC,players.surname ASC";
          //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '<div class="header current_roster"><span>Current roster</span><span class="toright">season '.($intSeason-1).'-'.$intSeason.'</span></div>';
                  if ($is_national_team==2 AND ($id_league==756 OR $id_league==758)){
                  $NameLeagueNational=$games->GetActualLeagueName($id_league,$intSeason);
         echo '
         <div class="box" style="margin:0px">
          <ul>
            <li><a href="/national-history/'.get_url_text($StrClubName,$id).'?season='.$intSeason.'&amp;league='.$id_league.'" title="Show this roster with all historical appearances on World championships and Olympic tournaments">Show this roster with <b>all historical appearances</b> on World championships and Olympic tournaments</a></li>
            <li><a href="/national-season/'.get_url_text($StrClubName,$id).'?season='.$intSeason.'&amp;league='.$id_league.'" title="Show this roster with current season club stats">Show this roster with <b>current season club stats</b></a></li>
            <li><a href="/stats/club/'.$intSeason.'/'.get_url_text($StrClubName,$id).'?league='.$id_league.'" title="Show stats for '.$NameLeagueNational.' '.$intSeason.'"><b>Show stats</b> for '.$NameLeagueNational.' '.$intSeason.'</a></li>
          </ul>
         </div>
         ';
         }
                  echo '<table class="basic roster">';
                  echo '
                  <thead>
                  <tr>
                    <th class="position" valign="top">Pos</th>
                    <th class="player" valign="top">Player name</th>
                    <th class="nationality" valign="top">State</th>
                    <th class="birth_date" valign="top">Born</th>
                    <th class="height" valign="top">Height</th>
                    <th class="weight" valign="top">Weight</th>
                    <th class="id_shoot" valign="top">Shoots</th>
                    <th class="link" valign="top">&nbsp;</th>
                    
                  </tr>
                  </thead>
                  <tbody>
                  ';
                  $result = $con->SelectQuery($query);
                  $counter=0;
                  while($write = $result->fetch_array()){
                    $counter++;
                    //if ($counter%2) $style=""; else $style="";
  		              echo '<tr>';        
                    
                 /*  if( $intSeason==ActSeasonWeb){          
                   $result_l=$con->GetSQLSingleResult("SELECT date_time as item FROM transfers WHERE id_player=".$write['id_player']." AND id_source_note=1 AND ((id_club_from=".$id." AND id_league_from=".$id_league.") OR (id_club_from=".$id." AND id_league_from=0)) ORDER BY date_time DESC LIMIT 1");   
                  $result_l2=$con->GetSQLSingleResult("SELECT date_time as item FROM transfers WHERE id_player=".$write['id_player']." AND id_source_note=1 AND ((id_club_to=".$id." AND id_league_to=".$id_league.") OR (id_club_to=".$id." AND id_league_to=0)) ORDER BY date_time DESC  LIMIT 1");  
                     
                  if(strtotime($result_l) > strtotime($result_l2)){$left="&nbsp;*";}else{$left = "";}
                  } else{$left="";}   */
                      $StrPosition=$con->GetSQLSingleResult("SELECT shortcut as item FROM players_positions_list WHERE id=".$write['id_position']);
                      if (empty($StrPosition)) $StrPosition="-";
                      echo '<td class="position" valign="top">'.$StrPosition.'</td>';
                      $player_name=$write['name'].' '.$write['surname'];
  		                echo '<td class="player" valign="top"><a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player profile: '.$player_name.' '.$player_surname.'">'.$write['name'].' <b>'.$write['surname'].$left.'</b></a>';
                      echo '</td>';
                       echo '<td class="nationality" valign="top">';
                       echo get_flag(strtolower($write['nationality']),15,11);
                       echo '</td>';
                       $birth_date=explode("-",$write['birth_date']);
                       if (!empty($birth_date[2])) $date_bith_day=$birth_date[2]; else $date_bith_day='??';
                       if (!empty($birth_date[1])) $date_bith_month=$birth_date[1]; else $date_bith_month='??';
                       if (!empty($birth_date[0])) $date_bith_year=$birth_date[0]; else $date_bith_year='????';
                       $birth_date=$date_bith_day.'.'.$date_bith_month.'.'.$date_bith_year;         
                       echo '<td class="birth_date" valign="top">'.$birth_date.'</td>';
                       if (empty($write['height'])) $StrHeight="-"; else  $StrHeight=$write['height'];
                       echo '<td class="height" valign="top">'.$StrHeight.' cm</td>';
                       if (empty($write['weight'])) $StrWeight="-"; else  $StrWeight=$write['weight'];
                       echo '<td class="weight" valign="top">'.$StrWeight.' kg</td>';
                       $StrShoot=$con->GetSQLSingleResult("SELECT name  as item FROM players_shoots_list WHERE id=".$write['id_shoot']);
                       if (empty($StrShoot)) $StrShoot="-";
                       echo '<td class="id_shoot" valign="top">'.$StrShoot.'</td>';
                       echo '<td class="link" valign="top"><span class="link"><a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player profile: '.$player_name.'">Show profile&raquo;</a></span></td>';
                      
                    echo '</tr>';
                  }
                  echo '</tbody></table>';
              }
              //coaches
             $query="SELECT DISTINCT players.id as id_player,players.name,players.surname,id_position,nationality,birth_date,penalty,minutes,coach_level_reached,coach_left from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id_league." AND id_club=".$id." AND id_season=".$intSeason."  AND id_stats_type=2 ORDER BY players.id_position ASC,players.surname ASC";
          //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '<table class="basic roster">';
                  echo '
                  <thead>
                  <tr>
                    <th class="player" valign="top">Coach name</th>
                    <th class="nationality" valign="top">State</th>
                    <th class="birth_date" valign="top">Born</th>
                    <th class="birth_date" valign="top">Coach position</th>
                    <th class="link" valign="top">&nbsp;</th>      
                  </tr>
                  </thead>
                  <tbody>
                  ';
                  $result = $con->SelectQuery($query);
                  $counter=0;
                  while($write = $result->fetch_array()){
                    $counter++;
                    //if ($counter%2) $style=""; else $style="";
  		              echo '<tr>';
                      $query_s = "SELECT is_national_team,id_player,penalty,minutes,id_season,wins,losts,draws,coach_level_reached,coach_hired,coach_left,reason_of_leaving,id_club,id_league,id_season_type from stats INNER JOIN clubs ON stats.id_club=clubs.id WHERE id_player=".$write['id_player']." AND id_stats_type=2 AND id_league=".$id_league." AND id_club=".$id." AND id_season=".$intSeason." ORDER BY id_season DESC, stats.id DESC, id_season_type DESC";
                      //echo $query_s;
                      $result_s = $con->SelectQuery($query_s);
                      $write_s = $result_s->fetch_array();
                      if ($write_s['coach_left']<>"0000-00-00" and !empty($write_s['coach_left'])){$left = ' *';}else{$left = '';}
  		                $player_name=$write['name'].' '.$write['surname'];
  		                echo '<td class="player" valign="top"><a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player profile: '.$player_name.' '.$player_surname.'">'.$write['name'].' <b>'.$write['surname'].$left.'</b></a>';
                      echo '</td>';
                       echo '<td class="nationality" valign="top">';
                       echo get_flag(strtolower($write['nationality']),15,11);
                       echo '</td>';
                       $birth_date=explode("-",$write['birth_date']);
                       if (!empty($birth_date[2])) $date_bith_day=$birth_date[2]; else $date_bith_day='??';
                       if (!empty($birth_date[1])) $date_bith_month=$birth_date[1]; else $date_bith_month='??';
                       if (!empty($birth_date[0])) $date_bith_year=$birth_date[0]; else $date_bith_year='????';
                       $birth_date=$date_bith_day.'.'.$date_bith_month.'.'.$date_bith_year;         
                       echo '<td class="birth_date" valign="top">'.$birth_date.'</td>';
                       $coach_position=$con->GetSQLSingleResult("SELECT name as item FROM players_coach_positions_list WHERE id=".$write['penalty']);
                       echo '<td class="birth_date" valign="top">'.$coach_position.'</td>';
                       
                       echo '<td class="link right" valign="top"><span class="link"><a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player profile: '.$player_name.'">Show profile&raquo;</a></span></td>';
                      
                    echo '</tr>';
                  }
                  echo '</tbody></table>';
              }
         ?>
         <p class="asterisk">* no longer in the club</p>
         <!-- main text end -->
      </div>
      
      <div id="col_right" class="shorter">
        
        
        <div id="rotate">
            
               <?
                $query="SELECT * FROM clubs_arenas_items WHERE id_club=".$id." ORDER BY id_arena ASC";
                //echo $query;
                $counter=0;
                $result = $con->SelectQuery($query);
                $IntCount=$con->GetQueryNum($query);
                if ($IntCount>0){
                  $strTab1.='<div id="fragment-1" class="box tabs">';
                  while($write = $result->fetch_array())
	               { 
	                 $counter++;
                   
                   $query_arenas="SELECT * FROM arenas WHERE id=".$write['id_arena'];
	                 $result_arenas = $con->SelectQuery($query_arenas);
	                 $write_arenas = $result_arenas->fetch_array();
	                 $StrArenaName=$games->GetActualArenaName($write_arenas['id'],$intSeason);
	                 $StrArenaAddress=$write_arenas['address'];
	                 $StrArenaTelephone=$write_arenas['telephone'];
	                 $StrArenaEmail=$write_arenas['email'];
	                 $StrArenaLink_1=$write_arenas['link_1'];
	                 $StrArenaCapacity_overall=$write_arenas['capacity_overall'];
	                 $StrArenaYear_built=$write_arenas['year_built'];
	                 
	                 $strTab1.= '<h3><a href="/arena/'.get_url_text($StrArenaName,$write['id_arena']).'" title="Show arena profile: '.$StrArenaName.'">'.$StrArenaName.'</a></h3>';
	                  
	                  $strTab1.= '<div class="ul_list_foto">';
	                  if (!empty($write_arenas['id_photo_folder'])){
	                  $id_image=$con->GetSQLSingleResult("SELECT id as item FROM photo WHERE id_photo_folder=".$write_arenas['id_photo_folder']." ORDER by id DESC");
                    if (!empty($id_image)){
	                     $strPhotoPath=get_photo_name($id_image);
	                     if (file_exists(PhotoFolder."/".$strPhotoPath)) {
	                       
                          $strTab1.= '<img class="border" src="/image/155-150-1-'.str_replace("-","_",$strPhotoPath).'" alt="'.$StrArenaName.' logo" />';
                         
                       }else {$strTab1.= '<img src="/img/default.jpg" width="155" alt="Logo not found" />';}
	                   }else {$strTab1.= '<img src="/img/default.jpg" width="155" alt="Logo not found" />';}
	                  }else {$strTab1.= '<img src="/img/default.jpg" width="155" alt="Logo not found" />';}
	                  $strTab1.= '</div>';
	                  
	                   
                   
                   $strTab1.= '<div class="ul_list">';
                      $strTab1.= '<ul class="no_padding_top">';
                        if (!empty($StrArenaAddress))$strTab1.= '<li><b>Adress:</b> '.nl2br($StrArenaAddress).'</li>';
                        if (!empty($StrArenaTelephone))$strTab1.= '<li><b>Phone:</b> '.$StrArenaTelephone.'</li>';
                        if (!empty($StrArenaEmail))$strTab1.= '<li><b>Email:</b> <a href="mailto:'.$StrArenaEmail.'">'.$StrArenaEmail.'</a></li>';
                        if (!empty($StrArenaLink_1))$strTab1.= '<li><b>Web:</b> <a onclick="return !window.open(this.href);" href="'.$StrArenaLink_1.'">'.$StrArenaLink_1.'</a></li>';
                        if (!empty($StrArenaCapacity_overall))$strTab1.= '<li><b>Capacity:</b> '.$StrArenaCapacity_overall.'</li>';
                        if (!empty($StrArenaYear_built))$strTab1.= '<li><b>Opened in:</b> '.$StrArenaYear_built.'</li>';
                      $strTab1.= '</ul>';
                   $strTab1.= '</div>';
	                 
	                 if ($counter<>$IntCount and $counter<$IntCount) {$strTab1.= '<div class="line">&nbsp;</div>';} else {$strTab1.= '<div class="clear">&nbsp;</div>';}
	                 	                 
         
          	     }
                 $strTab1.='</div>';
          	    }
                
               if (!empty($brief_history)){
                $strTab2.='<div id="fragment-2" class="box tabs">';
                $strTab2.= $brief_history; 
                $bollHistory=true;
               }
               $query="SELECT id_player,name,surname FROM clubs_players_items INNER JOIN players ON clubs_players_items.id_player=players.id  WHERE clubs_players_items.id_club=".$id." ORDER BY players.surname ASC, players.name ASC";
                //echo $query;
                $result = $con->SelectQuery($query);
                if ($con->GetQueryNum($query)>0){
                if ($bollHistory<>true) $strTab2.='<div id="fragment-2" class="box tabs">';
                $strTab2.= '<b>Notable players:</b><br />';
                $strTab2.= '<ul>';
                
                 while($write = $result->fetch_array())
	               {
                      $player_name=$write['name'].' '.$write['surname'];    
	                    $strTab2.= '<li><a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player profile: '.$player_name.'">'.$player_name.'</a></li>';
                 }
                 $bollHistory=true;
                 $strTab2.= '</ul>';
          	    }
                
                if ($bollHistory) $strTab2.= '</div>'; 
               
            
            
                $query="SELECT int_year,name FROM clubs_names WHERE id_club=".$id." ORDER BY int_year ASC";
                //echo $query;
                $result = $con->SelectQuery($query);
                $IntCount=$con->GetQueryNum($query);
                $counter=0;
                if ($IntCount>0){
                $strTab3.='<div id="fragment-3" class="box tabs">';
                $strTab3.= '<ul>';
                while($write = $result->fetch_array())
                 {
                  $name_year[$counter]=($write['int_year']-1);
                  $name_name[$counter]=$write['name'];
	                $counter++; 
          	     }
          	     for ($i=0;$i<=($counter-1);$i++){
          	       if ($i==($counter-1)) {$name_year_write="present";} else {$name_year_write=$name_year[$i+1];}
          	       $strTab3.= '<li>from '.$name_year[$i].' to '.$name_year_write.': <b>'.$name_name[$i].'</b></li>';
          	     }
          	     $strTab3.= '</ul>';
                 $strTab3.= '</div>';
          	    }
          	   
               
               if (!empty($achievments)){
                  $strTab4.='<div id="fragment-4" class="box tabs">';
                  $strTab4.= $achievments; 
                  $bollAchievments=true;
                }
               $query="SELECT id_league,int_year FROM leagues_past_winners WHERE id_club=".$id." ORDER BY int_year DESC";
                //echo $query;
                $result = $con->SelectQuery($query);
                if ($con->GetQueryNum($query)>0){
                if ($bollAchievments<>true) $strTab4.='<div id="fragment-4" class="box tabs">';
                $strTab4.= '<b>League winners:</b><br />';
                $strTab4.= '<ul>';
                
                  while($write = $result->fetch_array())
	               {    
	                    $StrChampionName=$games->GetActualLeagueName($write['id_league'],($write['int_year']));
	                    $strTab4.= '<li>'.($write['int_year']-1).'-'.($write['int_year']).': <a href="/league/'.get_url_text($StrChampionName,$write['id_league']).'" title="Show league profile: '.$StrChampionName.'">'.$StrChampionName.'</a></li>';
                 }
                  $bollAchievments=true;
                 $strTab4.= '</ul>';
          	    }
                if ($bollAchievments) $strTab4.= '</div>'; 
          	    
          	    
               $query="SELECT * FROM clubs_images WHERE id_club=".$id." ORDER BY int_year ASC";
                //echo $query;
                $result = $con->SelectQuery($query);
                $IntCount=$con->GetQueryNum($query);
                $counter=0;
                if ($IntCount>0){
                $strTab5.='<div id="fragment-5" class="box tabs">';
                while($write = $result->fetch_array())
                 {
                  $name_year[$counter]=($write['int_year']-1);
                  $name_name[$counter]=$write['id_image'];
                  $counter++; 
          	     }
          	     for ($i=0;$i<=($counter-1);$i++){
          	       if ($i==($counter-1)) {$name_year_write="present";} else {$name_year_write=$name_year[($i)];}
          	       if (!empty($name_name[$i])){
	                     $strPhotoPath=get_photo_name($name_name[$i]);
	                     if (file_exists(PhotoFolder."/".$strPhotoPath)) {
                          $strLogoImage='<img class="border" src="/image/155-150-1-'.str_replace("-","_",$strPhotoPath).'" alt="'.$StrClubName.' logo" />';
                       }else{
                          $strLogoImage='<img src="/img/default.jpg" width="155" alt="Logo not found" />';
                       }
	                 }else{$strLogoImage='<img src="/img/default.jpg" width="155" alt="Logo not found" />';}
          	       
          	       $strTab5.= '<div class="logo"><strong>from '.$name_year[$i].' to '.$name_year_write.':</strong>'.$strLogoImage.'</b></div>';
          	       
          	     }
                 $strTab5.='<div class="clear">&nbsp;</div></div>';
          	    }
               
    
              $query_sub="SELECT *,(SELECT name FROM clubs_links_list WHERE clubs_links.id_type=clubs_links_list.id) as type FROM clubs_links WHERE id_club=".$id." ORDER by id_type,name";
              //echo $query; 
              $intPocetLinks=$con->GetQueryNum($query_sub);
              if ($intPocetLinks){
                  $strTab6.='<div id="fragment-6" class="box tabs">';
                  $strTab6.= '<ul>';
                  $result_sub = $con->SelectQuery($query_sub);
                  while($write_sub = $result_sub->fetch_array()){
                      if (empty($write_sub['link_name'])) $strLinkName=$write_sub['name']; else $strLinkName=$write_sub['link_name'];
                      $strTab6.= '<li><a onclick="return !window.open(this.href);" href="'.$write_sub['name'].'">'.$strLinkName.'</a> ('.$write_sub['type'].')</li>';
                  }
                  $strTab6.= '</ul>';
                $strTab6.= '</div>';
              }
             
             if (!empty($strTab1) OR !empty($strTab2) OR !empty($strTab3) OR !empty($strTab4) OR !empty($strTab5) OR !empty($strTab6)){
             echo '<ul>';
              if (!empty($strTab1)) {echo '<li><a href="#fragment-1"><span>Arena</span></a></li>';}
              if (!empty($strTab2)) {echo '<li><a href="#fragment-2"><span>History</span></a></li>';}
              if (!empty($strTab3)) {echo '<li><a href="#fragment-3"><span>Name changes</span></a></li>';}
              if (!empty($strTab4)) {echo '<li><a href="#fragment-4"><span>Achievements</span></a></li>';}
              if (!empty($strTab5)) {echo '<li><a href="#fragment-5"><span>Logos</span></a></li>';}
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
                      AND articles_items.id_item_type=3 AND articles_items.id_item=".$id." 
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
                        AND news_items.id_item_type=3 AND news_items.id_item=".$id." 
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

        <div class="header_cufon"><span class="header_text">Team statistics</span></div>
        <div class="box leagues_statistic">
          
          <?php
              $query="SELECT DISTINCT id_season FROM stats WHERE id_league=".$id_league." AND id_club=".$id." AND id_stats_type=1 ORDER BY id_season DESC";
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
              
             ?>
            <div class="toleft"><strong>Historic rosters:</strong> </div>
            <div class="toright">
              <form action="">
              <div>
              <select name="season" onchange="redirect_by_select_box(this,'season','<?php echo '/club/'.get_url_text($StrClubName,$id);?>',<?php echo $id_league;?>)">
                 <?php echo $strSeasonList; ?>
              </select>
              </div>
              </form>
            </div>
            <div class="space">&nbsp;</div>
            
            
            <?php
            $strSeasonList="";
              $query="SELECT DISTINCT id_season FROM stats WHERE id_league=".$id_league." AND id_club=".$id." AND id_stats_type=1 ORDER BY id_season DESC";
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
              
             ?>
            <div class="toleft"><strong>Historic statistics:</strong> </div>
            <div class="toright">
              <form action="">
              <div>
              <select name="season" onchange="redirect_by_select_stats(this,'<?php echo '/stats/club/';?>','<?php echo '/'.get_url_text($StrClubName,$id).'?league='.$id_league;?>')">
                 <?php echo $strSeasonList; ?>
              </select>
              </div>
              </form>
            </div>
            <div class="space">&nbsp;</div>
            
           
            <div class="toleft"><strong>Past results:</strong> </div>
            <div class="toright">
              <form action="">
              <div>
              <?php
	               echo '<select name="season" onchange="redirect_by_url(this,\'id_season\',\'/games.html\',\'&amp;id_show=2&amp;id_league='.get_url_text($NameLeague,$id_league).'&amp;id_club='.get_url_text($StrClubName,$id).'\')">';
	               $strSeasonList="";
                 $query="SELECT DISTINCT id_season FROM games WHERE (id_club_home=".$id." or  id_club_visiting=".$id.")  AND id_league=".$id_league." ORDER BY id_season DESC";
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
	               echo '<select name="season" onchange="redirect_by_url(this,\'season\',\'/league/'.get_url_text($NameLeague,$id_league).'\',\'#standings\')">';
	               $strSeasonList="";
                 $query="SELECT DISTINCT id_season FROM standings WHERE id_league=".$id_league." ORDER BY id_season DESC";
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
            <div class="clear">&nbsp;</div>
            
        </div>
        
        <?
          $strFastFactsBox="";
          
          //nejmladsi hrac
          $query="SELECT DISTINCT players.id as id_player,birth_date,name,surname from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id_league." AND id_club=".$id." AND id_season=".$intSeason." AND birth_date NOT LIKE '%?%'  AND birth_date <> '' AND id_stats_type=1 AND id_season_type=1 ORDER BY birth_date DESC LIMIT 1";
          if ($con->GetQueryNum($query)>0){
            $result = $con->SelectQuery($query);
            $write = $result->fetch_array();
            $player_name=$write['name'].' '.$write['surname'];
            $player_age=floor((date("Ymd", strtotime(($intSeason)."-".date("m")."-".date("d"))) - date("Ymd", strtotime($write['birth_date']))) / 10000);
            $strFastFactsBox.='<li><strong>Youngest player:</strong> <a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player profile: '.$player_name.'">'.$player_name.'</a> ('.$player_age.' years)</li>';
          }
          //nejstarsi hrac
          $query="SELECT DISTINCT players.id as id_player,birth_date,name,surname from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id_league." AND id_club=".$id." AND id_season=".$intSeason." AND birth_date NOT LIKE '%?%'  AND birth_date <> ''  AND id_stats_type=1 AND id_season_type=1 ORDER BY birth_date ASC LIMIT 1";
          if ($con->GetQueryNum($query)>0){
            $result = $con->SelectQuery($query);
            $write = $result->fetch_array();
            $player_name=$write['name'].' '.$write['surname'];
            $player_age=floor((date("Ymd", strtotime(($intSeason)."-".date("m")."-".date("d"))) - date("Ymd", strtotime($write['birth_date']))) / 10000);
            $strFastFactsBox.='<li><strong>Oldest player:</strong> <a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player profile: '.$player_name.'">'.$player_name.'</a> ('.$player_age.' years)</li>';
          }
          //nejmensi hrac
          $query="SELECT DISTINCT players.id as id_player,height,name,surname from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id_league." AND id_club=".$id." AND id_season=".$intSeason." AND height>0  AND id_stats_type=1 AND id_season_type=1 ORDER BY height ASC LIMIT 1";
          if ($con->GetQueryNum($query)>0){
            $result = $con->SelectQuery($query);
            $write = $result->fetch_array();
            $player_name=$write['name'].' '.$write['surname'];
            $strFastFactsBox.='<li><strong>Shortest player:</strong> <a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player profile: '.$player_name.'">'.$player_name.'</a> ('.$write['height'].' cm)</li>';
          }
          //nejvyssi hrac                                                                                                                                                                                                               
          $query="SELECT DISTINCT players.id as id_player,height,name,surname from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id_league." AND id_club=".$id." AND id_season=".$intSeason." AND height>0 AND id_stats_type=1 AND id_season_type=1 ORDER BY height DESC LIMIT 1";
          if ($con->GetQueryNum($query)>0){
            $result = $con->SelectQuery($query);
            $write = $result->fetch_array();
            $player_name=$write['name'].' '.$write['surname'];
            $strFastFactsBox.='<li><strong>Tallest player:</strong> <a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player profile: '.$player_name.'">'.$player_name.'</a> ('.$write['height'].' cm)</li>';
          }
          //narodnosti
          $query="SELECT nationality from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id_league." AND id_club=".$id." AND id_season=".$intSeason." AND nationality<>'' AND id_stats_type=1 AND id_season_type=1 GROUP BY  nationality ORDER BY nationality ASC";
          $intCount=$con->GetQueryNum($query);
          $counter=0;
          if ($intCount>0){
            $strFastFactsBox.='<li><strong>Nationalities:</strong> ';
            $result = $con->SelectQuery($query);
            while($write = $result->fetch_array()){
              $nationality_name=$con->GetSQLSingleResult("SELECT name as item FROM countries WHERE shortcut='".$write['nationality']."'");
              $nationality_id=$con->GetSQLSingleResult("SELECT id as item FROM countries WHERE shortcut='".$write['nationality']."'");
              $sql="SELECT stats.id from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id_league." AND id_club=".$id." AND id_season=".$intSeason." AND nationality='".$write['nationality']."' AND id_stats_type=1 AND id_season_type=1 GROUP BY  stats.id_player";
              $nationality_number=$con->GetQueryNum($sql);
              $strFastFactsBox.= get_flag(strtolower($write['nationality']),15,11);
              $strFastFactsBox.='<a href="/country/'.get_url_text($nationality_name,$nationality_id).'" title="Show country profile: '.$nationality_name.'">'.$nationality_name.' ('.$nationality_number.')</a>';
              $counter++;
              if ($counter<$intCount){$strFastFactsBox.=', ';}
            }
            $strFastFactsBox.='</li>';
          }
          
          $intIDstage=$con->GetSQLSingleResult("SELECT id as item FROM games_stages WHERE id_league=".$intLeague." AND id_detault=1");
          
          //nejvyssi vyhra
          $query_home="SELECT * FROM games WHERE (id_club_home=".$id.") AND id_league=".$intLeague." AND id_season=".$intSeason." AND home_score<>visiting_score and home_score>visiting_score AND id_stage=".$intIDstage." ORDER by (home_score-visiting_score) DESC";
          if ($con->GetQueryNum($query_home)>0){
            $result = $con->SelectQuery($query_home);
            $write = $result->fetch_array();
            $intScoreHome=$write['home_score']-$write['visiting_score'];
          }
          
          $query_visiting="SELECT * FROM games WHERE (id_club_visiting=".$id.") AND id_league=".$intLeague." AND id_season=".$intSeason." AND home_score<>visiting_score and home_score<visiting_score AND id_stage=".$intIDstage." ORDER by (visiting_score-home_score) DESC";
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
          $query="SELECT sum(spectators)/count(id) as average from games WHERE spectators>0 AND id_league=".$intLeague." AND id_club_home=".$id." AND id_season=".$intSeason." AND id_stage=".$intIDstage."";
          //echo $query;
          if ($con->GetQueryNum($query)>0){
            $result = $con->SelectQuery($query);
            $write = $result->fetch_array();
            if (!empty($write['average'])){
              $average=ceil($write['average']);
              $strFastFactsBox.='<li><strong>Season average home attendance:</strong> '.$average.'</li>';
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
        
          <?php
                $query="SELECT DISTINCT id_league,name from stats INNER JOIN leagues ON leagues.id=stats.id_league WHERE id_club=".$id." and id_season=".$intSeason."  ORDER by id_order ASC, youth_league ASC,youth_league_id ASC,name ASC";
                $intCount=$con->GetQueryNum($query);
                $counter=0;
                if ($intCount>0){
                  echo '
                  <div class="header_cufon"><span class="header_text">Leagues</span></div>
                  <div class="box"> 
                  <p>'.$StrClubName.' parcitipates in season '.($intSeason-1).'-'.$intSeason.': ';
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    $NameLeague=$games->GetActualLeagueName($write['id_league'],$intSeason);
                    echo '<a href="/league/'.get_url_text($NameLeague,$write['id_league']).'?id_season='.$intSeason.'" title="'.$NameLeague.'">'.$NameLeague.'</a>';
                    $counter++;
                    $id_league=$write['id_league'];
                    if ($counter<$intCount){ echo ', ';}
                  }
                  echo '</p>
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