<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(32,1,0,1,0);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Groups for standings");
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
		$.tablesorter.defaults.sortList = [[0,0]];
		
		 //parser pro kody nn/nn
     $.tablesorter.addParser({ 
	   
	    id: 'kod', 
        is: function(s) { 
       
            return /(Kč){1}$/.test(s);
        }, 
        format: function(s) { 
            return $.tablesorter.formatFloat(s.replace(new RegExp(/[^0-9.]/g),""));
        }, 
        type: 'numeric' 
      });
		
		$("table").tablesorter({
			headers: {
				2: { sorter: false },
				0: { sorter: 'kod'}
			}
		});
  
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
		  <div id="content" class="box" style="min-height: 1000px;">

    <!-- hlavni text -->

<?php
  if (!empty($_GET['id_standings'])) {
  $query="SELECT * FROM standings WHERE id=".$_GET['id_standings'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
  $intOrder=$con->GetSQLSingleResult("SELECT order_group as item FROM standings_groups WHERE id_standings=".$_GET['id_standings']." ORDER by order_group DESC");
  if (empty($intOrder)) $intOrder=0; 
  $intOrder++;
  ?>
    
	<h1>Groups for standings "<?php echo $write['name'];?>"</h1>
    <p class="box">
    	 <a href="standings_info.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>&amp;id=<?echo $_GET['id_standings'];?>"  class="btn-list"><span>back to detailed standings</span></a>
	  </p>
	  
	  
	  <? 
	  	if (!empty($_GET['message'])){
	  	switch ($_GET['message']){
	  	  case 1:
          echo '<p class="msg done">group has been added.</p>';
        break;
        case 2:
          echo '<p class="msg done">group has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">group has been updated.</p>';
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
	

  
<form action="standings_action.php" method="post" id="form-01">
<fieldset>
	<legend>Add new group</legend>
	
	<table class="nostyle">
		  <tr>
			 <td><span class="label label-04">name</span></td>
        <td>
          <input size="50" type="text" name="name" value="" class="input-text required" />
        </td>
      <td><span class="label label-04">order</span></td>
        <td>
          <input size="1" type="text" name="order_group" value="<?php echo $intOrder;?>" class="input-text required" />
        </td>
     	 <td><input type="submit" class="input-submit" value="add new group" /></td>
		  </tr>
	 </table>
	<input type="hidden" name="action" value="group_add" />
	<input type="hidden" name="id_standings" value="<?php echo $write['id'];?>" />
	<input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	<input type="hidden" name="filter2" value="<?php echo $_GET['filter2'];?>" />
	<input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
	<?echo OdkazForm;?>
</fieldset>	
</form>

 	  	<table class="tablesorter"> <!-- Sort this TABLE -->
				<thead>
					<tr>
						<th>Order</th>
						<th>Name</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
<?
    $query="SELECT * FROM standings_groups WHERE id_standings=".$_GET['id_standings']." ORDER BY order_group ASC";
    //echo $query; 
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	  if ($a%2) $style=""; else $style="";
	     
	     $intCountGroupsItems=$con->GetSQLSingleResult("SELECT count(*) as item FROM standings_teams WHERE id_group=".$write["id"]);
	     
  		 echo '<tr class="'.$style.'">';
  		 
  		  echo '<td class="center"><b>'.$write['order_group'].'.</b></td>';
        echo'<td class="high-bg">';
         if ($users->checkUserRight(3)) echo'<a href="standings_groups_update.php'.Odkaz.'&amp;id_group='.$write["id"].'&amp;id_standings='.$_GET['id_standings'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item">';
         echo '<b>'.$write['name'].'</b>';
         if ($users->checkUserRight(3)) echo'</a>';
        echo'</td>';
        
        echo'<td>';
          if ($users->checkUserRight(3)) echo'<a href="standings_groups_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;id_standings='.$_GET['id_standings'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
          if ($users->checkUserRight(4) and $intCountGroupsItems==0) echo'<a href="javascript:delete_group(\''.$users->getSesid().'\','.$write["id"].',\''.$write["name"].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\',\''.$_GET['id_standings'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
        echo "
        </td>
        </tr>\n";
  		 $a++;
	   }
	   
	   
	  }else{
      echo '<tr><td colspan="10" class=""><p class="msg warning">No groups found</p></td></tr>';
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