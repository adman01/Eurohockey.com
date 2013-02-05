<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(1,1,0,0,0);
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("js/users_rules.js");

echo $head->setTitle(langGlogalTitle."User rights");
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
$strAdminMenu="users";
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
    
	<h1>User rights</h1>
    <p class="box">
       <?php if ($users->checkUserRight(2)){?><a href="users_rules_add.php<?echo Odkaz;?>"  class="btn-create"><span>Add new right</span></a><?php } ?>
       <a href="users.php<?echo Odkaz;?>"  class="btn-list"><span>Users</span></a>
       <?php if ($users->checkUserRight(2)){?><a href="users_add.php<?echo Odkaz;?>"  class="btn-create"><span>Add new user</span></a><?php } ?>
    	 <a href="users_logs.php<?echo Odkaz;?>"  class="btn-info"><span>User logs</span></a>
	  </p>
	  
	  <? 
	  	switch ($_GET['message']){
        case 1:
          echo '<p class="msg done">group has been added. <a href="users_rules_add.php'.Odkaz.'">Add another user group</a> ?</p>';
        break;
        case 2:
          echo '<p class="msg done">group has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">group has been changed. <a href="users_rules_update.php'.Odkaz.'&id='.$_GET['id'].'">Change user group again</a>.</p>';
        break;
        case 99:
           echo '<p class="msg error">wrong or missing input data.</p>';
        break;
      }
      ?>
	  
	  <table>
	  	<table class="tablesorter"> <!-- Sort this TABLE -->
				<thead>
					<tr>
						<th>Group name</th>
						<th>Count</th>
						<th colspan="2">&nbsp;</th>
					</tr>
				</thead>
	  
	  <?php
    If (!IsSet($_GET['order']) OR ($_GET['order'] == ""))	$order="name ASC"; else $order=$_GET['order'];
    $query="SELECT * from users_group ORDER BY ".$order."";
    //echo $query; 
    
    //listovani
    $listovani = new listing($con,$_SERVER["SCRIPT_NAME"].Odkaz."&amp;filter=".$_GET['filter']."&amp;filter2=".$_GET['filter2']."&amp;order=".$_GET['order']."&amp;",100,$query,3,"",$_GET['list_number']);
    $query=$listovani->updateQuery();
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	     $query="SELECT id FROM users WHERE id_group=".$write["id"]."";
       $number=$con->GetQueryNum($query);
	   
	     if ($a%2) $style=""; else $style="";
  		 echo '<tr class="'.$style.'">
  		 
        <td class="high-bg">'.$write['name'].'</td>
        <td class="t-center">'.$number.'</td>
        <td>';
        echo '<a href="users.php'.Odkaz.'&amp;filter2='.$write["id"].'" title="Show users in group" class="ico-show">show users</a></td>';
        echo '<td>';
        if ($users->checkUserRight(3)) echo '<a href="users_rules_update.php'.Odkaz.'&amp;id='.$write["id"].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
        echo '&nbsp;&nbsp;';
        if ($number<1){
          if ($users->checkUserRight(4)) echo '<a href="javascript:delete_group(\''.$users->getSesid().'\','.$write["id"].',\''.$write["name"].'\')"  title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
        }else{
          //echo $number.'x';
        }
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