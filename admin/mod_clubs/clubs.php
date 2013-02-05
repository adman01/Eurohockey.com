<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(25,1,0,0,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("js/functions.js");
echo $head->setJavascriptExtendFile("../inc/js/leagues_box.js");

echo $head->setTitle(langGlogalTitle."Leagues");

if(!empty($ArSpecialRight[2])) $sqlRightWhere="WHERE ".str_replace("id_league","id",$ArSpecialRight[2]); else $sqlRightWhere="";
  $query="SELECT id FROM leagues WHERE name LIKE '%".$q."%' ".$sqlRightWhere." ORDER BY name";
  //echo $query 
?>
<script type="text/javascript">
	$(document).ready(function(){
		$(".tabs > ul").tabs();
		$.tablesorter.defaults.sortList = [[2,0]];
		$("table").tablesorter({
			headers: {
			  7: { sorter: false },
				8: { sorter: false },
				9: { sorter: false },
				0: { sorter: false }
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
		<div id="content" class="box" style="width:1350px">

    <!-- hlavni text -->
    
	<h1>Clubs</h1>
    <p class="box">
       <?php if ($users->checkUserRight(2)){?><a href="clubs_add.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-create"><span>Add new club</span></a><?php } ?>
       <a href="../mod_arenas/arenas.php<?echo Odkaz;?>"  class="btn-info"><span>Arenas</span></a>
       <a href="../mod_leagues/leagues.php<?echo Odkaz;?>"  class="btn-info"><span>Leagues</span></a>
    	 <a href="../mod_countries/countries.php<?echo Odkaz;?>"  class="btn-info"><span>Countries</span></a>
	  </p>
	  
	  	<? 
	  	switch ($_GET['message']){
        case 1:
          echo '<p class="msg done">club has been added. <a href="clubs_add.php'.Odkaz.'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'">Add another club</a> ?</p>';
        break;
        case 2:
          echo '<p class="msg done">club has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">club has been changed. <a href="clubs_update.php'.Odkaz.'&id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'">Change club again</a>.</p>';
        break;
        case 4:
          echo '<p class="msg done"><b>'.$_GET['intCorrect'].'</b> clubs has been assigned.</p>';
          if (!empty($intError)) echo '<p class="msg error"><b>'.$_GET['intError'].'</b> errors.</p>';
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
			 <td><span class="label label-05">search&nbsp;club</span></td>
			 <td>
			   <div style="position:relative">
          <input type="text" name="filter" id="filter" autocomplete="off" value="<?php echo $_GET['filter']; ?>" class="input-text" onkeyup="send_livesearch_data(this.value)" />
          <div id="livesearch"></div>
         </div>
         
       </td>
        <td>
        <? show_league_select_box($con,1,$_GET['filter2'],"filter2",$users->getSesid(),$users->getIdPageRight());?>
        </td>
        <td>
        <b>OR</b>
        &nbsp;<span class="label label-04">country</span>
        <select name="filter3" class="input-text">
			  <option value="0">select country</option>
			  <?php
        if(!empty($ArSpecialRight[1])) $sqlRightWhere="WHERE ".str_replace("id_country","id",$ArSpecialRight[1]); else $sqlRightWhere="";
			  $query="SELECT * FROM countries ".$sqlRightWhere." ORDER by name";
        $result = $con->SelectQuery($query);
        while($write = $result->fetch_array()){
        //$games->GetActualLeagueName($write['id'],ActSeason)
        echo '
        <option value="'.$write['shortcut'].'"'.write_select($write['shortcut'],$_GET['filter3']).'>'.$write['name'].'</option>
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
					  <th>[x]</th>
					  <th>ID</th>
						<th>Default club name</th>
						<th>Country</th>
						<th>Status</th>
						<th>National</th>
						<th>League</th>
						<th>Assigns</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
	  <?php
	  If (!IsSet($_GET['order']) OR ($_GET['order'] == ""))	$order="name ASC"; else $order=$_GET['order'];
    If (!empty($_GET['filter']))	$filter=" AND (clubs.name LIKE '%".$_GET['filter']."%' or clubs.id LIKE '%".$_GET['filter']."%' OR clubs.short_name LIKE '%".$_GET['filter']."%' OR clubs.nickname LIKE '%".$_GET['filter']."%')"; else $filter="";
    
    If(!empty($ArSpecialRight[4])) $sqlRightWhere="AND ".str_replace("id_country","id_country",$ArSpecialRight[4]); else $sqlRightWhere="";
    If (!empty($_GET['filter2']) AND $_GET['filter2']>0){
      //filtr dle ligy
      if(!empty($ArSpecialRight[2])) $sqlRightWhereLeague="AND ".str_replace("id_league","clubs_leagues_items.id_league",$ArSpecialRight[2]); else $sqlRightWhereLeague="";
      $query="
      SELECT name,clubs.id as id,last_update,last_update_user,id_status,id_country,is_national_team  FROM clubs
      INNER JOIN clubs_leagues_items
      ON clubs.id=clubs_leagues_items.id_club WHERE clubs.id>0 AND clubs_leagues_items.id_league=".$_GET['filter2']." ".$sqlRightWhere." ".$sqlRightWhereLeague." ".$filter." ORDER BY ".$order."";
    }elseif (!empty($_GET['filter3'])){
      //filtr dle zeme
      if(!empty($ArSpecialRight[2])){
        if(!empty($ArSpecialRight[2])) $sqlRightWhereLeague="AND ".str_replace("id_league","clubs_leagues_items.id_league",$ArSpecialRight[2]); else $sqlRightWhereLeague="";
        $query="
          SELECT name,clubs.id as id,last_update,last_update_user,id_status,id_country,is_national_team  FROM clubs
          INNER JOIN clubs_leagues_items
          ON clubs.id=clubs_leagues_items.id_club WHERE clubs.id>0 AND clubs_leagues_items.id_league=".$_GET['filter2']." ".$sqlRightWhere." ".$sqlRightWhereLeague." ".$filter." ORDER BY ".$order."";
      }else{
        $query="SELECT name,id,last_update,last_update_user,id_status,id_country,is_national_team
        FROM clubs WHERE id>0 ".$sqlRightWhere."  ".$filter." ".$filter2." and (id_country='".$_GET['filter3']."') ORDER BY ".$order."";
      } 
      
    }else{
      //normalni situace
      if(!empty($ArSpecialRight[2])){
        if(!empty($ArSpecialRight[2])) $sqlRightWhereLeague="AND ".str_replace("id_league","clubs_leagues_items.id_league",$ArSpecialRight[2]); else $sqlRightWhereLeague="";
        $query="
          SELECT name,clubs.id as id,last_update,last_update_user,id_status,id_country,is_national_team  FROM clubs
          INNER JOIN clubs_leagues_items
          ON clubs.id=clubs_leagues_items.id_club WHERE clubs.id>0 AND clubs_leagues_items.id_club=clubs.id ".$sqlRightWhere." ".$sqlRightWhereLeague." ".$filter." ORDER BY ".$order."";
      }else{
      //if(!empty($ArSpecialRight[2])) $sqlRightWhereLeague="  AND (SELECT count(*) FROM clubs_leagues_items WHERE clubs_leagues_items.id_club=clubs.id AND (".str_replace("clubs_leagues_items.id_league","id_league",$ArSpecialRight[2])."))>0"; else $sqlRightWhereLeague="";
      $query="SELECT name,id,last_update,last_update_user,id_status,id_country,is_national_team
        FROM clubs WHERE id>0 ".$sqlRightWhere." ".$sqlRightWhereLeague." ".$filter." ".$filter2." ORDER BY ".$order."";
      } 
        
    }

    If (empty($_GET['filter3'])){
    //listovani
    $listovani = new listing($con,$_SERVER["SCRIPT_NAME"].Odkaz."&amp;filter=".$_GET['filter']."&amp;filter2=".$_GET['filter2']."&amp;filter3=".$_GET['filter3']."&amp;",30,$query,3,"",$_GET['list_number']);
    $query=$listovani->updateQuery();
    //echo $query;
    }
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	   
	   $query_sub="SELECT id_league FROM clubs_leagues_items WHERE id_club=".$write["id"]." ORDER BY id DESC";
	   $num_leagues=$con->GetQueryNum($query_sub);
	   $result_sub = $con->SelectQuery($query_sub);
	   $write_sub = $result_sub->fetch_array();
	   $id_league=$write_sub['id_league'];
	   
	   $query_sub="SELECT count(*) as pocet FROM clubs_names WHERE id_club=".$write["id"];
	   $result_sub = $con->SelectQuery($query_sub);
	   $write_sub = $result_sub->fetch_array();
	   $num_name=$write_sub['pocet'];
	   
	   $query_sub="SELECT count(*) as pocet FROM clubs_leagues_items WHERE id_club=".$write["id"];
	   $result_sub = $con->SelectQuery($query_sub);
	   $write_sub = $result_sub->fetch_array();
	   $num_leagues=$write_sub['pocet'];
	   
	   $query_sub="SELECT count(*) as pocet FROM clubs_images WHERE id_club=".$write["id"];
	   $result_sub = $con->SelectQuery($query_sub);
	   $write_sub = $result_sub->fetch_array();
	   $num_logo=$write_sub['pocet'];
	   
	   $query_sub="SELECT count(*) as pocet FROM clubs_arenas_items WHERE id_club=".$write["id"];
	   $result_sub = $con->SelectQuery($query_sub);
	   $write_sub = $result_sub->fetch_array();
	   $num_arena=$write_sub['pocet'];
	   
	   $query_sub="SELECT count(*) as pocet FROM clubs_players_items WHERE id_club=".$write["id"];
	   $result_sub = $con->SelectQuery($query_sub);
	   $write_sub = $result_sub->fetch_array();
	   $num_players=$write_sub['pocet'];
	   
	   $query_sub="SELECT count(*) as pocet FROM clubs_farm_items WHERE id_club=".$write["id"]." OR id_farm=".$write["id"];
	   $result_sub = $con->SelectQuery($query_sub);
	   $write_sub = $result_sub->fetch_array();
	   $num_farms=$write_sub['pocet'];
	   
	   $query_sub="SELECT count(*) as pocet FROM stats WHERE id_club=".$write["id"];
	   $result_sub = $con->SelectQuery($query_sub);
	   $write_sub = $result_sub->fetch_array();
	   $num_stats=$write_sub['pocet'];
	   
	   $query_sub="SELECT count(*) as pocet FROM clubs_links WHERE id_club=".$write["id"];
	   $result_sub = $con->SelectQuery($query_sub);
	   $write_sub = $result_sub->fetch_array();
	   $num_links=$write_sub['pocet'];
	   
	  $bollshow=true;
	  if ($bollshow) {
	  if ($a%2) $style=""; else $style="";
  		 echo '<tr class="'.$style.'">
  		  <td class="t-center"><input type="checkbox" id="checkbox" name="id['.$a.']" value="'.$write['id'].'" /></td>
  		  <td>'.$write["id"].'</td>
  		  <td class="high-bg">';
         if ($users->checkUserRight(3)) echo'<a href="clubs_update.php'.Odkaz.'&amp;id='.$write["id"].'" title="Edit club">';
         echo '<b>'.$write["name"].'</b></td>';
         if ($users->checkUserRight(3)) echo'</a>';
        echo '<td class="t-center"><b>';
        $id_country=$write["id_country"];
        if (empty($id_country)) {
          echo '<span class="ico-delete">N/A</span>';}
          else{
          echo $id_country;
        }
        echo '</b></td>';
        echo '<td class="t-left" style="white-space:nowrap">';
        if ($users->checkUserRight(3)) echo '<a href="clubs_action.php'.Odkaz.'&amp;action=switch&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Switch status">';
        
       $query_sub="SELECT id,name FROM clubs_status_list WHERE id=".$write["id_status"];
       $result_sub = $con->SelectQuery($query_sub);
	     $write_sub = $result_sub->fetch_array();
  	   $status_name=$write_sub['name'];
	     $status_id=$write_sub['id'];
        
        switch ($status_id){
          case 1:
            $status_id_color=2;
          break;
          case 2:
            $status_id_color=3;
          break;
          case 3:
            $status_id_color=1;
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
        if ($users->checkUserRight(3)) echo '<a href="clubs_action.php'.Odkaz.'&amp;action=switch_national&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Switch status">';
        
        switch ($write['is_national_team']){
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
        echo '<td class="" style="white-space:nowrap">';
        if (empty($id_league)) {
          echo 'N/A';}
          else{
            echo '<a href="clubs.php'.Odkaz.'&amp;filter2='.$id_league.'" title="Filter league item">'.$games->GetActualLeagueName($id_league,ActSeason).'';
            $query_sub="SELECT id FROM clubs_leagues_items WHERE id_league=".$id_league;
            echo ' ('.$con->GetQueryNum($query_sub).')</a>';
        }
        echo '</b></td>';
        echo '<td style="white-space:nowrap" class="t-left">
        <a class="ico-settings" href="clubs_assign.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign league">leagues&nbsp;('.$num_leagues.')</a>
        <a class="ico-settings" href="clubs_names.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign different name">name&nbsp;('.$num_name.')</a>
        <a class="ico-settings" href="clubs_images.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign logo">logo&nbsp;('.$num_logo.')</a>
        <a class="ico-settings" href="clubs_players.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign notable players">notable&nbsp;('.$num_players.')</a><br />
        <a class="ico-settings" href="clubs_arenas.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign arenas">arenas&nbsp;('.$num_arena.')</a>
        <a class="ico-settings" href="clubs_farms.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign farm affiliation">farm&nbsp;('.$num_farms.')</a>
        <a class="ico-settings" href="clubs_links.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign link">links&nbsp;('.$num_links.')</a>
        <a class="ico-settings" href="../mod_players/players_stats_club.php'.Odkaz.'&amp;id='.$write["id"].'" title="Edit stats">edit&nbsp;stats('.$num_stats.')</a>
        </td>';
        
        echo'<td style="white-space:nowrap">';
          if ($users->checkUserRight(3)) echo'<a href="clubs_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
          if ($users->checkUserRight(4) and $num_leagues==0 and $num_name==0 and $num_stats==0  and $num_farms==0  and $num_players==0  and $num_arena==0  and $num_logo==0 ){ 
          echo'<a href="javascript:delete_item(\''.$users->getSesid().'\','.$write["id"].',\''.$write["name"].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
          }
        echo "
        </td>";
        $last_update=$write["last_update"];
        if ($last_update=='0000-00-00 00:00:00') $last_update='-'; else $last_update=date("d.m.y",strtotime($last_update));
        echo '<td class="smaller low">'.$last_update.' | '.$users->GetUserSignatureID($write["last_update_user"]).'</td>';
        echo "</tr>\n";
  		 $a++;
  		}
	   }
	  echo '<tfoot>';
    echo '
          <tr class="bg">
              <td class="t-center">
              <input type="checkbox" id="check_all" name="check_all" onclick="CheckAll(this.form,\'checkbox\');" />
              </td>
					    <td colspan="12" class="arrow-01">
					    assign selected clubs to league   
						  &nbsp;&nbsp;<select name="id_league" class="input-text">
              ';						    
						    $query="SELECT * FROM leagues ORDER by name";
                $result = $con->SelectQuery($query);
                while($write = $result->fetch_array()){
                //$games->GetActualLeagueName($write['id'],ActSeason)
                echo '
                <option value="'.$write['id'].'"'.write_select($write['id'],$_GET['filter2']).'>'.$write['name'].'</option>
                ';
               } 
						   echo' 
						  </select>&nbsp;<input type="submit" value="OK" />
						  <input type="hidden" name="action" value="assign_league_grouped" />
	            <input type="hidden" name="filter" value="'.$_GET['filter'].'" />
	            <input type="hidden" name="filter2" value="'.$_GET['filter2'].'" />
	            <input type="hidden" name="filter3" value="'.$_GET['filter3'].'" />
	            <input type="hidden" name="list_number" value="'.$_GET['list_number'].'" />
	            <input type="hidden" name="number" value="'.$a.'" />
	           '.OdkazForm.'
						</td>
					</tr>
	  ';
	  If (empty($_GET['filter3'])){
	   //listovani
      $listovani->show_list();
    }
    echo '</tfoot>';
	   
	  }else{
      echo '<tr><td colspan="12" class=""><p class="msg warning">No data found</p></td></tr>';
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
