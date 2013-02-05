<?php
require_once("inc/global.inc");

require_once("inc/ads.inc");
$strHeaderKeywords='fan forum, list';
$strHeaderDescription='Fan forum login';
require_once("inc/head.inc");

echo $head->setJavascriptExtendFile("admin/inc/js/jquery.validate.js");
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

echo $head->setTitle($strHeaderDescription." - ".strProjectName);
echo $head->setEndHead();
?>
<body>

<div id="ads_top"><?php show_ads("top_main"); ?></div>
<div id="layout" class="longer">
  
  <?php require_once("inc/bar_login.inc"); ?>
  <div id="logo"><?php require_once("inc/logo.inc"); ?></div>
  <div id="menu"><?php require_once("inc/menu.inc"); ?></div>
  <div id="main">
      
      <div id="text">
         <!-- main text -->
         
         <h1>Fan forum - create new account</h1>
        
        
        
        <?php
          echo '
            <form action="forum_action.php" method="post" id="form-01">
            <fieldset>
            <legend>Log-in</legend>
            
            <div id="forum_registration_box">
            ';
            
          $error=$_GET['error'];
          $error=$input->valid_text($error,true,true);
          if (!empty($error)){
            switch ($error){
              case 1:
                $msg="Login failed. Did not enter your user name or password.";
              break;
              case 2:
                $msg="Login failed. You might have entered wrong username or password, or your account is not active.";
              break;
            }
          }
          if (empty($msg)) $strError="display:none;"; else  $strError="";
            echo'
            <div class="msg info error" style="'.$strError.'">
            <span>'.$msg.'</span>.
            </div>
            
            <img src="img/bcg_login.png" alt="" height="128" width="128" class="toleft" style="margin-top:15px" /> 
            <table class="nice" style="width:430px;float:right">
              <tr>
              <td class="item" style="width: 100px;"><label for="user_name" class="bold">User name:</label></td>
              <td><input class="required" type="text" name="user_name" id="user_name" value="'.$_GET['user_name'].'" size="30" maxlength="255" /></td>
              </tr>
              
              <tr>
              <td class="item" style="width: 100px;"><label for="password" class="bold">Password:</label></td>
              <td><input class="required" type="password" name="password" id="password" value="" size="30" maxlength="100" /></td>
              </tr>
              
              <tr>
              <td class="item" style="width: 100px;">&nbsp;</td>
              <td><input type="checkbox" name="permanent" id="permanent" value="1" /><label for="permanent" class="small">pernament</label></td>
              </tr>
          
              <tr>
              <td colspan="2" class="center" id="forum"><a class="ico_reg" href="registration.html" title="Create new account">create new account</a></td>
              </tr>
              
               <tr>
              </tr>
               <tr>
              <td colspan="2" class="item center" style="width:auto">
                     <input type="submit" class="submit" value="Log-in" />
                     <input type="hidden" name="action" value="login" />
                </td>
                
              </tr>
            </table>
            ';
            echo'
       	    
       	   
         
       	    
       	    
            <div class="hidden">
              <input type="text" name="message" value=""  />
       	      <input type="text" name="text" value=""  />
       	      <input type="text" name="user" value=""  />
       	    </div>
       	    </div>
       	    
       	  </fieldset>
       	</form>
        ';
         ?>
         
         <!-- main text end -->
      </div>
      
      <div id="col_right">
        <!--column right -->
        
        <?php require_once("inc/col_right_default.inc"); ?>
        
        <!-- column right end -->
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