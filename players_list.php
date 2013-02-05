<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");
$strHeaderKeywords='players, list';
$strHeaderDescription='Players';
require_once("inc/head.inc");
echo $head->setTitle($strHeaderDescription." - ".strProjectName);
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
         
         <h1>Players</h1>
         
        <div class="header box_normal">&nbsp;</div>
        <div class="box normal" id="player_search_form">
              
              <div id="classic" style="<?php if ($_GET['advanced']==1) echo 'display:none;'; else echo ''; ?>">
              <form action="" method="get">
              
              <div class="toleft">
                <b>Search player:</b> <input class="player" type="text" size="40" onclick="input_table_clean('player_search')" name="player_search" id="player_search" maxlength="255" value="<?php if (!empty($_GET['player_search'])) echo $_GET['player_search']; else echo 'type player name or surname'; ?>" />
                <div class="advanced right"><a href="javascript:toggle('advanced'); toggle('classic');" title="Advanced player search">advanced player search</a></div>
              </div>
              <span class="players_submit toright"><input type="submit" class="submit" value="" /></span>
              <div class="clear">&nbsp;</div>
              <input type="hidden" name="write_player_search" value="1" />
              </form>
              </div>
              
              <div id="advanced" style="<?php if ($_GET['advanced']==1) echo ''; else echo 'display:none;'; ?>">
                <form action="" method="get">
                    <table>
                    <tr>
                      <td colspan="2">
                        <b>Firstname:</b> <input class="firstname" type="text" size="40" name="firstname" maxlength="255" value="<? echo $_GET['firstname']; ?>" />
                        &nbsp;<b>Lastname:</b> <input class="lastname" type="text" size="40" name="lastname" maxlength="255" value="<? echo $_GET['lastname']; ?>" />  
                      </td>
                    </tr>
                    <tr>
                      <td class="right" style="width:180px"><b>Date of birth (dd/mm/yyyy):</b></td><td><input class="day" type="text" size="5" name="day" maxlength="2" value="<? echo $_GET['day']; ?>" /><b>&nbsp;/&nbsp;</b><input class="month" type="text" size="5" name="month" maxlength="2" value="<? echo $_GET['month']; ?>" /><b>&nbsp;/&nbsp;</b><input class="year" type="text" size="5" name="year" maxlength="4" value="<? echo $_GET['year']; ?>" /></td>
                    </tr>
                    <tr>
                      <td class="right"><b>Height in cm (min - max):</b></td><td><input class="day" type="text" size="5" name="height_min" maxlength="3" value="<? echo $_GET['height_min']; ?>" /><b>&nbsp;-&nbsp;</b><input class="day" type="text" size="5" name="height_max" maxlength="3" value="<? echo $_GET['height_max']; ?>" /></td>
                    </tr>
                    <tr>
                      <td class="right"><b>Weight in kg (min - max):</b></td><td><input class="day" type="text" size="5" name="weight_min" maxlength="3" value="<? echo $_GET['weight_min']; ?>" /><b>&nbsp;-&nbsp;</b><input class="day" type="text" size="5" name="weight_max" maxlength="3" value="<? echo $_GET['weight_max']; ?>" /></td>
                    </tr>    
                    <tr>
                      <td class="right"><b>Nationality:</b></td>
                      <td>
                        <select name="nationality">
			                   <option value="">all nationalities</option>
			                   <?php
                        $query_sub="SELECT * FROM countries ORDER by name";
                        $result_sub = $con->SelectQuery($query_sub);
                        while($write_sub = $result_sub->fetch_array()){
                        echo '
                          <option value="'.$write_sub['shortcut'].'"'.write_select($write_sub['shortcut'],$_GET['nationality']).'>'.$games->GetActualCountryName($write_sub['id'],ActSeasonWeb).'</option>
                        ';
                        }
                        ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="right"><b>Position:</b></td>
                      <td>
                        <select name="position">
			                   <option value="">all positions</option>
			                   <?php
                        $query_sub="SELECT * FROM players_positions_list WHERE id<>12 ORDER by id";
                        $result_sub = $con->SelectQuery($query_sub);
                        while($write_sub = $result_sub->fetch_array()){
                        echo '
                          <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$_GET['position']).'>'.$write_sub['shortcut'].' | '.$write_sub['name'].'</option>
                        ';
                        }
                        ?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="right"><b>Gender:</b></td>
                      <td>
                        <select name="gender">
			                   <option value="">all</option>
                          <option value="1"<? echo write_select(1,$_GET['gender']); ?>>male</option>
	 		                    <option value="2"<? echo write_select(2,$_GET['gender']); ?>>female</option>
                        </select>
                      </td>
                    </tr>
                     <tr>
                      <td class="right"><b>Status:</b></td>
                      <td>
                        <select name="status">
			                   <option value="">all</option>
                           <?php
                        $query_sub="SELECT * FROM players_status_list ORDER by id";
                        $result_sub = $con->SelectQuery($query_sub);
                        while($write_sub = $result_sub->fetch_array()){
                        echo '
                          <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$_GET['status']).'>'.$write_sub['name'].'</option>
                        ';
                        }
                        ?>
                        </select>
                      </td>
                    </tr>
                    </table>
                    
                <div class="clear">&nbsp;</div>
                <span class="players_submit center"><input type="submit" class="submit" value="" /></span>
                <input type="hidden" name="advanced" value="1" />
                <input type="hidden" name="write_player_search" value="1" />
                <div class="clear">&nbsp;</div>
                </form>
                
                
              </div>
         </div>
         
         <div class="space">&nbsp;</div>
              
              <div class="header box_normal">&nbsp;</div>
              <div class="box normal">
                
                <table>
                <tr><td valign="top" class="nowrap"><b>Most searched:</b></td><td valign="top">
                <?php
                        $query_sub="SELECT count(search) as pocet,search FROM players_search group by search ORDER by pocet DESC LIMIT 8";
                        $counter=0;
                        $result_sub = $con->SelectQuery($query_sub);
                        while($write_sub = $result_sub->fetch_array()){
                          $counter++;
                          echo '<a href="/players.html?player_search='.$write_sub['search'].'"  class="link" title="Show results for: '.$write_sub['search'].'">'.$write_sub['search'].'</a>';
                          if ($counter<8){echo ', ';}
                        }
                ?>
                </td></tr>
                <tr><td valign="top"><b>Last searched:</b></td><td valign="top">
                <?php
                        $query_sub="SELECT DISTINCT search FROM players_search ORDER by datetime DESC LIMIT 8";
                        $counter=0;
                        $result_sub = $con->SelectQuery($query_sub);
                        while($write_sub = $result_sub->fetch_array()){
                          $counter++;
                          echo '<a href="/players.html?player_search='.$write_sub['search'].'"  class="link" title="Show results for: '.$write_sub['search'].'">'.$write_sub['search'].'</a>';
                          if ($counter<8){echo ', ';}
                        }
                ?>  
                </td></tr>
                </table>
                
              </div>
         
         
          
          <?php
          
          function write_player_search ($search,$go){
              if ($go==1){
                global $con;
                if (stristr(strtolower($search),"http")==false and stristr(strtolower($search),"www.")==false  and stristr(strtolower($search),"player ")==false){
                  $query="INSERT INTO players_search (search) VALUES ('".$search."')";
                  $con->RunQuery($query);
                  //echo 'ok';
                  
                  $query_sub="DELETE FROM players_search WHERE datetime<(NOW()-INTERVAL 2 DAY)";
                  $con->RunQuery($query_sub);
                  
                }
              }
          }
          
          if ($_GET['advanced']==1){
            
            $strVariables.='advanced=1&amp;';   
            //advanced search
            if (!empty($_GET['firstname'])){
              $search=trim($_GET["firstname"]);
              $search=$input->valid_text($search,true,true);
              $strWhere.= " AND (name LIKE '%".$search."%')";
              $strVariables.='firstname='.$search.'&amp;';    
            }
            if (!empty($_GET['lastname'])){
              $search=trim($_GET["lastname"]);
              $search=$input->valid_text($search,true,true);
              write_player_search($search,$_GET['write_player_search']);
              $strWhere.= " AND (surname LIKE '%".$search."%')";
              $strVariables.='lastname='.$search.'&amp;';    
            }
            if (!empty($_GET['day'])){
              $search=$_GET["day"];
              $search=$input->check_number($search);
              $strWhere.= " AND (DAY(birth_date)='".$search."')";
              $strVariables.='day='.$search.'&amp;';      
            }
            if (!empty($_GET['month'])){
              $search=$_GET["month"];
              $search=$input->check_number($search);
              $strWhere.= " AND (month(birth_date)='".$search."')";
              $strVariables.='month='.$search.'&amp;';      
            }
            if (!empty($_GET['year'])){
              $search=$_GET["year"];
              $search=$input->check_number($search);
              $strWhere.= " AND (year(birth_date)='".$search."')";
              $strVariables.='year='.$search.'&amp;';      
            }
            if (!empty($_GET['day'])){
              $search=$_GET["day"];
              $search=$input->check_number($search);
              $strWhere.= " AND (DAY(birth_date)='".$search."')";
              $strVariables.='day='.$search.'&amp;';      
            }
            if (!empty($_GET['height_min'])){
              $search=$_GET["height_min"];
              $search=$input->check_number($search);
              $strWhere.= " AND (height>=".$search.")";
              $strVariables.='height_min='.$search.'&amp;';    
            }
            if (!empty($_GET['height_max'])){
              $search=$_GET["height_max"];
              $search=$input->check_number($search);
              $strWhere.= " AND (height<=".$search.")";
              $strVariables.='height_max='.$search.'&amp;';    
            }
            if (!empty($_GET['weight_min'])){
              $search=$_GET["weight_min"];
              $search=$input->check_number($search);
              $strWhere.= " AND (weight>=".$search.")";
              $strVariables.='weight_min='.$search.'&amp;';    
            }
            if (!empty($_GET['weight_max'])){
              $search=$_GET["weight_max"];
              $search=$input->check_number($search);
              $strWhere.= " AND (weight<=".$search.")";
              $strVariables.='weight_max='.$search.'&amp;';    
            }
            if (!empty($_GET['nationality'])){
              $search=$_GET["nationality"];
              $search=$input->valid_text($search,true,true);
              $strWhere.= " AND (nationality='".$search."' OR nationality_2='".$search."')";
              $strVariables.='nationality='.$search.'&amp;';    
            }
            if (!empty($_GET['position'])){
            
              $search=$_GET["position"];
              $search=$input->check_number($search);
              $strVariables.='position='.$search.'&amp;';
              switch ($search){
              case 4:
                $strWhere.=" AND (id_position=4 OR players.id_position=2 OR id_position=3 OR id_position=10)";
              break;
              case 9:
                $strWhere.=" AND (id_position=9 OR id_position=5 OR id_position=6 OR id_position=8 OR id_position=10)";
              break;
              default:
                $strWhere.=" AND id_position=".$search;
              }
              
              
                  
            }
            if (!empty($_GET['gender'])){
              $search=$_GET["gender"];
              $search=$input->check_number($search);
              $strWhere.= " AND (gender=".$search.")";
              $strVariables.='gender='.$search.'&amp;';    
            }
            if (!empty($_GET['status'])){
              $search=$_GET["status"];
              $search=$input->check_number($search);
              $strWhere.= " AND (id_status=".$search.")";
              $strVariables.='id_status='.$search.'&amp;';    
            }
            
            
          }else{
            
            //classic search
            if (!empty($_GET['player_search'])){
              $search=$_GET["player_search"];
              $search=$input->valid_text($search,true,true);
              write_player_search($search,$_GET['write_player_search']);
              $strWhere= " AND (CONCAT_WS(\" \",name,surname) LIKE '%".$search."%' or CONCAT_WS(\" \",surname,name) LIKE '%".$search."%' or name LIKE '%".$search."%' or surname LIKE '%".$search."%')";
              $strVariables.='player_search='.$search.'&amp;';    
            }
            
          }
          
          $intOrderDir=$_GET['dir'];
             switch ($intOrderDir){
              case 1:
                $strOrderDir="ASC";
                if ($_GET['is_order']==1) $intOrderDirChange=2; 
              break;
              case 2:
                $strOrderDir="DESC";
                if ($_GET['is_order']==1) $intOrderDirChange=1;
              break;
            }
          
          $intOrder=$_GET['order'];
            switch ($intOrder){
              case 1:
                if (empty($intOrderDir)) {$strOrderDir="ASC"; $intOrderDir=1; $intOrderDirChange=2;}
                $strOrder="surname ".$strOrderDir.",name ASC,nationality ASC";
              break;
              case 2:
                if (empty($intOrderDir)) {$strOrderDir="ASC"; $intOrderDir=1;  $intOrderDirChange=2;}
                $strOrder="id_position ".$strOrderDir.",surname ASC,name ASC,nationality ASC";
              break;
              case 3:
                if (empty($intOrderDir)) {$strOrderDir="ASC"; $intOrderDir=1;  $intOrderDirChange=2;}
                $strOrder="birth_date ".$strOrderDir.",surname ASC,name ASC,nationality ASC";
              break;
              case 4:
                if (empty($intOrderDir)) {$strOrderDir="ASC"; $intOrderDir=1;  $intOrderDirChange=2;}
                $strOrder="height ".$strOrderDir.",surname ASC,name ASC,nationality ASC";
              break;
              case 5:
                if (empty($intOrderDir)) {$strOrderDir="ASC"; $intOrderDir=1;  $intOrderDirChange=2;}
                $strOrder="weight ".$strOrderDir.",surname ASC,name ASC,nationality ASC";
              break;
              case 6:
                if (empty($intOrderDir)) {$strOrderDir="ASC"; $intOrderDir=1;  $intOrderDirChange=2;}
                $strOrder="id_shoot ".$strOrderDir.",surname ASC,name ASC,nationality ASC";
              break;
              case 7:
                if (empty($intOrderDir)) {$strOrderDir="ASC"; $intOrderDir=1;  $intOrderDirChange=2;}
                $strOrder="id_status ".$strOrderDir.",surname ASC,name ASC,nationality ASC";
              break;
              
              default:
                if (empty($intOrderDir)) {$strOrderDir="ASC"; $intOrderDir=1;  $intOrderDirChange=2;}
                $strOrder="surname ".$strOrderDir.",name ASC,nationality ASC";
                $intOrder=0;
            }
          
          $strVariables.='list_number='.$_GET['list_number'].'&amp;';
          
          $strVariables.='order='.$intOrder.'&amp;dir='.$intOrderDir.'&amp;';
          
          $query="SELECT name,surname,nationality,id,birth_date,id_position,weight,height,id_shoot,id_status,id FROM players WHERE 1 ".$strWhere." ORDER BY ".$strOrder;
          $listovani = new listing($con,"?".$strVariables."&amp;",100,$query,3,"",$_GET['list_number']);
          $query=$listovani->updateQuery();
          //echo $query; 
          if ($con->GetQueryNum($query)>0){
            
            echo '
          <table class="tablesorter basic" id="myTable">
          <thead>
                 <tr>
                    <th class="number center" valign="top">&nbsp;</th>
                    <th class="" valign="top"><a href="/players.html?is_order=1&amp;order=1&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by name">Name</a></th>
                    <th class="number" valign="top"><a href="/players.html?is_order=1&amp;order=2&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by name">Position</a></th>
                    <th class="" valign="top"><a href="/players.html?is_order=1&amp;order=3&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by name">Birth date</a></th>
                    <th class="number" valign="top"><a href="/players.html?is_order=1&amp;order=4&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by name">Height</a></th>
                    <th class="number" valign="top"><a href="/players.html?is_order=1&amp;order=5&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by name">Weight</a></th>
                    <th class="number" valign="top"><a href="/players.html?is_order=1&amp;order=6&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by name">Stick</a></th>
                    <th class="number" valign="top"><a href="/players.html?is_order=1&amp;order=7&amp;dir='.$intOrderDirChange.'&amp;'.$strVariables.'" title="Sort by name">Status</a></th>
                    <th class="link" valign="top">&nbsp;</th>
                  </tr>
          </thead>
          ';
            $counter=0;
            $result = $con->SelectQuery($query);
            while($write = $result->fetch_array())
	           {
	     
	             $name_player=$write["name"].' '.$write["surname"];
	             $url=get_url_text($name_player,$write['id']);
	             $birth_date=$write["birth_date"];
	             
	             if ($birth_date=="????-??-??"){
	               $birth_date="-";
               }else{
	               $birth_date=explode("-",$birth_date);
                if (!empty($birth_date[2])) $date_bith_day=$birth_date[2]; else $date_bith_day='??';
                if (!empty($birth_date[1])) $date_bith_month=$birth_date[1]; else $date_bith_month='??';
                if (!empty($birth_date[0])) $date_bith_year=$birth_date[0]; else $date_bith_year='????';
                $birth_date=$date_bith_day.'.'.$date_bith_month.'.'.$date_bith_year;
               }
	             $nationality=$write["nationality"];
	             $id_position=$con->GetSQLSingleResult("SELECT name as item FROM players_positions_list WHERE id=".$write['id_position']."");
	             $weight=$write["weight"];
	             if (empty($weight)) $weight="-"; else  $weight=$weight." kg";
	             $height=$write["height"];
	             if (empty($height)) $height="-"; else  $height=$height." cm";
	             $id_shoot=$con->GetSQLSingleResult("SELECT name as item FROM players_shoots_list WHERE id=".$write['id_shoot']."");
	             if ($write['id_shoot']==3) $id_shoot="-";
	             
	             //pokud neni v DB stats poslednich 10 sezon=retired
	             $intStatusID=$write['id_status'];
	             $countRetired=$con->GetSQLSingleResult("SELECT count(*) as item FROM stats WHERE id_player=".$write['id']." AND id_season>=".(ActSeasonWeb-10));
	             if ($countRetired==0)  {$intStatusID=4;}
	              
	             $id_status=$con->GetSQLSingleResult("SELECT name as item FROM players_status_list WHERE id=".$intStatusID."");
	             if ($intStatusID==2)  {$id_status="&nbsp;";}
	             
	             
	             if ($counter%2==0) {$strStyle="";} else {$strStyle="dark";}
               echo '<tr class="'.$strStyle.'">
                        <td class="number center" valign="top">'.get_flag(strtolower($nationality),15,11).'</td>
                        <td valign="top"><a href="/player/'.$url.'" title="Show player '.$name_player.'"><strong>'.$name_player.'</strong></a></td>
                        <td class="" valign="top">'.$id_position.'</td>
                        <td class="" valign="top">'.$birth_date.'</td>
                        <td class="number" valign="top">'.$height.'</td>
                        <td class="number" valign="top">'.$weight.'</td>
                        <td class="number" valign="top">'.$id_shoot.'</td>
                        <td class="" valign="top">'.$id_status.'</td>
                        <td class="link" valign="top"><span class="link"><a href="/player/'.$url.'" title="Show league '.$name_player.'">Show&nbsp;details&raquo;</a></span></td>
                      </tr>
               ';
               $counter++;
	         }
	         
	         echo '<tfoot>';
	    //listovani
      $listovani->show_list();
      echo '</tfoot>';
    echo '</table>';
	         
    }else{
        echo '<p class="center bold">"No players found for specified criteria.</p>';
    }
      
    ?>
    
    
         
         
          <div class="space">&nbsp;</div>
                          
         
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