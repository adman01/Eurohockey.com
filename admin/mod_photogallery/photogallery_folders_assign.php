<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(10,1,0,1,0);
$games = new games($con);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Add photo assignment");
echo $head->setJavascriptExtendFile("js/functions.js");
echo $head->setJavascriptExtendFile("../inc/js/leagues_box.js");
echo $head->setJavascriptExtendFile("../inc/js/clubs_box.js");
echo $head->setJavascriptExtendFile("../inc/js/players_box.js");
echo $head->setJavascriptExtendFile("../inc/js/games_box.js");
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
	
	$(".tabs > ul").tabs();
		$.tablesorter.defaults.sortList = [[2,0]];
		$("table").tablesorter({
			headers: {
				3: { sorter: false }
			}
		});
  
});

</script>
<?php
echo $head->setEndHead();
$strAdminMenu="photogallery";
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
		<div id="content" class="box" style="min-height: 1000px;">

    <!-- hlavni text -->
    
	<h1>Add photo assignment</h1>
    <p class="box">
    	 <a href="photogallery_folders.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of photo-folders</span></a>
	  </p>
	  
	  
	  <? 
	  	if (!empty($_GET['message'])){
	  	switch ($_GET['message']){
	  	  case 1:
          echo '<p class="msg done">assignment has been added.</p>';
        break;
        case 2:
          echo '<p class="msg done">assignment has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">assignment has been updated.</p>';
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
	
<?php
  if (!empty($_GET['id'])) {
  $query="SELECT * FROM photo_folder WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
    if (!empty($_GET['id_season'])) $id_season=$_GET['id_season']; else $id_season=$write['id_season'];
    
  ?>
  
  <h2>Photo folder name: <b><?php echo $write['name'];?></b></h2>
<form action="photogallery_folders_action.php" method="post" id="form-01">
<fieldset>
	<legend>Add new assignment</legend>
	
  
  	<table class="nostyle">
		  <tr>
      <td><span class="label label-04">Season</span></td>
       <td>
          <select name="id_season" class="input-text required">
	        <?php 
          for ($i=ActSeason;$i>=1900;$i--) { 
	         echo '<option value="'.$i.'"'.write_select($i,$id_season).'>'.($i-1).'/'.$i.'</option>';
	        } 
          ?>
	        </select>    
       </td>
			 <td><span class="label label-04">Assignment type</span></td>
			 <td>
         <span style="padding-top:8px; display:block">
	         <input type="radio" <? if ($_GET['is_item']==1) echo 'checked="checked"'  ?> name="is_item" value="1" class="input-text-02" onclick="document.getElementById('right_box_1').style.display='block';  document.getElementById('right_box_2').style.display='none';"> League
           <input type="radio" <? if ($_GET['is_item']==2) echo 'checked="checked"'  ?> name="is_item" value="2" class="input-text-02" onclick="document.getElementById('right_box_2').style.display='block'; document.getElementById('right_box_1').style.display='none';"> Game
        </span>
       </td>
     	 <td rowspan="2"><input type="submit" class="input-submit" value="assign" /></td>
		  </tr>
		  <tr>
		    <td colspan="3">
		    <div style="padding-top:10px">
		    
        
	     <div id="right_box_1" <? if ($_GET['is_item']<>1) echo 'style="display:none"'  ?>>
	       <? show_league_select_box($con,1,$_GET['id_league'],"id_league",$users->getSesid(),$users->getIdPageRight());?>
	     </div>
	     
	     
	     <div id="right_box_2" <? if ($_GET['is_item']<>2) echo 'style="display:none"'  ?>>
	       <? show_game_select_box($con,1,$_GET['id_game'],"id_game");?>
	     </div>
	
	</div>
		    
		    </td>
		  </tr>
	 </table>
 
	<input type="hidden" name="action" value="assign_add" />
	<input type="hidden" name="id" value="<?php echo $write['id'];?>" />
	<input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	<input type="hidden" name="filter2" value="<?php echo $_GET['filter2'];?>" />
	<input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
	<?echo OdkazForm;?>
</fieldset>	
</form>

 	  <table class="tablesorter"> <!-- Sort this TABLE -->
				<thead>
					<tr>
            <th>Season</th>
					  <th>Item type</th>
						<th>Item name</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
<?
    $query="SELECT * FROM photo_folder_assign WHERE id>0 AND id_folder=".$_GET['id']." ORDER BY id DESC";
    //echo $query; 
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	  if ($a%2) $style=""; else $style="";
	   
	     switch ($write['id_item_type']){
            case 1:
              $type_name="League";
              $type_link="mod_leagues/leagues";
              $name=$games->GetActualLeagueName($write['id_item'],ActSeason);;
            break;
            case 2:
              $type_name="Game";
              $type_link="mod_games/games";
              $query_sub="SELECT id,id_club_home,id_club_visiting,date,home_score,visiting_score FROM games WHERE id>0 AND id=".$write['id_item'];
              $result_sub = $con->SelectQuery($query_sub);
              $write_sub = $result_sub->fetch_array();
              
              $strDate=date("d.m.Y",strtotime($write_sub['date']));
	            $strHome=$games->GetActualClubName($write_sub['id_club_home'],ActSeason);
	            $strVisiting=$games->GetActualClubName($write_sub['id_club_visiting'],ActSeason);
	            $strScore=$games->GetScore($write_sub['id'],1);
              $name=$strDate.' '.$strHome.' vs. '.$strVisiting.' ('.$strScore.')';
            break;
          }
  		 echo '<tr class="'.$style.'">';
        echo '<td>'.($write["int_year"]-1).'-'.$write["int_year"].'</td>';
  		  echo '<td>'.$type_name.'</td>';
  		  echo '<td class="high-bg">';
         echo'<a href="../'.$type_link.'.php'.Odkaz.'&amp;filter='.$write["id_item"].'" title="Show item">';
         echo '<b>'.$name.'</b>';
         echo'</a>';
        echo'</td><td>';
          echo'<a href="javascript:delete_assign(\''.$users->getSesid().'\','.$write["id"].',\''.$name.'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\',\''.$_GET['id'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
        echo "
        </td>
        </tr>\n";
  		 $a++;
	   }
	   
	   
	  }else{
      echo '<tr><td colspan="10" class=""><p class="msg warning">No assigment found</p></td></tr>';
    }
    
    ?>
</table>

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