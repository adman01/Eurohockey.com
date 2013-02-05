<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");
$strHeaderKeywords='clubs, list';
$strHeaderDescription='Clubs';
require_once("inc/head.inc");
echo $head->setTitle($strHeaderDescription." - ".strProjectName);
echo $head->setEndHead();

$id_country=$_GET['id_country'];
$id_country=$input->check_number($id_country);
if (empty($id_country)){
echo '<body>';
}else{
echo '<body onload="show_leagues_select(\'club_table\','.$id_country.',1);">';
}

$id_league=$_GET['id_league'];
$id_league=$input->check_number($id_league);
if (empty($id_league)){
echo '<body>';
}else{
echo '<body onload="show_club_table(\'club_table\',\'\','.$id_league.')">';
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
         
         <h1>Clubs</h1>
         
         <form action="">
             <div class="header box_normal">&nbsp;</div>
             <div class="box normal">
              
              
              <table>
                <tr>
                  <td valign="top">
                  
              <?php
              $query="SELECT id,shortcut FROM countries ORDER BY name ASC";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    $countClubs=$con->GetSQLSingleResult("SELECT count(*) as item FROM clubs WHERE id_country='".$write['shortcut']."'");
                    $strCountryList.='<option value="'.$write['id'].'">'.$games->GetActualCountryName($write['id'],ActSeasonWeb).' ('.$countClubs.' clubs)</option>';
                  }
              }
             ?>
              <b>Select country:</b> <select name="id_country" id="club_table" onchange="show_leagues_select('club_table',0,0)">
                 <option value="">- Select country -</option>
	               <?php echo $strCountryList; ?>
              </select>
                  </td>
                  <td valign="top">
                  
                  <b>or search</b>: <input type="text" size="20" onkeyup="club_table_search('club_table')" onclick="input_table_clean('club_search')" name="club_search" id="club_search" maxlength="255" value="Type searched club" />
                  
                  </td>
            </tr>
            
            <tr>
              <td colspan="2">
                  
                  <div id="club_table_select" class="">
                  <input type="hidden" name="club_table_id_league" id="club_table_id_league" value="0" />
               </td>    
               </div>
                  
                </tr>
                
              </table>
              
              </div>
              
              <div class="space">&nbsp;</div>
              
              <div class="header box_normal">&nbsp;</div>
              <div class="box normal">
                
                <div class="toleft"><b>Clubs in:</b></div>       
                <a href="javascript:void(0);" onclick="show_leagues_select('club_table',13,1)" title="Leagues in Canada"><?php echo get_flag(strtolower("can"),26,19);?></a>
                <a href="javascript:void(0);" onclick="show_leagues_select('club_table',5,1)" title="Leagues in Czech republic"><?php echo get_flag(strtolower("cze"),26,19);?></a>
                <a href="javascript:void(0);" onclick="show_leagues_select('club_table',3,1)" title="Leagues in Finland"><?php echo get_flag(strtolower("fin"),26,19);?></a>
                <a href="javascript:void(0);" onclick="show_leagues_select('club_table',4,1)" title="Leagues in Germany"><?php echo get_flag(strtolower("ger"),26,19);?></a>
                <a href="javascript:void(0);" onclick="show_leagues_select('club_table',7,1)" title="Leagues in Russia"><?php echo get_flag(strtolower("rus"),26,19);?></a>
                <a href="javascript:void(0);" onclick="show_leagues_select('club_table',6,1)" title="Leagues in Slovakia"><?php echo get_flag(strtolower("svk"),26,19);?></a>
                <a href="javascript:void(0);" onclick="show_leagues_select('club_table',2,1)" title="Leagues in Sweden"><?php echo get_flag(strtolower("swe"),26,19);?></a>
                <a href="javascript:void(0);" onclick="show_leagues_select('club_table',1,1)" title="Leagues in Switzerland"><?php echo get_flag(strtolower("sui"),26,19);?></a>
                <a href="javascript:void(0);" onclick="show_leagues_select('club_table',14,1)" title="Leagues in USA"><?php echo get_flag(strtolower("usa"),26,19);?></a>
                <div class="clear">&nbsp;</div>
              </div>
              
              </form>
              
               <div style="position:relative">
                <div id="ajax_loading" class="hidden">Please wait, searching for clubs...</div>
               </div>
              
              <div id="club_table_box">
                <div class="space">&nbsp;</div>
                <p class="center bold">Please select country first, or type searched club.</p>
              </div>
              
              
              
       
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