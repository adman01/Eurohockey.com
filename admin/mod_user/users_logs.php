<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(1,1,0,0,0);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."User logs view");
?>
<script type="text/javascript">
	$(document).ready(function(){
	 
	    $.tablesorter.addParser({
	            id: 'dd.mm.yyyy',
	            is: function(s) {
	                return false;
	            },
	            format: function(s) {
	                s = '' + s; //Make sure it's a string
	                var hit = s.match(/(\d{1,2})\.(\d{1,2})\.(\d{4})/);
	                if (hit && hit.length == 4) {
	                    return hit[3] + hit[2] + hit[1];
	                }
	                else {
	                    return s;
	                }
	            },
	            type: 'text'
     });
	   
		$(".tabs > ul").tabs();
		$.tablesorter.defaults.sortList = [[1,1]];
		$("table").tablesorter({
			headers: {
			  1: { sorter: 'dd.mm.yyyy'},
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
    
	<h1>User logs view</h1>
    <p class="box">
    	 <a href="users.php<?echo Odkaz;?>"  class="btn-list"><span>Users</span></a>
    	 <a href="users_rules.php<?echo Odkaz;?>"  class="btn-list"><span>User rights</span></a>
    	  <?php if ($users->checkUserRight(2)){?><a href="users_add.php<?echo Odkaz;?>"  class="btn-create"><span>Add new user</span></a><?php } ?>
	  </p>
	  
	  <form action="users_logs.php" method="get">
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
						<th>Username</th>
						<th>Date time</th>
						<th>IP address</th>
						<th>Status</th>
					</tr>
				</thead>
	  
	  <?php
    If (!IsSet($_GET['order']) OR ($_GET['order'] == ""))	$order="date_time DESC"; else $order=$_GET['order'];
    If (IsSet($_GET['filter']))	$filter=" AND (ip LIKE '%".$_GET['filter']."%' or user_name LIKE '%".$_GET['filter']."%')"; else $filter="";
    $query="SELECT *,(SELECT id from users WHERE users_log.user_name=users.user_name LIMIT 1)as id_user from users_log WHERE id>0 ".$filter." ORDER BY ".$order."";
    //echo $query; 
    
    //listovani
    $listovani = new listing($con,"users_logs.php".Odkaz."&amp;filter=".$_GET['filter']."&amp;",100,$query,3,"",$_GET['list_number']);
    $query=$listovani->updateQuery();
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	     if ($a%2) $style="color1"; else $style="color2";
	     
	     
	     switch ($write['id_status']){
           case 1:
            $status="successful login";
            break;
           case 2:
            $status="wrong login data";
            break;
           case 3:
            $status="wrong input data";
            break;
           case 4:
            $status="automatic logout";
            break;
           case 5:
            $status="logout";
            break;
        }
  		 echo '<tr onmouseover="ZmenTridu(\'zvyraznene\',\'row_'.$write['id'].'\')"" onmouseout="ZmenTridu(\''.$style.'\',\'row_'.$write['id'].'\')" id="row_'.$write['id'].'" class="'.$style.'">
        <td class="bold">';
        if (isset($write['id_user']))
          {echo '<img src="../inc/design/ico-user-03.gif" alt="" height="16" width="16" /> <a href="users_update.php'.Odkaz.'&amp;id='.$write['id_user'].'">';}
        else{
          echo '<img src="../inc/design/ico-warning.gif" alt="" height="16" width="16" /> ';
        }
        
        echo $write['user_name'];
        if (isset($write['id_user']))echo '</a>';
        echo '</td>
        <td>'.date("d.m.Y H:i",strtotime($write['date_time'])).'</td>
        <td>'.$write['ip'].'</td>
        
        <td><span class="label label-0'.$write['id_status'].'">'.$status.'</span></td>';
        echo "</tr>\n";
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
</html>