<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(1,1,0,1,0);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Edit user group");
echo $head->setJavascriptExtendFile("js/users_rules.js");
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
  
});
</script>
<?php
echo $head->setEndHead();
$strAdminMenu="users";
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
    
	<h1>Edit user group</h1>
    <p class="box">
       <a href="users_rules.php<?echo Odkaz;?>"  class="btn-list"><span>User rights</span></a>
       <a href="users.php<?echo Odkaz;?>"  class="btn-list"><span>Users</span></a>
       <?php if ($users->checkUserRight(2)){?><a href="users_add.php<?echo Odkaz;?>"  class="btn-create"><span>Add new user</span></a><?php } ?>
    	 <a href="users_logs.php<?echo Odkaz;?>"  class="btn-info"><span>User logs</span></a>
	  </p>
	  
	  	<? 
	  	if (!empty($_GET['message'])){
	  	echo '<p class="msg error">';
			switch ($_GET['message']){
        case 99:
          echo 'wrong or missing input data';
        break;
      }
      echo '</p>';
      }
      ?>
    
    <div class="msg info error" style="display:none;">
      <span></span>.
    </div>
	
  <?php
  if (!empty($_GET['id'])) {
  $query="SELECT * FROM users_group WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $writeAll = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  ?>
	
<form action="users_rules_action.php" method="post" id="form-01">
<fieldset>
	<legend>Edit group</legend>
	
	<p class="nomt">
	<label for="inp-1" class="req">Group name:</label><br />
	<input type="text" size="50" name="name" maxlength="100" class="input-text-02 required" id="inp-1" value="<?php echo $writeAll['name']; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Set rights:</label><br />
  <table>
			  <tr><th>Module name</th><th>Read</th><th>Write</th><th>Modify</th><th>Delete</th><th>All</th></tr>
        <?php
        $query="SELECT * FROM users_rights WHERE show_item=1 ORDER by name";
        $result = $con->SelectQuery($query);
        while($write = $result->fetch_array()){
        
        $query="SELECT * FROM users_rights_items WHERE id_users_group=".$_GET['id']." AND id_users_rights=".$write['id']."";
        $result2 = $con->SelectQuery($query);
        $writeCheck = $result2->fetch_array();
        if ($writeCheck['user_read']==1)   $read=' checked="checked"'; else $read="";
        if ($writeCheck['user_add']==1)    $add=' checked="checked"'; else $add="";
        if ($writeCheck['user_update']==1) $update=' checked="checked"'; else $update="";
        if ($writeCheck['user_delete']==1) $delete=' checked="checked"'; else $delete="";
        if ($writeCheck['user_read']==1 and $writeCheck['user_add']==1 and $writeCheck['user_update']==1 and $writeCheck['user_delete']==1) $all=' checked="checked"'; else $all="";
        echo '
        <tr>
        <td class="high-bg">'.$write['name'].'</td>
        <td class="t-center"><input type="checkbox" '.$read.' name="read_'.$write['id'].'" id="row_'.$write['id'].'" value="1" /></td>
        <td class="t-center"><input type="checkbox" '.$add.' name="add_'.$write['id'].'" id="row_'.$write['id'].'" value="1" /></td>
        <td class="t-center"><input type="checkbox" '.$update.' name="update_'.$write['id'].'" id="row_'.$write['id'].'" value="1" /></td>
        <td class="t-center"><input type="checkbox" '.$delete.' name="delete_'.$write['id'].'" id="row_'.$write['id'].'" value="1" /></td>
        <td class="t-center"><input type="checkbox" '.$all.' onclick="CheckAll2(this.form,\'row_'.$write['id'].'\',this);" name="g" /></td>
        </tr>';
        }
        ?>
    </table>
  
	</p>
	
	<p class="box-01">
			<input type="submit" value="Edit group"  class="input-submit" />
			</p>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="id" value="<?php echo $writeAll['id'];?>" />
	<?echo OdkazForm;?>
</fieldset>	
</form>

<?php
	}
	else{echo '<p>'.langNodata.' <a href="users_rules.php'.Odkaz.'">'.langNodataAnchor.'</a></p>';}
  }
  else{echo '<p>'.langNodata.' <a href="users_rules.php'.Odkaz.'">'.langNodataAnchor.'</a></p>';}
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