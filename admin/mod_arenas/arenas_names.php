<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(27,1,0,1,0);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Add alternative name to arena");
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
    if (!empty($_GET['id_season'])) $id_season=$_GET['id_season']; else $id_season=$write['id_season'];
    if (!empty($_GET['name'])) $name=$_GET['name']; else $name=$write['name'];
    
    
  ?>
    
	<h1>Add alternative name to arena</h1>
    <p class="box">
    	 <a href="arenas.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of arenas</span></a>
    	 <a href="arenas_update.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>&amp;id=<?echo $write['id'];?>"  class="btn-list"><span>back to editing default informations about "<? echo $write['name'];?>"</span></a>
	  </p>
	  
	  
	  <? 
	  	if (!empty($_GET['message'])){
	  	switch ($_GET['message']){
	  	  case 1:
          echo '<p class="msg done">name has been added.</p>';
        break;
        case 2:
          echo '<p class="msg done">name has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">name has been updated.</p>';
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
	

  
  <h2>Default name of clubs: <b><?php echo $write['name'];?></b></h2>
	<p class="msg info">If there is no Alternative name, the default arena name will be used. Insert Alternative name for the season when it was changed only! It will be used for all future seasons, unless there is another one specified.</p>
<form action="arenas_action.php" method="post" id="form-01">
<fieldset>
	<legend>Add alternative name to arena</legend>
	
	<table class="nostyle">
		  <tr>
			 <td><span class="label label-04">Assign for season</span></td>
			 <td>
          <select name="id_season" class="input-text required">
	        <?php 
          for ($i=(ActSeason+1);$i>=1900;$i--) { 
	         echo '<option value="'.$i.'"'.write_select($i,$id_season).'>'.($i-1).'/'.($i).'</option>';
	        } 
          ?>
	        </select>    
       </td>
       <td><span class="label label-04">alternative name</span></td>
        <td>
          <input type="text" name="name" value="" class="input-text required" />
        </td>
     	 <td><input type="submit" class="input-submit" value="add new name" /></td>
		  </tr>
	 </table>
	<input type="hidden" name="action" value="name_add" />
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
						<th>Alternative name</th>
						<th>Season</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
<?
    $query="SELECT * FROM arenas_names WHERE id_arena=".$_GET['id']." ORDER BY int_year DESC";
    //echo $query; 
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	  if ($a%2) $style=""; else $style="";
  		 echo '<tr class="'.$style.'">
        <td class="high-bg">';
         if ($users->checkUserRight(3)) echo'<a href="arenas_names_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;id_arena='.$id.'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item">';
         echo '<b>'.$write['name'].'</b>';
         if ($users->checkUserRight(3)) echo'</a>';
        echo'</td>';
        echo '<td class="center">';
         echo 'from season <b>'.($write['int_year']-1).'-'.($write['int_year']).'</b>';
        echo'</td>';
        echo'<td>';
          if ($users->checkUserRight(3)) echo'<a href="arenas_names_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;id_arena='.$id.'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
          if ($users->checkUserRight(4)) echo'<a href="javascript:delete_name(\''.$users->getSesid().'\','.$write["id"].',\''.$write["name"].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\',\''.$_GET['id'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
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