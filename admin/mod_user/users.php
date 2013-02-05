<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(1,1,0,0,0);
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("js/users.js");

echo $head->setTitle(langGlogalTitle."Users");
?>
<script type="text/javascript">
	$(document).ready(function(){
		$(".tabs > ul").tabs();
		$.tablesorter.defaults.sortList = [[0,0]];
		$("table").tablesorter({
			headers: {
				5: { sorter: false },
        6: { sorter: false },
        7: { sorter: false },
        8: { sorter: false }
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
    
	<h1>Users</h1>
    <p class="box">
        <?php if ($users->checkUserRight(2)){?><a href="users_add.php<?echo Odkaz;?>"  class="btn-create"><span>Add new user</span></a><?php } ?>
    	 <a href="users_rules.php<?echo Odkaz;?>"  class="btn-list"><span>User rights</span></a>
    	 <a href="users_logs.php<?echo Odkaz;?>"  class="btn-info"><span>User logs</span></a>
    	 
	  </p>
	  
	  	<? 
	  	switch ($_GET['message']){
        case 1:
          echo '<p class="msg done">User has been added. <a href="users_add.php'.Odkaz.'">Add another user</a> ?</p>';
        break;
        case 2:
          echo '<p class="msg done">user has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">user has been changed. <a href="users_update.php'.Odkaz.'&id='.$_GET['id'].'">Change user again</a>.</p>';
        break;
        case 99:
           echo '<p class="msg error">wrong or missing input data.</p>';
        break;
      }
      ?>
	  
	  <form action="users.php" method="get">
    <fieldset>
	   <legend>Data filter</legend>
	   <table class="nostyle">
		  <tr>
			 <td><span class="label label-05">search text</span></td>
			 <td>
         <input type="text" name="filter" value="<?php echo $_GET['filter']; ?>" class="input-text" />
       </td>
       <td>
       <select name="filter2" class="input-text">
			  <option value="0">select group</option>
			  <?php
        $query="SELECT * FROM users_group ORDER by name";
        $result = $con->SelectQuery($query);
        while($write = $result->fetch_array()){
        echo '
        <option value="'.$write['id'].'"'.write_select($write['id'],$_GET['filter2']).'>'.$write['name'].'</option>
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
	  
	  <table>
	  	<table class="tablesorter"> <!-- Sort this TABLE -->
				<thead>
					<tr>
						<th>Username</th>
						<th>Name</th>
						<th>Email</th>
						<th>Phone</th>
						<th>User group</th>
						<th>Art.publish</th>
						<th>Special rights</th>
						<th>&nbsp;</th>
            <th>&nbsp;</th>
					</tr>
				</thead>
	  
	  <?php
    If (!IsSet($_GET['order']) OR ($_GET['order'] == ""))	$order="user_name ASC"; else $order=$_GET['order'];
    If (!empty($_GET['filter']))	$filter=" AND (user_name LIKE '%".$_GET['filter']."%' OR name LIKE '%".$_GET['filter']."%' OR surname LIKE '%".$_GET['filter']."%' OR phone LIKE '%".$_GET['filter']."%' OR email LIKE '%".$_GET['filter']."%')"; else $filter="";
    If (!empty($_GET['filter2']) AND $_GET['filter2']>0)	$filter2=" AND id_group=".$_GET['filter2']; else $filter2="";
    $query="SELECT *,
            (SELECT name from users_group WHERE users_group.id=users.id_group LIMIT 1)as group_name,
            (SELECT count(*) from users_special_rights WHERE users_special_rights.id_user=users.id)as num_rules
            from users WHERE id>0 ".$filter." ".$filter2." ORDER BY ".$order."";
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
  		 echo '<tr class="'.$style.'">
        <td class="high-bg">';
         if ($users->checkUserRight(3)) echo'<a href="users_update.php'.Odkaz.'&amp;id='.$write["id"].'" title="Edit user">';
         echo '<b>'.$write['user_name'].'</b></td>';
         if ($users->checkUserRight(3)) echo'</a>';
        echo '<td>'.$write['name'].' '.$write['surname'].'</td>
        <td><a href="mailto:'.$write['email'].'" class="mail">'.$write['email'].'</a></td>
        <td>'.$write['phone'].'</td>
        <td>'.$write['group_name'].'</td>
        <td class="t-left" style="white-space:nowrap">';
        if ($users->GetUserGroup($users->getIdUser())==3) echo '<a href="users_action.php'.Odkaz.'&amp;action=switch_public&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Switch status">';
        
        switch ($write['boolShowArticle']){
          case 1:
            $status_name="YES";
            $status_id_color=2;
          break;
          case 0:
            $status_id_color=1;
            $status_name="NO";
          break;
        }
        echo '<span class="label label-0'.$status_id_color.'">'.$status_name.'</span>';
        if ($users->GetUserGroup($users->getIdUser())==3) echo '</a>';
        echo'</td>
        <td>';
          if ($users->checkUserRight(3)) echo'<a class="ico-settings" href="users_special_rights.php'.Odkaz.'&amp;id_user='.$write["id"].'" title="Edit item">edit rights ('.$write["num_rules"].')</a>';
        echo '
        </td>
        <td>';
          if ($users->checkUserRight(3)) echo'<a href="users_update.php'.Odkaz.'&amp;id='.$write["id"].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          if ($users->checkUserRight(4)) echo'<a href="javascript:delete_user(\''.$users->getSesid().'\','.$write["id"].',\''.$write["user_name"].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
        echo '</td>';
        echo '<td class="smaller low">'.date("d.M.Y H:i",strtotime($write["login_date_last"])).'</td>';
        echo "
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
