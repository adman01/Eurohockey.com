<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");

$id=$_GET['id'];
$id=$input->valid_text($id,true,true);
$id=get_ID_from_url($id."-draft");
if (empty($id)){header("Location: /text/404-error.html");}
$id=$id+123;

$strHeaderKeywords='NHL draft details players '.$id;
$strHeaderDescription='NHL Draft '.$id;
require_once("inc/head.inc");
echo $head->setTitle($strHeaderDescription." - ".strProjectName);
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
                <div class="corner_nav_middle">&nbsp;</div>
                <div class="corner_nav_box">NHL Draft</div>
                <div class="corner_nav_middle">&nbsp;</div>
                <div class="corner_nav_box"><?php echo $id; ?></div>
                <div class="corner_nav_right">&nbsp;</div>
                
            </div>
         </div>
         
         <div class="space">&nbsp;</div>
         
          <?php
          $query="SELECT * FROM  players_draft WHERE 	d_year=".$id." AND d_position<>0 AND d_position<300  ORDER BY d_position ASC";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo ' <div id="item_preview" class="nophoto">';
                  echo '<h1>NHL Draft '.$id.'</h1>';
                  echo '<ul>';
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    echo '<li><b>'.$write['d_position'].'.</b> ';
                    $player_name=$con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$write['id_player']);
                    $player_nationality=$con->GetSQLSingleResult("SELECT nationality as item FROM players WHERE id=".$write['id_player']);
                     echo get_flag(strtolower($player_nationality),15,11);
                     echo ' <span><a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player profile: '.$player_name.'">'.$player_name.'</a>';
                     echo ' drafted by <b>'.$write['team_name'].'</b>';
                    echo '</li>';
                  }
                  echo '</ul>';
                  echo ' </div>';
              }
          
          ?>
    
         
         <!-- main text end -->
      </div>
      
      <div id="col_right">
        <!--column right -->
        
        <div id="ads_col_right">
        ad tails banners 2x
        </div>
        <div class="space">&nbsp;</div>
        
        <?php require_once("inc/col_right_player_search.inc"); ?>
         
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