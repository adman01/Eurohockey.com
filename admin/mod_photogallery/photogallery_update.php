<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(10,1,0,1,0);
require_once("inc/head.inc");
echo '
    <link rel="stylesheet" type="text/css" href="/inc/lightwindow/css/lightwindow.css" />
		<script type="text/javascript" src="/inc/lightwindow/javascript/prototype.js"></script>
		<script type="text/javascript" src="/inc/lightwindow/javascript/effects.js"></script>
		<script type="text/javascript" src="/inc/lightwindow/javascript/lightwindow.js"></script>
';
echo $head->setTitle(langGlogalTitle."Edit picture");
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
$strAdminMenu="photogallery";
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
    
	<h1>Edit picture</h1>
    <p class="box">
    	 <a href="photogallery.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of pictures</span></a>
	  </p>
	  
	  
	  <? 
	  	if (!empty($_GET['message'])){
	  	echo '<div class="msg error">';
			switch ($_GET['message']){
			 case 1:
          echo 'database error';
        break;
        case 2:
          echo '<ul>'.$_GET['strErrorText'].'</ul>';
        break;
        case 99:
          echo 'wrong or missing input data';
        break;
      }
      echo '</div>';
      }
      ?>
    
    <div class="msg info error" style="display:none;">
      <span></span>.
    </div>
	
<?php
  if (!empty($_GET['id'])) {
  $query="SELECT * FROM photo WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
    if (!empty($_GET['description'])) $description=$_GET['description']; else  $description=$write['description'];
    if (!empty($_GET['keywords'])) $keywords=$_GET['keywords']; else $keywords=$write['keywords'];
    if (!empty($_GET['id_photo_folder'])) $id_photo_folder=$_GET['id_photo_folder']; else $id_photo_folder=$write['id_photo_folder'];
  ?>

<form action="photogallery_action.php" method="post" enctype="multipart/form-data" id="form-01">	  
<fieldset>
	<legend>Edit picture</legend>
	
	     <h3 class="tit">
			    Picture: <a class="lightwindow page-options ico-show" rel="Pictures[]" href="http://<? echo $_SERVER["SERVER_NAME"].'/'.PhotoFolder.'/'.$write['file_name'];?>"><? echo $write['file_name']; ?></a>
			  </h3>
        
        <p class="nomt">
	       <a href="javascript:toggle('upload');" class="btn-create"><span>Change picture file</span></a>
	       <div class="fix"></div>
	       <div id="upload" class="box-01" style="display:none;">
	         	<p class="nom"><input type="file" size="90" name="filename[0]" class="input-text" /></p>
	       </div>
	       <br />
	       </p>
        
         <p class="nomt">
	         <label for="inp-1" class="req">Description:</label><br />
	         <input type="text" size="50" name="description" maxlength="255" class="input-text-02 required" id="inp-1" value="<? echo $description; ?>" /><br />
	         <span class="smaller low">short picture description</span>
	       </p>
	       
	       <p class="nomt">
	         <label for="inp-2" class="req">Keywords:</label><br />
	         <input type="text" size="50" name="keywords" maxlength="255" class="input-text-02 required" id="inp-2" value="<? echo $keywords; ?>" /><br />
	         <span class="smaller low">Keywords separated by a space. To link a foto to a player profile, insert "player_" and playerID (<a target="_blank" href="../mod_players/players.php'.Odkaz.'">you can find ID here</a>), eg. for Jaromir Jagr insert "player_10566".</span>
	       </p>
	       
	       	       
	       <p class="nomt">
	       <label for="inp-3" class="req">Picture folder:</label><br />
         <select id="inp-3" name="id_photo_folder" class="input-text-02">
			     <option value="0">not selected</option>
			     <?
			     $query_sub="SELECT * FROM photo_folder ORDER by id DESC";
           $result_sub = $con->SelectQuery($query_sub);
           while($write_sub = $result_sub->fetch_array()){
            echo '<option value="'.$write_sub['id'].'" '.write_select($write_sub['id'],$id_photo_folder).'>'.$write_sub['name'].'</option>';
           }
         ?>
         </select>
         </p>
	
	       <p class="box-01">
			     <input type="submit" value="Update picture"  class="input-submit" />
			    </p>
         <input type="hidden" name="action" value="update" />
	       <input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	       <input type="hidden" name="filter2" value="<?php echo $_GET['filter2'];?>" />
	       <input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
	       <input type="hidden" name="id" value="<?php echo $write['id'];?>" />
	       <input type="hidden" name="filename_old" value="<?php echo $write['file_name'];?>" />
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