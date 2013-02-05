<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(4,1,0,1,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
require_once("../inc/CLEditor.inc");
echo $head->setTitle(langGlogalTitle."Edit static text");
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
$strAdminMenu="static_texts";
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
    
	<h1>Edit text</h1>
    <p class="box">
    	 <a href="static_texts.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of texts</span></a>
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
  $query="SELECT * FROM static_texts WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
    if (!empty($_GET['header'])) $header=$_GET['header']; else  $header=$write['header'];
    if (!empty($_SESSION['edited_text'])) $text=$_SESSION['edited_text']; else $text=$write['text'];
    if (!empty($_GET['show_item'])) $show_item=$_GET['show_item']; else $show_item=$write['show_item'];
    if (!empty($_GET['keywords'])) $keywords=$_GET['keywords']; else $keywords=$write['keywords'];
    $boolAds=$write['is_ads'];
    $text=stripcslashes($text);
  ?>
	  
<form action="static_texts_action.php" method="post" id="form-01">
<fieldset>
	<legend>Update news</legend>
	
	<p class="nomt">
	<label for="inp-1" class="req">Description:</label><br />
	<input type="text" size="50" name="header" maxlength="255" class="input-text-02 required" id="inp-1" value="<?php echo $header; ?>" />
	<br /><span class="smaller low">short descripton to identified text</span>
	</p>
	
	<p class="nomt">
	<label for="elm1" class="req">Text:</label><br />
	<textarea <?php if (empty($boolAds)) echo 'id="elm1"'; ?> name="text" cols="70" rows="7" style="width:880px; height:350px" class="input-text-02"><?php echo $text; ?></textarea>
	</p><br />
	
	<p class="nomt">
	         <label for="inp-2" class="req">Keywords:</label><br />
	         <input type="text" size="50" name="keywords" maxlength="255" class="input-text-02 required" id="inp-2" value="<?php echo $keywords; ?>" /><br />
	         <span class="smaller low">keywords separated by a space</span>
	       </p>
	
	
	<p class="nomt">
	<label for="inp-7" class="req">Publishing:</label><br />
  <select id="inp-7" name="show_item" class="input-text-02 required">
			  <?php
			  if ($users->GetUserGroup($users->getIdUser())<>3){
			   $sqlWhere=" WHERE id=2";
        }
			  $query_sub="SELECT * FROM show_status_list ".$sqlWhere." ORDER by id";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$show_item).'>'.$write_sub['name'].'</option>
        ';
        }
        ?>
        </select>
  </p>
	
	
	<p class="box-01">
			<input type="submit" value="Edit text"  class="input-submit" />
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