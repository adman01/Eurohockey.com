<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(23,1,0,1,0);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Edit country");
echo $head->setJavascriptExtendFile("js/functions.js");
require_once("../inc/tinymce.inc");
echo $head->setJavascriptExtendFile("../inc/js/images_box.js");
//require_once("../inc/lightwindow.inc");
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
$strAdminMenu="users";
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
    
	<h1>Edit country</h1>
    <p class="box">
    	 <a href="countries.php<?echo Odkaz;?>"  class="btn-list"><span>back to list of countries</span></a>
	  </p>
	  
	  
	  <? 
	  	if (!empty($_GET['message'])){
	  	echo '<p class="msg error">';
			switch ($_GET['message']){
			 case 1:
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
  $query="SELECT * FROM countries WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
    if (!empty($_GET['name'])) $name=$_GET['name']; else  $name=$write['name'];
    if (!empty($_GET['shortcut'])) $shortcut=$_GET['shortcut']; else $shortcut=$write['shortcut'];
    if (!empty($_GET['image_flag'])) $image_flag=$_GET['image_flag']; else $image_flag=$write['image_flag'];
    if (!empty($_GET['image_asociation_logo'])) $image_asociation_logo=$_GET['image_asociation_logo']; else $image_asociation_logo=$write['image_asociation_logo'];
    if (!empty($_GET['hockey_asociation'])) $hockey_asociation=$_GET['hockey_asociation']; else $hockey_asociation=$write['hockey_asociation'];
    if (!empty($_GET['address'])) $address=$_GET['address']; else $address=$write['address'];
    if (!empty($_GET['telephone'])) $telephone=$_GET['telephone']; else $telephone=$write['telephone'];
    if (!empty($_GET['fax'])) $fax=$_GET['fax']; else $fax=$write['fax'];
    if (!empty($_GET['email'])) $email=$_GET['email']; else $email=$write['email'];
    if (!empty($_GET['link_1'])) $link_1=$_GET['link_1']; else $link_1=$write['link_1'];
    if (!empty($_GET['link_2'])) $link_2=$_GET['link_2']; else $link_2=$write['link_2'];
    if (!empty($_GET['link_3'])) $link_3=$_GET['link_3']; else $link_3=$write['link_3'];
    if (!empty($_GET['year_founded'])) $year_founded=$_GET['year_founded']; else $year_founded=$write['year_founded'];
    if (!empty($_GET['year_incorporated'])) $year_incorporated=$_GET['year_incorporated']; else $year_incorporated=$write['year_incorporated'];
    if (!empty($_GET['brief_history'])) $brief_history=$_GET['brief_history']; else $brief_history=$write['brief_history'];
    if (!empty($_GET['best_achievments'])) $best_achievments=$_GET['best_achievments']; else $best_achievments=$write['best_achievments'];
    if (!empty($_GET['registered_players'])) $registered_players=$_GET['registered_players']; else $registered_players=$write['registered_players'];
    if (!empty($_GET['placement_at_IHWC'])) $placement_at_IHWC=$_GET['placement_at_IHWC']; else $placement_at_IHWC=$write['placement_at_IHWC'];
    if (!empty($_GET['world_ranking'])) $world_ranking=$_GET['world_ranking']; else $world_ranking=$write['world_ranking'];
    
  ?>
	  
<form action="countries_action.php" method="post" id="form-01">
<fieldset>
	<legend>Edit country</legend>
	
	<p class="nomt">
	<label for="inp-1" class="req">Name:</label><br />
	<input type="text" size="50" name="name" maxlength="255" class="input-text-02 required" id="inp-1" value="<?php echo $name; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Shortcut</label><br />
  <input type="text" size="10" name="shortcut" maxlength="3" class="input-text-02 required" id="inp-2" value="<?php echo $shortcut; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-15" class="req">Flag:</label><br />
  <? show_image_select_box($con,0,$image_flag,"image_flag");?>
	</p>
	
	<p class="nomt">
	<label for="inp-3" class="req">Name of the ice hockey asociation:</label><br />
	<input type="text" size="50" name="hockey_asociation" maxlength="255" class="input-text-02" id="inp-3" value="<?php echo $hockey_asociation; ?>" />
	
	</p>
	
	<p class="nomt">
	<label for="inp-16" class="req">Hockey asociation logo:</label><br />
  <? show_image_select_box($con,1,$image_asociation_logo,"image_asociation_logo");?>
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
	<label for="inp-8" class="req">Official link #1:</label><br />
  <input type="text" size="50" name="link_1" maxlength="50" class="input-text-02" id="inp-8" value="<?php  echo $link_1; ?>" />
  </p>
	
	<p class="nomt">
	<label for="inp-9" class="req">Official link #2:</label><br />
  <input type="text" size="50" name="link_2" maxlength="50" class="input-text-02" id="inp-9" value="<?php echo $link_2; ?>" />
  </p>
	
	<p class="nomt">
	<label for="inp-10" class="req">Official link #3:</label><br />
  <input type="text" size="50" name="link_3" maxlength="50" class="input-text-02" id="inp-10" value="<?php echo $link_3; ?>" />
  </p>
	
	<p class="nomt">
	<label for="inp-11" class="req">Year founded:</label><br />
  <input type="text" size="5" name="year_founded" maxlength="4" class="input-text-02" id="inp-11" value="<?php echo $year_founded; ?>" />
  <br /><span class="smaller low">fill only numbers, e.g. 1982</span>
	</p>
	
	<p class="nomt">
	<label for="inp-12" class="req">Year incorporated to IIHF:</label><br />
  <input type="text" size="5" name="year_incorporated" maxlength="4" class="input-text-02" id="inp-12" value="<?php echo $year_incorporated; ?>" />
  <br /><span class="smaller low">fill only numbers, e.g. 1982</span>
	</p>
		
	<p class="nomt">
	<label for="inp-13" class="req">Brief history:</label><br />
  <textarea id="elm1" name="brief_history" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"><?php echo $brief_history; ?></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').hide();">[Hide editor]</a>
  		</span>
	</p>
	
	<p class="nomt">
	<label for="inp-14" class="req">Best achievments:</label><br />
  <textarea id="elm2" name="best_achievments" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"><?php echo $best_achievments; ?></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm2').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm2').hide();">[Hide editor]</a>
  		</span>
	</p>
	
	<p class="nomt">
	<label for="inp-17" class="req">Number of registered players:</label><br />
  <input type="text" size="5" name="registered_players" maxlength="6" class="input-text-02" id="inp-17" value="<?php echo $registered_players; ?>" />
  <br /><span class="smaller low">fill only numbers, e.g. 12456</span>
	</p>
	
	<p class="nomt">
	<label for="inp-18" class="req">Placement at IHWC:</label><br />
  <input type="text" size="5" name="placement_at_IHWC" maxlength="15" class="input-text-02" id="inp-18" value="<?php echo $placement_at_IHWC; ?>" />
  </p>
	
	<p class="nomt">
	<label for="inp-19" class="req">World ranking:</label><br />
  <input type="text" size="5" name="world_ranking" maxlength="4" class="input-text-02" id="inp-19" value="<?php echo $world_ranking; ?>" />
  <br /><span class="smaller low">fill only numbers, e.g. 15</span>
	
	</p>
	
	<p class="nomt">
	<label for="inp-19" class="req">Most notable players:</label><br />
	<?php
	 echo '<p>'; 
        if ($users->checkUserRight(2)) echo'  <a onclick="return !window.open(this.href);" href="countries_players.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign new player" class="ico-list">assign new player</a></p>';
        $query_sub="SELECT countries_players_items.id_player as id,name,surname FROM countries_players_items INNER JOIN players ON players.id=countries_players_items.id_player WHERE id_country=".$_GET['id']." ORDER BY surname";
        echo '<span class="label label-01">Assigned most notable players</span><ul style="margin:10px 0px 10px 0px">';
        if ($con->GetQueryNum($query_sub)>0){
        $result_sub = $con->SelectQuery($query_sub);
        echo '<li>';
        while($write_sub = $result_sub->fetch_array()){
          echo '<a onclick="return !window.open(this.href);" href="../mod_players/players.php'.Odkaz.'&amp;filter='.$write_sub["id"].'" title="Show player">'.$write_sub['name'].' '.$write_sub['surname'].'</a>';
          if ($users->checkUserRight(3)) echo'&nbsp;<a onclick="return !window.open(this.href);"  href="countries_players.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
        }
        echo '</li>';
        
        }else{
          echo '<li>no notable players has been found</li>';
        }
        echo '</ul>';
        ?>
	</p>
	
	<p class="box-01">
			<input type="submit" value="Edit country"  class="input-submit" />
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