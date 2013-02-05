<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(27,1,1,0,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
require_once("../inc/tinymce.inc");
echo $head->setTitle(langGlogalTitle."Add arena");
echo $head->setJavascriptExtendFile("js/functions.js");
echo $head->setJavascriptExtendFile("../inc/js/images_folder_box.js");
echo $head->setJavascriptExtendFile("../inc/js/clubs_box.js");
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
    
	<h1>Add new arena</h1>
    <p class="box">
    	 <a href="arenas.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of arenas</span></a>
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
	  	  
<form action="arenas_action.php" method="post" id="form-01">
<fieldset>
	<legend>Add arena</legend>
	
	<p class="nomt">
	<label for="inp-1" class="req">Name:</label><br />
	<input type="text" size="50" name="name" maxlength="255" class="input-text-02 required" id="inp-1" value="<?php echo $_GET['name']; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Also known as:</label><br />
  <input type="text" size="50" name="also_known_as" maxlength="255" class="input-text-02" id="inp-2" value="<?php echo $_GET['also_known_as']; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-16" class="req">Pictures:</label><br />
	<? show_image_folder_select_box($con,1,$_GET['id_photo_folder'],"id_photo_folder");?>
	</p>
	
	<p class="nomt">
	<label for="inp-6" class="req">Country</label><br />
  <select id="inp-6" name="id_country" class="input-text-02 required">
			  <option value="">select country</option>
			  <?php
			  if(!empty($ArSpecialRight[1])) $sqlRightWhere="WHERE ".str_replace("id_country","id",$ArSpecialRight[1]); else $sqlRightWhere="";
        $query_sub="SELECT * FROM countries ".$sqlRightWhere." ORDER by name";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$_GET['id_country']).'>'.$games->GetActualCountryName($write_sub['id'],ActSeason).'</option>
        ';
        }
        ?>
        </select>
  </p>
  
  <p class="nomt">
	<label for="inp-16" class="req">Assign club:</label><br />
	<? show_club_select_box($con,1,$_GET['id_club'],"id_club");?>
	</p>
	
	<p class="nomt">
	<label for="inp-4" class="req">Address:</label><br />
  <textarea id="inp-4" name="address" cols="70" rows="7" style="width:400px; height:100px" class="input-text-02"><?php echo $_GET['address']; ?></textarea>
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
	<label for="inp-7" class="req">Email:</label><br />
  <input type="text" size="50" name="email" maxlength="50" class="input-text-02" id="inp-7" value="<?php echo $_GET['email']; ?>" />
  <br /><span class="smaller low">e.g. email@email.com</span>
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">GPS location:</label><br />
  <input type="text" size="20" name="GPS_location" maxlength="50" class="input-text-02" id="inp-2" value="<?php echo $_GET['GPS_location']; ?>" />
  <br /><span class="smaller low"><a onclick="return !window.open(this.href);" href="http://itouchmap.com/latlong.html">how find GPS location ?</a></span>
	</p>
	
	<p class="nomt">
	<label for="inp-4" class="req">Capacity overall for icehockey:</label><br />
	<input type="text" size="5" name="capacity_overall" maxlength="5" class="input-text-02" id="inp-4" value="<?php echo $_GET['capacity_overall']; ?>" />
	<br /><span class="smaller low">fill only numbers, e.g. 18200</span>
	</p>
	
	<p class="nomt">
	<label for="inp-4" class="req">Capacity seating for icehockey:</label><br />
	<input type="text" size="5" name="capacity_seating" maxlength="5" class="input-text-02" id="inp-4" value="<?php echo $_GET['capacity_seating']; ?>" />
	<br /><span class="smaller low">fill only numbers, e.g. 18200</span>
	</p>
	
	<p class="nomt">
	<label for="inp-5" class="req">Rink size:</label><br />
  <input type="text" size="20" name="rink_size" id="rink_size" maxlength="30" class="input-text-02" id="inp-5" value="<?php echo $_GET['rink_size']; ?>" /> <a class="ico-arrow-left" href="" onclick="document.getElementById('rink_size').value='60x30';return false;">Set rink size to 60x30</a>
  </p>
	
	<p class="nomt">
	<label for="inp-4" class="req">Year built:</label><br />
	<input type="text" size="5" name="year_built" maxlength="4" class="input-text-02" id="inp-4" value="<?php echo $_GET['year_built']; ?>" />
	<br /><span class="smaller low">fill only numbers, e.g. 1982</span>
	</p>
	
	<p class="nomt">
	<label for="inp-1" class="req">Roofed:</label><br />
	   <select id="inp-7" name="roofed" class="input-text-02 required">
			  <option value="1" <?php if ($_GET['roofed']==1) echo ' selected="selected"'; ?>>Yes</option>
			  <option value="2" <?php if ($_GET['roofed']==2) echo ' selected="selected"'; ?>>No</option>
		</select>
	</p>
	
	<p class="nomt">
	<label for="inp-4" class="req">Year roofed:</label><br />
	<input type="text" size="5" name="year_roofed" maxlength="4" class="input-text-02" id="inp-4" value="<?php echo $_GET['year_roofed']; ?>" />
	<br /><span class="smaller low">fill only if roofed is set to YES</span>
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Last major reconstruction:</label><br />
  <input type="text" size="50" name="last_major_reconstruction" maxlength="255" class="input-text-02" id="inp-2" value="<?php echo $_GET['last_major_reconstruction']; ?>" />
	</p>
	
	<p class="nomt">
	<label for="elm1" class="req">Also used for:</label><br />
	<textarea id="elm1" name="also_used_for" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"><?php echo $_GET['also_used_for']; ?></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').hide();">[Hide editor]</a>
  		</span>
	
	</p>
	
	<p class="nomt">
	<label for="elm2" class="req">Most notable games/events:</label><br />
	<textarea id="elm2" name="most_notable_games" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"><?php echo $_GET['most_notable_games']; ?></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm2').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm2').hide();">[Hide editor]</a>
  		</span>
	
	</p>
	
	<p class="nomt">
	<label for="inp-8" class="req">Official link #1:</label><br />
  <input type="text" size="50" name="link_1" maxlength="255" class="input-text-02" id="inp-8" value="<?php  echo $_GET['link_1']; ?>" />
  </p>
	
	<p class="nomt">
	<label for="inp-9" class="req">Official link #2:</label><br />
  <input type="text" size="50" name="link_2" maxlength="255" class="input-text-02" id="inp-9" value="<?php echo $_GET['link_2']; ?>" />
  </p>
	
	<p class="nomt">
	<label for="inp-10" class="req">Official link #3:</label><br />
  <input type="text" size="50" name="link_3" maxlength="255" class="input-text-02" id="inp-10" value="<?php echo $_GET['link_3']; ?>" />
  </p>
   
   
  <p class="nomt">
	<label for="inp-7" class="req">Status</label><br />
  <select id="inp-7" name="id_status" class="input-text-02 required">
			  <option value="">select status</option>
			  <?php
        $query_sub="SELECT * FROM arenas_status_list ORDER by id";
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
	
	
	<p class="box-01">
			<input type="submit" value="Add new arena"  class="input-submit" />
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