<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");
$strHeaderKeywords='countries, list';
$strHeaderDescription='Countries';
require_once("inc/head.inc");
echo $head->setTitle($strHeaderDescription." - ".strProjectName);
?>
<script type="text/javascript">
$(document).ready(function(){
	$.tablesorter.addParser({
	            id: 'dd.mm.yyyy',
	            is: function(s) {
	                return false;
	            },
	            format: function(s) {
	                s = '' + s; //Make sure it's a string
	                var hit = s.match(/(\d{1,2})\.(\d{1,2})\.(\d{4})/);
	                if (hit && hit.length == 4) {
	                    return hit[3] + hit[2] + hit[1];
	                }
	                else {
	                    return s;
	                }
	            },
	            type: 'text'
     });
     
     $.tablesorter.addParser({ 
	   
	    id: 'number', 
        is: function(s) { 
       
            return /(Kč|Sk|CZK|USD|EUR|AUD|GBP|PLN|SKK){1}$/.test(s);
        }, 
        format: function(s) { 
            return $.tablesorter.formatFloat(s.replace(new RegExp(/[^0-9.]/g),""));
        }, 
        type: 'numeric' 
      }); 
	   
		$.tablesorter.defaults.sortList = [[0,1]];
		$("table").tablesorter({  
			headers: {
			  3: { sorter: 'number'},
			  4: { sorter: 'number'},
			  5: { sorter: 'number'},
			  6: { sorter: 'number'},
			  1: { sorter: false },
			  7: { sorter: false }
			},
      widgets: ['zebra']
		});
	});
</script>
<?
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
         
         <h1>Countries</h1>
         
              <?php
              $query="SELECT * FROM countries ORDER BY name";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '<table class="tablesorter basic" id="myTable">';
                  echo '
                  <thead>
                  <tr>
                    <th class="hidden">&nbsp;</th>
                    <th class="flag" valign="top">&nbsp;</th>
                    <th class="" valign="top">Name</th>
                    <th class="" valign="top">Leagues</th>
                    <th class="number" valign="top">Clubs</th>
                    <th class="number" valign="top">Players</th>
                    <th class="number" valign="top">Arenas</th>
                    <th class="link" valign="top">&nbsp;</th>
                    
                  </tr>
                  </thead>
                  <tbody>
                  ';
                  $result = $con->SelectQuery($query);
                  $counter=0;
                  while($write = $result->fetch_array()){
                    $counter++;
                    //if ($counter%2) $style=""; else $style="dark";
  		              echo '<tr>';
  		                echo '<td class="hidden" valign="top">'.$write['is_important'].'</td>';
                      $StrPosition=$con->GetSQLSingleResult("SELECT shortcut as item FROM transfers_position_list WHERE id=".$write['id_position']);
                      $StrCountryName=$games->GetActualCountryName($write['id'],ActSeasonWeb);
                      echo '<td class="flag" valign="middle">';
                      echo get_flag(strtolower($write['shortcut']),20,14);
                      echo '</td>';                         
                      echo '<td class="" valign="top"><a href="/country/'.get_url_text($StrCountryName,$write['id']).'" title="Show country: '.$StrCountryName.'"><strong>'.$StrCountryName.'</strong></a></td>';
                      $countLeagues=$con->GetSQLSingleResult("SELECT count(*) as item FROM leagues_countries_items WHERE id_country=".$write['id']."");
                      if ($countLeagues>1) $name_items_correct="leagues"; else $name_items_correct="league";
                      echo '<td class="number" valign="top"><a href="/leagues.html?id_country='.$write['id'].'" title="Show leagues from '.$StrCountryName.'"><strong>'.$countLeagues.'</strong> '.$name_items_correct.'</a></td>';
                      $countClubs=$con->GetSQLSingleResult("SELECT count(*) as item FROM clubs WHERE id_country='".$write['shortcut']."'");
                      if ($countClubs>1) $name_items_correct="clubs"; else $name_items_correct="club";
                      echo '<td class="number" valign="top"><a href="/clubs.html?id_country='.$write['id'].'" title="Show clubs from '.$StrCountryName.'"><strong>'.$countClubs.'</strong> '.$name_items_correct.'</a></td>';
                      $countPlayers=$con->GetSQLSingleResult("SELECT count(*) as item FROM players WHERE nationality='".$write['shortcut']."'");
                      if ($countPlayers>1) $name_items_correct="players"; else $name_items_correct="player";
                      echo '<td class="number" valign="top"><a href="/players.html?nationality='.$write['shortcut'].'&amp;advanced=1&amp;write_player_search=1" title="Show players from '.$StrCountryName.'"><strong>'.$countPlayers.'</strong> '.$name_items_correct.'</a></td>';
                      $countArenas=$con->GetSQLSingleResult("SELECT count(*) as item FROM arenas WHERE id_country=".$write['id']);
                      if ($countArenas>1) $name_items_correct="arenas"; else $name_items_correct="arena";
                      echo '<td class="number" valign="top"><a href="/arenas.html?id_country='.$write['id'].'" title="Show arenas from '.$StrCountryName.'"><strong>'.$countArenas.'</strong> '.$name_items_correct.'</a></td>';
                      echo '<td class="link" valign="top"><span class="link"><a href="/country/'.get_url_text($StrCountryName,$write['id']).'" title="Show country: '.$StrCountryName.'">Show details&raquo;</a></span></td>';
                      
                    echo '</tr>';
                  }
                  echo '</tbody></table>';
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