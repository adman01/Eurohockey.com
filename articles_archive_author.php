<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");
$strHeaderKeywords='atricles, archive';
$strHeaderDescription='Articles archive';
require_once("inc/head.inc");
echo $head->setTitle($strHeaderDescription." - ".strProjectName);
echo $head->setEndHead();

$filter=$_GET['filter'];
$filter=$input->valid_text($filter,true,true);
?>
<body>

<div id="ads_top"><?php show_ads("top_main"); ?></div>
<div id="layout" class="longer">
  
  <div id="logo"><?php require_once("inc/logo.inc"); ?></div>
  <div id="menu"><?php require_once("inc/menu.inc"); ?></div>
  <div id="main">
      
      <div id="text">
         <!-- main text -->
         
         <h1>Articles archive</h1>
         
         <div id="archive">
         
        <form action="/articles.html">
        <div class="header box_normal">&nbsp;</div>
        <div class="box normal">
                <span class="search_input">
                <b>Filter:</b>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="text" name="filter" id="search_text" maxlength="255" value="<?php echo $filter; ?>" />
                </span>
                <span class="transfer_submit"><input type="submit" class="submit" value="" /></span>
              <div class="clear">&nbsp;</div>
         </div>                                                                      
        </form>
         
         <?php
              if (!empty($filter)){ $strWhere=" AND (author LIKE '%".$filter."%')";}
              $query="SELECT id,header,perex,id_image,date_time FROM articles WHERE show_item=1 ".$strWhere." AND pub_date<=NOW() AND pub_date<=NOW() AND (expire_date>=NOW() OR expire_date='0000-00-00 00:00:00') order by date_time DESC";
              $listovani = new listing($con,"?filter=".$_GET['filter']."&amp;filter2=".$_GET['filter2']."&amp;",15,$query,3,"",$_GET['list_number']);
              $query=$listovani->updateQuery();
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    echo '<h4><a href="/article/'.get_url_text($write['header'],$write['id']).'" title="Read article: '.$write['header'].'">'.$write['header'].'</a></h4>';
                    
                    echo '<a href="/article/'.get_url_text($write['header'],$write['id']).'" title="Read article: '.$write['header'].'">';
                    if (!empty($write['id_image'])){
                       $strPhotoPath=get_photo_name($write['id_image']);
	                     if (file_exists(PhotoFolder."/".$strPhotoPath)) {echo '<img src="/image/120-120-1-'.str_replace("-","_",$strPhotoPath).'" alt="'.$write['header'].'" />';}
	                       else{echo '<img src="/img/default_article.jpg"  width="120" alt="'.$write['header'].'" />';}
	                  }else{echo '<img src="/img/default_article.jpg" width="120" alt="'.$write['header'].'" />';}
	                  echo '</a>';
	                     
                    echo '<p class="floated"><span style="color:gray">('.date("d M Y, H:i",strtotime($write['date_time'])).')</span> | '.$write['perex'].'&nbsp;<span><a href="/article/'.get_url_text($write['header'],$write['id']).'" title="Read article: '.$write['header'].'">Read&nbsp;more&raquo;</a></span></p>';
                    echo '<div class="clear">&nbsp;</div>';
                    echo '<div class="space">&nbsp;</div>';
                    echo '<div class="line">&nbsp;</div>';
                    
                  }
                //listovani
                $listovani->show_list();
              }
              ?>
            </div>
         
         
         
         <!-- main text end -->
      </div>
      
      <div id="col_right">
        <!--column right -->
        
        <?php require_once("inc/col_right_default.inc"); ?>
        
        <!-- column right end -->
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