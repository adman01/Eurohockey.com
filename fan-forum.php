<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");
$strHeaderKeywords='fan forum, list';
$strHeaderDescription='Fan forum';
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
         
         <h1>Fan forum</h1>


<p>Please discuss about world of ice hockey on <a href="http://forums.internationalhockey.net/">http://forums.internationalhockey.net</a>
        
          
          
         
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