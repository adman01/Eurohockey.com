<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(24,1,0,1,0);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."League stages");
echo $head->setJavascriptExtendFile("js/functions.js");
require_once("../inc/tinymce.inc");
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
		$.tablesorter.defaults.sortList = [[0,1]];
		$("table").tablesorter({
			headers: {
				4: { sorter: false }
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
		<div id="content" class="box">

    <!-- hlavni text -->
    
	<h1>League stages</h1>
    <p class="box">
       <a href="leagues.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of leagues</span></a>
    	 <?php if ($users->checkUserRight(2)){?><a href="javascript:toggle('past_data');" class="btn-create"><span>Add new league stage</span></a><?php } ?>
	  </p>
	  
	  
	  <? 
	  	if (!empty($_GET['message'])){
	  	switch ($_GET['message']){
	  	  case 1:
          echo '<p class="msg done">stage has been added.</p>';
        break;
        case 2:
          echo '<p class="msg done">stage has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">stage has been updated.</p>';
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
  
  <h2>League: <b><?php echo $write['name'];?></b></h2>
  
  <div id="past_data" style="display:none;">
<form action="leagues_action.php" method="post" id="form-01">
<fieldset>
	
  <p class="nomt">
	 <label for="inp-1" class="req">Stage name:</label><br />
	 <input type="text" name="name" size="50" maxlength="255" value="" class="input-text-02 required" />
	</p>
	
	<p class="nomt">
	<label for="inp-1" class="req">Detailed description:</label><br />
	 <input type="text" name="description" size="50" maxlength="255" value="" class="input-text-02" />
	</p>
	
	
	
	
	<p class="box-01">
			<input type="submit" value="Add new stage"  class="input-submit" />
			</p>
	
	<input type="hidden" name="action" value="stage_add" />
	<input type="hidden" name="id" value="<?php echo $write['id'];?>" />
	<input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	<input type="hidden" name="filter2" value="<?php echo $_GET['filter2'];?>" />
	<input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
	<?echo OdkazForm;?>
</fieldset>	
</form>
<br />
</div>

 	  	<table class="tablesorter"> <!-- Sort this TABLE -->
				<thead>
					<tr>
					  <th>Stage name</th>
					  <th>Description</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
<?

//DEFAULT dump stages
$boolDump=false;
if ($boolDump){

$query="SELECT id FROM leagues";
//echo $query; 
$result = $con->SelectQuery($query);
    
if ($con->GetQueryNum($query)>0){
while($write = $result->fetch_array())
{
  $query_stages="SELECT name,id FROM stats_types_list ORDER by id";
  $result_stages = $con->SelectQuery($query_stages);
  while($write_stages = $result_stages->fetch_array())
  {
    $query_dump="INSERT INTO games_stages (id_league,name,id_detault) VALUES (".$write['id'].",'".$write_stages['name']."',".$write_stages['id'].")";
    $con->RunQuery($query_dump);
  }
  }
}
}
    
    $query="SELECT * FROM games_stages WHERE id_league=".$_GET['id']." ORDER BY name ASC";
    //echo $query; 
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	  if ($a%2) $style=""; else $style="";
  		 echo '<tr class="'.$style.'">';
        
        echo '<td class="high-bg">';
         if ($users->checkUserRight(3)) echo'<a href="leagues_stages_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;id_league='.$id.'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item">';
         echo '<b>'.$write['name'].'</b>';
         if ($users->checkUserRight(3)) echo'</a>';
        echo'</td>';
        echo '<td>'.$write["description"].'</td>';
        echo'<td>';
          if ($users->checkUserRight(3)) echo'<a href="leagues_stages_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;id_league='.$id.'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
          
          if ($write["id_detault"]==0){
            $intStageStats=$con->GetSQLSingleResult("SELECT id as item FROM stats WHERE  id_season_type=".$write["id"]." AND	id_league=".$id);
          }else{
            $intStageStats=$con->GetSQLSingleResult("SELECT id as item FROM stats WHERE  id_season_type=".$write["id_detault"]." AND	id_league=".$id);
          }
          $intStageGames=$con->GetSQLSingleResult("SELECT id as item FROM games WHERE  id_stage=".$write["id"]." AND	id_league=".$id);
          
          if ($users->checkUserRight(4) and empty($intStageStats) and empty($intStageGames)  and $write["id_detault"]<>1) echo $intStageGames.'<a href="javascript:delete_item5(\''.$users->getSesid().'\','.$write["id"].',\''.$write["name"].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\',\''.$_GET['id'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
        echo "
        </td>
        </tr>\n";
  		 $a++;
	   }
	   
	   
	  }else{
      echo '<tr><td colspan="10" class=""><p class="msg warning">No alternative names found</p></td></tr>';
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
