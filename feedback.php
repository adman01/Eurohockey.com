<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");
$strHeaderKeywords='feedback form';
$strHeaderDescription='Feedback';

//captcha: vygenerujeme si kód který bude obsahovat čísla i písmena
$znak = strtolower(strtoupper(substr(md5(rand()),0,4))); 
$_SESSION['captcha'] = strtolower($znak);

 

require_once("inc/head.inc");
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
         
         <h1>Feedback</h1>
         
         <p>Thank you for your suggestions and comments.</p>
         
         <form method="post" action="/feedback_send.php">
          <fieldset>
          <legend>Feedback form</legend>
          
          <div>
            <p><b>Your name*:</b> <input type="text" name="realname" style="width:300px" /></p>
            <p><b>Your email*:</b> <input type="text" name="email" style="width:300px" /></p>
            <p><b>Your comment*:</b><br />
                <textarea name="comments" rows="" cols="" style="margin-left:8px; width:550px; height:200px"></textarea>
            <p><b>Retype control code*:</b>
                    <input type="text" class="required" maxlength="4" name="cislo" size="10" />
                    <!-- input pro zadání -->
                    <img src="captcha.php" alt="Kód" />
                    <?php
                    echo '<input type="hidden" name="kontrolniznak" value="'.md5($_SESSION['captcha']).'" />';
                    echo '<input type="hidden" name="link" value="'.$_REQUEST['link'].'" />';
                    ?>
                    <!-- kód pro ověření -->
            </p>
            <div class="normal center"><input style="background:#B7E2F2; color:#000000; padding:5px 15px 5px 15px; border:0px; font-size:13px; font-weight:bold" class="submit" type="submit" value="Submit form"></div>

            </div> 
          
          
          </fieldset>
         </form>
         
         
   
         
         
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