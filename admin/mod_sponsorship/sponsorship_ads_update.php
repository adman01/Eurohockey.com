<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(33,1,0,1,0);
$files = new files();
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Edit ads");
echo $head->setJavascriptExtendFile("js/functions.js");
require_once("../inc/uploadify.inc");
?>
<script type="text/javascript">
$(document).ready(function(){

	
  
    $('.type').change(function () {
      intIDtype=$(this).val();
      if (intIDtype==1){
      	$('#box_text').show();	
      	$('#box_image').hide();	
      	$('#html_text').addClass("required");	
      	
      	
      }else{
      	$('#box_text').hide();	
      	$('#box_image').show();	
      	$('#html_text').removeClass("required");	
      }
      
   
      });

    <?php 
  $strUploadifyDataType="sponsorship"; 
  $strUploadifyDataExt="jpg";
  ?>
  
  $("#fileInput1").uploadify({
		'uploader'       : '../inc/uploadify/uploadify.swf',
		'script'         : '../inc/uploadify/uploadify_item.php<?php echo Odkaz;?>_datetype_<?php echo $strUploadifyDataType; ?>_datetype_<?php echo $strUploadifyDataExt; ?>',
		'cancelImg'      : '../inc/uploadify/cancel.png',
		'folder'         : '/admin/inc/uploadify/uploads_temp',
		'buttonText'     : 'CHANGE IMAGE',
		'auto'           : true,
		'multi'          : false,
		'fileExt'        : '*.jpg',
		'fileDesc'       : 'jpeg images',
		
		onAllComplete: function() {
       $("#image_box").show();
       $("#id_image").val(1);
    }
   });

   $('#remove_image').click(function()
    {
   		$("#id_image").val(3);
   		$("#image_box_1").html("<p>no banner found</p>");
   });

  

  
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
$strAdminMenu="sponsorship";
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
    
	<h1>Edit ADS</h1>
    <p class="box">
    	 <a href="sponsorship_ads.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of ADS</span></a>
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
  $query="SELECT * FROM sponsorship_ads WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  	$id=$_GET['id'];
    if (!empty($_GET['id_type'])) $id_type=$_GET['id_type']; else  $id_type=$write['id_type'];
    if (!empty($_GET['name'])) $name=$_GET['name']; else  $name=$write['name'];
    if (!empty($_GET['url'])) $url=$_GET['url']; else  $url=$write['url'];
    if (!empty($_GET['html_text'])) $html_text=$_GET['html_text']; else  $html_text=$write['html_text'];
    if (!empty($_GET['id_image'])) $id_image=$_GET['id_image']; else  $id_image=$write['id_image'];

  ?>
	  
<form action="sponsorship_ads_action.php" method="post" id="form-01">
<fieldset>
	<legend>Edit ads</legend>
	
	<p class="nomt">
	<label for="inp-1" class="req">Name:</label><br />
	<input type="text" size="50" name="name" maxlength="255" class="input-text-02 required" id="inp-1" value="<?php echo $name; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">URL</label><br />
  	<input type="text" size="50" name="url" maxlength="255" class="input-text-02" id="inp-2" value="<?php echo $url; ?>" />
	</p>

	<p class="nomt">
	<label class="req">ADS type</label><br />
	  <select name="id_type" class="input-text-02 type">
			  <?php
        $query_sub="SELECT * FROM sponsorship_ads_type_list ORDER by id";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$id_type).'>'.$write_sub['name'].'</option>
        ';
        }
        ?>
        </select>
  </p>
  

	
	<div id="box_text" <?php if ($id_type<>1) echo 'style="display:none"'; ?>>
		

		<p class="nomt">
		<label for="html_text" class="req">Text</label><br />
  		<input type="text" size="50" name="html_text" maxlength="255" class="input-text-02" id="html_text" value="<?php echo $html_text; ?>" />
		</p>


	</div>

	<div id="box_image" <?php if ($id_type<>2) echo 'style="display:none"'; ?>>
		
		<p class="nomt">
	<label for="inp-16" class="req">Banner:</label>

	<div id="image_box_1">
	<?php
	
  $FilePath=IncludeAdminPath.'/'.PhotoFolder.'/sponsorship/sponsorship_'.$id.'.jpg';
  if ($files->checkFile($FilePath)){
	 echo '<p><a class="ico-show" onclick="return !window.open(this.href);" href="/'.PhotoFolder.'/sponsorship/sponsorship_'.$id.'.jpg">Show actual banner</a></p>';
	 echo '<p><a class="ico-delete" id="remove_image"  href="javascript:void()">Remove banner</a></p>';
	 $id_image=1;
	}else{
   echo '<p>no banner found</p>';
   $id_image=0;  
  }
	?>
	</div>
	
  <input id="fileInput1" name="fileInput1" type="file" />
  <?php
  if ($_GET['id_image']==1){
    $strImageBox="";
    $id_image=1;
  }else{
    $strImageBox="display:none";
    $id_image=0;
  }
  echo '<div id="image_box" style="'.$strImageBox.'">New banner is uploaded <a class="ico-show" onclick="return !window.open(this.href);" href="/admin/inc/uploadify/uploads_temp/'.$users->sesid.'/'.$strUploadifyDataType.'_'.$users->sesid.'.'.$strUploadifyDataExt.'">show banner</a></div>';
  echo '<input type="hidden" name="id_image" id="id_image" value="'.$id_image.'" />';
  ?>
	</p>
  
  <br />

	</div>

	

	
	<p class="box-01">
			<input type="submit" value="Edit ads"  class="input-submit" />
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