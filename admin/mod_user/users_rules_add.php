<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(1,1,1,0,0);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Add user group");
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
    
	<h1>Add user group</h1>
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
	  	  
<form action="users_rules_action.php" method="post" id="form-01">
<fieldset>
	<legend>Add group</legend>
	
	<p class="nomt">
	<label for="inp-1" class="req">Group name:</label><br />
	<input type="text" size="50" name="name" maxlength="100" maxlength="100" class="input-text-02 required" id="inp-1" value="<?php echo $_GET['name']; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Set rights:</label><br />
	
	<table>
			  <tr><th>Module name</th><th>Read</th><th>Write</th><th>Modify</th><th>Delete</th><th>All</th></tr>
        <?php
        $query="SELECT * FROM users_rights WHERE show_item=1 ORDER by name";
        $result = $con->SelectQuery($query);
        while($write = $result->fetch_array()){
        echo '
        <tr>
        <td class="high-bg">'.$write['name'].'</td>
        <td class="t-center"><input type="checkbox" name="read_'.$write['id'].'" id="row_'.$write['id'].'" value="1" /></td>
        <td class="t-center"><input type="checkbox" name="add_'.$write['id'].'" id="row_'.$write['id'].'" value="1" /></td>
        <td class="t-center"><input type="checkbox" name="update_'.$write['id'].'" id="row_'.$write['id'].'" value="1" /></td>
        <td class="t-center"><input type="checkbox" name="delete_'.$write['id'].'" id="row_'.$write['id'].'" value="1" /></td>
        <td class="t-center"><input type="checkbox" onclick="CheckAll2(this.form,\'row_'.$write['id'].'\',this);" name="g" /></td>
        </tr>';
        }
        ?>
  </table>
	</p>
	
	<p class="box-01">
			<input type="submit" value="Add new group"  class="input-submit" />
			</p>
	<input type="hidden" name="action" value="add" />
	<?echo OdkazForm;?>
</fieldset>	
</form>


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