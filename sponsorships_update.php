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
  $intAdsID=$write['id_ads'];


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
  if ($write['id_status']<>1)  header("Location: /sponsorship.html");
  

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
  <script type="text/javascript" src="/inc/jquery/jquery.validate.min.js"></script>
  <script type="text/javascript">
  $(document).ready(function(){
 
    $("#form1").validate({});

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
          
          echo '<h3>Update sponsorship for '.$ItemName.' ('.$strTypeName.')</h3>';

          echo '<form id="form1" action="/sponsorship-action/update-sponsorship.html" method="post">';
          echo '<fieldset>'; 
          echo '<legend>Update sponsorship</b></legend>'; 
          echo '<div class="space">&nbsp;</div>';
          
           echo '
         <div class="input_holder">
              <label for="duration">Sponsorship duration *:</label>
              <select id="duration" name="duration" class="required">
          ';  
            echo '<option value="">choose duration</option>';
            echo '<option value="1"'.write_select($intMonths,1).'>1 month</option>';
                echo '<option value="2"'.write_select($intMonths,2).'>2 months</option>';
                echo '<option value="3"'.write_select($intMonths,3).'>3 months</option>';
                echo '<option value="4"'.write_select($intMonths,4).'>4 months (15% discount)</option>';
                echo '<option value="6"'.write_select($intMonths,6).'>6 months (20% discount)</option>';
                echo '<option value="12"'.write_select($intMonths,12).'>1 year (30% discount)</option>';
                echo '<option value="24"'.write_select($intMonths,24).'>2 years (40% discount)</option>';
                echo '<option value="60"'.write_select($intMonths,60).'>5 years (50% discount)</option>';
                echo '<option value="120"'.write_select($intMonths,120).'>10 years (60% discount)</option>';
                  
                
          echo '
              </select>
              <div class="clear"></div>
        </div>';

          $query="SELECT * from sponsorship_ads  WHERE is_approved=2 AND id_customer=".$intCustomerID;
          if ($con->GetQueryNum($query)>0){
             echo '<div class="input_holder">
              <label for="existed_ads">Approved ads:</label>
              <select id="existed_ads" name="id_ads" class="required">
              ';  
                echo '<option value="">select ads</option>';
                $result = $con->SelectQuery($query);
                while($write = $result->fetch_array()){
                  echo '<option value="'.$write['id'].'"'.write_select($intAdsID,$write['id']).'>'.$write['name'].'</option>';
                }
              echo '
              </select>
              <div class="clear"></div>
              </div>
            ';
          }
          echo '

               <div class="space"></div>
         <div class="line"></div>
         <div class="space"></div>

         



          <div class="inputSubmit center">
            <input type="hidden" name="action" value="update_sponsorship" />
            <input type="hidden" name="id" value="'.$intID.'" />
            <input type="submit" name="submit" value="Update sponsorship" class="submit"/>
          </div>
  
        ';
         echo '</fieldset>'; 
         echo '</form>';

         
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