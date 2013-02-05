<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(27,1,0,1,0);
$games = new games($con);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Assign arena to club");
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
		$.tablesorter.defaults.sortList = [[0,0]];
		$("table").tablesorter({
			headers: {
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
  $query="SELECT * FROM arenas WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
    if (!empty($_GET['id'])) $id=$_GET['id']; else  $id=$write['id'];
    if (!empty($_GET['id_club'])) $id_club=$_GET['id_club']; else $id_club=$write['id_club'];
    
  ?>    
	<h1>Assign arena to club</h1>
    <p class="box">
    	 <a href="arenas.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of arenas</span></a>
    	 <a href="arenas_update.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>&amp;id=<?echo $write['id'];?>"  class="btn-list"><span>back to editing default informations about "<? echo $write['name'];?>"</span></a>
	  </p>
	  
	  
	  <? 
	  	if (!empty($_GET['message'])){
	  	switch ($_GET['message']){
	  	  case 1:
          echo '<p class="msg done">assigment has been added.</p>';
        break;
        case 2:
          echo '<p class="msg done">assigment has been removed.</p>';
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
	

	  
<form action="arenas_action.php" method="post" id="form-01">
<fieldset>
	<legend>Assign arena to club</legend>
	
	<table class="nostyle">
		  <tr>
			 <td><span class="label label-04">Assign arena</span></td>
			 <td>
         <input type="text" name="name" disabled="disabled" value="<?php echo $games->GetActualArenaName($write['id'],ActSeason); ?>" class="input-text" /> <b>to</b>
       </td>
       <td>
       <? show_club_select_box($con,1,$_GET['id_club'],"id_club");?>
        <td>
        
       </td>
     	 <td><input type="submit" class="input-submit" value="assign" /></td>
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
						<th>Club name</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
<?
    $query="SELECT * FROM clubs_arenas_items WHERE id>0 AND id_arena=".$_GET['id']." ORDER BY id DESC";
    //echo $query; 
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	  if ($a%2) $style=""; else $style="";
	  $strClubName=$games->GetActualClubName($write['id_club'],ActSeason);
  		 echo '<tr class="'.$style.'">
        <td class="high-bg">';
         echo'<a href="../mod_clubs/clubs.php'.Odkaz.'&amp;filter='.$write["id_club"].'" title="Show club">';
         echo '<b>'.$strClubName.'</b>';
         echo'</a>';
        echo'</td><td>';
          if ($users->checkUserRight(4)) echo'<a href="javascript:delete_assign(\''.$users->getSesid().'\','.$write["id"].',\''.$strClubName.'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\',\''.$_GET['id'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
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