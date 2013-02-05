<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");

$id=$_GET['id'];
$id=$input->valid_text($id,true,true);
$id=get_ID_from_url($id);
if (empty($id)){header("Location: /text/404-error.html");}

$query="SELECT * from countries WHERE id=".$id;
if ($con->GetQueryNum($query)>0){
    $result = $con->SelectQuery($query);
    $write = $result->fetch_array();
    $Name=$games->GetActualCountryName($write['id'],ActSeasonWeb);
    $name=$write['name'];
    $shortcut=$write['shortcut'];
    $hockey_asociation=$write['hockey_asociation'];
    $address=$write['address'];
    $telephone=$write['telephone'];
    $fax=$write['fax'];
    $email=$write['email'];
    $year_founded=$write['year_founded'];
    $year_incorporated=$write['year_incorporated'];
    $brief_history=$write['brief_history'];
    $best_achievments=$write['best_achievments'];
    $registered_players=$write['registered_players'];
    $placement_at_IHWC=$write['placement_at_IHWC'];
    $world_ranking=$write['world_ranking'];
    $link_1=$write['link_1'];
    $link_2=$write['link_2'];
    $link_3=$write['link_3'];
    $image_asociation_logo=$write['image_asociation_logo'];
    $image_flag=$write['image_flag'];
    
     
    
}else{
    header("Location: /text/404-error.html");
}

$strHeaderKeywords='country details '.$Name.'';
$strHeaderDescription=$Name.' details';
require_once("inc/head.inc");
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
  
  <div id="logo"><?php require_once("inc/logo.inc"); ?></div>
  <div id="menu"><?php require_once("inc/menu.inc"); ?></div>
  <div id="main">
      
      <div id="text" class="shorter">
  
         <!-- main text -->
         <h1 class="hidden"><?php echo $Name; ?></h1>
         
         <div id="navigation">
            <div class="toleft">
                <div class="corner_nav_left">&nbsp;</div>
                <div class="corner_nav_box"><a href="/countries.html" title="Countries">Countries</a></div>
                <div class="corner_nav_middle">&nbsp;</div>
                <div class="corner_nav_box"><?php echo $Name; ?></div>
                <div class="corner_nav_right">&nbsp;</div>
                
            </div>
            
            <div class="toright">
              
            </div>
            
            <div class="clear">&nbsp;</div>
            
         </div>
          <div class="space">&nbsp;</div>
          
          <div id="item_logo">
          
          <?php 
           
           if (!empty($image_flag)){
           
	           $strPhotoPath=get_photo_name($image_flag);
	           if (file_exists(PhotoFolder."/".$strPhotoPath)) {
              echo '<img src="/image/155-150-1-'.str_replace("-","_",$strPhotoPath).'" alt="'.$StrName.' logo" />';
              $boolFlag=true;
             }
           }
           
           if (!empty($image_asociation_logo)){
           
	           $strPhotoPath=get_photo_name($image_asociation_logo);
	           if (file_exists(PhotoFolder."/".$strPhotoPath)) {
              echo '<img src="/image/155-150-1-'.str_replace("-","_",$strPhotoPath).'" alt="'.$StrName.' logo" />';
              $boolAssoc=true;
             }
          }
	         
	         if ($boolFlag==false and $boolAssoc==false) echo '<img src="/img/default.jpg" width="190" alt="Logo not found" />';
           ?>
         </div>
         
         <div id="item_preview">
            <h1><?php echo $Name; ?></h1>
            <ul>
            <?php
              if (!empty($hockey_asociation)) echo '<li><strong>Hockey asociation:</strong> '.$hockey_asociation.'</li>';
              if (!empty($address)) echo '<li><strong>Address:</strong> '.nl2br($address).'</li>';
              if (!empty($telephone)) echo '<li><strong>Phone:</strong> '.$telephone.'</li>';
              if (!empty($fax)) echo '<li><strong>Fax:</strong> '.$fax.'</li>';
              if (!empty($email)) echo '<li><strong>Email:</strong> <a href="mailto:'.$email.'">'.$email.'</a></li>';
              if (!empty($year_founded)) echo '<li><strong>Year founded:</strong> '.$year_founded.'</li>';
              if (!empty($year_incorporated)) echo '<li><strong>Year incorporated:</strong> '.$year_incorporated.'</li>';
              if (!empty($placement_at_IHWC)) echo '<li><strong>Placement at IHWC:</strong> '.$placement_at_IHWC.'</li>';
              if (!empty($world_ranking)) echo '<li><strong>World ranking:</strong> '.$world_ranking.'</li>';
              
            ?>
            </ul>
            
         </div>
         <div class="clear">&nbsp;</div>
         
                 
         
         <?php echo socials(0); ?>
         
         <p class="center">Did you find any incorrect or incomplete information? <a href="/feedback.html?link=<?php echo curPageURL(); ?>">Please, let us know</a>.</p>
         
         <?php
              $query="SELECT 
                        transfers.date_time, transfers.id_player,transfers.id_position,transfers.id_club_from,transfers.id_club_to,transfers.id_country_from,transfers.id_country_to,transfers.id_retire_status 
                        FROM transfers INNER JOIN clubs as club1 ON club1.id=transfers.id_club_from  INNER JOIN clubs as club2 ON club2.id=transfers.id_club_to
                        WHERE
                          transfers.id_source_note=1 AND (transfers.id_country_from=".$id." OR transfers.id_country_to=".$id." OR club1.id_country='".$shortcut."'  OR club2.id_country='".$shortcut."')
                        ORDER BY date_time DESC  LIMIT 4";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '<div class="header_cufon blue_490"><span class="header_text blue">Recent transfers</span><span class="toright"><a href="/transfers.html?id_country='.$id.'" title="Show all transfers from '.$Name.'">Show all transfers from '.$Name.'&raquo;</a></span></div>';
                  echo '<table class="basic transfers">';
                  $result = $con->SelectQuery($query);
                  $counter=0;
                  while($write = $result->fetch_array()){
                    $counter++;
                    if ($counter%2) $style="dark"; else $style="";
  		              echo '<tr class="'.$style.'">';
                      
                      echo '<td class="date" valign="top">'.date("d M Y",strtotime($write['date_time'])).' | </td>';
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
              $query="SELECT leagues.name,leagues.id FROM leagues INNER JOIN  leagues_countries_items ON leagues_countries_items.id_league=leagues.id WHERE leagues_countries_items.id_country=".$id." ORDER BY 	id_order 	ASC, name ASC";
              //echo $query;
              $intCount=$con->GetQueryNum($query);
              $intCountAll=15; 
              if ($intCount>0){
                  echo '<div class="header_cufon blue_490"><span class="header_text blue">Leagues</span><span class="toright"><a href="/leagues.html?id_country='.$id.'" title="Show all '.$intCount.' leagues from '.$Name.'">Show all '.$intCount.' leagues from '.$Name.'&raquo;</a></span></div>';
                  $result = $con->SelectQuery($query);
                  $counter=0;
                  echo '<div class="box">';
                  while($write = $result->fetch_array()){
                    $strLeagueName=$games->GetActualLeagueName($write['id'],ActSeasonWeb);
                    echo '<a href="/league/'.get_url_text($write['name'],$write['id']).'" title="Show league profile: '.$strLeagueName.'">'.$strLeagueName.'</a>';
                    $counter++;
                    if ($counter<$intCountAll and $counter<$intCount) echo ' | '; else {break;}
                  }
                  if ($counter<$intCount){ echo '<br /><span class="link bold"><a href="/leagues.html?id_country='.$id.'" title="Show all '.$intCount.' leagues from '.$Name.'">and next '.($intCount-$intCountAll).' leagues...</a></span>';}
                  echo '</div>';
                  echo '<div class="space">&nbsp;</div>';
                  
              }
              
              ?>
              
              <?php
              $query="SELECT name,id FROM clubs WHERE id_country='".$shortcut."' AND is_national_team<>2 ORDER BY name ASC";
              $intCount=$con->GetQueryNum($query);
              $intCountAll=15; 
              if ($intCount>0){
                  echo '<div class="header_cufon blue_490"><span class="header_text blue">Clubs</span><span class="toright"><a href="/clubs.html?id_country='.$id.'" title="Show all '.$intCount.' clubs from '.$Name.'">Show all '.$intCount.' clubs from '.$Name.'&raquo;</a></span></div>';
                  $result = $con->SelectQuery($query);
                  $counter=0;
                  echo '<div class="box">';
                  while($write = $result->fetch_array()){
                    $strClubName=$games->GetActualClubName($write['id'],ActSeasonWeb);
                    echo '<a href="/club/'.get_url_text($write['name'],$write['id']).'" title="Show club profile: '.$strClubName.'">'.$strClubName.'</a>';
                    $counter++;
                    if ($counter<$intCountAll and $counter<$intCount) echo ' | '; else {break;}
                    
                    
                  }
                  if ($counter<$intCount){ echo '<br /><span class="link bold"><a href="/clubs.html?id_country='.$id.'" title="Show all '.$intCount.' clubs from '.$Name.'">and next '.($intCount-$intCountAll).' clubs...</a>';}
                  echo '</div>';
                   echo '<div class="space">&nbsp;</div>';
                  
              }
              
              ?>
              
              <?php
              if ($boolNationals){
              $query="SELECT id FROM clubs WHERE id_country='".$shortcut."' AND is_national_team=2 ORDER BY name ASC";
              $result = $con->SelectQuery($query);
              $write = $result->fetch_array();
              $id_national=$write['id'];
              
              $query="SELECT DISTINCT id_league FROM stats WHERE id_club=".$id_national." AND id_season=".(ActSeasonWeb-1)." ORDER BY id_season DESC";
              
              $intCount=$con->GetQueryNum($query);
              $intCountAll=15; 
              if ($intCount>0){
                  echo '<div class="header_cufon blue_490"><span class="header_text blue">National teams</span><span class="toright"><a href="/clubs.html?id_country='.$id.'" title="Show all '.$intCount.' national teams from '.$Name.'">Show all '.$intCount.' national teams from '.$Name.'&raquo;</a></span></div>';
                  $result = $con->SelectQuery($query);
                  $counter=0;
                  echo '<div class="box">';
                  while($write = $result->fetch_array()){
                    $StrLeagueName=$games->GetActualLeagueName($write['id_league'],ActSeasonWeb);
                    echo '<a href="/national/'.get_url_text($write['name'],$StrLeagueName).'" title="Show national team profile: '.$StrLeagueName.'">'.$StrLeagueName.'</a>';
                    $counter++;
                    if ($counter<$intCountAll and $counter<$intCount) echo ' | '; else {break;}
                  }
                  if ($counter<$intCount){ echo '<br /><span class="link bold"><a href="/national.html?id_country='.$id.'" title="Show all '.$intCount.' national teams from '.$Name.'">and next '.($intCount-$intCountAll).' national teams...</a>';}
                  echo '</div>';
                   echo '<div class="space">&nbsp;</div>';
                  
              }
              }
              
              ?>
         
     
         
         <!-- main text end -->
      </div>
      
      <div id="col_right" class="shorter">
        
        <div id="rotate">
          
                <?
               if (!empty($brief_history)){
                  $strTab1='<div id="fragment-1" class="box tabs">'.$brief_history.'</div>';
               }
          	
               if (!empty($best_achievments)){
                $strTab2='<div id="fragment-5" class="box tabs">'.$best_achievments.'</div>';
               }
               
                $query="SELECT players.id as id_player,name,surname from countries_players_items INNER JOIN players ON countries_players_items.id_player=players.id WHERE countries_players_items.id_country=".$id." ORDER BY surname";
                $result = $con->SelectQuery($query);
                $IntCount=$con->GetQueryNum($query);
                $counter=0;
                if ($IntCount>0){
                $strTab3.='<div id="fragment-2" class="box tabs">';
                $strTab3.= '<ul>'; 
                while($write = $result->fetch_array())
                 {
                  
          	       $strTab3.= '<li><a href="/player/'.get_url_text($write['name'].' '.$write['surname'],$write['id_player']).'" title="Show player profile: '.$write['name'].' '.$write['surname'].'">'.$write['name'].' '.$write['surname'].'</a></li>';
          	     }
          	     $strTab3.= '</ul></div>';
          	    }
          	 
          	   
            
                $query="SELECT id from arenas WHERE id_country=".$id."";
                $IntCountAll=$con->GetQueryNum($query);
                if ($IntCountAll>0){
                $strTab4.='<div id="fragment-3" class="box tabs">';
                $query="SELECT * from arenas WHERE id_country=".$id." and important_arenas=1 ORDER BY name ASC";
                $result = $con->SelectQuery($query);
                $IntCount=$con->GetQueryNum($query);
                if ($IntCount>0){
                $strTab4.="<b>Most inportant arenas:</b>";
                while($write = $result->fetch_array())
                 {
                   
                   $StrArenaName=$games->GetActualArenaName($write['id'],ActSeasonWeb);
	                 $StrArenaAddress=$write['address'];
	                 $StrArenaTelephone=$write['telephone'];
	                 $StrArenaEmail=$write['email'];
	                 $StrArenaLink_1=$write['link_1'];
	                 $StrArenaCapacity_overall=$write['capacity_overall'];
	                 $StrArenaYear_built=$write['year_built'];
	                 
	                 $strTab4.= '<h3><a href="/arena/'.get_url_text($StrArenaName,$write['id']).'" title="Show arena profile: '.$StrArenaName.'">'.$StrArenaName.'</a></h3>';
	                  
	                  $strTab4.= '<div class="ul_list_foto">';
	                  if (!empty($write['id_photo_folder'])){
	                  $id_image=$con->GetSQLSingleResult("SELECT id as item FROM photo WHERE id_photo_folder=".$write['id_photo_folder']." ORDER by id DESC");
                    if (!empty($id_image)){
	                     $strPhotoPath=get_photo_name($id_image);
	                     if (file_exists(PhotoFolder."/".$strPhotoPath)) {
	                       
                          $strTab4.= '<img class="border" src="/image/155-150-1-'.str_replace("-","_",$strPhotoPath).'" alt="'.$StrArenaName.' logo" />';
                         
                       }else {$strTab4.= '<img src="/img/default.jpg" width="155" alt="Logo not found" />';}
	                   }else {$strTab4.= '<img src="/img/default.jpg" width="155" alt="Logo not found" />';}
	                  }else {$strTab4.= '<img src="/img/default.jpg" width="155" alt="Logo not found" />';}
	                  $strTab4.= '</div>';
	                  
	                   
                   
                   $strTab4.= '<div class="ul_list">';
                      $strTab4.= '<ul class="no_padding_top">';
                        if (!empty($StrArenaAddress))$strTab4.= '<li><b>Adress:</b> '.nl2br($StrArenaAddress).'</li>';
                        if (!empty($StrArenaTelephone))$strTab4.= '<li><b>Phone:</b> '.$StrArenaTelephone.'</li>';
                        if (!empty($StrArenaEmail))$strTab4.= '<li><b>Email:</b> <a href="mailto:'.$StrArenaEmail.'">'.$StrArenaEmail.'</a></li>';
                        if (!empty($StrArenaLink_1))$strTab4.= '<li><b>Web:</b> <a onclick="return !window.open(this.href);" href="'.$StrArenaLink_1.'">'.$StrArenaLink_1.'</a></li>';
                        if (!empty($StrArenaCapacity_overall))$strTab4.= '<li><b>Capacity:</b> '.$StrArenaCapacity_overall.'</li>';
                        if (!empty($StrArenaYear_built))$strTab4.= '<li><b>Opened in:</b> '.$StrArenaYear_built.'</li>';
                      $strTab4.= '</ul>';
                   $strTab4.= '</div>';
	                 
	                 if ($counter<>$IntCount and $counter<$IntCount) {$strTab4.= '<div class="line">&nbsp;</div>';} else {$strTab4.= '<div class="clear">&nbsp;</div>';}
                   
          	     }
          	     
          	    }else{
                  $strTab4.= '<ul><li>no important arenas found</li></ul>';
                  
                }
                
                $strTab4.= '<p class="center"><a href="/arenas.html?id_country='.$id.'">show all <b>'.$IntCountAll.'</b> arenas from '.$Name.'</a></p>';
                $strTab4.= '</div>';
                }
                
          	  
            
              if (!empty($link_1) or !empty($link_2) or !empty($link_3)) {
                $strTab5.= '<div id="fragment-4" class="box tabs">';
                $strTab5.=  '<ul>';
                if (!empty($link_1))  $strTab5.=  '<li><a onclick="return !window.open(this.href);" href="http://'.$link_1.'">'.$link_1.'</a></li>';
                if (!empty($link_2))  $strTab5.=  '<li><a onclick="return !window.open(this.href);" href="http://'.$link_2.'">'.$link_2.'</a></li>';
                if (!empty($link_3))  $strTab5.=  '<li><a onclick="return !window.open(this.href);" href="http://'.$link_3.'">'.$link_3.'</a></li>';
                $strTab5.=  '</ul>';
                $strTab5.=  '</div>';
              }
              
            
            if (!empty($strTab1) OR !empty($strTab2) OR !empty($strTab3) OR !empty($strTab4) OR !empty($strTab5)){
             echo '<ul>';
              if (!empty($strTab1)) {echo '<li><a href="#fragment-1"><span>History</span></a></li>';}
              if (!empty($strTab2)) {echo '<li><a href="#fragment-5"><span>Best achievments</span></a></li>';}
              if (!empty($strTab3)) {echo '<li><a href="#fragment-2"><span>Notable players</span></a></li>';}
              if (!empty($strTab4)) {echo '<li><a href="#fragment-3"><span>Arenas</span></a></li>';}
              if (!empty($strTab5)) {echo '<li><a href="#fragment-4"><span>Links</span></a></li>';}
             echo '</ul>'; 
             echo $strTab1;
             echo $strTab2;
             echo $strTab3;
             echo $strTab4;
             echo $strTab5;
            }
            
            ?>
            
      
      </div>
        
        <?php show_ads("ads_col_right_subpages"); ?>

      
        <?php
           $query="SELECT articles.id,articles.header FROM articles
                   INNER JOIN articles_items ON articles.id=articles_items.id_article  
                   WHERE 
                      articles.show_item=1 AND articles.pub_date<=NOW() AND articles.pub_date<=NOW() AND (expire_date>=NOW() OR expire_date='0000-00-00 00:00:00')
                      AND articles_items.id_item_type=1 AND articles_items.id_item=".$id." 
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
                      echo '<a href="/article/'.get_url_text($write['header'],$write['id']).'" title="Read article: '.$write['header'].'">'.$write['header'].'</a>';
                    echo '</li>';
                  }
                  echo '</ul>
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
                              echo '<a href="/club/'.get_url_text($ItemName,$write['id_club_home']).'?season='.$intSeason.'" title="Show club profile: '.$ItemName.'">'.$ItemName.'</a>';
                      echo '&nbsp;-&nbsp;';
                              $ItemName=$games->GetActualClubName($write['id_club_visiting'],$intSeason);
                              $GameName.=$ItemName;
                              echo '<a href="/club/'.get_url_text($ItemName,$write['id_club_visiting']).'?season='.$intSeason.'" title="Show club profile: '.$ItemName.'">'.$ItemName.'</a>';
                              $strLeagueName=$games->GetActualLeagueName($write['id_league'],ActSeasonWeb);
                              echo ' <small>(<a href="/league/'.get_url_text($strLeagueName,$write['id_league']).'" title="Show league profile: '.$strLeagueName.'">'.$strLeagueName.'</a>)</small>';
                      echo '</td>';
                      echo '<td class="score" valign="top"><a href="/game/detail/'.get_url_text($GameName,$write['id']).'" title="Show game details: '.$GameName.'">'.$games->GetScore($write['id'],1).'</a></span></td>';
                      echo '<td class="link" valign="top"><span class="link"><a href="/game/detail/'.get_url_text($GameName,$write['id']).'" title="Show game details: '.$GameName.'">details&raquo;</a></span></td>';
               }
         
              $query="SELECT games.id FROM games 
                        INNER JOIN leagues_countries_items ON games.id_league=leagues_countries_items.id_league  
                        WHERE leagues_countries_items.id_country=".$id." AND games.id_season=".ActSeasonWeb." AND games.date<=date(NOW()) AND games.home_score is NOT null AND games.visiting_score is NOT null
                        ORDER BY games.date DESC,	games.time DESC LIMIT 15";
              
              //echo $query;
              $intCountFuture=$con->GetQueryNum($query);  
              if ($intCountFuture>0){
                  if ($counter==0){
                  echo '<div class="header_cufon"><span class="header_text">Game fixtures</span><span class="toright"><a href="/games.html?id_country='.$id.'" title="Show all games">Show all games</a></span></div>';
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
          
        
        <?
        $strFastFactsBox="";
          
          //Celkovy pocet tymu v zemi
          $query="SELECT count(*) as pocet from clubs WHERE id_country='".$shortcut."'";
          if ($con->GetQueryNum($query)>0){
            $result = $con->SelectQuery($query);
            $write = $result->fetch_array();
            $strFastFactsBox.='<li><strong>Number of teams in our database:</strong> '.$write['pocet'].'</li>';
          }
          
          //Celkovy pocet hracu v zemi
          if (!empty($registered_players)) $strFastFactsBox.='<li><strong>Registered players:</strong> '.$registered_players.'</li>';
          
          //Celkove pocet aren v zemi
          $query="SELECT count(*) as pocet from arenas WHERE id_country=".$id."";
          if ($con->GetQueryNum($query)>0){
            $result = $con->SelectQuery($query);
            $write = $result->fetch_array();
            $strFastFactsBox.='<li><strong>Number of arenas in our database:</strong> '.$write['pocet'].'</li>';
          }
          //Rok zalozeni hokejove asociace
          if (!empty($year_founded)) $strFastFactsBox.='<li><strong>Year ice hockey association was founded in:</strong> '.$year_founded.'</li>';
          
          if (!empty($strFastFactsBox)){
            echo '
            <div class="header_cufon"><span class="header_text">Fast facts</span></div>
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