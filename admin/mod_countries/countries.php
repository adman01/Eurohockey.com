<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(23,1,0,0,0);
$games = new games($con);
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("js/functions.js");

echo $head->setTitle(langGlogalTitle."Countries");
?>
<script type="text/javascript">
	$(document).ready(function(){
		$(".tabs > ul").tabs();
		$.tablesorter.defaults.sortList = [[0,0]];
		$("table").tablesorter({
			headers: {
				5: { sorter: false }
			}
		});
	});
</script>
<?php
echo $head->setEndHead();
$strAdminMenu="countries";
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
    
	<h1>Countries</h1>
    <p class="box">
       <?php if ($users->checkUserRight(2)){?><a href="countries_add.php<?echo Odkaz;?>"  class="btn-create"><span>Add new country</span></a><?php } ?>
       <a href="countries_mass_update.php<?echo Odkaz;?>"  class="btn-info"><span>Mass update</span></a>
    	 <a href="../mod_leagues/leagues.php<?echo Odkaz;?>"  class="btn-info"><span>Leagues</span></a>
    	 <a href="../mod_clubs/clubs.php<?echo Odkaz;?>"  class="btn-info"><span>Clubs</span></a>
    	 
	  </p>
	  
	  	<? 
	  	switch ($_GET['message']){
        case 1:
          echo '<p class="msg done">Country has been added. <a href="countries_add.php'.Odkaz.'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'">Add another country</a> ?</p>';
        break;
        case 2:
          echo '<p class="msg done">country has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">country has been changed. <a href="countries_update.php'.Odkaz.'&id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'">Change country again</a>.</p>';
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
					  <th>Default country name</th>
						<th>Shortcut</th>
						<th>Important</th>
						<th>Names</th>
						<th>Notable players</th>
						<th>Leagues</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
	  <?php
    If (!IsSet($_GET['order']) OR ($_GET['order'] == ""))	$order="name ASC"; else $order=$_GET['order'];
    If (!empty($_GET['filter']))	$filter=" AND (name LIKE '%".$_GET['filter']."%' OR shortcut LIKE '%".$_GET['filter']."%')"; else $filter="";
    //If (!empty($_GET['filter2']) AND $_GET['filter2']>0)	$filter2=" AND id_group=".$_GET['filter2']; else $filter2="";
    $query="SELECT *,
            (SELECT count(*) FROM leagues_countries_items WHERE leagues_countries_items.id_country=countries.id)as num_leagues,
            (SELECT count(*) FROM countries_names WHERE countries_names.id_country=countries.id) as num_countries,
            (SELECT count(*) FROM countries_players_items WHERE countries_players_items.id_country=countries.id) as num_players
            FROM countries WHERE id>0 ".$filter." ".$filter2." ORDER BY ".$order."";
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
         if ($users->checkUserRight(3)) echo'<a href="countries_update.php'.Odkaz.'&amp;id='.$write["id"].'" title="Edit country">';
         echo '<b>'.$write['name'].'</b></td>';
         if ($users->checkUserRight(3)) echo'</a>';
        echo '<td style="white-space:nowrap" class="t-center">'.$write['shortcut'].'</td>';
        echo '<td class="t-left" style="white-space:nowrap">';
        if ($users->checkUserRight(3)) echo '<a href="countries_action.php'.Odkaz.'&amp;action=switch&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Switch status">';
        
        switch ($write['is_important']){
          case 0:
            $status_id_color=2;
            $status_name="NO";
          break;
          case 1:
            $status_id_color=3;
            $status_name="YES";
          break;
        }
        echo '<span class="label label-0'.$status_id_color.'">'.$status_name.'</span>';
        if ($users->checkUserRight(3)) echo '</a>';
        echo'</td>';
        echo '<td style="white-space:nowrap" class="t-center"><a class="ico-settings" href="countries_names.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign different name">assign name ('.$write['num_countries'].')</a></td>';
        echo '<td style="white-space:nowrap" class="t-center"><a class="ico-settings" href="countries_players.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign notable players">notable players ('.$write['num_players'].')</a></td>';
        echo '<td style="white-space:nowrap" class="t-center"><a class="ico-show" href="../mod_leagues/leagues.php'.Odkaz.'&amp;filter2='.$write["id"].'" title="Show leagues">'.$write['num_leagues'].' leagues</a></td>';
        $last_update=$write["last_update"];
        if ($last_update=='0000-00-00 00:00:00') $last_update='-'; else $last_update=date("d.m.y",strtotime($last_update));
        echo '<td style="white-space:nowrap" class="smaller low">'.$last_update.' | '.$users->GetUserSignatureID($write["last_update_user"]).'</td>';
        echo '
        <td style="white-space:nowrap">';
          if ($users->checkUserRight(3)) echo'<a href="countries_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
          if ($users->checkUserRight(4) and $write['num_leagues']==0 and $write['num_countries']==0) echo'<a href="javascript:delete_item(\''.$users->getSesid().'\','.$write["id"].',\''.$write["name"].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
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
