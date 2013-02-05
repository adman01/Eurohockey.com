<?php
require_once("inc/global.inc");
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("inc/js/index.js");
echo $head->setTitle("Server administration ".strProjectName);
echo $head->setEndHead();

$error_message=$_GET['error'];
switch ($error_message){
  case 1: 
    $error_message="You did not specify the name or password.";
  break;
  case 2: 
    $error_message="Login information is not correct.";
  break;
  case 3: 
    $error_message="You have been automatically disconnected.";
  break;
  case 4: 
    $error_message="You've been logged out from the system.";
  break;
  case 5: 
    $error_message="Your username has been changed, log in again";
  break;
}
?>
<script type="text/javascript">
$(document).ready(function(){
  
  $("#form-01").validate({
		
	});
  
});
</script>
<body id="login">

<div id="main-02">

	<div id="login-top"></div>

	<div id="login-box">

		<!-- Logo -->
		<p class="nom t-center"><a href="#"><img src="inc/tmp/logo.gif" alt="Our logo" title="Visit Site" /></a></p>

		<!-- Messages -->
		<?php if (isset($error_message)){ ?>
      <p class="msg error"><?php echo $error_message; ?></p>
   <?php } ?>
		
		<p class="msg info">Enter your username and password.</p>

		<!-- Form -->
		<form action="login.php" method="post" id="form-01">
		<table class="nom nostyle">
			<tr>
				<td style="width:75px;"><label for="login-user"><strong>Username:</strong></label></td>
				<td><input type="text" name="username" maxlength="50" class="input-text required" id="login-user" style="width:300px;" /></td>
			</tr>
			<tr>
				<td><label for="login-pass"><strong>Password:</strong></label></td>
				<td><input type="password" name="heslo" maxlength="50" class="input-text required" id="login-pass" style="width:300px;" /></td>
			</tr>
			<tr>
				<td colspan="2" class="t-right"><input type="submit" class="input-submit" value="Sign In &raquo;" /></td>
			</tr>
		</table>
		</form>

	</div> <!-- /login-box -->

	<div id="login-bottom"></div>

</div> <!-- /main -->

</body>
</html>
