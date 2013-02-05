<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(26,1,0,0,0);
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
	
		
});

</script>
<?php
echo $head->setTitle(langGlogalTitle."Edit player´s profiles");
echo $head->setEndHead();
$strAdminMenu="players";
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
		<div id="content" class="box">

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
<h1>Players for club: <span style="color:#FFF5CC"><? echo $games->GetActualClubName($id_club,($id_season)); ?></span> and season <span style="color:#FFF5CC"><? echo ($id_season-1)."/".$id_season; ?></span></h1>
<?php
  }else{
?>
<h1>Edit player´s profiles</span></h1>
<?php
  }
?>  
    <p class="box">
       <a href="players.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of players</span></a>
	  </p>
	  
	  <form action="<? echo $_SERVER["SCRIPT_NAME"]; ?>" name="filter_form" id="form-01" method="get">
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
          
          $query_sub="SELECT name from leagues_youth_list WHERE id=".$write['youth_league'];
          $result_sub = $con->SelectQuery($query_sub);
          $write_sub = $result_sub->fetch_array();
          $strYouthLeague=" (".$write_sub['name'].")";
        }else{
          $strYouthLeague="";
        }
        
        $query_count="SELECT DISTINCT id_player from stats WHERE stats.id_club=".$id_club." AND stats.id_season=".$id_season." AND stats.id_league=".$write['id_league'];
        $IntPocet=$con->GetQueryNum($query_count);
        echo '
        <option value="'.$write['id_league'].'" '.write_select($id_league,$write['id_league']).'>'.$write['name'].$strYouthLeague.' ('.$IntPocet.')</option>
        ';
        $i++;
        }
        ?>
        </select> 
        
       </td>
     	 <td><input type="submit" class="input-submit" value="filtrate" /></td>
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
  echo '<p class="msg warning">League not found. Maybe you have not necessary user rights.</p>';
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
          echo '<p class="msg done"><b>'.$_GET['intCorrect'].'</b> players has been changed.</p>';
          if (!empty($_GET['intError'])) echo '<p class="msg error"><b>'.$_GET['intError'].'</b> players could not be changed.</p>';
        break;
        case 4:
          echo '<p class="msg done"><b>'.$_GET['intCorrect'].'</b> statistics has been copied.</p>';
          if (!empty($_GET['intError'])) echo '<p class="msg error"><b>'.$_GET['intError'].'</b> statistics could not be copied.</p>';
          echo '<p class="msg info">Show season <a href="players_stats_club.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;id_season='.$_GET['id_season_copied'].'&amp;id_league='.$_GET['id_league'].'">where statistic was copied</a>.</p>';
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

<?php
if ($intCountClub>0){


 echo '
    <form action="players_action.php" id="form_stats'.$type.'" method="post">
    <table style=" z-index:50">
    ';
 
 $strClubName=$games->GetActualClubName($id_club,($id_season));
 $a = 1;
    
    $query_stats="SELECT 
      DISTINCT players.id as id,players.name,players.surname,players.nationality,players.name_cyrrilic,birth_date,birth_place,weight,height,id_shoot,id_position
      FROM stats  INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id_league." AND id_club=".$id_club." AND id_season=".$id_season." ORDER BY players.surname ASC";
    $result_stats = $con->SelectQuery($query_stats);
    
    if ($con->GetQueryNum($query_stats)>0){
    
    $strPostName="Players from club  <span style=\"color:#0085CC\">".$strClubName."</span> for <span style=\"color:#0085CC\">".$type_name."</span>  <span style=\"color:#0085CC\">".($id_season-1).'/'.$id_season."</span>";
    echo'<tr>';
      echo '<th>Player</th>';
      echo '<th>Cyrillic name</th>';
      echo '<th>Nationality</th>';
      echo '<th>Date of birth<br /><span class="smaller">e.g. 09.04.1982</span></th>';
      echo '<th>Place of birth<br /><span class="smaller">City, Region/state, Country</span></th>';
      echo '<th>Height</th>';
      echo '<th>Weight</th>';
      echo '<th>Position</th>';
      echo '<th>Shots</th>';
      echo '</tr>';
    
    while($write_stats = $result_stats->fetch_array())
	   {
	     
       if ($a%2) $style=' class="bg"'; else $style="";
	     echo '<tr'.$style.'>';
	       echo'<td><a class="ico-show" href="players.php'.Odkaz.'&amp;filter='.$write_stats["id"].'" title="Show item">'.$write_stats['surname'].' '.$write_stats['name'].'</a></td>';
         echo'<td><input type="text" name="name_cyrrilic['.$a.']" size="20" maxlength="50" value="'.$write_stats['name_cyrrilic'].'" /></td>';
	       echo'<td>';
           echo '<select name="nationality['.$a.']" id="nationality['.$a.']" class="input-text required">';   
           echo'<option value="N/A">Unknown</option>';    
	            $query="SELECT * FROM countries ORDER by shortcut ASC";
              $result = $con->SelectQuery($query);
              while($write = $result->fetch_array()){
                echo '<option value="'.$write['shortcut'].'" '.write_select($write_stats['nationality'],$write['shortcut']).'>'.$write['shortcut'].' ('.$games->GetActualCountryName($write['id'],ActSeason).')</option>';
              }
            echo '</select>';   
         echo '</td>';
         
         
         if (!empty($write_stats['birth_date'])){
          $strDate=explode("-",$write_stats['birth_date']);
          $strDate=$strDate[2].'.'.$strDate[1].'.'.$strDate[0];
         }else{
          $strDate="";
         }
         
         echo'<td><input type="text" name="birth_date['.$a.']" size="8"  maxlength="10" value="'.$strDate.'" /></td>';
	       echo'<td><input type="text" name="birth_place['.$a.']" size="15" maxlength="50" value="'.$write_stats['birth_place'].'" /></td>';
	       echo'<td><input type="text" name="height['.$a.']" size="2" maxlength="3" value="'.$write_stats['height'].'" />cm</td>';
	       echo'<td><input type="text" name="weight['.$a.']" size="2" maxlength="3" value="'.$write_stats['weight'].'" />kg</td>';
	       echo'<td>';
           echo '<select name="id_position['.$a.']" id="id_position['.$a.']" class="input-text required">
            <option value="">select position</option>
           ';   
              $query="SELECT * FROM players_positions_list ORDER by id";
              $result = $con->SelectQuery($query);
              while($write = $result->fetch_array()){
                echo '<option value="'.$write['id'].'" '.write_select($write_stats['id_position'],$write['id']).'>'.$write['name'].'</option>';
              }
            echo '</select>';   
         echo '</td>';
         echo'<td>';
           echo '<select name="id_shoot['.$a.']" id="id_shoot['.$a.']" class="input-text required">';   
           echo' <option value="">select side</option>';    
	            $query="SELECT * FROM players_shoots_list ORDER by id";
              $result = $con->SelectQuery($query);
              while($write = $result->fetch_array()){
                echo '<option value="'.$write['id'].'" '.write_select($write_stats['id_shoot'],$write['id']).'>'.$write['name'].'</option>';
              }
            echo '</select>';
         echo'<input type="hidden" name="id_player['.$a.']" value="'.$write_stats['id'].'" />';   
         echo '</td>';
            
        echo "</tr>\n";
        $a++;
        
  }
     
      }else{
             echo '<p class="msg warning">Data not found</p>';
          
  
  }
  
  	echo '
  	</table>
  	';
    echo'
  	<p class="box-01">
			     <input type="submit" value="Update players"  class="input-submit" />
		</p>
  
        <input type="hidden" name="action" value="update_mass_players" />
	      <input type="hidden" name="id_club" value="'. $id_club.'" />
	      <input type="hidden" name="id_league_select" value="'. $id_league.'" />
	      <input type="hidden" name="id_season" value="'. $_GET['id_season'].'" />
	      <input type="hidden" name="number" id="number" value="'. $a.'" />
	      <input type="hidden" name="filter" value="'. $_GET['filter'].'" />
	      <input type="hidden" name="filter2" value="'. $_GET['filter2'].'" />
	      <input type="hidden" name="list_number" value="'. $_GET['list_number'].'" />
	      '. OdkazForm.'
	  ';
	echo'
	</form>
  ';
  
  
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
