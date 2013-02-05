<?php
require_once("inc/global.inc");



function send_registration_email ($strEmail,$strFirstName,$id_user){
  
          $strMailTo = $strEmail;
          //$strMailTo="martin.formanek@gmail.com";
          $strMailHeader = "From: ".strEmailFrom;
          $strMailSubject = "Welcome to Eurohockey.com";
          if (empty($strFirstName)) $strMailSalutation=$strEmail; else $strMailSalutation=$strFirstName; 
$strMailContents = "
Dear ".$strMailSalutation.",
Welcome to ".strWWWurl."!

Please keep this e-mail for your records. Your account information is as follows:

-------------------------------------
Username/e-mail: ".$strMailTo."

Login URL: http://".strWWWurl."/user-login.html
-------------------------------------

Your password has been securely stored in our database and cannot be
retrieved. In the event that it is forgotten, you will be able to reset it
using the email address associated with your account.

Thank you for registering to ".strWWWurl."!
The Eurohockey.com Team
";
sendEmail(strEmailFrom,"Eurohockey.com",strEmailFrom,"Eurohockey.com",$strMailTo,$strMailSubject,$strMailContents);
  
}

//add new user througt facebook
if ($_GET['action']=="user-facebook"){
$bollRegister=true;
$facebook_code = $_REQUEST["code"];
if (!empty($facebook_code)) {
  
  $web_users->get_facebook_tooken(YOUR_APP_ID,YOUR_APP_SECRET,$facebook_code,"/user-register-action/user-facebook.html");
  $user=$web_users->get_facebook_data();
  
  $intFacebookID=$user->id;
  $intFacebookID=$input->check_number($intFacebookID);
  $strFirstName=$user->first_name;
  $strFirstName=$input->valid_text($strFirstName,true,true);
  $strLastName=$user->last_name;
  $strLastName=$input->valid_text($strLastName,true,true);
  $strEmail=$user->email;
  $strEmail=$input->valid_text($strEmail,true,true);
  if (!empty($intFacebookID)){
    
    $isEmail=$con->GetSQLSingleResult("SELECT count(*) as item FROM web_users WHERE lcase(email)=lcase('".$strEmail."')");
    $isFacebookID=$con->GetSQLSingleResult("SELECT count(*) as item FROM web_users WHERE id_facebook=".$intFacebookID."");
    
    if ($isFacebookID==0){
      if ($isEmail==0){
        $query="INSERT INTO web_users (id_facebook,registration_type,registration_status,name,surname,email) VALUES (".$intFacebookID.",1,1,'".$strFirstName."','".$strLastName."','".$strEmail."')";
        //echo $query; 
        if (!$con->RunQuery($query)){
          $bollRegister=false;
        }else{
          
          $id_user=$con->GetSQLSingleResult("SELECT id as item FROM web_users WHERE lcase(email)=lcase('".$strEmail."') ORDER by id DESC LIMIT 1");
          send_registration_email ($strEmail,$strFirstName,$id_user);
          
        }
      }
    }
  }else{
    $bollRegister=false;
  }
  
} 
else{
  $bollRegister=false;
 }
if ($bollRegister){
    $intLogin=$web_users->login($intFacebookID,md5(strWWWurl."facebook"),1);
    header("Location: /sponsorship.html");
}else {
  header("Location: /user-login.html");
}

}

//update email user profile througt facebook
if ($_GET['action']=="user-facebook-join"){
$bollRegister=true;
$facebook_code = $_REQUEST["code"];
if (!empty($facebook_code)) {
  $web_users->get_facebook_tooken(YOUR_APP_ID,YOUR_APP_SECRET,$facebook_code,"/user-register-action/user-facebook-join.html");
  $user=$web_users->get_facebook_data();
  
  $strUserArray=check_login(0);
  $strEmail=$user->email;
  $strEmail=$input->valid_text($strEmail,true,true);
  $intFacebookID=$user->id;
  $intFacebookID=$input->check_number($intFacebookID);
  $intID=$strUserArray['id'];
  if (!empty($strEmail)){
    
    $isFacebook=$con->GetSQLSingleResult("SELECT count(*) as item FROM web_users  WHERE id_facebook=".$intFacebookID." AND id<>".$intID);
    if ($isFacebook==0){
        $query="UPDATE web_users SET registration_type=1,password='',id_facebook=".$intFacebookID." WHERE id=".$intID;
        //echo $query; 
        if (!$con->RunQuery($query)){
          $bollRegister=false;
          $message=7;
        }else{
          
          //update OK
          
        }
    }else{
      $bollRegister=false;
      $message=8;
    }
  }else{
    $bollRegister=false;
    $message=7;
  }
  
} 
else{
  $bollRegister=false;
  $message=7;
 }
 
if ($bollRegister){
    $intLogin=$web_users->login($intFacebookID,md5(strWWWurl."facebook"),1);
    header("Location: /sponsorship.html");
}else {
  header("Location: /user-login.html?message=".$message);
}

}


//add new user througt web form
if ($_GET['action']=="new-user"){

  $bollRegister=true;
  
  //$query="delete FROM players";
  //$con->RunQuery($query);
  
  $strFirstName=$_POST['firstname'];
  $strFirstName=$input->valid_text($strFirstName,true,true);
  $strLastName=$_POST['lastname'];
  $strLastName=$input->valid_text($strLastName,true,true);
  $strEmail=$_POST['email'];
  $strEmail=$input->valid_text($strEmail,true,true);
  $strEmailConfirm=$_POST['email_confirm'];
  $strEmailConfirm=$input->valid_text($strEmailConfirm,true,true);
  $strPassword=$_POST['password'];
  $strPassword=$input->valid_text($strPassword,true,true);
  $strPassword_confirm=$_POST['password_confirm'];
  $strPassword_confirm=$input->valid_text($strPassword_confirm,true,true);
  $intRememberMe=$_POST['remember_me'];
  $intRememberMe=$input->check_number($intRememberMe);
  
  $strReturn="&firstname=".$_POST['firstname']."&lastname=".$_POST['lastname']."&email=".$_POST['email']."&email_confirm=".$_POST['email_confirm']."&remember_me=".$_POST['remember_me'];
  if (empty($strFirstName) or empty($strLastName) or empty($strEmail) or empty($strEmailConfirm) or empty($strPassword) or $strFirstName == "First name" or $strLastName == "Last name" or $strPassword == "Password"){
    $bollRegister=false;
    $strErrorMessage=1;
  }elseif ($strPassword<>$strPassword_confirm) {
    $bollRegister=false;
    $strErrorMessage=2;
  }elseif ($strEmail<>$strEmailConfirm) {
    $bollRegister=false;
    $strErrorMessage=6;
  }elseif (!$web_users->checkEmailAddress($strEmail)){
    $bollRegister=false;
    $strErrorMessage=3;
  }else{
    $isEmail=$con->GetSQLSingleResult("SELECT count(*) as item FROM web_users  WHERE lcase(email)=lcase('".$strEmail."')");
    if ($isEmail==0){
        
        $query="INSERT INTO web_users (registration_type,registration_status,name,surname,email,password) VALUES (2,1,'".$strFirstName."','".$strLastName."','".$strEmail."','".md5($strPassword)."')";
        //echo $query; 
        if (!$con->RunQuery($query)){
          $bollRegister=false;
          $strErrorMessage=5;
        }else{
           
           $id_user=$con->GetSQLSingleResult("SELECT id as item FROM web_users WHERE lcase(email)=lcase('".$strEmail."') ORDER by id DESC LIMIT 1");
           send_registration_email($strEmail,$strFirstName,$id_user);
           $intLogin=$web_users->login($strEmail,md5($strPassword),$intRememberMe);
           if ($intLogin){
            header("Location: /sponsorship.html");
           }else{
            header("Location: /user-login.html?message2=".$intLogin);
           }
             
                   
      }
        
    }else{
        $bollRegister=false;
        $strErrorMessage=4;
    }
  }
  if ($bollRegister){
  
  }else{
    header("Location: /user-login.html?message2=".$strErrorMessage.$strReturn);
  }
  
  
}

//login by web form
if ($_GET['action']=="login"){

  $strEmail=$_POST['email'];
  $strEmail=$input->valid_text($strEmail,true,true);
  $strPassword=$_POST['password_login'];
  $strPassword=$input->valid_text($strPassword,true,true);
  $intRememberMe=$_POST['remember_me'];
  $intRememberMe=$input->check_number($intRememberMe);
  
  $intLogin=$web_users->login($strEmail,md5($strPassword),$intRememberMe);
  if ($intLogin==1){
    header("Location: /sponsorship.html");
    
  }else{
     header("Location: /user-login.html?message=".$intLogin);
  }

}


//logout
if ($_GET['action']=="logout"){
  $CookieDuration = -3400;
  setcookie("permanent_is_loged",0,$CookieDuration,"/");
  setcookie("permanent_userID",0,$CookieDuration,"/");
  setcookie("permanent_userTooken",0,$CookieDuration,"/");
  $_SESSION['is_loged']=false;
  $_SESSION['userID']="";
  $_SESSION['userTooken']="";
  if ($_GET['loc']==1){
    header("Location: /user-login.html");
  }else{
    header("Location: /user-login.html");
  }
}

//send registration email
if ($_GET['action']=="send-registration-email"){
  
  $strUserArray=check_login(1);
  send_registration_email2 ($strUserArray['email'],$strUserArray['name'],$strUserArray['id']);
  header("Location: /dashboard.html");
}

function generatePassword($length=9, $strength=0) {
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) {
		$vowels .= "AEUY";
	}
	if ($strength & 4) {
		$consonants .= '23456789';
	}
	if ($strength & 8) {
		$consonants .= '@#$%';
	}
 
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}

//reset password
if ($_GET['action']=="forgot-password"){
  
  $strEmail=$_POST['email'];
  $strEmail=$input->valid_text($strEmail,true,true);
  
  if (!$web_users->checkEmailAddress($strEmail)){
    $bollRegister=false;
    $strErrorMessage=2;
  }else{
    $isEmail=$con->GetSQLSingleResult("SELECT count(*) as item FROM web_users  WHERE lcase(email)=lcase('".$strEmail."') AND registration_type=2");
    if ($isEmail==1){
      
      $strNewPassword=generatePassword();
      
      $query="UPDATE web_users SET password='".md5($strNewPassword)."' WHERE lcase(email)=lcase('".$strEmail."')  AND registration_type=2";
        if (!$con->RunQuery($query)){
          $bollRegister=false;
          $message=3;
        }else{
        
        $strMailTo = $strEmail;
          $strMailHeader = "From: ".strEmailFrom;
          $strMailSubject = "Your password has been reset - Eurohockey.com";
$strMailContents = "
We've reset your password as you requested.

E-mail: ".$strEmail."
Password: ".$strNewPassword."

If you click this URL you may be able to by pass log-in prompt. Then please change your password to something you will remember.

http://".strWWWurl."/user-login.html

Thanks,
The Eurohockey.com Team
";
         
        ;

          
        sendEmail(strEmailFrom,"Eurohockey.com",strEmailFrom,"Eurohockey.com",$strMailTo,$strMailSubject,$strMailContents);
	        
        $strErrorMessage=1;
         
        }
      
    }else{
      $bollRegister=false;
      $strErrorMessage=3;
    }
  }
  
  header("Location: /user-login-forgot-password.html?message=".$strErrorMessage."&email=".$strEmail);
  
}

//update user 
if ($_GET['action']=="update-user"){
  $strUserArray=check_login(1);
  $bollUpdate=true;
  
  $strFirstName=$_POST['firstname'];
  $strFirstName=$input->valid_text($strFirstName,true,true);
  $strLastName=$_POST['lastname'];
  $strLastName=$input->valid_text($strLastName,true,true);
  $strEmail=$_POST['email'];
  $strEmail=$input->valid_text($strEmail,true,true);
  $strPassword=$_POST['password'];
  $strPassword=$input->valid_text($strPassword,true,true);
  $strPassword_confirm=$_POST['password_confirm'];
  $strPassword_confirm=$input->valid_text($strPassword_confirm,true,true);
  
  $strAddress=$_POST['address'];
  $strAddress=$input->valid_text($strAddress,true,true);
  $strCity=$_POST['city'];
  $strCity=$input->valid_text($strCity,true,true);
  $strPostalCode=$_POST['postal_code'];
  $strPostalCode=$input->valid_text($strPostalCode,true,true);
  $strPhone=$_POST['phone'];
  $strPhone=$input->valid_text($strPhone,true,true);
  $StrIsPassword=$_POST['is_password'];
  $StrIsPassword=$input->check_number($StrIsPassword);
  $intIdCountry=$_POST['id_country'];
  $intIdCountry=$input->check_number($intIdCountry);

  $strIco=$_POST['ico'];
  $strIco=$input->valid_text($strIco,true,true);
  $strDic=$_POST['dic'];
  $strDic=$input->valid_text($strDic,true,true);

  $strReturn="&firstname=".$_POST['firstname']."&lastname=".$_POST['lastname']."&email=".$_POST['email']."&city=".$_POST['city']."&postal_code=".$_POST['postal_code']."&phone=".$_POST['phone']."&id_country=".$_POST['id_country']."&ico=".$_POST['ico']."&dic=".$_POST['dic']."&is_password=".$_POST['is_password']."&address=".$_POST['address'];
  //echo $strReturn; 
  if (empty($strFirstName) or empty($strLastName) or empty($intIdCountry) or empty($strAddress) or empty($strPostalCode)){
    $bollUpdate=false;
    $strErrorMessage=2;
  }else{
    
    $intRegistrationType=$strUserArray["registration_type"];
    if ($intRegistrationType==2){
      //email registration
      
      if ($StrIsPassword==1){
        if ($strPassword<>$strPassword_confirm) {
          $bollUpdate=false;
          $strErrorMessage=3;   
        }else{
           $strSQLUpdate.=",password='".md5($strPassword)."'";
        }
      }
      
      if ($strEmail!=$strUserArray["email"]){
      if (empty($strEmail) and $bollUpdate){
        $bollUpdate=false;
        $strErrorMessage=2;
      }elseif (!$web_users->checkEmailAddress($strEmail)){
        $bollUpdate=false;
        $strErrorMessage=4;
      }else{
        
        $isEmail=$con->GetSQLSingleResult("SELECT count(*) as item FROM web_users  WHERE lcase(email)=lcase('".$strEmail."')");
        if ($isEmail==0){
             $strSQLUpdate.=",email='".$strEmail."'";
        }else{
          $bollUpdate=false;
          $strErrorMessage=5;
        }
        
      }
      }
      
    }
    
    if ($bollUpdate){ 
    
        $query="UPDATE web_users SET 
                  name='".$strFirstName."',surname='".$strLastName."'".$strSQLUpdate."
                  ,address='".$strAddress."',city='".$strCity."',postal_code='".$strPostalCode."',phone='".$strPhone."',
                  ico='".$strIco."',dic='".$strDic."',id_country='".$intIdCountry."'
                  
                WHERE id=".$strUserArray["id"];

        //echo $query; 
        if (!$con->RunQuery($query)){
          $bollUpdate=false;
          $strErrorMessage=6;
        }else{

      
        }
    
    }
  }
   // echo $strErrorMessage.'<br />';
    //echo $strSQLUpdate;
  
  if ($bollUpdate){
    
    header("Location: /sponsorship.html");
    
  }else{
    header("Location: /user-update.html?message=".$strErrorMessage.$strReturn);
  }
  
  
}




?>
   