<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(29,1,0,0,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("js/functions.js");

echo $head->setTitle(langGlogalTitle."Transfers");
echo $head->setEndHead();
$strAdminMenu="transfers";
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
		<div id="content" class="box" style="width:1100px">

    <!-- hlavni text -->
    
	<h1>Transfers</h1>
    <p class="box">
       <?php if ($users->checkUserRight(2)){?><a href="transfers_add.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-create"><span>Add new transfer</span></a><?php } ?>
	  </p>
	  
	  	<? 
	  	switch ($_GET['message']){
        case 1:
          echo '<p class="msg done"><b>'.$_GET['intCorrect'].'</b> transfers has been added.</p>';
          if (!empty($_GET['intError'])) echo '<p class="msg error"><b>'.$_GET['intError'].'</b> transfers could not be added.</p>';
        break;
        case 2:
          echo '<p class="msg done">transfer has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">transfer has been changed. <a href="transfers_update.php'.Odkaz.'&id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'">Change transfer again</a>.</p>';
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
			 <td><span class="label label-05">search player</span></td>
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
						<th>Date</th>
            <th>Player</th>
            <th>Position</th>
            <th>Retire</th>
            <th>from</th>
            <th>to</th>
            <th>Credibility</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
	  <?php
    If (!IsSet($_GET['order']) OR ($_GET['order'] == ""))	$order="transfers.date_time DESC, transfers.id DESC"; else $order=$_GET['order'];
    If (!empty($_GET['filter']))	$filter=" AND (note LIKE '%".$_GET['filter']."%' OR source LIKE '%".$_GET['filter']."%' OR CONCAT_WS(\" \",players.name,players.surname) LIKE '%".$_GET['filter']."%' or CONCAT_WS(\" \",players.surname,players.name) LIKE '%".$_GET['filter']."%' or players.name LIKE '%".$_GET['filter']."%' or players.surname LIKE '%".$_GET['filter']."%')"; else $filter="";
    If (!empty($_GET['filter2']))	{
      $strCountry=$con->GetSQLSingleResult("SELECT shortcut as item FROM countries WHERE id=".$_GET['filter2']."");
      $filter2=" AND (id_country_from='".$_GET['filter2']."' OR id_country_to='".$_GET['filter2']."' OR 
                (SELECT id FROM clubs WHERE clubs.id_country='".$strCountry."' AND clubs.id=transfers.id_club_from)>0 OR
                (SELECT id FROM clubs WHERE clubs.id_country='".$strCountry."' AND clubs.id=transfers.id_club_to)>0
                )
                ";                                                                                   
                                                                                                                
    }
                                                                                                      
    else {
      
      $filter2="";
    }
    //if(!empty($ArSpecialRight[1])) $sqlRightWhere="AND (".str_replace("id_country","id_country_from",$ArSpecialRight[1])." OR ".str_replace("id_country","id_country_to",$ArSpecialRight[1])."  OR (SELECT id FROM clubs WHERE ".$ArSpecialRight[1]." AND clubs.id=transfers.id_club_from)>0R) "; else $sqlRightWhere="";
    $query="SELECT transfers.*
            FROM transfers 
            INNER JOIN players ON transfers.id_player = players.id
            WHERE transfers.id>0 ".$filter." ".$filter2." ORDER BY ".$order."";
    //echo $query;                  
    
    //listovani
    $listovani = new listing($con,$_SERVER["SCRIPT_NAME"].Odkaz."&amp;filter=".$_GET['filter']."&amp;filter2=".$_GET['filter2']."&amp;",25,$query,3,"",$_GET['list_number']);
    $query=$listovani->updateQuery();
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	  if ($a%2) $style=""; else $style="";
  		 echo '<tr class="'.$style.'">
  		  <td>'.date("d.m.Y",strtotime($write["date_time"])).'</td>
        <td class="high-bg">';
         if ($users->checkUserRight(3)) echo'<a href="transfers_update.php'.Odkaz.'&amp;id='.$write["id"].'" title="Edit transfer">';
          $query_sub="SELECT CONCAT(name,' ',surname) as player FROM players WHERE id=".$write["id_player"];
          $result_sub = $con->SelectQuery($query_sub);
	        $write_sub = $result_sub->fetch_array();
	        $strPlayer=$write_sub['player'];
         echo '<b>'.$write_sub['player'].'</b></td>';
         if ($users->checkUserRight(3)) echo'</a>';
        
        $query_sub="SELECT name FROM transfers_position_list WHERE id=".$write["id_position"];
        $result_sub = $con->SelectQuery($query_sub);
	      $write_sub = $result_sub->fetch_array();
  	    echo '<td class="t-left">'.$write_sub['name'].'</td>';
  	    
  	    $query_sub="SELECT name FROM transfers_retire_list  WHERE id=".$write["id_retire_status"];
        $result_sub = $con->SelectQuery($query_sub);
	      $write_sub = $result_sub->fetch_array();
  	    echo '<td class="t-left">'.$write_sub['name'].'</td>';
  	    
  	    if (!empty($write['id_club_from'])){
  	     $strFrom=$games->GetActualClubName($write['id_club_from'],ActSeason);
  	     if (!empty($write['id_league_from']))$strFrom.='<p class="low smaller">'.$games->GetActualLeagueName($write['id_league_from'],ActSeason).'</p>';
  	    }else{
  	     if (!empty($write['id_country_from'])){
          $strFrom=$games->GetActualCountryName($write['id_country_from'],ActSeason);
         }else{
          $strFrom="?";
         }
        }
  	    echo '<td class="t-left" style="white-space:nowrap"><b>'.$strFrom.'</b></td>';
  	    
  	    if (!empty($write['id_club_to'])){
  	     $strTo=$games->GetActualClubName($write['id_club_to'],ActSeason);
  	     if (!empty($write['id_league_to']))$strTo.='<p class="low smaller">'.$games->GetActualLeagueName($write['id_league_to'],ActSeason).'</p>';
  	    }else{
  	     if (!empty($write['id_country_to'])){
          $strTo=$games->GetActualCountryName($write['id_country_to'],ActSeason);
         }else{
          $strTo="?";
         }
        }
  	    echo '<td class="t-left" style="white-space:nowrap"><b>'.$strTo.'</b></td>';
  	    
  	    $query_sub="SELECT name FROM transfers_source_list WHERE id=".$write["id_source_note"];
        $result_sub = $con->SelectQuery($query_sub);
	      $write_sub = $result_sub->fetch_array();
  	    echo '<td class="t-left">'.$write_sub['name'].'</td>';
  	    
  	    echo'<td style="white-space:nowrap">';
          if ($users->checkUserRight(3)) echo'<a href="transfers_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
          
          if ($users->checkUserRight(4)){ 
            echo'<a href="javascript:delete_transfer(\''.$users->getSesid().'\','.$write["id"].',\''.$strPlayer.'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
          }
        echo "
        </td>";
        $last_update=$write["last_update"];
        if ($last_update=='0000-00-00 00:00:00') $last_update='-'; else $last_update=date("d.m.y H:i",strtotime($last_update));
        echo '<td class="smaller low">'.$last_update.' | '.$users->GetUserSignatureID($write["last_update_user"]).'</td>';
        echo "</tr>\n";
        
        if (!empty($write['id_club_to'])){
          $intId_club_to=$write['id_club_to'];
          
           $query_sub="SELECT id_league FROM clubs_leagues_items INNER JOIN leagues ON leagues.id=clubs_leagues_items.id_league WHERE id_club=".$intId_club_to. " ORDER BY id_order ASC, leagues.id ASC";
           $result_sub = $con->SelectQuery($query_sub);
	         $write_sub = $result_sub->fetch_array();
  	       $intId_league_to=$write_sub['id_league'];
  	       echo '<tr><td colspan="9" class="smaller low">If you assume that this player plays in league where we follow detailed game stats, please don\'t forget to <a href="../mod_players/players_stats.php'.Odkaz.'&amp;id='.$write["id_player"].'&amp;id_season='.ActSeason.'&amp;id_club='.$intId_club_to.'&amp;id_league='.$intId_league_to.'">assign this player to respective club roster/stats</a>.</td></tr>';
        }   
        
        
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
