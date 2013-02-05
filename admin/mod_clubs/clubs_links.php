<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(25,1,0,1,0);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Add link");
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
		<div id="content" class="box">

    <!-- hlavni text -->

<?php
  if (!empty($_GET['id'])) {
  $query="SELECT * FROM clubs WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
    if (!empty($_GET['id'])) $id=$_GET['id']; else  $id=$write['id'];
    if (!empty($_GET['id_season'])) $id_season=$_GET['id_season']; else $id_season=$write['id_season'];
    if (!empty($_GET['name'])) $name=$_GET['name']; else $name=$write['name'];
    
    
  ?>
    
	<h1>Add link for club "<?echo $name;?>"</h1>
    <p class="box">
    	 <a href="clubs.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of clubs</span></a>
    	 <a href="clubs_update.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>&amp;id=<?echo $write['id'];?>"  class="btn-list"><span>back to editing default informations about "<? echo $write['name'];?>"</span></a>
	  </p>
	  
	  
	  <? 
	  	if (!empty($_GET['message'])){
	  	switch ($_GET['message']){
	  	  case 1:
          echo '<p class="msg done">link has been added.</p>';
        break;
        case 2:
          echo '<p class="msg done">link has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">link has been updated.</p>';
        break;
        case 98:
          echo '<p class="msg error">database error</p>';
        break;
        case 99:
          echo '<p class="msg error">wrong or missing input data</p>';
        break;
      }
      }
      
      $bollDump=false;
      if ($bollDump){
      $query_sub="SELECT * FROM clubs WHERE link_1<>'' OR link_2<>'' OR link_3<>''";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
            if (!empty($write_sub['link_1'])) {
                $link_1_status=0;
                if ($write_sub['link_1_status']==1) $link_1_status=1; 
                if ($write_sub['link_1_status']==0) $link_1_status=1;
                if ($write_sub['link_1_status']==2) $link_1_status=2;
                $query="INSERT INTO clubs_links (name,id_type,id_club) VALUES ('".$write_sub['link_1']."',".$link_1_status.",".$write_sub['id'].")";
                //echo $query;
                $con->RunQuery($query);
            }
            
            if (!empty($write_sub['link_2'])) {
                $link_2_status=0;
                if ($write_sub['link_2_status']==1) $link_2_status=1; 
                if ($write_sub['link_2_status']==0) $link_2_status=1;
                if ($write_sub['link_2_status']==2) $link_2_status=2;
                $query="INSERT INTO clubs_links (name,id_type,id_club) VALUES ('".$write_sub['link_2']."',".$link_2_status.",".$write_sub['id'].")";
                //echo $query;
                $con->RunQuery($query);
            }
            
            if (!empty($write_sub['link_3'])) {
                $link_3_status=0;
                if ($write_sub['link_3_status']==1) $link_3_status=1; 
                if ($write_sub['link_3_status']==0) $link_3_status=1;
                if ($write_sub['link_3_status']==2) $link_3_status=2;
                $query="INSERT INTO clubs_links (name,id_type,id_club) VALUES ('".$write_sub['link_3']."',".$link_3_status.",".$write_sub['id'].")";
                //echo $query;
                $con->RunQuery($query);
            }
            
        } 
      }
      ?>
    
    <div class="msg info error" style="display:none;">
      <span></span>.
    </div>
	

<form action="clubs_action.php" method="post" id="form-01">
<fieldset>
	<legend>Add new link</legend>
	
	<table class="nostyle">
		  <tr>
		  <td><span class="label label-04">URL</span></td>
        <td>
          <input type="text" size="50" name="name" value="" class="input-text required" />
       </td>
       <td><span class="label label-04">Name</span></td>
        <td>
          <input type="text" size="30" name="link_name" value="" class="input-text" />
        </td>
       <td>
          <select name="id_type" class="input-text required">
	        <?php 
          $query_sub="SELECT * FROM clubs_links_list ORDER by id";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['id'].'">'.$write_sub['name'].'</option>
        ';
        } 
          ?>
	        </select>    
       </td>
       
     	 <td><input type="submit" class="input-submit" value="add new link" /></td>
		  </tr>
	 </table>
	<input type="hidden" name="action" value="links_add" />
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
					  <th>Name</th>
						<th>URL</th>
						<th>Type</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
<?
    $query="SELECT * FROM clubs_links WHERE id_club=".$_GET['id']." ORDER BY name ASC";
    //echo $query; 
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	  if ($a%2) $style=""; else $style="";
  		 echo '<tr class="'.$style.'">
        <td class="">';
         echo ''.$write['link_name'].'';
        echo'</td>
        <td class="high-bg">';
         if ($users->checkUserRight(3)) echo'<a href="clubs_links_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;id_club='.$id.'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item">';
         echo '<b>'.$write['name'].'</b>';
         if ($users->checkUserRight(3)) echo'</a>';
        echo'</td>';
        echo '<td class="center">';
        $strType=$con->GetSQLSingleResult("SELECT name as item FROM clubs_links_list WHERE id=".$write['id_type']);
        
         echo '<b>'.$strType.'</b>';
        echo'</td>';
        echo'<td>';
          if ($users->checkUserRight(3)) echo'<a href="clubs_links_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;id_club='.$id.'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
          if ($users->checkUserRight(4)) echo'<a href="javascript:delete_links(\''.$users->getSesid().'\','.$write["id"].',\''.$write["name"].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\',\''.$_GET['id'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
        echo "
        </td>
        </tr>\n";
  		 $a++;
	   }
	   
	   
	  }else{
      echo '<tr><td colspan="10" class=""><p class="msg warning">No links found</p></td></tr>';
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