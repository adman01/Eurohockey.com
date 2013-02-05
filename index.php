<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");
$strHeaderKeywords='';
$strHeaderDescription=strProjectName." - all about European ice hockey";
$boolIndexSlider=true;
require_once("inc/head.inc");
echo $head->setTitle(strProjectName." - all about European ice hockey");
?>
<link rel="stylesheet" type="text/css" media="all" href="/inc/jScrollPane/jScrollPane.css" />
<script type="text/javascript" src="/inc/jScrollPane/jquery.mousewheel.js"></script>
<script type="text/javascript" src="/inc/jScrollPane/jScrollPane.js"></script>
<script type="text/javascript">
			
			$(function()
			{
				// this initialises the demo scollpanes on the page.
				$('.news_panel').jScrollPane({showArrows:true, scrollbarWidth: 22, arrowSize: 14,scrollbarMargin:10});
			});
</script>			
<?php
echo $head->setEndHead();
?>
<body>

<div id="ads_top"><?php show_ads("top_main"); ?></div>
<div id="layout" class="longer">
  
  <div id="logo"><?php require_once("inc/logo.inc"); ?></div>
  <div id="menu"><?php require_once("inc/menu.inc"); ?></div>
  <div id="main">
  <?php 
    echo ' 
    <div id="infobar">'.get_static_text(40).'</div>
    <div class="space infobar">&nbsp;</div>
    ';
  ?>    
  
      <div id="text">
  
         <!-- main text -->
         
         <div id="top_story">
         
         <? 
         $query="SELECT * FROM articles WHERE is_top=1";
         if ($con->GetQueryNum($query)>0){
 	          $result = $con->SelectQuery($query);
            $write = $result->fetch_array();
	             
	             echo '<div class="toleft">';
               if (!empty($write['id_image'])){
	               $strPhotoPath=get_photo_name($write['id_image']);
	               if (file_exists(PhotoFolder."/".$strPhotoPath)) {echo '<img src="/image/190-190-1-'.str_replace("-","_",$strPhotoPath).'" alt="'.$write['header'].'" />';}
                    else{echo '<img src="/img/default_article.jpg"  width="190" alt="'.$write['header'].'" />';}
	              }else{echo '<img src="/img/default_article.jpg" width="190" alt="'.$write['header'].'" />';}
	              echo '</div>';
	             
	             echo '<div class="toright">';
	               echo '<h1><a href="/article/'.get_url_text($write['header'],$write['id']).'" title="Read article: '.$write['header'].'">'.$write['header'].'</a></h1>';
	               echo '<div id="author">';
                        echo date("d M Y",strtotime($write['date_time'])).' | ';
                        echo $write['author'];
                echo '</div>';
                echo '<p>'.$write['perex'].'<span>&nbsp;<a href="/article/'.get_url_text($write['header'],$write['id']).'" title="Read article: '.$write['header'].'">Read&nbsp;more&raquo;</a></span></p>';
               echo '</div>';
         }
         ?>
         <div class="clear">&nbsp;</div>
         </div>
         <div class="space">&nbsp;</div>
         
         <div id="box_recent_articles_first">
              <?php
              $query="SELECT * FROM articles WHERE is_top=0 AND show_item=1 AND pub_date<=NOW() AND pub_date<=NOW() AND (expire_date>=NOW() OR expire_date='0000-00-00 00:00:00') order by date_time DESC  LIMIT 3";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  $result = $con->SelectQuery($query);
                  echo '<ul class="article_list">';
                  while($write = $result->fetch_array()){
                      echo '<li>
                              <div class="toleft">';
                                 if (!empty($write['id_image'])){
                	               $strPhotoPath=get_photo_name($write['id_image']);
                	               if (file_exists(PhotoFolder."/".$strPhotoPath)) {echo '<img src="/image/90-90-1-'.str_replace("-","_",$strPhotoPath).'" alt="'.$write['header'].'" />';}
                                    else{echo '<img src="/img/default_article.jpg"  width="90" alt="'.$write['header'].'" />';}
                	              }else{echo '<img src="/img/default_article.jpg" width="90" alt="'.$write['header'].'" />';}
                              echo '
              	              </div>
                              <div class="toright">
                                <h2><a href="/article/'.get_url_text($write['header'],$write['id']).'" title="Read article: '.$write['header'].'">'.$write['header'].'</a></h2>                        
                                <p><span class="date">'.date("d M Y",strtotime($write['date_time'])).' |</span> '.$write['perex'].'<span>&nbsp;<a href="/article/'.get_url_text($write['header'],$write['id']).'" title="Read article: '.$write['header'].'">Read&nbsp;more&raquo;</a></span></p>
                              </div>
                              <div class="clear"></div>
                           </li>';
                  }
                  echo '</ul>';
              }
              ?>
              
            </div>
         
          <div class="text_col_left">
            
            <div class="header short_news"><span>Short news</span><span class="toright"><a href="/news.html" title="Show all news">Show all news&raquo;</a></span></div>
            <div id="news">
              <div class="news_panel">
              <?php
              $query="SELECT * FROM news WHERE show_item=1 AND pub_date<=NOW() AND pub_date<=NOW() AND (expire_date>=NOW() OR expire_date='0000-00-00 00:00:00') order by date_time DESC  LIMIT 8";
              //echo $query;
              $intCountNews=$con->GetQueryNum($query);
              $counter=1; 
              if ($intCountNews>0){
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                      echo '
                      <p class="header">'.$write['header'].'</p>
                      ';
                      echo '<p>'.$write['text'].'</p>';
                      if ($write['is_link']==1) echo '<p><span><a href="http://'.$write['link'].'" onclick="return !window.open(this.href);">Related link&raquo;</a></span></p>';
                      echo '<span class="date small">('.date("d M Y",strtotime($write['date_time'])).')</span>';
                      echo '<div class="line">&nbsp;</div>'; 
                    $counter++;
                  }
              }
              ?>
              </div>
            </div>     
            
            <div class="space">&nbsp;</div>
            
            <div class="header recent_articles"><span>Recent articles</span><span class="toright"><a href="/articles.html" title="Show all articles">Show all articles&raquo;</a></span></div>
            <div class="box_recent_articles">
              <?php
              $query="SELECT * FROM articles WHERE is_top=0 AND show_item=1 AND pub_date<=NOW() AND pub_date<=NOW() AND (expire_date>=NOW() OR expire_date='0000-00-00 00:00:00') order by date_time DESC  LIMIT 3,10";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '<ul>';
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    echo '<a href="/article/'.get_url_text($write['header'],$write['id']).'" title="Read article: '.$write['header'].'"><li>
                            <div class="toleft">';
                                 if (!empty($write['id_image'])){
                	               $strPhotoPath=get_photo_name($write['id_image']);
                	               if (file_exists(PhotoFolder."/".$strPhotoPath)) {echo '<img src="/image/50-30-1-'.str_replace("-","_",$strPhotoPath).'" alt="'.$write['header'].'" />';}
                                    else{echo '<img src="/img/default_article.jpg"  width="30" alt="'.$write['header'].'" />';}
                	              }else{echo '<img src="/img/default_article.jpg" width="30" alt="'.$write['header'].'" />';}
                            echo '</div>' ;                  
                      echo '<div class="toright">
                            <h2>'.$write['header'].'</h2>
                           </div>';
                          
                    echo '<div class="clear"></div></li></a>';
                  }
                  echo '</ul>';
              }
              ?>
              
            </div>
             
          </div>
          
          <div class="text_col_right features">
            <?php require_once("inc/text_col_right_features.inc"); ?>
          </div>
          
        <div class="space">&nbsp;</div>
        <?php require_once("inc/text_bottom_default.inc"); ?>
         
         <!-- main text end -->
      </div>
      
      <div id="col_right">
        <?php require_once("inc/col_right_default.inc"); ?>
      </div>
      
      <div class="clear">&nbsp;</div>
      <div id="text_space">&nbsp;</div>
      
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
