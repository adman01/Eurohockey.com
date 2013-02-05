<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(24,1,0,1,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Assign country");
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
		$("table").tablesorter({
			headers: {
				1: { sorter: false }
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
    
	<h1>Assign league to country</h1>
    <p class="box">
    	 <a href="leagues.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of leagues</span></a>
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
	
<?php
  if (!empty($_GET['id'])) {
  $query="SELECT * FROM leagues WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
    if (!empty($_GET['id'])) $id=$_GET['id']; else  $id=$write['id'];
    if (!empty($_GET['id_country'])) $id_country=$_GET['id_country']; else $id_country=$write['id_country'];
    
  ?>
	  
<form action="leagues_action.php" method="post" id="form-01">
<fieldset>
	<legend>Assign league to country</legend>
	
	<table class="nostyle">
		  <tr>
			 <td><span class="label label-04">Assign league</span></td>
			 <td>
         <input type="text" name="name" disabled="disabled" value="<?php echo $games->GetActualLeagueName($write['id'],ActSeason); ?>" class="input-text" />
       </td>
       <td><span class="label label-04">to country</span></td>
        <td>
       <select name="id_country" class="input-text required">
			  <option value="">select country</option>
			  <?php
        if(!empty($ArSpecialRight[1])) $sqlRightWhere="WHERE ".str_replace("id_country","id",$ArSpecialRight[1]); else $sqlRightWhere="";
        $query_sub="SELECT * FROM countries ".$sqlRightWhere." ORDER by name";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$id_country).'>'.$games->GetActualCountryName($write_sub['id'],ActSeason).'</option>
        ';
        }
        ?>
        </select> 
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
						<th>Country name</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
<?
    $query="SELECT *,(SELECT id from countries WHERE leagues_countries_items.id_country=countries.id LIMIT 1) as name FROM leagues_countries_items WHERE id>0 AND id_league=".$_GET['id']." ORDER BY id DESC";
    //echo $query; 
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	  if ($a%2) $style=""; else $style="";
  		 echo '<tr class="'.$style.'">
        <td class="high-bg">';
         if ($users->checkUserRight(3)) echo'<a href="../mod_countries/countries_update.php'.Odkaz.'&amp;id='.$write["id_country"].'" title="Edit country">';
         echo '<b>'.$games->GetActualCountryName($write['id_country'],ActSeason).'</b>';
         if ($users->checkUserRight(3)) echo'</a>';
        echo'</td><td>';
          if ($users->checkUserRight(4)) echo'<a href="javascript:delete_item2(\''.$users->getSesid().'\','.$write["id"].',\''.$write["name"].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\',\''.$_GET['id'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
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