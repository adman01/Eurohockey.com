         <?php require_once("col_right_player_search.inc"); ?>
         
         <div class="header recent_transfers"><span>Recent transfers</span><span class="toright"><a href="/transfers.html" title="Show all transfers">Show all transfers&raquo;</a></span></div>
         <div class="box_items">
            
            <?php
            
              $query="SELECT date_time, id_player,id_position,id_club_from,id_club_to,id_country_from,id_country_to,id_retire_status FROM transfers  WHERE id_source_note=1 ORDER BY date_time DESC,last_update DESC  LIMIT 7";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '<ol>';
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    echo '<li>';
                      $player_name=$con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$write['id_player']);
                      $player_nationality=$con->GetSQLSingleResult("SELECT nationality as item FROM players WHERE id=".$write['id_player']);
                      echo '<span class="name"><strong>'.date("d M Y",strtotime($write['date_time'])).'&nbsp;|&nbsp;</span>';
                      echo '<span class="name">';
                              echo get_flag(strtolower($player_nationality),15,11);
                              echo '
                              <a href="/player/'.get_url_text($player_name,$write['id_player']).'" title="Show player: '.$player_name.'">'.$player_name.'</a> 
                              &nbsp;('.$con->GetSQLSingleResult("SELECT shortcut as item FROM transfers_position_list WHERE id=".$write['id_position']).')
                              </strong>
                            </span>
                            ';
                            echo '<div class="clear">&nbsp;</div>';
                            //from club/country
                            
                            echo '<span>';
                            if (empty($write['id_club_from']) and empty($write['id_country_from'])){
                              echo 'unknown';
                            }else
                            {
                            if (!empty($write['id_club_from'])){
                              $ItemName=$games->GetActualClubName($write['id_club_from'],ActSeasonWeb);
                              $CountryShortCut=$con->GetSQLSingleResult("SELECT id_country as item FROM clubs WHERE id=".$write['id_club_from']);
                              if (empty($CountryShortCut)){
                                
                                $LeagueID=$con->GetSQLSingleResult("SELECT id_league as item FROM clubs_leagues_items WHERE id_club=".$write['id_club_from']." ORDER by id DESC");
                                if (!empty($LeagueID)){
                                  $CountryShortCut=$con->GetSQLSingleResult("SELECT (SELECT shortcut FROM countries WHERE leagues_countries_items.id_country=countries.id LIMIT 1) as item FROM leagues_countries_items WHERE id_league=".$LeagueID." ORDER by id DESC LIMIT 1");
                                }
                              }
                              echo get_flag(strtolower($CountryShortCut),15,11);
                              echo '<a href="/club/'.get_url_text($ItemName,$write['id_club_from']).'" title="Show club: '.$ItemName.'">'.$ItemName.'</a>';
                              
                              
                            }else{
                              $ItemName=$games->GetActualCountryName($write['id_country_from'],ActSeasonWeb);
                              $CountryShortCut=$con->GetSQLSingleResult("SELECT shortcut as item FROM countries WHERE id=".$write['id_country_from']);
                              echo get_flag(strtolower($CountryShortCut),15,11);
                              echo '<a href="/country/'.get_url_text($ItemName,$write['id_country_from']).'" title="Show country: '.$ItemName.'">'.$ItemName.'</a>';
                              
                            }
                            }
                            echo '</span>';
                            
                            echo '<img src="/img/arrow_right.png" class="arrow_right" alt="arrow" height="9" width="12" />';
                            
                            //to club/country
                            echo '<span>';
                            if (empty($write['id_club_to']) and empty($write['id_country_to'])){
                              if ($write['id_retire_status']>1){
                                echo $con->GetSQLSingleResult("SELECT name as item FROM transfers_retire_list WHERE id=".$write['id_retire_status']); 
                              }else{
                                echo 'unknown';
                              } 	
                              
                            }else
                            {
                            if (!empty($write['id_club_to'])){
                              $ItemName=$games->GetActualClubName($write['id_club_to'],ActSeasonWeb);
                              $CountryShortCut=$con->GetSQLSingleResult("SELECT id_country as item FROM clubs WHERE id=".$write['id_club_to']);
                              if (empty($CountryShortCut)){
                                
                                $LeagueID=$con->GetSQLSingleResult("SELECT id_league as item FROM clubs_leagues_items WHERE id_club=".$write['id_club_to']." ORDER by id DESC");
                                if (!empty($LeagueID)){
                                  $CountryShortCut=$con->GetSQLSingleResult("SELECT (SELECT shortcut FROM countries WHERE leagues_countries_items.id_country=countries.id LIMIT 1) as item FROM leagues_countries_items WHERE id_league=".$LeagueID." ORDER by id DESC LIMIT 1");
                                }
                              }
                              echo get_flag(strtolower($CountryShortCut),15,11);
                              echo '<a href="/club/'.get_url_text($ItemName,$write['id_club_to']).'" title="Show club: '.$ItemName.'">'.$ItemName.'</a>';
                              
                            }else{
                              //
                              $ItemName=$games->GetActualCountryName($write['id_country_to'],ActSeasonWeb);
                              $CountryShortCut=$con->GetSQLSingleResult("SELECT shortcut as item FROM countries WHERE id=".$write['id_country_to']);
                              echo get_flag(strtolower($CountryShortCut),15,11);
                              echo '<a href="/country/'.get_url_text($ItemName,$write['id_country_to']).'" title="Show country: '.$ItemName.'">'.$ItemName.'</a>';
                            }
                            }
                            echo '</span>';
                    echo '</li>';
                  }
                  echo '</ol>';
              }
              
              ?>
            
         </div>
       
       <div id="ads_col_right"><?php show_ads("ads_col_right"); ?></div>
       
         <?php require_once("col_right_games_list.inc"); ?>
         
         <?php require_once("col_right_leagues_list.inc"); ?>
         
         <div class="header leagues_statistic"><span>Leagues statistic</span></div>
         <div class="box leagues_statistic">
            <?php
              $strCountryList="";
              $query="SELECT id FROM countries ORDER BY name ASC";
              //$query="SELECT id FROM countries WHERE league_status=1 AND youth_league=0 AND name<>'' ORDER BY name ASC LIMIT 10";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  $result = $con->SelectQuery($query);
                  while($write = $result->fetch_array()){
                    $strCountryList.='<option value="'.$write['id'].'">'.$games->GetActualCountryName($write['id'],ActSeasonWeb).'</option>';
                  }
              }
             ?>

            <div class="toleft"><strong>League standings</strong> </div>
            <div class="toright">
              <form action="">
              <div>
              <select name="id_country" id="leagues_standings" onchange="show_leagues_col_right('leagues_standings')">
                 <option value="">- select country -</option>
	               <?php echo $strCountryList; ?>
              </select>
              <div id="leagues_standings_box" class="hidden">
                <div id="leagues_standings_select"></div>
              </div>
              </div>
              </form>
            </div>
            <div class="space">&nbsp;</div>
            
            <div class="toleft"><strong>Game fixtures</strong> </div>
            <div class="toright">
              <form action="">
              <div>
              <select name="id_country" id="leagues_fixtures" onchange="show_leagues_col_right('leagues_fixtures')">
                 <option value="">- select country -</option>
	               <?php echo $strCountryList; ?>
              </select>
              <div id="leagues_fixtures_box" class="hidden">
                <div id="leagues_fixtures_select"></div>
              </div>
              </div>
              </form>
            </div>
            <div class="space">&nbsp;</div>
            
            <div class="toleft"><strong>League leaders</strong> </div>
            <div class="toright">
              <form action="">
              <div>
              <select name="id_country" id="leagues_leaders" onchange="show_leagues_col_right('leagues_leaders')">
                 <option value="">- select country -</option>
	               <?php echo $strCountryList; ?>
              </select>
              <div id="leagues_leaders_box" class="hidden">
                <div id="leagues_leaders_select"></div>
              </div>
              </div>
              </form>
            </div>
            <div class="clear">&nbsp;</div>
          
         </div>
         
         <div class="hidden header_cufon"><span class="header_text">Recent transfers</span><span class="toright"><a href="/transfers.html" title="Show all transfers">Show all transfers&raquo;</a></span></div>