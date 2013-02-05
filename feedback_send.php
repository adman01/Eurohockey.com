<?php
require_once("inc/global.inc");
require_once(IncludeAdminPath."/inc/phpmailer/class.phpmailer.php");
require_once("inc/ads.inc");

//captcha: vygenerujeme si kód který bude obsahovat čísla i písmena
$znak = strtolower(strtoupper(substr(md5(rand()),0,4))); 
$_SESSION['captcha'] = strtolower($znak);

$strHeaderKeywords='feedback form';
$strHeaderDescription='Feedback';
require_once("inc/head.inc");
echo $head->setTitle($strHeaderDescription." - ".strProjectName);
echo $head->setEndHead();

$realname=$_POST['realname'];
$realname=$input->valid_text($realname,true,true);
$email=$_POST['email'];
$email=$input->valid_text($email,true,true);
$comments=$_POST['comments'];
$comments=$input->valid_text($comments,true,true);
$link=$_POST['link'];
$link=$input->valid_text($link,true,true);

$cislo = md5(strtolower($_POST["cislo"])); //námi zadané číslo do kolonky bude zakódováno funkcí md5()
$kontrolni = strtolower($_POST["kontrolniznak"]); //odeslaný kontrolní kód ve formátu md5()
//...pak už jen zkontrolujem správnost zadaného kódu:

if (!empty($realname) AND !empty($email) AND !empty($comments) AND ($password==$password_check) AND strtolower($kontrolni) == strtolower($cislo)){

      $to      = 'info@eurohockey.com';
      //$to      = 'martin.formanek@gmail.com';
      $subject= 'Eurohockey.com feedback form';
      $message.='Realname: '.$realname.'
';
      $message.='Email: '.$email.'
';
      $message.= 'Comment: '.$comments.'';
      if (!empty($link)){
      $message.= '
URL: '.$link.'';
      }
      
    SendEmail("info@eurohockey.com","Eurohockey.com","info@eurohockey.com","Eurohockey.com",$to,$subject,$message);
    
    
    $strInfo='Thank you for your message. <a href="/">Continue on the main page</a>.';

}else{
  $strInfo='You did not enter the required data. <a href="javascript:history.back()">Please try again</a>.';
}


?>
<body>

<div id="ads_top"><?php show_ads("top_main"); ?></div>
<div id="layout" class="longer">
  
  <div id="logo"><?php require_once("inc/logo.inc"); ?></div>
  <div id="menu"><?php require_once("inc/menu.inc"); ?></div>
  <div id="main">
      
      <div id="text">
         <!-- main text -->
         
         <h1>Feedback</h1>
         
           <?php echo $strInfo;?>
         
         
   
         
         
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
