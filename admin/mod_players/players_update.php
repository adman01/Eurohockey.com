<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(26,1,0,1,0);
$games = new games($con);
require_once("inc/head.inc");
require_once("../inc/tinymce.inc");
echo $head->setTitle(langGlogalTitle."Edit player");
echo $head->setJavascriptExtendFile("js/functions.js");
echo $head->setJavascriptExtendFile("../inc/js/images_folder_box.js");
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
  if (!empty($_GET['id'])) {
  $query="SELECT * FROM players WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  ?>
    
	<h1>Edit player <span style="color:#FFF5CC"><?php echo $write['name'].' '.$write['surname']; ?></span></h1>
    <p class="box">
    	 <a href="players.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of players</span></a>
    	 <a href="players_stats.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>&amp;id=<?echo $_GET['id'];?>"  class="btn-list"><span>back to stats editing</span></a>
	  </p>
	  
	  
	  <? 
	  	if (!empty($_GET['message'])){
	  	echo '<p class="msg error">';
			switch ($_GET['message']){
			 case 98:
          echo 'database error';
        break;
        case 99:
          echo 'wrong or missing input data';
        break;
      }
      echo '</p>';
      }
      ?>
    
    <div class="msg info error" style="display:none;">
      <span></span>.
    </div>
	
<? 
  
    if (!empty($_GET['name'])) $name=$_GET['name']; else  $name=$write['name'];
    if (!empty($_GET['surname'])) $surname=$_GET['surname']; else  $surname=$write['surname'];
    if (!empty($_GET['maiden_name'])) $maiden_name=$_GET['maiden_name']; else  $maiden_name=$write['maiden_name'];
    if (!empty($_GET['name_cyrrilic'])) $name_cyrrilic=$_GET['name_cyrrilic']; else  $name_cyrrilic=$write['name_cyrrilic'];
    
    if (!empty($_GET['nationality'])) $nationality=$_GET['nationality']; else $nationality=$write['nationality'];
    if (!empty($_GET['nationality2'])) $nationality2=$_GET['nationality2']; else $nationality2=$write['nationality_2'];
    if (!empty($_GET['birth_date'])) $birth_date=$_GET['birth_date']; else $birth_date=$write['birth_date'];
    if (!empty($birth_date)){
      $birth_date=explode("-",$birth_date);
      if (!empty($birth_date[2])) $date_bith_day=$birth_date[2]; else $date_bith_day='??';
      if (!empty($birth_date[1])) $date_bith_month=$birth_date[1]; else $date_bith_month='??';
      if (!empty($birth_date[0])) $date_bith_year=$birth_date[0]; else $date_bith_year='????';
      $birth_date=$date_bith_day.'.'.$date_bith_month.'.'.$date_bith_year;
    }
    
    
    if (!empty($_GET['birth_place'])) $birth_place=$_GET['birth_place']; else $birth_place=$write['birth_place'];
    if (!empty($_GET['height'])) $height=$_GET['height']; else $height=$write['height'];
    if (!empty($_GET['weight'])) $weight=$_GET['weight']; else $weight=$write['weight'];
    if (!empty($_GET['id_position'])) $id_position=$_GET['id_position']; else $id_position=$write['id_position'];
    if (!empty($_GET['id_shoot'])) $id_shoot=$_GET['id_shoot']; else $id_shoot=$write['id_shoot'];
    if (!empty($_GET['id_status'])) $id_status=$_GET['id_status']; else $id_status=$write['id_status'];
    if (!empty($_GET['gender'])) $gender=$_GET['gender']; else $gender=$write['gender'];
    if (!empty($_GET['id_photo_folder'])) $id_photo_folder=$_GET['id_photo_folder']; else $id_photo_folder=$write['id_photo_folder'];
    
    
    $query_sub="SELECT * FROM players_draft WHERE id_player=".$_GET['id']." ORDER by id ASC";
        $result_sub = $con->SelectQuery($query_sub);
        $i=0;
        while($write_sub = $result_sub->fetch_array()){
         $draft_id_DB[$i]=$write_sub['id'];
         $draft_team_DB[$i]=$write_sub['team_name'];
         $draft_year_DB[$i]=$write_sub['d_year'];
         $draft_round_DB[$i]=$write_sub['d_round'];
         $draft_position_DB[$i]=$write_sub['d_position'];
         $i++;
      }
    if (!empty($draft_id_DB[0])) $draft_1_id=$draft_id_DB[0];
    if (!empty($_GET['draft_1_team'])) $draft_1_team=$_GET['draft_1_team']; else $draft_1_team=$draft_team_DB[0];
    if (!empty($_GET['draft_1_year'])) $draft_1_year=$_GET['draft_1_year']; else $draft_1_year=$draft_year_DB[0];
    if (!empty($_GET['draft_1_round'])) $draft_1_round=$_GET['draft_1_round']; else $draft_1_round=$draft_round_DB[0];
    if (!empty($_GET['draft_1_position'])) $draft_1_position=$_GET['draft_1_position']; else $draft_1_position=$draft_position_DB[0];
    if (!empty($draft_id_DB[1])) $draft_2_id=$draft_id_DB[1];
    if (!empty($_GET['draft_2_team'])) $draft_2_team=$_GET['draft_2_team']; else $draft_2_team=$draft_team_DB[1];
    if (!empty($_GET['draft_2_year'])) $draft_2_year=$_GET['draft_2_year']; else $draft_2_year=$draft_year_DB[1];
    if (!empty($_GET['draft_2_round'])) $draft_2_round=$_GET['draft_2_round']; else $draft_2_round=$draft_round_DB[1];
    if (!empty($_GET['draft_2_position'])) $draft_2_position=$_GET['draft_2_position']; else $draft_2_position=$draft_position_DB[1];
    if (!empty($draft_id_DB[2])) $draft_3_id=$draft_id_DB[2];
    if (!empty($_GET['draft_3_team'])) $draft_3_team=$_GET['draft_3_team']; else $draft_3_team=$draft_team_DB[2];
    if (!empty($_GET['draft_3_year'])) $draft_3_year=$_GET['draft_3_year']; else $draft_3_year=$draft_year_DB[2];
    if (!empty($_GET['draft_3_round'])) $draft_3_round=$_GET['draft_3_round']; else $draft_3_round=$draft_round_DB[2];
    if (!empty($_GET['draft_3_position'])) $draft_3_position=$_GET['draft_3_position']; else $draft_3_position=$draft_position_DB[2];
    
    if (!empty($_GET['info'])) $info=$_GET['info']; else {
        $query_sub="SELECT detail  FROM players_details WHERE id_player=".$_GET['id']."";
        $result_sub = $con->SelectQuery($query_sub);
        $write_sub = $result_sub->fetch_array();
        $info=$write_sub['detail'];
      }
    
  ?>
	  
<form action="players_action.php" method="post" id="form-01">
<fieldset>
	<legend>Edit player <?php echo $name.' '.$surname; ?></legend>
	
	<p class="nomt">
	<label for="inp-1" class="req">Name:</label><br />
	<input type="text" size="50" name="name" maxlength="50" class="input-text-02 required" id="inp-1" value="<?php echo $name; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Surname:</label><br />
	<input type="text" size="50" name="surname" maxlength="50" class="input-text-02 required" id="inp-2" value="<?php echo $surname; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Maiden name:</label><br />
	<input type="text" size="50" name="maiden_name" maxlength="50" class="input-text-02" id="inp-2" value="<?php echo $maiden_name; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Name in cyrrilic:</label><br />
	<input type="text" size="50" name="name_cyrrilic" maxlength="255" class="input-text-02" id="inp-2" value="<?php echo $name_cyrrilic; ?>" />
	</p>

	<p class="nomt">
	<label for="inp-3" class="req">Gender:</label><br />
  <select id="inp-3" name="gender" class="input-text-02 required">
			  <option value="1" <? echo write_select(1,$gender); ?>>male</option>
			  <option value="2" <? echo write_select(2,$gender); ?>>female</option>
	</select>
  </p>
	
	<p class="nomt">
	<label for="inp-3" class="req">Nationality:</label><br />
  <select id="inp-3" name="nationality" class="input-text-02 required">
			  <option value="">select nationality</option>
			  <option value="N/A" <? echo write_select("N/A",$nationality); ?>>Unknown</option>
			  <?php
        $query_sub="SELECT * FROM countries ORDER by shortcut ASC";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['shortcut'].'"'.write_select($write_sub['shortcut'],$nationality).'>'.$write_sub['shortcut'].' ('.$games->GetActualCountryName($write_sub['id'],ActSeason).')</option>
        ';
        }
        ?>
        </select>
  </p>
	
	<p class="nomt">
	<label for="inp-3" class="req">Second nationality:</label><br />
  <select id="inp-3" name="nationality2" class="input-text-02">
			  <option value="">select nationality</option>
			  <?php
        $query_sub="SELECT * FROM countries ORDER by shortcut ASC";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['shortcut'].'"'.write_select($write_sub['shortcut'],$nationality2).'>'.$write_sub['shortcut'].' ('.$games->GetActualCountryName($write_sub['id'],ActSeason).')</option>
        ';
        }
        ?>
        </select>
  </p>
	 
  <p class="nomt">
	<label for="inp-4" class="req">Birth date:</label><br />
	<input type="text" size="10" name="birth_date" maxlength="10" class="input-text-02" id="inp-4" value="<?php echo $birth_date; ?>" /><br />
	<span class="smaller low">e.g. 09.04.1982</span>
	</p>
	
	<p class="nomt">
	<label for="inp-5" class="req">Birth place:</label><br />
	<input type="text" size="50" name="birth_place" maxlength="50" class="input-text-02" id="inp-5" value="<?php echo $birth_place; ?>" /><br />
  <span class="smaller low">City, Region/state, Country</span>
	</p>
	
	<p class="nomt">
	<label for="inp-6" class="req">Height:</label><br />
	<input type="text" size="5" name="height" maxlength="3" class="input-text-02" id="inp-6" value="<?php echo $height; ?>" /><br />
	<span class="smaller low">in centimeters, only number</span>
	</p>
	
	<p class="nomt">
	<label for="inp-7" class="req">Weight:</label><br />
	<input type="text" size="5" name="weight" maxlength="3" class="input-text-02" id="inp-7" value="<?php echo $weight; ?>" /><br />
	<span class="smaller low">in kilograms, only number</span>
	</p>
	
	<p class="nomt">
	<label for="inp-8" class="req">Position</label><br />
  <select id="inp-8" name="id_position" class="input-text-02 required">
			  <option value="">select position</option>
			  <?php
        $query_sub="SELECT * FROM players_positions_list ORDER by id";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$id_position).'>'.$write_sub['name'].' ('.$write_sub['shortcut'].')</option>
        ';
        }
        ?>
        </select>
  </p>
  
  <p class="nomt">
	<label for="inp-9" class="req">Shoot/catch:</label><br />
  <select id="inp-9" name="id_shoot" class="input-text-02 required">
			  <option value="">select side</option>
			  <?php
        $query_sub="SELECT * FROM players_shoots_list ORDER by id";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$id_shoot).'>'.$write_sub['name'].'</option>
        ';
        }
        ?>
        </select>
  </p>
  
   <p class="nomt">
	<label for="inp-10" class="req">Player actual status:</label><br />
  <select id="inp-10" name="id_status" class="input-text-02 required">
			  <option value="">select status</option>
			  <?php
        $query_sub="SELECT * FROM players_status_list ORDER by id";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$id_status).'>'.$write_sub['name'].'</option>
        ';
        }
        ?>
        </select>
  </p>
  
  <p class="nomt">
	<label for="inp-16" class="req">Pictures:</label><br />
	<? show_image_folder_select_box($con,1,$id_photo_folder,"id_photo_folder");?>
	</p>
  
  <p class="nomt">
	<label for="inp-11" class="req">NHL Draft information #1:</label><br />
	<span class="label label-02">team_name</span> <input type="text" size="20" name="draft_1_team" maxlength="255" class="input-text" id="inp-11" value="<?php echo $draft_1_team; ?>" />&nbsp;
	<span class="label label-02">year</span> <input type="text" size="3" name="draft_1_year" maxlength="4" class="input-text" id="inp-11" value="<?php echo $draft_1_year; ?>" />&nbsp;
	<span class="label label-02">round</span> <input type="text" size="3" name="draft_1_round" maxlength="3" class="input-text" id="inp-11" value="<?php echo $draft_1_round; ?>" />&nbsp;
	<span class="label label-02">position in the draft</span><input type="text" size="3" name="draft_1_position" maxlength="4" class="input-text" id="inp-11" value="<?php echo $draft_1_position; ?>" />&nbsp;
	<input type="hidden" name="draft_1_id" value="<?php echo $draft_1_id; ?>" />
  </p>
	
	<p class="nomt">
	<label for="inp-12" class="req">NHL Draft information #2:</label><br />
	<span class="label label-02">team_name</span> <input type="text" size="20" name="draft_2_team" maxlength="255" class="input-text" id="inp-12" value="<?php echo $draft_2_team; ?>" />&nbsp;
	<span class="label label-02">year</span> <input type="text" size="3" name="draft_2_year" maxlength="4" class="input-text" id="inp-12" value="<?php echo $draft_2_year; ?>" />&nbsp;
	<span class="label label-02">round</span> <input type="text" size="3" name="draft_2_round" maxlength="3" class="input-text" id="inp-12" value="<?php echo $draft_2_round; ?>" />&nbsp;
	<span class="label label-02">position in the draft</span> <input type="text" size="3" name="draft_2_position" maxlength="4" class="input-text" id="inp-12" value="<?php echo $draft_2_position; ?>" />&nbsp;
	<input type="hidden" name="draft_2_id" value="<?php echo $draft_2_id; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-13" class="req">NHL Draft information #3:</label><br />
	<span class="label label-02">team_name</span> <input type="text" size="20" name="draft_3_team" maxlength="255" class="input-text" id="inp-13" value="<?php echo $draft_3_team; ?>" />&nbsp;
	<span class="label label-02">year</span> <input type="text" size="3" name="draft_3_year" maxlength="4" class="input-text" id="inp-13" value="<?php echo $draft_3_year; ?>" />&nbsp;
	<span class="label label-02">round</span> <input type="text" size="3" name="draft_3_round" maxlength="3" class="input-text" id="inp-13" value="<?php echo $draft_3_round; ?>" />&nbsp;
	<span class="label label-02">position in the draft</span> <input type="text" size="3" name="draft_3_position" maxlength="4" class="input-text" id="inp-13" value="<?php echo $draft_3_position; ?>" />&nbsp;
	<input type="hidden" name="draft_3_id" value="<?php echo $draft_3_id; ?>" />
	</p>
  
  
  
  <p class="nomt">
	<label for="elm1" class="req">More information:</label><br />
	<textarea id="elm1" name="info" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"><?php echo $info; ?></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').hide();">[Hide editor]</a>
  		</span>
	
	</p>
	
	<p class="box-01">
			<input type="submit" value="Update player"  class="input-submit" />
			</p>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="id" value="<?php echo $write['id'];?>" />
	<input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	<input type="hidden" name="filter2" value="<?php echo $_GET['filter2'];?>" />
	<input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
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