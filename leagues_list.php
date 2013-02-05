<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");
$strHeaderKeywords='leagues, list';
$strHeaderDescription='Leagues';
require_once("inc/head.inc");
echo $head->setTitle($strHeaderDescription." - ".strProjectName);
echo $head->setEndHead();

$id_country=$_GET['id_country'];
$id_country=$input->check_number($id_country);
if (empty($id_country)){
echo '<body>';
}else{
echo '<body onload="show_leagues_table(\'leagues_table\',\'\','.$id_country.')">';
}
?>

<div id="ads_top"><?php show_ads("top_main"); ?></div>
<div id="layout" class="longer">
  
  <?php require_once("inc/bar_login.inc"); ?>
  <div id="logo"><?php require_once("inc/logo.inc"); ?></div>
  <div id="menu"><?php require_once("inc/menu.inc"); ?></div>
  <div id="main">
      
      <div id="text">
         <!-- main text -->
         
         <h1>Leagues</h1>
         
              <form action="">
              
              <div class="header box_normal">&nbsp;</div>
              <div class="box normal">
              <?php
              $query="SELECT id,shortcut FROM countries ORDER BY name ASC";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    $countLeagues=$con->GetSQLSingleResult("SELECT count(*) as item FROM leagues_countries_items WHERE id_country=".$write['id']."");
                    if ($countLeagues>1) $NameLeagues="leagues"; else $NameLeagues="league";
                    $strCountryList.='<option value="'.$write['id'].'">'.$games->GetActualCountryName($write['id'],ActSeasonWeb).' ('.$countLeagues.' '.$NameLeagues.')</option>';
                  }
              }
             ?>
              <b>Select country:</b> <select name="id_country" id="leagues_table" onchange="show_leagues_table('leagues_table','',0)">
                 <option value="">- Select country -</option>
	               <?php echo $strCountryList; ?>
              </select>
              
              <b>or search:</b> <input type="text" size="20" onkeyup="leagues_table_search('leagues_table')" onclick="input_table_clean('league_search')" name="league_search" id="league_search" maxlength="255" value="Type searched league" />
              <br>
              <p><b>or <a style="color:#AA0B0B" href="javascript:void(0);" onclick="show_tournaments_table('leagues_table','','')" title="Tournaments">show all tournaments</a></b></p>
                
              
              </div>
              
              <div class="space">&nbsp;</div>
              
              <div class="header box_normal">&nbsp;</div>
              <div class="box normal">
                
                <div class="toleft"><b>Top leagues in:</b></div>
                <a href="javascript:void(0);" onclick="show_leagues_table('leagues_table','',13)" title="Leagues in Canada"><?php echo get_flag(strtolower("can"),26,19);?></a>
                <a href="javascript:void(0);" onclick="show_leagues_table('leagues_table','',5)" title="Leagues in Czech republic"><?php echo get_flag(strtolower("cze"),26,19);?></a>
                <a href="javascript:void(0);" onclick="show_leagues_table('leagues_table','',3)" title="Leagues in Finland"><?php echo get_flag(strtolower("fin"),26,19);?></a>
                <a href="javascript:void(0);" onclick="show_leagues_table('leagues_table','',4)" title="Leagues in Germany"><?php echo get_flag(strtolower("ger"),26,19);?></a>
                <a href="javascript:void(0);" onclick="show_leagues_table('leagues_table','',7)" title="Leagues in Russia"><?php echo get_flag(strtolower("rus"),26,19);?></a>
                <a href="javascript:void(0);" onclick="show_leagues_table('leagues_table','',6)" title="Leagues in Slovakia"><?php echo get_flag(strtolower("svk"),26,19);?></a>
                <a href="javascript:void(0);" onclick="show_leagues_table('leagues_table','',2)" title="Leagues in Sweden"><?php echo get_flag(strtolower("swe"),26,19);?></a>
                <a href="javascript:void(0);" onclick="show_leagues_table('leagues_table','',1)" title="Leagues in Switzerland"><?php echo get_flag(strtolower("sui"),26,19);?></a>
                <a href="javascript:void(0);" onclick="show_leagues_table('leagues_table','',14)" title="Leagues in USA"><?php echo get_flag(strtolower("usa"),26,19);?></a>
                <div class="clear">&nbsp;</div>
              </div>
              
              </form>
              
               
               <div style="position:relative">
                <div id="ajax_loading" class="hidden">Please wait, searching for leagues...</div>
               </div>
              
              <div id="leagues_table_box">
                
                 
                  
                <div class="space">&nbsp;</div>
                <p class="center bold">Please select country (and league) first, or type searched league.</p>
              </div>
              
              
              <div class="space">&nbsp;</div>
              
              
         
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