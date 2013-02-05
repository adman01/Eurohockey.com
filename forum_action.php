<?php
require_once("inc/global.inc");
//captcha: vygenerujeme si kód který bude obsahovat čísla i písmena
$znak = strtolower(strtoupper(substr(md5(rand()),0,4))); 
$_SESSION['captcha'] = strtolower($znak);

//login
if ($_POST["action"]=="login"){
    $user_name=$_POST['user_name'];
    $user_name=$input->valid_text($user_name,true,true);
    $password=$_POST['password'];
    $password=$input->valid_text($password,true,true);
    $permanent=$_POST['permanent'];
    $permanent=$input->check_number($permanent);
    if (!empty($user_name) AND !empty($password)){
        $query="SELECT id,password,email FROM web_users WHERE lcase(username)=lcase('".$user_name."') AND lcase(password)=lcase('".md5($password)."') AND id_status=1";
        if ($con->GetQueryNum($query)==1){
          $result = $con->SelectQuery($query);
          $write = $result->fetch_array();
          $_SESSION['login_web_user']=true;
          $_SESSION['login_web_user_id']=$write['id'];
          if ($permanent==1) {
            $Month = 2592000 + time();
            setcookie("eurohockey_permanent",1,$Month);
            setcookie("eurohockey_permanent_id",$write['id'],$Month);
            setcookie("eurohockey_permanent_login",md5($write['email']),$Month);
          }
          header("Location: /fan-forum.html");
    }else{
      //uzivatel nenalezen
      header("Location: /login.html?error=2");
    }
    
}else{
  //chybi jmeno nebo heslo
  header("Location: /login.html?error=1");
}
} 

if ($_GET["action"]=="logout"){
  $_SESSION['login_web_user']=false;
  $_SESSION['login_web_user_id']=0;
  setcookie("eurohockey_permanent",0,$Month);
  setcookie("eurohockey_permanent_id","",$Month);
  setcookie("eurohockey_permanent_password","",$Month);
  header("Location: /fan-forum.html");
}

 //forum post add
if ($_POST["action"]=="add_post"){
  $text_forum=$_POST['text_forum'];
  $text_forum=urldecode($text_forum);
  $text_forum=nl2br($input->valid_text($text_forum,true,true));
  if (!empty($text_forum) AND !empty($_SESSION['login_web_user_id']) AND $_SESSION['login_web_user']==true) {
  $query="INSERT INTO forum (id_related,text_forum,id_user,show_item,date_time) VALUES 
          (0,'".$text_forum."',".$_SESSION['login_web_user_id'].",1,NOW())";
  $con->RunQuery($query);
  //echo $query;
  header("Location: /fan-forum.html");
  }else{
     header("Location: /fan-forum.html?error=1");
  }
}

require_once("inc/ads.inc");
$strHeaderKeywords='fan forum, list';
$strHeaderDescription='Fan forum registration';
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
         
        <?php
         //registrace insert
        if ($_POST["action"]=="add_user"){
           
        echo '<h1>Fan forum - create new account</h1>'; 
          $user_name=$_POST['user_name'];
          $user_name=$input->valid_text($user_name,true,true);
          $password=$_POST['password'];
          $password=$input->valid_text($password,true,true);
          $password_check=$_POST['password_check'];
          $password_check=$input->valid_text($password_check,true,true);
          $name=$_POST['name'];
          $name=$input->valid_text($name,true,true);
          $surname=$_POST['surname'];
          $surname=$input->valid_text($surname,true,true);
          $town=$_POST['town'];
          $town=$input->valid_text($town,true,true);
          $id_country=$_POST['id_country'];
          $id_country=$input->valid_text($id_country,true,true);
          $email=$_POST['email'];
          $email=$input->valid_text($email,true,true);
          
          $Odkaz='?user_name='.$user_name.'&amp;name='.urlencode($name).'&amp;surname='.urlencode($surname).'&amp;town='.urlencode($town).'&amp;email='.urlencode($email).'&amp;id_country='.urlencode($id_country);
          $cislo = md5(strtolower($_POST["cislo"])); //námi zadané číslo do kolonky bude zakódováno funkcí md5()
          $kontrolni = strtolower($_POST["kontrolniznak"]); //odeslaný kontrolní kód ve formátu md5()
          //...pak už jen zkontrolujem správnost zadaného kódu:
        
          if ($input->one_word($user_name)==true AND $input->one_word($password)==true){
            if (isset($user_name) AND isset($password) AND isset($password_check) AND ($password==$password_check) AND strtolower($kontrolni) == strtolower($cislo)){
            
                $query="SELECT id FROM web_users WHERE lcase(username)=lcase('".$user_name."')";
                if ($con->GetQueryNum($query)==0){
                  $query="SELECT id FROM web_users WHERE lcase(email)=lcase('".$email."')";
                  if ($con->GetQueryNum($query)==0){
                      $query="INSERT INTO web_users (date_time,login_date_actual,login_date_last,id_status,username,password,email,name,surname,id_country,town)
                          VALUES (NOW(),NOW(),NOW(),0,'".$user_name."','".md5($password)."','".$email."','".$name."','".$surname."','".$id_country."','".$town."')
                      ";
                      $con->RunQuery($query);
                      
                      $query="SELECT id FROM web_users WHERE lcase(email)=lcase('".$email."') and lcase(username)=lcase('".$user_name."')";
                      $result = $con->SelectQuery($query);
                      $write = $result->fetch_array();
                      $id_user=$write['id'];
                      
                      if (!empty($id_user)){
                        $strBody="Activation email for registration on ".strWWWurl."\n\n";
                        $strBody=$strBody."Dear user,\n";
                        $strBody=$strBody."Thank you for registering with a username: '".$user_name."'.\n";
                        $strBody=$strBody."To activate the user account, please click or copy into the address bar of your browser this URL:\n";
                        $strBody=$strBody."http://".strWWWurl."/registration-confirm.html?id=".md5($user_name.$email)."&id_user=".$write['id']."&action=confirm\n";
                        $strBody=$strBody."\nThank you for your support";
                        $strBody=$strBody."\eurohockey.com";
                        $headers .= "From: " . strEmailFrom."\n";
                        $headers .= "Content-Type: text/plain; charset=utf-8\n";
                      
                        $strSubject="Activation email for registration on ".strWWWurl;
                        if (Mail($email, $strSubject, $strBody,$headers)) {
                         
                        echo '<h3>Thank you for registering.</h3><p>To email <b>'.$email.'</b> was sent a confirmation e-mail. User account will be activated by clicking on the activation link.<br /> <a class="bold"  href="/fan-forum.html" title="Continue to the forum">Continue to the forum</a></p>';
                      }else{
                        echo '<div class="error">Error! It was not possible to send a confirmation email.<br />Please contact the <a class="mail" href="/text/148-about-us.html">webmaster.</a> <br /><a class="bold" href="/" title="Continue on the main page">Continue on the main page</a></div>';
                      }
                      
                     } 
                  }
                  else{
                  echo '<div class="error">Error! To email <b>. '.$email.' </ b> is already registered with another user account.<br />Please enter a valid email address to another is not registered in the system.<br /><a class="bold" href="/registration.html'.$Odkaz.'" title="Go back">Go back</a></div>';    
                }
                }else{
                  echo '<div class="error">Error! Username <b> '.$user_name.' </ b> is already assigned, please choose another.<br /><a class="bold" href="/registration.html'.$Odkaz.'" title="Go back">Go back</a></div>';    
                }
            }
            else{
              echo '<div class="error">Error! You did not enter all the required data, or passwords you entered do not match, or you entered the wrong verification code. <br /><a class="bold" href="/registration.html'.$Odkaz.'" title="Go back">Go back</a></div>';
            }
          }else{
            echo '<div class="error">Error! In user name and password can not contain spaces.<br /><a class="bold" href="/registration.html'.$Odkaz.'" title="Go back">Go back</a></div>';
          }
          
          }
          
        //registrace update
        if ($_POST["action"]=="update_user"){
           
        echo '<h1>Fan forum - update account</h1>'; 
          $password=$_POST['password'];
          $password=$input->valid_text($password,true,true);
          $password_check=$_POST['password_check'];
          $password_check=$input->valid_text($password_check,true,true);
          $name=$_POST['name'];
          $name=$input->valid_text($name,true,true);
          $surname=$_POST['surname'];
          $surname=$input->valid_text($surname,true,true);
          $town=$_POST['town'];
          $town=$input->valid_text($town,true,true);
          $id_country=$_POST['id_country'];
          $id_country=$input->valid_text($id_country,true,true);
          
          $Odkaz='?user_name='.$user_name.'&amp;name='.urlencode($name).'&amp;surname='.urlencode($surname).'&amp;town='.urlencode($town).'&amp;email='.urlencode($email).'&amp;id_country='.urlencode($id_country);
          
          if ($input->one_word($password)==true){
            if (isset($password) AND isset($password_check) AND ($password==$password_check) and !empty($_SESSION['login_web_user_id'])){
            
                      $query="UPDATE web_users SET password='".md5($password)."',name='".$name."',surname='".$surname."',id_country='".$id_country."',town='".$town."' WHERE id=".$_SESSION['login_web_user_id'];
                      //echo $query;
                      $con->RunQuery($query);
                      echo '<h3>Your account has been successfully updated.</h3><br /> <a class="bold"  href="/fan-forum.html" title="Continue to the forum">Continue to the forum</a></p>';
            }
            else{
              echo '<div class="error">Error! You did not enter all the required data, or passwords you entered do not match, or you entered the wrong verification code. <br /><a class="bold" href="/user-update.html'.$Odkaz.'" title="Go back">Go back</a></div>';
            }
          }else{
            echo '<div class="error">Error! Password can not contain spaces.<br /><a class="bold" href="/user-update.html'.$Odkaz.'" title="Go back">Go back</a></div>';
          }
          
          }
          
        //registrace confirm
        if ($_GET["action"]=="confirm"){
           
          echo '<h1>Fan forum - account confirmation</h1>'; 
      
          $id=$_GET['id'];
          $id=$input->valid_text($id,true,true);
          $id_user=$_GET['id_user'];
          $id_user=$input->check_number($id_user);
          if ($input->one_word($id)==true AND !empty($id_user) AND !empty($id)){
              $query="SELECT id,username,email FROM web_users WHERE id=".$id_user." AND id_status=0";
                if ($con->GetQueryNum($query)==1){
                  
                  $result = $con->SelectQuery($query);
                  $write = $result->fetch_array();
                  if ($id==md5($write['username'].$write['email'])){
                    $query="UPDATE web_users SET id_status=1 WHERE id=".$write['id'];
                    $con->RunQuery($query);
                    echo '<h3>Your user account '.$write['user_name'].' has been successfully activated.</h3><p><a class="bold" href="/fan-forum.html" title="Continue to the forum">Continue to the forum</a></p>';
                  }else{
                    echo '<div class="error">Unfortunately it was not possible to activate the registration. <br /> You\'ve probably entered the wrong activation link or the user account is already active.<br /><a class="bold" href="/" title="Continue on the main page">Continue on the main page</a></div>';
                  }
                }else{
                  echo '<div class="error">Unfortunately it was not possible to activate the registration. <br /> You\'ve probably entered the wrong activation link or the user account is already active.<br /><a class="bold" href="/" title="Continue on the main page">Continue on the main page</a></div>';
                }
          }else{
            echo '<div class="error">Unfortunately it was not possible to activate the registration. <br /> You\'ve probably entered the wrong activation link or the user account is already active.<br /><a class="bold" href="/" title="Continue on the main page">Continue on the main page</a></div>';
          }
          
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