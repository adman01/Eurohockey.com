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
      
    $('.show_ads').click(function () {
      intID=$(this).attr('id');
      $('#'+intID+'_box').toggle();
  });

  });


   function delete_ads(id,name) {
  if (confirm ("Delete ads '"+name+"' ?")) 
        document.location=("/sponsorship-action/delete-ads.html?id="+id);
  }
  </script>

  <style>
    .images img {
      max-width: 550px;
    }
  </style>
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
              echo '<p class="message center info">Your AD has been updated.</p>';
            break;
             case 2:
              echo '<p class="message center info">Your AD has been removed.</p>';
            break;
          }
          

         echo '<h3>My ADS</h3>';

          $query="SELECT * from sponsorship_ads WHERE id_customer=".$intCustomerID. ' ORDER BY name ASC';
          if ($con->GetQueryNum($query)>0){
            echo '<table class="basic">';
              echo '<tr>';
                echo '<thead>';
                  echo '<th>Type</th>';
                  echo '<th>Name</th>';
                  echo '<th>Approved</th>';
                  echo '<th></th>';
                echo '</thead>';
              echo '</tr>';
              $counter=0;
              $result = $con->SelectQuery($query);
              while($write = $result->fetch_array()){

                  $counter++;
                  if ($counter%2) $style=""; else $style="dark";
                  switch ($write['id_type']) {
                    case 2:
                      $strType="Banner";
                      break;
                    case 1:
                      $strType="Text";
                      break;
                  }
                  $strStatus=$con->GetSQLSingleResult("SELECT name as item FROM sponsorship_approving_list WHERE id=".$write['is_approved']);
                  echo '<tr class="'.$style.'">'; 
                  echo '<td valign="top">'.$strType.'</td>';
                  echo '<td valign="top"><b>'.$write['name'].'</b>';
                  if (!empty($write['id_ads_update'])){
                    echo '<br /><small>request for change</small>';
                  }
                  echo '</td>';
                  echo '<td valign="top">'.$strStatus.'</td>';
                  echo '<td valign="top" class="link">
                    <span class="link"><a href="javascript:void(0);" class="show_ads" id="ads_'.$write['id'].'">Show ad</a></span>
                  ';
                  $intIsUpdated=$con->GetSQLSingleResult("SELECT count(*) as item FROM sponsorship_ads WHERE id_ads_update=".$write['id']);
                  if (empty($intIsUpdated)){
                  echo'&nbsp;|&nbsp;
                    <span class="link"><a href="/sponsorship-ads-update/'.get_url_text($write['name'],$write['id']).'" title="Update AD">Update</a></span>
                  ';

                  }
                  $intUsedAds=$con->GetSQLSingleResult("SELECT count(*) as item FROM sponsorship WHERE id_ads=".$write["id"]);
                  if (empty($intUsedAds)) echo '<span class="link">&nbsp;|&nbsp;&nbsp;<a href="javascript:delete_ads(\''.$write['id'].'\',\''.$write["name"].'\')" title="Delete item">X</a></span>';
                  echo '</td>';
                  echo '</tr>';
                  echo '<tr class="hidden" id="ads_'.$write['id'].'_box">'; 
                    echo '<td class="images" valign="top" colspan="5" style="padding-top:20px; padding-bottom:20px; padding-left:20px;">';
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
                    echo '</td>';
                  echo '</tr>';
              }
            echo '</table>';
          }else{
            echo '<p>No AD found.</p>'; 
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