<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
if ($_GET['id']<>$users->id_user) $users->setUserRight(1,1,0,1,0);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Edit user");
echo $head->setJavascriptExtendFile("js/users.js");
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

		messages: {
			 password_check: {
			 	  required: " ",
				  equalTo: "Please enter the same password as above"	
  			}
  		}
		
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
    
	<h1>Edit user</h1>
    <p class="box">
    	 <a href="users.php<?echo Odkaz;?>"  class="btn-list"><span>Users</span></a>
    	 <a href="users_rules.php<?echo Odkaz;?>"  class="btn-list"><span>User rights</span></a>
    	 <a href="users_logs.php<?echo Odkaz;?>"  class="btn-info"><span>User logs</span></a>
	  </p>
	  
	  
	  	<? 
	  	if (!empty($_GET['message'])){
	  	echo '<p class="msg error">';
			switch ($_GET['message']){
        case 1:
          echo 'username and password must be single word';
        break;
        case 3:
          echo 'username already exists';
        break;
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
  $query="SELECT * FROM users WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
    if (!empty($_GET['user_name'])) $user_name=$_GET['user_name']; else  $user_name=$write['user_name'];
    if (!empty($_GET['password'])) $password=$_GET['password']; else $password="";
    if (!empty($_GET['password_check'])) $password_check=$_GET['password_check']; else $password_check="";
    if (!empty($_GET['id_group'])) $id_group=$_GET['id_group']; else  $id_group=$write['id_group'];
    if (!empty($_GET['name'])) $name=$_GET['name']; else  $name=$write['name'];
    if (!empty($_GET['surname'])) $surname=$_GET['surname']; else  $surname=$write['surname'];
    if (!empty($_GET['email'])) $email=$_GET['email']; else  $email=$write['email'];
    if (!empty($_GET['phone'])) $phone=$_GET['phone']; else  $phone=$write['phone'];
  ?>
	  
<form action="users_action.php" method="post" id="form-01">
<fieldset>
	<legend>Edit user</legend>
	
	<p class="nomt">
	<label for="inp-1" class="req">Username:</label><br />
	<input type="text" size="50" name="user_name" maxlength="255" class="input-text-02 required" id="inp-1" value="<?php echo $user_name; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Change password ?</label><br />
	<input type="checkbox" name="activate" value="1" onclick="active();"  class="input-text-02" />
	</p>
	
	
	<p class="nomt">
	<label for="inp-2" class="req">Password:</label><br />
	<input type="password" size="60" name="password" maxlength="100" disabled class="input-text-02 required password" id="inp-2" value="<?php echo $password; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-3" class="req">Confirm password:</label><br />
	<input type="password" size="60" name="password_check" equalto="#inp-2" disabled maxlength="100" class="input-text-02 required" id="inp-3"  value="<?php echo $password_check; ?>" />
	</p>

	<?php
	if ($_GET['id']<>$users->id_user) {
	?>
	<p class="nomt">
	<label for="inp-4" class="req">User group:</label><br />
	 <select id="inp-4" name="id_group" class="input-text-02 required">
			  <option value="">select group</option>
			  <?php
        $query2="SELECT * FROM users_group ORDER by name";
        $result2 = $con->SelectQuery($query2);
        while($write2 = $result2->fetch_array()){
        echo '<option value="'.$write2['id'].'"'.write_select($write2['id'],$id_group).'>'.$write2['name'].'</option>';
        }
        ?>
    </select> 
	</p>
	<?php
	}else{
    echo '<input type="hidden" name="id_group" value="'.$id_group.'" />';
  }
	?>
	
	
	<p class="nomt">
	<label for="inp-5" class="req">Name:</label><br />
	<input type="text" size="60" name="name" maxlength="100" class="input-text-02" id="inp-5" value="<?php echo $name; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-6" class="req">Surname:</label><br />
  <input type="text" size="60" name="surname" maxlength="100" class="input-text-02"id="inp-6" value="<?php echo $surname; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-7" class="req">Email:</label><br />
	<input type="text" size="60" name="email" maxlength="100" class="input-text-02 required email" id="inp-7" value="<?php echo $email; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-8" class="req">Phone:</label><br />
	<input type="text" size="30" name="phone" maxlength="20" class="input-text-02" id="inp-8" value="<?php echo $phone; ?>" />
	</p>
	
	<p class="box-01">
			<input type="submit" value="Edit user"  class="input-submit" />
			</p>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="user_name_old" value="<?php echo $write['user_name'];?>" />
	<input type="hidden" name="id" value="<?php echo $write['id'];?>" />
	<?echo OdkazForm;?>
</fieldset>	
</form>
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