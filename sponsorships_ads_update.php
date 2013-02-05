<?php
require_once("inc/global.inc");
$strUserArray=check_login(1);
$intCustomerID=$strUserArray['id'];
$files = new files();
require_once("inc/ads.inc");

$strHeader="Sponsorship";

$strHeaderKeywords=$strHeader;
$strHeaderDescription=$strHeader;


$id=$_GET['id'];
$id=$input->valid_text($id,true,true);
$intID=get_ID_from_url($id);

$query="SELECT * from sponsorship_ads WHERE id_customer=".$intCustomerID." AND  id=".$intID;
//echo $query;
if ($con->GetQueryNum($query)>0){
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  
  $id_type=$write['id_type'];
  $name=$write['name'];
  $url=$write['url'];
  $html_text=$write['html_text'];
  $id_image=$write['id_image'];
  $is_approved=$write['is_approved'];
  
}else{
  header("Location: /text/404-error.html");
}



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


     $('.type').change(function () {
      intIDtype=$(this).val();
      if (intIDtype==1){
        $('#box_text').show();  
        $('#box_image').hide(); 
        $('#html_text').addClass("required"); 
        
        
      }else{
        $('#box_text').hide();  
        $('#box_image').show(); 
        $('#html_text').removeClass("required");  
      }
      
   
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

          echo '<h3>Update AD</h3>';

          if ($is_approved<>1){
            echo '<p class="message center info">This ad is already approved. If you edit the ad, you will have to wait for approval.</p>';
          }

           switch ($_GET['message']){
            case 1:
              echo '<p class="message center info">AD  size must be 600x125 or lower.</p>';
            break;
             
          }
          

          echo '<div class="space">&nbsp;</div>';
          echo '<form id="form1" action="/sponsorship-action/update-ads.html" method="post">';
          echo '<fieldset>'; 
          echo '<legend>Update AD</b></legend>

          <div class="space">&nbsp;</div>
          <div class="input_holder">
              <label for="ads_name">Name *:</label>
              <input type="text" class="required" name="ads_name" id="ads_url" value="'.$name.'" size="50" maxlength="255" />
              <div class="clear"></div>
          </div>

          <div class="input_holder">
              <label for="ads_url">URL :</label>
              <input type="text" class="" name="ads_url" id="ads_url" value="'.$url.'" size="50" maxlength="255" />
              <div class="clear"></div>
          </div>
          ';
          ?>

          <div class="input_holder">
          <label for="ads_type">Type</label>
          <select name="ads_id_type" class="type">
          <?php
          $query_sub="SELECT * FROM sponsorship_ads_type_list ORDER by id";
          $result_sub = $con->SelectQuery($query_sub);
          while($write_sub = $result_sub->fetch_array()){
            echo '<option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$id_type).'>'.$write_sub['name'].'</option>';
          }
          ?>
          </select>
          <div class="clear"></div>
          </div>

          <div id="box_text" <?php if ($id_type<>1) echo 'style="display:none"'; ?>>

            <div class="input_holder">
              <label for="html_text">Text :</label>
              <input type="text" class="" name="html_text" id="html_text" value="<?php echo $html_text; ?>" size="50" maxlength="255" />
              <div class="clear"></div>
            </div>
          </div>

          <div id="box_image" <?php if ($id_type<>2) echo 'style="display:none"'; ?>>
    
          <div class="input_holder">
            <label>Banner:</label>

             
            <div style="float:left">
            <div id="image_box_1">
            <?php
            $FilePath=IncludeAdminPath.'/'.PhotoFolder.'/sponsorship/sponsorship_'.$intID.'.jpg';
            if ($files->checkFile($FilePath)){
              echo '<p><a class="ico-show" onclick="return !window.open(this.href);" href="/'.PhotoFolder.'/sponsorship/sponsorship_'.$intID.'.jpg">Show actual banner</a></p>';
              $id_image=1;
            }else{
              echo '<p>no banner found</p>';
              $id_image=0;  
            }
            ?>
            </div>
  
            <input id="fileInput1" name="fileInput1" type="file" />
            <?php
            if ($_GET['id_image']==1){
              $strImageBox="";
              $id_image=1;
            }else{
              $strImageBox="display:none";
              $id_image=0;
            }
            echo '<div id="image_box" style="'.$strImageBox.'">Banner is uploaded <a onclick="return !window.open(this.href);" href="/inc/uploadify/uploads_temp/'.$strUploadifyDataType.'_'.md5($strUserArray['id']).'.'.$strUploadifyDataExt.'">show banner</a></div>';
            echo '<input type="hidden" name="id_image" id="id_image" value="'.$id_image.'" />';
            ?>
          </div>
          </div>
          <div class="clear"></div>
          <div class="space"></div>
        </div>
  <?php
  echo '
          <div class="line"></div>
         <div class="inputSubmit center">
            <input type="hidden" name="id" value="'.$intID.'" />
            <input type="hidden" name="action" value="update-ads" />
            
            <input type="submit" name="submit" value="Update AD" class="submit"/>
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