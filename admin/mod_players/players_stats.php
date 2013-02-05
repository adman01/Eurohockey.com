<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(28,1,0,0,0);
$games = new games($con);
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("js/functions.js");
echo $head->setJavascriptExtendFile("../inc/js/clubs_stats_box.js");
echo $head->setJavascriptExtendFile("../inc/js/leagues_stats_box.js");
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
		$.tablesorter.defaults.sortList = [[0,0]];
		$("table").tablesorter({
			headers: {
				1: { sorter: false }
			}
		});
	});
</script>
<?php
echo $head->setTitle(langGlogalTitle."Player statistic");
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

<?php
  if (isset($_GET['id'])) {
  $id_player=$_GET['id'];
  $query2="SELECT CONCAT(name,' ',surname) as player ,id_position FROM players WHERE id=".$id_player;
  $result2 = $con->SelectQuery($query2);
  $write2 = $result2->fetch_array();
  if ($con->GetQueryNum($query2)>0){
  $post=$write2['id_position'];
  
  $query_num="SELECT id FROM stats WHERE id_player=".$id_player." AND id_stats_type=2";
  $stats_type_num=$con->GetQueryNum($query_num);
  
?>    
<h1>Statistics: <span style="color:#FFF5CC"><? echo $write2['player']; ?></span></h1>
    
    <p class="box">
       <a href="players_stats_coach.php<?echo Odkaz;?>&amp;id=<?echo $id_player;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-create"><span>Switch to COACH statistic (<?echo $stats_type_num;?>)</span></a>
       <?php if ($users->checkUserRight(3)){?><a href="players_update.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>&amp;id=<?echo $id_player;?>"  class="btn-info"><span>Edit player profile</span></a><?php } ?>
       <?php if ($users->checkUserRight(2)){?><a href="players.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-info"><span>Players list</span></a><?php } ?>
	  </p>
	  
	  	<? 
	  	switch ($_GET['message']){
        case 1:
          echo '<p class="msg done">statistic has been added.</p>';
        break;
        case 2:
          echo '<p class="msg done">statistic has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done"><b>'.$_GET['intCorrect'].'</b> statistics has been changed.</p>';
          if (!empty($_GET['intError'])) echo '<p class="msg error"><b>'.$_GET['intError'].'</b> statistics could not be changed.</p>';
        break;
       case 98:
          echo '<p class="msg error">database error</p>';
        break;
        case 99:
           echo '<p class="msg error">wrong or missing input data.</p>';
        break;
      }
      ?>
      <div class="msg info error" style="display:none;">
      <span></span>.
    </div>


<?php if ($users->checkUserRight(2)) {

echo '
<h2>New PLAYER statistic</h2>

<form action="players_stats_action.php" method="post" id="form-01"  style="">
<table>
<tr>
 
';
      echo '<th>Season</th>';
      echo '<th>Club name</th>';
      echo '<th>League name</th>';
      echo '<th>Stage</th>';
      echo '<th>GP</th>';
      if ($post==1) {
      echo '<th>GP D</th>';
      echo '<th>MIN</th>';
      }
      echo '<th>G</th>';
      echo '<th>A</th>';
      echo '<th>TM</th>';
      if ($post<>1) echo '<th>+/</th>';
      if ($post==1) {
      echo '<th>AVG</th>';
      echo '<th>PER</th>';
      echo '<th>SO</th>';
      }
    
echo '</tr>';
echo '<tr>';
	  echo'<td>';  
                echo '<select name="id_season" id="id_season" class="input-text required">';
			          echo'<option value="">select season</option>';
                for ($i=(ActSeason+1);$i>=1900;$i--) { 
	               echo '<option value="'.$i.'" '.write_select($i,$_GET['id_season']).'>'.($i-1).'/'.$i.'</option>';
	               }
	               echo '</select>';
	    echo'</td>';
	    echo'<td>';
        show_club_stats_select_box ($con,$games,0,$_GET['id_club'],ActSeason,"id_club");
      echo '</td>'; 
      echo'<td>';
        show_league_stats_select_box ($con,$games,0,$_GET['id_league'],ActSeason,"id_league",'id_type',0);
      echo '</td>';
      echo'<td>';
          echo'<div id="id_type_0">';
              
              if (!empty($_GET['id_league'])){
               echo '<select name="id_type"  class="input-text required">';   
               echo'<option value=""  class="">select stage</option>';    
	             $query="SELECT name,id FROM games_stages WHERE id_league=".$_GET['id_league']." ORDER by id";
                $result = $con->SelectQuery($query);
                while($write = $result->fetch_array()){
                  $id_stage=$write["id"]; 
	                $intIDstageDefault=$con->GetSQLSingleResult("SELECT id_detault as item FROM games_stages WHERE id=".$id_stage);
                  if (!empty($intIDstageDefault)) {$id_stage=$intIDstageDefault;}
                  echo '<option value="'.$id_stage.'" '.write_select($_GET['id_type'],$id_stage).'>'.$write['name'].'</option>';
                }
                echo '</select>';
              }
          echo '</div>';   
      echo '</td>';
              echo'
              <td><input type="text" name="games" size="2" value="'.$_GET['games'].'" />
              </td>
              ';
              if ($post==1){ 
                echo '<td><input type="text" name="games_dressed" size="2" value="'.$_GET['games_dressed'].'" /></td>';
                echo '<td><input type="text" name="minutes" size="2" value="'.$_GET['minutes'].'" /></td>';
              }
              echo'
              <td><input type="text" name="goals" size="2" value="'.$_GET['goals'].'" /></td>
              <td><input type="text" name="assist" size="2" value="'.$_GET['assist'].'" /></td>
              <td><input type="text" name="penalty" size="2" value="'.$_GET['penalty'].'" /></td>';
              if ($post<>1) echo '<td><input type="text" name="plusminus" size="2" value="'.$_GET['plusminus'].'" /></td>';
              if ($post==1) {
              echo'
              <td><input type="text" name="AVG" size="2" value="'.$_GET['AVG'].'" /></td>
              <td><input type="text" name="PCE" size="2" value="'.$_GET['PCE'].'" /></td>
              <td><input type="text" name="shotouts" size="2" value="'.$_GET['shotouts'].'" /></td>
              ';}
              
echo '</tr>
</table>

        	<p class="box-01">
			     <input type="submit" value="Add new statistic"  class="input-submit" />
			   </p>
  
        <input type="hidden" name="action" value="add_stats" />
	      <input type="hidden" name="filter" value="'. $_GET['filter'].'" />
	      <input type="hidden" name="filter2" value="'. $_GET['filter2'].'" />
	      <input type="hidden" name="list_number" value="'. $_GET['list_number'].'" />
	      <input type="hidden" name="id_player" value="'. $id_player.'" />
	      '. OdkazForm.'
	      </form>
';
}
?>

<?php
function show_stats($con,$games,$users,$id_player,$post,$type){

    $query_stats="SELECT * from stats WHERE id_player=".$id_player." AND id_season_type=".$type."  AND id_stats_type=1 ORDER BY id_season DESC, id_season_type DESC , id DESC";
    $result_stats = $con->SelectQuery($query_stats);
    $a = 1;
    if ($con->GetQueryNum($query_stats)>0){
    
    echo '
<form action="players_stats_action.php" method="post">
<table style=" z-index:50" class="tablesorter">
<thead>
<tr>
';
      echo '<th>Season</th>';
      echo '<th>Club name</th>';
      echo '<th>League name</th>';
      echo '<th>Stage</th>';
      echo '<th>GP</th>';
      if ($post==1) {
      echo '<th>GP D</th>';
      echo '<th>MIN</th>';
      }
      echo '<th>G</th>';
      echo '<th>A</th>';
      echo '<th>TM</th>';
      if ($post<>1) echo '<th>+/</th>';
      if ($post==1){
      echo '<th>AVG</th>';
      echo '<th>PER</th>';
      echo '<th>SO</th>';
       }
      echo '<th>&nbsp;</th>';
     
echo '</tr>
</thead>
';
	    
    while($write_stats = $result_stats->fetch_array())
	   {
	     if ($a%2) $style=' class="bg"'; else $style="";
	     echo '<tr'.$style.'>';
	     
	     
	     echo'<td>';  
                echo '<select name="id_season['.$a.']" id="id_season['.$a.']" class="input-text required">';
			          echo'<option value="">select season</option>';
                for ($i=(ActSeason+1);$i>=1900;$i--) { 
	               echo '<option value="'.$i.'" '.write_select($write_stats['id_season'],$i).'>'.($i-1).'/'.$i.'</option>';
	               }
	               echo '</select>';
	    echo'</td>';
	     echo'<td>';
        show_club_stats_select_box ($con,$games,$write_stats['id'],$write_stats['id_club'],($write_stats['id_season']),"id_club[".$a."]");
      echo '</td>'; 
      echo'<td>';
        show_league_stats_select_box ($con,$games,$write_stats['id'],$write_stats['id_league'],($write_stats['id_season']),"id_league[".$a."]","id_type[".$a."]",$write_stats['id']);
      echo '</td>';
      echo'<td>';
          echo'<div id="id_type['.$a.']_'.$write_stats['id'].'">';
            if (!empty($write_stats['id_league'])){
               echo '<select name="id_type['.$a.']" class="input-text required">';   
               echo'<option value=""  class="">select stage</option>';       
	             $query="SELECT name,id FROM games_stages WHERE id_league=".$write_stats['id_league']." ORDER by id";
                $result = $con->SelectQuery($query);
                while($write = $result->fetch_array()){
                  $id_stage=$write["id"]; 
	                $intIDstageDefault=$con->GetSQLSingleResult("SELECT id_detault as item FROM games_stages WHERE id=".$id_stage);
                  if (!empty($intIDstageDefault)) {$id_stage=$intIDstageDefault;}
                  echo '<option value="'.$id_stage.'" '.write_select($write_stats['id_season_type'],$id_stage).'>'.$write['name'].'</option>';
                }
                echo '</select>';
              }
        
        echo '</div>';   
      echo '</td>';
      
       
              echo'
              <td><input type="text" name="games['.$a.']" size="2" value="'.$write_stats['games'].'" /></td>';
              if ($post==1) {
                echo'<td><input type="text" name="games_dressed['.$a.']" size="2" value="'.$write_stats['games_dressed'].'" /></td>';
                echo '<td><input type="text" name="minutes['.$a.']" size="2" value="'.$write_stats['minutes'].'" /></td>';
              }
              echo'
              <td><input type="text" name="goals['.$a.']" size="2" value="'.$write_stats['goals'].'" /></td>
              <td><input type="text" name="assist['.$a.']" size="2" value="'.$write_stats['assist'].'" /></td>
              <td><input type="text" name="penalty['.$a.']" size="2" value="'.$write_stats['penalty'].'" /></td>';
              if ($post<>1) echo '<td><input type="text" name="plusminus['.$a.']" size="2" value="'.$write_stats['plusminus'].'" /></td>';
              if ($post==1){
              echo'
              <td><input type="text" name="AVG['.$a.']" size="2" value="'.$write_stats['AVG'].'" /></td>
              <td><input type="text" name="PCE['.$a.']" size="2" value="'.$write_stats['PCE'].'" /></td>
              <td><input type="text" name="shotouts['.$a.']" size="2" value="'.$write_stats['shotouts'].'" /></td>
              ';
             }
          echo'<td>';
          if ($post==1) echo '<input type="hidden" name="plusminus['.$a.']" value="'.$write_stats['plusminus'].'" />';
          echo '<input type="hidden" name="id['.$a.']" value="'.$write_stats['id'].'" />';
          if ($users->checkUserRight(4)) echo'<a href="javascript:delete_players_stats(\''.$users->getSesid().'\','.$write_stats["id_player"].',\''.($write_stats['id_season']-1).'/'.$write_stats['id_season'].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\',\''.$write_stats['id'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
        echo'</td>';
        echo "</tr>\n";
        $a++;
  }
  	echo '
  	</table>
  	<p class="box-01">
			     <input type="submit" value="Update statistics"  class="input-submit" />
			   </p>
  
        <input type="hidden" name="action" value="update_stats" />
	      <input type="hidden" name="id_player" value="'. $id_player.'" />
	      <input type="hidden" name="number" id="number" value="'. $a.'" />
	      <input type="hidden" name="filter" value="'. $_GET['filter'].'" />
	      <input type="hidden" name="filter2" value="'. $_GET['filter2'].'" />
	      <input type="hidden" name="list_number" value="'. $_GET['list_number'].'" />
	      '. OdkazForm.'
	</form>
  ';
     
      }else{
             echo '<p class="msg warning">No statistic data found</p>';
          }
  
  }
  
  $query_sub="SELECT * FROM stats_types_list ORDER BY id ASC";
  if ($con->GetQueryNum($query_sub)>0){
	   $result_sub = $con->SelectQuery($query_sub);
	   while($write_sub = $result_sub->fetch_array()){
	     echo '<h2>'.$write_sub['name'].'</h2>';
       show_stats($con,$games,$users,$id_player,$write2['id_position'],$write_sub['id']);     
	  }
	}
	
	$query_sub="SELECT DISTINCT id_season_type FROM stats WHERE id_season_type>3 AND id_player=".$id_player;
	if ($con->GetQueryNum($query_sub)>0){
	   $result_sub = $con->SelectQuery($query_sub);
	   while($write_sub = $result_sub->fetch_array()){
	     
	     $strStageName=$con->GetSQLSingleResult("SELECT name as item FROM games_stages WHERE id=".$write_sub['id_season_type']);
	     echo '<h2>'.$strStageName.'</h2>';
       show_stats($con,$games,$users,$id_player,$write2['id_position'],$write_sub['id_season_type']);     
	  }
	}
	
	
  
}else{
  echo '<p class="msg warning">No data found</p>';
}
}else{
  echo '<p class="msg warning">No data found</p>';
}

 
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

