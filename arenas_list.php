<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");
$strHeaderKeywords='arenas, list';
$strHeaderDescription='Arenas';
require_once("inc/head.inc");                                    
echo $head->setTitle($strHeaderDescription." - ".strProjectName);
echo $head->setEndHead();

$id_sort=$_GET['id_sort'];
$id_sort=$input->check_number($id_sort);
if (empty($id_sort)) $id_sort=1;
?>
<body>
<div id="ads_top"><?php show_ads("top_main"); ?></div>
<div id="layout" class="longer">
  
  <div id="logo"><?php require_once("inc/logo.inc"); ?></div>
  <div id="menu"><?php require_once("inc/menu.inc"); ?></div>
  <div id="main">
      
      <div id="text">
         <!-- main text -->
         
         <h1>Arenas</h1>
         
        <div class="header box_normal">&nbsp;</div>
        <div class="box normal" id="arena_search_form">
              
              <form action="" method="get">
              
              <div class="toleft">
                <div>
                <b>Search arena:</b> <input style="width:410px" class="arena" type="text" size="70" onclick="input_table_clean('arena_search')" name="arena_search" id="arena_search" maxlength="255" value="<?php if (!empty($_GET['arena_search'])) echo $_GET['arena_search']; else echo 'type arena name or some text'; ?>" />
                </div>
                <div class="space">&nbsp;</div>
                <div>
                 <?php
              $query="SELECT id,shortcut FROM countries ORDER BY name ASC";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    $countArenas=$con->GetSQLSingleResult("SELECT count(*) as item FROM arenas WHERE id_country=".$write['id']."");
                    if ($countArenas>0){
                    if ($countArenas<=1) $strArenaNum='arena'; else $strArenaNum='arenas';
                    $strCountryList.='<option value="'.$write['id'].'" '.write_select($write['id'],$_GET['id_country']).'>'.$games->GetActualCountryName($write['id'],ActSeasonWeb).' ('.$countArenas.' '.$strArenaNum.')</option>';
                    }
                  }
              }
             ?>
              <b>Search by country:</b> <select name="id_country">
                 <option value="">- Select country -</option>
	               <?php echo $strCountryList; ?>
              </select>
              &nbsp;&nbsp;<b>Sort by:</b> <select style="width:150px" name="id_sort">
                 <option value="1" <?php echo write_select($id_sort,1); ?>>name</option>
	               <option value="2" <?php echo write_select($id_sort,2); ?>>capatity</option>
              </select>
              
              </div>
                
              </div>
              
              
              <span class="arena_submit"><input type="submit" class="submit" style="margin-top:10px" value="" /></span>
              <div class="clear">&nbsp;</div>
              </form>
              
         </div>
         
         <div class="space">&nbsp;</div>
              
              <div class="header box_normal">&nbsp;</div>
              <div class="box normal">
                
                <div class="toleft"><b>TOP Arenas in:</b></div>
                <a href="/arenas.html?id_country=13" title="Arenas in Canada"><?php echo get_flag(strtolower("can"),26,19);?></a>
                <a href="/arenas.html?id_country=5" title="Arenas in Czech republic"><?php echo get_flag(strtolower("cze"),26,19);?></a>
                <a href="/arenas.html?id_country=3" title="Arenas in Finland"><?php echo get_flag(strtolower("fin"),26,19);?></a>
                <a href="/arenas.html?id_country=4" title="Arenas in Germany"><?php echo get_flag(strtolower("ger"),26,19);?></a>
                <a href="/arenas.html?id_country=7" title="Arenas in Russia"><?php echo get_flag(strtolower("rus"),26,19);?></a>
                <a href="/arenas.html?id_country=6" title="Arenas in Slovakia"><?php echo get_flag(strtolower("svk"),26,19);?></a>
                <a href="/arenas.html?id_country=2" title="Arenas in Sweden"><?php echo get_flag(strtolower("swe"),26,19);?></a>
                <a href="/arenas.html?id_country=1" title="Arenas in Switzerland"><?php echo get_flag(strtolower("sui"),26,19);?></a>
                <a href="/arenas.html?id_country=14" title="Arenas in USA"><?php echo get_flag(strtolower("usa"),26,19);?></a>
                <div class="clear">&nbsp;</div>
              </div>
         
          
          <?php
         
            //classic search
            if ((!empty($_GET['arena_search']) and $_GET['arena_search']!='type arena name or some text') or !empty($_GET['id_country'])){
              
              if ((!empty($_GET['arena_search']) and $_GET['arena_search']!='type arena name or some text')){
                $search=$_GET["arena_search"];
                $search=$input->valid_text($search,true,true);
                $strWhere.= " AND (name LIKE '%".$search."%' or also_known_as LIKE '%".$search."%' or telephone LIKE '%".$search."%' or fax LIKE '%".$search."%' or email LIKE '%".$search."%' or last_major_reconstruction LIKE '%".$search."%' or also_used_for LIKE '%".$search."%' or most_notable_games LIKE '%".$search."%' or link_1 LIKE '%".$search."%' or link_2 LIKE '%".$search."%' or link_3 LIKE '%".$search."%' or id_status_info LIKE '%".$search."%')";
                $strVariables.='arena_search='.$search.'&amp;';
              }
              
              if (!empty($_GET['id_country'])){
                $search_country=$_GET["id_country"];
                $search_country=$input->check_number($search_country);
                $strWhere.= " AND id_country=".$search_country;
                $strVariables.='id_country='.$search_country.'&amp;';
              }

          $strVariables.='list_number='.$_GET['list_number'].'&amp;id_sort='.$id_sort.'&amp;';
          
          switch ($id_sort){
            case 1:
              $strOrder=" important_arenas DESC, name ASC" ;
            break;
            case 2:
              $strOrder=" important_arenas DESC, 	capacity_overall DESC";
            break;
          }
          $query="SELECT * FROM arenas WHERE 1 ".$strWhere." ORDER BY".$strOrder;
          $listovani = new listing($con,"?".$strVariables."&amp;",15,$query,3,"",$_GET['list_number']);
          $query=$listovani->updateQuery();
          //echo $query; 
          if ($con->GetQueryNum($query)>0){
            if (!empty($search_country)) echo '<h2>Arenas in '.$games->GetActualCountryName($search_country,ActSeasonWeb).'</h2>';
            echo '<div id="arenas_list">';
            $counter=0;
            $result = $con->SelectQuery($query);
            while($write = $result->fetch_array())
	           {
	             $name=$write["name"];
	             $url='/arena/'.get_url_text($name,$write['id']);
	             $StrArenaAddress=$write["address"];
	             $StrArenaTelephone=$write["telephone"];
	             $StrArenaCapacity_overall=$write["capacity_overall"];
	             $StrArenaYear_built=$write["year_built"];
	             $StrArenaLink_1=$write["link_1"];
	             $StrGPS_location=$write["GPS_location"];
	             if ($counter==0) $BollImportant_arenas=$write["important_arenas"];
	             if ($BollImportant_arenas<>$write["important_arenas"] and $counter>0){
                  $BollImportant_arenas=$write["important_arenas"];
                  echo '<div style="background:#EAF3F8">&nbsp;</div>';
               }
                
	            echo '<h2><a href="'.$url.'" title="Show detail about arena: '.$name.'">'.$name.'</a></h2>';
	            
	            echo '<div class="photo">';
	            if (!empty($write['id_photo_folder'])){
	                  $id_image=$con->GetSQLSingleResult("SELECT id as item FROM photo WHERE id_photo_folder=".$write['id_photo_folder']." ORDER by id DESC");
                    if (!empty($id_image)){
	                     $strPhotoPath=get_photo_name($id_image);
	                     if (file_exists(PhotoFolder."/".$strPhotoPath)) {
	                       
                          echo '<img class="border" src="/image/138-97-1-'.str_replace("-","_",$strPhotoPath).'" alt="'.$name.' logo" />';
                         
                       }else {echo '<img class="" src="/img/default.jpg" width="138" alt="Logo not found" />';}
	                   }else {echo '<img  class="" src="/img/default.jpg" width="138" alt="Logo not found" />';}
	                  }else {echo '<img  class="" src="/img/default.jpg" width="138" alt="Logo not found" />';}
              echo '</div>';
              
              echo '<div class="info">';
                echo '<ul>';
                  if (!empty($StrArenaAddress))echo '<li><b>Adress:</b> '.nl2br($StrArenaAddress).'</li>';
                  if (!empty($StrArenaTelephone))echo '<li><b>Phone:</b> '.$StrArenaTelephone.'</li>';
                  if (!empty($StrArenaLink_1))echo '<li><b>WWW:</b> <a onclick="return !window.open(this.href);" href="'.$StrArenaLink_1.'">link</a></li>';
                  if (!empty($StrArenaCapacity_overall))echo '<li><b>Capacity:</b> '.$StrArenaCapacity_overall.'</li>';
                  if (!empty($StrArenaYear_built))echo '<li><b>Opened in:</b> '.$StrArenaYear_built.'</li>';
                echo '</ul>';
              echo '</div>';
              
              if (!empty($StrGPS_location))echo '<div class="map"><a href="'.$url.'" title="Show detail about arena: '.$name.'"><img src="/img/show_on_map.jpg" alt="'.$name.' map" /></a></div>';
              echo '<div class="clear">&nbsp;</div>';
              echo '<div class="line">&nbsp;</div>';
	            $counter++; 
	         }
	         echo '</div>';
    }else{
        echo '<p class="center bold">No arenas found for specified criteria.</p>';
    }
    echo '<table class="basic">';
      echo '<tfoot>';
	    //listovani
      $listovani->show_list();
      echo '</tfoot>';
    echo '</table>';
    }else{
      echo '<p class="center bold">Please select country first, or type searched arena.</p>';
    }
    ?>

              
                          
         
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