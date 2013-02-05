<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(25,1,1,0,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
require_once("../inc/tinymce.inc");

echo $head->setTitle(langGlogalTitle."Add club");
echo $head->setJavascriptExtendFile("js/functions.js");
echo $head->setJavascriptExtendFile("../inc/js/leagues_box.js");
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
$strAdminMenu="clubs";
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
    
	<h1>Add new club</h1>
    <p class="box">
    	 <a href="clubs.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of clubs</span></a>
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
	  	  
<form action="clubs_action.php" method="post" id="form-01">
<fieldset>
	<legend>Add club</legend>
	
	<p class="nomt">
	<label for="inp-1" class="req">Name:</label><br />
	<input type="text" size="50" name="name" maxlength="255" class="input-text-02 required" id="inp-1" value="<?php echo $_GET['name']; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Short name:</label><br />
  <input type="text" size="50" name="short_name" maxlength="50" class="input-text-02" id="inp-2" value="<?php echo $_GET['short_name']; ?>" />
  <br /><span class="smaller low">for using in fixtures or standings, Sparta for HC Sparta Praha for example</span>
  </p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Nickname:</label><br />
  <input type="text" size="50" name="nickname" maxlength="50" class="input-text-02" id="inp-2" value="<?php echo $_GET['nickname']; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Name in original language:</label><br />
  <input type="text" size="50" name="name_original" maxlength="50" class="input-text-02" id="inp-2" value="<?php echo $_GET['name_original']; ?>" />
  <br /><span class="smaller low">cyrilic etc</span>
	</p>
	
	<p class="nomt">
	<label for="inp-6" class="req">League:</label><br />
  <? show_league_select_box($con,1,$_GET['id_league'],"id_league",$users->getSesid(),$users->getIdPageRight());?>  
  </p>
  
  <p class="nomt">
	<label for="inp-7" class="req">Status:</label><br />
  <select id="inp-7" name="id_status" class="input-text-02 required">
			  <option value="">select status</option>
			  <?php
        $query_sub="SELECT * FROM clubs_status_list ORDER by id";
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
	<label for="inp-10" class="req">Status text info:</label><br />
  <input type="text" size="50" name="id_status_info" maxlength="255" class="input-text-02" id="inp-10" value="<?php echo $_GET['id_status_info']; ?>" />
  <br /><span class="smaller low">fill only if status is set to "not active". Otherwise, the text on the webpage is not showing.</span>
  </p>
  
  <p class="nomt">
	<label for="inp-8" class="req">National team:</label><br />
  <select id="inp-6" name="is_national" class="input-text-02">
        <option value="1" <?php if ($_GET['is_national']==1)  echo 'selected="selected"' ; ?>>club is NOT national team</option>
			  <option value="2" <?php if ($_GET['is_national']==2)  echo 'selected="selected"' ; ?>>club is national team</option>
  </select>
  </p>
	
	<p class="nomt">
	<label for="inp-6" class="req">Country:</label><br />
  <select id="inp-6" name="id_country" class="input-text-02 required">
			  <option value="">select country</option>
			  <?php
        if(!empty($ArSpecialRight[1])) $sqlRightWhere="WHERE ".str_replace("id_country","id",$ArSpecialRight[1]); else $sqlRightWhere="";
        $query_sub="SELECT * FROM countries ".$sqlRightWhere." ORDER by name";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['shortcut'].'"'.write_select($write_sub['shortcut'],$_GET['id_country']).'>'.$games->GetActualCountryName($write_sub['id'],ActSeason).'</option>
        ';
        }
        ?>
        </select>
  </p>
	
	<p class="nomt">
	<label for="inp-4" class="req">Year founded:</label><br />
	<input type="text" size="5" name="year_founded" maxlength="4" class="input-text-02" id="inp-4" value="<?php echo $_GET['year_founded']; ?>" />
	<br /><span class="smaller low">fill only numbers, e.g. 1982</span>
	</p>
	
	<p class="nomt">
	<label for="inp-4" class="req">Address:</label><br />
  <textarea id="inp-4" name="address" cols="70" rows="7" style="width:400px; height:100px" class="input-text-02"><?php echo $_GET['address']; ?></textarea>
	</p>
	
	<p class="nomt">
	<label for="inp-3" class="req">City:</label><br />
	<input type="text" size="50" name="city" maxlength="50" class="input-text-02" id="inp-3" value="<?php echo $_GET['city']; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-5" class="req">Telephone:</label><br />
  <input type="text" size="20" name="telephone" maxlength="40" class="input-text-02" id="inp-5" value="<?php echo $_GET['telephone']; ?>" />
  <br /><span class="smaller low">international format e.g. +420 123 456 789</span>
	</p>
	
	<p class="nomt">
	<label for="inp-6" class="req">Fax:</label><br />
  <input type="text" size="20" name="fax" maxlength="40" class="input-text-02" id="inp-6" value="<?php echo $_GET['fax']; ?>" />
  <br /><span class="smaller low">international format e.g. +420 123 456 789</span>
	</p>
	
	<p class="nomt">
	<label for="inp-7" class="req">Email #1 and note:</label><br />
  <input type="text" size="35" name="email_1" maxlength="50" class="input-text-02" id="inp-7" value="<?php echo $_GET['email_1']; ?>" />
  <b>note:</b><input type="text" size="30" name="email_1_note" maxlength="50" class="input-text-02" id="inp-7" value="<?php echo $_GET['email_1_note']; ?>" />
  <br /><span class="smaller low">e.g. email@email.com and tickets</span>
	</p>
	
	<p class="nomt">
	<label for="inp-7" class="req">Email #2 and note:</label><br />
  <input type="text" size="35" name="email_2" maxlength="50" class="input-text-02" id="inp-7" value="<?php echo $_GET['email_2']; ?>" />
  <b>note:</b><input type="text" size="30" name="email_2_note" maxlength="50" class="input-text-02" id="inp-7" value="<?php echo $_GET['email_2_note']; ?>" />
  <br /><span class="smaller low">e.g. email@email.com and tickets</span>
	</p>
	
	<p class="nomt">
	<label for="inp-7" class="req">Email #3 and note:</label><br />
  <input type="text" size="35" name="email_3" maxlength="50" class="input-text-02" id="inp-7" value="<?php echo $_GET['email_3']; ?>" />
  <b>note:</b><input type="text" size="30" name="email_3_note" maxlength="50" class="input-text-02" id="inp-7" value="<?php echo $_GET['email_3_note']; ?>" />
  <br /><span class="smaller low">e.g. email@email.com and tickets</span>
	</p>
	
	<p class="nomt">
	<label for="inp-3" class="req">Colours:</label><br />
	<input type="text" size="50" name="colours" maxlength="255" class="input-text-02" id="inp-3" value="<?php echo $_GET['colours']; ?>" />
	</p>
	
	<p class="nomt">
	<label for="elm1" class="req">Brief history:</label><br />
	<textarea id="elm1" name="brief_history" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"><?php echo $_GET['brief_history']; ?></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').hide();">[Hide editor]</a>
  		</span>
	
	</p>
	
	<p class="nomt">
	<label for="elm1" class="req">Achievements:</label><br />
	<textarea id="elm2" name="achievments" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"><?php echo $_GET['achievments']; ?></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm2').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm2').hide();">[Hide editor]</a>
  		</span>
	
	</p>
	
	<p class="nomt">
	<label for="elm1" class="req">Team management:</label><br />
	<textarea id="elm3" name="team_management" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"><?php echo $_GET['team_management']; ?></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm3').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm3').hide();">[Hide editor]</a>
  		</span>
	
	</p>
	
		
	<p class="box-01">
			<input type="submit" value="Add new club"  class="input-submit" />
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