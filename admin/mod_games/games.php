<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(31,1,0,0,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("js/functions.js");
echo $head->setJavascriptExtendFile("../inc/js/clubs_stats_box.js");
echo $head->setJavascriptExtendFile("../inc/js/clubs_box.js");

echo $head->setTitle(langGlogalTitle."Games");
?>
<script type="text/javascript">
$(document).ready(function(){
  
  jQuery.validator.messages.required = "";
  
  $("#form-01").validate({
		invalidHandler: function(e, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				var message = errors == 1
					? 'You missed <b>1 field</b>. It has been highlighted below'
					: 'You missed <b>' + errors + ' fields</b>..  They have been highlighted below';
				$("div.error span").html(message);
				$("div.error").show();
			} else {
				$("div.error").hide();
			}
		},
		onkeyup: false,
	});
  
});

</script>
<?php
echo $head->setEndHead();
$strAdminMenu="games";
?>
<body onload="javascript:logout('<?php echo $users->sesid ;?>', '<?php echo MaxTimeConnection ;?>')">

<div id="main">

	<!-- Tray -->
	<?php require_once("../inc/tray.inc");  ?>
  <!--  /tray -->
	<hr class="noscreen" />
	
	<!-- Columns -->
	<div id="cols" class="box">

		<!-- Aside (Left Column) -->
		<div id="aside" class="box">
      <?php require_once("../inc/menu.inc"); ?>
		</div> <!-- /aside -->
		<hr class="noscreen" />

		<!-- Content (Right Column) -->
		<div id="content" class="box" style="width:1200px">

    <!-- hlavni text -->
    
	  	<? 
	  	if (empty($_GET['filter'])){
	  	
	  	
      $id_league=$_GET['id'];
  if (empty($id_league)) $id_league=0; else $id_league=$id_league;
  if (empty($_GET['id_season'])) $id_season=ActSeason; else $id_season=$_GET['id_season'];
  
  if(!empty($ArSpecialRight[1])) $sqlRightWhereCountry=" AND (SELECT count(*) FROM leagues_countries_items WHERE (".str_replace("id_country","leagues_countries_items.id_country",$ArSpecialRight[1]).") AND leagues_countries_items.id_league=leagues.id)>0";
  if(!empty($ArSpecialRight[2])) $sqlRightWhereLeague=" AND ".str_replace("id_league","id",$ArSpecialRight[2]); else $sqlRightWhereLeague="";
  $query2="SELECT id FROM leagues WHERE id=".$id_league." ".$sqlRightWhereClub." ".$sqlRightWhereCountry." ".$sqlRightWhereLeague." LIMIT 1";
  //echo $query2;
  $result2 = $con->SelectQuery($query2);
  $write2 = $result2->fetch_array();
  $intLeagueClub=$con->GetQueryNum($query2);
  if ($intLeagueClub>0){
?>    
<h1>Games for league: <span style="color:#FFF5CC"><? echo $games->GetActualLeagueName($id_league,($id_season)); ?></span> and season <span style="color:#FFF5CC"><? echo ($id_season-1)."/".$id_season; ?></span></h1>
<?php
  }else{
?>
<h1>Games for league</span></h1>
<?php
  }

}else{
  echo '<h1>Recent games</span></h1>';
}  
?>

<p class="box">
       <?php 
       if (empty($_GET['filter'])){
       ?>
        <a href="games.php<?echo Odkaz;?>&amp;filter=1&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-info"><span>Show recent games</span></a>
       <?php }else{ ?>
        <a href="games.php<?echo Odkaz;?>&amp;filter=0&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-info"><span>Show games by league</span></a>
       <?php } ?>
    	 <?php if ($users->checkUserRight(2)){?><a href="games_add.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>&amp;id_season=<?echo $_GET['id_season'];?>&amp;id=<?echo $_GET['id'];?>"  class="btn-create"><span>Add new games</span></a><?php } ?>
    	 <a href="../mod_standings/standings.php<?echo Odkaz;?>"  class="btn-info"><span>Standings</span></a>
       <a href="../mod_clubs/clubs.php<?echo Odkaz;?>"  class="btn-info"><span>Clubs</span></a>
    	 <a href="../mod_leagues/leagues.php<?echo Odkaz;?>"  class="btn-info"><span>Leagues</span></a>
    	 
	  </p>
<?php	  
	  switch ($_GET['message']){
        case 1:
          echo '<p class="msg done"><b>'.$_GET['intCorrect'].'</b> games has been added. <a href="games_add.php'.Odkaz.'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'&amp;id_season='.$_GET['id_season'].'&amp;id='.$_GET['id_league'].'">Add another games</a> ?</p>';
          if (!empty($_GET['intError'])) echo '<p class="msg error"><b>'.$_GET['intError'].'</b> games could not be added.</p>';
          
        break;
        case 2:
          echo '<p class="msg done">game has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done"><b>'.$_GET['intCorrect'].'</b> games has been updated.</p>';
          if (!empty($_GET['intError'])) echo '<p class="msg error"><b>'.$_GET['intError'].'</b> games could not be updated.</p>';
        break;
        case 99:
           echo '<p class="msg error">wrong or missing input data.</p>';
        break;
      }
      
if (empty($_GET['filter'])){      
 ?>   
    <form action="<? echo $_SERVER["SCRIPT_NAME"]; ?>" name="filter_form" method="get">
    <fieldset>
	   <legend>First select season and league</legend>
	   <table class="nostyle">
		  <tr>
		  <td><span class="label label-05">selected&nbsp;season</span></td>
      <td>
       <select name="id_season" class="input-text required">
			  <option value="0">select season</option>
			  <?php
        for ($i=(ActSeason+1);$i>=1900;$i--) { 
	               echo '<option value="'.$i.'" '.write_select($id_season,$i).'>'.($i-1).'/'.$i.'</option>';
	      }
        ?>
        </select> 
       </td>
			 <td><span class="label label-05">selected&nbsp;league</span></td>
			 <td>
			   <div style="position:relative">
          <input type="text" name="filter_name" autocomplete="off" value="<?php echo $games->GetActualLeagueName($id_league,($id_season));; ?>" class="input-text required" onkeyup="send_livesearch_leagues_data(this.value,'<? echo $users->getSesid(); ?>',<? echo $users->getIdPageRight();?>)" />
          <div id="livesearch" style="z-index:99"></div>
         </div>
         
       </td>
       
     	 <td><input type="submit" class="input-submit" value="filter" /></td>
		  </tr>
	 </table>
	 <input type="hidden" name="order" value="<?echo $_GET['order'];?>" />
	 <input type="hidden" name="id" id="id_league_filter" value="<?echo $_GET['id'];?>" />
	 
	 <?echo OdkazForm;?>
  </fieldset>	
  </form>
<?}?>
  
<?php
if (!empty($id_league) or $_GET['filter']==1){
?>
<form action="<? echo $_SERVER["SCRIPT_NAME"]; ?>" method="get">
    <fieldset>
	   <legend>Game filter</legend>
	   <table class="nostyle">
		  <tr>
			 <td><span class="label label-05">filter&nbsp;type</span></td>
			  <td>
        <select name="filter2" class="input-text">
			  <option value="">show all recent games</option>
			  <option value="1" <?php if ($_GET['filter2']==1) echo 'selected="selected"'; ?>>show recent games - no score</option>
			  <option value="2" <?php if ($_GET['filter2']==2) echo 'selected="selected"'; ?>>all games</option>
			  </select>
        
       </td>
       <td>
       
       <table class="nostyle">
          <tr>
            <td rowspan="2"><span class="label label-05">date</span></td>
            <td><span class="label label-04">FROM:</span></td>
            <td>&nbsp;<input type="text" size="10" name="date_from" maxlength="10" class="input-text" id="date_from" value="<?php if (empty($_GET['date_from'])) echo "";else echo $_GET['date_from']; ?>" /></td>
          </tr>
          <tr>
            <td><span class="label label-04">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TO:</span></td>
            <td>&nbsp;<input type="text" size="10" name="date_to" maxlength="10" class="input-text" id="date_to" value="<?php if (empty($_GET['date_to'])) { if (!empty($_GET['date_to'])) {echo date("d.m.Y");}}else {echo $_GET['date_to'];} ?>" /></td>
          </tr>
        </table>
       </td>
       
        <td>
        
        <? show_club_select_box($con,1,$_GET['id_club'],"id_club");?>
        
       </td>
       
       <td><input type="submit" class="input-submit" value="filter" /></td>
		  </tr>
	 </table>
	 <input type="hidden" name="id_season" value="<?echo $_GET['id_season'];?>" />
   <input type="hidden" name="id" value="<?echo $_GET['id'];?>" />
   <input type="hidden" name="filter" value="<?echo $_GET['filter'];?>" />
	 <?echo OdkazForm;?>
  </fieldset>	
  </form>
  
    <div class="msg info error" style="display:none;">
      <span></span>.
  </div><br />


  
<?php    	  
echo '
<form action="games_action.php" method="post" id="form-01"  style="">
<table>
<tr>
';
      echo '<th>Date</th>';
      echo '<th>Time</th>';
      if ($_GET['filter']==1){
         echo '<th></th>';
      }
      echo '<th>Stage</th>';
      echo '<th>Round</th>';
      echo '<th>Home</th>';
      echo '<th>Visiting</th>';
      echo '<th>Score H</th>';
      echo '<th>Score V</th>';
      echo '<th>End of game</th>';
      echo '<th></th>';
echo '</tr>';

function ClubsSelect($id_league,$id_season,$id_selected){
    global $con,$games;

              $query="SELECT DISTINCT id_club as item FROM clubs_leagues_items WHERE id_club<>".$id_selected." AND id_league=".$id_league;
              //echo $query; 
	            //if ($con->GetQueryNum($query)>0){
              $result = $con->SelectQuery($query);
              $counter=0;
              while($write = $result->fetch_array()){
                $strClubArray[$counter][0]=$games->GetActualClubName($write['item'],$id_season); 
                $strClubArray[$counter][1]=$write['item'];
                $counter++;
              }
              $strClubArray[$counter+1][0]=$games->GetActualClubName($id_selected,$id_season);
              $strClubArray[$counter+1][1]=$id_selected;
              
              sort($strClubArray);
              foreach ($strClubArray as $key => $val) {
                 $strClubsSelect.='<option value="'.$val[1].'" '.write_select($val[1],$id_selected).'>'.$val[0].'</option>';
              }
              //}    
              
    $strClubsSelect.='</select>';
    return $strClubsSelect; 
}


if (empty($_GET['filter'])){
   $strWhere1=" AND id_league=".$id_league." and id_season=".$id_season."";
}

if (empty($_GET['filter2'])){
  $strWhere2=" AND date<=NOW()";
}else{

  switch ($_GET['filter2']){
    case 1:
      $strWhere2=" AND date<=NOW() AND (home_score is null OR visiting_score is null)";
    break;
    case 2:
      $strWhere2="";
    break;
    
  }
}

if (!empty($_GET['date_from'])){
  $strWhereDate1=" AND date>='".date("Y-m-d",strtotime($_GET['date_from']))."'";
}

if (!empty($_GET['date_to'])){
  $strWhereDate2=" AND date<='".date("Y-m-d",strtotime($_GET['date_to']))."'";
}

if (!empty($_GET['id_club'])){
  $strWhereClub=" AND (id_club_home=".$_GET['id_club']." OR	id_club_visiting=".$_GET['id_club'].")";
}


 
$query="SELECT * FROM games WHERE 1 ".$strWhere1." ".$strWhere2." ".$strWhereDate1." ".$strWhereDate2." ".$strWhereClub." ORDER BY date DESC, time DESC";
//echo $query; 
$listovani = new listing($con,$_SERVER["SCRIPT_NAME"].Odkaz."&amp;filter=".$_GET['filter']."&amp;filter2=".$_GET['filter2']."&amp;id_season=".$id_season."&amp;id=".$id_league."&amp;",50,$query,3,"",$_GET['list_number']);
    $query=$listovani->updateQuery();
    
    $result = $con->SelectQuery($query);
    $i = 0;
    $intPocet=$con->GetQueryNum($query);
    if ($intPocet>0){
    while($write = $result->fetch_array())
	   {
      
      if ($write['visiting_score']=="" or $write['home_score']==""){echo '<tr class="bg">';} else {echo '<tr>';}
         echo '<td style="white-space:nowrap">
         ';
         if ($users->checkUserRight(3)) echo'<a href="games_info.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Detail"><img src="../inc/design/ico-info.gif" class="ico" alt="Detail"></a>';
         echo '&nbsp;&nbsp;';
         echo '<input type="text" name="date['.$i.']" id="date'.$i.'" size="6" value="'.date("d.m.Y",strtotime($write['date'])).'" maxlength="10" class="input-text" onchange="get_required('.$i.')" /></td>';
         $strTime=date("H:i",strtotime($write['time']));
         if ($strTime=='00:00') $strTime="";
         echo '<td><input type="text" name="time['.$i.']" id="time'.$i.'" size="2" value="'.$strTime.'" maxlength="5" class="input-text" /></td>';
         if ($_GET['filter']==1){
         echo'<a href="games.php'.Odkaz.'&amp;filter=0&amp;id_season='.$write['id_season'].'&amp;id='.$write['id_league'].'" title="Show games from this season"><b>'.($write['id_season']-1).'/'.$write['id_season'].'</b>, '.$games->GetActualLeagueName($write['id_league'],$write['id_season']).'</a>
         ';
         echo ' 
         </td>';
         }
         echo'<td style="white-space:nowrap">';
          echo '<a onclick="return !window.open(this.href);" href="../../xml/games/games_'.$write['id_season'].'_'.$write["id_league"].'_'.$write['id_stage'].'.xml" title="Show XML"><img src="../inc/design/ico-list.gif" class="ico" alt="Info"></a>';
          echo '&nbsp;';
         echo '<select name="id_stage['.$i.']" id="id_stage'.$i.'" class="input-text" onchange="get_required('.$i.')">';   
              $query_sub="SELECT * FROM games_stages WHERE id_league=".$write['id_league']." ORDER by id ASC";
	            $result_sub = $con->SelectQuery($query_sub);
              while($write_sub = $result_sub->fetch_array()){
                echo '<option value="'.$write_sub['id'].'" '.write_select($write_sub['id'],$write['id_stage']).'>'.$write_sub['name'].'</option>';
              }
          echo '</select>';   
         echo '</td>';
         echo'<td><input type="text" name="round['.$i.']" id="round'.$i.'" size="5" maxlength="15" value="'.$write['round'].'" class="input-text" /></td>';
	       echo'<td style="white-space:nowrap">';
	        
	        echo'<div id="home_team_select'.$i.'">';
          echo '<select name="id_home_team['.$i.']" id="id_home_team'.$i.'" class="input-text" onchange="get_required('.$i.')" style="width:180px;">';   
          echo ClubsSelect($write['id_league'],$write['id_season'],$write['id_club_home']);
          echo'&nbsp;<a href="javascript:toggle_games(\'home\','.$i.',1);" title="Switch clubs"><img src="../inc/design/ico-settings.gif" class="ico" alt="Settings"></a>';
          echo'</div>';
          echo'<div style="display:none" id="home_team_ajax'.$i.'">';
                
                echo'<a href="javascript:toggle_games(\'home\','.$i.',2);" title="Switch clubs"><img src="../inc/design/ico-settings.gif" class="ico" style="float:right; display:block; widht:5px" alt="Settings"></a>';
                echo'<div style="float:left">';
                show_club_stats_select_box ($con,$games,$write['id'],$write['id_club_home'],$write['id_season'],"home_team_select_ajax[".$i."]");
                echo'</div>
                <br class="fix" />
                ';
                
                
          echo'</div>';
          echo '<input type="hidden" name="home_team_select_type['.$i.']" id="home_team_select_type'.$i.'" value="1" />';
              
         echo '</td>'; 
         
         echo'<td style="white-space:nowrap">';
	        
	        echo'<div id="visiting_team_select'.$i.'">';
          echo '<select name="id_visiting_team['.$i.']" id="id_visiting_team'.$i.'" class="input-text" onchange="get_required('.$i.')" style="width:180px;">';   
          echo ClubsSelect($write['id_league'],$write['id_season'],$write['id_club_visiting']);
          echo'&nbsp;<a href="javascript:toggle_games(\'visiting\','.$i.',1);" title="Switch clubs"><img src="../inc/design/ico-settings.gif" class="ico" alt="Settings"></a>';
          echo'</div>';
          echo'<div style="display:none" id="visiting_team_ajax'.$i.'">';
                
                echo'<a href="javascript:toggle_games(\'visiting\','.$i.',2);" title="Switch clubs"><img src="../inc/design/ico-settings.gif" class="ico" style="float:right; display:block; widht:5px" alt="Settings"></a>';
                echo'<div style="float:left">';
                show_club_stats_select_box ($con,$games,($write['id']*100),$write['id_club_visiting'],$write['id_season'],"visiting_team_select_ajax[".$i."]");
                echo'
                </div>
                <br class="fix" />
                
                ';
                
          echo'</div>';
          echo '<input type="hidden" name="visiting_team_select_type['.$i.']" id="visiting_team_select_type'.$i.'" value="1" />';
              
         echo '</td>'; 
              echo'
              <td><input type="text" name="home_score['.$i.']" size="1" maxlength="2" value="'.$write['home_score'].'" class="input-text" /></td>
              <td><input type="text" name="visiting_score['.$i.']" size="1" maxlength="2" value="'.$write['visiting_score'].'" class="input-text" /></td>
              ';
             echo'<td>';
	        echo '<select name="games_status['.$i.']" id="games_status'.$i.'" class="input-text" onchange="get_required('.$i.')">';   
              $query_sub="SELECT * FROM games_status_list ORDER by id";
              $result_sub = $con->SelectQuery($query_sub);
              while($write_sub = $result_sub->fetch_array()){
                echo '<option value="'.$write_sub['id'].'" '.write_select($write_sub['id'],$write['games_status']).'>'.$write_sub['name'].'</option>';
              }
          echo '</select>';   
         echo '</td>';
         echo '<td style="white-space:nowrap">';
         
          echo '<input type="hidden" name="id['.$i.']" value="'.$write['id'].'" />';
          echo '<input type="hidden" name="id_season['.$i.']" value="'.$write['id_season'].'" />';
          echo '<input type="hidden" name="id_league['.$i.']" value="'.$write['id_league'].'" />';
          
          if ($users->checkUserRight(3)) echo'<a href="games_info.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Detail"><img src="../inc/design/ico-info.gif" class="ico" alt="Detail"></a>';
          echo '&nbsp;&nbsp;';
          
          echo '<a onclick="return !window.open(this.href);" href="../../xml/games/detail/game_'.$write["id"].'.xml" title="Show XML"><img src="../inc/design/ico-list.gif" class="ico" alt="Info"></a>';
          echo '&nbsp;&nbsp;';
          
          $num_periods=$con->GetSQLSingleResult("SELECT count(*) as item FROM games_goals WHERE id_game=".$write['id']);
          $num_goals=$con->GetSQLSingleResult("SELECT count(*) as item FROM games_periods WHERE id_game=".$write['id']);
          $num_stats=$con->GetSQLSingleResult("SELECT count(*) as item FROM games_stats WHERE id_game=".$write['id']);
          
          if ($users->checkUserRight(4) and $num_periods==0 and $num_goals==0 and $num_stats==0){ 
          echo'<a href="javascript:delete_item(\''.$users->getSesid().'\','.$write["id"].',\''.$write["id"].': '.date("d.m.Y",strtotime($write['date'])).'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\','.$_GET['id'].','.$_GET['id_season'].')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
          }
         echo '</td>';
         
echo '</tr>
';

$i++;
	   }
	   
	   echo '<tfoot>';
	   //listovani
      $listovani->show_list();
     echo '</tfoot>';
	   
	  }else{
      echo '<tr><td colspan="10" class=""><p class="msg warning">No data found</p></td></tr>';
    }

echo'
</table>


        	<p class="box-01">
			     <input type="submit" value="Update games"  class="input-submit" />
			   </p>
  
        <input type="hidden" name="action" value="update" />
	      <input type="hidden" name="filter" value="'. $_GET['filter'].'" />
	      <input type="hidden" name="filter2" value="'. $_GET['filter2'].'" />
	      <input type="hidden" name="date_from" value="'. $_GET['date_from'].'" />
	      <input type="hidden" name="date_to" value="'. $_GET['date_to'].'" />
	      <input type="hidden" name="id_club" value="'. $_GET['id_club'].'" />
	      <input type="hidden" name="list_number" value="'. $_GET['list_number'].'" />
	      
	      <input type="hidden" name="number" value="'. $i.'" />
	      '. OdkazForm.'
	      </form>
';


?>
<!-- Datedit by Ivo Skalicky - ITPro CZ - http://www.itpro.cz -->
  <link rel="stylesheet" href="../inc/datedit/datedit.css" type="text/css" media="screen" />
  <script type="text/javascript" charset="iso-8859-1" src="../inc/datedit/datedit.js"></script>
  <script type="text/javascript" charset="utf-8" src="../inc/datedit/lang/cz.js"></script>
  <script type="text/javascript">
  <?php 
  for ($i=0;$i<$intPocet;$i++) {
  ?>
    datedit("date<?php echo $i ?>","d.m.yyyy");
  <?php } ?>
  datedit("date_from","d.m.yyyy");
  datedit("date_to","d.m.yyyy");
  </script>
<?php

	 

}else{

  echo '<p class="msg warning">No data found, please select league and season. Maybe you do not have necessary user rights.</p>';
}
?>
	  
	  
   <!-- /hlavni text -->
			

		</div> <!-- /content -->

	</div> <!-- /cols -->

	<hr class="noscreen" />

	<!-- Footer -->
	<?php require_once("../inc/footer.inc");  ?> 
  <!-- /footer -->

</div> <!-- /main -->

</body>

