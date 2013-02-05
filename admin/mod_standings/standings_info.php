<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(32,1,0,1,0);
$games = new games($con);
$ArSpecialRight=$users->getSpecialRightsSQL();
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Standings tables");
echo $head->setJavascriptExtendFile("js/functions.js");
echo $head->setJavascriptExtendFile("../inc/js/clubs_box.js");
echo $head->setJavascriptExtendFile("../inc/js/clubs_stats_box.js");
echo $head->setJavascriptExtendFile("../inc/js/leagues_stats_box.js");

require_once("../inc/tinymce.inc");

if (!empty($_GET['tab'])) $tab=$_GET['tab']; else $tab=0;
?>
<script type="text/javascript">
	$(document).ready(function(){
		var $tabs = $('#tabs').tabs(); // first tab selected
		$tabs.tabs('select',<?php echo $tab; ?>); // switch to third tab
	});
</script>

<?php
echo $head->setEndHead();
$strAdminMenu="standings";
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
  if (!empty($_GET['id'])) {
  $query="SELECT * FROM standings WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
    $id_season=$write['id_season'];
    $id_league=$write['id_league'];
    $id_stage=$write['id_stage'];
    $id_type=$write['id_type'];
    $name=$write['name'];
    
    $last_update=$write["last_update"];
    if ($last_update=='0000-00-00 00:00:00') $last_update='-'; else $last_update=date("d.m.y, H:i",strtotime($last_update));
    $strLastEdit=$last_update.' | '.$users->GetUserSignatureID($write["id_user"]);
    
    $intCountGroups=$con->GetSQLSingleResult("SELECT count(*) as item FROM standings_groups WHERE id_standings=".$_GET['id']);
  ?>
    
	<h1>Edit standings <span style="color:#FFF5CC"><?echo $$name;?></span></h1>
    <p class="box">
    	 <a href="standings.php<?echo Odkaz;?>&amp;id=<?echo $_GET['filter'];?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of standings</span></a>
    	 <a href="standings_groups.php<?echo Odkaz;?>&amp;id=<?echo $_GET['filter'];?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;id_standings=<?echo $_GET['id'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-info"><span>standings groups (<?echo $intCountGroups;?>)</span></a>
    	 <a href="javascript:toggle('copy')"  class="btn-create"><span>copy standings</span></a>
	  </p>
	  
	  <?
	  echo '
	  <form action="standings_action.php" method="post" id="copy" style="display:none">
  <fieldset>
	<legend>Copy standings</legend>
	
	<table class="nostyle">
		  <tr>
			 <td><span class="label label-04">Copy standings to season</span></td>
			 <td>
       <select name="id_season" id="id_season" class="input-text">
			  <option value="">select season</option>
			  ';
        for ($i=(ActSeason+1);$i>=1900;$i--) { 
	         echo '<option value="'.$i.'" '.write_select(($id_season+1),$i).'>'.($i-1).'/'.$i.'</option>';
	      }
        echo'
       </select> 
       </td>
			 <td><span class="label label-04">and league</span></td>
			 <td>';
			 show_league_stats_select_box ($con,$games,($counter+1),$id_league,ActSeason,"id_league",0,0);
       echo'</td>
       <td><input type="submit" class="input-submit" value="copy" /></td>
		  </tr>
	 </table>
	<input type="hidden" name="action" value="copy_standings" />
	<input type="hidden" name="id_standings" value="'.$_GET['id'].'" />
	<input type="hidden" name="filter" value="'.$_GET['filter'].'" />
	<input type="hidden" name="filter2" value="'.$_GET['filter2'].'" />
	<input type="hidden" name="list_number" value="'.$_GET['list_number'].'" />
	'.OdkazForm.'
  </fieldset>	
  </form>';
  ?>
	  
	  <p class="msg info"><b>Last update: </b><?echo $strLastEdit;?></p>
	

<div class="tabs box" id="tabs">
			<ul>
			    <? 
           
            $query="SELECT * FROM standings_groups WHERE id_standings=".$_GET['id']." ORDER BY order_group ASC";
            $result = $con->SelectQuery($query);
            $counter=1;
            if ($con->GetQueryNum($query)>0){
              while($write = $result->fetch_array())
	             {
                echo '<li><a href="#tab0'.$counter.'"><span>Group: '.$write['name'].'</span></a></li>';
                $counter++;
               }
               
              $query="SELECT id FROM standings_teams WHERE id_group=0 AND id_standings=".$_GET['id'];
              $intGroupTeam=$con->GetQueryNum($query);
              if ($intGroupTeam>0){
                echo '<li><a href="#tab0'.$counter.'"><span>Not in group ('.$intGroupTeam.')</span></a></li>';
              }
              
               
               
               
            }else{
              echo '<li><a href="#tab01"><span>No groups</span></a></li>';
            }
           
          
					?>
				</ul>
			</div> <!-- /tabs -->

  <? 
	  	if (!empty($_GET['message'])){
	  	switch ($_GET['message']){
	  	 case 1:
          echo '<p class="msg done">club has been added.</p>';
        break;
        case 2:
          echo '<p class="msg done">club has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">standings has been updated.</p>';
        break;
        case 4:
          echo '<p class="msg done">club has been deleted from standings.</p>';
        break;
        case 5:
          echo '<p class="msg done">line has been added.</p>';
        break;
        case 6:
          echo '<p class="msg done">line has been removed.</p>';
        break;
        case 7:
          echo '<p class="msg done">info has been updated.</p>';
        break;
        case 8:
          echo '<p class="msg done">standings has been copied.</p>';
        break;
			 case 98:
          echo '<p class="msg error">database error</p>';
        break;
        case 99:
          echo '<p class="msg error">wrong or missing input data</p>';
        break;
      }
      }
  ?>
    
    <div class="msg info error" style="display:none;">
      <span></span>.
    </div>




<? 
function get_table_form ($counter,$id_group,$id_standings){
  global $con;
  if (!empty($id_group)) {
    $strText=' to group &quot;'.$con->GetSQLSingleResult("SELECT name as item FROM standings_groups WHERE id=".$id_group).'&quot;';
  }else {$strText='';}
  echo '
  <form action="standings_action.php" method="post">
  <fieldset>
	<legend>Add new club'.$strText.'</legend>
	
	<table class="nostyle">
		  <tr>
			 <td>
       ';
       show_club_select_box($con,$counter,$_GET['id_club'],"id_club");
       echo '</td>
       <td><input type="submit" class="input-submit" value="add new club'.$strText.'" /></td>
		  </tr>
	 </table>
	<input type="hidden" name="action" value="club_add" />
	<input type="hidden" name="id_standings" value="'.$id_standings.'" />
	<input type="hidden" name="id_group" value="'.$id_group.'" />
	<input type="hidden" name="tab" value="'.$counter.'" />
	<input type="hidden" name="filter" value="'.$_GET['filter'].'" />
	<input type="hidden" name="filter2" value="'.$_GET['filter2'].'" />
	<input type="hidden" name="list_number" value="'.$_GET['list_number'].'" />
	'.OdkazForm.'
  </fieldset>	
  </form>
  
  ';
}

function get_table_clubs ($counter,$id_group,$id_standings,$id_type){
  global $con,$games,$users;
  if (!empty($id_group)) {
    $strText=' to group &quot;'.$con->GetSQLSingleResult("SELECT name as item FROM standings_groups WHERE id=".$id_group).'&quot;';
  }else {$strText='';}
  echo '
  <form action="standings_action.php" method="post">
  <fieldset>
	<legend>Standings'.$strText.'</legend>
	';
  
  if ($id_type==1){
    $query="SELECT id FROM standings_teams WHERE id_table_type=".$id_type." AND id_group=".$id_group." AND id_standings=".$id_standings; 
    if ($con->GetQueryNum($query)==0){generate_auto_standings($id_standings); $boolGenerateFirst=true;}
    
  }
    
  $query="SELECT id FROM standings_teams WHERE int_order=0 AND id_table_type=".$id_type." AND id_group=".$id_group." AND id_standings=".$id_standings;
  $intPocet=$con->GetQueryNum($query);
  if ($intPocet>0){
    $strOrder=" (points+bonus_points) DESC, (score1-score2) DESC,score1 DESC, id DESC";
    if ($id_type==1) generate_auto_standings($id_standings);
  }else{
    $strOrder=" int_order ASC";
    if ($id_type==1) $strInfoText=" and standings updating (from games)";
    if (!$boolGenerateFirst) echo '<p class="msg info">Automatic sorting'.$strInfoText.' is disabled. Use arrows on the left to manually sort the table. <a href="standings_action.php'.Odkaz.'&amp;action=move_cancel&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'&amp;id_standings='.$id_standings.'&amp;id_group='.$id_group.'&amp;tab='.$counter.'&amp;id_type='.$id_type.'" title="Cancel manual ordering">If you want to enable automatic sorting'.$strInfoText.', click here</a>.</p>';
  }
    
  echo'
  <form action="games_action.php" method="post" id="form-01"  style="">
  <table>
  <tr>
      <th>#</th>
      <th>Club</th>
      <th>Games</th>
      <th>Wins</th>
      <th>Wins-OT</th>
      <th>Draws</th>
      <th>Losses-OT</th>
      <th>Losses</th>
      <th>Score</th>
      <th>Points</th>
      <th>B Points</th>
      <th colspan="2"></th>
  </tr>';
  
  $query="SELECT * FROM  standings_teams WHERE id_group=".$id_group." AND id_table_type=".$id_type." AND id_standings=".$id_standings." ORDER BY ".$strOrder;
  //echo $query; 
    
    $result = $con->SelectQuery($query);
    $i = 0;
    $intPocet=$con->GetQueryNum($query);
    if ($intPocet>0){
    while($write = $result->fetch_array())
	  {
	     echo '<tr>';
	     echo '<td>'.($i+1).'.';
       
             echo '<table class="nostyle" style="float:left">';
             if ($i>0) echo '<tr><td valign="top"><a href="standings_action.php'.Odkaz.'&amp;action=move&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'&amp;id_standings='.$id_standings.'&amp;id_group='.$id_group.'&amp;id='.$write['id'].'&amp;tab='.$counter.'&amp;id_type='.$id_type.'&amp;order='.$i.'" class="arrow-up" title="Move up"></a></td></tr>';
             if (($i+1)<$intPocet) echo '<tr><td valign="top"><a href="standings_action.php'.Odkaz.'&amp;action=move&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'&amp;id_standings='.$id_standings.'&amp;id_group='.$id_group.'&amp;id='.$write['id'].'&amp;tab='.$counter.'&amp;id_type='.$id_type.'&amp;order='.($i+2).'" class="arrow-down" title="Move down"></a></td></tr>';
             echo '</table>';
             
       echo '</td>';
       if ($id_type==2){
	     echo '<td>';
       show_club_stats_select_box ($con,$games,$write['id'],$write['id_club'],ActSeason,"id_club[".$i."]");
       $strClubName=$games->GetActualClubName($write['id_club'],ActSeason);
       echo '</td>';
	     echo '<td><input type="text" name="games['.$i.']" size="2" value="'.$write['games'].'" maxlength="3" class="input-text" /></td>';
	     echo '<td><input type="text" name="wins['.$i.']" size="2" value="'.$write['wins'].'" maxlength="3" class="input-text" /></td>';
	     echo '<td><input type="text" name="wins_ot['.$i.']" size="2" value="'.$write['wins_ot'].'" maxlength="3" class="input-text" /></td>';
	     echo '<td><input type="text" name="draws['.$i.']" size="2" value="'.$write['draws'].'" maxlength="3" class="input-text" /></td>';
	     echo '<td><input type="text" name="losts_ot['.$i.']" size="2" value="'.$write['losts_ot'].'" maxlength="3" class="input-text" /></td>';
	     echo '<td><input type="text" name="losts['.$i.']" size="2" value="'.$write['losts'].'" maxlength="3" class="input-text" /></td>';
	     echo '<td><input type="text" name="score1['.$i.']" size="2" value="'.$write['score1'].'" maxlength="3" class="input-text" /> : <input type="text" name="score2['.$i.']" size="2" value="'.$write['score2'].'" maxlength="3" class="input-text" /></td>';
	     echo '<td><input type="text" name="points['.$i.']" size="2" value="'.$write['points'].'" maxlength="3" class="input-text" /></td>';
	     }else{
        
       echo '<td>';
       $strClubName=$games->GetActualClubName($write['id_club'],ActSeason);
       echo $strClubName; 
       echo '</td>';
	     echo '<td class="t-center">'.$write['games'].'</td>';
	     echo '<td class="t-center">'.$write['wins'].'</td>';
	     echo '<td class="t-center">'.$write['wins_ot'].'</td>';
	     echo '<td class="t-center">'.$write['draws'].'</td>';
	     echo '<td class="t-center">'.$write['losts_ot'].'</td>';
	     echo '<td class="t-center">'.$write['losts'].'</td>';
	     echo '<td class="t-center">'.$write['score1'].':'.$write['score2'].'</td>';
	     echo '<td class="t-center"><b>'.$write['points'].'</b></td>';
	     }
	     echo '<td><input type="text" name="bonus_points['.$i.']" size="2" value="'.$write['bonus_points'].'" maxlength="4" class="input-text" /></td>';
       
       echo'<td>';
	        echo '<select name="id_group['.$i.']"  class="input-text">';   
	            echo '<option value="0">no group</option>';
              $query_sub="SELECT * FROM standings_groups WHERE id_standings=".$id_standings." ORDER by id ASC";
	            $result_sub = $con->SelectQuery($query_sub);
              while($write_sub = $result_sub->fetch_array()){
                echo '<option value="'.$write_sub['id'].'" '.write_select($write_sub['id'],$write['id_group']).'>'.$write_sub['name'].'</option>';
              }
          echo '</select>';   
         echo '</td>';
	     
	     echo '<td>
             <input type="hidden" name="id['.$i.']" value="'.$write['id'].'" />';
             if ($users->checkUserRight(4)) echo'<a href="javascript:delete_table_club(\''.$users->getSesid().'\','.$write["id"].',\''.$strClubName.'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\',\''.$id_standings.'\',\''.($counter-1).'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
       echo '</td>';
	     
	     echo '</tr>';
	     
	      $query_sub="SELECT name,id FROM standings_lines WHERE id_group=".$id_group." AND position=".($i+1)." AND id_table_type=".$id_type." AND id_standings=".$id_standings;
        $result_sub = $con->SelectQuery($query_sub);
        if ($con->GetQueryNum($query_sub)>0){
          $write_sub = $result_sub->fetch_array();
          echo '<tr class="bg"><td colspan="12" class="low smaller t-center">'.$write_sub['name'].'</td>';
          echo '<td>';
          if ($users->checkUserRight(4)) echo'<a href="javascript:delete_line(\''.$users->getSesid().'\','.$write_sub["id"].',\''.$write_sub['name'].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\',\''.$id_standings.'\',\''.($counter-1).'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
          echo '</td>';
          echo '</tr>';
        }
	     
	     $i++;
	   
      }
      echo '</table>
      <p class="box-01">
			     <input type="submit" value="Update standings"  class="input-submit" />
			   </p>
      ';
    }else{
         echo '<tr><td colspan="15"><p class="msg warning">no team found</p></td>';
         echo '</table>';
    }
  echo'
	
        <input type="hidden" name="action" value="standings_update" />
	      <input type="hidden" name="filter" value="'. $_GET['filter'].'" />
	      <input type="hidden" name="filter2" value="'. $_GET['filter2'].'" />
	      <input type="hidden" name="list_number" value="'. $_GET['list_number'].'" />
	      <input type="hidden" name="id_standings" value="'.$id_standings.'" />
	      <input type="hidden" name="tab" value="'.$counter.'" />
	      <input type="hidden" name="number" value="'. $i.'" />
	      '. OdkazForm.'
	       </fieldset>
	      </form>
';
}


function get_line_form ($counter,$id_group,$id_standings,$id_type){
  global $con;
  
  $strText=$con->GetSQLSingleResult("SELECT info as item FROM standings_info WHERE id_group=".$id_group." AND id_table_type=".$id_type." AND id_standings=".$id_standings);
  echo '
  <form action="standings_action.php" method="post">
  <fieldset>
	<legend>Aditional info</legend>
	
	<textarea id="elm'.($counter+1).'" name="info" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02">'.$strText.'</textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get(\'elm'.($counter+1).'\').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get(\'elm'.($counter+1).'\').hide();">[Hide editor]</a>
  		</span>
  		<p><input type="submit" class="input-submit" value="update info" /></p>
	
	<input type="hidden" name="action" value="info_update" />
	<input type="hidden" name="id_standings" value="'.$id_standings.'" />
	<input type="hidden" name="id_group" value="'.$id_group.'" />
	<input type="hidden" name="id_type" value="'.$id_type.'" />
	<input type="hidden" name="tab" value="'.$counter.'" />
	<input type="hidden" name="filter" value="'.$_GET['filter'].'" />
	<input type="hidden" name="filter2" value="'.$_GET['filter2'].'" />
	<input type="hidden" name="list_number" value="'.$_GET['list_number'].'" />
	'.OdkazForm.'
  </fieldset>	
  </form>
  
  <form action="standings_action.php" method="post">
  <fieldset>
	<legend>Add new line</legend>
	
	<table class="nostyle">
		  <tr>
			 <td><span class="label label-04">Add new line AFTER position</span></td>
			 <td><input type="text" size="1" name="position" class="input-text" value="1" /></td>
			 <td><span class="label label-04">name/description</span></td>
			 <td><input type="text" size="50" name="name" class="input-text" value="" /></td>
       <td><input type="submit" class="input-submit" value="add new line" /></td>
		  </tr>
	 </table>
	<input type="hidden" name="action" value="line_add" />
	<input type="hidden" name="id_standings" value="'.$id_standings.'" />
	<input type="hidden" name="id_group" value="'.$id_group.'" />
	<input type="hidden" name="id_type" value="'.$id_type.'" />
	<input type="hidden" name="tab" value="'.$counter.'" />
	<input type="hidden" name="filter" value="'.$_GET['filter'].'" />
	<input type="hidden" name="filter2" value="'.$_GET['filter2'].'" />
	<input type="hidden" name="list_number" value="'.$_GET['list_number'].'" />
	'.OdkazForm.'
  </fieldset>	
  </form>
  
  
  ';
}

 $query="SELECT * FROM standings_groups WHERE id_standings=".$_GET['id']." ORDER BY order_group ASC";
            $result = $con->SelectQuery($query);
            $counter=1;
            if ($con->GetQueryNum($query)>0){
            
              while($write = $result->fetch_array())
	             {
                echo '<div id="tab0'.$counter.'">';
                         if ($id_type<>1) get_table_form($counter,$write['id'],$_GET['id']);
                         get_table_clubs($counter,$write['id'],$_GET['id'],$id_type);
                         get_line_form ($counter,$write['id'],$_GET['id'],$id_type);
                echo '</div>';
                $counter++;
              }
              
              $query="SELECT id FROM standings_teams WHERE id_group=0 AND id_standings=".$_GET['id'];
              $intGroupTeam=$con->GetQueryNum($query);
              if ($intGroupTeam>0){
              
                 echo '<div id="tab0'.$counter.'">';
                         if ($id_type<>1) get_table_form($counter,0,$_GET['id']);
                         get_table_clubs($counter,0,$_GET['id'],$id_type);
                         get_line_form ($counter,0,$_GET['id'],$id_type);
                echo '</div>';
                
              }
              
            }else{

              echo '<div id="tab01">';
                if ($id_type<>1)get_table_form(1,0,$_GET['id']);
                get_table_clubs($counter,0,$_GET['id'],$id_type);
                get_line_form ($counter,0,$_GET['id'],$id_type);
               echo '</div>';
            }

?>


	

<?php
	}else{echo '<p class="msg warning">No data found</p>';}
}else{echo '<p class="msg warning">No data found</p>';}
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
</html>