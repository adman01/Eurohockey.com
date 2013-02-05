<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(10,1,0,0,0);
$games = new games($con);
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("js/functions.js");

echo $head->setTitle(langGlogalTitle."Picture folders");
?>
<script type="text/javascript">
	$(document).ready(function(){
		$(".tabs > ul").tabs();
		$.tablesorter.defaults.sortList = [[0,0]];
		$("table").tablesorter({
			headers: {
			  2: { sorter: false },
				3: { sorter: false }
				
			}
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
    
	<h1>Picture folders</h1>
    <p class="box">
       <?php if ($users->checkUserRight(2)){?><a href="photogallery_folders_add.php<?echo Odkaz;?>"  class="btn-create"><span>Add new folfer</span></a><?php } ?>
    	 <a href="photogallery.php<?echo Odkaz;?>"  class="btn-info"><span>Photogallery</span></a>
	  </p>
	  
	  	<? 
	  	switch ($_GET['message']){
        case 1:
          echo '<p class="msg done">folder has been added. <a href="photogallery_folders_add.php'.Odkaz.'">Add another folder</a> ?</p>';
        break;
        case 2:
          echo '<p class="msg done">folder has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">folder has been changed. <a href="photogallery_folders_update.php'.Odkaz.'&id='.$_GET['id'].'">Change folder again</a>.</p>';
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
			 <td><span class="label label-05">search text</span></td>
			 <td>
         <input type="text" name="filter" value="<?php echo $_GET['filter']; ?>" class="input-text" />
       </td>
     	 <td><input type="submit" class="input-submit" value="filter" /></td>
		  </tr>
	 </table>
	 <input type="hidden" name="order" value="<?echo $_GET['order'];?>" />
	 <?echo OdkazForm;?>
  </fieldset>	
  </form>
	  
	  <table>
	  	<table class="tablesorter"> <!-- Sort this TABLE -->
				<thead>
					<tr>
						<th>Name</th>
						<th>Description</th>
						<th>&nbsp;</th>
            <th>&nbsp;</th>
						<th colspan="2">&nbsp;</th>
					</tr>
				</thead>
	  
	  <?php
    If (!IsSet($_GET['order']) OR ($_GET['order'] == ""))	$order="id DESC"; else $order=$_GET['order'];
    If (!empty($_GET['filter']))	$filter=" AND (name LIKE '%".$_GET['filter']."%' OR description LIKE '%".$_GET['filter']."%')"; else $filter="";
    $query="SELECT *,
            (SELECT count(*) FROM photo WHERE photo.id_photo_folder=photo_folder.id) as num_photos,
            (SELECT count(*) FROM photo_folder_assign WHERE photo_folder_assign.id_folder=photo_folder.id) as num_assignment
            
            FROM photo_folder WHERE id>0 ".$filter." ".$filter2." ORDER BY ".$order."";
    //echo $query; 
    
    //listovani
    $listovani = new listing($con,$_SERVER["SCRIPT_NAME"].Odkaz."&amp;filter=".$_GET['filter']."&amp;filter2=".$_GET['filter2']."&amp;",100,$query,3,"",$_GET['list_number']);
    $query=$listovani->updateQuery();
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	  if ($a%2) $style=""; else $style="";
  		  echo '<tr class="'.$style.'">';
        echo '<td class="high-bg"><a class="ico-show" href="photogallery.php'.Odkaz.'&amp;filter2='.$write["id"].'" title="Show photos"><b>'.$write['name'].'</b></a></td>';
        echo '<td class="t-left">'.$write['description'].'</td>';
        echo '<td class="t-center"><a class="ico-settings" href="photogallery_folders_assign.php'.Odkaz.'&amp;id='.$write["id"].'" title="Show assignment">show assignment ('.$write['num_assignment'].')</a></td>';
        echo '<td class="t-center"><a class="ico-folder" href="photogallery.php'.Odkaz.'&amp;filter2='.$write["id"].'" title="Show photos">show photos ('.$write['num_photos'].')</a></td>';
        echo '<td class="t-left">';
        if ($users->checkUserRight(3)) echo '<a href="photogallery_folders_action.php'.Odkaz.'&amp;action=switch&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Switch status">';
        switch ($write['show_item']){
          case 0:
            $status_id_color=1;
            $status_name="hidden";
          break;
          case 1:
            $status_id_color=2;
            $status_name="published";
          break;
        }
        echo '<span class="label label-0'.$status_id_color.'">'.$status_name.'</span>';
        if ($users->checkUserRight(3)) echo '</a>';
        echo'</td>';
        echo'
        <td>';
          if ($users->checkUserRight(3)) echo'<a href="photogallery_folders_update.php'.Odkaz.'&amp;id='.$write["id"].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
          if ($users->checkUserRight(4) and $write['num_photos']==0) echo'<a href="javascript:delete_item(\''.$users->getSesid().'\','.$write["id"].',\''.$write["name"].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
        echo "
        </td>
        </tr>\n";
  		 $a++;
	   }
	   
	  echo '<tfoot>';
	   //listovani
      $listovani->show_list();
    echo '</tfoot>';
	   
	  }else{
      echo '<tr><td colspan="10" class=""><p class="msg warning">No data found</p></td></tr>';
    }
    
    ?>
    </table>
   <!-- /hlavni text -->
			

		</div> <!-- /content -->

	</div> <!-- /cols -->

	<hr class="noscreen" />

	<!-- Footer -->
	<?php require_once("../inc/footer.inc");  ?> 
  <!-- /footer -->

</div> <!-- /main -->

</body>
