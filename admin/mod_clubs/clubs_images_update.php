<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(25,1,0,1,0);
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("../inc/js/images_box.js");
require_once("../inc/lightwindow.inc");
echo $head->setTitle(langGlogalTitle."Edit image assign");
echo $head->setJavascriptExtendFile("js/functions.js");
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

<?php
  if (!empty($_GET['id'])) {
  $query="SELECT name,id FROM clubs WHERE id=".$_GET['id_club'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  $defaultName=$write['name'];
  $defaultID=$write['id'];
  }
  ?>

	<h1>Edit image assign</h1>
    <p class="box">
    	 <a href="clubs_images.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>&amp;id=<?echo $_GET['id_club'];?>"  class="btn-list"><span>back to list of alternative names</span></a>
    	 <a href="clubs_update.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>&amp;id=<?echo $defaultID;?>"  class="btn-list"><span>back to editing default informations about "<? echo $defaultName;?>"</span></a>
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
  $query="SELECT * FROM clubs_images WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
    if (!empty($_GET['id_season'])) $id_season=$_GET['id_season']; else $id_season=$write['int_year'];
    if (!empty($_GET['id_image'])) $id_image=$_GET['id_image']; else $id_image=$write['id_image'];
    
  ?>    
	  
<form action="clubs_action.php" method="post" id="form-01">
<fieldset>
	<legend>Edit image assign</legend>
	
	<p class="nomt">
	<label for="inp-1" class="req">Season:</label><br />
	<select name="id_season" class="input-text-02 required" id="inp-1">
	        <?php 
          for ($i=(ActSeason+1);$i>=1900;$i--) { 
	         echo '<option value="'.$i.'"'.write_select($i,$id_season).'>'.($i-1).'/'.($i).'</option>';
	        } 
          ?>
	  </select>    
	</p>
	
  <p class="nomt">
	<label for="inp-2" class="req">Image name:</label><br />
	<? show_image_select_box($con,0,$id_image,"id_image");?>
	</p>
	
	<p class="box-01">
			<input type="submit" value="Edit image assign"  class="input-submit" />
			</p>
	<input type="hidden" name="action" value="image_update" />
	<input type="hidden" name="id" value="<?php echo $write['id'];?>" />
	<input type="hidden" name="id_club" value="<?php echo $_GET['id_club']?>" />
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
