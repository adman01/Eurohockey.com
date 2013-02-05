<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");

$id=$_GET['id'];
$id=$input->valid_text($id,true,true);
$id=get_ID_from_url($id); 

$passw=$_GET['passw'];
$passw=$input->valid_text($passw,true,true);
if ($passw==md5("eurohockey.com")) {$SQLshow="";} else{$SQLshow=" show_item=1 AND pub_date<=NOW() AND pub_date<=NOW() AND (expire_date>=NOW() OR expire_date='0000-00-00 00:00:00') AND ";}

if (!isset($id) or empty($id)){
  header("Location: /text/404-error.html");
}else{
  $query="SELECT * from articles WHERE ".$SQLshow." id=".$id."";
  //echo $query; 
  $result = $con->SelectQuery($query);
  if ($con->GetQueryNum($query)>0){
    $write = $result->fetch_array();
    $strHeader=$write['header'];
    $strPerex=$write['perex'];
    $strText=$write['text'];
    //$strText=$games->showRelatedAnchors($strText);
    $intIdImage=$write['id_image'];
    $strDate=$write['date_time'];
    $intIdUser=$write['id_user'];
    $strAuthor=$write['author'];
    
   }else{
    header("Location: /text/404-error.html");
  }
}

$strHeaderKeywords=$strHeader;
$strHeaderDescription=$strPerex;
require_once("inc/head.inc");
if (!empty($intIdImage)) {
  $strPhotoPath=get_photo_name($intIdImage);
	if (file_exists(PhotoFolder."/".$strPhotoPath)) echo '<link rel="image_src" href="/image/190-150-1-'.str_replace("-","_",$strPhotoPath).'" />';
}
echo $head->setTitle($strHeader." - ".strProjectName);
echo $head->setEndHead();
?>
<body>

<div id="ads_top"><?php show_ads("top_main"); ?></div>
<div id="layout" class="longer">
  
  <div id="logo"><?php require_once("inc/logo.inc"); ?></div>
  <div id="menu"><?php require_once("inc/menu.inc"); ?></div>
  <div id="main">
      
      <div id="text">
  
         <!-- main text -->
         <?php  
      
         echo '<div id="top_story">'   ;
         echo '<div class="toleft">';
               if (!empty($intIdImage)){
	               $strPhotoPath=get_photo_name($intIdImage);
	               if (file_exists(PhotoFolder."/".$strPhotoPath)) {echo '<img src="/image/190-190-1-'.str_replace("-","_",$strPhotoPath).'" alt="'.$strHeader.'" />';}
	                else{echo '<img src="/img/default_article.jpg"  width="190" alt="'.$write['header'].'" />';}
	              }else{echo '<img src="/img/default_article.jpg" width="190" alt="'.$write['header'].'" />';}
	              echo '</div>';
	             
	             echo '<div class="toright">';
	               echo '<h1>'.$strHeader.'</h1>';
	               echo '<div id="author">';
                        echo date("d M Y",strtotime($strDate)).' | ';
                        echo $strAuthor;
                echo '</div>';
                echo '<p>'.$strPerex.'</p>';
               echo '</div>';
               echo '<div class="clear">&nbsp;</div>';
         echo '</div>';
         
         echo '<div class="space">&nbsp;</div>';
         
         
         ?>
         
         <div id="text_col_left_main">
            
          <?php 
          echo '<div class="space">&nbsp;</div>';
          show_ads("ads_article_perex");
          echo '<div class="space">&nbsp;</div>';
          
          echo $strText; ?>
          
          <div class="service_box" style="margin-bottom:10px">
            <div class="toleft">
              <a onclick="return !window.open(this.href);" href="http://www.facebook.com/sharer.php?u=<?php echo curPageURL();?>" title="Add to Facebook"><img class="toleft" src="/img/ico/ico_facebook.png" alt="Ico Facebook" height="16" width="16" /></a>
              <a onclick="return !window.open(this.href);" href="http://del.icio.us/post?url=<?php echo curPageURL();?>&amp;title=<?php echo urlencode($strHeader);?>" title="Add to Del.icio.us"><img class="toleft" src="/img/ico/ico_del.png" alt="Ico Del.icio.us" height="16" width="16" /></a>
              <a onclick="return !window.open(this.href);" href="http://www.stumbleupon.com/submit?url=<?php echo curPageURL();?>&title=<?php echo urlencode($strHeader);?>" title="Add to Stumbleupon"><img class="toleft" src="/img/ico/ico_stumbleupon.png" alt="Ico Stumbleupon" height="16" width="16" /></a>
		          <a onclick="return !window.open(this.href);" href="http://twitter.com/home?status=<?php echo urlencode($strHeader);?>" title="Add to Twitter"><img class="toleft" src="/img/ico/ico_twitter.png" alt="Ico Twitter" height="16" width="16" /></a>
      		    <a onclick="return !window.open(this.href);" href="http://digg.com/submit?phase=2&amp;url=<?php echo curPageURL();?>&amp;title=<?php echo urlencode($strHeader);?>" title="Add to Digg it"><img class="toleft" src="/img/ico/ico_digg.png" alt="Ico Digg it" height="16" width="16" /></a>
      		  </div>
      		  <div class="toright">
              <a class="toright" href="javascript:void(0);" onclick="print();" title="Print"><img src="/img/ico/ico_print.png" alt="Print" height="16" width="16" /></a>
            </div>
		      </div>
		      
		      <g:plusone></g:plusone>
          
          <fb:like href="<?php echo curPageURL();?>" send="true" width="375" show_faces="true" colorscheme="light" font="arial" style="margin-top:10px"></fb:like>
        	<fb:comments href="<?php echo curPageURL();?>" num_posts="2" width="375"></fb:comments>
		      
		        
         </div>
         
         <div class="text_col_right">
            
            <?php
            $query_related="SELECT * FROM articles_items WHERE id_article=".$id." order by id_item_type ASC";
                  $intCountRelated=$con->GetQueryNum($query_related);
                  if ($intCountRelated>0){
                  $result_related = $con->SelectQuery($query_related);
                  $counter=0;
                    while($write_related = $result_related->fetch_array()){
                      
                      switch ($write_related['id_item_type']){
                         case 1:
                            $name=$games->GetActualCountryName($write_related['id_item'],ActSeasonWeb);
                            $name='<a href="/country/'.get_url_text($name,$write_related['id_item']).'" title="Show country '.$name.'">'.$name.'</a>';
                         break;
                         case 2:
                            $name=$games->GetActualLeagueName($write_related['id_item'],ActSeasonWeb);
                            $name='<a href="/league/'.get_url_text($name,$write_related['id_item']).'" title="Show league '.$name.'">'.$name.'</a>';
                         break;
                         case 3:
                            $name=$games->GetActualClubName($write_related['id_item'],ActSeasonWeb);
                            $name='<a href="/club/'.get_url_text($name,$write_related['id_item']).'" title="Show club '.$name.'">'.$name.'</a>';
                         break;
                         case 4:
                            $query_sub="SELECT name,surname FROM players WHERE id>0 AND id=".$write_related['id_item'];
                            $result_sub = $con->SelectQuery($query_sub);
                            $write_sub = $result_sub->fetch_array();
                            $name=$write_sub['surname'].' '.$write_sub['name'];
                            $name='<a href="/player/'.get_url_text($name,$write_related['id_item']).'" title="Show player '.$name.'">'.$name.'</a>';
                        break;
                        case 5:
                            $query_sub="SELECT id,id_club_home,id_club_visiting,date,home_score,visiting_score FROM games WHERE id>0 AND id=".$write_related['id_item'];
                            $result_sub = $con->SelectQuery($query_sub);
                            $write_sub = $result_sub->fetch_array();
                            $strHome=$games->GetActualClubName($write_sub['id_club_home'],ActSeasonWeb);
	                          $strVisiting=$games->GetActualClubName($write_sub['id_club_visiting'],ActSeasonWeb);
	                          $strScore=$games->GetScore($write_sub['id'],1);
	                          $name=''.$strHome.' - '.$strVisiting.'';
                            $name='<a href="/game/detail/'.get_url_text($name,$write_related['id_item']).'" title="Show game detail '.$name.'">'.$name.' ('.$strScore.')</a>';
                        break;
                      }
                      
                      $strRelated.='<li>'.$name.'</li>';
                      $counter++;
                    }
                }
            
            if (!empty($strRelated)){
            echo'
            <div class="header_cufon blue_200"><span class="header_text blue">Relevant links</span></div>
            <div class="box">
                <ul>
                '.$strRelated.'
                </ul>
            </div>
            <div class="space">&nbsp;</div>
            ';
            }
            ?>
            
            <div class="header_cufon blue_200"><span class="header_text blue">Recent news</span></div>
            <div class="box">
                
              <?php
              $query="SELECT id,header FROM articles WHERE id<>".$id." AND show_item=1 AND pub_date<=NOW() AND pub_date<=NOW() AND (expire_date>=NOW() OR expire_date='0000-00-00 00:00:00') order by date_time DESC  LIMIT 8";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '<ul>';
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    echo '<li>'; 
                      echo '<a href="/article/'.get_url_text($write['header'],$write['id']).'" title="Read article: '.$write['header'].'">'.$write['header'].'</a>';
                    echo '</li>';
                  }
                  echo '</ul>';
              }
              ?>
                
            </div>
            <div class="space">&nbsp;</div>
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