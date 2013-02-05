<?php
require_once("inc/global.inc");
$strUserArray=check_login(1);
$intCustomerID=$strUserArray['id'];

$id=$_GET['id'];
$id=$input->valid_text($id,true,true);
$intID=get_ID_from_url($id);

$query="SELECT * from sponsorship_payments WHERE id=".$intID." AND id_customer=".$intCustomerID;
//echo $query;
if ($con->GetQueryNum($query)>0){
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  $intAmount=$write['price_total'];
  $strCurrency=$con->GetSQLSingleResult("SELECT name as item FROM sponsorship_currency_list WHERE id=".$write['id_curency']);
  $intQuantity=$con->GetSQLSingleResult("SELECT count(*) as item FROM sponsorship_payments_items WHERE id_payment=".$intID);
  $strURL='/sponsorship-payments/'.get_url_text("payment",$write['id']);
  $strItemName="Eurohockey sponsorship (".$intQuantity." items)";
  

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
              echo '<p class="message center"><b>Error:</b> PAYMENT WAS CANCELED! Please try it again.</p>';
            break;
          }
          
         echo '<h3>PAY NOW</h3>';    
         echo '<p><b>Your amount to pay is '.$intAmount.' '.$strCurrency.'</b>, please select payment method:</p>';

        
          
         echo '<h2>PayPal</h2>';         

      // PayPal settings
      $return_url = 'http://'.strWWWurl.'/sponsorship-payments.html?message=1';
      $cancel_url = 'http://'.strWWWurl.'/'.$strURL.'?message=1';
      //$notify_url = 'http://'.strWWWurl.'/sponsorships_payments_paypal.php';
      //<input name="notify_url" type="hidden" value="'.$notify_url.'" />
    
      
      $paypal_email=DEFAULT_PAYPAL_ID;
      
      echo '
      <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <div class="center">
        <input name="cmd" type="hidden" value="_xclick" />
        <input name="no_note" type="hidden" value="1" />
        
        <input name="currency_code" type="hidden" value="'.$strCurrency.'" />
        
        <input name="business" type="hidden" value="'.$paypal_email.'" />
        <input name="item_number" type="hidden" value="'.$intID.'" />
        <input name="item_name" type="hidden" value="'.$strItemName.'" />
        <input name="amount" type="hidden" value="'.$intAmount.'" />
        <input name="quantity" type="hidden" value="1" />
        
        <input name="first_name" type="hidden" value="'.$strUserArray['name'].'" />
        <input name="last_name" type="hidden" value="'.$strUserArray['surname'].'" />
        <input name="email" type="hidden" value="'.$strUserArray['email'].'" />
        
        <input name="return" type="hidden" value="'.$return_url.'" />
        <input name="cancel_return" type="hidden" value="'.$cancel_url.'" />
        
        
        <img src="/img/button_paypal.png" height="50" style="margin-bottom:-15px" />
        
        <input type="image" name="submit" border="0"  src="https://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" alt="PayPal - The safer, easier way to pay online">
        <p class="small">You will be redirected to Paypal site, where you could choose to pay with your Paypal account or directly with credit card. Please note it may take up to 20 seconds to redirect to Paypal.</p>
        </div>
      </form>
    ';

         //echo '
          //<div style="width:450px; margin:auto">
            //<span class="hrBehind"></span>
            //<span class="hrBehind-or">or</span>
            //<span class="hrBehind"></span>
            //<div class="clear"></div>
            //</div>
         //';

         //echo '<h2>Platba převodem</h2>';         

         //echo '<p>nejake informace jak zaplatit prevodem</p>';         

       
        
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