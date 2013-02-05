<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(10,1,1,0,0);
$files = new files();

$is_uploaded=0;
$sesIDdir=$users->getSesid();
$strDir="../inc/uploadify/uploads_temp/photos/".$sesIDdir."/";
if (file_exists($strDir)){ 
$handle=opendir($strDir);

 while (false!==($file = readdir($handle)))
 { 
  if ($file != "." && $file != "..") 
   {
    ;
    $file_rename=str_replace("_","-",$file);
    if ($file<>$file_rename) rename ($strDir.$file,$strDir.$file_rename);
    $is_uploaded++;
   } 
  }
}
if (empty($is_uploaded)){
  //pokud neni uploadovana ani jedna fotka
  header("Location: photogallery.php".Odkaz."&message=4");
}else{

require_once("inc/head.inc");

echo $head->setTitle(langGlogalTitle."Add picture");
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
    
	<h1>Add new pictures</h1>
    <p class="box">
    	 <a href="photogallery.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of pictures</span></a>
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
	  
	  <? 
	  	if (!empty($strError)){
           echo '<div class="msg error"><ul>'.$strError.'</ul></div>';
      }
    
    ?>
    <form action="photogallery_action.php" method="post" id="form-01">
    <fieldset>
	  <legend>Add pictures</legend>
	  <?
	  $handle=opendir("../inc/uploadify/uploads_temp/photos/".$sesIDdir);
    $i=0; 
    while (false!==($file = readdir($handle))) 
    { 
      if ($file != "." && $file != "..") 
      { 
        chmod ("../inc/uploadify/uploads_temp/photos/".$sesIDdir."/".$file,0777);
        echo' 
       	<h3 class="tit">
			    Picture: <a  class="ico-show" onclick="return !window.open(this.href);" href="../inc/uploadify/uploads_temp/photos/'.$sesIDdir.'/'.$file.'"">'.$file.'</a>
			  </h3>
        
         <p class="nomt">
	         <label for="inp-1" class="req">Description:</label><br />
	         <input type="text" size="50" name="description['.$i.']" maxlength="255" class="input-text-02 required" id="inp-1" value="" /><br />
	         <span class="smaller low">short picture description</span>
	       </p>
	       
	       <p class="nomt">
	         <label for="inp-2" class="req">Keywords:</label><br />
	         <input type="text" size="50" name=keywords['.$i.']" maxlength="255" class="input-text-02 required" id="inp-2" value="" /><br />
	         <span class="smaller low">Keywords separated by a space. To link a foto to a player profile, insert "player_" and playerID (<a target="_blank" href="../mod_players/players.php'.Odkaz.'">you can find ID here</a>), eg. for Jaromir Jagr insert "player_10566".</span>
	       </p>
	       
	       <p class="nomt">
	       <label for="inp-3" class="req">Picture folder:</label><br />
         <select id="inp-3" name="id_photo_folder['.$i.']" class="input-text-02">
			     <option value="0">not selected</option>';
			     $query_sub="SELECT * FROM photo_folder ORDER by name ASC";
           $result_sub = $con->SelectQuery($query_sub);
           while($write_sub = $result_sub->fetch_array()){
            echo '
            <option value="'.$write_sub['id'].'">'.$write_sub['name'].'</option>
            ';
            }
         echo'
         </select>
         </p>
	       
	       <input type="hidden" name=filename['.$i.']" value="'.$file.'" />
         
			  ';
	       
	    $i++;    
        
      } 
    }
    closedir($handle); 
    
    ?>
    	<p class="box-01">
			     <input type="submit" value="Add new pictures"  class="input-submit" />
			    </p>
         <input type="hidden" name="action" value="add" />
	       <input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	       <input type="hidden" name="filter2" value="<?php echo $_GET['filter2'];?>" />
	       <input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
	       <input type="hidden" name="number" value="<?php echo ($i-1);?>" />
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
<?
}
?>