<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(29,1,1,0,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
require_once("../inc/tinymce.inc");
echo $head->setTitle(langGlogalTitle."Add new transfers");
echo $head->setJavascriptExtendFile("js/functions.js");
echo $head->setJavascriptExtendFile("../inc/js/players_stats_box.js");
echo $head->setJavascriptExtendFile("../inc/js/clubs_stats_box.js");
echo $head->setJavascriptExtendFile("../inc/js/leagues_stats_box.js");
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
		<div id="content" class="box">

    <!-- hlavni text -->
    
	<h1>Add new transfers</h1>
    <p class="box">
    	 <a href="transfers.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of transfers</span></a>
	  </p>
	  
	  
	  	<? 
	  	if (!empty($_GET['message'])){
	  	echo '<p class="msg error">';
			switch ($_GET['message']){
			 case 98:
          echo 'database error';
        break;
        case 99:
          echo 'wrong or missing input data';
        break;
      }
      echo '</p>';
      }
      ?>
    
    <div class="msg info error" style="display:none;">
      <span></span>.
    </div>
	  	  
<form action="transfers_action.php" method="post" id="form-01">
<fieldset>
	<legend>Add transfers</legend>
  
  <p class="box-01">
			<input type="submit" value="Add new transfers"  class="input-submit" />
			</p>
  
  <form action="players_stats_action.php" method="post">
  <table style=" z-index:50" class="tablesorter">
    <thead>
    <tr>
      <th>Date</th>
      <th>Player</th>
      <th>Position</th>
      <th>Retire</th>
      <th colspan="2">transfer from</th>
      <th colspan="2">transfer to</th>
      <th>Note</th>
      <th>Source</th>
      <th>Credibility</th>
    </tr>
    </thead>	

      
	<?
   
	for ($i=1;$i<=10;$i++) {
  
  if ($i%2) $style=' class="bg"'; else $style="";
	     echo '<tr'.$style.'>';
	        
          echo '<td><input type="text" name="date_time['.$i.']" size="8" maxlength="10" value="'.date("d.m.Y").'" /></td>';
          echo '<td>';
          show_player_stats_select_box($con,$games,$i,0,"id_player[".$i."]");
          echo '</td>';
          echo'<td>';
          echo '<select name="id_position['.$i.']" id="id_position['.$i.']" class="input-text">';   
              $query_sub="SELECT * FROM transfers_position_list ORDER by id";
              $result_sub = $con->SelectQuery($query_sub);
              while($write_sub = $result_sub->fetch_array()){
                echo '<option value="'.$write_sub['id'].'">'.$write_sub['name'].'</option>';
              }
          echo '</select>';   
          echo '</td>';
          
          echo'<td>';
          echo '<select name="id_retire_status['.$i.']" id="id_retire_status['.$i.']" class="input-text">';   
              $query_sub="SELECT * FROM transfers_retire_list ORDER by id";
              $result_sub = $con->SelectQuery($query_sub);
              while($write_sub = $result_sub->fetch_array()){
                echo '<option value="'.$write_sub['id'].'">'.$write_sub['name'].'</option>';
              }
          echo '</select>';   
          echo '</td>';
          echo '<td style="white-space:nowrap">';
            echo '<input type="radio" checked="checked" name="is_from['.$i.']" value="1" class="input-text" onclick="document.getElementById(\'box_from_'.$i.'_1\').style.display=\'\'; document.getElementById(\'box_from_'.$i.'_2\').style.display=\'none\'; document.getElementById(\'box_from_'.$i.'_3\').style.display=\'none\';">&nbsp;club<br />';
            echo '<input type="radio" name="is_from['.$i.']" value="2" class="input-text" onclick="document.getElementById(\'box_from_'.$i.'_1\').style.display=\'none\'; document.getElementById(\'box_from_'.$i.'_2\').style.display=\'\'; document.getElementById(\'box_from_'.$i.'_3\').style.display=\'none\';">&nbsp;country<br />';
            echo '<input type="radio" name="is_from['.$i.']" value="3" class="input-text" onclick="document.getElementById(\'box_from_'.$i.'_1\').style.display=\'none\'; document.getElementById(\'box_from_'.$i.'_2\').style.display=\'none\'; document.getElementById(\'box_from_'.$i.'_3\').style.display=\'\';">&nbsp;?';
          echo '</td>';
          echo '<td>';
          
          echo '<div id="box_from_'.$i.'_1">
            <p class="low smaller">select club:</p>';
            show_club_stats_select_box ($con,$games,($i+100),0,ActSeason,"id_club_from[".$i."]");
            echo '<p class="low smaller">select league:</p>';
            $boolNoAnchor=true;
            show_league_stats_select_box ($con,$games,($i+100),0,ActSeason,"id_league_from[".$i."]",'id_type',0);
          echo '</div';
          
          echo '<div id="box_from_'.$i.'_2" style="display:none">';
            echo '
              <p class="low smaller">select country:</p>
              <select name="id_country_from['.$i.']" class="input-text">
            ';
            $query_sub="SELECT * FROM countries ".$sqlRightWhere." ORDER by name";
            $result_sub = $con->SelectQuery($query_sub);
            while($write_sub = $result_sub->fetch_array()){
            echo '<option value="'.$write_sub['id'].'">'.$games->GetActualCountryName($write_sub['id'],ActSeason).'</option>';
            }
            echo '
              </select>
          </div';
          
          echo '<div id="box_from_'.$i.'_3" style="display:none">';
            echo '<p class="low smaller">destination:</p>unknown';
          echo '</div';
          
          echo '</td>';
           echo '<td style="white-space:nowrap">';
            echo '<input type="radio" checked="checked" name="is_to['.$i.']" value="1" class="input-text" onclick="document.getElementById(\'box_to_'.$i.'_1\').style.display=\'\'; document.getElementById(\'box_to_'.$i.'_2\').style.display=\'none\'; document.getElementById(\'box_to_'.$i.'_3\').style.display=\'none\';">&nbsp;club<br />';
            echo '<input type="radio" name="is_to['.$i.']" value="2" class="input-text" onclick="document.getElementById(\'box_to_'.$i.'_1\').style.display=\'none\'; document.getElementById(\'box_to_'.$i.'_2\').style.display=\'\'; document.getElementById(\'box_to_'.$i.'_3\').style.display=\'none\';">&nbsp;country<br />';
            echo '<input type="radio" name="is_to['.$i.']" value="3" class="input-text" onclick="document.getElementById(\'box_to_'.$i.'_1\').style.display=\'none\'; document.getElementById(\'box_to_'.$i.'_2\').style.display=\'none\'; document.getElementById(\'box_to_'.$i.'_3\').style.display=\'\';">&nbsp;?';
          echo '</td>';
          echo '<td>';
           echo '<div id="box_to_'.$i.'_1">
            <p class="low smaller">select club:</p>';
            show_club_stats_select_box ($con,$games,$i,0,ActSeason,"id_club_to[".$i."]");
            echo '<p class="low smaller">select league:</p>';
            $boolNoAnchor=true;
            show_league_stats_select_box ($con,$games,$i,0,ActSeason,"id_league_to[".$i."]",'id_type',0);
          echo '</div';
          echo '<div id="box_to_'.$i.'_2" style="display:none">';
            echo '
              <p class="low smaller">select country:</p>
              <select name="id_country_to['.$i.']" class="input-text">
            ';
            $query_sub="SELECT * FROM countries ".$sqlRightWhere." ORDER by name";
            $result_sub = $con->SelectQuery($query_sub);
            while($write_sub = $result_sub->fetch_array()){
            echo '<option value="'.$write_sub['id'].'">'.$games->GetActualCountryName($write_sub['id'],ActSeason).'</option>';
            }
            echo '
              </select>
          </div';
          
          echo '<div id="box_to_'.$i.'_3" style="display:none">';
            echo '<p class="low smaller">destination:</p>unknown';
          echo '</div';
          
          echo '</td>';
          echo '<td><input type="text" name="note['.$i.']" size="20" value="" /></td>';
          echo '<td><input type="text" name="source['.$i.']" size="25" value="" /></td>';
          echo'<td>';
          echo '<select name="id_source_note['.$i.']" id="id_source_note['.$i.']" class="input-text">';   
              $query_sub="SELECT * FROM transfers_source_list ORDER by id";
              $result_sub = $con->SelectQuery($query_sub);
              while($write_sub = $result_sub->fetch_array()){
                echo '<option value="'.$write_sub['id'].'">'.$write_sub['name'].'</option>';
              }
          echo '</select>';   
          echo '</td>';
      
      echo "</tr>\n";
  }  	
  ?>
	 
	</table>
	
	<p class="box-01">
			<input type="submit" value="Add new transfers"  class="input-submit" />
			</p>
	<input type="hidden" name="action" value="add" />
	<input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	<input type="hidden" name="filter2" value="<?php echo $_GET['filter2'];?>" />
	<input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
	<input type="hidden" name="number" value="<?php echo $i;?>" />
	<?echo OdkazForm;?>
</fieldset>	
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
</html>