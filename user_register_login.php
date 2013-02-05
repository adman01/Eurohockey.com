<?php
require_once("inc/global.inc");
$strUserArray=check_login(0);

require_once("inc/ads.inc");

$strHeader="Eurohockey.com user section";
if ($web_users->is_loged()) {
  header("Location: /sponsorship.html");
}

$strHeaderKeywords=$strHeader;
$strHeaderDescription=$strHeader;
require_once("inc/head.inc");
?>
<script type="text/javascript" src="/inc/jquery/jquery.validate.min.js"></script>
  <script type="text/javascript">
 $(document).ready(function() {
      $("#form1").validate({});
       $("#form2").validate({
        rules: {
          password: { 
                required: true, minlength: 5
          }, 
          password_confirm: { 
                required: true, equalTo: "#password", minlength: 5
          } 
        }

      });
  });
  </script>
<?php
echo $head->setTitle($strHeader." - ".strProjectName);
echo $head->setEndHead();
?>
<body>

<div id="ads_top"><?php show_ads("top_main"); ?></div>
<div id="layout" class="full">
  
 
  <?php require_once("inc/bar_login.inc"); ?>
  <div id="logo"><?php require_once("inc/logo.inc"); ?></div>
  <div id="menu"><?php require_once("inc/menu.inc"); ?></div>
  <div id="main">
      
      <div id="text">
  
         <!-- main text -->
         <?php  
         echo '<h1>'.$strHeader.'</h1>';
         ?>
  
          <div id="login" class="login">

          <div id="login_left">

            <h2>Login to Eurohockey.com</h2>
            <a href="https://www.facebook.com/dialog/oauth?client_id=<?php echo YOUR_APP_ID; ?>&redirect_uri=http://<?php echo strWWWurl; ?>/user-register-action/user-facebook.html&scope=email" class="loginFB"><span>Login with Facebook</span></a>
            <div class="clear"></div>

            <span class="hrBehind"></span>
            <span class="hrBehind-or">or</span>
            <span class="hrBehind"></span>
            <div class="clear"></div>

            <form id="form1" action="/user-register-action/login.html" method="post">
  
            <?php
            switch ($_GET['message']){
            case 2:
              $strMessage="You did not enter email or password.";
            break;
            case 3:
              $strMessage="Wrong email or password.";
            break;
            case 4:
              $strMessage="Unfortunately you are not logged.";
            break;
            }
            if (!empty($strMessage))echo '<p class="message center"><b>Error:</b> '.$strMessage.'</p>';

            if (empty($_GET['firstname'])) $strFirstname=""; else $strFirstname=$_GET['firstname']; 
            if (empty($_GET['lastname'])) $strLastname=""; else $strLastname=$_GET['lastname'];
            if (empty($_GET['email'])) $strEmail=""; else $strEmail=$_GET['email'];
            if (empty($_GET['email_confirm'])) $strEmailConfirm=""; else $strEmailConfirm=$_GET['email_confirm'];
            if (empty($_GET['remember_me'])) $strRememberMe=""; else $strRememberMe=' checked="checked"';
            ?>
  
            <div class="input_holder">
              <label for="email">Email:</label>
              <input type="text" class="required" name="email" id="email" value="" size="50" maxlength="50" />
              <div class="clear"></div>
            </div>
            <div class="input_holder">
              <label for="password_login">Password:</label>
              <input type="password" class="required login" name="password_login" id="password_login" value="" size="50" maxlength="50" />
              <div class="clear"></div>
            </div>
            
            <div class="input_holder toright remember_me"><input type="checkbox" class="checkbox" name="remember_me" id="remember_me" value="1" /> <span class="loginSubtext">Remember me next time</span></div>
            <div class="input_holder toright remember_me"><a href="/user-login-forgot-password.html" title="Forgot your password ?">Forgot your password ?</a>&nbsp;&nbsp;</div>
            
            <div class="clear"></div>
  
            <div class="inputSubmit center">
            <input type="submit" name="submit" value="Login" class="submit"/>
            </div>
  
          </form>
  

        </div>  
  
        <div id="login_right">
    
        <h2>Don´t have an account yet ?</h2>
        
        <a href="https://www.facebook.com/dialog/oauth?client_id=<?php echo YOUR_APP_ID; ?>&redirect_uri=http://<?php echo strWWWurl; ?>/user-register-action/user-facebook.html&scope=email" class="connectFB"><span>Connect with Facebook</span></a>
        <div class="clear"></div>

            <span class="hrBehind"></span>
            <span class="hrBehind-or">or</span>
            <span class="hrBehind"></span>
            <div class="clear"></div>
      
        <form id="form2" action="/user-register-action/new-user.html" method="post">
  
        <?php
  
        switch ($_GET['message2']){
        case 1:
          $strMessage2="You have not entered all the required fields.";
        break;
        case 2:
          $strMessage2="Passwords must be the same.";
        break;
        case 3:
          $strMessage2="E-mail ".$strEmail." is not valid.";
        break;
        case 4:
          $strMessage2="E-mail ".$strEmail." is already registred.";
        break;
        case 5:
          $strMessage2="Database error. Please contact webmaster.";
        break;
        case 6:
          $strMessage2="E-mails must be the same.";
        break;
        }
        if (!empty($strMessage2))echo '<p class="message center"><b>Error:</b> '.$strMessage2.'</p>';
  
  
        ?>
  
        <div class="input_holder">
              <label for="firstname">Your first name:</label>
              <input type="text" class="required login" name="firstname" id="firstname" value="<?php echo $strFirstname ?>" size="50" maxlength="50" />
              <div class="clear"></div>
        </div>

        <div class="input_holder">
              <label for="lastname">Your last name:</label>
              <input type="text" class="required login" name="lastname" id="lastname" value="<?php echo $strLastname ?>" size="50" maxlength="50" />
              <div class="clear"></div>
        </div>

        <div class="input_holder">
              <label for="email_reg">Your email:</label>
              <input type="text" class="required email login" name="email" id="email_reg" value="<?php echo $strEmail ?>" size="50" maxlength="50"   />
              <div class="clear"></div>
        </div>

        <div class="input_holder">
              <label for="email_confirm">Confirm email:</label>
              <input type="text" class="required email login" name="email_confirm" id="email_confirm" value="<?php echo $strEmailConfirm ?>" size="50" maxlength="50" />
              <div class="clear"></div>
        </div>

        <div class="input_holder">
              <label for="password">Password:</label>
              <input type="password" class="required login" name="password" id="password" value="" size="50" maxlength="50"   />
              <div class="clear"></div>
        </div>

        <div class="input_holder">
              <label for="password_confirm">Confirm password:</label>
              <input type="password" class="required login" name="password_confirm" id="password_confirm" value="" size="50" maxlength="50"   />
              <div class="clear"></div>
        </div>

        <input type="hidden" name="remember_me" value="1" />
        <div class="clear"></div>
  


         <div class="inputSubmit center">
            <input type="submit" name="submit" value="Create Account" class="submit"/>
            </div>
  
        <p class="small center">By clicking "Create Account" or "Connect to facebook"<br /> you confirm that you accept the <a href="/text/169-terms-of-service.html" title="Terms of Service">Terms of Service</a></p>
    
  
        </form>
      
      </div>

      <div class="clear"></div>

      </form>
    </div>

        
         
         <!-- main text end -->
      </div>
      
      <div class="clear">&nbsp;</div>
      <div id="text_space">&nbsp;</div>
      <div id="headlines_box"><?php require_once("inc/text_bottom_headlines.inc"); ?></div>
      
  </div>
  <?php if ($BoolBottomInfo){ ?>
  <!-- info box bottom -->
  <div class="corners top"><div class="toleft corner">&nbsp;</div><div class="toright corner">&nbsp;</div></div>
  <div id="bottom_info"><?php require_once("inc/bottom_info.inc"); ?></div>
  <div class="corners bottom"><div class="toleft corner">&nbsp;</div><div class="toright corner">&nbsp;</div></div>
  <!-- info box bottom end -->
  <?php } ?>
  
  <!-- bottom -->
  <div id="bottom_links"><?php require_once("inc/bottom_links.inc"); ?></div>
  <div id="bottom"><?php require_once("inc/bottom.inc"); ?></div>
  
</div>

</body>
</html>