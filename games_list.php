<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");
$strHeaderKeywords='games, list';
$strHeaderDescription='Games';
require_once("inc/head.inc");

echo $head->setTitle($strHeaderDescription." - ".strProjectName);


$id_country=$_GET['id_country'];
$id_country=$input->check_number($id_country);
$id_league_get=$_GET['id_league'];
if (!empty($id_league_get)) {
  $id_league=get_ID_from_url($id_league_get);
  if (empty($id_country)) $id_country=$con->GetSQLSingleResult("SELECT id_country as item FROM leagues_countries_items WHERE id_league=".$id_league);
}
$id_league=$input->check_number($id_league);

$id_club=$_GET['id_club'];
if (!empty($id_club)) $id_club=get_ID_from_url($id_club);
$id_club=$input->check_number($id_club);

$club_name=$_GET['club_name'];
$club_name=$input->valid_text($club_name,true,true);

if (!empty($id_club) AND empty($club_name)) $club_name=$games->GetActualClubName($id_club,ActSeason);

$date_from=$_GET['date_from'];
$date_from=$input->valid_text($date_from,true,true);
$strDateFromDatePicker=date("d.m.Y",strtotime($date_from));
$date_to=$_GET['date_to'];
$date_to=$input->valid_text($date_to,true,true);
$strDateToDatePicker=date("d.m.Y",strtotime($date_to));

$id_season=$_GET['id_season'];
$id_season=$input->check_number($id_season);

$id_show=$_GET['id_show'];
$id_show=$input->check_number($id_show);
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

echo $head->setEndHead();


switch ($id_show){
  case 1:
    $date_to="";
    if (empty($date_from)) $date_from=date("Y-m-d");
    $strOrderWhere=" ASC";
    $strHeaderGamesFront="Upcoming games ";
  break;
  case 2:
    $strOrderWhere=" ASC" ;
    $strHeaderGamesFront="Games ";
  break;
  default:
    if (empty($date_to)) $date_to=date("Y-m-d");
    $date_from="";
    $strOrderWhere=" DESC";
    $strHeaderGamesFront="Recent games ";
}

if (!empty($date_from)) $date_from=date("Y-m-d",strtotime($date_from));
if (!empty($date_to))   $date_to=date("Y-m-d",strtotime($date_to));
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
         
         <h1>Games</h1>
         
        <form action="">
        <div class="header box_normal">&nbsp;</div>
        <div class="box normal">
              <p>
              <b>Show:</b>&nbsp;&nbsp;&nbsp;&nbsp;
                <select name="id_show" class="id_show"> 
                <option value="">recent games</option>
			          <option value="1" <?php if ($_GET['id_show']==1) echo 'selected="selected"'; ?>>upcoming games</option>
			          <option value="2" <?php if ($_GET['id_show']==2) echo 'selected="selected"'; ?>>all games</option>
			          </select> 
              <b>and date from:</b> <input type="text" size="10" class="date" name="date_from" id="date_from" maxlength="10" value="<?php if (!empty($date_from)){ echo date("d.m.Y",strtotime($date_from));} ?>" />
              <b>to:</b> <input type="text" size="10" class="date" name="date_to" id="date_to" maxlength="10" value="<?php if (!empty($date_to)) {echo date("d.m.Y",strtotime($date_to));} ?>" /><br />
              </p>
              <?php
              $query="SELECT id FROM countries ORDER BY name ASC";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    $strCountryList.='<option value="'.$write['id'].'"'.write_select($write['id'],$id_country).'>'.$games->GetActualCountryName($write['id'],ActSeasonWeb).'</option>';
                  }
              }
             ?>
              
              <div id="transfers_list_box2">
              
              <div style="float:left">
              <b>Country:</b>&nbsp;&nbsp;<select name="id_country" id="transfers_list" onchange="show_leagues('transfers_list')">
                 <option value="">- select country -</option>
	               <?php echo $strCountryList; ?>
              </select>
              </div>
              <div id="transfers_list_box" class="hidden" style="float:left;margin-top:-5px">
                <div class="toleft"><b>or league:</b>&nbsp;</div>
                <div class="toleft" id="transfers_list_select"></div>
                <div id="transfers_anchor""></div>
                
              </div>
              
              </div>
              
              <div class="clear">&nbsp;</div>
              <p>
              <b>Club name:</b>&nbsp;
                <input type="text" name="club_name" style="width:130px" value="<?php echo $club_name; ?>" class="input-text" />
              
              &nbsp;<b>season:</b>&nbsp;
                <select name="id_season" class="input-text required">
			           <option value="0">select season</option>
			           <?php
                    for ($i=(ActSeasonWeb);$i>=1900;$i--) { 
	                     echo '<option value="'.$i.'" '.write_select($id_season,$i).'>'.($i-1).'/'.$i.'</option>';
	                   }
                 ?>
              </select>
              </p>
              
              <span class="transfer_submit center"><input type="submit" class="submit" value="" /></span> 
              
              <div class="clear">&nbsp;</div>
         </div>
        </form>
         <script type="text/javascript">
        show_leagues('transfers_list');
        </script> 
        
        <div class="space">&nbsp;</div>
              
              <div class="header box_normal">&nbsp;</div>
              <div class="box normal">
                
                <div class="toleft"><b>Top leagues in:</b></div>
                <a href="games.html?id_country=13" title="Leagues in Canada"><?php echo get_flag(strtolower("can"),26,19);?></a>
                <a href="games.html?id_country=5" title="Leagues in Czech republic"><?php echo get_flag(strtolower("cze"),26,19);?></a>
                <a href="games.html?id_country=3" title="Leagues in Finland"><?php echo get_flag(strtolower("fin"),26,19);?></a>
                <a href="games.html?id_country=4" title="Leagues in Germany"><?php echo get_flag(strtolower("ger"),26,19);?></a>
                <a href="games.html?id_country=7" title="Leagues in Russia"><?php echo get_flag(strtolower("rus"),26,19);?></a>
                <a href="games.html?id_country=6" title="Leagues in Slovakia"><?php echo get_flag(strtolower("svk"),26,19);?></a>
                <a href="games.html?id_country=2" title="Leagues in Sweden"><?php echo get_flag(strtolower("swe"),26,19);?></a>
                <a href="games.html?id_country=1" title="Leagues in Switzerland"><?php echo get_flag(strtolower("sui"),26,19);?></a>
                <a href="games.html?id_country=14" title="Leagues in USA"><?php echo get_flag(strtolower("usa"),26,19);?></a>
                <div class="clear">&nbsp;</div>
              </div>
         
         <?                       
        If (!empty($club_name))	{$FilterSearch=" AND 
          (lcase(home_team.name) LIKE lcase('%".$club_name."%')  or lcase(away_team.name) LIKE lcase('%".$club_name."%'))";
          $strHeaderGames.=" from ".$club_name;
          }
        If (!empty($id_league))	{
          $FilterCountry="";
          $FilterLeague=" AND id_league=".$id_league."";
          if (!empty($strHeaderGames)) $strHeaderGames.=', '; else $strHeaderGames.=' from ';
          $strHeaderLeagueName=$games->GetActualLeagueName($id_league,ActSeasonWeb);
          $strHeaderGames.='<a href="/league/'.get_url_text($strHeaderLeagueName,$id_league).'?season='.$id_season.'" title="'.$strHeaderLeagueName.'">'.$strHeaderLeagueName.'</a>';
          
          if (empty($id_season)) {
            $intSeasonStandings=ActSeasonWeb;
          }else{
            $intSeasonStandings=$id_season;
          }
          $isStandings=$con->GetSQLSingleResult("SELECT count(id) as item FROM standings WHERE id_season=".$intSeasonStandings." AND id_league=".$id_league."");
          if ($isStandings>0){
          
          $strStandingsLink='<div id="navigation"><div class="toright link bold" style="width:auto;float:none"><span class="link"><a href="/league/'.get_url_text($strHeaderLeagueName,$id_league).'?season='.$intSeasonStandings.'#standings" title="Show standings for '.$strHeaderLeagueName.'">Show standings for '.($intSeasonStandings-1).'-'.($intSeasonStandings).' '.$strHeaderLeagueName.'</a></span></div></div>';
          }
        }
        If (!empty($id_country))	{$FilterCountry=" AND ((SELECT count(*) FROM leagues_countries_items WHERE leagues_countries_items.id_league=games.id_league AND leagues_countries_items.id_country=".$id_country.")=1)";
          if (!empty($strHeaderGames)) $strHeaderGames.=', ';  else $strHeaderGames.=' from ';
          $strHeaderCountryName=$games->GetActualCountryName($id_country,ActSeasonWeb);
          $strHeaderGames.='<a href="/country/'.get_url_text($strHeaderCountryName,$id_country).'" title="'.$strHeaderCountryName.'">'.$strHeaderCountryName.'</a>';
        } 	
        
        If (!empty($id_season))	{$FilterSeason=" AND id_season=".$id_season;}
        If (!empty($id_club))	{$FilterClub=" AND (id_club_home=".$id_club." OR id_club_visiting=".$id_club.")";}
        If (!empty($date_from))	{$FilterDateFROM=" AND (date>='".date("Y-m-d",strtotime($date_from))."')";}
        If (!empty($date_to))	{$FilterDateTO=" AND (date<='".date("Y-m-d",strtotime($date_to))."')";}
         
         echo '<h2>'.$strHeaderGamesFront.' '.$strHeaderGames.'</h2>';
         echo $strStandingsLink;
         //$query="SELECT * FROM games WHERE 1 ".$FilterClub." ".$FilterSeason." ".$FilterSearch." ".$FilterCountry." ".$FilterLeague." ".$FilterDateFROM." ".$FilterDateTO." ORDER BY date ".$strOrderWhere.", time ".$strOrderWhere."";
         $query="SELECT games.id, games.date,games.time,games.id_club_home,games.id_club_visiting,games.id_league,games.id_season,games.id_stage,games.round,games.games_status
                 FROM games INNER JOIN clubs as home_team ON games.id_club_home = home_team.id INNER JOIN clubs as away_team ON games.id_club_visiting = away_team.id  
                 WHERE 1 ".$FilterClub." ".$FilterSeason." ".$FilterSearch." ".$FilterCountry." ".$FilterLeague." ".$FilterDateFROM." ".$FilterDateTO." ORDER BY date ".$strOrderWhere.", time ".$strOrderWhere."";   
         
         //echo $query; 
         //break;
         
         $StrLink="id_country=".$_GET['id_country']."&amp;id_league=".$_GET['id_league']."&amp;date_from=".$_GET['date_from']."&amp;date_to=".$_GET['date_to']."&amp;id_show=".$_GET['id_show']."&amp;id_season=".$_GET['id_season'] ; 
         $listovani = new listing($con,"?".$StrLink."&amp;",100,$query,3,"",$_GET['list_number']);
              $query=$listovani->updateQuery();
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '<table class="basic games_list">';
                  echo '
                  <thead>
                  <tr
                    <th class="" valign="top">Date</th>
                    <th class="" valign="top">League/stage/round</th>
                    <th class="" valign="top">Teams</th>
                    <th class="" valign="top">Score</th>
                    <th class="" valign="top">&nbsp;</th>
                  </tr>
                  </thead>
                  ';
                  
                  
                  $result = $con->SelectQuery($query);
                  $counter=0;
                  while($write = $result->fetch_array()){
                    $counter++;
                    if ($counter%2) $style=""; else $style="dark";
                      $details_query = "SELECT scorer FROM games_goals WHERE id_game=".$write['id']; 
                      
                      if($con->GetQueryNum($details_query)>0){$style .=" complete";}                    
                            
  		                echo '<tr class="'.$style.'">';
                      
                      echo '<td class="date" valign="top">'.date("M. d Y",strtotime($write['date']));
                      if ($write['time']<>"00:00:00") echo '<br /><small>'.date("H:i",strtotime($write['time'])).'</small>';
                      echo '</td>';
                      echo '<td valign="top">';
                      $CountryID=$con->GetSQLSingleResult("SELECT id_country as item FROM clubs WHERE id=".$write['id_club_home']);
                      if (!empty($CountryID)) {
                        echo get_flag(strtolower($CountryID),15,11);
                      }
                      $ItemNameLegue=$games->GetActualLeagueName($write['id_league'],$write['id_season']);
                      echo '<a href="/games,html?id_country='.$CountryID.'&amp;id_season='.$_GET['id_season'].'&amp;date_from='.$_GET['date_from'].'&amp;date_to='.$_GET['date_to'].'&amp;id_show='.$_GET['id_show'].'&amp;id_league='.get_url_text($ItemNameLegue,$write['id_league']).'" title="Show league games: '.$ItemNameLegue.'">'.$ItemNameLegue.'</a>';
                      $strStage=$con->GetSQLSingleResult("SELECT name as item FROM games_stages WHERE id=".$write['id_stage']);
                      echo '<br /><small>'.$strStage.'</small>';
                      if (!empty($write['round'])) echo '<small> | '.$write['round'].'</small>';
                      echo '</td>';
                      echo '<td valign="top" class="teams">';
                              $ItemName=$games->GetActualClubName($write['id_club_home'],$write['id_season']);
                              $GameName=$ItemName;        
                              echo '<a href="/games,html?id_country='.$CountryID.'&amp;id_season='.$_GET['id_season'].'&amp;date_from='.$_GET['date_from'].'&amp;date_to='.$_GET['date_to'].'&amp;id_show='.$_GET['id_show'].'&amp;id_club='.get_url_text($ItemName,$write['id_club_home']).'" title="Show club games: '.$ItemName.'">'.$ItemName.'</a>';
                              echo '&nbsp;-&nbsp;';
                              $ItemName=$games->GetActualClubName($write['id_club_visiting'],$write['id_season']);
                              $GameName.=" - ".$ItemName;
                              echo '<a href="/games,html?id_country='.$CountryID.'&amp;id_season='.$_GET['id_season'].'&amp;date_from='.$_GET['date_from'].'&amp;date_to='.$_GET['date_to'].'&amp;id_show='.$_GET['id_show'].'&amp;id_club='.get_url_text($ItemName,$write['id_club_visiting']).'" title="Show club games: '.$ItemName.'">'.$ItemName.'</a>';
                      echo '</td>';
                      echo '<td class="score center" valign="top"><a href="/game/detail/'.get_url_text($GameName,$write['id']).'" title="Show game details: '.$GameName.'">';
                      if ($write['games_status']==4){
                        echo "POST";
                      }else
                      {
                        echo $games->GetScore($write['id'],1);
                      }
                      echo '</a></span></td>';
                      echo '<td class="link" valign="top">
                        <span class="link"><a href="/game/detail/'.get_url_text($GameName,$write['id']).'" title="Show game details: '.$GameName.'">Show details&raquo;</a></span><br />
                      </td>';
                      
                    echo '</tr>';
                  }
                   echo '<tfoot>';
	                //listovani
                  $listovani->show_list();
                  echo '</tfoot>';
                  echo '</table>';
                 
              }else{
                 echo '<p class="center bold">No games found for specified criteria.</p>';
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
