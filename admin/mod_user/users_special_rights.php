<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(1,1,0,0,0);
$games = new games($con);
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("js/users.js");

echo $head->setTitle(langGlogalTitle."User special rights");
?>
<script type="text/javascript">
	$(document).ready(function(){
		$(".tabs > ul").tabs();
		$.tablesorter.defaults.sortList = [[0,0]];
		$("table").tablesorter({
			headers: {
				4: { sorter: false }
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
    
	<h1>User special rights</h1>
    <p class="box">
       <?php if ($users->checkUserRight(2)){?><a href="users_special_rights_add.php<?echo Odkaz;?>&amp;id_user=<?echo $_GET['id_user'];?>"  class="btn-create"><span>Add new special right</span></a><?php } ?>
       <a href="users.php<?echo Odkaz;?>"  class="btn-list"><span>Users</span></a>
       <?php if ($users->checkUserRight(2)){?><a href="users_add.php<?echo Odkaz;?>"  class="btn-create"><span>Add new user</span></a><?php } ?>
    	 <a href="users_logs.php<?echo Odkaz;?>"  class="btn-info"><span>User logs</span></a>
	  </p>
	  
	  <? 
	  	switch ($_GET['message']){
        case 1:
          echo '<p class="msg done">right has been added. <a href="users_special_rights_add.php'.Odkaz.'&id_user='.$_GET['id_user'].'">Add another right</a> ?</p>';
        break;
        case 2:
          echo '<p class="msg done">right has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">right has been changed. <a href="users_special_rights_update.php'.Odkaz.'&id='.$_GET['id'].'&id_user='.$_GET['id_user'].'">Change right again</a>.</p>';
        break;
        case 99:
           echo '<p class="msg error">wrong or missing input data.</p>';
        break;
      }
      
      $query_user="SELECT	user_name from users WHERE id=".$_GET['id_user']."";
      $result_user = $con->SelectQuery($query_user);
      if ($con->GetQueryNum($query_user)>0){
        $write_user = $result_user->fetch_array();
        echo '<h2>Special rules for user "'.$write_user['user_name'].'"</h2>';
      ?>
	  
	  <table>
	  	<table class="tablesorter"> <!-- Sort this TABLE -->
				<thead>
					<tr>
						<th>Modul name</th>
						<th>Alowed countries</th>
						<th>Alowed leagues</th>
						<th>Alowed clubs</th>
						<th colspan="2">&nbsp;</th>
					</tr>
				</thead>
	  
	  <?php
    If (!IsSet($_GET['order']) OR ($_GET['order'] == ""))	$order="id_right ASC"; else $order=$_GET['order'];
    $query="SELECT *,(SELECT name FROM users_rights WHERE users_rights.id=users_special_rights.id_right LIMIT 1) as users_rights_name from users_special_rights WHERE id_user=".$_GET['id_user']." ORDER BY ".$order."";
    //echo $query; 
    
    //listovani
    $listovani = new listing($con,$_SERVER["SCRIPT_NAME"].Odkaz."&amp;filter=".$_GET['filter']."&amp;filter2=".$_GET['filter2']."&amp;order=".$_GET['order']."&amp;",100,$query,3,"",$_GET['list_number']);
    $query=$listovani->updateQuery();
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	     if ($a%2) $style=""; else $style="";
	     if (!empty($write['id_country'])) $nameCountry=$games->GetActualCountryName($write['id_country'],ActSeason); else $nameCountry="";
	     if (!empty($write['id_league'])) $nameLeague=$games->GetActualLeagueName($write['id_league'],ActSeason);else $nameLeague="";
	     if (!empty($write['id_club'])) $nameClub=$games->GetActualClubName($write['id_club'],ActSeason); else $nameClub="";
  		 echo '<tr class="'.$style.'">
  		  <td class="high-bg"><b>'.$write['users_rights_name'].'</b></td>
        <td class="t-left">'.$nameCountry.'</td>
        <td class="t-left">'.$nameLeague.'</td>
        <td class="t-left">'.$nameClub.'</td>
        <td>
        ';
        if ($users->checkUserRight(3)) echo '<a href="users_special_rights_update.php'.Odkaz.'&amp;id_user='.$write["id_user"].'&amp;id='.$write["id"].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
        echo '&nbsp;&nbsp;';
        if ($users->checkUserRight(4)) echo '<a href="javascript:delete_special_right(\''.$users->getSesid().'\','.$write["id"].','.$write["id_user"].',\''.$write['users_rights_name'].'\')"  title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
        
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
    <?
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