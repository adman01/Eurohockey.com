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
    
	<h1>Countries mass update</h1>
    <p class="box">
       <?php if ($users->checkUserRight(2)){?><a href="countries_add.php<?echo Odkaz;?>"  class="btn-create"><span>Add new country</span></a><?php } ?>
       <a href="countries_mass_update.php<?echo Odkaz;?>"  class="btn-info"><span>Mass update</span></a>
    	 <a href="../mod_leagues/leagues.php<?echo Odkaz;?>"  class="btn-info"><span>Leagues</span></a>
    	 <a href="../mod_clubs/clubs.php<?echo Odkaz;?>"  class="btn-info"><span>Clubs</span></a>
    	 
	  </p>
	  
	  	<? 
	  	switch ($_GET['message']){
        case 3:
          echo '<p class="msg done"><b>'.$_GET['intCorrect'].'</b> countries has been changed.</p>';
          if (!empty($_GET['intError'])) echo '<p class="msg error"><b>'.$_GET['intError'].'</b> countries could not be changed.</p>';
        break;
        case 99:
           echo '<p class="msg error">wrong or missing input data.</p>';
        break;
      }
    
      
      echo '
    <form action="countries_action.php"  method="post">
    <table style=" z-index:50">
    ';
 
 $a = 1;
    
    $query_stats="SELECT 
      *
      FROM countries ORDER BY name ASC";
    $result_stats = $con->SelectQuery($query_stats);
    
    if ($con->GetQueryNum($query_stats)>0){
    
    echo'<tr>';
      echo '<th>Country</th>';
      echo '<th>Placement at IHWC</th>';
      echo '<th>World ranking</th>';
      echo '<th>Registered players</th>';
      echo '</tr>';
    
    while($write_stats = $result_stats->fetch_array())
	   {
	     
       if ($a%2) $style=' class="bg"'; else $style="";
	     echo '<tr'.$style.'>';
	       echo'<td><a class="ico-show" href="countries.php'.Odkaz.'&amp;filter='.$write_stats["name"].'" title="Show item">'.$write_stats['name'].'</a></td>';
         echo'<td><input type="text" name="placement_at_IHWC['.$a.']" size="20" maxlength="50" value="'.$write_stats['placement_at_IHWC'].'" />';
         echo'<input type="hidden" name="id_country['.$a.']" value="'.$write_stats['id'].'" />';
         echo '</td>';
         echo'<td><input type="text" name="world_ranking['.$a.']" size="20" maxlength="50" value="'.$write_stats['world_ranking'].'" /></td>';
         echo'<td><input type="text" name="registered_players['.$a.']" size="20" maxlength="50" value="'.$write_stats['registered_players'].'" /></td>';
            
            
        echo "</tr>\n";
        $a++;
        
  }
     
      }else{
             echo '<p class="msg warning">Data not found</p>';
          
  
  }
  
  	echo '
  	</table>
  	';
    echo'
  	<p class="box-01">
			     <input type="submit" value="Update countries"  class="input-submit" />
		</p>
  
        <input type="hidden" name="action" value="update_mass_countries" />
	      <input type="hidden" name="number" id="number" value="'. $a.'" />
	      <input type="hidden" name="filter" value="'. $_GET['filter'].'" />
	      <input type="hidden" name="filter2" value="'. $_GET['filter2'].'" />
	      <input type="hidden" name="list_number" value="'. $_GET['list_number'].'" />
	      '. OdkazForm.'
	  ';
	echo'
	</form>
  ';
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
