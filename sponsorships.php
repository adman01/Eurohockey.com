<?php
require_once("inc/global.inc");
$strUserArray=check_login(0);
if ($web_users->is_loged()){
  $web_users->checkRequiredData();
}
require_once("inc/ads.inc");

$id=$_GET['id'];
$id=$input->valid_text($id,true,true);
$id=get_ID_from_url($id); 

$id_type=$_GET['id_type'];
$id_type=$input->check_number($id_type);

if (empty($id_type) and (empty($id) or $id==-123)){

  if (!empty($_SESSION['sponsorship_id']) and !empty($_SESSION['sponsorship_type'])){
  $id= $_SESSION['sponsorship_id'];
  $id_type= $_SESSION['sponsorship_type'];
  }
}

if (!empty($id_type) and !empty($id) and $id<>-123){

  
  $_SESSION['sponsorship_id']=$id;
  $_SESSION['sponsorship_type']=$id_type;

  $isNotInSponsorShip=$con->GetSQLSingleResult("SELECT count(*) as item FROM sponsorship WHERE id_item=".$id." AND id_type=".$id_type." AND ( (date_expire>=NOW() AND id_status=2) OR (id_status=1))");
  //echo $isNotInSponsorShip;
  if (empty($isNotInSponsorShip)){
    switch ($id_type) {
      case 1:
        $ItemName=$games->GetActualLeagueName($id,ActSeason);
        $strTypeName="league";
        break;
      case 2:
        $ItemName=$games->GetActualClubName($id,ActSeason);
        $strTypeName="club";
        break;
      case 3:
        $ItemName=$con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$id);
        $strTypeName="player";
        break;
    }
    $intIdCategory=$con->GetSQLSingleResult("SELECT id_category as item FROM sponsorship_items WHERE id_item=".$id." AND id_type=".$id_type);
    if (empty($intIdCategory)){$intIdCategory=6;}
    $strCategoryName=$con->GetSQLSingleResult("SELECT name_".$id_type." as item FROM  sponsorship_category WHERE id=".$intIdCategory);

    
  }

   
}


$strHeader="Sponsorship";

$strHeaderKeywords=$strHeader;
$strHeaderDescription=$strHeader;



require_once("inc/head.inc");
echo $head->setTitle($strHeader." - ".strProjectName);

?>
<script type="text/javascript" src="/inc/jquery/jquery.validate.min.js"></script>
<link rel="stylesheet" media="screen,projection" type="text/css" href="/inc/uploadify/uploadify.css" /> 
<script type="text/javascript" src="/inc/uploadify/swfobject.js"></script>
<script type="text/javascript" src="/inc/uploadify/jquery.uploadify.v2.1.0.min.js"></script>
  <script type="text/javascript">
  $(document).ready(function(){
  
    <?php 
    $strUploadifyDataType="sponsorship"; 
    $strUploadifyDataExt="jpg";
    ?>
  
      $("#fileInput1").uploadify({
    'uploader'       : '/inc/uploadify/uploadify.swf',
    'script'         : '/inc/uploadify/uploadify.php?sesid=<?php echo md5($strUserArray['id']);?>_datetype_<?php echo $strUploadifyDataType; ?>_datetype_<?php echo $strUploadifyDataExt; ?>',
    'cancelImg'      : '/inc/uploadify/cancel.png',
    'folder'         : '/inc/uploadify/uploads_temp',
    'buttonText'     : 'SELECT IMAGE',
    'auto'           : true,
    'multi'          : false,
    'fileExt'        : '*.jpg',
    'fileDesc'       : 'jpeg images',
    
    onAllComplete: function() {
       $("#image_box").show();
       $("#id_image").val(1);
    }
   });
  
      
   $("#form1").validate({
        

   });

   //ads_name

  });

  function delete_sponsorship(id,name) {
  if (confirm ("Delete sponsorship '"+name+"' ?")) 
        document.location=("/sponsorship-action/delete-sponsorship.html?id="+id);
  }
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
         if ($web_users->is_loged()){

          $intCustomerID=$strUserArray['id'];
          
          require_once("inc/sponsorship_menu.inc");

          switch ($_GET['message']){
            case 1:
              echo '<p class="message center"><b>Error:</b> You have not entered all the required fields.</p>';
            break;

            case 2:
              echo '<p class="message center info">Your sponsorship has been added.</p>';
            break;
            case 3:
              echo '<p class="message center info">Your sponsorship has been deleted.</p>';
            break;
            case 4:
              echo '<p class="message center info">Your sponsorship has been updated.</p>';
            break;
            case 5:
              echo '<p class="message center"><b>Error:</b> AD size must be 600x125 or lower.</p>';
            break;
          }
          
        

         if (!empty($ItemName)){

          echo '<div class="space">&nbsp;</div>';
          echo '<form id="form1" action="/sponsorship-action/login.html" method="post">';
          echo '<fieldset>'; 
          echo '<legend>Create new sponsorship</b></legend>'; 
          echo '<div class="toright bold"><a href="/sponsorship-action/cancel-order.html">Cancel order</a></div>';
          
          echo '<h3>Selected '.$strTypeName.' <b>'.$ItemName.'</b></h3>'; 
          echo '<p><b>'.$ItemName.'</b> is in <b>'.$strCategoryName.'</b>.</p>'; 
          
          echo '<div class="line"></div>';

          echo '<h3>Price list for <b>'.$strCategoryName.'</b></h3>'; 
          echo '<p class="small">(prices per calendar month)</p>';
          $query="SELECT sponsorship_prices.*,sponsorship_currency_list.name from sponsorship_prices INNER JOIN  sponsorship_currency_list ON sponsorship_prices.id_currency=sponsorship_currency_list.id WHERE id_category=".$intIdCategory;
          if ($con->GetQueryNum($query)>0){
            echo '<table class="nice">';
              echo '<tr>';
                echo '<thead>';
                $result = $con->SelectQuery($query);
                while($write = $result->fetch_array()){
                  echo '<td>'.$write['name'].'</td>';
                }
                echo '</thead>';
              echo '</tr>';
              echo '<tr>';
              $result = $con->SelectQuery($query);
              while($write = $result->fetch_array()){
                  echo '<td>'.number_format($write['price_'.$id_type], 2, ',', ' ').''.$write['name'].'</td>';
              }
              echo '</tr>';
              echo '<tr>';
              $result = $con->SelectQuery($query);
              while($write = $result->fetch_array()){
                  echo '<td class="center"><input type="radio" class="required" name="price" value="'.$write['id'].'" /></td>';
              }
              echo '</tr>';
            echo '</table>';

            
         }

         echo '
         <div class="input_holder">
              <label for="duration">Sponsorship duration *:</label>
              <select id="duration" name="duration" class="required">
          ';  
            echo '<option value="">choose duration</option>';
                echo '<option value="1">1 month</option>';
                echo '<option value="2">2 months</option>';
                echo '<option value="3">3 months</option>';
                echo '<option value="4">4 months (15% discount)</option>';
                echo '<option value="6">6 months (20% discount)</option>';
                echo '<option value="12">1 year (30% discount)</option>';
                echo '<option value="24">2 years (40% discount)</option>';
                echo '<option value="60">5 years (50% discount)</option>';
                echo '<option value="120">10 years (60% discount)</option>';
          echo '
              </select>
              <div class="clear"></div>
        </div>

        ';

        echo '<h3>Group discount</h3>';
            echo '<ul>';
            echo '<li>sponsor <b>3</b> players: <b>15% discount</b> from total price</li>';
            echo '<li>sponsor <b>5</b> players: <b>20% discount</b> from total price</li>';
            echo '<li>sponsor <b>10</b> players: <b>30% discount</b> from total price</li>';
            echo '<li>sponsor <b>25</b> players: <b>35% discount</b> from total price</li>';
            echo '<li>sponsor <b>50</b> players: <b>40% discount</b> from total price</li>';
            echo '</ul>';

        echo '<div class="line"></div>';
        echo '<h3>Select existing ad or create new one</h3>'; 

          echo '<p class="small bold">Select existing ad:</p>'; 
          $query="SELECT * from sponsorship_ads  WHERE id_customer=".$intCustomerID;
          if ($con->GetQueryNum($query)>0){
             echo '<div class="input_holder">
              <label for="existed_ads">Existing ads:</label>
              <select id="existed_ads" name="id_ads">
              ';  
                echo '<option value="">select ads</option>';
                $result = $con->SelectQuery($query);
                while($write = $result->fetch_array()){
                  echo '<option value="'.$write['id'].'">'.$write['name'].'</option>';
                }
              echo '
              </select>
              <div class="clear"></div>
              </div>';

          }else{
            echo '<p class="center">Currently, you have no advertising. Add new advertising below.</p>'; 
          }

          echo '
            <div style="width:450px; margin:auto">
            <span class="hrBehind"></span>
            <span class="hrBehind-or">or</span>
            <span class="hrBehind"></span>
            <div class="clear"></div>
            </div>
          ';

        echo '
          <p class="small bold">Create a new ad:</p>

          <div class="input_holder">
              <label for="ads_name">Ad name *:</label>
              <input type="text" class="" name="ads_name" id="ads_url" value="" size="50" maxlength="255" />
              <div class="clear"></div>
          </div>

          <div class="input_holder">
              <label for="ads_url">Ad - URL :</label>
              <input type="text" class="" name="ads_url" id="ads_url" value="" size="50" maxlength="255" />
              <div class="clear"></div>
          </div>

          <div class="space"></div>
          <div class="line"></div>
          <div class="space"></div>          

           <div class="input_holder">
              <label for="ads_html_text">Plain text:</label>
              <input type="text" class="" name="ads_html_text" id="ads_html_text" value="" size="50" maxlength="50" />
              <div class="clear"></div>
            </div>
          
          <p class="small bold" style="padding-left:50px">OR</p>
          <div class="input_holder">
              <label for="ads_banner">Banner - image:</label>
              <input id="fileInput1" name="fileInput1" type="file" />
              ';
              if ($_GET['id_image']==1){
                  $strImageBox="";
                  $strImageBoxValue=1;
              }else{
                  $strImageBox="display:none";
                  $strImageBoxValue=0;
              }
                echo '<div class="input_upload_info" id="image_box" style="'.$strImageBox.'">Banner is uploaded <a onclick="return !window.open(this.href);" href="/inc/uploadify/uploads_temp/'.$strUploadifyDataType.'_'.md5($strUserArray['id']).'.'.$strUploadifyDataExt.'">show banner</a></div>';
                echo '<input type="hidden" name="id_image" id="id_image" value="'.$strImageBoxValue.'" />';

              
              echo '
  
  
              <div class="clear"></div>
              <p class="small">Maximum width: 600px, maximum height: 125px. Please note that your ad has to be confirmed by the administrator before it is shown on the site."<p>
          </div>
          
          

         <div class="space"></div>
         <div class="line"></div>
         <div class="space"></div>

         



          <div class="inputSubmit center">
            <input type="hidden" name="action" value="add_sponsorship" />
            <input type="submit" name="submit" value="Create sponsorship" class="submit"/>
          </div>
  
        ';
         echo '</fieldset>'; 
         echo '</form>';
        }
        

        
           echo '<h3>My sponsorships</h3>';  

            $query="SELECT * from sponsorship WHERE id_customer=".$intCustomerID. " ORDER BY date_expire DESC";
          if ($con->GetQueryNum($query)>0){
            echo '<table class="basic">';
              echo '<tr>';
                echo '<thead>';
                  echo '<th>Type</th>';
                  echo '<th>Item name</th>';
                  echo '<th>Expire</th>';
                  echo '<th>Ads name</th>';
                  echo '<th>Status</th>';
                  echo '<th></th>';
                echo '</thead>';
              echo '</tr>';
              $counter=0;
              $result = $con->SelectQuery($query);
              while($write = $result->fetch_array()){

                  $counter++;
                  if ($counter%2) $style=""; else $style="dark";
                  echo '<tr class="'.$style.'">';
                  
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
                  echo '<td>'.$strTypeName.'</td>';
                  $strURL=get_url_text($ItemName,$write['id_item']);
                  echo '<td><a class="bold" href="/'.$strTypeName.'/'.$strURL.'">'.$ItemName.'</a></td>';
                  if ($write['id_status']==2){
                    $strDateExired=date("d M Y",strtotime($write['date_expire']));
                  }else{
                    $strDateExired="-";
                  }
                  $strAdsName=$con->GetSQLSingleResult("SELECT name as item FROM sponsorship_ads WHERE id=".$write['id_ads']);
                  echo '<td>'.$strDateExired.'</td>';
                  echo '<td>'.$strAdsName.'</td>';
                  $strStatus=$con->GetSQLSingleResult("SELECT name as item FROM sponsorship_status_list WHERE id=".$write['id_status']);
                  if ($write['id_status']==2 AND strtotime($write["date_expire"])<date("U")){
                   $strStatus='<span style="color:red;">expired</span>'; 
                  }
                  echo '<td>'.$strStatus.'</td>';
                  echo '<td class="link">
                  <span class="link"><a href="/sponsorship-item/'.get_url_text($ItemName,$write['id']).'">Details</a></span>
                  &nbsp;|&nbsp;
                  ';
                  if ($write['id_status']==1){
                  echo '
                  <span class="link"><a href="/sponsorship-update/'.get_url_text($ItemName,$write['id']).'">Update</a></span>
                  &nbsp;|&nbsp;
                  ';
                  }
                  echo '<span class="link"><a onclick="return !window.open(this.href);" href="/'.$strTypeName.'/'.$strURL.'?preview=1&id_ads='.$write['id_ads'].'">Preview</a></span>
                  ';
                  if ($write['id_status']<>2)echo '<span class="link">&nbsp;|&nbsp;&nbsp;<a href="javascript:delete_sponsorship(\''.$write['id'].'\',\''.$ItemName.'\')" title="Delete item">X</a></span>';
                  echo '</td>';

                echo '</tr>';    
              }
              
              
            echo '</table>';
         }else{
            echo '<p>Currently, you have no sponsorships. Visit a player/league/club page and choose some.</p>'; 
         }


           


        }else{
          echo '<p class="message"><a href="/user-login.html">Registration</a> is required for sponsorship actions. If you have already registered, please <a href="/user-login.html">log in</a>.</p>';
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