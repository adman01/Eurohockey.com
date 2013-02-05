<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");
$strHeaderKeywords='transfers, list';
$strHeaderDescription='Transfers';
require_once("inc/head.inc");
echo $head->setTitle($strHeaderDescription." - ".strProjectName);
echo $head->setEndHead();

$id_country=$_GET['id_country'];
$id_country=$input->valid_text($id_country,true,true);
$id_country_split = split("-",$id_country);
$id_country=$id_country_split[0];
$str_country=$id_country_split[1];
                                
$id_nationality=$_GET['id_nationality'];
$id_nationality=$input->valid_text($id_nationality,true,true);
$id_league_get=$_GET['id_league'];
if (!empty($id_league_get)) $id_league=get_ID_from_url($id_league_get);
$id_league=$input->check_number($id_league);

$id_status=$_GET['id_status'];
$id_status=$input->check_number($id_status);


$date_from=$_GET['date_from'];
$date_from=$input->valid_text($date_from,true,true);
$strDateFromDatePicker=date("d.m.Y",strtotime($date_from));
$date_to=$_GET['date_to'];
$date_to=$input->valid_text($date_to,true,true);
$strDateToDatePicker=date("d.m.Y",strtotime($date_to));

$search_player=$_GET['search_player'];
$search_player=$input->valid_text($search_player,true,true);
$search_club=$_GET['search_club'];
$search_club=$input->valid_text($search_club,true,true);

if (!empty($search_club)){
    $search_player="";
}


?>

<link rel="stylesheet" href="/inc/jquery/css/redmond/jquery-ui-1.8.6.custom.css" type="text/css" media="all" />
<script type="text/javascript">
	$(function() {
		$( "#date_from").datepicker();
		$( "#date_from").datepicker( "option", "dateFormat", "d.m.yy");
	  <?php if (!empty($date_from)){ ?>	
		$( "#date_from").datepicker( "setDate" , "<?php echo $strDateFromDatePicker; ?>");
		<?php } ?>
		$( "#date_to" ).datepicker();
		$( "#date_to" ).datepicker( "option", "dateFormat", "d.m.yy");
		<?php if (!empty($date_to)){ ?>
		$( "#date_to").datepicker( "setDate" , "<?php echo $strDateToDatePicker; ?>");
		<?php } ?>
		
	});
	</script>
<?php

if (empty($id_country)){
echo '<body>';
}else{
echo '<body onload="show_leagues_set_selected(\'transfers_list_form_select\',\''.$id_league_get.'\')">';
}
?>

<div id="ads_top"><?php show_ads("top_main"); ?></div>
<div id="layout" class="longer">
  
  <div id="logo"><?php require_once("inc/logo.inc"); ?></div>
  <div id="menu"><?php require_once("inc/menu.inc"); ?></div>
  <div id="main">
      
      <div id="text">
         <!-- main text -->
         
         <h1>Transfers</h1>
         
         
        <form action="">
        <div class="header box_normal">&nbsp;</div>
        <div class="box normal">
              <p>
              <b>Search player:</b> 
              <input type="text" size="55"  style="width:150px" class="transfer" name="search_player" id="search_player" maxlength="255" value="<?php echo $search_player; ?>" onclick="input_table_clean('search')" />
              <b> OR club:</b> 
              <input type="text" size="55"  style="width:150px" class="transfer" name="search_club" id="search_club" maxlength="255" value="<?php echo $search_club; ?>" onclick="input_table_clean('search')" />
              </p>
              <p>
              <b>Player nationality:&nbsp;&nbsp;</b>
              <select name="id_nationality" style="width:200px">
                 <option value="">- select country -</option>
	               <?php
              $query="SELECT shortcut,id FROM countries ORDER BY name ASC";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    echo '<option value="'.$write['shortcut'].'"'.write_select($write['shortcut'],$id_nationality).'>'.$games->GetActualCountryName($write['id'],ActSeasonWeb).'</option>';
                  }
              }
             ?>
              </select>
             </p>
             <p>
              <b>Transfer date from:</b>&nbsp;&nbsp;<input type="text" size="10" class="date" name="date_from" id="date_from" maxlength="10" value="<?php echo $date_from; ?>" />
              <b>to:</b> <input type="text" size="10" class="date" name="date_to" id="date_to" maxlength="10" value="<?php echo $date_to; ?>" />
             </p> 
             
             <p>
              <b>Transfer status:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
              <select name="id_status" style="width:200px">
                 <option value="">- all -</option>
	               <?php
              $query="SELECT name,id FROM transfers_source_list ORDER BY name ASC";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    echo '<option value="'.$write['id'].'"'.write_select($write['id'],$id_status).'>'.$write['name'].'</option>';
                  }
              }
             ?>
              </select>
             </p>
              
              
              <?php
              $query="SELECT id,shortcut FROM countries ORDER BY name ASC";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    $strCountryList.='<option value="'.$write['id'].'-'.$write['shortcut'].'"'.write_select($write['id'],$id_country).'>'.$games->GetActualCountryName($write['id'],ActSeasonWeb).'</option>';
                  }
              }
             ?>
              
              <div id="transfers_list_box2">
              <b>From / to Country:</b>&nbsp;&nbsp;&nbsp;
              <select name="id_country" id="transfers_list" onchange="show_leagues('transfers_list')">
                 <option value="">- select country -</option>
	               <?php echo $strCountryList; ?>
              </select>
              </div>
              
              
              <div id="transfers_list_box" class="hidden" style="float:left;">
                <div class="toleft"><b>or league:</b>&nbsp;</div>
                <div class="toleft" id="transfers_list_select"></div>
                <div id="transfers_anchor""></div>
                
              </div>
              
              
              <div class="clear">&nbsp;</div>
              
              
              <span class="transfer_submit center"><input type="submit" class="submit" value="" /></span> 
              
              <div class="clear">&nbsp;</div>
         </div>
        </form>
        
        <div class="space">&nbsp;</div>
              
              <div class="header box_normal">&nbsp;</div>
              <div class="box normal">
                
                <div class="toleft"><b>TOP transfers in:</b></div>
                <a href="/transfers.html?id_country=13-CAN" title="Transfers in Canada"><?php echo get_flag(strtolower("can"),26,19);?></a>
                <a href="/transfers.html?id_country=5-CZE" title="Transfers in Czech republic"><?php echo get_flag(strtolower("cze"),26,19);?></a>
                <a href="/transfers.html?id_country=3-FIN" title="Transfers in Finland"><?php echo get_flag(strtolower("fin"),26,19);?></a>
                <a href="/transfers.html?id_country=4-GER" title="Transfers in Germany"><?php echo get_flag(strtolower("ger"),26,19);?></a>
                <a href="/transfers.html?id_country=7-RUS" title="Transfers in Russia"><?php echo get_flag(strtolower("rus"),26,19);?></a>
                <a href="/transfers.html?id_country=6-SVK" title="Transfers in Slovakia"><?php echo get_flag(strtolower("svk"),26,19);?></a>
                <a href="/transfers.html?id_country=2-SWE" title="Transfers in Sweden"><?php echo get_flag(strtolower("swe"),26,19);?></a>
                <a href="/transfers.html?id_country=1-SUI" title="Transfers in Switzerland"><?php echo get_flag(strtolower("sui"),26,19);?></a>
                <a href="/transfers.html?id_country=14-USA" title="Transfers in USA"><?php echo get_flag(strtolower("usa"),26,19);?></a>
                <div class="clear">&nbsp;</div>
              </div>
         
        
        <script type="text/javascript">
        show_leagues('transfers_list');
        </script> 
         
         <?php
         
         
        If (!empty($id_country))	{$FilterCountry="
             AND (transfers.id_country_from=".$id_country." OR transfers.id_country_to=".$id_country." OR clubs1.id_country='".$str_country."' OR  clubs2.id_country='".$str_country."')
            ";
        }
          
        If (!empty($id_league))	{
          $FilterCountry="";
          $FilterLeague=" AND (
            (SELECT count(*) FROM clubs_leagues_items WHERE clubs_leagues_items.id_league=".$id_league." AND (clubs_leagues_items.id_club=transfers.id_club_from or clubs_leagues_items.id_club=transfers.id_club_to))>0
          )";
        }
        
        If (!empty($id_nationality))	{$FilterNation=" AND (
          SELECT count(*) FROM players WHERE
            (nationality='".$id_nationality."' or  	nationality_2='".$id_nationality."')
            AND players.id=transfers.id_player
           )=1
        
        ";}
        
        
        If (!empty($date_from))	{$FilterDateFROM=" AND (date_time>='".date("Y-m-d",strtotime($date_from))."')";}
        If (!empty($date_to))	{$FilterDateTO=" AND (date_time<='".date("Y-m-d",strtotime($date_to))."')";}
        
         if (!empty($search_club)){
            $FilterClub=" AND (clubs1.name LIKE '%".$search_club."%' OR clubs2.name LIKE '%".$search_club."%')";
         }
         if (!empty($search_player)){
            $FilterPlayer=" AND ((CONCAT_WS(\" \",players.name,players.surname) LIKE '%".$search_player."%' or CONCAT_WS(\" \",players.surname,players.name) LIKE '%".$search_player."%' or players.name LIKE '%".$search_player."%' or players.surname LIKE '%".$search_player."%'))";
         }
         
         if (!empty($id_status)){
            $FilterStatus=" AND id_source_note=".$id_status;
         }
         
         
         
                 $query="SELECT 
                 transfers.date_time, transfers.id_player,transfers.id_position,transfers.id_club_from,transfers.id_club_to,transfers.id_country_from,transfers.id_country_to,transfers.id_retire_status,transfers.note,transfers.source,transfers.id_source_note 
                 FROM transfers 
                        INNER JOIN players ON transfers.id_player = players.id
                        INNER JOIN clubs as clubs1 ON transfers.id_club_from = clubs1.id INNER JOIN clubs as clubs2 ON transfers.id_club_to = clubs2.id
                 WHERE 1 
                  
                  ".$FilterPlayer." ".$FilterClub." ".$FilterCountry." ".$FilterLeague." ".$FilterNation." ".$FilterDateFROM." ".$FilterDateTO." ".$FilterStatus." 
                  ORDER BY transfers.date_time DESC, transfers.id DESC";
            
         
         
         //echo $query;
         //break; 
         $listovani = new listing($con,"?id_country=".$_GET['id_country']."&amp;id_league=".$_GET['id_league']."&amp;date_from=".$_GET['date_from']."&amp;date_to=".$_GET['date_to']."&amp;search=".$_GET['search']."&amp;",50,$query,3,"",$_GET['list_number']);
              $query=$listovani->updateQuery();
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '<table class="basic transfers">';
                  $result = $con->SelectQuery($query);
                  $counter=0;
                  while($write = $result->fetch_array()){
                    $counter++;
                    if ($counter%2) $style="dark"; else $style="";
  		              echo '<tr class="'.$style.'">';
                      
                      echo '<td class="date" valign="top">'.date("d M Y",strtotime($write['date_time'])).' | </td>';
                      echo '<td class="player" valign="top">';
                      $player_name=$con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$write['id_player']);
                      $player_nationality=$con->GetSQLSingleResult("SELECT nationality as item FROM players WHERE id=".$write['id_player']);
                      echo get_flag(strtolower($player_nationality),15,11);
                      echo '<span><a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player: '.$player_name.'" style="font-size:1.1em">'.$player_name.'</a> 
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
                              echo '<a href="/club/'.get_url_text($ItemName,$write['id_club_from']).'" title="Show club: '.$ItemName.'">'.$ItemName.'</a>';
                            }else{
                              //
                              $ItemName=$games->GetActualCountryName($write['id_country_from'],$intSeason);
                              $CountryShortCut=$con->GetSQLSingleResult("SELECT shortcut as item FROM countries WHERE id=".$write['id_country_from']);
                              echo get_flag(strtolower($CountryShortCut),15,11);
                              echo '<a href="/country/'.get_url_text($ItemName,$write['id_country_from']).'" title="Show country: '.$ItemName.'">'.$ItemName.'</a>';
                            }
                            }
                      echo '<img src="/img/arrow_right.png" class="arrow_right" alt="arrow" height="9" width="12" />';
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
                              echo '<a href="/club/'.get_url_text($ItemName,$write['id_club_to']).'" title="Show club: '.$ItemName.'">'.$ItemName.'</a>';
                              
                              
                              
                            }else{
                              //
                              $ItemName=$games->GetActualCountryName($write['id_country_to'],$intSeason);
                              $CountryShortCut=$con->GetSQLSingleResult("SELECT shortcut as item FROM countries WHERE id=".$write['id_country_to']);
                              echo get_flag(strtolower($CountryShortCut),15,11);
                              echo '<a href="/country/'.get_url_text($ItemName,$write['id_country_to']).'" title="Show country: '.$ItemName.'">'.$ItemName.'</a>';
                            }
                            }
                      echo '</td>';
                    echo '</tr>';
                    
                    $strAllTransfer="";
                    
                    if (!empty($write['id_source_note'])) {
                          $strCredibility=$con->GetSQLSingleResult("SELECT name as item FROM transfers_source_list WHERE id=".$write['id_source_note']." ORDER by id DESC");
                          if (!empty($strAllTransfer)) $strAllTransfer.=", ";
                          $strAllTransfer.="<b>Credibility:</b> ".$strCredibility;
                    }
                    
                    if ($write['id_retire_status']>1) {
                          $strRetire=$con->GetSQLSingleResult("SELECT name as item FROM transfers_retire_list WHERE id=".$write['id_retire_status']." ORDER by id DESC");
                          if (!empty($strAllTransfer)) $strAllTransfer.=", ";
                          $strAllTransfer.="<b>Retire:</b> ".$strRetire;
                    }
                    if (!empty($write['source'])) {
                          if (!empty($strAllTransfer)) $strAllTransfer.=", ";
                          $strSource = preg_replace('#(http://|ftp://|(www\.))([\w\-]*\.[\w\-\.]*([/?][^\s]*)?)#e',"'<a onclick=\"return !window.open(this.href);\" href=\"'.('\\1'=='www.'?'http://':'\\1').'\\2\\3\">'.((strlen('\\2\\3')>23)?(substr('\\2\\3',0,20).'&hellip;'):'\\2\\3').'</a>'",$write['source']); 
                          $strAllTransfer.="<b>Source:</b> ".$strSource;
                    }
                    if (!empty($write['note'])) {
                          if (!empty($strAllTransfer)) $strAllTransfer.=", ";
                          $strNote = preg_replace('#(http://|ftp://|(www\.))([\w\-]*\.[\w\-\.]*([/?][^\s]*)?)#e',"'<a onclick=\"return !window.open(this.href);\" href=\"'.('\\1'=='www.'?'http://':'\\1').'\\2\\3\">'.((strlen('\\2\\3')>23)?(substr('\\2\\3',0,20).'&hellip;'):'\\2\\3').'</a>'",$write['note']); 
                          $strAllTransfer.="<b>Note:</b> ".$strNote;
                          
                    }
                    
                    
                    if (!empty($strAllTransfer)) echo '<tr class="'.$style.' noborder"><td colspan="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$strAllTransfer.'</td></tr>';
                  }
                   echo '<tfoot>';
	                //listovani
                  $listovani->show_list();
                  echo '</tfoot>';
                  echo '</table>';
                 
              }else{
                 echo '<p class="center bold">No transfers found for specified criteria.</p>';
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