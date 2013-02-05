<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(32,1,0,0,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("js/functions.js");
echo $head->setJavascriptExtendFile("../inc/js/clubs_stats_box.js");

echo $head->setTitle(langGlogalTitle."Standings");
?>
<script type="text/javascript">
$(document).ready(function(){
  
  jQuery.validator.messages.required = "";
  
  $("#form-01").validate({
		invalidHandler: function(e, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				var message = errors == 1
					? 'You missed <b>1 field</b>. It has been highlighted below'
					: 'You missed <b>' + errors + ' fields</b>..  They have been highlighted below';
				$("div.error span").html(message);
				$("div.error").show();
			} else {
				$("div.error").hide();
			}
		},
		onkeyup: false,
	});
  
});

$(document).ready(function(){
		$(".tabs > ul").tabs();
		$.tablesorter.defaults.sortList = [[0,1]];
		$("table").tablesorter({
			headers: {
			  3: { sorter: false },
			  5: { sorter: false },
				6: { sorter: false }
				
			}
		});
	});

</script>
<?php
echo $head->setEndHead();
$strAdminMenu="standings";

$id_league=$_GET['id'];
if (!empty($id_league)) $_GET['filter']=$id_league;
      
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
		<div id="content" class="box" style="width:1250px">

    <!-- hlavni text -->
    

<h1>Standings</span></h1>

 <p class="box">
       <?php if ($users->checkUserRight(2)){?><a href="standings_add.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-create"><span>Add new standings table</span></a><?php } ?>
       <a href="../mod_games/games.php<?echo Odkaz;?>"  class="btn-info"><span>Games</span></a>
       <a href="../mod_clubs/clubs.php<?echo Odkaz;?>"  class="btn-info"><span>Clubs</span></a>
    	 <a href="../mod_leagues/leagues.php<?echo Odkaz;?>"  class="btn-info"><span>Leagues</span></a>
    	 
    	 
	  </p>
	  
	  	<? 
	  	switch ($_GET['message']){
        case 1:
          echo '<p class="msg done">standings has been added. <a href="standings_add.php'.Odkaz.'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'">Add another standings</a> ?</p>';
        break;
        case 2:
          echo '<p class="msg done">standings has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">standings has been changed. <a href="standings_update.php'.Odkaz.'&id='.$_GET['id2'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'">Change standings again</a>.</p>';
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
			 <td><span class="label label-05">selected&nbsp;league</span></td>
			 <td>
			   <div style="position:relative">
          <input type="text" name="filter_name" autocomplete="off" value="<?php echo $games->GetActualLeagueName($id_league,ActSeason);; ?>" class="input-text required" onkeyup="send_livesearch_leagues_data(this.value,'<? echo $users->getSesid(); ?>',<? echo $users->getIdPageRight();?>)" />
          <div id="livesearch" style="z-index:99"></div>
         </div>
         
       </td>
       
     	 <td><input type="submit" class="input-submit" value="filter" /></td>
		  </tr>
	 </table>
	 <input type="hidden" name="order" value="<?echo $_GET['order'];?>" />
	 <input type="hidden" name="id" id="id_league_filter" value="<?echo $_GET['id'];?>" />
	 <?echo OdkazForm;?>
  </fieldset>	
  </form>
	  
	  <table>
	  	<table class="tablesorter"> <!-- Sort this TABLE -->
				<thead>
					<tr>
					  <th>Season</th>
					  <th>Stage</th>
					  <th>League</th>
					  <th>Type</th>
						<th>Standigs name</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
	  <?php
	  
	  
    If (!IsSet($_GET['order']) OR ($_GET['order'] == ""))	$order="id_season DESC"; else $order=$_GET['order'];
    //If (!empty($_GET['filter2']) AND $_GET['filter2']>0)	$filter2=" AND (SELECT count(*) FROM leagues_countries_items WHERE leagues_countries_items.id_country=".$_GET['filter2']." AND leagues_countries_items.id_league=leagues.id)>0 "; else $filter2="";
    //if(!empty($ArSpecialRight[1])) $sqlRightWhere=" AND (SELECT count(*) FROM leagues_countries_items WHERE (".str_replace("id_country","leagues_countries_items.id_country",$ArSpecialRight[1]).") AND leagues_countries_items.id_league=leagues.id)>0";
    
    if (!empty($id_league)){ $strWhere=" AND id_league=".$id_league."";} 
    $query="SELECT * FROM standings WHERE id>0 ".$strWhere." ".$sqlRightWhere." ".$filter." ".$filter2." ORDER BY ".$order."";
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
  		 echo '<tr class="'.$style.'">';
  		  echo '<td style="white-space:nowrap">'.($write["id_season"]-1).'-'.($write["id_season"]).'</td>';

        $strStage=$con->GetSQLSingleResult("SELECT name as item FROM games_stages WHERE id=".$write['id_stage']);
        echo '<td style="white-space:nowrap">'.$strStage.'</td>';
        
        $strLeagueName=$games->GetActualLeagueName($write['id_league'],ActSeason);
  		  echo '<td style="white-space:nowrap"><a href="../mod_leagues/leagues.php'.Odkaz.'&amp;filter='.$strLeagueName.'" title="Show league" class="ico-show">'.$strLeagueName.'</a></td>';
  		  
  		  echo '<td class="t-left" style="white-space:nowrap">';
        if ($users->checkUserRight(3)) echo '<a href="standings_action.php'.Odkaz.'&amp;action=switch&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Switch status">';
        
       $query_sub="SELECT id,name FROM standings_type_list WHERE id=".$write["id_type"];
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
        }
        echo '<span class="label label-0'.$status_id_color.'">'.$status_name.'</span>';
        if ($users->checkUserRight(3)) echo '</a>';
        echo'</td>';
        
        echo '<td class="high-bg">';
         if ($users->checkUserRight(3)) echo'<a href="standings_update.php'.Odkaz.'&amp;id='.$write["id"].'" title="Edit standings">';
         echo '<b>'.$write['name'].'</b>';
         if ($users->checkUserRight(3)) echo '</a>';
         echo '</td>';
         
         $num_tabs=$con->GetSQLSingleResult("SELECT count(*) as item FROM standings_teams WHERE id_table_type=".$status_id." AND id_standings=".$write["id"]);
         
         echo'<td style="white-space:nowrap"><a class="ico-settings" href="standings_info.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit standings">edit standings table ('.$num_tabs.')</a></td>';
         
          echo'<td style="white-space:nowrap">';
          echo '<a href="standings_info.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit standings"><img src="../inc/design/ico-info.gif" class="ico" alt="Info"></a>';
          echo '&nbsp;&nbsp;';
          echo '<a onclick="return !window.open(this.href);" href="../../xml/standings/standings_'.$write["id"].'.xml" title="Show XML"><img src="../inc/design/ico-list.gif" class="ico" alt="Info"></a>';
          echo '&nbsp;&nbsp;';
          if ($users->checkUserRight(3)) echo'<a href="standings_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
          if ($users->checkUserRight(4)){ 
            echo'<a href="javascript:delete_item(\''.$users->getSesid().'\','.$write["id"].',\''.$write["name"].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
          }else{
            
          }
        echo "
        </td>";  
        
       $last_update=$write["last_update"];
        if ($last_update=='0000-00-00 00:00:00') $last_update='-'; else $last_update=date("d.m.y",strtotime($last_update));
        echo '<td class="smaller low">'.$last_update.' | '.$users->GetUserSignatureID($write["id_user"]).'</td>';
        
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
