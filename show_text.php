<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");

$id=$_GET['id'];
$id=$input->valid_text($id,true,true);
$id=get_ID_from_url($id);
$passw=$_GET['passw'];
$passw=$input->valid_text($passw,true,true);
if ($passw==md5("eurohockey.com")) {$SQLshow="";} else{$SQLshow=" show_item=1 AND";}

if (!isset($id) or empty($id)) $id=1;

$query="SELECT text,header from static_texts WHERE ".$SQLshow." id='".$id."'";
$result = $con->SelectQuery($query);
if ($con->GetQueryNum($query)>0){
    //echo $query;    
}else{
    $query="SELECT text,header from static_texts WHERE id=1";
    $result = $con->SelectQuery($query);
}

$write = $result->fetch_array();
$strText=$write['text'];
$strText=stripcslashes($strText);
$strTitle=$write['header'];
$strKeywords=$write['keywords'];

$strHeaderKeywords=$strKeywords;
$strHeaderDescription=$strTitle;

require_once("inc/head.inc");
echo $head->setTitle(HeaderDescription." - ".$strTitle);
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
         
         <?php echo $strText; ?>
         
         <!-- main text end -->
      </div>
      
      <div id="col_right">
        <?php require_once("inc/col_right_default.inc"); ?>
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