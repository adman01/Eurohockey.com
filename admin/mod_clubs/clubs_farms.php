<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(25,1,0,1,0);
$games = new games($con);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Add assign to farm");
echo $head->setJavascriptExtendFile("../inc/js/clubs_box.js");
//require_once("../inc/lightwindow.inc");
echo $head->setJavascriptExtendFile("js/functions.js");
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
				3: { sorter: false },
        1: { sorter: false }
			}
		});
  
});

</script>
<?php
echo $head->setEndHead();
$strAdminMenu="clubs";
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

<?php
  if (!empty($_GET['id'])) {
  $query="SELECT * FROM clubs WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
    $strDefaultName=$write['name'];
  
    if (!empty($_GET['id'])) $id=$_GET['id']; else  $id=$write['id'];
  ?>
    
	<h1>Add assign to farm</h1>
    <p class="box">
    	 <a href="clubs.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of clubs</span></a>
    	 <a href="clubs_update.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>&amp;id=<?echo $write['id'];?>"  class="btn-list"><span>back to editing default informations about "<? echo $write['name'];?>"</span></a>
	  </p>
	  
	  
	  <? 
	  	if (!empty($_GET['message'])){
	  	switch ($_GET['message']){
	  	  case 1:
          echo '<p class="msg done">club has been assigned.</p>';
        break;
        case 2:
          echo '<p class="msg done">club assign has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">club assign has been updated.</p>';
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
	

  
  <h2>You editing club: <b><?php echo $strDefaultName ;?></b></h2>
<form action="clubs_action.php" method="post" id="form-01">
<fieldset>
	<legend>Add assign to farm</legend>
	
	<table class="nostyle">
		  <tr>
		    <td>
          Club <b><? echo $strDefaultName; ?></b>
        </td>
		    <td>
          <select name="id_farm_statut" class="input-text">
	        <?php
	        $query_sub="SELECT id,name FROM clubs_farms_list ORDER BY id";
	        $result_sub = $con->SelectQuery($query_sub);
          while($write_sub = $result_sub->fetch_array())
           echo '<option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$_GET['id_farm_statut']).'>'.$write_sub['name'].'</option>';
	        ?>
	        </select>    
       </td>
			  <td>
          <? show_club_select_box($con,0,$_GET['id_farm'],"id_farm");?>
        </td>
     	 <td><input type="submit" class="input-submit" value="assign club" /></td>
		  </tr>
	 </table>
	<input type="hidden" name="action" value="farm_add" />
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
						<th>Club 1</th>
						<th>&nbsp;</th>
						<th>Club 2</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
<?
    $query="SELECT *,(SELECT name FROM clubs_farms_list WHERE clubs_farms_list.id=clubs_farm_items.id_farm_statut) as farm_status_name FROM clubs_farm_items WHERE id_club=".$_GET['id']." or id_farm=".$_GET['id']." ORDER BY id DESC";
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	  if ($a%2) $style=""; else $style="";
	     $ClubName=$games->GetActualClubName($write["id_club"],ActSeason);
       $FarmName=$games->GetActualClubName($write["id_farm"],ActSeason);
         
  		 echo '<tr class="'.$style.'">';
  		  echo '<td class="high-bg">';
         echo '<b>'.$ClubName.'</b>';
        echo'</td>';
  		  echo '<td class="center">';
         echo '<b>'.$write['farm_status_name'].'</b>';
        echo'</td>';
        echo '
        <td class="high-bg">';
         echo '<b>'.$FarmName.'</b>';
         echo'</td>';
        
        echo'<td>';
          if ($users->checkUserRight(4)) echo'<a href="javascript:delete_farm(\''.$users->getSesid().'\','.$write["id"].',\''.$FarmName.'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\',\''.$_GET['id'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
        echo "
        </td>
        </tr>\n";
  		 $a++;
	   }
	   
	   
	  }else{
      echo '<tr><td colspan="10" class=""><p class="msg warning">No farms assign found</p></td></tr>';
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