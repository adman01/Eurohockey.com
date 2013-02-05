<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(3,1,0,1,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
require_once("../inc/CLEditor.inc");
echo $head->setTitle(langGlogalTitle."Edit polls");
echo $head->setJavascriptExtendFile("js/functions.js");
echo $head->setJavascriptExtendFile("../inc/js/images_folder_box.js");
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
</script>
<?php
echo $head->setEndHead();
$strAdminMenu="polls";
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
    
	<h1>Edit poll</h1>
    <p class="box">
    	 <a href="polls.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of polls</span></a>
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
  $query="SELECT * FROM poll WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
    if (!empty($_GET['question'])) $question=$_GET['question']; else  $question=$write['name'];
    if (!empty($_GET['active'])) $active=$_GET['active']; else $active=$write['active'];
    if (!empty($_GET['date_time'])) {$date_time=$_GET['date_time'];} else {
      if ($write['date_time']!='0000-00-00 00:00:00'){
        $date_time=$write['date_time'];
      }else{
        $date_time="";
      }
    }
    if (!empty($_GET['pub_date_time'])) {$pub_date_time=$_GET['pub_date_time'];} else {
      if ($write['pub_date']!='0000-00-00 00:00:00'){
        $pub_date_time=$write['pub_date'];
      }else{
        $pub_date_time="";
      }
    }
    if (!empty($_GET['expire_date_time'])) {$expire_date_time=$_GET['expire_date_time'];} else {
      if ($write['expire_date']!='0000-00-00 00:00:00'){
        $expire_date_time=$write['expire_date'];
      }else{
        $expire_date_time="";
      }
    }
  ?>
	  
<form action="polls_action.php" method="post" id="form-01">
<fieldset>
	<legend>Update poll</legend>
	
	<p class="nomt">
	<label for="inp-1" class="req">Question:</label><br />
	<input type="text" size="50" name="question" maxlength="255" class="input-text-02 required" id="inp-1" value="<?php echo $question; ?>" />
	</p>
	
	<p class="nomt">
	<label for="elm1" class="req">Date and time:</label><br />
	 <input type="text" size="8" name="date" maxlength="15" class="input-text-02 required" id="datum1" value="<?php if (!empty($date_time)) echo date("d.m.Y",strtotime($date_time)); ?>" />
	 <input type="text" size="3" name="time" maxlength="15" class="input-text-02 required" id="inp-1" value="<?php if (!empty($date_time)) echo date("H:i",strtotime($date_time)) ?>" />
	</p>
	
	<p class="nomt">
	<label for="elm1" class="req">Pub date:</label><br />
	 <input type="text" size="8" name="pub_date" maxlength="15" class="input-text-02 required" id="datum2" value="<?php if (!empty($pub_date_time)) echo date("d.m.Y",strtotime($pub_date_time)); ?>" />
	 <input type="text" size="3" name="pub_time" maxlength="15" class="input-text-02 required" id="inp-1" value="<?php if (!empty($pub_date_time)) echo date("H:i",strtotime($pub_date_time)) ?>" />
	 <br /><span class="smaller low">date and time when news will be automatically published</span>
	</p>
	
	<p class="nomt">
	<label for="elm1" class="req">Expire date:</label><br />
	 <input type="text" size="8" name="expire_date" maxlength="15" class="input-text-02" id="datum3" value="<?php if (!empty($expire_date_time)) echo date("d.m.Y",strtotime($expire_date_time)); ?>" />
	 <input type="text" size="3" name="expire_time" maxlength="15" class="input-text-02" id="inp-1" value="<?php if (!empty($expire_date_time)) echo date("H:i",strtotime($expire_date_time)) ?>" />
	 <br /><span class="smaller low">date and time when news will be automatically disabled</span>
	</p>
	<br />
	
	
	<p class="box-01">
			<input type="submit" value="Edit article"  class="input-submit" />
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

<!-- Datedit by Ivo Skalicky - ITPro CZ - http://www.itpro.cz -->
  <link rel="stylesheet" href="../inc/datedit/datedit.css" type="text/css" media="screen" />
  <script type="text/javascript" charset="iso-8859-1" src="../inc/datedit/datedit.js"></script>
  <script type="text/javascript" charset="utf-8" src="../inc/datedit/lang/cz.js"></script>
  <script type="text/javascript">
    datedit("datum1","d.m.yyyy");
    datedit("datum2","d.m.yyyy");
    datedit("datum3","d.m.yyyy");
  </script>

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