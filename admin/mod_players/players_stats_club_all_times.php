<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(28,1,0,0,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("js/functions.js");
echo $head->setJavascriptExtendFile("../inc/js/leagues_stats_box.js");
echo $head->setJavascriptExtendFile("../inc/js/players_stats_box.js");
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
	
	$("#form-02").validate({
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
echo $head->setTitle(langGlogalTitle."Edit club statistic");
echo $head->setEndHead();
$strAdminMenu="players_stats";
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
  
  $id_club=$_GET['id'];
  if (empty($id_club)) $id_club=0; else $id_club=$id_club;
  
  If(!empty($ArSpecialRight[4])) $sqlRightWhereCountry="AND ".str_replace("id_country","id_country",$ArSpecialRight[4]); else $sqlRightWhereCountry="";
  if(!empty($ArSpecialRight[2])) $sqlRightWhereLeague="  AND (SELECT count(*) FROM clubs_leagues_items WHERE clubs_leagues_items.id_club=clubs.id AND (".str_replace("clubs_leagues_items.id_league","id_league",$ArSpecialRight[2])."))>0"; else $sqlRightWhereLeague="";
  if(!empty($ArSpecialRight[3])) $sqlRightWhereClub=" AND ".str_replace("id_club","id",$ArSpecialRight[3]); else $sqlRightWhereClub="";
  $query2="SELECT id FROM clubs WHERE id=".$id_club." ".$sqlRightWhereClub." ".$sqlRightWhereCountry." ".$sqlRightWhereLeague." LIMIT 1";
  //echo $query2;
  $result2 = $con->SelectQuery($query2);
  $write2 = $result2->fetch_array();
  $intCountClub=$con->GetQueryNum($query2);
  if ($intCountClub>0){
?>    
<h1>Statistics for club (all seasons and leagues): <span style="color:#FFF5CC"><? echo $games->GetActualClubName($id_club,ActSeason); ?></span></h1>
<?php
  }else{
?>
<h1>Statistics for club (all seasons and leagues)</span></h1>
<?php
  }
?>  
    <p class="box">
       <a href="players_stats_club.php<?echo Odkaz;?>&amp;id=<?echo $id_club;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-info"><span>Statistic - SELECTED seasons and leagues</span></a>
       <?php if ($users->checkUserRight(2)){?><a href="players.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-info"><span>Players list</span></a><?php } ?>
       <a href="../mod_clubs/clubs.php<?echo Odkaz;?>"  class="btn-info"><span>Clubs list</span></a>
	  </p>
	  
	  <form action="<? echo $_SERVER["SCRIPT_NAME"]; ?>" name="filter_form" method="get">
    <fieldset>
	   <legend>Club filter</legend>
	   <table class="nostyle">
		  <tr>
			 <td><span class="label label-05">selected&nbsp;club</span></td>
			 <td>
			   <div style="position:relative">
          <input type="text" name="filter_name" autocomplete="off" value="<?php echo $games->GetActualClubName($id_club,ActSeason); ?>" class="input-text required" onkeyup="send_livesearch_clubs_data(this.value,'<? echo $users->getSesid(); ?>',<? echo $users->getIdPageRight();?>)" />
          <div id="livesearch" style="z-index:99"></div>
         </div>
         
       </td>
       <td><input type="submit" class="input-submit" value="filter" /></td>
		  </tr>
	 </table>
	 <input type="hidden" name="order" value="<?echo $_GET['order'];?>" />
	 <input type="hidden" name="id" id="id_club_filter" value="<?echo $_GET['id'];?>" />
	 
	 <?echo OdkazForm;?>
  </fieldset>	
  </form>
  
	  	<? 
	  	switch ($_GET['message']){
        case 1:
          echo '<p class="msg done">statistic has been added. Add new <a href="javascript:toggle_stats(\'new_player\',\'new_goalkeeper\');"><span>player statistic</span></a> or new <a href="javascript:toggle_stats(\'new_goalkeeper\',\'new_player\');"><span>goalkeeper statistic</span></a></p>';
        break;
        case 2:
          echo '<p class="msg done">statistic has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done"><b>'.$_GET['intCorrect'].'</b> statistics has been changed.</p>';
          if (!empty($_GET['intError'])) echo '<p class="msg error"><b>'.$_GET['intError'].'</b> statistics could not be changed.</p>';
        break;
        case 4:
          echo '<p class="msg done"><b>'.$_GET['intCorrect'].'</b> statistics has been copied.</p>';
          if (!empty($_GET['intError'])) echo '<p class="msg error"><b>'.$_GET['intError'].'</b> statistics could not be copied.</p>';
          echo '<p class="msg info">Show season <a href="players_stats_club.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;id_season='.$_GET['id_season_copied'].'&amp;id_league='.$_GET['id_league'].'">where statistic was copied</a>.</p>';
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
    <br />

<?php
if ($intCountClub>0){

if ($users->checkUserRight(2)) {


echo '
<a href="javascript:toggle_stats(\'new_player\',\'new_goalkeeper\');" class="btn-create"><span>New player</span></a>
<a href="javascript:toggle_stats(\'new_goalkeeper\',\'new_player\');" class="btn-create"><span>New goalkeeper</span></a>
<div class="fix" style="margin-bottom:10px">&nbsp;</div>

<div id="new_player" class=""'; 
if ($_GET['id_stats_type']!=1) echo 'style="display:none;"';
echo'>
<form action="players_stats_action.php" method="post" id="form-01"  style="">
<table>
<tr>
 
';
      echo '<th>Season</th>';
      echo '<th>League name</th>';
      echo '<th>Stage</th>';
      echo '<th>Player</th>';
      echo '<th>GP</th>';
      echo '<th>G</th>';
      echo '<th>A</th>';
      echo '<th>TM</th>';
      echo '<th>+/</th>';
    
echo '</tr>';
echo '<tr>';
        echo'<td>
         <select name="id_season" class="input-text required">
			     <option value="">select season</option>
			  ';
			     for ($i=(ActSeason+1);$i>=1900;$i--) { 
	            echo '<option value="'.$i.'" '.write_select($_GET['id_season'],$i).'>'.($i-1).'/'.$i.'</option>';
	       }
	      echo '
        </select>
        </td>';
          echo'<td>';
        show_league_stats_select_box ($con,$games,0,$_GET['id_league'],ActSeason,"id_league",'id_type',1);
      echo '</td>';
           echo'<td>';
           echo'<div id="id_type_1">';  
          
              if (!empty($_GET['id_league'])){
               echo '<select name="id_type" class="input-text required">';   
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
      
      
        echo'<td>';
          show_player_stats_select_box($con,$games,0,$_GET['id_player'],"id_player");
         echo '</td>';
	    
	    
              echo'
              <td><input type="text" name="games" size="2" value="'.$_GET['games'].'" /></td>
              <td><input type="text" name="goals" size="2" value="'.$_GET['goals'].'" /></td>
              <td><input type="text" name="assist" size="2" value="'.$_GET['assist'].'" /></td>
              <td><input type="text" name="penalty" size="2" value="'.$_GET['penalty'].'" /></td>
              <td><input type="text" name="plusminus" size="2" value="'.$_GET['plusminus'].'" /></td>';
    echo '</tr>
</table>

        	<p class="box-01">
			     <input type="submit" value="Add new player statistic"  class="input-submit" />
			   </p>
  
        <input type="hidden" name="action" value="add_stats_club" />
        <input type="hidden" name="stats_admin_type" value="2" />
        <input type="hidden" name="id_stats_type" value="1" />
	      <input type="hidden" name="filter" value="'. $_GET['filter'].'" />
	      <input type="hidden" name="filter2" value="'. $_GET['filter2'].'" />
	      <input type="hidden" name="list_number" value="'. $_GET['list_number'].'" />
	      <input type="hidden" name="id_club" value="'. $id_club.'" />
	      '. OdkazForm.'
	      </form>
</div>

<div id="new_goalkeeper" class=""'; 
if ($_GET['id_stats_type']!=2) echo 'style="display:none;"';
echo'>
<form action="players_stats_action.php" method="post" id="form-02"  style="">
<table>
<tr>
 
';
      echo '<th>Season</th>';
      echo '<th>League name</th>';
      echo '<th>Stage</th>';
      echo '<th>Player</th>';
      echo '<th>GP</th>';
      echo '<th>GP D</th>';
      echo '<th>MIN</th>';
      echo '<th>G</th>';
      echo '<th>A</th>';
      echo '<th>TM</th>';
      echo '<th>AVG</th>';
      echo '<th>PER</th>';
      echo '<th>SO</th>';
      
echo '</tr>';
echo '<tr>';
      echo'<td>
         <select name="id_season" class="input-text required">
			     <option value="">select season</option>
			  ';
			     for ($i=(ActSeason+1);$i>=1900;$i--) { 
	            echo '<option value="'.$i.'" '.write_select($_GET['id_season'],$i).'>'.($i-1).'/'.$i.'</option>';
	       }
	      echo '
        </select>
        </td>';
         echo'<td>';
        show_league_stats_select_box ($con,$games,1,$_GET['id_league'],ActSeason,"id_league",'id_type',2);
      echo '</td>';
         
	       echo'<td>';
	         echo'<div id="id_type_2">';  
           
              if (!empty($_GET['id_league'])){
              echo '<select name="id_type" class="input-text required">';   
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
	   echo'<td>';
          show_player_stats_select_box($con,$games,1,$_GET['id_player'],"id_player");
         echo '</td>';
              echo'<td><input type="text" name="games" size="2" value="'.$_GET['games'].'" /></td>';
              echo '<td><input type="text" name="games_dressed" size="2" value="'.$_GET['games_dressed'].'" /></td>';
              echo '<td><input type="text" name="minutes" size="2" value="'.$_GET['minutes'].'" /></td>';
              echo'
              <td><input type="text" name="goals" size="2" value="'.$_GET['goals'].'" /></td>
              <td><input type="text" name="assist" size="2" value="'.$_GET['assist'].'" /></td>
              <td><input type="text" name="penalty" size="2" value="'.$_GET['penalty'].'" /></td>';
              echo'
              <td><input type="text" name="AVG" size="2" value="'.$_GET['AVG'].'" /></td>
              <td><input type="text" name="PCE" size="2" value="'.$_GET['PCE'].'" /></td>
              <td><input type="text" name="shotouts" size="2" value="'.$_GET['shotouts'].'" /></td>';
              
echo '</tr>
</table>

        	<p class="box-01">
			     <input type="submit" value="Add new goalkeeper statistic"  class="input-submit" />
			   </p>
  
        <input type="hidden" name="action" value="add_stats_club" />
        <input type="hidden" name="stats_admin_type" value="2" />
        <input type="hidden" name="id_stats_type" value="2" />
	      <input type="hidden" name="filter" value="'. $_GET['filter'].'" />
	      <input type="hidden" name="filter2" value="'. $_GET['filter2'].'" />
	      <input type="hidden" name="list_number" value="'. $_GET['list_number'].'" />
	      <input type="hidden" name="id_club" value="'. $id_club.'" />
	      '. OdkazForm.'
	      </form>
</div>
';

}

function show_stats($con,$games,$users,$id_league,$id_club,$id_season,$type,$type_name){
 
 echo '
    <form action="players_stats_action.php" id="form_stats'.$type.'" method="post">
    <table style=" z-index:50">
    ';
 
 $strClubName=$games->GetActualClubName($id_club,($id_season));
 $a = 1;
 for ($counter = 1; $counter <= 2; $counter ++) {
    
    if ($counter==1){
      $query_stats="SELECT *,stats.id as id from stats INNER JOIN players ON stats.id_player=players.id WHERE players.id_position=1 AND id_club=".$id_club."  AND id_stats_type=1 ORDER BY id_season DESC,id_league,id_season_type, players.surname ASC";
    }else{
      $query_stats="SELECT *,stats.id as id from stats INNER JOIN players ON stats.id_player=players.id WHERE players.id_position<>1 AND id_club=".$id_club." AND id_stats_type=1 ORDER BY  id_season DESC,id_league,id_season_type,players.surname ASC";
    }
    $listovani = new listing($con,$_SERVER["SCRIPT_NAME"].Odkaz."&amp;id_season=".$_GET['id_season']."&amp;id=".$_GET['id']."&amp;",50,$query_stats,3,"",$_GET['list_number']);
    $query_stats=$listovani->updateQuery();
    
    //echo $query_stats; 
    $result_stats = $con->SelectQuery($query_stats);
    
    if ($con->GetQueryNum($query_stats)>0){
    
    if ($counter==1){
      $boolGoalkeepers=true;
      $strPostName="Goalkeepers from club <span style=\"color:#0085CC\">".$strClubName."</span>";
    }else{
      $boolPlayers=true;
      $strPostName="Players from club  <span style=\"color:#0085CC\">".$strClubName."</span>";
    }
    echo'
      <tr><td colspan="15" class="high-bg"><b style="font-size:15px">'.$strPostName.'</b></td></tr>
      <tr>
    ';   
      echo '<th>Season</th>';
      echo '<th>League name</th>';
      echo '<th>Stage</th>';
      echo '<th>Player</th>';
      echo '<th>GP</th>';
      if ($counter==1) {
      echo '<th>GP D</th>';
      echo '<th>MIN</th>';
      }
      echo '<th>G</th>';
      echo '<th>A</th>';
      echo '<th>PTS</th>';
      echo '<th>TM</th>';
      if ($counter<>1) echo '<th>+/</th>';
      if ($counter==1){
      echo '<th>AVG</th>';
      echo '<th>PER</th>';
      echo '<th>SO</th>';
      echo '<th>&nbsp;</th>';
       }else{
       echo '<th colspan="4">&nbsp;</th>';
       echo '<th>&nbsp;</th>';
       }
      echo '</tr>';
    
    while($write_stats = $result_stats->fetch_array())
	   {
	     $post=$write_stats['id_position'];
	     
       
	     if ($a%2) $style=' class="bg"'; else $style="";
	     echo '<tr'.$style.'>';
	       echo '<td>
	         <select name="id_season['.$a.']" class="input-text required">
			     <option value="0">select season</option>
			  ';
			     for ($i=(ActSeason+1);$i>=1900;$i--) { 
	            echo '<option value="'.$i.'" '.write_select($write_stats['id_season'],$i).'>'.($i-1).'/'.$i.'</option>';
	       }
	      echo '
        </select>
        </td>';
         echo'<td style="width:200px;">';
             show_league_stats_select_box ($con,$games,$write_stats['id'],$write_stats['id_league'],$write_stats['id_season'],"id_league[".$a."]","id_type[".$a."]",$write_stats['id']);
	        echo '</td>';
         
	       echo'<td>';
               
	          echo'<div id="id_type['.$a.']_'.$write_stats['id'].'">';
	            if (!empty($write_stats['id_league'])){
               echo '<select name="id_type['.$a.']" id="id_type['.$a.']" class="input-text required">';   
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
          echo'<td>';
          show_player_stats_select_box($con,$games,$write_stats['id'],$write_stats['id_player'],"id_player[".$a."]");
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
          <td class="t-center"><b>'.($write_stats['goals']+$write_stats['assist']).'</b></td>
          <td><input type="text" name="penalty['.$a.']" size="2" value="'.$write_stats['penalty'].'" /></td>';
          if ($post<>1) echo '<td><input type="text" name="plusminus['.$a.']" size="2" value="'.$write_stats['plusminus'].'" /></td>';
          if ($post==1){
          echo'
          <td><input type="text" name="AVG['.$a.']" size="2" value="'.$write_stats['AVG'].'" /></td>
          <td><input type="text" name="PCE['.$a.']" size="2" value="'.$write_stats['PCE'].'" /></td>
          <td><input type="text" name="shotouts['.$a.']" size="2" value="'.$write_stats['shotouts'].'" /></td>
          ';
          }
          if ($post<>1)echo '<td colspan="4">&nbsp;</td>';
          echo '<td>';
            if ($post==1) echo '<input type="hidden" name="plusminus['.$a.']" value="'.$write_stats['plusminus'].'" />';
            echo'<input type="hidden" name="id_stats['.$a.']" value="'.$write_stats['id'].'" />';
            if ($users->checkUserRight(4)) echo'<a href="javascript:delete_club_stat(\''.$users->getSesid().'\','.$write_stats["id_club"].','.$write_stats["id_league"].',\''.($write_stats['id_season']-1).'/'.$write_stats['id_season'].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\',\''.$write_stats['id'].'\',\''.$write_stats['id_season'].'\',2)" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
          echo'</td>';
        echo "</tr>\n";
        $a++;
        
  }
     
      }else{
          if ($counter==1){
             echo '<tr><td colspan="15"><p class="msg warning">Data not found for goalkeepers and '.$type_name.'</p></td></tr>';
            }else{
              echo '<tr><td colspan="15"><p class="msg warning">Data not found for players and '.$type_name.'</p></td></tr>';
            }
          }
  
  }
    
     echo '<tfoot>';
     //listovani
      $listovani->show_list();
    echo '</tfoot>';
    
  	echo '
  	</table>
  	';
    if ($boolGoalkeepers or $boolPlayers){  
  	echo'
  	<p class="box-01">
			     <input type="submit" value="Update statistics"  class="input-submit" />
		</p>
  
        <input type="hidden" name="action" id="action_input'.$type.'" value="update_stats_club" />
        <input type="hidden" name="stats_admin_type" value="2" />
	      <input type="hidden" name="id_club" value="'. $id_club.'" />
	      <input type="hidden" name="number" id="number" value="'. $a.'" />
	      <input type="hidden" name="filter" value="'. $_GET['filter'].'" />
	      <input type="hidden" name="filter2" value="'. $_GET['filter2'].'" />
	      <input type="hidden" name="list_number" value="'. $_GET['list_number'].'" />
	      '. OdkazForm.'
	  ';
	  }
	echo'
	</form>
  ';
  
  
  }
  
       show_stats($con,$games,$users,$id_league,$id_club,$id_season,$write_sub['id'],$write_sub['name']);     
	
}else{
  echo '<p class="msg warning">No data found, please select club. Maybe you do not have necessary user right.</p>';
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
