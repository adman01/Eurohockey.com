<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(24,1,0,0,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("js/functions.js");

echo $head->setTitle(langGlogalTitle."Leagues");
?>
<script type="text/javascript">
	$(document).ready(function(){
		$(".tabs > ul").tabs();
		$.tablesorter.defaults.sortList = [[1,0]];
		$("table").tablesorter({
			headers: {
				6: { sorter: false },
				8: { sorter: false }
			}
		});
	});
</script>
<?php
echo $head->setEndHead();
$strAdminMenu="leagues";
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
		<div id="content" class="box" style="width:1350px">

    <!-- hlavni text -->
    
	<h1>leagues</h1>
    <p class="box">
       <?php if ($users->checkUserRight(2)){?><a href="leagues_add.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-create"><span>Add new league</span></a><?php } ?>
    	 <a href="../mod_countries/countries.php<?echo Odkaz;?>"  class="btn-info"><span>Countries</span></a>
    	 <a href="../mod_clubs/clubs.php<?echo Odkaz;?>"  class="btn-info"><span>Clubs</span></a>
    	 
	  </p>
	  
	  	<? 
	  	switch ($_GET['message']){
        case 1:
          echo '<p class="msg done">league has been added. <a href="leagues_add.php'.Odkaz.'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'">Add another league</a> ?</p>';
        break;
        case 2:
          echo '<p class="msg done">league has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">league has been changed. <a href="leagues_update.php'.Odkaz.'&id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'">Change league again</a>.</p>';
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
        if(!empty($ArSpecialRight[1])) $sqlRightWhere="WHERE ".str_replace("id_country","id",$ArSpecialRight[1]); else $sqlRightWhere="";
			  $query="SELECT * FROM countries ".$sqlRightWhere." ORDER by name";
        $result = $con->SelectQuery($query);
        while($write = $result->fetch_array()){
        echo '
        <option value="'.$write['id'].'"'.write_select($write['id'],$_GET['filter2']).'>'.$games->GetActualCountryName($write['id'],ActSeason).'</option>
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
					  <th>ID</th>
						<th>Default league name</th>
						<th>Youth</th>
						<th>Status</th>
						<th>Tournament</th>
						<th>Country</th>
						<th>Assigns</th>
						<th>Clubs</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
	  <?php
	  
	  
    If (!IsSet($_GET['order']) OR ($_GET['order'] == ""))	$order="name ASC"; else $order=$_GET['order'];
    If (!empty($_GET['filter']))	$filter=" AND (name LIKE '%".$_GET['filter']."%' OR shortcut LIKE '%".$_GET['filter']."%')"; else $filter="";
    If (!empty($_GET['filter2']) AND $_GET['filter2']>0)	$filter2=" AND (SELECT count(*) FROM leagues_countries_items WHERE leagues_countries_items.id_country=".$_GET['filter2']." AND leagues_countries_items.id_league=leagues.id)>0 "; else $filter2="";
    if(!empty($ArSpecialRight[1])) $sqlRightWhere=" AND (SELECT count(*) FROM leagues_countries_items WHERE (".str_replace("id_country","leagues_countries_items.id_country",$ArSpecialRight[1]).") AND leagues_countries_items.id_league=leagues.id)>0";
    $query="SELECT *,
            (SELECT count(*) FROM clubs_leagues_items WHERE clubs_leagues_items.id_league=leagues.id)as num_clubs, 
            (SELECT (SELECT shortcut from countries WHERE leagues_countries_items.id_country=countries.id LIMIT 1) FROM leagues_countries_items WHERE leagues_countries_items.id_league=leagues.id ORDER BY id DESC LIMIT 1) as country,
            (SELECT count(*) FROM leagues_countries_items WHERE leagues_countries_items.id_league=leagues.id) as num_country,
            (SELECT count(*) FROM leagues_past_winners WHERE leagues_past_winners.id_league=leagues.id) as num_winners,
            (SELECT count(*) FROM leagues_names WHERE leagues_names.id_league=leagues.id) as num_name 
            FROM leagues WHERE id>0 ".$sqlRightWhere." ".$filter." ".$filter2." ORDER BY ".$order."";
    //echo $query; 
    
    //listovani
    $listovani = new listing($con,$_SERVER["SCRIPT_NAME"].Odkaz."&amp;filter=".$_GET['filter']."&amp;filter2=".$_GET['filter2']."&amp;",50,$query,3,"",$_GET['list_number']);
    $query=$listovani->updateQuery();
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	  if ($a%2) $style=""; else $style="";
  		 echo '<tr class="'.$style.'">
  		  <td>'.$write["id"].'</td>
  		  <td class="high-bg">';
         if ($users->checkUserRight(3)) echo'<a href="leagues_update.php'.Odkaz.'&amp;id='.$write["id"].'" title="Edit league">';
         echo '<b>'.$write['name'].'</b></td>';
         if ($users->checkUserRight(3)) echo'</a>';
        echo '<td class="t-center" style="white-space:nowrap">';
          if ($write['youth_league']==1){
              $query_sub="SELECT name FROM leagues_youth_list WHERE id=".$write["youth_league_id"];
              $result_sub = $con->SelectQuery($query_sub);
	            $write_sub = $result_sub->fetch_array();
  	          echo $write_sub['name'];
          }else{
            echo 'senior';
          }
        echo'</td>';
        echo '<td class="t-left" style="white-space:nowrap">';
        if ($users->checkUserRight(3)) echo '<a href="leagues_action.php'.Odkaz.'&amp;action=switch&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Switch status">';
        
       $query_sub="SELECT id,name FROM leagues_status_list WHERE id=".$write["league_status"];
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
          default:
           $status_id_color=5;
          break;
        }
        echo '<span class="label label-0'.$status_id_color.'">'.$status_name.'</span>';
        if ($users->checkUserRight(3)) echo '</a>';
        echo'</td>';
        echo '<td class="t-left" style="white-space:nowrap">';
        if ($users->checkUserRight(3)) echo '<a href="leagues_action.php'.Odkaz.'&amp;action=switch_tournament&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Switch status">';
        
        switch ($write['is_tournament']){
          case 1:
            $status_id_color=5;
            $status_name="NO";
          break;
          case 2:
            $status_id_color=4;
            $status_name="YES";
          break;
          default:
           $status_id_color=5;
           $status_name="NO";
          break;
        }
        echo '<span class="label label-0'.$status_id_color.'">'.$status_name.'</span>';
        if ($users->checkUserRight(3)) echo '</a>';
        echo'</td>';
        echo '<td class="t-center" style="white-space:nowrap"><b>';
        if (empty($write['country'])) {
          echo 'N/A';}
          else{                              
          echo $write['country'];
          if ($write['num_country']>1) echo ' ('.($write['num_country']-1).' other)';
        }
        echo '</b></td>';
        echo '<td>';
        echo '<a class="ico-settings" href="leagues_assign.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign country">country&nbsp;('.$write['num_country'].')</a> ';
        echo '<a class="ico-settings" href="leagues_names.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign different data to past seasons">past seasons&nbsp;('.$write['num_name'].')</a> ';
        
        $query_sub="SELECT count(*) as pocet FROM games_stages WHERE id_league=".$write["id"];
	      $result_sub = $con->SelectQuery($query_sub);
	      $write_sub = $result_sub->fetch_array();
	      $num_stages=$write_sub['pocet'];
	      
        echo '<a class="ico-settings" href="leagues_stages.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign stage">stages&nbsp;('.$num_stages.')</a> ';
        echo '<a class="ico-settings" href="leagues_winners.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign past winners">winners&nbsp;('.$write['num_winners'].')</a> ';
        
        $query_sub="SELECT count(*) as pocet FROM stats WHERE id_league=".$write["id"];
	      $result_sub = $con->SelectQuery($query_sub);
	      $write_sub = $result_sub->fetch_array();
	      $num_stats=($write_sub['pocet']);
                
        echo '<a class="ico-settings" href="../mod_players/players_stats_league.php'.Odkaz.'&amp;id='.$write["id"].'" title="Edit statistics">stats&nbsp;<b>('.$num_stats.')</b></a></td>';
        echo '<td style="white-space:nowrap" class="t-center"><a class="ico-show" href="../mod_clubs/clubs.php'.Odkaz.'&amp;filter2='.$write["id"].'" title="Show clubs">'.$write['num_clubs'].' clubs</a></td>';
        echo'<td style="white-space:nowrap">';
          if ($users->checkUserRight(3)) echo'<a href="leagues_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
          
          if ($users->checkUserRight(4) and $write['num_clubs']==0 and $write['num_country']==0 and $write['num_name']==0 and $write['num_winners']==0  and $num_stats==0){ 
            echo'<a href="javascript:delete_item(\''.$users->getSesid().'\','.$write["id"].',\''.$write["name"].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
          }else{
            
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
