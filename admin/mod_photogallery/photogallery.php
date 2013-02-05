<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(10,1,0,0,0);
$games = new games($con);
require_once("inc/head.inc");
require_once("../inc/lightwindow.inc");
echo $head->setJavascriptExtendFile("js/functions.js");
echo $head->setTitle(langGlogalTitle."Photogallery");

$strAdminMenu="photogallery";
require_once("../inc/uploadify.inc");
?>
<script type="text/javascript">
	$(document).ready(function(){
	 
		$("#fileInput1").uploadify({
		'uploader'       : '../inc/uploadify/uploadify.swf',
		'script'         : '../inc/uploadify/uploadify.php<?php echo Odkaz;?>',
		'cancelImg'      : '../inc/uploadify/cancel.png',
		'folder'         : '/admin/inc/uploadify/uploads_temp/photos',
		'buttonText'     : 'SELECT PHOTOS',
		'auto'           : true,
		'multi'          : true,
		
		onAllComplete: function() {
    alert("All photos were uploaded."); 
    }
   });
 }); 
</script>
<?php
echo $head->setEndHead();
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
    
	<h1>Photogallery</h1>
    <p class="box">
       <?php if ($users->checkUserRight(2)){?><a href="javascript:toggle('upload');" class="btn-create"><span>Upload pictures</span></a><?php } ?>
    	 <a href="photogallery_folders.php<?echo Odkaz;?>"  class="btn-info"><span>Picture folders</span></a>
    	 
	  </p>
	  
	  	<!-- Upload -->
			<div id="upload" class="box-01" style="display:none;">

				<form action="photogallery_add.php" method="post" enctype="multipart/form-data">
				
					<p class="nom">
				 <input id="fileInput1" name="fileInput1" type="file" />
         </p>
         <br />

					<p class="nom">
						<input type="submit" value="Continue to describe photos" class="input-submit" />
					</p>
					<input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	        <input type="hidden" name="filter2" value="<?php echo $_GET['filter2'];?>" />
	        <input type="hidden" name="id" value="<?php echo $_GET['list_number'];?>" />
	        <?echo OdkazForm;?>
					
				</form>

			</div> <!-- /upload -->
	  
	  	<? 
	  	switch ($_GET['message']){
        case 1:
          echo '<p class="msg done"><b>'.$_GET['intCorrect'].'</b> pictures has been added.</p>';
          if (!empty($intError)) echo '<p class="msg error"><b>'.$_GET['intError'].'</b> pictures could not be added.</p>';
        break;
        case 2:
          echo '<p class="msg done">picture has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">picture has been changed. <a href="photogallery_update.php'.Odkaz.'&id='.$_GET['id'].'">Change picture again</a>.</p>';
        break;
        case 4:
          echo '<p class="msg error">You must select at least one picture to upload.</p>';
        break;
        case 99:
           echo '<p class="msg error">wrong or missing input data.</p>';
        break;
      }
      ?>
	  
	  <form action="<? echo $_SERVER["SCRIPT_NAME"]; ?>" method="get">
    <fieldset>
	   <legend>Data filter</legend>
	   <table class="nostyle">
		  <tr>
			 <td><span class="label label-05">search&nbsp;text</span></td>
			 <td>
         <input type="text" name="filter" value="<?php echo $_GET['filter']; ?>" class="input-text" />
       </td>
         <td>
       <select name="filter2" class="input-text">
			  <option value="0">select folder</option>
			  <?php
        $query="SELECT *,(SELECT count(*) FROM photo WHERE photo_folder.id=photo.id_photo_folder) as pocet FROM photo_folder ORDER by id DESC";
        $result = $con->SelectQuery($query);
        while($write = $result->fetch_array()){
        echo '
        <option value="'.$write['id'].'"'.write_select($write['id'],$_GET['filter2']).'>'.$write['name'].' ('.$write['pocet'].')</option>
        ';
        }
        ?>
        </select> 
       </td>
     	 <td><input type="submit" class="input-submit" value="filter" /></td>
		  </tr>
	 </table>
	 <input type="hidden" name="order" value="<?echo $_GET['order'];?>" />
	 <?echo OdkazForm;?>
  </fieldset>	
  </form>
	  
	  <form action="photogallery_action.php" method="post">
	  <?php
    If (!IsSet($_GET['order']) OR ($_GET['order'] == ""))	$order="id DESC"; else $order=$_GET['order'];
    If (!empty($_GET['filter']))	$filter=" AND (file_name LIKE '%".$_GET['filter']."%' OR description LIKE '%".$_GET['filter']."%' OR keywords LIKE '%".$_GET['filter']."%')"; else $filter="";
    If (!empty($_GET['filter2']) AND $_GET['filter2']>0)	$filter2=" AND id_photo_folder=".$_GET['filter2']; else $filter2="";
    $query="SELECT *,(SELECT name FROM photo_folder WHERE photo_folder.id=photo.id_photo_folder LIMIT 1) as folder_name FROM photo WHERE id>0 ".$filter." ".$filter2." ORDER BY ".$order."";
    
    //listovani
    $listovani = new listing($con,$_SERVER["SCRIPT_NAME"].Odkaz."&amp;filter=".$_GET['filter']."&amp;filter2=".$_GET['filter2']."&amp;",56,$query,3,"",$_GET['list_number']);
    $query=$listovani->updateQuery();
    
    $result = $con->SelectQuery($query);
    $a = 0;
    $count_image = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
  		 $strImagePath=IncludeAdminPath.PhotoFolder."/".$write["file_name"];
  		 $intImageSize=getimagesize($strImagePath);
  		 if (!empty($write["id_photo_folder"])) $strFolder='<a href="photogallery.php'.Odkaz.'&amp;filter='.$_GET["filter"].'&amp;filter2='.$write["id_photo_folder"].'" class="ico-show" title="Show folder">'.$write["folder_name"].'</a>'; else $strFolder='-';
  		 echo '
    <!-- Gallery (Item) -->
			<div class="gallery">

				<p>
					<label><input type="checkbox" id="checkbox" name="id['.$a.']" value="'.$write['id'].'" />
					<code>'.$write['description'].'</code></label><br />
					<span class="smaller">Size: <strong>'.$intImageSize[0].'&times;'.$intImageSize[1].'</strong>, Date: <strong>'.date("d.m.y",strtotime($write['date_time'])).'</strong></span><br />
					<span class="smaller">Folder: '.$strFolder.'</span><br />
					<a href="http://'.$_SERVER["SERVER_NAME"].'/'.PhotoFolder.'/'.$write["file_name"].'"  onclick="return !window.open(this.href);" title="'.$write['description'].'"><img src="/image/200-150-0-'.str_replace("-","_",$write["file_name"]).'" class="gallery-img" alt="'.$write['description'].'" /></a>
					<p class="smaller" style="padding-bottom:10px">Path: <input value="/'.PhotoFolder.'/'.$write["file_name"].'" style="width:160px;" /></p>
					';
					if ($users->checkUserRight(4)) echo'<a class="ico-delete" href="javascript:delete_item2(\''.$users->getSesid().'\','.$write["id"].',\''.$write["description"].'\')" title="Delete item">Delete</a> &nbsp;';
          if ($users->checkUserRight(3)) echo'<a class="ico-edit" href="photogallery_update.php'.Odkaz.'&amp;id='.$write["id"].'" title="Edit item">Edit</a> &nbsp;'; 
          echo'
          <a href="http://'.$_SERVER["SERVER_NAME"].'/'.PhotoFolder.'/'.$write["file_name"].'"  onclick="return !window.open(this.href);">Detail</a>
				</p>

			</div> <!-- /gallery -->
    ';
    	 $a++;
    	 $count_image++;
    	 if ($count_image==4){
        echo '<div class="fix"></div>';
        $count_image=0;
      }
	   }
	  
	  echo '<div class="fix"></div>';
    
     echo '
	  	<!-- Actions -->
			<div class="box-02 bottom box">
        
        <input type="checkbox" id="check_all" name="check_all" onclick="CheckAll(this.form,\'checkbox\');"  class="input-text" /> 	<strong>select all</strong> |
        
				<strong>for selected items, execute action:</strong>

				<select name="action" class="input-text">
					<option value="delete_all">Delete</option>
					<option value="remove_all">Remove</option>
				</select>
				<input type="submit" value="OK" />

			</div> <!-- /box-02 -->
		</form>
    ';
	  
     
	  echo '<table width="100%">';
	  echo '<tfoot>';
	   //listovani
      $listovani->show_list();
    echo '</tfoot>';
    echo '</table>';
	   
	  }else{
      echo '<p class="msg warning">No data found</p>';
    }
    
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
