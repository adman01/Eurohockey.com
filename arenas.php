<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");

$id=$_GET['id'];
$id=$input->valid_text($id,true,true);
$id=get_ID_from_url($id);
if (empty($id)){header("Location: /text/404-error.html");}

$query="SELECT * from arenas WHERE id=".$id;
if ($con->GetQueryNum($query)>0){
    $result = $con->SelectQuery($query);
    $write = $result->fetch_array();
    $StrName=$games->GetActualArenaName($write['id'],ActSeasonWeb);
    $url='/arena/'.get_url_text($name,$write['id']);
    
    $id_status=$write['id_status'];
    $id_status_info=$write['id_status_info'];
    $IntIdCountry=$write["id_country"];
	  $StrArenaAddress=$write["address"];
	  $StrArenaTelephone=$write["telephone"];
	  $StrArenaCapacity_overall=$write["capacity_overall"];
	  $StrGPS_location=$write["GPS_location"];
	  $StrArenafax=$write['fax'];
    $StrArenaemail=$write['email'];
	  $StrArenaYear_built=$write["year_built"];
	  $StrArenaYear_roofed=$write["year_roofed"];
	  
	  $StrArenaAlso_known_as=$write["also_known_as"];
	  $StrArenaCapacity_overall=$write["capacity_overall"];
	  $StrArenaCapacity_seating=$write["capacity_seating"];
	  $StrArenaRink_size=$write["rink_size"];
	  $StrArenaRoofed=$write["roofed"];
	  $StrArenaLast_major_reconstruction=$write["last_major_reconstruction"];
	  $StrArenaAlso_used_for=$write["also_used_for"];
	  $StrArenaMost_notable_gamesr=$write["most_notable_games"];
	  
	  $StrArenaLink_1=$write["link_1"];
	  $StrArenaLink_2=$write["link_2"];
	  $StrArenaLink_3=$write["link_3"];
	  
	  $id_photo_folder=$write["id_photo_folder"];
	  
}else{
    header("Location: /text/404-error.html");
}

$strHeaderKeywords='arena details '.$StrName.'';
$strHeaderDescription=$StrName.' details';

require_once("inc/head.inc");
require_once("inc/header_pirobox.inc");
echo $head->setTitle($strHeaderDescription." - ".strProjectName);
?>
<script type="text/javascript">
$(function() {
   $( "#rotate" ).tabs({ fx: { opacity: 'toggle' } });
});

</script>
<link rel="stylesheet" href="/inc/ui.tabs.css" type="text/css" media="screen, projection" />
<?php

if (!empty($StrGPS_location)){
	
	  ?>
	  <script type="text/javascript" src="http://www.google.com/jsapi?key=ABQIAAAAY-eFcSG5OGTrWqsfW55P0RTn-s35hD_li_1h8KWM0nhafi8w1xRSfpnKq07IePmh0Rr1WLg4QJ0OEg"></script>
    <script type="text/javascript">
      google.load("maps", "2",{"other_params":"sensor=true"});

      function initialize() {
        var map = new google.maps.Map2(document.getElementById("map"));
        map.addControl(new GSmallMapControl());
      	map.addControl(new GMapTypeControl());
        map.setCenter(new google.maps.LatLng(<?php echo $StrGPS_location; ?>), 13);
        var marker = new GMarker(new GLatLng(<?php echo $StrGPS_location; ?>));
      	map.addOverlay(marker);


      }
      google.setOnLoadCallback(initialize);
    </script>
	  <?php
	  }

echo $head->setEndHead();
?>
<body>

<div id="ads_top"><?php show_ads("top_main"); ?></div>
<div id="layout" class="shorter">>
  
  <?php require_once("inc/bar_login.inc"); ?>
  <div id="logo"><?php require_once("inc/logo.inc"); ?></div>
  <div id="menu"><?php require_once("inc/menu.inc"); ?></div>
  <div id="main">
      
      <div id="text" class="shorter">
         <!-- main text -->
         
         
         
         <div id="navigation">
            <div class="toleft">
                <div class="corner_nav_left">&nbsp;</div>
                <div class="corner_nav_box"><a href="/arenas.html" title="Arenas">Arenas</a></div>
                <?php
                //countries
                  $query="SELECT id as item FROM countries WHERE id=".$IntIdCountry." ORDER by item";
                  if ($con->GetQueryNum($query)>0){
                    echo '<div class="corner_nav_middle">&nbsp;</div>';
                    echo '<div class="corner_nav_box">';
                    $result = $con->SelectQuery($query);
                    $write = $result->fetch_array();
                      $NameCountry=$games->GetActualCountryName($write['item'],$intSeason);
                      echo '<a href="/arenas.html?id_country='.$write['item'].'" title="'.$NameCountry.'">'.$NameCountry.'</a>';
                      echo '</div>';
                  }
                ?>
                <div class="corner_nav_middle">&nbsp;</div>
                <div class="corner_nav_box"><?php echo $StrName; ?></div>
                <div class="corner_nav_right">&nbsp;</div>
                
            </div>
         </div>
         
         <div class="space">&nbsp;</div>
         
          <?php
         
              echo '<div id="item_logo">';
	             if (!empty($id_photo_folder)){
	                  $id_image=$con->GetSQLSingleResult("SELECT id as item FROM photo WHERE id_photo_folder=".$id_photo_folder." ORDER by id DESC");
                    if (!empty($id_image)){
	                     $strPhotoPath=get_photo_name($id_image);
	                     if (file_exists(PhotoFolder."/".$strPhotoPath)) {
	                       
                          echo '<img class="border" src="/image/155-150-1-'.str_replace("-","_",$strPhotoPath).'" alt="'.$StrName.' logo" />';
                         
                       }else {echo '<img class="" src="/img/default.jpg" width="190" alt="Logo not found" />';}
	                   }else {echo '<img class="" src="/img/default.jpg" width="190" alt="Logo not found" />';}
	                  }else {echo '<img class="" src="/img/default.jpg" width="190" alt="Logo not found" />';}
	            
              echo '</div>';
              
              echo ' <div id="item_preview">';
              echo '<h1>'.$StrName.'</h1>';
                echo '<ul>';
                  
                  $strStatus=$con->GetSQLSingleResult("SELECT name as item FROM arenas_status_list WHERE id=".$id_status);
                  if (!empty($id_status_info)) $strStatusInfo=" (".$id_status_info.")";
                  echo '<li><strong>Status:</strong> '.$strStatus.$strStatusInfo.'</li>';
                  
                  if (!empty($StrArenaAlso_known_as))echo '<li><b>Also known as:</b> '.$StrArenaAlso_known_as.'</li>';
                  if (!empty($StrArenaAddress))echo '<li><b>Adress:</b> '.nl2br($StrArenaAddress).'</li>';
                  if (!empty($StrGPS_location))echo '<li><b>GPS:</b> '.$StrGPS_location.'</li>';
                  if (!empty($StrArenaTelephone))echo '<li><b>Phone:</b> '.$StrArenaTelephone.'</li>';
                  
                  if (!empty($StrArenafax))echo '<li><b>Fax:</b> '.$StrArenafax.'</li>';
                  if (!empty($StrArenaemail)) echo '<a href="mailto:'.$StrArenaemail.'">'.$StrArenaemail.'</a>';
                  
                  if (!empty($StrArenaLink_1)) $StrArenaLink_1='<a onclick="return !window.open(this.href);" href="'.$StrArenaLink_1.'">'.$StrArenaLink_1.'</a>';
                  if (!empty($StrArenaLink_2)) $StrArenaLink_2='<a onclick="return !window.open(this.href);" href="'.$StrArenaLink_2.'">'.$StrArenaLink_2.'</a>';
                  if (!empty($StrArenaLink_3)) $StrArenaLink_3='<a onclick="return !window.open(this.href);" href="'.$StrArenaLink_3.'">'.$StrArenaLink_3.'</a>';
                  if ( !empty($StrArenaLink_2)) $link_2_main=', '.$StrArenaLink_2;
                  if ( !empty($StrArenaLink_3)) $link_3_main=', '.$StrArenaLink_3;
                  if (!empty($StrArenaLink_1) or !empty($StrArenaLink_2) or !empty($StrArenaLink_3))  echo '<li><strong>WWW:</strong> '.$StrArenaLink_1.''.$link_2_main.''.$link_3_main.'</li>';
                  
                  if (!empty($StrArenaCapacity_overall))echo '<li><b>Capacity overall:</b> '.$StrArenaCapacity_overall.'</li>';
                  if (!empty($StrArenaCapacity_seating))echo '<li><b>Capacity seating:</b> '.$StrArenaCapacity_seating.'</li>';
                  
                  if (!empty($StrArenaYear_built))echo '<li><b>Opened in:</b> '.$StrArenaYear_built.'</li>';
                  if ($StrArenaRoofed==1) $StrArenaRoofedText="yes"; else $StrArenaRoofedText="no";
                  if (!empty($StrArenaRoofed))echo '<li><b>Roofed:</b> '.$StrArenaRoofedText.'</li>';
                  if (!empty($StrArenaYear_roofed) and $StrArenaRoofed==1)echo '<li><b>Roofed in:</b> '.$StrArenaYear_roofed.'</li>';
                  
                  if (!empty($StrArenaRink_size))echo '<li><b>Rink size:</b> '.$StrArenaRink_size.'</li>';
                  if (!empty($StrArenaLast_major_reconstruction))echo '<li><b>Last major reconstruction:</b> '.$StrArenaLast_major_reconstruction.'</li>';
                 
                echo '</ul>';
              echo '</div>';
              
            
              
              if (!empty($id_photo_folder)){
              
              $query="SELECT id,description  FROM photo WHERE show_item=1 AND id_photo_folder=".$id_photo_folder." ORDER by id DESC";
              //echo $query;
              $counter=0; 
              if ($con->GetQueryNum($query)>0){
                echo '
                <div class="clear">&nbsp;</div>
                <div class="space">&nbsp;</div>
                <div class="header_cufon blue_490"><span class="header_text blue">Arena photos</span></div>';
                echo '<div class="box normal">';
                
                $result = $con->SelectQuery($query);
                while($write = $result->fetch_array()){
                  $strPhotoPath=get_photo_name($write['id']);
	                if (file_exists(PhotoFolder."/".$strPhotoPath)) {
	                 
	                  echo '<a href="/'.PhotoFolder."/".$strPhotoPath.'" title="'.$write['description'].'" class="pirobox_gall">
	                  <span style="background-image: url(/image/140-140-1-'.str_replace("-","_",$strPhotoPath).');" class="image">&nbsp;</span>
                    </a>';
	                }
	                else{
                    echo '<span style="background-image: url(/image/140-140-1-'.str_replace("-","_","default.gif").');" class="image">&nbsp;</span>';
                  }
                  
                  $counter++;
                  if ($counter==3){ echo '<div class="clear">&nbsp;</div>';}
                  
                }
                
                echo '<div class="clear">&nbsp;</div>';
                echo '</div>';
                echo '<div class="space">&nbsp;</div>';
              }
            }
            
            
              
               
    ?>
    
    
    <?php echo socials(0); ?>
    
    <p class="center">Did you find any incorrect or incomplete information? <a href="/feedback.html?link=<?php echo curPageURL(); ?>">Please, let us know</a>.</p>
             
                          
         
         <!-- main text end -->
      </div>
      
      <div id="col_right" class="shorter">
        <!--column right -->
        
         <div id="rotate">
            
               <?
               if (!empty($StrArenaAlso_used_for)){
                  $strTab1.='<div id="fragment-1" class="box tabs">';
                  $strTab1.= $StrArenaAlso_used_for;
                  $strTab1.='</div>';
               }
                
            
               if (!empty($StrArenaMost_notable_gamesr)){
                  $strTab2.='<div id="fragment-2" class="box tabs">';
                  $strTab2.= $StrArenaMost_notable_gamesr;
                  $strTab2.='</div>';
                }
               
               
                $query="SELECT * FROM arenas_names WHERE id_arena=".$id." ORDER BY int_year ASC";
                //echo $query;
                $result = $con->SelectQuery($query);
                $IntCount=$con->GetQueryNum($query);
                $counter=0;
                if ($IntCount>0){
                $strTab3.='<div id="fragment-3" class="box tabs">';
                $strTab3.= '<ul>'; 
                while($write = $result->fetch_array())
                 {
                  $name_year[$counter]=$write['int_year']-1;
                  $name_name[$counter]=$write['name'];
	                $counter++; 
          	     }
          	     for ($i=0;$i<=($counter-1);$i++){
          	       if ($i==($counter-1)) {$name_year_write="present";} else {$name_year_write=$name_year[($i+1)];}
          	       $strTab3.= '<li>from '.$name_year[$i].' to '.$name_year_write.': <b>'.$name_name[$i].'</b></li>';
          	     }
          	     $strTab3.= '</ul>';
                 $strTab3.= '</div>';
          	     
          	    }
                
              if (!empty($StrArenaLink_1) or !empty($StrArenaLink_2) or !empty($StrArenaLink_3)) {
                $strTab4.='<div id="fragment-4" class="box tabs">';
                $strTab4.= '<ul>';
                if (!empty($StrArenaLink_1))  $strTab4.= '<li>'.$StrArenaLink_1.'</li>';
                if (!empty($StrArenaLink_2))  $strTab4.= '<li>'.$StrArenaLink_2.'</li>';
                if (!empty($StrArenaLink_3))  $strTab4.= '<li>'.$StrArenaLink_3.'</li>';
                $strTab4.= '</ul>';
                $strTab4.= '</div>';
              }
              
              
             if (!empty($strTab1) OR !empty($strTab2) OR !empty($strTab3) OR !empty($strTab4)){
             echo '<ul>';
              if (!empty($strTab1)) {echo '<li><a href="#fragment-1"><span>Also used for</span></a></li>';}
              if (!empty($strTab2)) {echo '<li><a href="#fragment-2"><span>Most notable games</span></a></li>';}
              if (!empty($strTab3)) {echo '<li><a href="#fragment-3"><span>Name changes</span></a></li>';}
              if (!empty($strTab4)) {echo '<li><a href="#fragment-4"><span>Links</span></a></li>';}
             echo '</ul>'; 
             echo $strTab1;
             echo $strTab2;
             echo $strTab3;
             echo $strTab4;
            }
              
            ?>

        </div>
        <div class="space">&nbsp;</div>
        
        <?php
                $query="SELECT id_club from clubs_arenas_items WHERE id_arena=".$id." ORDER by id_club ASC";
                $intCount=$con->GetQueryNum($query);
                $counter=0;
                if ($intCount>0){
                  echo '
                  <div class="header_cufon"><span class="header_text">Clubs in arena</span></div>
                  <div class="box"> 
                  <p>Clubs in '.$StrName.': ';
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    $NameClub=$games->GetActualClubName($write['id_club'],ActSeasonWeb);
                    echo '<a href="/club/'.get_url_text($NameClub,$write['id_club']).'" title="Show details about club: '.$NameClub.'">'.$NameClub.'</a>';
                    $counter++;
                    $id_club=$write['id_club'];
                    if ($counter<$intCount){ echo ', ';}
                  }
                  echo '</p>
                    </div>
                    
                    
                  ';
                  echo '<div class="space">&nbsp;</div>';
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
         
            
              
               $query="SELECT id FROM games  WHERE id_arena=".$id." AND id_season=".ActSeasonWeb." AND date<=date(NOW()) ORDER BY date DESC,	time DESC LIMIT 3";
              //echo $query;
              $intCountPast=$con->GetQueryNum($query);
              $counter=0;
              if ($intCountPast>0){
                  echo '<div class="header_cufon"><span class="header_text">Games in arena</span></div>';
                  
                   echo '<table class="games" cellspacing="0" cellpadding="0">';
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
              $query="SELECT id FROM games  WHERE  id_arena=".$id." AND id_season=".ActSeasonWeb." AND date>date(NOW())  ORDER BY date ASC,	time ASC LIMIT ".$intCountFuture;
              //echo $query; 
              $intCountFuture=$con->GetQueryNum($query);  
              if ($intCountFuture>0){
                  if ($counter==0){
                  echo '<div class="header_cufon"><span class="header_text">Games in arena</span></div>';
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
          
          <?php
          if (!empty($StrGPS_location)){
              echo '
                <div class="clear">&nbsp;</div>
                <div class="space">&nbsp;</div>
                <div id="map" style="width: 405px; height: 330px"></div>                  
              ';
              };
          
          ?>  
          <div class="space">&nbsp;</div>
            
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