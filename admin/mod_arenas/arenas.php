<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(27,1,0,0,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
//echo $ArSpecialRight[1].'<br />';
//echo $ArSpecialRight[2].'<br />';
//echo $ArSpecialRight[3].'<br />';
$games = new games($con);
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("js/functions.js");

echo $head->setTitle(langGlogalTitle."Arenas");
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
$strAdminMenu="clubs";
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
    
	<h1>Arenas</h1>
    <p class="box">
       <?php if ($users->checkUserRight(2)){?><a href="arenas_add.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-create"><span>Add new arena</span></a><?php } ?>
       <a href="../mod_clubs/clubs.php<?echo Odkaz;?>"  class="btn-info"><span>Clubs</span></a>
       <a href="../mod_leagues/leagues.php<?echo Odkaz;?>"  class="btn-info"><span>Leagues</span></a>
    	 <a href="../mod_countries/countries.php<?echo Odkaz;?>"  class="btn-info"><span>Countries</span></a>
	  </p>
	  
	  	<? 
	  	switch ($_GET['message']){
        case 1:
          echo '<p class="msg done">arena has been added. <a href="arenas_add.php'.Odkaz.'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'">Add another arena</a> ?</p>';
          echo '<p class="msg warning">if you changed GPS location, <a onclick="return !window.open(this.href);" href="/arena/'.($_GET['id']+123).'-arena.html" class="bold">please CHECK map here</a>.</p>';
          break;
        case 2:
          echo '<p class="msg done">arena has been removed.</p>';
          break;
        case 3:
          echo '<p class="msg done">arena has been changed. <a href="arenas_update.php'.Odkaz.'&id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'">Change arena again</a>.</p>';
          echo '<p class="msg warning">if you changed GPS location, <a onclick="return !window.open(this.href);" href="/arena/'.($_GET['id']+123).'-arena.html" class="bold">please CHECK map here</a>.</p>';
        break;
        case 99:
           echo '<p class="msg error">wrong or missing input data.</p>';
        break;
      }
      ?>
	  
	  <form action="<? echo $_SERVER["SCRIPT_NAME"]; ?>" name="filter_form" method="get">
    <fieldset>
	   <legend>Datafilter</legend>
	   <table class="nostyle">
		  <tr>
			 <td><span class="label label-05">search&nbsp;text</span></td>
			 <td>
          <input type="text" class="input-text" name="filter" id="filter" autocomplete="off" value="<?php echo $_GET['filter']; ?>" />
       </td>
       <td>
       &nbsp;<span class="label label-04">country</span>
        <select name="filter2" class="input-text">
			  <option value="0">select country</option>
			  <?php
			  if(!empty($ArSpecialRight[1])) $sqlRightWhere="WHERE ".str_replace("id_country","id",$ArSpecialRight[1]); else $sqlRightWhere="";
			  $query="SELECT * FROM countries ".$sqlRightWhere." ORDER by name";
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
						<th>Default arena name</th>
						<th>Status</th>
						<th>Important</th>
						<th>Names</th>
						<th>Clubs</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
	  <?php
    If (!IsSet($_GET['order']) OR ($_GET['order'] == ""))	$order="name ASC"; else $order=$_GET['order'];
    If (!empty($_GET['filter']))	$filter=" AND (name LIKE '%".$_GET['filter']."%' OR also_known_as LIKE '%".$_GET['filter']."%' OR address LIKE '%".$_GET['filter']."%' OR also_used_for LIKE '%".$_GET['filter']."%' OR most_notable_games LIKE '%".$_GET['filter']."%')"; else $filter="";
    If (!empty($_GET['filter2']))	$filter2=" AND (id_country='".$_GET['filter2']."')"; else $filter2="";
    if(!empty($ArSpecialRight[1])) $sqlRightWhere="AND ".str_replace("id_country","id_country",$ArSpecialRight[1]); else $sqlRightWhere="";
		$query="SELECT *,
            (SELECT count(*) FROM arenas_names WHERE arenas_names.id_arena=arenas.id) as num_names,
            (SELECT count(*) FROM clubs_arenas_items WHERE clubs_arenas_items.id_arena=arenas.id) as num_clubs 
            FROM arenas WHERE id>0 ".$sqlRightWhere." ".$filter." ".$filter2." ORDER BY ".$order."";
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
         if ($users->checkUserRight(3)) echo'<a href="arenas_update.php'.Odkaz.'&amp;id='.$write["id"].'" title="Edit arena">';
         echo '<b>'.$write['name'].'</b></td>';
         if ($users->checkUserRight(3)) echo'</a>';
        echo '<td class="t-left" style="white-space:nowrap">';
        if ($users->checkUserRight(3)) echo '<a href="arenas_action.php'.Odkaz.'&amp;action=switch&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Switch status">';
        
       $query_sub="SELECT id,name FROM arenas_status_list WHERE id=".$write["id_status"];
       $result_sub = $con->SelectQuery($query_sub);
	     $write_sub = $result_sub->fetch_array();
  	   $status_name=$write_sub['name'];
	     $status_id=$write_sub['id'];
        
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
          case 5:
            $status_id_color=5;
          break;
          default:
           $status_id_color=5;
          break;
        }
        echo '<span class="label label-0'.$status_id_color.'">'.$status_name.'</span>';
        if ($users->checkUserRight(3)) echo '</a>';
        echo'</td>';
        echo '<td class="t-left" style="white-space:nowrap">';
        if ($users->checkUserRight(3)) echo '<a href="arenas_action.php'.Odkaz.'&amp;action=switch_important&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Switch status">';
  	     
         $status_id=$write['important_arenas'];
        switch ($status_id){
          case 0:
            $status_id_color=3;
            $status_name="No";
          break;
          case 1:
            $status_id_color=2;
            $status_name="Yes";
          break;
        }
        echo '<span class="label label-0'.$status_id_color.'">'.$status_name.'</span>';
        if ($users->checkUserRight(3)) echo '</a>';
        echo'</td>';
        
        echo '<td class="t-center" style="white-space:nowrap"><a class="ico-settings" href="arenas_names.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign different name">assign name ('.$write['num_names'].')</a></td>';
        echo '<td class="t-center" style="white-space:nowrap"><a class="ico-settings" href="arenas_assign.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign club">assign club ('.$write['num_clubs'].')</a></td>';
        echo'<td style="white-space:nowrap">';
          if ($users->checkUserRight(3)) echo'<a href="arenas_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
          
          if ($users->checkUserRight(4)){ 
            echo'<a href="javascript:delete_arena(\''.$users->getSesid().'\','.$write["id"].',\''.$write["name"].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
          }
        echo "
        </td>";
        $last_update=$write["last_update"];
        if ($last_update=='0000-00-00 00:00:00') $last_update='-'; else $last_update=date("d.m.y",strtotime($last_update));
        echo '<td class="smaller low">'.$last_update.' | '.$users->GetUserSignatureID($write["last_update_user"]).'</td>';
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
