<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(24,1,0,1,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Edit league");
echo $head->setJavascriptExtendFile("js/functions.js");
require_once("../inc/tinymce.inc");
echo $head->setJavascriptExtendFile("../inc/js/images_box.js");
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

function show_box() {
    if (document.getElementById("youth_box").style.display== 'none'){
       document.getElementById("youth_box").style.display = '';
    }
    else
    {
      document.getElementById("youth_box").style.display = 'none';
    }
}

</script>
<?php
echo $head->setEndHead();
$strAdminMenu="leagues";
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
    
	<h1>Edit league</h1>
    <p class="box">
    	 <a href="leagues.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of leagues</span></a>
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
  $query="SELECT * FROM leagues WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
    if (!empty($_GET['name'])) $name=$_GET['name']; else  $name=$write['name'];
    if (!empty($_GET['id_order'])) $id_order=$_GET['id_order']; else  $id_order=$write['id_order'];
    if (!empty($_GET['shortcut'])) $shortcut=$_GET['shortcut']; else $shortcut=$write['shortcut'];
    if (!empty($_GET['name_short'])) $name_short=$_GET['name_short']; else $name_short=$write['name_short'];
    if (!empty($_GET['id_image'])) $id_image=$_GET['id_image']; else $id_image=$write['id_image'];
    if (!empty($_GET['english_name'])) $english_name=$_GET['english_name']; else $english_name=$write['english_name'];
    if (!empty($_GET['administered_by'])) $administered_by=$_GET['administered_by']; else $administered_by=$write['administered_by'];
    if (!empty($_GET['head_manager'])) $head_manager=$_GET['head_manager']; else $head_manager=$write['head_manager'];
    if (!empty($_GET['link_1'])) $link_1=$_GET['link_1']; else $link_1=$write['link_1'];
    if (!empty($_GET['link_2'])) $link_2=$_GET['link_2']; else $link_2=$write['link_2'];
    if (!empty($_GET['link_3'])) $link_3=$_GET['link_3']; else $link_3=$write['link_3'];
    if (!empty($_GET['league_format'])) $league_format=$_GET['league_format']; else $league_format=$write['league_format'];
    if (!empty($_GET['promotion'])) $promotion=$_GET['promotion']; else $promotion=$write['promotion'];
    if (!empty($_GET['playoff'])) $playoff=$_GET['playoff']; else $playoff=$write['playoff'];
    if (!empty($_GET['year_of_start'])) $year_of_start=$_GET['year_of_start']; else $year_of_start=$write['year_of_start'];
    if (!empty($_GET['brief_history'])) $brief_history=$_GET['brief_history']; else $brief_history=$write['brief_history'];
    if (!empty($_GET['youth_league'])) $youth_league=$_GET['youth_league']; else $youth_league=$write['youth_league'];
    if (!empty($_GET['youth_league_id'])) $youth_league_id=$_GET['youth_league_id']; else $youth_league_id=$write['youth_league_id'];
    if (!empty($_GET['league_status'])) $league_status=$_GET['league_status']; else $league_status=$write['league_status'];
    if (!empty($_GET['is_tournament'])) $is_tournament=$_GET['is_tournament']; else $is_tournament=$write['is_tournament'];
  ?>
	  
<form action="leagues_action.php" method="post" id="form-01">
<fieldset>
	<legend>Edit league</legend>
	
	<p class="nomt">
	<label for="inp-1" class="req">Name:</label><br />
	<input type="text" size="50" name="name" maxlength="255" class="input-text-02 required" id="inp-1" value="<?php echo $name; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-1" class="req">Short name:</label><br />
	<input type="text" size="50" name="name_short" maxlength="255" class="input-text-02" id="inp-1" value="<?php echo $name_short; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Shortcut</label><br />
  <input type="text" size="5" name="shortcut" maxlength="3" class="input-text-02" id="inp-2" value="<?php echo $shortcut; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-1" class="req">English name:</label><br />
	<input type="text" size="50" name="english_name" maxlength="255" class="input-text-02" id="inp-1" value="<?php echo $english_name; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-1" class="req">League level:</label><br />
	<input type="text" size="2" name="id_order" maxlength="3" class="input-text-02" id="inp-1" value="<?php echo $id_order; ?>" /><br />
	<span class="smaller low">League level - insert as a number (1 is the top division, higher number represents lower league)</span>
	</p>
	
	
	<p class="nomt">
	<label for="inp-16" class="req">League logo:</label><br />
  <? show_image_select_box($con,1,$id_image,"id_image");?>
	</p>
  
  <p class="nomt">
	<label for="inp-1" class="req">Administered by:</label><br />
	<input type="text" size="50" name="administered_by" maxlength="255" class="input-text-02" id="inp-1" value="<?php echo $administered_by; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-1" class="req">League head manager:</label><br />
	<input type="text" size="50" name="head_manager" maxlength="50" class="input-text-02" id="inp-1" value="<?php echo $head_manager; ?>" />
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
	<label for="inp-14" class="req">League format:</label><br />
  <textarea id="elm1" name="league_format" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"><?php echo $league_format; ?></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').hide();">[Hide editor]</a>
  		</span>
	</p>
	
	<p class="nomt">
	<label for="inp-14" class="req">Promotion / relegation rules:</label><br />
  <textarea id="elm2" name="promotion" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"><?php echo $promotion; ?></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm2').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm2').hide();">[Hide editor]</a>
  		</span>
	</p>
	
	<p class="nomt">
	<label for="inp-10" class="req">Tournament:</label><br />
  <select id="inp-6" name="is_tournament" class="input-text-02">
			  <option value="1" <?php if ($is_tournament==1)  echo 'selected="selected"' ; ?>>league is NOT tournament</option>
			  <option value="2" <?php if ($is_tournament==2)  echo 'selected="selected"' ; ?>>league is tournament</option>
  </select>
  </p>
	
	<p class="nomt">
	<label for="inp-1" class="req">Play-off:</label><br />
	 <select name="playoff">
	     <option value="1"<?php if ($playoff==1) echo ' selected="selected"'; ?>>Yes</option>
	     <option value="0"<?php if (empty($playoff)) echo ' selected="selected"'; ?>>No</option>
    </select>
	</p>
	
	<p class="nomt">
	<label for="inp-1" class="req">Year of start:</label><br />
	<input type="text" size="5" name="year_of_start" maxlength="4" class="input-text-02" id="inp-1" value="<?php echo $year_of_start; ?>" />
	<br /><span class="smaller low">fill only numbers, e.g. 1982</span>
	</p>
	
	<p class="nomt">
	<label for="inp-14" class="req">Brief history:</label><br />
  <textarea id="elm3" name="brief_history" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"><?php echo $brief_history; ?></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm3').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm3').hide();">[Hide editor]</a>
  		</span>
	</p>
	
	<p class="nomt">
	<label for="inp-1" class="req">Youth league:</label><br />
	 <input type="checkbox" name="youth_league" onclick="show_box()" value="1"<?php if ($youth_league==1) echo ' checked="checked"'; ?> />
	</p>
	
	<p class="nomt" id="youth_box" <?php if (empty($youth_league)) echo ' style="display:none"'; ?>>
	<label for="inp-2" class="req">Youth category</label><br />
  <select name="youth_league_id" class="input-text-02">
			  <?php
        $query_sub="SELECT * FROM leagues_youth_list ORDER by name";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$youth_league_id).'>'.$write_sub['name'].'</option>
        ';
        }
        ?>
        </select>
  </p>
  
  <p class="nomt">
	<label for="inp-2" class="req">League status</label><br />
  <select name="league_status" class="input-text-02 required">
			  <option value="">select status</option>
			  <?php
        $query_sub="SELECT * FROM leagues_status_list ORDER by name";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$league_status).'>'.$write_sub['name'].'</option>
        ';
        }
        ?>
  </select>
  </p>
	
	<p class="box-01">
			<input type="submit" value="Edit league"  class="input-submit" />
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