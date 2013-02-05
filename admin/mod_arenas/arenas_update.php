<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(27,1,0,1,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
require_once("../inc/tinymce.inc");
echo $head->setTitle(langGlogalTitle."Edit arena");
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
    
	<h1>Edit arena</h1>
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
	
<?php
  if (!empty($_GET['id'])) {
  $query="SELECT * FROM arenas WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
    if (!empty($_GET['name'])) $name=$_GET['name']; else  $name=$write['name'];
    if (!empty($_GET['also_known_as'])) $also_known_as=$_GET['also_known_as']; else $also_known_as=$write['also_known_as'];
    if (!empty($_GET['id_photo_folder'])) $id_photo_folder=$_GET['id_photo_folder']; else $id_photo_folder=$write['id_photo_folder'];
    if (!empty($_GET['id_country'])) $id_country=$_GET['id_country']; else  $id_country=$write['id_country'];
    if (!empty($_GET['address'])) $address=$_GET['address']; else $address=$write['address'];
    if (!empty($_GET['telephone'])) $telephone=$_GET['telephone']; else $telephone=$write['telephone'];
    if (!empty($_GET['fax'])) $fax=$_GET['fax']; else $fax=$write['fax'];
    if (!empty($_GET['email'])) $email=$_GET['email']; else $email=$write['email'];
    if (!empty($_GET['GPS_location'])) $GPS_location=$_GET['GPS_location']; else $GPS_location=$write['GPS_location'];
    if (!empty($_GET['capacity_overall'])) $capacity_overall=$_GET['capacity_overall']; else $capacity_overall=$write['capacity_overall'];
    if (!empty($_GET['capacity_seating'])) $capacity_seating=$_GET['capacity_seating']; else $capacity_seating=$write['capacity_seating'];
    if (!empty($_GET['rink_size'])) $rink_size=$_GET['rink_size']; else $rink_size=$write['rink_size'];
    if (!empty($_GET['year_built'])) $year_built=$_GET['year_built']; else $year_built=$write['year_built'];
    if (!empty($_GET['roofed'])) $roofed=$_GET['roofed']; else $roofed=$write['roofed'];
    if (!empty($_GET['year_roofed'])) $year_roofed=$_GET['year_roofed']; else $year_roofed=$write['year_roofed'];
    if (!empty($_GET['last_major_reconstruction'])) $last_major_reconstruction=$_GET['last_major_reconstruction']; else $last_major_reconstruction=$write['last_major_reconstruction'];
    if (!empty($_GET['also_used_for'])) $also_used_for=$_GET['also_used_for']; else $also_used_for=$write['also_used_for'];
    if (!empty($_GET['most_notable_games'])) $most_notable_games=$_GET['most_notable_games']; else $most_notable_games=$write['most_notable_games'];
    if (!empty($_GET['id_status'])) $id_status=$_GET['id_status']; else $id_status=$write['id_status'];    
    if (!empty($_GET['id_status_info'])) $id_status_info=$_GET['id_status_info']; else $id_status_info=$write['id_status_info'];
    if (!empty($_GET['link_1'])) $link_1=$_GET['link_1']; else $link_1=$write['link_1'];
    if (!empty($_GET['link_2'])) $link_2=$_GET['link_2']; else $link_2=$write['link_2'];
    if (!empty($_GET['link_3'])) $link_3=$_GET['link_3']; else $link_3=$write['link_3'];
  ?>
	  
<form action="arenas_action.php" method="post" id="form-01">
<fieldset>
	<legend>Update arena</legend>
	
	<p class="nomt">
	<label for="inp-1" class="req">Name:</label><br />
	<input type="text" size="50" name="name" maxlength="255" class="input-text-02 required" id="inp-1" value="<?php echo $name; ?>" />
	<div class="fix"></div>

	 <?php
	 echo '<p><span class="label label-01">Alternative names</span>'; 
        if ($users->checkUserRight(2)) echo'  <a href="arenas_names.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Add new alternative name" class="ico-list">add new alternative name</a></p>';
        $query_sub="SELECT * FROM arenas_names WHERE id_arena=".$_GET['id']." ORDER by name";
        echo '<ul style="margin:10px 0px 10px 0px">';
        if ($con->GetQueryNum($query_sub)>0){
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
          echo '<li>';
          if ($users->checkUserRight(3)) echo'<a href="arenas_names_update.php'.Odkaz.'&amp;id='.$write_sub["id"].'&amp;id_club='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item">';
          echo $write_sub['name'];
          if ($users->checkUserRight(3)) echo'</a>';
          if ($users->checkUserRight(3)) echo'&nbsp;&nbsp;<a href="arenas_names_update.php'.Odkaz.'&amp;id='.$write_sub["id"].'&amp;id_club='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '</li>';
        }
        
        }else{
          echo '<li>no alternative name has been found</li>';
        }
        echo '</ul>';
        ?>
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Also known as:</label><br />
  <input type="text" size="50" name="also_known_as" maxlength="255" class="input-text-02" id="inp-2" value="<?php echo $also_known_as; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-16" class="req">Pictures:</label><br />
	<? show_image_folder_select_box($con,1,$id_photo_folder,"id_photo_folder");?>
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
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$id_country).'>'.$games->GetActualCountryName($write_sub['id'],ActSeason).'</option>
        ';
        }
        ?>
        </select>
  </p>
  	
	<p class="nomt">
	<label for="inp-4" class="req">Address:</label><br />
  <textarea id="inp-4" name="address" cols="70" rows="7" style="width:400px; height:100px" class="input-text-02"><?php echo $address; ?></textarea>
	</p>
	
	<p class="nomt">
	<label for="inp-5" class="req">Telephone:</label><br />
  <input type="text" size="20" name="telephone" maxlength="40" class="input-text-02" id="inp-5" value="<?php echo $telephone; ?>" />
  <br /><span class="smaller low">international format e.g. +420 123 456 789</span>
	</p>
	
	<p class="nomt">
	<label for="inp-6" class="req">Fax:</label><br />
  <input type="text" size="20" name="fax" maxlength="40" class="input-text-02" id="inp-6" value="<?php echo $fax; ?>" />
  <br /><span class="smaller low">international format e.g. +420 123 456 789</span>
	</p>
	
	<p class="nomt">
	<label for="inp-7" class="req">Email:</label><br />
  <input type="text" size="50" name="email" maxlength="50" class="input-text-02" id="inp-7" value="<?php echo $email; ?>" />
  <br /><span class="smaller low">e.g. email@email.com</span>
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">GPS location:</label><br />
  <input type="text" size="20" name="GPS_location" maxlength="50" class="input-text-02" id="inp-2" value="<?php echo $GPS_location; ?>" />
  <br /><span class="smaller low"><a onclick="return !window.open(this.href);" href="http://itouchmap.com/latlong.html">how find GPS location ?</a></span>
  
	</p>
	
	<p class="nomt">
	<label for="inp-4" class="req">Capacity overall for icehockey:</label><br />
	<input type="text" size="5" name="capacity_overall" maxlength="5" class="input-text-02" id="inp-4" value="<?php echo $capacity_overall; ?>" />
	<br /><span class="smaller low">fill only numbers, e.g. 18200</span>
	</p>
	
	<p class="nomt">
	<label for="inp-4" class="req">Capacity seating for icehockey:</label><br />
	<input type="text" size="5" name="capacity_seating" maxlength="5" class="input-text-02" id="inp-4" value="<?php echo $capacity_seating; ?>" />
	<br /><span class="smaller low">fill only numbers, e.g. 18200</span>
	</p>
	
	<p class="nomt">
	<label for="inp-5" class="req">Rink size:</label><br />
  <input type="text" size="20" name="rink_size" id="rink_size" maxlength="30" class="input-text-02" id="inp-5" value="<?php echo $rink_size; ?>" /> <a class="ico-arrow-left" href="" onclick="document.getElementById('rink_size').value='60x30';return false;">Set rink size to 60x30</a>
  </p>
	
	<p class="nomt">
	<label for="inp-4" class="req">Year built:</label><br />
	<input type="text" size="5" name="year_built" maxlength="4" class="input-text-02" id="inp-4" value="<?php echo $year_built; ?>" />
	<br /><span class="smaller low">fill only numbers, e.g. 1982</span>
	</p>
	
	<p class="nomt">
	<label for="inp-1" class="req">Roofed:</label><br />
	   <select id="inp-7" name="roofed" class="input-text-02 required">
			  <option value="1" <?php if ($roofed==1) echo ' selected="selected"'; ?>>Yes</option>
			  <option value="2" <?php if ($roofed==2) echo ' selected="selected"'; ?>>No</option>
		</select>
	</p>
	
	<p class="nomt">
	<label for="inp-4" class="req">Year roofed:</label><br />
	<input type="text" size="5" name="year_roofed" maxlength="4" class="input-text-02" id="inp-4" value="<?php echo $year_roofed; ?>" />
	<br /><span class="smaller low">fill only if roofed is set to YES</span>
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Last major reconstruction:</label><br />
  <input type="text" size="50" name="last_major_reconstruction" maxlength="255" class="input-text-02" id="inp-2" value="<?php echo $last_major_reconstruction; ?>" />
	</p>
	
	<p class="nomt">
	<label for="elm1" class="req">Also used for:</label><br />
	<textarea id="elm1" name="also_used_for" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"><?php echo $also_used_for; ?></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').hide();">[Hide editor]</a>
  		</span>
	
	</p>
	
	<p class="nomt">
	<label for="elm2" class="req">Most notable games/events:</label><br />
	<textarea id="elm2" name="most_notable_games" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"><?php echo $most_notable_games; ?></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm2').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm2').hide();">[Hide editor]</a>
  		</span>
	
	</p>
	
	<p class="nomt">
	<label for="inp-8" class="req">Official link #1:</label><br />
  <input type="text" size="50" name="link_1" maxlength="255" class="input-text-02" id="inp-8" value="<?php  echo $link_1; ?>" />
  </p>
	
	<p class="nomt">
	<label for="inp-9" class="req">Official link #2:</label><br />
  <input type="text" size="50" name="link_2" maxlength="255" class="input-text-02" id="inp-9" value="<?php echo $link_2; ?>" />
  </p>
	
	<p class="nomt">
	<label for="inp-10" class="req">Official link #3:</label><br />
  <input type="text" size="50" name="link_3" maxlength="255" class="input-text-02" id="inp-10" value="<?php echo $link_3; ?>" />
  </p>
  
  <p class="nomt">
	<label for="inp-19" class="req">Assigned clubs:</label><br />
	<?php
	 echo '<p>'; 
        $query_sub="SELECT * FROM clubs_arenas_items INNER JOIN clubs ON clubs.id=clubs_arenas_items.id_club WHERE id_arena=".$_GET['id']." ORDER BY name";
        echo '<span class="label label-01">Assigned clubs</span>';
        if ($users->checkUserRight(2)) echo'  <a onclick="return !window.open(this.href);" href="arenas_assign.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign new club" class="ico-list">assign new club</a></p>';
        echo'<ul style="margin:10px 0px 10px 0px">';
        if ($con->GetQueryNum($query_sub)>0){
        $result_sub = $con->SelectQuery($query_sub);
        echo '<li>';
        while($write_sub = $result_sub->fetch_array()){
          echo '<a onclick="return !window.open(this.href);" href="../mod_clubs/clubs.php'.Odkaz.'&amp;filter='.$write_sub["id_club"].'" title="Show club">'.$write_sub['name'].'</a>';
          if ($users->checkUserRight(3)) echo'&nbsp;<a onclick="return !window.open(this.href);"  href="arenas_names_update.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
        }
        echo '</li>';
        
        }else{
          echo '<li>no notable players has been found</li>';
        }
        echo '</ul>';
        ?>
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
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$id_status).'>'.$write_sub['name'].'</option>
        ';
        }
        ?>
        </select>
  </p>
  
  <p class="nomt">
	<label for="inp-10" class="req">Status text info:</label><br />
  <input type="text" size="50" name="id_status_info" maxlength="255" class="input-text-02" id="inp-10" value="<?php echo $id_status_info; ?>" />
  <br /><span class="smaller low">fill only if status is set to "not active". Otherwise, the text on the webpage is not showing.</span>
  </p>
	
	<p class="box-01">
			<input type="submit" value="Edit arena"  class="input-submit" />
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