<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(31,1,0,1,0);
$games = new games($con);
$ArSpecialRight=$users->getSpecialRightsSQL();
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Edit game");
echo $head->setJavascriptExtendFile("js/functions.js");
require_once("../inc/tinymce_big.inc");
echo $head->setJavascriptExtendFile("../inc/js/arena_box.js");

if (!empty($_GET['tab'])) $tab=$_GET['tab']; else $tab=0;
?>
<script type="text/javascript">
$(document).ready(function(){
   
   jQuery.validator.addMethod("checkplayers", function(value, element) {
      
      strReturn=true;
      intPlayerHome1=$("#scorer_home").val();
      intPlayerHome2=$("#assist_1_home").val();
      intPlayerHome3=$("#assist_2_home").val();
      intPlayerVisiting1=$("#scorer_visiting").val();
      intPlayerVisiting2=$("#assist_1_visiting").val();
      intPlayerVisiting3=$("#assist_2_visiting").val();
      if ((intPlayerHome1==intPlayerHome2) && (intPlayerHome1!="" && intPlayerHome2!="")){
         strReturn=false;
      }
      if ((intPlayerHome1==intPlayerHome3) && (intPlayerHome1!="" && intPlayerHome3!="")){
         strReturn=false;
      }
      if ((intPlayerHome2==intPlayerHome3) && (intPlayerHome2!="" && intPlayerHome3!="")){
         strReturn=false;
      }
      
      if ((intPlayerVisiting1==intPlayerVisiting2) && (intPlayerVisiting1!="" && intPlayerVisiting2!="")){
         strReturn=false;
      }
      if ((intPlayerVisiting1==intPlayerVisiting3) && (intPlayerVisiting1!="" && intPlayerVisiting3!="")){
         strReturn=false;
      }
      if ((intPlayerVisiting2==intPlayerVisiting3) && (intPlayerVisiting2!="" && intPlayerVisiting3!="")){
         strReturn=false;
      }
      return strReturn; 
      
      
    },
    jQuery.validator.messages.required="Player is already selected" 
    );

   
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
		var $tabs = $('#tabs').tabs(); // first tab selected
		$tabs.tabs('select',<?php echo $tab; ?>); // switch to third tab
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
		<div id="content" class="box">

    <!-- hlavni text -->

<?php
  if (!empty($_GET['id'])) {
  $query="SELECT * FROM games WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
    $id_season=$write['id_season'];
    $id_league=$write['id_league'];
    $id_club_home=$write['id_club_home'];
    $str_club_home=$games->GetActualClubName($write['id_club_home'],$id_season);
    $id_club_visiting=$write['id_club_visiting'];
    $str_club_visiting=$games->GetActualClubName($write['id_club_visiting'],$id_season);
    $id_stage=$write['id_stage'];
    
    $intIDstageDefault=$con->GetSQLSingleResult("SELECT id_detault as item FROM games_stages WHERE id=".$id_stage);
    if (!empty($intIDstageDefault)) {$id_stage=$intIDstageDefault;}
    
    $id_stage_DB=$write['id_stage'];
  
    if ($write['home_score']=="") $strHome_score="-"; else $strHome_score=$write['home_score'];
    if ($write['visiting_score']=="") $strVisiting_score="-"; else $strVisiting_score=$write['visiting_score'];
    $StrGames_status=$con->GetSQLSingleResult("SELECT name as item FROM games_status_list WHERE id=".$write['games_status']." ORDER by id DESC");
    
    $strGameText=date("d.m.Y",strtotime($write['date'])).', '.$str_club_home.' - '.$str_club_visiting.', '.$strHome_score.':'.$strVisiting_score.' ('.$StrGames_status.')';
    
    
    $last_update=$write["last_update"];
    if ($last_update=='0000-00-00 00:00:00') $last_update='-'; else $last_update=date("d.m.y",strtotime($last_update));
    $strLastEdit=$last_update.' | '.$users->GetUserSignatureID($write["id_user"]);
    
    $id_arena=$write['id_arena']; 
    $referees=$write['referees'];
    $spectators=$write['spectators'];
                                     
    //if (!empty($_GET['is_tournament'])) $is_tournament=$_GET['is_tournament']; else $is_tournament=$write['is_tournament'];
    
  ?>

    
	<h1>Edit game <span style="color:#FFF5CC"><?echo $strGameText;?></span></h1>
    <p class="box">
    	 <a href="games.php<?echo Odkaz;?>&amp;id_season=<?echo $id_season;?>&amp;id=<?echo $id_league;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of games</span></a>
	  </p>
	  
	  <p class="msg info"><b>Last update: </b><?echo $strLastEdit;?></p>
	

<div class="tabs box" id="tabs">
			<ul>

          <?php 
            $intPeriods=$con->GetSQLSingleResult("SELECT count(*) as item FROM games_periods WHERE id_game=".$_GET['id']);
            if (empty($intPeriods)) $intPeriods=0;
            
            $intGoals=$con->GetSQLSingleResult("SELECT count(*) as item FROM games_goals WHERE id_game=".$_GET['id']);
            if (empty($intGoals)) $intGoals=0;
            
            $intStats=$con->GetSQLSingleResult("SELECT count(*) as item FROM games_stats WHERE id_game=".$_GET['id']);
            if (empty($intStats)) $intStats=0;
            
            
          ?>
					<li><a href="#tab01"><span>Periods (<?php echo $intPeriods; ?>)</span></a></li>
					<li><a href="#tab02"><span>Goals and assists (<?php echo $intGoals; ?>)</span></a></li>
					<li><a href="#tab03"><span>Players stats (<?php echo $intStats; ?>)</span></a></li>
					<li><a href="#tab04"><span>Additional info</span></a></li>
				</ul>
			</div> <!-- /tabs -->

  <? 
	  	if (!empty($_GET['message'])){
	  	switch ($_GET['message']){
	  	 //periods
	  	 case 1:
          echo '<p class="msg done">periods has been added.</p>';
        break;
        case 2:
          echo '<p class="msg done">period has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">periods has been changed.</p>';
        break;
       //goals
	  	  case 4:
          echo '<p class="msg done">goal has been added.</p>';
        break;
        case 5:
          echo '<p class="msg done">goal has been removed.</p>';
        break;
        case 6:
          echo '<p class="msg done">goal has been changed.</p>';
        break;
        case 7:
          echo '<p class="msg done">additional info has been changed.</p>';
        break;
        case 8:
          echo '<p class="msg done">roster has been updated.</p>';
        break;
        case 9:
          echo '<p class="msg done">game stats has been deleted.</p>';
        break;
        case 10:
          echo '<p class="msg done">game stats has been changed.</p>';
        break;
			 case 98:
          echo '<p class="msg error">database error</p>';
        break;
        case 99:
          echo '<p class="msg error">wrong or missing input data</p>';
        break;
      }
      }
  ?>
    
    <div class="msg info error" style="display:none;">
      <span></span>.
    </div>


<div id="tab01">	  
<form action="games_action.php" method="post" id="form-01">
<fieldset>
	<legend>Add new periods</legend>
	
	<?php
	$intPeriods=$con->GetSQLSingleResult("SELECT periods_order as item FROM games_periods WHERE id_game=".$_GET['id']." ORDER by periods_order DESC");
	$intPeriods=$intPeriods+1;
	?>
	
	<table>
	<tr>
    <th>Order</th>
    <th>Score (home:visiting)</th>
    <th>Shots (home:visiting)</th>
    <th>Type</th>
   </tr>
   
  <?php
	for ($i=1; $i<=(5); $i++){
	?>
   
	 <tr>
    <td><input type="text" size="1" name="order[<?php echo ($i);?>]" maxlength="2" class="input-text required" value="<?php echo ($intPeriods+($i-1));?>" /></td>
    <td><input type="text" size="1" name="score_home[<?php echo ($i);?>]" maxlength="2" class="input-text" value="" /> <b>:</b> <input type="text" size="1" name="score_visiting[<?php echo ($i);?>]" maxlength="2" class="input-text" value="" /></td>
    <td><input type="text" size="1" name="shoots_home[<?php echo ($i);?>]" maxlength="2" class="input-text" value="" /> <b>:</b> <input type="text" size="1" name="shoots_visiting[<?php echo ($i);?>]" maxlength="2" class="input-text" value="" /></td>
    <td>
    <select name="id_type[<?php echo ($i);?>]" class="input-text">
			  <?php
        $query_sub="SELECT * FROM games_periods_types ORDER by id";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        if ($i<4) $id_type=1; else $id_type=2; 
        if ($i==5) $id_type=3;
        echo '
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$id_type).'>'.$write_sub['name'].'</option>
        ';
        }
        ?>
     </select>
     </td>
     </tr>
     
     <?php
	   }
     ?>
     
     <tr>
     <td colspan="4" class="small t-right">
         You must fill score, or shots, to add a period
         <input type="submit" value="Add periods"  class="input-submit" />
         <input type="hidden" name="action" value="add_periods" />
	       <input type="hidden" name="id_game" value="<?php echo $_GET['id'];?>" />
	       <input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	       <input type="hidden" name="filter2" value="<?php echo $_GET['filter2'];?>" />
	       <input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
	       <?echo OdkazForm;?>
     </td>
   </tr>
	</table>

</fieldset>	
</form>

<?

$query="SELECT * FROM games_periods WHERE id_game=".$_GET['id']." ORDER by periods_order ASC, id ASC";
//echo $query;
 

    $result = $con->SelectQuery($query);
    $i = 0;
    $intPocet=$con->GetQueryNum($query);
    if ($intPocet>0){
    
    
    echo '
<form action="games_action.php" method="post" id="form-02"  style="">
<fieldset>
<legend>Existing periods</legend>

<table>
  <tr>
    <th>Order</th>
    <th>Score (home:visiting)</th>
    <th>Shots (home:visiting)</th>
    <th>Type</th>
    <th></th>
  </tr>
';


    
    while($write = $result->fetch_array())
	   {
      
      echo '<tr>';
      
         echo'<td><input type="text" name="order['.$i.']" size="2" maxlength="2" value="'.$write['periods_order'].'" class="input-text required" /></td>';
         echo'<td><input type="text" size="1" name="score_home['.$i.']" maxlength="2" class="input-text" value="'.$write['score_home'].'" /> <b>:</b> <input type="text" size="1" name="score_visiting['.$i.']" maxlength="2" class="input-text" value="'.$write['score_visiting'].'" /></td>';
         echo'<td><input type="text" size="1" name="shoots_home['.$i.']" maxlength="2" class="input-text" value="'.$write['shoots_home'].'" /> <b>:</b> <input type="text" size="1" name="shoots_visiting['.$i.']" maxlength="2" class="input-text" value="'.$write['shoots_visiting'].'" /></td>';
         echo'<td>';
	       echo '<select name="id_type['.$i.']" class="input-text">';   
              $query_sub="SELECT * FROM games_periods_types ORDER by id ASC";
              $result_sub = $con->SelectQuery($query_sub);
              while($write_sub = $result_sub->fetch_array()){
                echo '<option value="'.$write_sub['id'].'" '.write_select($write_sub['id'],$write['id_type']).'>'.$write_sub['name'].'</option>';
              }
         echo '</select>';   
         echo '</td>';
         echo '<td style="white-space:nowrap">';
         
          echo '<input type="hidden" name="id['.$i.']" value="'.$write['id'].'" />';
          
          if ($users->checkUserRight(4) and $num_stats==0){ 
          echo'<a href="javascript:delete_period(\''.$users->getSesid().'\','.$write["id"].','.$write["id_game"].',\''.$write["periods_order"].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
          }
         echo '</td>';
         
      echo '</tr>
    ';
    $i++;
    }
    echo'</table>';
    echo '
    	<p class="box-01">
			     <input type="submit" value="Update periods"  class="input-submit" />
			   </p>
  
        <input type="hidden" name="action" value="update_period" />
	      <input type="hidden" name="filter" value="'. $_GET['filter'].'" />
	      <input type="hidden" name="filter2" value="'. $_GET['filter2'].'" />
	      <input type="hidden" name="list_number" value="'. $_GET['list_number'].'" />
	      <input type="hidden" name="id_game" value="'. $_GET['id'].'" />
	        
	      <input type="hidden" name="number" value="'. $i.'" />
	      '. OdkazForm.'
	      </fieldset>	
	      </form>
    ';
    
	   
	  }else{
      echo '<p class="msg warning">No periods found</p>';
      
    }


?>
</div>

<div id="tab02">	  
<form action="games_action.php" method="post" id="form-03">
<fieldset>
	<legend>Add new goal</legend>
	
	<p class="msg info">
  If the player is not in the list, <a href="../mod_players/players.php<?php echo Odkaz; ?>">add a <?php echo ($id_season-1).'-'.$id_season; ?> season statistics, club and league for the given player</a>.
  </p>

<?	
	function PlayerSelect($id_season,$id_league,$id_stage,$id_club,$id_selected){
    global $con,$games;
    
    $query="SELECT DISTINCT players.id as id_player,name,surname,id_position from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id_league." AND id_club=".$id_club." AND id_season=".$id_season."  AND id_season_type=".$id_stage." AND id_stats_type=1 ORDER BY players.surname ASC,players.name ASC";
    //echo $query; 
    if ($con->GetQueryNum($query)>0){
         $result = $con->SelectQuery($query);
         while($write = $result->fetch_array()){
            $strPlayerPos=$con->GetSQLSingleResult("SELECT shortcut as item FROM players_positions_list WHERE id=".$write['id_position']);
            $strPlayerSelect.='<option value="'.$write['id_player'].'" '.write_select($write['id_player'],$id_selected).'>'.$write['surname'].' '.$write['name'].' ('.$strPlayerPos.')</option>';
         }
    }
    return $strPlayerSelect; 
}
?>
	
	<table>
	<tr>
	  <th>Team</th>
    <th>Time</th>
    <th>Scorer</th>
    <th>1st assist</th>
    <th>2nd assist</th>
    <th>Type</th>
    <th></th>
   </tr>
	 <tr>
	  <td valign="top" style="white-space:nowrap">
	   <?php
	   echo '<input type="radio" name="id_club" value="'.$id_club_home.'" checked="checked" onclick="toggle_goals(\'home\')" /> <b>'.$games->GetActualClubName($id_club_home,$id_season).'</b><br />';
	   echo '<input type="radio" name="id_club" value="'.$id_club_visiting.'" onclick="toggle_goals(\'visiting\')" /> <b>'.$games->GetActualClubName($id_club_visiting,$id_season).'</b>';
	   ?>
    </td>
    <td><input type="text" size="1" name="goal_time_min" maxlength="3" class="input-text required" value="" /><b>:</b><input type="text" size="1" name="goal_time_sec" maxlength="2" class="input-text required" value="" /></td>
    <td>
     <select name="scorer_home" id="scorer_home" class="checkplayers input-text">
      <option value="">select scorer</option>
      <?php
      echo PlayerSelect($id_season,$id_league,$id_stage,$id_club_home,0);
      ?>
      </select>       
      <select name="scorer_visiting" id="scorer_visiting" class="checkplayers input-text" style="display:none">
      <option value="">select scorer</option>
      <?php
      echo PlayerSelect($id_season,$id_league,$id_stage,$id_club_visiting,0);
      ?>
      </select>
    </td>
    <td>
     <select name="assist_1_home" id="assist_1_home" class="checkplayers input-text">
      <option value="">without assist 1</option>
      <?php
      echo PlayerSelect($id_season,$id_league,$id_stage,$id_club_home,0);
      ?>
      </select>
      <select name="assist_1_visiting" id="assist_1_visiting" class="checkplayers input-text" style="display:none">
      <option value="">without assist 1</option>
      <?php
      echo PlayerSelect($id_season,$id_league,$id_stage,$id_club_visiting,0);
      ?>
      </select>
    </td>
    <td>
     <select name="assist_2_home" id="assist_2_home" class="checkplayers input-text">
      <option value="">without assist 2</option>
      <?php
      echo PlayerSelect($id_season,$id_league,$id_stage,$id_club_home,0);
      ?>
      </select>
      <select name="assist_2_visiting" id="assist_2_visiting" class="checkplayers input-text" style="display:none">>
      <option value="">without assist 2</option>
      <?php
      echo PlayerSelect($id_season,$id_league,$id_stage,$id_club_visiting,0);
      ?>
      </select>
    </td>
    <td>
    <select name="id_goal_type" class="input-text">
			  <?php
        $query_sub="SELECT * FROM games_goals_types ORDER by id";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['id'].'">'.$write_sub['name'].'</option>
        ';
        }
        ?>
     </select>
     </td>
     <td>
         <input type="submit" value="Add goal"  class="input-submit" />
         <input type="hidden" name="action" value="add_goal" />
	       <input type="hidden" name="id_game" value="<?php echo $_GET['id'];?>" />
	       <input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	       <input type="hidden" name="filter2" value="<?php echo $_GET['filter2'];?>" />
	       <input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
	       <?echo OdkazForm;?>
     </td>
   </tr>
	</table>

</fieldset>	
</form>

<?

$query="SELECT * FROM games_goals WHERE id_game=".$_GET['id']." ORDER by goal_time_min ASC,goal_time_sec ASC, id ASC";
//echo $query;

    $result = $con->SelectQuery($query);
    $i = 0;
    $intPocet=$con->GetQueryNum($query);
    if ($intPocet>0){
    
    
    echo '
<form action="games_action.php" method="post" id="form-04"  style="">
<fieldset>
<legend>Existing goals</legend>

<table>
  <tr>
    <th>Team</th>
    <th>Time</th>
    <th>Scorer</th>
    <th>1st assist</th>
    <th>2nd assist</th>
    <th>Type</th>
    <th></th>
  </tr>
';
    
    while($write = $result->fetch_array())
	   {
      
      echo '<tr>';
         
         echo'<td><b>'.$games->GetActualClubName($write['id_club'],$id_season).'</b></td>';
         echo'<td><input type="text" size="1" name="goal_time_min['.$i.']" maxlength="3" class="input-text required" value="'.$write['goal_time_min'].'" /><b>:</b><input type="text" size="1" name="goal_time_sec['.$i.']" maxlength="2" class="input-text required" value="'.$write['goal_time_sec'].'" /></td>';
         echo'<td>';
	       echo '
         <select name="scorer['.$i.']" class="input-text required">';
          echo PlayerSelect($id_season,$id_league,$id_stage,$write['id_club'],$write['scorer']);
         echo'</select>';   
         echo '</td>';
         
         echo'<td>';
	       echo '
          <select name="assist_1['.$i.']" class="input-text">
            <option value="">without assist 1</option>';
              echo PlayerSelect($id_season,$id_league,$id_stage,$write['id_club'],$write['assist_1']);
         echo'</select>';   
         echo '</td>';
         
         echo'<td>';
	       echo '
          <select name="assist_2['.$i.']" class="input-text">
            <option value="">without assist 2</option>';
              echo PlayerSelect($id_season,$id_league,$id_stage,$write['id_club'],$write['assist_2']);
         echo'</select>';   
         echo '</td>';
         
         echo'<td>';
	       echo '<select name="id_goal_type['.$i.']" class="input-text">';   
              $query_sub="SELECT * FROM games_goals_types ORDER by id ASC";
              $result_sub = $con->SelectQuery($query_sub);
              while($write_sub = $result_sub->fetch_array()){
                echo '<option value="'.$write_sub['id'].'" '.write_select($write_sub['id'],$write['id_goal_type']).'>'.$write_sub['name'].'</option>';
              }
         echo '</select>';   
         echo '</td>';
         
         echo '<td style="white-space:nowrap">';
         
          echo '<input type="hidden" name="id['.$i.']" value="'.$write['id'].'" />';
          
          if ($users->checkUserRight(4) and $num_stats==0){ 
          echo'<a href="javascript:delete_goal(\''.$users->getSesid().'\','.$write["id"].','.$write["id_game"].',\''.$write["goal_time_min"].':'.$write["goal_time_sec"].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
          }
         echo '</td>';
         
      echo '</tr>
    ';
    $i++;
    }
    echo'</table>';
    echo '
    	<p class="box-01">
			     <input type="submit" value="Update goals"  class="input-submit" />
			   </p>
  
        <input type="hidden" name="action" value="update_goal" />
	      <input type="hidden" name="filter" value="'. $_GET['filter'].'" />
	      <input type="hidden" name="filter2" value="'. $_GET['filter2'].'" />
	      <input type="hidden" name="list_number" value="'. $_GET['list_number'].'" />
	      <input type="hidden" name="id_game" value="'. $_GET['id'].'" />
	        
	      <input type="hidden" name="number" value="'. $i.'" />
	      '. OdkazForm.'
	      </fieldset>	
	      </form>
    ';
    
	   
	  }else{
      echo '<p class="msg warning">No goals found</p>';
      
    }


?>
</div>


<div id="tab04">
<form action="games_action.php" method="post">
<fieldset>
	<legend>Additional info</legend>
      
  <p class="nomt">
	<label for="inp-2" class="req">Arena:</label><br />
  <? show_arena_select_box($con,1,$id_arena,"id_arena");?>
	</p>
  
  
  <p class="nomt">
	<label for="inp-1" class="req">Referees:</label><br />
	<input type="text" size="50" name="referees" maxlength="255" class="input-text-02 required" value="<?php echo $referees; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-1" class="req">Number of spectators:</label><br />
	<input type="text" size="5" name="spectators" maxlength="5" class="input-text-02" value="<?php echo $spectators; ?>" />
	</p>
	
	<p class="nomt">
	<label for="elm1" class="req">Game recap:</label><br />
	
	<?php
  $info=$con->GetSQLSingleResult("SELECT text as item FROM games_detail WHERE id_game=".$_GET['id']); 
  ?>
	<textarea id="elm1" name="info" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"><?php echo $info; ?></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').hide();">[Hide editor]</a>
  		</span>
	
	</p>
	
	    
      
      <p class="box-01">
			<input type="submit" value="Update data"  class="input-submit" />
			</p>
	<input type="hidden" name="action" value="update_info" />
	<input type="hidden" name="id_game" value="<?php echo $_GET['id'];?>" />
	<input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	<input type="hidden" name="filter2" value="<?php echo $_GET['filter2'];?>" />
	<input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
	<?echo OdkazForm;?>
</fieldset>	
</form>

</div>

<div id="tab03">

<a href="javascript:toggle('roster_add')"  class="btn-create"><span>Add players to game roster</span></a>
<br class="fix" />
<div id="roster_add" style="display:none">
<form action="games_action.php"  method="post">
<fieldset>
	<legend>Add players to game roster</legend>
	  
    <p class="msg info">
  If the player is not in the list, <a href="../mod_players/players.php<?php echo Odkaz; ?>">add a <?php echo ($id_season-1).'-'.$id_season; ?> season statistics, club and league for the given player</a>.
  </p>
       
	 <?php   
   	
   	echo '
     <table style="width:300px; float:left;">
    ';
     //domaci
     $i=0;
    $query="SELECT DISTINCT players.id as id_player,name,surname,id_position from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id_league." AND id_club=".$id_club_home." AND id_season_type=".$id_stage." AND id_season=".$id_season." AND id_stats_type=1 AND (SELECT COUNT(*) FROM games_stats WHERE games_stats.id_game=".$_GET['id']." AND games_stats.id_player=players.id AND id_club=".$id_club_home.")=0 ORDER BY players.surname ASC,players.name ASC";
    //echo $query; 
    if ($con->GetQueryNum($query)>0){
         
         echo '
         <tr>
          <th colspan="2">'.$str_club_home.'</th>
          
         </tr>';
         $result = $con->SelectQuery($query);
         while($write = $result->fetch_array()){
            $strPlayerPos=$con->GetSQLSingleResult("SELECT shortcut as item FROM players_positions_list WHERE id=".$write['id_position']);
            echo '<tr>';
            echo '<td class="t-center"><input type="checkbox" id="checkbox1" name="id['.$i.']" value="'.$write['id_player'].'_'.$id_club_home.'" /></td>';
            echo '<td><a class="ico-show" href="../mod_players/players_stats.php'.Odkaz.'&amp;id='.$write['id_player'].'" title="Show player stats" target="_blank">'.$write['surname'].' '.$write['name'].'</a> ('.$strPlayerPos.')</td>';
            echo '</tr>';
            $i++;
         }
         echo '
         <tfoot>
  	    <tr class="high-bg">
          <td class="t-center">
          <input type="checkbox" id="check_all_1" name="check_all_1" onclick="CheckAll2(this.form,\'checkbox1\',\'check_all_1\');" />
          </td>
          <td class="smaller low">check all</td>
        </tr>
      </tfoot>
         '    ;
    }
    echo '
     
    </table>
    
    <table style="width:300px;float:left; margin-left:15px">
    ';
    
    $query="SELECT DISTINCT players.id as id_player,name,surname,id_position from stats INNER JOIN players ON stats.id_player=players.id WHERE id_league=".$id_league." AND id_club=".$id_club_visiting." AND id_season=".$id_season." AND id_season_type=".$id_stage." AND id_stats_type=1 AND (SELECT COUNT(*) FROM games_stats WHERE games_stats.id_game=".$_GET['id']." AND games_stats.id_player=players.id AND id_club=".$id_club_visiting.")=0 ORDER BY players.surname ASC,players.name ASC";
    //echo $query; 
    if ($con->GetQueryNum($query)>0){
         echo '<tr><th colspan="2">'.$str_club_visiting.'</th></tr>';
         $result = $con->SelectQuery($query);
         while($write = $result->fetch_array()){
            $strPlayerPos=$con->GetSQLSingleResult("SELECT shortcut as item FROM players_positions_list WHERE id=".$write['id_position']);
            echo '<tr>';
            echo '<td class="t-center"><input type="checkbox" id="checkbox2" name="id['.$i.']" value="'.$write['id_player'].'_'.$id_club_visiting.'" /></td>';
            echo '<td><a class="ico-show" href="../mod_players/players_stats.php'.Odkaz.'&amp;id='.$write['id_player'].'" title="Show player stats" target="_blank">'.$write['surname'].' '.$write['name'].'</a> ('.$strPlayerPos.')</td>';
            echo '</tr>';
            $i++;
         }
         echo '
         <tfoot>
  	    <tr class="high-bg">
          <td class="t-center">
          <input type="checkbox" id="check_all2" name="check_all2" onclick="CheckAll2(this.form,\'checkbox2\',\'check_all2\');" />
          </td>
          <td class="smaller low">check all</td>
        </tr>
      </tfoot>';
    }
    echo '
     
    </table>
    <br class="fix" />
    ';
       
  ?> 
	    
	    
      <p class="box-01">
			 <input type="submit" value="Add to game roster"  class="input-submit" />
			</p>
	<input type="hidden" name="action" value="add_roster" />
	<input type="hidden" name="id_game" value="<?php echo $_GET['id'];?>" />
	<input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	<input type="hidden" name="filter2" value="<?php echo $_GET['filter2'];?>" />
	<input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
	<input type="hidden" name="number" value="<?php echo $i;?>" />
	<input type="hidden" name="id_season" value="<?php echo $id_season;?>" />
	<input type="hidden" name="id_league" value="<?php echo $id_league;?>" />
	<?echo OdkazForm;?>
</fieldset>	
</form>
</div>

<form action="games_action.php" method="post">
<fieldset>
	<legend>Game roster and stats</legend>
	
	 <?php   
   	
   	$query_lock="SELECT * FROM stats_lock WHERE id_club=0 AND id_season=".$id_season." AND id_league=".$id_league."";
    if ($con->GetQueryNum($query_lock)==0){
        $query_lock="SELECT * FROM stats_lock WHERE id_club=".$id_club_home." AND id_season=".$id_season." AND id_league=".$id_league."";
        //echo $query_lock; 
        if ($con->GetQueryNum($query_lock)>0){
          echo '<p class="msg warning">Automatic stats counting for season <b>'.($id_season-1).'-'.$id_season.'</b> and club <b>'.$games->GetActualClubName($id_club_home,($id_season)).'</b> is LOCKED</p>';
        }
    }else{
      echo '<p class="msg warning">Automatic stats counting for season <b>'.($id_season-1).'-'.$id_season.'</b> and league <b>'.$games->GetActualLeagueName($id_league,($id_season)).'</b> is LOCKED</p>';  
    }
   	
   	$i=0;
    echo '
   	<table>
    ';
     //domaci skaters
    $query="SELECT DISTINCT players.id as id_player,games_stats.id as id,id_club,name,surname,id_position,goals,assist,plusminus,penalty,id_game from games_stats INNER JOIN players ON games_stats.id_player=players.id WHERE games_stats.id_game=".$_GET['id']." AND games_stats.id_club=".$id_club_home." AND id_position<>1 ORDER BY players.surname ASC,players.name ASC";
    //echo $query; 
    if ($con->GetQueryNum($query)>0){
         
         echo '
         <tr>
          <th>'.$str_club_home.' - skaters</th>
          <th>G</th>
          <th>A</th>
          <th>PIM</th>
          <th>+/</th>
          <th></th>
         </tr>';
         $result = $con->SelectQuery($query);
         while($write = $result->fetch_array()){
            $strPlayerPos=$con->GetSQLSingleResult("SELECT shortcut as item FROM players_positions_list WHERE id=".$write['id_position']);
            echo '<tr>';
            echo '<td style="width:200px;"><a class="ico-show" href="../mod_players/players_stats.php'.Odkaz.'&amp;id='.$write['id_player'].'" title="Show player stats" target="_blank">'.$write['surname'].' '.$write['name'].'</a> ('.$strPlayerPos.')</td>';
            echo '<td><input type="text" size="1" name="goals['.$i.']" maxlength="2" class="input-text" value="'.$write['goals'].'" /></td>';
            echo '<td><input type="text" size="1" name="assist['.$i.']" maxlength="2" class="input-text" value="'.$write['assist'].'" /></td>';
            echo '<td><input type="text" size="1" name="penalty['.$i.']" maxlength="2" class="input-text" value="'.$write['penalty'].'" /></td>';
            echo '<td><input type="text" size="1" name="plusminus['.$i.']" maxlength="3" class="input-text" value="'.$write['plusminus'].'" /></td>';
            echo '
            <td>
              <input type="hidden" name="id['.$i.']" value="'.$write['id'].'" />
               <input type="hidden" name="id_club['.$i.']" value="'.$write['id_club'].'" />
              <a href="javascript:delete_stats(\''.$users->getSesid().'\','.$write["id"].','.$write["id_game"].',\''.$write['surname'].' '.$write['name'].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>
            </td>';
            echo '</tr>';
            $i++;
         }
    }
    echo '
    </table>
    
     <table>
    ';
     //domaci goalies
    $query="SELECT DISTINCT players.id as id_player,games_stats.id as id,id_club,name,surname,id_position,goals,assist,shoots_against,goals_against,penalty,minutes,games_dressed,games_shotouts,id_game from games_stats INNER JOIN players ON games_stats.id_player=players.id WHERE games_stats.id_game=".$_GET['id']." AND games_stats.id_club=".$id_club_home." AND id_position=1 ORDER BY players.surname ASC,players.name ASC";
    if ($con->GetQueryNum($query)>0){
         
         echo '
         <tr>
          <th>'.$str_club_home.' - goalies</th>
          <th>GPD</th>
          <th>SO</th>
          <th>SA</th>
          <th>GA</th>
          <th>MIN</th>
          <th>G</th>
          <th>A</th>
          <th>PIM</th>
          <th></th>
         </tr>';
         $result = $con->SelectQuery($query);
         while($write = $result->fetch_array()){
            $strPlayerPos=$con->GetSQLSingleResult("SELECT shortcut as item FROM players_positions_list WHERE id=".$write['id_position']);
            echo '<tr>';
            echo '<td style="width:200px;"><a class="ico-show" href="../mod_players/players_stats.php'.Odkaz.'&amp;id='.$write['id_player'].'" title="Show player stats" target="_blank">'.$write['surname'].' '.$write['name'].'</a> ('.$strPlayerPos.')</td>';
            
            if ($write['games_dressed']==1) $strDressed=' checked="checked"'; else $strDressed='';
            echo '<td class="t-center"><input type="checkbox" name="games_dressed['.$i.']" '.$strDressed.' value="1" /></td>';
            if ($write['games_shotouts']==1) $strShotouts=' checked="checked"'; else $strShotouts='';
            echo '<td class="t-center"><input type="checkbox" name="games_shotouts['.$i.']" '.$strShotouts.' value="1" /></td>';
            echo '<td><input type="text" size="1" name="shoots_against['.$i.']" maxlength="3" class="input-text" value="'.$write['shoots_against'].'" /></td>';
            echo '<td><input type="text" size="1" name="goals_against['.$i.']" maxlength="2" class="input-text" value="'.$write['goals_against'].'" /></td>';
            echo '<td><input type="text" size="1" name="minutes['.$i.']" maxlength="3" class="input-text" value="'.$write['minutes'].'" /></td>';
            echo '<td><input type="text" size="1" name="goals['.$i.']" maxlength="2" class="input-text" value="'.$write['goals'].'" /></td>';
            echo '<td><input type="text" size="1" name="assist['.$i.']" maxlength="2" class="input-text" value="'.$write['assist'].'" /></td>';
            echo '<td><input type="text" size="1" name="penalty['.$i.']" maxlength="2" class="input-text" value="'.$write['penalty'].'" /></td>';
            echo '
            <td>
              <input type="hidden" name="id['.$i.']" value="'.$write['id'].'" />
               <input type="hidden" name="id_club['.$i.']" value="'.$write['id_club'].'" />
              <a href="javascript:delete_stats(\''.$users->getSesid().'\','.$write["id"].','.$write["id_game"].',\''.$write['surname'].' '.$write['name'].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>
            </td>';
            echo '</tr>';
            $i++;
         }
    }
    echo '
    </table>
    <br />
    <hr />
    <br />
    ';
    
    $query_lock="SELECT * FROM stats_lock WHERE id_club=0 AND id_season=".$id_season." AND id_league=".$id_league."";
    if ($con->GetQueryNum($query_lock)==0){
        $query_lock="SELECT * FROM stats_lock WHERE id_club=".$id_club_visiting." AND id_season=".$id_season." AND id_league=".$id_league."";
        //echo $query_lock; 
        if ($con->GetQueryNum($query_lock)>0){
          echo '<p class="msg warning">Automatic stats counting for season <b>'.($id_season-1).'-'.$id_season.'</b> and club <b>'.$games->GetActualClubName($id_club_visiting,($id_season)).'</b> is LOCKED</p>';
        }
    }else{
      echo '<p class="msg warning">Automatic stats counting for season <b>'.($id_season-1).'-'.$id_season.'</b> and league <b>'.$games->GetActualLeagueName($id_league,($id_season)).'</b> is LOCKED</p>';  
    }
    
     echo '
   	<table>
    ';
     //hostujici skaters
    $query="SELECT DISTINCT players.id as id_player,games_stats.id as id,id_club,name,surname,id_position,goals,assist,plusminus,penalty,id_game from games_stats INNER JOIN players ON games_stats.id_player=players.id WHERE games_stats.id_game=".$_GET['id']." AND games_stats.id_club=".$id_club_visiting." AND id_position<>1 ORDER BY players.surname ASC,players.name ASC";
    if ($con->GetQueryNum($query)>0){
         
         echo '
         <tr>
          <th>'.$str_club_visiting.' - skaters</th>
          <th>G</th>
          <th>A</th>
          <th>PIM</th>
          <th>+/</th>
          <th></th>
         </tr>';
         $result = $con->SelectQuery($query);
         while($write = $result->fetch_array()){
            $strPlayerPos=$con->GetSQLSingleResult("SELECT shortcut as item FROM players_positions_list WHERE id=".$write['id_position']);
            echo '<tr>';
            echo '<td style="width:200px;"><a class="ico-show" href="../mod_players/players_stats.php'.Odkaz.'&amp;id='.$write['id_player'].'" title="Show player stats" target="_blank">'.$write['surname'].' '.$write['name'].'</a> ('.$strPlayerPos.')</td>';
            echo '<td><input type="text" size="1" name="goals['.$i.']" maxlength="2" class="input-text" value="'.$write['goals'].'" /></td>';
            echo '<td><input type="text" size="1" name="assist['.$i.']" maxlength="2" class="input-text" value="'.$write['assist'].'" /></td>';
            echo '<td><input type="text" size="1" name="penalty['.$i.']" maxlength="2" class="input-text" value="'.$write['penalty'].'" /></td>';
            echo '<td><input type="text" size="1" name="plusminus['.$i.']" maxlength="3" class="input-text" value="'.$write['plusminus'].'" /></td>';
            echo '
            <td>
              <input type="hidden" name="id['.$i.']" value="'.$write['id'].'" />
               <input type="hidden" name="id_club['.$i.']" value="'.$write['id_club'].'" />
              <a href="javascript:delete_stats(\''.$users->getSesid().'\','.$write["id"].','.$write["id_game"].',\''.$write['surname'].' '.$write['name'].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>
            </td>';
            echo '</tr>';
            $i++;
         }
    }
    echo '
    </table>
    
     <table>
    ';
     //hostujici goalies
    $query="SELECT DISTINCT players.id as id_player,games_stats.id as id,id_club,name,surname,id_position,goals,assist,shoots_against,goals_against,goals_against,penalty,minutes,games_dressed,games_shotouts,id_game from games_stats INNER JOIN players ON games_stats.id_player=players.id WHERE games_stats.id_game=".$_GET['id']." AND games_stats.id_club=".$id_club_visiting." AND id_position=1 ORDER BY players.surname ASC,players.name ASC";
    if ($con->GetQueryNum($query)>0){
         
         echo '
         <tr>
          <th>'.$str_club_visiting.' - goalies</th>
          <th>GPD</th>
          <th>SO</th>
          <th>SA</th>
          <th>GA</th>
          <th>MIN</th>
          <th>G</th>
          <th>A</th>
          <th>PIM</th>
          <th></th>
         </tr>';
         $result = $con->SelectQuery($query);
         while($write = $result->fetch_array()){
            $strPlayerPos=$con->GetSQLSingleResult("SELECT shortcut as item FROM players_positions_list WHERE id=".$write['id_position']);
            echo '<tr>';
            echo '<td style="width:200px;"><a class="ico-show" href="../mod_players/players_stats.php'.Odkaz.'&amp;id='.$write['id_player'].'" title="Show player stats" target="_blank">'.$write['surname'].' '.$write['name'].'</a> ('.$strPlayerPos.')</td>';
            
            if ($write['games_dressed']==1) $strDressed=' checked="checked"'; else $strDressed='';
            echo '<td class="t-center"><input type="checkbox" name="games_dressed['.$i.']" '.$strDressed.' value="1" /></td>';
            if ($write['games_shotouts']==1) $strShotouts=' checked="checked"'; else $strShotouts='';
            echo '<td class="t-center"><input type="checkbox" name="games_shotouts['.$i.']" '.$strShotouts.' value="1" /></td>';
            echo '<td><input type="text" size="1" name="shoots_against['.$i.']" maxlength="3" class="input-text" value="'.$write['shoots_against'].'" /></td>';
            echo '<td><input type="text" size="1" name="goals_against['.$i.']" maxlength="2" class="input-text" value="'.$write['goals_against'].'" /></td>';
            echo '<td><input type="text" size="1" name="minutes['.$i.']" maxlength="3" class="input-text" value="'.$write['minutes'].'" /></td>';
            echo '<td><input type="text" size="1" name="goals['.$i.']" maxlength="2" class="input-text" value="'.$write['goals'].'" /></td>';
            echo '<td><input type="text" size="1" name="assist['.$i.']" maxlength="2" class="input-text" value="'.$write['assist'].'" /></td>';
            echo '<td><input type="text" size="1" name="penalty['.$i.']" maxlength="2" class="input-text" value="'.$write['penalty'].'" /></td>';
            echo '
            <td>
              <input type="hidden" name="id['.$i.']" value="'.$write['id'].'" />
              <input type="hidden" name="id_club['.$i.']" value="'.$write['id_club'].'" />
              <a href="javascript:delete_stats(\''.$users->getSesid().'\','.$write["id"].','.$write["id_game"].',\''.$write['surname'].' '.$write['name'].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>
            </td>';
            echo '</tr>';
            $i++;
         }
    }
    echo '
    </table>
    ';
       
  ?> 
	
      <p class="box-01">
			<input type="submit" value="Update stats"  class="input-submit" />
			</p>
	<input type="hidden" name="action" value="update_stats" />
	<input type="hidden" name="number" value="<?php echo $i;?>" />
	<input type="hidden" name="id_game" value="<?php echo $_GET['id'];?>" />
	<input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	<input type="hidden" name="filter2" value="<?php echo $_GET['filter2'];?>" />
	<input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
	<input type="hidden" name="id_season" value="<?php echo $id_season;?>" />
	<input type="hidden" name="id_league" value="<?php echo $id_league;?>" />
	<input type="hidden" name="id_stage" value="<?php echo $id_stage_DB;?>" />
	<?echo OdkazForm;?>
</fieldset>	
</form>

	

<?php
	}else{echo '<p class="msg warning">No data found</p>';}
}else{echo '<p class="msg warning">No data found</p>';}
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
</html>