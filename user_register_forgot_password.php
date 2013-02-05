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

    
        <h2>Reset your password</h2>
       
  
        <?php


        
        $strEmail=$_GET['email'];
        switch ($_GET['message']){
        case 1:
          echo "<p class=\"message center info\">A new password has been sent to ".$strEmail.".</p>";
        break;
        case 2:
          echo "<p class=\"message center\">Error: Email ".$strEmail." is not valid.</p>";
        break;
        case 3:
          echo "<p class=\"message center\">Error: No account exists for ".$strEmail.". Maybe you signed up using a different/incorrect e-mail address.</p>";
        break;
        }

        if (empty($_GET['message'])) echo '<p class="message center info">Enter your e-mail address to have the password associated with that account reset. A new password will be e-mailed to the address.</p>';
      
        ?>
  
       



<form id="form1" action="/user-register-action/forgot-password.html" method="post">
  
 
 
 <div class="input_holder">
              <label for="email" class="right">E-mail:</label>
              <input type="text" class="required email login" name="email" id="email" value="" size="50" maxlength="50" />
              <div class="clear"></div>
        </div>


  <div class="inputSubmit">
    <input type="submit" name="submit" id="submit" value="Reset password" class="submit"/>
  </div>
  
  

</form>
      
      </div>

      <div class="clear"></div>

   
        
         
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