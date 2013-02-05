<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(1,1,1,0,0);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Add user");
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
    
	<h1>Add new user</h1>
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
	  	  
<form action="users_action.php" method="post" id="form-01">
<fieldset>
	<legend>Add user</legend>
	
	<p class="nomt">
	<label for="inp-1" class="req">Username:</label><br />
	<input type="text" size="50" name="user_name" maxlength="255" class="input-text-02 required" id="inp-1" value="<?php echo $_GET['user_name']; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Password:</label><br />
	<input type="password" size="60" name="password" maxlength="100" class="input-text-02 required password" id="inp-2" value="<?php echo $_GET['password']; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-3" class="req">Confirm password:</label><br />
	<input type="password" size="60" name="password_check" equalto="#inp-2" maxlength="100" class="input-text-02 required" id="inp-3"  value="<?php echo $_GET['password_check']; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-4" class="req">User group:</label><br />
	 <select id="inp-4" name="id_group" class="input-text-02 required">
			  <option value="">select group</option>
			  <?php
        $query="SELECT * FROM users_group ORDER by name";
        $result = $con->SelectQuery($query);
        while($write = $result->fetch_array()){
        echo '
        <option value="'.$write['id'].'"'.write_select($write['id'],$_GET['id_group']).'>'.$write['name'].'</option>
        ';
        }
        ?>
    </select> 
	</p>
	
	<p class="nomt">
	<label for="inp-5" class="req">Name:</label><br />
	<input type="text" size="60" name="name" maxlength="100" class="input-text-02" id="inp-5" value="<?php echo $_GET['name']; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-6" class="req">Surname:</label><br />
  <input type="text" size="60" name="surname" maxlength="100" class="input-text-02"id="inp-6" value="<?php echo $_GET['surname']; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-7" class="req">Email:</label><br />
	<input type="text" size="60" name="email" maxlength="100" class="input-text-02 required email" id="inp-7" value="<?php echo $_GET['email']; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-8" class="req">Phone:</label><br />
	<input type="text" size="30" name="phone" maxlength="20" class="input-text-02" id="inp-8" value="<?php echo $_GET['phone']; ?>" />
	</p>
	
	<p class="box-01">
			<input type="submit" value="Add new user"  class="input-submit" />
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