<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(24,1,0,1,0);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Edit stage");
echo $head->setJavascriptExtendFile("js/functions.js");
require_once("../inc/tinymce.inc");
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
    
	<h1>Edit stages</h1>
    <p class="box">
    	 <a href="leagues_stages.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>&amp;id=<?echo $_GET['id_league'];?>"  class="btn-list"><span>back to list of alternative names</span></a>
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
  $query="SELECT * FROM games_stages WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
    if (!empty($_GET['name'])) $name=$_GET['name']; else $name=$write['name'];
    if (!empty($_GET['description'])) $description=$_GET['description']; else $description=$write['description'];
    
  ?>
	  
<form action="leagues_action.php" method="post" id="form-01">
<fieldset>
	<legend>Edit stage</legend>
	
	
	<p class="nomt">
	<label for="inp-2" class="req">Name:</label><br />
	<input type="text" size="50" name="name" maxlength="255" class="input-text-02 required" id="inp-2" value="<?php echo $name; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-1" class="req">Standings:</label><br />
	<input type="text" size="50" name="description" maxlength="255" class="input-text-02" id="inp-2" value="<?php echo $description; ?>" />
	</p>
	
	
	<p class="box-01">
			<input type="submit" value="Edit stage"  class="input-submit" />
	</p>
	<input type="hidden" name="action" value="stage_update" />
	<input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />
	<input type="hidden" name="id_league" value="<?php echo $_GET['id_league']?>" />
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