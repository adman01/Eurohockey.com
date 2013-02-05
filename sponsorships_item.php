<?php
require_once("inc/global.inc");
$strUserArray=check_login(1);
$intCustomerID=$strUserArray['id'];

$id=$_GET['id'];
$id=$input->valid_text($id,true,true);
$intID=get_ID_from_url($id);

$query="SELECT * from sponsorship WHERE id=".$intID." AND id_customer=".$intCustomerID;
//echo $query;
if ($con->GetQueryNum($query)>0){
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  $intAmount=$write['price'];
  $intMonths=$write['duration_months'];
  $strDateAdd=$write['date_add'];
  if ($write['id_status']==2){
    $strDateExpire=date("d M Y",strtotime($write['date_expire']));
  }else{
    $strDateExpire="-";
  }
  $intPrice=number_format($write['price'], 2, ',', ' ');
  $intPriceTotal=number_format(($write['price']*$write['duration_months']), 2, ',', ' ');
  $intAdsID=$write['id_ads'];


  $strCurrency=$con->GetSQLSingleResult("SELECT name as item FROM sponsorship_currency_list WHERE id=".$write['id_curency']);
  $strStatus=$con->GetSQLSingleResult("SELECT name as item FROM sponsorship_status_list WHERE id=".$write['id_status']);
  if ($write['id_status']==2 AND strtotime($write["date_expire"])<date("U")){
                   $strStatus='expired'; 
  }
  $intItemId=$write['id_item'];
  switch ($write['id_type']) {
                  case 1:
                    $ItemName=$games->GetActualLeagueName($write['id_item'],ActSeason);
                    $strTypeName="league";
                  break;
                  case 2:
                    $ItemName=$games->GetActualClubName($write['id_item'],ActSeason);
                    $strTypeName="club";
                  break;
                  case 3:
                    $ItemName=$con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$write['id_item']);
                    $strTypeName="player";
                  break;
  }
  

}else{
  header("Location: /text/404-error.html");
}

require_once("inc/ads.inc");

$strHeader="Sponsorship";

$strHeaderKeywords=$strHeader;
$strHeaderDescription=$strHeader;

require_once("inc/head.inc");
echo $head->setTitle($strHeader." - ".strProjectName);

?>
  <script type="text/javascript">
  $(document).ready(function(){
 

  });
  </script>
<?php

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
         <div id="login">

         <?php  
         echo '<h1>'.$strHeader.'</h1>';

         require_once("inc/sponsorship_menu.inc");

           switch ($_GET['message']){
            case 1:
              echo '<p class="message center"></p>';
            break;
          }
          
          echo '<h3>Detail for sponsorship #'.$intID.'</h3>';
         echo '<table class="nice">';
           echo '<tr>';
           echo '<td valign="top" class="item">Created:</td>';
           echo '<td valign="top">'.date("d M Y",strtotime($strDateAdd)).'</td>';
           echo '</tr>';
           echo '<tr>';
           echo '<td valign="top" class="item">Actual status:</td>';
           echo '<td valign="top">'.$strStatus.'</td>';
           echo '</tr>';
           echo '<tr>';
           echo '<td valign="top" class="item">Item type:</td>';
           echo '<td valign="top">'.$strTypeName.'</td>';
           echo '</tr>';
           echo '<tr>';
           echo '<td valign="top" class="item">Item name:</td>';
           $strURL=get_url_text($ItemName,$intItemId);
           echo '<td valign="top"><a class="bold" href="/'.$strTypeName.'/'.$strURL.'">'.$ItemName.'</a></td>';
           echo '</tr>';
           echo '<tr>';
           echo '<td valign="top" class="item">Fee:</td>';
           echo '<td valign="top">'.$intPrice.' '.$strCurrency.' <small>(per month)</small> | '.$intPriceTotal.' '.$strCurrency.' <small>(total)</small></td>';
           echo '</tr>';
           echo '<tr>';
           echo '<td valign="top" class="item">Duration:</td>';
           echo '<td valign="top">'.$intMonths.' months</td>';
           echo '</tr>';
           echo '<tr>';
           echo '<td valign="top" class="item">Expire date:</td>';
           echo '<td valign="top">'.$strDateExpire.'</td>';
           echo '</tr>';
           echo '<tr>';
           echo '<td valign="top" class="item">AD:</td>';
           echo '<td valign="top" class="sponsorship_item_show" style="padding-top:20px; padding-bottom:20px; padding-left:20px;">';
               $query="SELECT * from sponsorship_ads WHERE id=".$intAdsID." AND id_customer=".$intCustomerID;
               //echo $query;
               if ($con->GetQueryNum($query)>0){
               $result = $con->SelectQuery($query);
               $write = $result->fetch_array();
               $strAds="";
                      switch ($write['id_type']) {
                        case 2:
                          if (!empty($write['id_image'])){
                            $strImagePath=PhotoFolder."/sponsorship/sponsorship_".$write['id'].".jpg";
                            if (file_exists($strImagePath)) {
                               $strAds= '<img src="/'.$strImagePath.'" alt="'.$write['name'].'" />';
                            }
                          }
                        break;
                        case 1:
                          $strAds=$write['html_text'];
                        break;
                      }
                    if (!empty($write['url'])){ echo '<a onclick="return !window.open(this.href);" href="'.$input->check_external_link($write['url']).'">';}
                    echo $strAds;
                    if (!empty($write['url'])){ echo '</a>';}
                }
                
           echo '</td>';
           echo '</tr>';
           
         echo '</table>';
         ?>
       
       </div>
         

       
        
         
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