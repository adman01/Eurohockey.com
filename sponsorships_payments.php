<?php
require_once("inc/global.inc");
$strUserArray=check_login(1);
$intCustomerID=$strUserArray['id'];
require_once("inc/ads.inc");

$strHeader="Sponsorship";

$strHeaderKeywords=$strHeader;
$strHeaderDescription=$strHeader;



require_once("inc/head.inc");
echo $head->setTitle($strHeader." - ".strProjectName);

?>
  <script type="text/javascript">
  $(document).ready(function(){
      

       $('.show_items').click(function () {
      intID=$(this).attr('id');
      $('#'+intID+'_box').toggle();
   
      });

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

         echo '<h3>My payments</h3>';
         
          switch ($_GET['message']){
            case 1:
              echo '<p class="message center info">PAYMENT VALIDATED & VERIFIED. Thank you for your payment.</p>';
            break;
          }

          $query="SELECT * from sponsorship_payments WHERE id_customer=".$intCustomerID. ' ORDER BY date_add DESC';
          if ($con->GetQueryNum($query)>0){
            echo '<table class="basic">';
              echo '<tr>';
                echo '<thead>';
                  echo '<th>Date</th>';
                  echo '<th>Paid date</th>';
                  echo '<th>Fee</th>';
                  echo '<th>Status</th>';
                  echo '<th>Discount</th>';
                  echo '<th></th>';
                echo '</thead>';
              echo '</tr>';
              $counter=0;
              $result = $con->SelectQuery($query);
              while($write = $result->fetch_array()){

                  $counter++;
                  if ($counter%2) $style=""; else $style="dark";
                 
                  switch ($write['id_status']) {
                    case 1:
                      $strStatus="Paid";
                      break;
                    case 0:
                      $strStatus='<span style="color:red" class="bold">NOT paid</span>';
                      break;
                  }
                  echo '<tr class="'.$style.'">'; 
                  echo '<td valign="top">'.date("d M Y",strtotime($write['date_add'])).'</td>';
                  if (!empty($write['date_paid'])){
                    $strPaidDate=date("d M Y",strtotime($write['date_paid']));
                  }else{$strPaidDate="-";}
                  echo '<td valign="top">'.$strPaidDate.'</td>';
                  $strCurrencyName=$con->GetSQLSingleResult("SELECT name as item FROM sponsorship_currency_list WHERE id=".$write['id_curency']);
                  echo '<td valign="top" class="bold">'.number_format($write['price_total'], 2, ',', ' ').' '.$strCurrencyName.'</td>';
                  echo '<td valign="top">'.$strStatus.'</td>';
                  echo '<td valign="top">'.$write['id_discount'].'%</td>';
                  
                  if (empty($write['id_status'])) $strPayNow='<span class="link"><a class="pay_now" href="/sponsorship-payments/'.get_url_text("payment",$write['id']).'"><span>PAY NOW</span></a></span>'; else  $strPayNow='';

                  echo '<td valign="top" class="link">
                  <span class="link"><a href="javascript:void(0);" class="show_items" id="items_'.$write['id'].'">Show payment items</a></span>';
                  if (!empty($strPayNow)) echo ' | '.$strPayNow;
                  echo'</td>';
                  echo '</tr>';
                  echo '<tr class="hidden" id="items_'.$write['id'].'_box">'; 
                    echo '<td valign="top" colspan="6" style="padding-top:10px; padding-bottom:10px; padding-left:10px;">';
                       
                       $strTotalFee=0;
                       $query_sub="SELECT sponsorship.* from sponsorship INNER JOIN sponsorship_payments_items ON  sponsorship.id=sponsorship_payments_items.id_sponsorship WHERE id_customer=".$intCustomerID." AND sponsorship_payments_items.id_payment=".$write['id']." order by id DESC";
                       //echo $query_sub;
                       if ($con->GetQueryNum($query_sub)>0){
                        echo '<table class="basic">';
                          echo '<tr>';
                            echo '<thead>';
                              echo '<th>Type</th>';
                              echo '<th>Item name</th>';
                              echo '<th>Ad</th>';
                              echo '<th>Fee</th>';
                              echo '<th>Months</th>';
                              echo '<th>Total</th>';
                              echo '<th></th>';
                            echo '</thead>';
                          echo '</tr>';
                          $counter=0;
                          $result_sub = $con->SelectQuery($query_sub);
                          while($write_sub = $result_sub->fetch_array()){

                            $counter++;
                            if ($counter%2) $style=""; else $style="dark";
                            echo '<tr class="'.$style.'">';
                  
                            switch ($write_sub['id_type']) {
                            case 1:
                              $ItemName=$games->GetActualLeagueName($write_sub['id_item'],ActSeason);
                              $strTypeName="league";
                            break;
                            case 2:
                              $ItemName=$games->GetActualClubName($write_sub['id_item'],ActSeason);
                              $strTypeName="club";
                            break;
                            case 3:
                              $ItemName=$con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$write_sub['id_item']);
                              $strTypeName="player";
                            break;
                            }
                            echo '<td>'.$strTypeName.'</td>';
                            $strURL=get_url_text($ItemName,$write_sub['id_item']);
                            echo '<td><a class="bold" href="/'.$strTypeName.'/'.$strURL.'">'.$ItemName.'</a></td>';
                    
                            $strAdsName=$con->GetSQLSingleResult("SELECT name as item FROM sponsorship_ads WHERE id=".$write_sub['id_ads']);
                            echo '<td>'.$strAdsName.'</td>';
                            echo '<td valign="top" class="">'.number_format($write_sub['price'], 2, ',', ' ').' '.$strCurrencyName.'</td>';
                            echo '<td valign="top" class="">'.$write_sub['duration_months'].'</td>';
                            $strTotalFee=$strTotalFee+($write_sub['price']*$write_sub['duration_months']);
                            echo '<td valign="top" class="bold">'.number_format(($write_sub['price']*$write_sub['duration_months']), 2, ',', ' ').' '.$strCurrencyName.'</td>';
                            echo '<td class="link"><span class="link"><a href="/sponsorship-item/'.get_url_text($ItemName,$write_sub['id']).'">More details</a></span></td>';
                            echo '</tr>';    

                        }
                        
                        echo '<tr class="dark">';
                          echo '<td colspan="5">&nbsp;</td>';
                          echo '<td valign="top" class="bold" style="color:red">'.number_format(($write['price_total']), 2, ',', ' ').' '.$strCurrencyName.'</td>';
                          echo '<td class="link">'.$strPayNow.'</td>';
                        echo '</tr>';    
                        echo '</table>';
                      }

                    echo '</td>';
                  echo '</tr>';
              }
            echo '</table>';
          }else{
            echo '<p>No payments found.</p>'; 
          }
        
                  
        
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