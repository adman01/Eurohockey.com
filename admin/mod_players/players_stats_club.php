<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(28,1,0,0,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("js/functions.js");
echo $head->setJavascriptExtendFile("../inc/js/leagues_stats_box.js");
echo $head->setJavascriptExtendFile("../inc/js/players_stats_box.js");
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
	
	$("#form-02").validate({
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
  
  	$("#form-03").validate({
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

$(document).ready(function(){
		$(".tabs > ul").tabs();
		$.tablesorter.defaults.sortList = [[0,0]];
		$("table").tablesorter({
			headers: {
				1: { sorter: false }
			}
		});
	});
</script>
<?php
echo $head->setTitle(langGlogalTitle."Edit club statistic");
echo $head->setEndHead();
$strAdminMenu="players_stats";
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
		<div id="content" class="box" style="min-width:1000px">

    <!-- hlavni text -->

<?php
  
  $id_club=$_GET['id'];
  if (empty($id_club)) $id_club=0; else $id_club=$id_club;
  if (empty($_GET['id_season'])) $id_season=ActSeason; else $id_season=$_GET['id_season'];
  
  If(!empty($ArSpecialRight[4])) $sqlRightWhereCountry="AND ".str_replace("id_country","id_country",$ArSpecialRight[4]); else $sqlRightWhereCountry="";
  if(!empty($ArSpecialRight[2])) $sqlRightWhereLeague="  AND (SELECT count(*) FROM clubs_leagues_items WHERE clubs_leagues_items.id_club=clubs.id AND (".str_replace("clubs_leagues_items.id_league","id_league",$ArSpecialRight[2])."))>0"; else $sqlRightWhereLeague="";
  if(!empty($ArSpecialRight[3])) $sqlRightWhereClub=" AND ".str_replace("id_club","id",$ArSpecialRight[3]); else $sqlRightWhereClub="";
  $query2="SELECT id FROM clubs WHERE id=".$id_club." ".$sqlRightWhereClub." ".$sqlRightWhereCountry." ".$sqlRightWhereLeague." LIMIT 1";
  //echo $query2;
  $result2 = $con->SelectQuery($query2);
  $write2 = $result2->fetch_array();
  $intCountClub=$con->GetQueryNum($query2);
  if ($intCountClub>0){
?>    
<h1>Statistics for club: <span style="color:#FFF5CC"><? echo $games->GetActualClubName($id_club,($id_season)); ?></span> and season <span style="color:#FFF5CC"><? echo ($id_season-1)."/".$id_season; ?></span></h1>
<?php
  }else{
?>
<h1>Statistics for club</span></h1>
<?php
  }
?>  
    <p class="box">
       <a href="players_stats_club_all_times.php<?echo Odkaz;?>&amp;id=<?echo $id_club;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-info"><span>Statistic - ALL seasons and leagues</span></a>
       <?php if ($users->checkUserRight(2)){?><a href="players.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-info"><span>Players list</span></a><?php } ?>
       <a href="../mod_clubs/clubs.php<?echo Odkaz;?>"  class="btn-info"><span>Clubs list</span></a>
	  </p>
	  
	  <form action="<? echo $_SERVER["SCRIPT_NAME"]; ?>" name="filter_form" method="get">
    <fieldset>
	   <legend>Club filter</legend>
	   <table class="nostyle">
		  <tr>
			 <td><span class="label label-05">selected&nbsp;club</span></td>
			 <td>
			   <div style="position:relative">
          <input type="text" name="filter_name" autocomplete="off" value="<?php echo $games->GetActualClubName($id_club,($id_season));; ?>" class="input-text required" onkeyup="send_livesearch_clubs_data(this.value,'<? echo $users->getSesid(); ?>',<? echo $users->getIdPageRight();?>)" />
          <div id="livesearch" style="z-index:99"></div>
         </div>
         
       </td>
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
     	 <td><input type="submit" class="input-submit" value="filter" /></td>
		  </tr>
	 </table>
	 <input type="hidden" name="order" value="<?echo $_GET['order'];?>" />
	 <input type="hidden" name="id" id="id_club_filter" value="<?echo $_GET['id'];?>" />
	 
	 <?echo OdkazForm;?>
  </fieldset>	
  </form>
  
<?php
if ($intCountClub>0){

			  if (!empty($_GET['id_league'])) $id_league=$_GET['id_league']; else $id_league=0;
			  if(!empty($ArSpecialRight[2])) $sqlRightWhereLeague="AND ".str_replace("id_league","stats.id_league",$ArSpecialRight[2]); else $sqlRightWhereLeague="";
        $query="SELECT stats.id_league as id_league,leagues.name as name,leagues.youth_league_id,leagues.youth_league from stats INNER JOIN leagues ON stats.id_league=leagues.id  WHERE stats.id_club=".$id_club." AND stats.id_season=".$id_season." ".$sqlRightWhereLeague." GROUP BY stats.id_league ORDER by leagues.youth_league ASC";
        $result = $con->SelectQuery($query);
        $i=0;
        if ($con->GetQueryNum($query)>0){
?>
 
  <form action="<? echo $_SERVER["SCRIPT_NAME"]; ?>" name="filter_form2" id="form-02" method="get" style="z-index:50">
    <fieldset>
	   <legend>League filter</legend>
	   <table class="nostyle" style="z-index:50">
		  <tr>
			 <td><span class="label label-05">selected&nbsp;league</span></td>
			 <td>
       <select name="id_league" class="input-text required">
			  <?php
			  while($write = $result->fetch_array()){
        if (empty($id_league) and empty($i)) $id_league=$write['id_league'];
        if (!empty($write['youth_league'])){
          
          $query_sub="SELECT name from leagues_youth_list WHERE id=".$write['youth_league_id'];
          $result_sub = $con->SelectQuery($query_sub);
          $write_sub = $result_sub->fetch_array();
          $strYouthLeague=" (".$write_sub['name'].")";
        }else{
          $strYouthLeague="";
        }
        
        $query_count="SELECT id from stats WHERE stats.id_club=".$id_club." AND stats.id_season=".$id_season." AND stats.id_league=".$write['id_league'];
        $IntPocet=$con->GetQueryNum($query_count);
        echo '
        <option value="'.$write['id_league'].'" '.write_select($id_league,$write['id_league']).'>'.$write['name'].$strYouthLeague.' ('.$IntPocet.')</option>
        ';
        $i++;
        }
        ?>
        </select> 
        
       </td>
     	 <td><input type="submit" class="input-submit" value="filter" /></td>
		  </tr>
	 </table>
	 <input type="hidden" name="id" value="<?echo $id_club;?>" />
	 <input type="hidden" name="id_season" value="<?echo $id_season;?>" />
	 
	 <?echo OdkazForm;
   ?>
  </fieldset>	
  </form>
  
<?php
}
        
}
//pokud neni nalezena žádná liga pro klub
if ($i==0){
  echo '<p class="msg warning">League not found. Maybe you do not have necessary user right.</p>';
}else{
?>
  
	  
	  	<? 
	  	switch ($_GET['message']){
        case 1:
          echo '<p class="msg done">statistic has been added. Add new <a href="javascript:toggle_stats(\'new_player\',\'new_goalkeeper\');"><span>player statistic</span></a> or new <a href="javascript:toggle_stats(\'new_goalkeeper\',\'new_player\');"><span>goalkeeper statistic</span></a></p>';
        break;
        case 2:
          echo '<p class="msg done">statistic has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done"><b>'.$_GET['intCorrect'].'</b> statistics has been changed.</p>';
          if (!empty($_GET['intError'])) echo '<p class="msg error"><b>'.$_GET['intError'].'</b> statistics could not be changed.</p>';
        break;
        case 4:
          echo '<p class="msg done"><b>'.$_GET['intCorrect'].'</b> statistics has been copied.</p>';
          if (!empty($_GET['intError'])) echo '<p class="msg error"><b>'.$_GET['intError'].'</b> statistics could not be copied.</p>';
          echo '<p class="msg info">Show season <a href="players_stats_club.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;id_season='.$_GET['id_season_copied'].'&amp;id_league='.$_GET['id_league'].'">where statistic was copied</a>.</p>';
        break;
       case 5:
          echo '<p class="msg done">season has been locked.</p>';
        break;
       case 6:
          echo '<p class="msg done">season has been unlocked.</p>';
        break;
       case 98:
          echo '<p class="msg error">database error</p>';
        break;
        case 99:
           echo '<p class="msg error">wrong or missing input data.</p>';
        break;
      }
      ?>
      <div class="msg info error" style="display:none;">
      <span></span>.
    </div>
    <br />

<?php
if ($intCountClub>0){

if ($users->checkUserRight(2)) {


echo '
<a href="javascript:toggle_stats(\'new_player\',\'new_goalkeeper\');" class="btn-create" onclick="document.getElementById(\'new_coach\').style.display = \'none\'"><span>New player</span></a>
<a href="javascript:toggle_stats(\'new_goalkeeper\',\'new_player\');" class="btn-create" onclick="document.getElementById(\'new_coach\').style.display = \'none\'"><span>New goalkeeper</span></a>
<a href="javascript:toggle_stats(\'new_coach\',\'new_goalkeeper\');" class="btn-create" onclick="document.getElementById(\'new_player\').style.display = \'none\'"><span>New coach</span></a>
<a href="players_mass_update.php'.Odkaz.'&amp;id='.$id_club.'&amp;id_season='.$id_season.'&amp;id_league='.$id_league.'" class="btn-info"><span>Player mass update</span></a>
';
$query_lock="SELECT * FROM stats_lock WHERE id_club=0 AND id_season=".$id_season." AND id_league=".$id_league."";
if ($con->GetQueryNum($query_lock)==0){
  $query_lock="SELECT * FROM stats_lock WHERE id_club=".$id_club." AND id_season=".$id_season." AND id_league=".$id_league."";
  //echo $query_lock; 
  if ($con->GetQueryNum($query_lock)>0){
    echo '<a href="players_stats_action.php'.Odkaz.'&amp;action=lock&amp;is_lock=1&amp;id_club='.$id_club.'&amp;id_season='.$id_season.'&amp;id_league='.$id_league.'" class="btn-delete"><span>Unlock stats for season '.($id_season-1).'-'.$id_season.', '.$games->GetActualLeagueName($id_league,($id_season)).' and '.$games->GetActualClubName($id_club,($id_season)).'</span></a>';
  }else{
    echo '<a href="players_stats_action.php'.Odkaz.'&amp;action=lock&amp;is_lock=0&amp;id_club='.$id_club.'&amp;id_season='.$id_season.'&amp;id_league='.$id_league.'" class="btn-create"><span>Lock stats for season '.($id_season-1).'-'.$id_season.', '.$games->GetActualLeagueName($id_league,($id_season)).' and '.$games->GetActualClubName($id_club,($id_season)).'</span></a>';
  }
}else{
  echo '<a href="" class="btn-info"><span>All stats for for season '.($id_season-1).'-'.$id_season.' and league '.$games->GetActualLeagueName($id_league,($id_season)).' are LOCKED</span></a>';  
}

echo '
<div class="fix">&nbsp;</div>
<div class="smaller low" style="margin-top:10px">Locking disables the automatic counting statistics in the games module</div>';

echo '
<div class="fix" style="margin-bottom:10px">&nbsp;</div>

<div id="new_player" class=""'; 
if ($_GET['id_stats_type']!=1) echo 'style="display:none;"';
echo'>
<form action="players_stats_action.php" method="post" id="form-01"  style="">
<table>
<tr>
 
';
      echo '<th>Player</th>';
      echo '<th>League name</th>';
      echo '<th>Stage</th>';
      echo '<th>GP</th>';
      echo '<th>G</th>';
      echo '<th>A</th>';
      echo '<th>TM</th>';
      echo '<th>+/</th>';
    
echo '</tr>';
echo '<tr>';
        echo'<td>';
          show_player_stats_select_box($con,$games,0,$_GET['id_player'],"id_player");
         echo '</td>';
	       
	    echo'<td>';
        show_league_stats_select_box ($con,$games,1,$_GET['id_league'],ActSeason,"id_league",'id_type',1);
      echo '</td>';
      echo'<td>';
              
              
              echo'<div id="id_type_1">';  
              if (!empty($_GET['id_league'])){
               echo '<select name="id_type" class="input-text required">';   
               echo'<option value=""  class="">select stage</option>';    
	             $query="SELECT name,id FROM games_stages WHERE id_league=".$_GET['id_league']." ORDER by id";
                $result = $con->SelectQuery($query);
                while($write = $result->fetch_array()){
                  $id_stage=$write["id"]; 
	                $intIDstageDefault=$con->GetSQLSingleResult("SELECT id_detault as item FROM games_stages WHERE id=".$id_stage);
                  if (!empty($intIDstageDefault)) {$id_stage=$intIDstageDefault;}
                  echo '<option value="'.$id_stage.'" '.write_select($_GET['id_type'],$id_stage).'>'.$write['name'].'</option>';
                }
                echo '</select>';
              }
          echo '</div>';   
      echo '</td>';
              echo'
              <td><input type="text" name="games" size="2" value="'.$_GET['games'].'" /></td>
              <td><input type="text" name="goals" size="2" value="'.$_GET['goals'].'" /></td>
              <td><input type="text" name="assist" size="2" value="'.$_GET['assist'].'" /></td>
              <td><input type="text" name="penalty" size="2" value="'.$_GET['penalty'].'" /></td>
              <td><input type="text" name="plusminus" size="2" value="'.$_GET['plusminus'].'" /></td>';
    echo '</tr>
</table>

        	<p class="box-01">
			     <input type="submit" value="Add new player statistic"  class="input-submit" />
			   </p>
  
        <input type="hidden" name="action" value="add_stats_club" />
        <input type="hidden" name="id_stats_type" value="1" />
        <input type="hidden" name="filter" value="'. $_GET['filter'].'" />
	      <input type="hidden" name="id_season" value="'. $_GET['id_season'].'" />
	      <input type="hidden" name="filter2" value="'. $_GET['filter2'].'" />
	      <input type="hidden" name="list_number" value="'. $_GET['list_number'].'" />
	      <input type="hidden" name="id_club" value="'. $id_club.'" />
	      '. OdkazForm.'
	      </form>
</div>

<div id="new_goalkeeper" class=""'; 
if ($_GET['id_stats_type']!=2) echo 'style="display:none;"';
echo'>
<form action="players_stats_action.php" method="post" id="form-02"  style="">
<table>
<tr>
 
';
      echo '<th>Player</th>';
      echo '<th>League name</th>';
      echo '<th>Stage</th>';
      echo '<th>GP</th>';
      echo '<th>GP D</th>';
      echo '<th>MIN</th>';
      echo '<th>G</th>';
      echo '<th>A</th>';
      echo '<th>TM</th>';
      echo '<th>AVG</th>';
      echo '<th>PER</th>';
      echo '<th>SO</th>';
      
echo '</tr>';
echo '<tr>';
         echo'<td>';
          show_player_stats_select_box($con,$games,1,$_GET['id_player'],"id_player");
         echo '</td>';
	       
	    echo'<td>';
        show_league_stats_select_box ($con,$games,2,$_GET['id_league'],ActSeason,"id_league",'id_type',2);
      echo '</td>';
      echo'<td>';
              
              echo'<div id="id_type_2">';
              if (!empty($_GET['id_league'])){
               echo '<select name="id_type" class="input-text required">';   
               echo'<option value=""  class="">select stage</option>';    
	             $query="SELECT name,id FROM games_stages WHERE id_league=".$_GET['id_league']." ORDER by id";
                $result = $con->SelectQuery($query);
                while($write = $result->fetch_array()){
                  $id_stage=$write["id"]; 
	                $intIDstageDefault=$con->GetSQLSingleResult("SELECT id_detault as item FROM games_stages WHERE id=".$id_stage);
                  if (!empty($intIDstageDefault)) {$id_stage=$intIDstageDefault;}
                  echo '<option value="'.$id_stage.'" '.write_select($_GET['id_type'],$id_stage).'>'.$write['name'].'</option>';
                }
                echo '</select>';
              }
          echo '</div>';  
      echo '</td>';
              echo'<td><input type="text" name="games" size="2" value="'.$_GET['games'].'" /></td>';
              echo '<td><input type="text" name="games_dressed" size="2" value="'.$_GET['games_dressed'].'" /></td>';
              echo '<td><input type="text" name="minutes" size="2" value="'.$_GET['minutes'].'" /></td>';
              echo'
              <td><input type="text" name="goals" size="2" value="'.$_GET['goals'].'" /></td>
              <td><input type="text" name="assist" size="2" value="'.$_GET['assist'].'" /></td>
              <td><input type="text" name="penalty" size="2" value="'.$_GET['penalty'].'" /></td>
              ';
              echo'
              <td><input type="text" name="AVG" size="2" value="'.$_GET['AVG'].'" /></td>
              <td><input type="text" name="PCE" size="2" value="'.$_GET['PCE'].'" /></td>
              <td><input type="text" name="shotouts" size="2" value="'.$_GET['shotouts'].'" /></td>';
              
echo '</tr>
</table>

        	<p class="box-01">
			     <input type="submit" value="Add new goalkeeper statistic"  class="input-submit" />
			   </p>
  
        <input type="hidden" name="action" value="add_stats_club" />
        <input type="hidden" name="id_stats_type" value="2" />
        <input type="hidden" name="filter" value="'. $_GET['filter'].'" />
	      <input type="hidden" name="id_season" value="'. $_GET['id_season'].'" />
	      <input type="hidden" name="filter2" value="'. $_GET['filter2'].'" />
	      <input type="hidden" name="list_number" value="'. $_GET['list_number'].'" />
	      <input type="hidden" name="id_club" value="'. $id_club.'" />
	      '. OdkazForm.'
	      </form>

</div>

<div id="new_coach" class=""'; 
if ($_GET['id_stats_type']!=3) echo 'style="display:none;"';
echo'>
<form action="players_stats_action.php" method="post" id="form-03"  style="">
<table>
<tr>
 
';
      echo '<th>Player</th>';
      echo '<th>League name</th>';
      echo '<th>Stage</th>';
      echo '<th>Position</th>';
      echo '<th>League pos.</th>';
      echo '<th>W</th>';
      echo '<th>L</th>';
      echo '<th>T</th>';
      echo '<th>Level reached</th>';
      echo '<th>Hired</th>';
      echo '<th>Left</th>';
      echo '<th>Reason of leaving</th>';
    
echo '</tr>';
echo '<tr>';
         echo'<td>';
          show_player_stats_select_box($con,$games,2,$_GET['id_player'],"id_player");
         echo '</td>';
	       
	    echo'<td>';
        show_league_stats_select_box ($con,$games,3,$_GET['id_league'],ActSeason,"id_league",'id_type',3);
      echo '</td>';
      echo'<td>';
              
              echo'<div id="id_type_3">';
              if (!empty($_GET['id_league'])){
               echo '<select name="id_type" class="input-text required">';   
               echo'<option value=""  class="">select stage</option>';    
	             $query="SELECT name,id FROM games_stages WHERE id_league=".$_GET['id_league']." ORDER by id";
                $result = $con->SelectQuery($query);
                while($write = $result->fetch_array()){
                  $id_stage=$write["id"]; 
	                $intIDstageDefault=$con->GetSQLSingleResult("SELECT id_detault as item FROM games_stages WHERE id=".$id_stage);
                  if (!empty($intIDstageDefault)) {$id_stage=$intIDstageDefault;}
                  echo '<option value="'.$id_stage.'" '.write_select($_GET['id_type'],$id_stage).'>'.$write['name'].'</option>';
                }
                echo '</select>';
              }
          echo '</div>';  
      echo '</td>';
      echo '<td>';
          echo '<select name="id_coach_position" class="input-text required">';   
           echo'<option value=""  class="">select position</option>';    
	            $query="SELECT * FROM players_coach_positions_list ORDER by id";
        $result = $con->SelectQuery($query);
        while($write = $result->fetch_array()){
        echo '
        <option value="'.$write['id'].'" '.write_select($write['id'],$_GET['id_coach_position']).'>'.$write['name'].'</option>
        ';
        }
        echo '</select>';   
      echo '</td>';
              
              echo'
              <td valign="top"><input type="text" name="league_position" size="2" value="'.$_GET['league_position'].'" /><br /><span class="smaller low">number</span></td>
              <td valign="top"><input type="text" name="wins" size="2" value="'.$_GET['wins'].'" /><br /><span class="smaller low">number</span></td>
              <td valign="top"><input type="text" name="losts" size="2" value="'.$_GET['losts'].'" /><br /><span class="smaller low">number</span></td>
              <td valign="top"><input type="text" name="draws" size="2" value="'.$_GET['draws'].'" /><br /><span class="smaller low">number</span></td>
              <td valign="top"><input type="text" name="coach_level_reached" size="10" maxlength="50" value="'.$_GET['coach_level_reached'].'" /><br /><span class="smaller low">e.g. semi-final</span></td>
              <td valign="top"><input type="text" name="coach_hired" size="8" maxlength="50" value="'.$_GET['coach_hired'].'" /><br /><span class="smaller low">dd.mm.yyyy</span></td>
              <td valign="top"><input type="text" name="coach_left" size="8" maxlength="50" value="'.$_GET['coach_left'].'" /><br /><span class="smaller low">dd.mm.yyyy</span></td>
              <td valign="top"><input type="text" name="reason_of_leaving" size="10" maxlength="50" value="'.$_GET['reason_of_leaving'].'" /><br /><span class="smaller low">e.g. fired</span></td>
              ';
echo '</tr>
</table>

        	<p class="box-01">
			     <input type="submit" value="Add new coach statistic"  class="input-submit" />
			   </p>
  
        <input type="hidden" name="action" value="add_stats_club" />
        <input type="hidden" name="id_stats_type" value="3" />
        <input type="hidden" name="is_coach" value="1" />
	      <input type="hidden" name="filter" value="'. $_GET['filter'].'" />
	      <input type="hidden" name="id_season" value="'. $_GET['id_season'].'" />
	      <input type="hidden" name="filter2" value="'. $_GET['filter2'].'" />
	      <input type="hidden" name="list_number" value="'. $_GET['list_number'].'" />
	      <input type="hidden" name="id_club" value="'. $id_club.'" />
	      '. OdkazForm.'
	      </form>

</div>
';


}





function show_stats($con,$games,$users,$id_league,$id_club,$id_season,$type,$type_name){
 
 echo '
    <form action="players_stats_action.php" id="form_stats'.$type.'" method="post">
    <table style=" z-index:50">
    ';
 
 $strClubName=$games->GetActualClubName($id_club,($id_season));
 $a = 1;
 for ($counter = 1; $counter <= 3; $counter ++) {
    
    switch ($counter){
      //coach
      case 1:
        $query_stats="SELECT *,stats.id as id from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id_league." AND id_club=".$id_club." AND id_season_type=".$type."  AND id_season=".$id_season." AND id_stats_type=2 ORDER BY players.surname ASC";
      break;
      //goalie
      case 2:
        $query_stats="SELECT *,stats.id as id from stats INNER JOIN players ON stats.id_player=players.id WHERE players.id_position=1 AND id_league=".$id_league." AND id_club=".$id_club." AND id_season_type=".$type."  AND id_season=".$id_season." AND id_stats_type=1 ORDER BY players.surname ASC";
      break;
      //player
      case 3:
        $query_stats="SELECT *,stats.id as id from stats INNER JOIN players ON stats.id_player=players.id WHERE players.id_position<>1 AND id_league=".$id_league." AND id_club=".$id_club." AND id_season_type=".$type."  AND id_season=".$id_season." AND id_stats_type=1 ORDER BY (stats.goals+stats.assist) DESC,players.surname ASC";
      break;
    }
    
    //echo $query_stats; 
    $result_stats = $con->SelectQuery($query_stats);
    
    $boolIsStats=false;
    if ($con->GetQueryNum($query_stats)>0){
    
    $boolIsStats=true;
    switch ($counter){
      //coach
      case 1:
        $strPostName="Coaches from club  <span style=\"color:#0085CC\">".$strClubName."</span> for <span style=\"color:#0085CC\">".$type_name."</span>  <span style=\"color:#0085CC\">".($id_season-1).'/'.$id_season."</span>";
      break;
      //goalie
      case 2:
        $strPostName="Goalkeepers from club <span style=\"color:#0085CC\">".$strClubName."</span> for <span style=\"color:#0085CC\">".$type_name."</span>  <span style=\"color:#0085CC\">".($id_season-1).'/'.$id_season."</span>";
      break;
      //player
      case 3:
        $strPostName="Players from club  <span style=\"color:#0085CC\">".$strClubName."</span> for <span style=\"color:#0085CC\">".$type_name."</span>  <span style=\"color:#0085CC\">".($id_season-1).'/'.$id_season."</span>";
      break;
    }
    
    
    echo'
      <tr><td colspan="18" class="high-bg"><b style="font-size:15px">'.$strPostName.'</b></td></tr>
      <tr>
    ';   
      echo '<th>[x]</th>'; 
      echo '<th>Player</th>';
      echo '<th>League name</th>';
      echo '<th>Stage</th>';
      
    switch ($counter){
      //coach
      case 1:
        
        echo '<th colspan="3">Position</th>';
        echo '<th>LPOS</th>';
        echo '<th>W</th>';
        echo '<th>L</th>';
        echo '<th>T</th>';
        echo '<th colspan="2">LVL reached</th>';
        echo '<th colspan="2">Hired</th>';
        echo '<th>Left</th>';
        echo '<th>Reason L </th>';
        echo '<th>&nbsp;</th>';
        
      break;
      //goalie
      case 2:
        echo '<th>GP</th>';
        echo '<th>GP D</th>';
        echo '<th>MIN</th>';
        echo '<th>G</th>';
        echo '<th>A</th>';
        echo '<th>PTS</th>';
        echo '<th>TM</th>';
        echo '<th>AVG</th>';
        echo '<th>PER</th>';
        echo '<th>SO</th>';
        echo '<th colspan="3">&nbsp;</th>';
        echo '<th>&nbsp;</th>';
      break;
      //player
      case 3:
        echo '<th>GP</th>';
        echo '<th>G</th>';
        echo '<th>A</th>';
        echo '<th>PTS</th>';
        echo '<th>TM</th>';
        echo '<th>+/</th>';
        echo '<th colspan="7">&nbsp;</th>';
        echo '<th>&nbsp;</th>';
      break;
    }
      
      
      echo '</tr>';
    
    while($write_stats = $result_stats->fetch_array())
	   {
	     $post=$write_stats['id_position'];
	     
       
	     if ($a%2) $style=' class="bg"'; else $style="";
	     echo '<tr'.$style.'>';
	       echo '<td class="t-center"><input type="checkbox" id="checkbox" name="id['.$a.']" value="'.$write_stats['id'].'" /></td>';
	       echo'<td>';
          show_player_stats_select_box($con,$games,$write_stats['id'],$write_stats['id_player'],"id_player[".$a."]");
         echo '</td>';
	        echo'<td style="width:200px;">';
	           show_league_stats_select_box ($con,$games,$write_stats['id'],$write_stats['id_league'],$id_season,"id_league[".$a."]","id_type[".$a."]",$write_stats['id']);
	        echo '</td>';
          echo'<td>';
               
	          echo'<div id="id_type['.$a.']_'.$write_stats['id'].'">';
            if (!empty($write_stats['id_league'])){
               echo '<select name="id_type['.$a.']" class="input-text required">';   
               echo'<option value=""  class="">select stage</option>';    
	             $query="SELECT name,id FROM games_stages WHERE id_league=".$write_stats['id_league']." ORDER by id";
                $result = $con->SelectQuery($query);
                while($write = $result->fetch_array()){
                  $id_stage=$write["id"]; 
	                $intIDstageDefault=$con->GetSQLSingleResult("SELECT id_detault as item FROM games_stages WHERE id=".$id_stage);
                  if (!empty($intIDstageDefault)) {$id_stage=$intIDstageDefault;}
                  echo '<option value="'.$id_stage.'" '.write_select($write_stats['id_season_type'],$id_stage).'>'.$write['name'].'</option>';
                }
                echo '</select>';
              }
        
          echo '</div>';   
          echo '</td>';
          
          switch ($counter){
      //coach
      case 1:
      
                  echo'<td colspan="3"><select name="id_coach_position['.$a.']" class="input-text">';   
           echo'<option value=""  class="">select position</option>';    
	            $query="SELECT * FROM players_coach_positions_list ORDER by id";
        $result = $con->SelectQuery($query);
        while($write = $result->fetch_array()){
        echo '
        <option value="'.$write['id'].'" '.write_select($write['id'],$write_stats['penalty']).'>'.$write['name'].'</option>
        ';
        }
        echo '</select>';   
      echo '</td>';
      
              echo'<td><input type="text" name="league_position['.$a.']" size="2" value="'.$write_stats['minutes'].'" /></td>';
              echo '<td><input type="text" name="wins['.$a.']" size="2" value="'.$write_stats['wins'].'" /></td>';
              $coach_hired=$write_stats['coach_hired'];
              if (!empty($coach_hired)){
                $coach_hired=explode("-",$coach_hired);
                $coach_hired=$coach_hired[2].'.'.$coach_hired[1].'.'.$coach_hired[0];
              }
              $coach_left=$write_stats['coach_left'];
              if (!empty($coach_left)){
                $coach_left=explode("-",$coach_left);
                $coach_left=$coach_left[2].'.'.$coach_left[1].'.'.$coach_left[0];
              }
              
              
              
              echo'
              <td><input type="text" name="losts['.$a.']" size="2" value="'.$write_stats['losts'].'" /></td>
              <td><input type="text" name="draws['.$a.']" size="2" value="'.$write_stats['draws'].'" /></td>
              <td colspan="2"><input type="text" name="coach_level_reached['.$a.']" size="10" maxlength="50" value="'.$write_stats['coach_level_reached'].'" /></td>
              <td colspan="2"><input type="text" name="coach_hired['.$a.']" size="8" maxlength="50" value="'.$coach_hired.'" /></td>
              <td><input type="text" name="coach_left['.$a.']" size="8" maxlength="50" value="'.$coach_left.'" /></td>
              <td><input type="text" name="reason_of_leaving['.$a.']" size="10" maxlength="50" value="'.$write_stats['reason_of_leaving'].'" /></td>';
              
               echo'<td>
          <input type="hidden" name="id_stats['.$a.']" value="'.$write_stats['id'].'" />
          <input type="hidden" name="is_coach['.$a.']" value="1" />
          ';
          if ($users->checkUserRight(4)) echo'<a href="javascript:delete_club_stat(\''.$users->getSesid().'\','.$write_stats["id_club"].','.$write_stats["id_league"].',\''.($write_stats['id_season']-1).'/'.$write_stats['id_season'].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\',\''.$write_stats['id'].'\',\''.$write_stats['id_season'].'\',0)" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
        echo'</td>';
        
        
        
      break;
      //goalie
      case 2:
        echo'<td><input type="text" name="games['.$a.']" size="2" value="'.$write_stats['games'].'" /></td>';
        echo'<td><input type="text" name="games_dressed['.$a.']" size="2" value="'.$write_stats['games_dressed'].'" /></td>';
        echo '<td><input type="text" name="minutes['.$a.']" size="2" value="'.$write_stats['minutes'].'" /></td>';
        echo'
          <td><input type="text" name="goals['.$a.']" size="2" value="'.$write_stats['goals'].'" /></td>
          <td><input type="text" name="assist['.$a.']" size="2" value="'.$write_stats['assist'].'" /></td>
          <td class="t-center"><b>'.($write_stats['goals']+$write_stats['assist']).'</b></td>
          <td><input type="text" name="penalty['.$a.']" size="2" value="'.$write_stats['penalty'].'" /></td>
        ';
        echo'
          <td><input type="text" name="AVG['.$a.']" size="3" value="'.$write_stats['AVG'].'" /></td>
          <td><input type="text" name="PCE['.$a.']" size="3" value="'.$write_stats['PCE'].'" /></td>
          <td><input type="text" name="shotouts['.$a.']" size="2" value="'.$write_stats['shotouts'].'" /></td>
          ';
         echo '<td colspan="3">&nbsp;</td>';

          echo '<td>';
            echo'<input type="hidden" name="id_stats['.$a.']" value="'.$write_stats['id'].'" />';
            echo '<input type="hidden" name="plusminus['.$a.']" value="'.$write_stats['plusminus'].'" />
            <input type="hidden" name="is_coach['.$a.']" value="0" />
            ';
            
            if ($users->checkUserRight(4)) echo'<a href="javascript:delete_club_stat(\''.$users->getSesid().'\','.$write_stats["id_club"].','.$write_stats["id_league"].',\''.($write_stats['id_season']-1).'/'.$write_stats['id_season'].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\',\''.$write_stats['id'].'\',\''.$write_stats['id_season'].'\',0)" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
          echo'</td>';
      break;
      
      //player
      case 3:
        echo'<td><input type="text" name="games['.$a.']" size="2" value="'.$write_stats['games'].'" /></td>';
        echo'
          <td><input type="text" name="goals['.$a.']" size="2" value="'.$write_stats['goals'].'" /></td>
          <td><input type="text" name="assist['.$a.']" size="2" value="'.$write_stats['assist'].'" /></td>
          <td class="t-center"><b>'.($write_stats['goals']+$write_stats['assist']).'</b></td>
          <td><input type="text" name="penalty['.$a.']" size="2" value="'.$write_stats['penalty'].'" /></td>
        ';
        echo '<td><input type="text" name="plusminus['.$a.']" size="2" value="'.$write_stats['plusminus'].'" /></td>';
        echo '<td colspan="7">&nbsp;</td>';
        echo '<td>';
            echo'<input type="hidden" name="id_stats['.$a.']" value="'.$write_stats['id'].'" />
            <input type="hidden" name="is_coach['.$a.']" value="0" />
            ';
            if ($users->checkUserRight(4)) echo'<a href="javascript:delete_club_stat(\''.$users->getSesid().'\','.$write_stats["id_club"].','.$write_stats["id_league"].',\''.($write_stats['id_season']-1).'/'.$write_stats['id_season'].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\',\''.$write_stats['id'].'\',\''.$write_stats['id_season'].'\',0)" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
          echo'</td>';
      break;
    }
    
        echo "</tr>\n";
        $a++;
        
  }
     
      }else{
          
          
           switch ($counter){
          //coach
          case 1:
            echo '<tr><td colspan="18"><p class="msg warning">Data not found for coaches and '.$type_name.' '.($id_season-1).'/'.$id_season.'</p></td></tr>';
          break;
          //goalie
          case 2:
            echo '<tr><td colspan="18"><p class="msg warning">Data not found for goalkeepers and '.$type_name.' '.($id_season-1).'/'.$id_season.'</p></td></tr>';
          break;
          //player
          case 3:
            echo '<tr><td colspan="18"><p class="msg warning">Data not found for players and '.$type_name.' '.($id_season-1).'/'.$id_season.'</p></td></tr>';
          break;
          }
      }
  
  }
  
  if ($boolIsStats){
  echo '
    <tfoot>
  	         <tr class="high-bg">
              <td class="t-center">
              <input type="checkbox" id="check_all" name="check_all" onclick="CheckAll(this.form,\'checkbox\');" />
              </td>
					    <td colspan="18" class="arrow-01" valign="top">
					    <table class="nostyle">
					    <tr>
					    <td>
					    <b>copy</b> selected players stats to <b>season:</b>   
						  </td>
						  <td>
               <select name="id_season_copy"  class="input-text">';
			           for ($i=(ActSeason+1);$i>=1900;$i--) { 
	                 echo '<option value="'.$i.'" '.write_select(($id_season+1),$i).'>'.($i-1).'/'.$i.'</option>';
	               }
              echo '</select> ';
              echo ' and <b>league:</b></td>
              <td>';
              show_league_stats_select_box ($con,$games,$id_season.$type,$id_league,ActSeason,"id_league_copy","id_type_copy",$id_season.$type);
              echo '
              </td>
              <td>
              and ';
              
              echo'<span id="id_type_copy_'.$id_season.$type.'">';                  
	            if (!empty($id_league)){
               echo '<select name="id_type_copy" class="input-text required">';   
               echo'<option value=""  class="">select stage</option>';    
	             $query="SELECT name,id FROM games_stages WHERE id_league=".$id_league." ORDER by id";
                $result = $con->SelectQuery($query);
                while($write = $result->fetch_array()){
                  $id_stage=$write["id"]; 
	                $intIDstageDefault=$con->GetSQLSingleResult("SELECT id_detault as item FROM games_stages WHERE id=".$id_stage);
                  if (!empty($intIDstageDefault)) {$id_stage=$intIDstageDefault;}
                  echo '<option value="'.$id_stage.'" '.write_select($type,$id_stage).'>'.$write['name'].'</option>';
                }
                echo '</select>';
              }
               echo '</span>';
              
              echo'
              </td>
              <td> 
              <input type="button" value="copy statistic" onclick="send_form_copy_stats(\''.$type.'\',this.form,\'checkbox\');"  class="input-text" />
              
              </td>
              </tr>
              
              </table>
              </td>
					</tr>
  	</tfoot>
  	';
    }
  	echo '
  	</table>
  	';
    if ($boolIsStats){  
  	echo'
  	<p class="box-01">
			     <input type="submit" value="Update statistics"  class="input-submit" />
		</p>
  
        <input type="hidden" name="action" id="action_input'.$type.'" value="update_stats_club" />
	      <input type="hidden" name="id_club" value="'. $id_club.'" />
	      <input type="hidden" name="id_league_select" value="'. $id_league.'" />
	      <input type="hidden" name="id_season" value="'. $id_season.'" />
	      <input type="hidden" name="number" id="number" value="'. $a.'" />
	      <input type="hidden" name="filter" value="'. $_GET['filter'].'" />
	      <input type="hidden" name="filter2" value="'. $_GET['filter2'].'" />
	      <input type="hidden" name="list_number" value="'. $_GET['list_number'].'" />
	      '. OdkazForm.'
	  ';
	  }
	echo'
	</form>
  ';
  
  
  }
  
  $query_sub="SELECT * FROM stats_types_list ORDER BY id ASC";
	if ($con->GetQueryNum($query_sub)>0){
	   $result_sub = $con->SelectQuery($query_sub);
	   while($write_sub = $result_sub->fetch_array()){
	     echo '<h2>'.$write_sub['name'].'</h2>';
       show_stats($con,$games,$users,$id_league,$id_club,$id_season,$write_sub['id'],$write_sub['name']);     
	  }
	}
	
	$query_sub="SELECT DISTINCT id_season_type FROM stats WHERE id_season_type>3 AND id_league=".$id_league." AND id_season=".$id_season." AND  id_club=".$id_club;
	if ($con->GetQueryNum($query_sub)>0){
	   $result_sub = $con->SelectQuery($query_sub);
	   while($write_sub = $result_sub->fetch_array()){
	     
	     $strStageName=$con->GetSQLSingleResult("SELECT name as item FROM games_stages WHERE id=".$write_sub['id_season_type']);
	     echo '<h2>'.$strStageName.'</h2>';
       show_stats($con,$games,$users,$id_league,$id_club,$id_season,$write_sub['id_season_type'],$strStageName);     
	  }
	}

}else{
  echo '<p class="msg warning">No data found, please select club and season. Maybe you have not necessary user rights.</p>';
}
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
