<?php
require_once("inc/global.inc");
//captcha: vygenerujeme si kód který bude obsahovat čísla i písmena
$znak = strtolower(strtoupper(substr(md5(rand()),0,4))); 
$_SESSION['captcha'] = strtolower($znak);

require_once("inc/ads.inc");
$strHeaderKeywords='fan forum, list';
$strHeaderDescription='Fan forum update registration';
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

		messages: {
			 password_check: {
			 	  required: " ",
				  equalTo: "<br />Please enter the same password as above"	
  			}
  		}
		
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
  
  <div id="logo"><?php require_once("inc/logo.inc"); ?></div>
  <div id="menu"><?php require_once("inc/menu.inc"); ?></div>
  <div id="main">
      
      <div id="text">
         <!-- main text -->
         
         <h1>Fan forum - update account</h1>
        
        
        
        <?php
           if ($_SESSION['login_web_user']==true){
              
              $query="SELECT * FROM web_users WHERE id=".$_SESSION['login_web_user_id']." AND  id_status=1";
              if ($con->GetQueryNum($query)==1){
                $result = $con->SelectQuery($query);
                $write = $result->fetch_array();
                $id_user=$write['id'];
                $username=$write['username'];
                $password=$write['password'];
                $email=$write['email'];
                $name=$write['name'];
                $surname=$write['surname'];
                $town=$write['town'];
                $id_country=$write['id_country'];
                
                $boolShow=true;
              }
            }
         if ($boolShow==true){
          echo '
            <form action="forum_action.php" method="post" id="form-01">
            <fieldset>
            <legend>Update account</legend>
            
            <div id="forum_registration_box">
            
            <div class="msg info error" style="display:none;">
            <span></span>.
            </div>
            
            <table class="nice">
              <tr>
              <td class="item"><label for="user_name" class="bold">User name *:</label></td>
              <td class="bold">'.$username.'</td>
              </tr>
              
              <tr>
              <td class="item"><label for="password" class="bold">Password *:</label></td>
              <td><input class="required password" type="password" name="password" id="password" value="" maxlength="100" /></td>
              </tr>
              
              <tr>
              <td class="item"><label for="password_check" class="bold">Retype password *:</label></td>
              <td><input equalto="#password" class="required" type="password" name="password_check"  id="password_check" value="" maxlength="100" /></td>
              </tr>
              
              <tr>
              <td class="item"><label for="email" class="bold">Email address *:</label></td>
              <td class="bold">'.$email.'</td>
              </tr>
              
              <tr>
              <td class="item"><label for="name" class="bold">Name *:</label></td>
              <td><input type="text" name="name" id="name" value="'.$name.'" size="35" maxlength="50" /></td>
              </tr>
              
              <tr>
              <td class="item"><label for="surname" class="bold">Surname *:</label></td>
              <td><input type="text" name="surname" id="surname" value="'.$surname.'" size="35" maxlength="50" /></td>
              </tr>
            
              <tr>
              <td class="item"><label for="town" class="bold">Town *:</label></td>
              <td><input class="required" type="text" name="town" id="town" value="'.$town.'" size="35" maxlength="50" /></td>
              </tr>
            
              <tr>
              <td class="item"><label for="id_country">Country *:</label></td>
              <td>
              <select name="id_country" class="required">
			        <option value="">select country</option>';
                $query_sub="SELECT * FROM countries_list ORDER by name";
                $result_sub = $con->SelectQuery($query_sub);
                while($write_sub = $result_sub->fetch_array()){
                  echo '<option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$id_country).'>'.$write_sub['name'].'</option>';
                }
              echo'</select></td></tr>  
              
              <tr>
              <td colspan="2" class="center"><small>* Fields marked with * are required</small></td>
              </tr>
              
               <tr>
              <td colspan="2" class="item center" style="width:auto">
                     <input type="submit" class="submit" value="Update" />
                     <input type="hidden" name="action" value="update_user" />
                </td>
                
              </tr>
            </table>
            ';
            echo'
      
       	    </div>
       	    
       	  </fieldset>
       	</form>
        ';
        }else{
          echo '<div class="error">Error! Fo account change you need to be logged. <a class="bold" href="/login.html'.$Odkaz.'" title="To login page">To login page</a></div>';
        }
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