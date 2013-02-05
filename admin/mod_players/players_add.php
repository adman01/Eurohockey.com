<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(26,1,1,0,0);
$games = new games($con);
require_once("inc/head.inc");
require_once("../inc/tinymce.inc");

echo $head->setTitle(langGlogalTitle."Add player");
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
    
	<h1>Add new player</h1>
    <p class="box">
    	 <a href="players.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of players</span></a>
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
	  	  
<form action="players_action.php" method="post" id="form-01">
<fieldset>
	<legend>Add player</legend>
	
	<p class="nomt">
	<label for="inp-1" class="req">Name:</label><br />
	<input type="text" size="50" name="name" maxlength="50" class="input-text-02 required" id="inp-1" value="<?php echo $_GET['name']; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Surname:</label><br />
	<input type="text" size="50" name="surname" maxlength="50" class="input-text-02 required" id="inp-2" value="<?php echo $_GET['surname']; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Maiden name:</label><br />
	<input type="text" size="50" name="maiden_name" maxlength="50" class="input-text-02" id="inp-2" value="<?php echo $_GET['maiden_name']; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Name in cyrrilic:</label><br />
	<input type="text" size="50" name="name_cyrrilic" maxlength="255" class="input-text-02" id="inp-2" value="<?php echo $_GET['name_cyrrilic']; ?>" />
	</p>
	 	
	
	<p class="nomt">
	<label for="inp-3" class="req">Gender:</label><br />
  <select id="inp-3" name="gender" class="input-text-02 required">
			  <option value="1"<? echo write_select(1,$_GET['gender']); ?>>male</option>
			  <option value="2"<? echo write_select(2,$_GET['gender']); ?>>female</option>
	</select>
  </p>
	
	<p class="nomt">
	<label for="inp-3" class="req">Nationality:</label><br />
  <select id="inp-3" name="nationality" class="input-text-02 required">
			  <option value="">select nationality</option>
			  <option value="N/A">Unknown</option>
			  <?php
        $query_sub="SELECT * FROM countries ORDER by shortcut ASC";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['shortcut'].'"'.write_select($write_sub['shortcut'],$_GET['nationality']).'>'.$write_sub['shortcut'].' ('.$games->GetActualCountryName($write_sub['id'],ActSeason).')</option>
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
        <option value="'.$write_sub['shortcut'].'"'.write_select($write_sub['shortcut'],$_GET['nationality2']).'>'.$write_sub['shortcut'].' ('.$games->GetActualCountryName($write_sub['id'],ActSeason).')</option>
        ';
        }
        ?>
        </select>
  </p>
	
	 
  <p class="nomt">
	<label for="inp-4" class="req">Birth date:</label><br />
	<input type="text" size="10" name="birth_date" maxlength="10" class="input-text-02" id="inp-4" value="<?php echo $_GET['date_bith']; ?>" /><br />
	<span class="smaller low">e.g. 09.04.1982</span>
	</p>
	
	<p class="nomt">
	<label for="inp-5" class="req">Birth place:</label><br />
	<input type="text" size="50" name="birth_place" maxlength="50" class="input-text-02" id="inp-5" value="<?php echo $_GET['date_bith']; ?>" /><br />
  <span class="smaller low">City, Region/state, Country</span>
   
	</p>
	
	<p class="nomt">
	<label for="inp-6" class="req">Height:</label><br />
	<input type="text" size="5" name="height" maxlength="3" class="input-text-02" id="inp-6" value="<?php echo $_GET['height']; ?>" /><br />
	<span class="smaller low">in centimeters, only number</span>
	</p>
	
	<p class="nomt">
	<label for="inp-7" class="req">Weight:</label><br />
	<input type="text" size="5" name="weight" maxlength="3" class="input-text-02" id="inp-7" value="<?php echo $_GET['weight']; ?>" /><br />
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
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$_GET['id_position']).'>'.$write_sub['name'].' ('.$write_sub['shortcut'].')</option>
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
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$_GET['id_shoot']).'>'.$write_sub['name'].'</option>
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
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$_GET['id_status']).'>'.$write_sub['name'].'</option>
        ';
        }
        ?>
        </select>
  </p>
  
  <p class="nomt">
	<label for="inp-16" class="req">Pictures:</label><br />
	<? show_image_folder_select_box($con,1,$_GET['id_photo_folder'],"id_photo_folder");?>
	</p>
  
  <p class="nomt">
	<label for="inp-11" class="req">NHL Draft information #1:</label><br />
	<span class="label label-02">team_name</span> <input type="text" size="20" name="draft_1_team" maxlength="255" class="input-text" id="inp-11" value="<?php echo $_GET['draft_1_team']; ?>" />&nbsp;
	<span class="label label-02">year</span> <input type="text" size="3" name="draft_1_year" maxlength="4" class="input-text" id="inp-11" value="<?php echo $_GET['draft_1_year']; ?>" />&nbsp;
	<span class="label label-02">round</span> <input type="text" size="3" name="draft_1_round" maxlength="3" class="input-text" id="inp-11" value="<?php echo $_GET['draft_1_round']; ?>" />&nbsp;
	<span class="label label-02">position in the draft</span> <input type="text" size="3" name="draft_1_position" maxlength="4" class="input-text" id="inp-11" value="<?php echo $_GET['draft_1_position']; ?>" />&nbsp;
	</p>
	
	<p class="nomt">
	<label for="inp-12" class="req">NHL Draft information #2:</label><br />
	<span class="label label-02">team_name</span> <input type="text" size="20" name="draft_2_team" maxlength="255" class="input-text" id="inp-12" value="<?php echo $_GET['draft_2_team']; ?>" />&nbsp;
	<span class="label label-02">year</span> <input type="text" size="3" name="draft_2_year" maxlength="4" class="input-text" id="inp-12" value="<?php echo $_GET['draft_2_year']; ?>" />&nbsp;
	<span class="label label-02">round</span> <input type="text" size="3" name="draft_2_round" maxlength="3" class="input-text" id="inp-12" value="<?php echo $_GET['draft_2_round']; ?>" />&nbsp;
	<span class="label label-02">position in the draft</span> <input type="text" size="3" name="draft_2_position" maxlength="4" class="input-text" id="inp-12" value="<?php echo $_GET['draft_2_position']; ?>" />&nbsp;
	</p>
	
	<p class="nomt">
	<label for="inp-13" class="req">NHL Draft information #3:</label><br />
	<span class="label label-02">team_name</span> <input type="text" size="20" name="draft_3_team" maxlength="255" class="input-text" id="inp-13" value="<?php echo $_GET['draft_3_team']; ?>" />&nbsp;
	<span class="label label-02">year</span> <input type="text" size="3" name="draft_3_year" maxlength="4" class="input-text" id="inp-13" value="<?php echo $_GET['draft_3_year']; ?>" />&nbsp;
	<span class="label label-02">round</span> <input type="text" size="3" name="draft_3_round" maxlength="3" class="input-text" id="inp-13" value="<?php echo $_GET['draft_3_round']; ?>" />&nbsp;
	<span class="label label-02">position in the draft</span> <input type="text" size="3" name="draft_3_position" maxlength="4" class="input-text" id="inp-13" value="<?php echo $_GET['draft_3_position']; ?>" />&nbsp;
	</p>
  
  
  
  <p class="nomt">
	<label for="elm1" class="req">More information:</label><br />
	<textarea id="elm1" name="info" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"><?php echo $_GET['info']; ?></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').hide();">[Hide editor]</a>
  		</span>
	
	</p>
	
	<p class="box-01">
			<input type="submit" value="Add new player"  class="input-submit" />
			</p>
	<input type="hidden" name="action" value="add" />
	<input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	<input type="hidden" name="filter2" value="<?php echo $_GET['filter2'];?>" />
	<input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
	<?echo OdkazForm;?>
</fieldset>	
</form>


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