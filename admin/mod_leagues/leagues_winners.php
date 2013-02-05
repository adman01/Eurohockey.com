<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(24,1,0,1,0);
$games = new games($con);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Add past winners");
echo $head->setJavascriptExtendFile("js/functions.js");
echo $head->setJavascriptExtendFile("../inc/js/clubs_box.js");
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
		$.tablesorter.defaults.sortList = [[1,1]];
		$("table").tablesorter({
			headers: {
				2: { sorter: false }
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
		<div id="content" class="box" style="min-height: 1000px;">

    <!-- hlavni text -->
    
	<h1>Add past winners</h1>
    <p class="box">
    	 <a href="leagues.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of leagues</span></a>
	  </p>
	  
	  
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
          echo '<p class="msg done">club has been updated.</p>';
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
  $query="SELECT * FROM leagues WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
    if (!empty($_GET['id'])) $id=$_GET['id']; else  $id=$write['id'];
    if (!empty($_GET['id_season'])) $id_season=$_GET['id_season']; else $id_season=$write['id_season'];
    if (!empty($_GET['name'])) $name=$_GET['name']; else $name=$write['name'];
    
    
  ?>
  
  <h2>Default name of league: <b><?php echo $write['name'];?></b></h2>
<form action="leagues_action.php" method="post" id="form-01">
<fieldset>
	<legend>Add past winner</legend>
	
	<table class="nostyle">
		  <tr>
			 <td><span class="label label-04">Assign for season</span></td>
			 <td>
          <select name="id_season" class="input-text required">
	        <?php 
          for ($i=ActSeason;$i>=1900;$i--) { 
	         echo '<option value="'.$i.'"'.write_select($i,$id_season).'>'.($i-1).'/'.$i.'</option>';
	        } 
          ?>
	        </select>    
       </td>
       <td>
          <? show_club_select_box($con,0,$_GET['id_club'],"id_club");?>
        </td>
     	 <td><input type="submit" class="input-submit" value="add winner" /></td>
		  </tr>
	 </table>
	<input type="hidden" name="action" value="winner_add" />
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
						<th>Past winner club</th>
						<th>Season</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
<?
    $query="SELECT * FROM leagues_past_winners WHERE id_league=".$_GET['id']." ORDER BY int_year DESC";
    //echo $query; 
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	  if ($a%2) $style=""; else $style="";
  		 echo '<tr class="'.$style.'">
        <td class="high-bg">';
         if ($users->checkUserRight(3)) echo'<a href="leagues_winners_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;id_league='.$id.'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item">';
         $club_name=$games->GetActualClubName($write['id_club'],$id_season);
         echo '<b>'.$club_name.'</b>';
         if ($users->checkUserRight(3)) echo'</a>';
        echo'</td>';
        echo '<td class="center">';
         echo 'from season <b>'.($write['int_year']-1).'-'.($write['int_year']).'</b>';
        echo'</td>';
        echo'<td>';
          if ($users->checkUserRight(3)) echo'<a href="leagues_winners_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;id_league='.$id.'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
          if ($users->checkUserRight(4)) echo'<a href="javascript:delete_item4(\''.$users->getSesid().'\','.$write["id"].',\''.$club_name.'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\',\''.$_GET['id'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
        echo "
        </td>
        </tr>\n";
  		 $a++;
	   }
	   
	   
	  }else{
      echo '<tr><td colspan="10" class=""><p class="msg warning">No past winners found</p></td></tr>';
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