<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(26,1,0,0,0);
$games = new games($con);
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("js/functions.js");

echo $head->setTitle(langGlogalTitle."Players");
?>
<script type="text/javascript">
	$(document).ready(function(){
		$(".tabs > ul").tabs();
		$.tablesorter.defaults.sortList = [[0,0]];
		$("table").tablesorter({
			headers: {
				9: { sorter: false }
			}
		});
	});
</script>
<?php
echo $head->setEndHead();
$strAdminMenu="players";
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
    
	<h1>Players</h1>
    <p class="box">
       <?php if ($users->checkUserRight(2)){?><a href="players_add.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-create"><span>Add new player</span></a><?php } ?>
       <a href="../mod_countries/countries.php<?echo Odkaz;?>"  class="btn-info"><span>Countries</span></a>
       <?php if ($users->checkUserRight(3)){?><a href="players_mass_update.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-info"><span>Mass update</span></a><?php } ?>
       <?php if ($users->checkUserRight(3)){?><a href="players_action.php<?echo Odkaz;?>&amp;action=check_status&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-info"><span>Check players status (long time operation)</span></a><?php } ?>
	  </p>
	  
	  	<? 
	  	switch ($_GET['message']){
        case 1:
          echo '<p class="msg done">player has been added. <a href="players_add.php'.Odkaz.'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'">Add another player</a> or <a href="players_stats.php'.Odkaz.'&id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'">Edit player´s stats</a> ?</p>';
        break;
        case 2:
          echo '<p class="msg done">player has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">player has been changed. <a href="players_update.php'.Odkaz.'&id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'">Change player again</a> ?</p>';
        break;
        case 4:
          echo '<p class="msg done"><b>'.$_GET['intCorrect'].'</b> players has been assigned.</p>';
          if (!empty($intError)) echo '<p class="msg error"><b>'.$_GET['intError'].'</b> errors.</p>';
        break;
        case 99:
           echo '<p class="msg error">wrong or missing input data.</p>';
        break;
      }
      ?>
	  
	  <form action="<? echo $_SERVER["SCRIPT_NAME"]; ?>" name="filter_form" method="get">
    <fieldset>
	   <legend>Data filter</legend>
	   <table class="nostyle">
		  <tr>
			 <td><span class="label label-05">search&nbsp;text</span></td>
			 <td>
			   <div style="position:relative">
          <input type="text" name="filter" id="filter" autocomplete="off" value="<?php echo $_GET['filter']; ?>" class="input-text" onkeyup="send_livesearch_data(this.value)" />
          <div id="livesearch"></div>
         </div>
         
       </td>
        <td>
        <select name="filter2" class="input-text">
			  <option value="0">select country</option>
			  <?php
        $query="SELECT * FROM countries ORDER by name";
        $result = $con->SelectQuery($query);
        while($write = $result->fetch_array()){
        //$games->GetActualLeagueName($write['id'],ActSeason)
        echo '
        
        <option value="'.$write['shortcut'].'"'.write_select($write['shortcut'],$_GET['filter2']).'>'.$write['name'].'</option>
        ';
        }
        ?>
        </select>
        
       </td>
       
       <td>
        <select name="filter3" class="input-text">
			  <option value="0">select status</option>
			  <?php
        $query="SELECT * FROM players_status_list ORDER by name";
        $result = $con->SelectQuery($query);
        while($write = $result->fetch_array()){
        echo '
        
        <option value="'.$write['id'].'"'.write_select($write['id'],$_GET['filter3']).'>'.$write['name'].'</option>
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
	  
	  
	  <form action="clubs_action.php" method="post">
	  <table>
	  	<table class="tablesorter"> <!-- Sort this TABLE -->
				<thead>
					<tr>
					  <th>ID</th>
					  <th>Surname</th>
						<th>Name</th>
						<th>Pos.</th>
						<th>Nat.</th>
						<th>Birth.</th>
						<th>Stats</th>
						<th>Status</th>
						<th>Photo</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
	  <?php
    If (!IsSet($_GET['order']) OR ($_GET['order'] == ""))	$order="surname ASC"; else $order=$_GET['order'];
    If (!empty($_GET['filter']))	$filter=" AND (id='".$_GET['filter']."' OR name LIKE '%".$_GET['filter']."%' OR surname LIKE '%".$_GET['filter']."%' OR birth_date LIKE '%".$_GET['filter']."%' OR birth_place LIKE '%".$_GET['filter']."%' OR nationality LIKE '%".$_GET['filter']."%' or CONCAT_WS(\" \",name,surname) LIKE '%".$_GET['filter']."%')"; else $filter="";
    If (!empty($_GET['filter2']))	$filter2=" AND nationality='".$_GET['filter2']."'"; else $filter2="";
    If (!empty($_GET['filter3']))	$filter3=" AND id_status='".$_GET['filter3']."'"; else $filter3="";
    $query="SELECT *
        FROM players WHERE id>0 ".$filter." ".$filter2." ".$filter3." ORDER BY ".$order."";
    //echo $query;
    
    
    //listovani
    $listovani = new listing($con,$_SERVER["SCRIPT_NAME"].Odkaz."&amp;filter=".$_GET['filter']."&amp;filter2=".$_GET['filter2']."&amp;",100,$query,3,"",$_GET['list_number']);
    $query=$listovani->updateQuery();
    //echo $query;
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	   
	   $query_sub="SELECT count(*)as pocet FROM stats WHERE id_player=".$write["id"]."";
	   //$num_stats=$con->GetQueryNum($query_sub);
	   $result_sub = $con->SelectQuery($query_sub);
	   $write_sub = $result_sub->fetch_array();
	   $num_stats=$write_sub['pocet'];
	   
	   $query_sub="SELECT id,name FROM players_status_list WHERE id=".$write["id_status"];
	   $result_sub = $con->SelectQuery($query_sub);
	   $write_sub = $result_sub->fetch_array();
	   $status_name=$write_sub['name'];
	   $status_id=$write_sub['id'];
	   
	   $query_sub="SELECT shortcut FROM players_positions_list WHERE id=".$write["id_position"];
	   $result_sub = $con->SelectQuery($query_sub);
	   $write_sub = $result_sub->fetch_array();
	   $position_name=$write_sub['shortcut'];
	   
	   
	  if ($a%2) $style=""; else $style="";
  		 echo '<tr class="'.$style.'">
  		  <td>'.$write["id"].'</td>
  		  <td class="high-bg">';
         if ($users->checkUserRight(3)) echo'<a href="players_update.php'.Odkaz.'&amp;id='.$write["id"].'" title="Edit player">';
         echo '<b>'.$write["surname"].'</b>';
         if ($users->checkUserRight(3)) echo'</a>';
        echo '</td>
        <td>'.$write["name"].'</td>
        <td style="white-space:nowrap" class="t-center">'.$position_name.'</td>
  		  <td style="white-space:nowrap" class="t-center"><a href="players.php'.Odkaz.'&amp;filter='.$write["nationality"].'&amp;filter2='.$_GET['filter2'].'" title="Filter nationality">'.$write["nationality"].'</a></td>
  		  <td style="white-space:nowrap" class="t-center">'.$write["birth_date"].'</td>
  		  
        ';
        echo '<td style="white-space:nowrap" class="t-left"><a class="ico-settings" href="players_stats.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit statistics">Edit statistics <b>('.$num_stats.')</b></a></td>';
        echo '<td style="white-space:nowrap" class="t-left">';
        if ($users->checkUserRight(3)) echo '<a href="players_action.php'.Odkaz.'&amp;action=switch&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Switch status">';
        switch ($status_id){
          case 1:
            $status_id_color=2;
          break;
          case 2:
            $status_id_color=1;
          break;
          case 3:
            $status_id_color=3;
          break;
          case 4:
            $status_id_color=4;
          break;
          default:
           $status_id_color=5;
          break;
        }
        echo '<span class="label label-0'.$status_id_color.'">'.$status_name.'</span>';
        if ($users->checkUserRight(3)) echo '</a>';
        echo'</td>';
        
         echo '<td style="white-space:nowrap" class="t-left">';
        if ($users->checkUserRight(3)) echo '<a href="players_action.php'.Odkaz.'&amp;action=switch_photo&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Switch photo">';
        switch ($write["id_photo_status"]){
          case 1:
            $status_id_color=3;
            $status_name="ID key";
          break;
          default:
           $status_id_color=2;
           $status_name="ALL";
          break;
        }
        echo '<span class="label label-0'.$status_id_color.'">'.$status_name.'</span>';
        if ($users->checkUserRight(3)) echo '</a>';
        echo'</td>';
        
        
        echo'<td style="white-space:nowrap">';
          if ($users->checkUserRight(3)) echo'<a href="players_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
          if ($users->checkUserRight(4)) echo'<a href="javascript:delete_item(\''.$users->getSesid().'\','.$write["id"].',\''.$write["name"].' '.$write["surname"].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
        echo "
        </td>";
        $last_update=$write["last_update"];
        if ($last_update=='0000-00-00 00:00:00') $last_update='-'; else $last_update=date("d.m.y H:i:s",strtotime($last_update));
        echo '<td class="smaller low">'.$last_update.' | '.$users->GetUserSignatureID($write["last_update_user"]).'</td>';
        echo"</tr>\n";
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
