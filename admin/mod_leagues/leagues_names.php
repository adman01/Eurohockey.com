<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(24,1,0,1,0);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Add data from past seasons");
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
    
	<h1>Add data from past seasons</h1>
    <p class="box">
       <a href="leagues.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of leagues</span></a>
    	 <?php if ($users->checkUserRight(2)){?><a href="javascript:toggle('past_data');" class="btn-create"><span>Add new data for past seasons</span></a><?php } ?>
	  </p>
	  
	  
	  <? 
	  	if (!empty($_GET['message'])){
	  	switch ($_GET['message']){
	  	  case 1:
          echo '<p class="msg done">data has been added.</p>';
        break;
        case 2:
          echo '<p class="msg done">data has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">data has been updated.</p>';
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
  
  <div id="past_data" style="display:none;">
<form action="leagues_action.php" method="post" id="form-01">
<fieldset>
	<legend>Add data for season</legend>
	
	<p class="nomt">
	<label for="inp-2" class="req">Data for season:</label><br />
  		 <select name="id_season" class="input-text-02 required">
	        <?php 
          for ($i=(ActSeason+1);$i>=1900;$i--) { 
	         echo '<option value="'.$i.'"'.write_select($i,$id_season).'>'.($i-1).'/'.$i.'</option>';
	        } 
          ?>
	     </select>   
  </p>
	
	<p class="nomt">
	 <label for="inp-1" class="req">Alternative name:</label><br />
	 <input type="text" name="name" size="50" maxlength="255" value="" class="input-text-02 required" />
	</p>
	
	<p class="nomt">
	<br>
    <span class="label label-04">For tournaments:</span>
  </p>
	
	<p class="nomt">
	 <label for="inp-1" class="req">Place/City:</label><br />
	 <input type="text" name="place" size="50" maxlength="50" value="" class="input-text-02" />
	</p>
	
	<p class="nomt">
	 <label for="inp-1" class="req">Official link:</label><br />
	 <input type="text" name="link" size="50" maxlength="255" value="" class="input-text-02" />
	</p>
	
	<p class="nomt">
	<label for="inp-14" class="req">Tournament standings:</label><br />
  <textarea id="elm1" name="standings" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').hide();">[Hide editor]</a>
  		</span>
	</p>
	
	<p class="nomt">
	<label for="inp-14" class="req">Additional data:</label><br />
  <textarea id="elm2" name="additional_data" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"><?php echo $promotion; ?></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm2').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm2').hide();">[Hide editor]</a>
  		</span>
	</p>
	
	
	
	<p class="box-01">
			<input type="submit" value="Add new data"  class="input-submit" />
			</p>
	
	<input type="hidden" name="action" value="name_add" />
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
					  <th>From/for season</th>
					  <th>Alternative name</th>
						<th>Place</th>
						<th>Link</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
<?
    $query="SELECT * FROM leagues_names WHERE id_league=".$_GET['id']." ORDER BY int_year DESC";
    //echo $query; 
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	  if ($a%2) $style=""; else $style="";
  		 echo '<tr class="'.$style.'">';
        
        echo '<td class="center">';
         echo '<b>'.($write['int_year']-1).'-'.($write['int_year']).'</b>';
        echo'</td>';
        echo '<td class="high-bg">';
         if ($users->checkUserRight(3)) echo'<a href="leagues_names_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;id_league='.$id.'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item">';
         echo '<b>'.$write['name'].'</b>';
         if ($users->checkUserRight(3)) echo'</a>';
        echo'</td>';
        echo '<td class="t-left">'.$write['place'].'</td>';
        echo '<td class="t-left">'.$write['link'].'</td>';
        echo'<td>';
          if ($users->checkUserRight(3)) echo'<a href="leagues_names_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;id_league='.$id.'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
          if ($users->checkUserRight(4)) echo'<a href="javascript:delete_item3(\''.$users->getSesid().'\','.$write["id"].',\''.$write["name"].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\',\''.$_GET['id'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
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