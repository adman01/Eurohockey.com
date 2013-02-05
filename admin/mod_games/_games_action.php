<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(31,1,0,0,0);
$input = new Input_filter();
$games = new games($con);
$xml = new xml_generator($con);

function get_id_stats($id_game,$id_player,$id_club){
  global $con;
  
  $query_game="SELECT id_season,id_league,id_stage FROM games WHERE id=".$id_game;
  $result_game = $con->SelectQuery($query_game);
  $write_game = $result_game->fetch_array();
  $id_season=$write_game['id_season'];
  $id_league=$write_game['id_league'];
  $id_stage=$write_game['id_stage'];
  
  $intIDstageDefault=$con->GetSQLSingleResult("SELECT id_detault as item FROM games_stages WHERE id=".$id_stage);
  if (!empty($intIDstageDefault)) {$id_stage=$intIDstageDefault;} 
  
  $query_stats="SELECT id FROM stats WHERE id_player=".$id_player." AND id_club=".$id_club." AND id_season=".$id_season." AND id_league=".$id_league." AND id_season_type=".$id_stage." AND id_stats_type=1 ORDER BY id DESC";
  if ($con->GetQueryNum($query_stats)>0){
    $result_stats = $con->SelectQuery($query_stats);
    $write_stats = $result_stats->fetch_array();
    $id_stats=$write_stats['id'];
  }else{
    $query="INSERT INTO stats (id_stats_type,id_player,id_club,id_league,id_season,id_season_type)
            VALUES (1,".$id_player.",".$id_club.",".$id_league.",".$id_season.",".$id_stage.")";
    if ($con->RunQuery($query)){
       $query_stats="SELECT id FROM stats WHERE id_player=".$id_player." AND id_club=".$id_club." AND id_season=".$id_season." AND id_league=".$id_league." AND id_season_type=".$id_stage." AND id_stats_type=1 ORDER BY id DESC";
       $result_stats = $con->SelectQuery($query_stats);
       $write_stats = $result_stats->fetch_array();
       $id_stats=$write_stats['id'];
    }
  }
  return $id_stats;
}

function get_lock($id_season,$id_league,$id_club){
  //stats lock
  global $con;
  $boolStatsLock=false;  
  $query_lock="SELECT * FROM stats_lock WHERE id_club=0 AND id_season=".$id_season." AND id_league=".$id_league."";
  if ($con->GetQueryNum($query_lock)==0){
    $query_lock="SELECT * FROM stats_lock WHERE id_club=".$id_club." AND id_season=".$id_season." AND id_league=".$id_league."";
    if ($con->GetQueryNum($query_lock)>0){$boolStatsLock=true;}
  }else{$boolStatsLock=true;}
  return $boolStatsLock;          
}

//pridat novy zaznam
    if ($_POST['action']=="add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $id_league=$_POST['id_league'];
    $id_league=$input->check_number($id_league);
    $id_season=$_POST['id_season'];
    $id_season=$input->check_number($id_season);
    
    $number=$_POST['number'];
    for ($i=0; $i<=($number); $i++){
     
      $date=$_POST['date'][$i];
      $date=$input->valid_text($date,true,true);
      $time=$_POST['time'][$i];
      $time=$input->valid_text($time,true,true);
      $id_stage=$_POST['id_stage'][$i];
      $id_stage=$input->check_number($id_stage);
      $round=$_POST['round'][$i];
      $round=$input->valid_text($round,true,true);
      $id_home_team=$_POST['id_home_team'][$i];
      $id_home_team=$input->check_number($id_home_team);
      $id_visiting_team=$_POST['id_visiting_team'][$i];
      $id_visiting_team=$input->check_number($id_visiting_team);
      $home_score=$_POST['home_score'][$i];
      if ($home_score=="") $home_score="NULL"; else $home_score=$input->check_number($home_score);
      $visiting_score=$_POST['visiting_score'][$i];
      if ($visiting_score=="") $visiting_score="NULL"; else $visiting_score=$input->check_number($visiting_score);
      $games_status=$_POST['games_status'][$i];
      $games_status=$input->check_number($games_status);
      
      $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_season='.$id_season.'&id='.$id_league;
      
      if (!empty($date) and !empty($id_stage) and !empty($id_home_team) and !empty($id_visiting_team) and !empty($games_status)){
        
        $date=date("Y-m-d",strtotime($date));
        
        $query="INSERT INTO games (last_update,id_user,id_club_home,id_club_visiting,round,date,time,id_season,id_league,id_stage,home_score,visiting_score,games_status)
                  VALUES (NOW(),".$users->getIdUser().",".$id_home_team.",".$id_visiting_team.",'".$round."','".$date."','".$time."',".$id_season.",".$id_league.",".$id_stage.",".$home_score.",".$visiting_score.",".$games_status.")
            ";
        //echo $query; 
        if (!$con->RunQuery($query)){
              //spatna DB
              $intError++;
        }else{
              $xml->generate_xml_games($id_season,$id_league,$id_stage);
              $intCorrect++;
              
              //automaticke vygenerovani tabulky
              $id_standings=$con->GetSQLSingleResult("SELECT id as item FROM standings WHERE id_type=1 AND id_league=".$id_league." AND id_season=".$id_season." AND id_stage=".$id_stage);
              if (!empty($id_standings)) {
                generate_auto_standings($id_standings);
                $xml->generate_xml_standings($id_standings);
              }
              
              //automaticke prirazeni domaci areny
              $id_arena=$con->GetSQLSingleResult("SELECT id_arena as item FROM clubs_arenas_items WHERE id_club=".$id_home_team." ORDER by id_arena ASC");
              if (!empty($id_arena)) {
                
                $id_game_new=$con->GetSQLSingleResult("SELECT id as item FROM games ORDER by id DESC LIMIT 1");  
                $query="UPDATE games SET 	id_arena=".$id_arena." WHERE id=".$id_game_new;
                $con->RunQuery($query);
              }
              
        }
        
        
      }else{
          //špatná nebo chybějící vstupní data
          //$intError++;
      }
    }
      header("Location: games.php".Odkaz."&message=1".$strReturnData."&intCorrect=".$intCorrect."&intError=".$intError);
}  

//pridat novy zaznam   CSV IMPORT
    if ($_POST['action']=="add_csv" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $number=$_POST['number'];
    for ($i=2; $i<=($number); $i++){
      $date=$_POST['date'][$i];
      $date=$input->valid_text($date,true,true);
      $time=$_POST['time'][$i];
      $time=$input->valid_text($time,true,true);
      $id_stage=$_POST['id_type'][$i];
      $id_stage=$input->check_number($id_stage);
      $round=$_POST['round'][$i];
      $round=$input->valid_text($round,true,true);
      $id_home_team=$_POST['id_club_home'][$i];
      $id_home_team=$input->check_number($id_home_team);
      $id_visiting_team=$_POST['id_club_visiting'][$i];
      $id_visiting_team=$input->check_number($id_visiting_team);
      $home_score=$_POST['home_score'][$i];
      if ($home_score=="") $home_score="NULL"; else $home_score=$input->check_number($home_score);
      $visiting_score=$_POST['visiting_score'][$i];
      if ($visiting_score=="") $visiting_score="NULL"; else $visiting_score=$input->check_number($visiting_score);
      $games_status=$_POST['games_status'][$i];
      $games_status=$input->check_number($games_status);
      $id_league=$_POST['id_league'][$i];
      $id_league=$input->check_number($id_league);
      $id_season=$_POST['id_season'][$i];
      $id_season=$input->check_number($id_season);
    
      $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_season='.$id_season.'&id='.$id_league;
      //echo $id_home_team;
      if (!empty($date) and !empty($id_league)  and !empty($id_season) and !empty($id_stage) and !empty($id_home_team) and !empty($id_visiting_team) and !empty($games_status)){
        
        $date=date("Y-m-d",strtotime($date));
        $time=date("H:i",strtotime($time));
        
        $query="INSERT INTO games (last_update,id_user,id_club_home,id_club_visiting,round,date,time,id_season,id_league,id_stage,home_score,visiting_score,games_status)
                  VALUES (NOW(),".$users->getIdUser().",".$id_home_team.",".$id_visiting_team.",'".$round."','".$date."','".$time."',".$id_season.",".$id_league.",".$id_stage.",".$home_score.",".$visiting_score.",".$games_status.")
            ";
        if (!$con->RunQuery($query)){
              //spatna DB
              $intError++;
        }else{
              $intCorrect++;
              $xml->generate_xml_games($id_season,$id_league,$id_stage);
              
              //automaticke prirazeni domaci areny
              $id_arena=$con->GetSQLSingleResult("SELECT id_arena as item FROM clubs_arenas_items WHERE id_club=".$id_home_team." ORDER by id_arena ASC");
              if (!empty($id_arena)) {
                
                $id_game_new=$con->GetSQLSingleResult("SELECT id as item FROM games ORDER by id DESC LIMIT 1");  
                $query="UPDATE games SET 	id_arena=".$id_arena." WHERE id=".$id_game_new;
                $con->RunQuery($query);
              }
              
        }
        
        
      }else{
          //špatná nebo chybějící vstupní data
          //$intError++;
      }
    }
      header("Location: games.php".Odkaz."&message=1".$strReturnData."&intCorrect=".$intCorrect."&intError=".$intError);
}  


//upravit zaznam
    if ($_POST['action']=="update" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $number=$_POST['number'];
    
    for ($i=0; $i<($number); $i++){
      
      $id=$_POST['id'][$i];
      $id=$input->check_number($id);
      
      $id_league=$_POST['id_league'][$i];
      $id_league=$input->check_number($id_league);
      $id_season=$_POST['id_season'][$i];
      $id_season=$input->check_number($id_season);
    
      $date=$_POST['date'][$i];
      $date=$input->valid_text($date,true,true);
      $time=$_POST['time'][$i];
      $time=$input->valid_text($time,true,true);
      $id_stage=$_POST['id_stage'][$i];
      $id_stage=$input->check_number($id_stage);
      $round=$_POST['round'][$i];
      $round=$input->valid_text($round,true,true);
      
      $home_team_select_type=$_POST['home_team_select_type'][$i];
      $home_team_select_type=$input->check_number($home_team_select_type);
      if ($home_team_select_type==1){
        $id_home_team=$_POST['id_home_team'][$i];
        $id_home_team=$input->check_number($id_home_team);
      }else{
        $id_home_team=$_POST['home_team_select_ajax'][$i];
        $id_home_team=$input->check_number($id_home_team);
        
      }
      
      $visiting_team_select_type=$_POST['visiting_team_select_type'][$i];
      $visiting_team_select_type=$input->check_number($visiting_team_select_type);
      if ($visiting_team_select_type==1){
        $id_visiting_team=$_POST['id_visiting_team'][$i];
        $id_visiting_team=$input->check_number($id_visiting_team);
      }else{
        $id_visiting_team=$_POST['visiting_team_select_ajax'][$i];
        $id_visiting_team=$input->check_number($id_visiting_team);
      }
      
      $home_score=$_POST['home_score'][$i];
      if ($home_score=="") $home_score="NULL"; else $home_score=$input->check_number($home_score);
      $visiting_score=$_POST['visiting_score'][$i];
      if ($visiting_score=="") $visiting_score="NULL"; else $visiting_score=$input->check_number($visiting_score);
      $games_status=$_POST['games_status'][$i];
      $games_status=$input->check_number($games_status);
      
      $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&date_from='.$_POST['date_from'].'&date_to='.$_POST['date_to'].'&id_club='.$_POST['id_club'].'&id_season='.$id_season.'&id='.$id_league;
      if (!empty($date) and !empty($id_stage) and !empty($id_home_team) and !empty($id_visiting_team) and !empty($games_status)){
        
        $date=date("Y-m-d",strtotime($date));
        
        $query="UPDATE games SET last_update=NOW(),id_user=".$users->getIdUser().",id_club_home=".$id_home_team.",id_club_visiting=".$id_visiting_team.",round='".$round."',date='".$date."',time='".$time."',id_season=".$id_season.",id_league=".$id_league.",id_stage=".$id_stage.",home_score=".$home_score.",visiting_score=".$visiting_score.",games_status=".$games_status."
        WHERE id=".$id;
            
        //echo $query; 
        if (!$con->RunQuery($query)){
              //spatna DB
              $intError++;
        }else{
              $intCorrect++;
              $xml->generate_xml_games($id_season,$id_league,$id_stage);
              
              $id_standings=$con->GetSQLSingleResult("SELECT id as item FROM standings WHERE id_type=1 AND id_league=".$id_league." AND id_season=".$id_season." AND id_stage=".$id_stage);
              if (!empty($id_standings)) {
                generate_auto_standings($id_standings);
                $xml->generate_xml_standings($id_standings);
              }
              
        }
        
        
      }else{
          //špatná nebo chybějící vstupní data
          //$intError++;
      }
    }
      header("Location: games.php".Odkaz."&message=3".$strReturnData."&intCorrect=".$intCorrect."&intError=".$intError);
}          

//smazat zaznam / GAMES
    if ($_GET['action']=="delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&id_season='.$_GET['id_season'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        
        $query_xml="SELECT id_season,id_league,id_stage FROM games WHERE id=".$id;
        if ($con->GetQueryNum($query_xml)>0){
          $result_xml = $con->SelectQuery($query_xml);
          $write_xml = $result_xml->fetch_array();
          $id_season=$write_xml['id_season'];
          $id_league=$write_xml['id_league'];
          $id_stage=$write_xml['id_stage'];
        }
        
        $query="DELETE FROM games WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           $xml->generate_xml_games($id_season,$id_league,$id_stage);
           header("Location: games.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: games.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: games.php".Odkaz."&message=99".$strReturnData);}
    }


//pridat novy zaznam PERIODS
    if ($_POST['action']=="add_period" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $order=$_POST['order'];
    $order=$input->check_number($order);
    $score_home=$_POST['score_home'];
    if ($score_home=="") $score_home="NULL"; else $score_home=$input->check_number($score_home);
    $score_visiting=$_POST['score_visiting'];
    if ($score_visiting=="") $score_visiting="NULL"; else $score_visiting=$input->check_number($score_visiting);
    $shoots_home=$_POST['shoots_home'];
    if ($shoots_home=="") $shoots_home="NULL"; else $shoots_home=$input->check_number($shoots_home);
    $shoots_visiting=$_POST['shoots_visiting'];
    if ($shoots_visiting=="") $shoots_visiting="NULL"; else $shoots_visiting=$input->check_number($shoots_visiting);
    $id_type=$_POST['id_type'];
    $id_type=$input->check_number($id_type);
    $id_game=$_POST['id_game'];
    $id_game=$input->check_number($id_game);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&order='.$order.'&score_home='.$score_home.'&score_visiting='.$score_visiting.'&shoots_home='.$shoots_home.'&shoots_visiting='.$shoots_visiting.'&id_type='.$id_type.'&id='.$id_game;
    
    if (!empty($order) and !empty($id_type) and !empty($id_game)){
          $query="INSERT INTO games_periods (periods_order,score_home,score_visiting,shoots_home,shoots_visiting,id_type,id_game)
                  VALUES (".$order.",".$score_home.",".$score_visiting.",".$shoots_home.",".$shoots_visiting.",".$id_type.",".$id_game.")
            ";
           // echo $query;
           if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=98;
           }else{
              
              
           }
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: games_info.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         
         $query_xml="SELECT id_season,id_league,id_stage FROM games WHERE id=".$id_game;
         if ($con->GetQueryNum($query_xml)>0){
          $result_xml = $con->SelectQuery($query_xml);
          $write_xml = $result_xml->fetch_array();
          $id_season=$write_xml['id_season'];
          $id_league=$write_xml['id_league'];
          $id_stage=$write_xml['id_stage'];
         }
         $xml->generate_xml_games($id_season,$id_league,$id_stage);
         
         header("Location: games_info.php".Odkaz."&message=1".$strReturnData);
    }
  }
  
//smazat zaznam / PERIODS
    if ($_GET['action']=="delete_period" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        
        $intGameID=$con->GetSQLSingleResult("SELECT id_game as item FROM games_periods WHERE id=".$id);
        $query_xml="SELECT id_season,id_league,id_stage FROM games WHERE id=".$intGameID;
              if ($con->GetQueryNum($query_xml)>0){
                $result_xml = $con->SelectQuery($query_xml);
                $write_xml = $result_xml->fetch_array();
                $id_season=$write_xml['id_season'];
                $id_league=$write_xml['id_league'];
                $id_stage=$write_xml['id_stage'];
            }
        
        $query="DELETE FROM games_periods WHERE id=".$id;
        if ($con->RunQuery($query)==true){
          $xml->generate_xml_games($id_season,$id_league,$id_stage);
           header("Location: games_info.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: games_info.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: games_info.php".Odkaz."&message=99".$strReturnData);}
    }

//upravit zaznam  PERIODS
    if ($_POST['action']=="update_period" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $number=$_POST['number'];
    $id_game=$_POST['id_game'];
    $id_game=$input->check_number($id_game);
    
    for ($i=0; $i<=($number); $i++){
      
      $id=$_POST['id'][$i];
      $id=$input->check_number($id);
      
      $order=$_POST['order'][$i];
      $order=$input->check_number($order);
      $score_home=$_POST['score_home'][$i];
      if ($score_home=="") $score_home="NULL"; else $score_home=$input->check_number($score_home);
      $score_visiting=$_POST['score_visiting'][$i];
      if ($score_visiting=="") $score_visiting="NULL"; else $score_visiting=$input->check_number($score_visiting);
      $shoots_home=$_POST['shoots_home'][$i];
      if ($shoots_home=="") $shoots_home="NULL"; else $shoots_home=$input->check_number($shoots_home);
      $shoots_visiting=$_POST['shoots_visiting'][$i];
      if ($shoots_visiting=="") $shoots_visiting="NULL"; else $shoots_visiting=$input->check_number($shoots_visiting);
      $id_type=$_POST['id_type'][$i];
      $id_type=$input->check_number($id_type);
    
      
      $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_game;
      
      if (!empty($id) and !empty($order) and !empty($id_type) and !empty($id_game)){
        
        $query="UPDATE games_periods SET periods_order=".$order.",score_home=".$score_home.",score_visiting=".$score_visiting.",shoots_home=".$shoots_home.",shoots_visiting=".$shoots_visiting.",id_type=".$id_type." WHERE id=".$id;
            
        //echo $query; 
        if (!$con->RunQuery($query)){
              //spatna DB
              $intError++;
        }else{
              
              $query_xml="SELECT id_season,id_league,id_stage FROM games WHERE id=".$id_game;
              if ($con->GetQueryNum($query_xml)>0){
                $result_xml = $con->SelectQuery($query_xml);
                $write_xml = $result_xml->fetch_array();
                $id_season=$write_xml['id_season'];
                $id_league=$write_xml['id_league'];
                $id_stage=$write_xml['id_stage'];
              }
              $xml->generate_xml_games($id_season,$id_league,$id_stage);
         
              $intCorrect++;
        }
        
        
      }else{
          //špatná nebo chybějící vstupní data
          //$intError++;
      }
    }
      header("Location: games_info.php".Odkaz."&message=3".$strReturnData."&intCorrect=".$intCorrect."&intError=".$intError);
}

//pridat novy zaznam GOALS
    if ($_POST['action']=="add_goal" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $goal_time_min=$_POST['goal_time_min'];
    $goal_time_min=$input->check_number($goal_time_min);
    $goal_time_sec=$_POST['goal_time_sec'];
    $goal_time_sec=$input->check_number($goal_time_sec);
    
    if (!empty($_POST['scorer_home'])) $scorer=$_POST['scorer_home']; else $scorer=$_POST['scorer_visiting'];
    $scorer=$input->check_number($scorer);
    if (!empty($_POST['assist_1_home'])) $assist_1=$_POST['assist_1_home']; else $assist_1=$_POST['assist_1_visiting'];
    if ($assist_1=="") $assist_1="NULL"; else $assist_1=$input->check_number($assist_1);
    if (!empty($_POST['assist_2_home'])) $assist_2=$_POST['assist_2_home']; else $assist_2=$_POST['assist_2_visiting'];
    if ($assist_2=="") $assist_2="NULL"; else $assist_2=$input->check_number($assist_2);
    $id_goal_type=$_POST['id_goal_type'];
    $id_goal_type=$input->check_number($id_goal_type);
    $id_game=$_POST['id_game'];
    $id_game=$input->check_number($id_game);
    $id_club=$_POST['id_club'];
    $id_club=$input->check_number($id_club);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_game.'&tab=1';
    if (!empty($scorer) and !empty($id_goal_type) and !empty($id_game) and !empty($id_club)){
          $query="INSERT INTO games_goals (goal_time_min,goal_time_sec,scorer,assist_1,assist_2,id_goal_type,id_game,id_club)
                  VALUES (".$goal_time_min.",".$goal_time_sec.",".$scorer.",".$assist_1.",".$assist_2.",".$id_goal_type.",".$id_game.",".$id_club.")
            ";
           // echo $query;
           if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=98;
           }else{
             
             
             //automaticke pridani scorers assist atd do podrobnych stats
             $id_season=$con->GetSQLSingleResult("SELECT id_season as item FROM games WHERE id=".$id_game);
             $id_league=$con->GetSQLSingleResult("SELECT id_league as item FROM games WHERE id=".$id_game);
             $boolStatsLock=get_lock($id_season,$id_league,$id_club);
             
             
             if (!empty($scorer)){
                $id_stats=get_id_stats($id_game,$scorer,$id_club);
                $query_check="SELECT id FROM games_stats WHERE id_player=".$scorer." AND id_club=".$id_club." AND id_game=".$id_game;
                if ($con->GetQueryNum($query_check)==0){
                    $query_insert="INSERT INTO games_stats (id_player,id_game,id_club,goals) VALUES (".$scorer.",".$id_game.",".$id_club.",1)";
                    $con->RunQuery($query_insert);
                
                    if (!$boolStatsLock){
                      $games->WriteStats($id_stats,'games','+',1);
                    }
                    
                }else{
                    
                   $result_check = $con->SelectQuery($query_check);
                   $write_check = $result_check->fetch_array();
                   $intGoals=$con->GetSQLSingleResult("SELECT goals as item FROM games_stats WHERE id=".$write_check['id']);
                   $intGoals=$intGoals+1;
                   $query_update="UPDATE games_stats SET goals=".$intGoals." WHERE id=".$write_check['id'];
                   //echo $query_update; 
                   $con->RunQuery($query_update);
                   
                }
                
                if (!$boolStatsLock){
                    $games->WriteStats($id_stats,'goals','+',1);
                }
                
             }
             
             if (!empty($assist_1)){
                $id_stats=get_id_stats($id_game,$assist_1,$id_club);
                $query_check="SELECT id FROM games_stats WHERE id_player=".$assist_1." AND id_club=".$id_club." AND id_game=".$id_game;
                if ($con->GetQueryNum($query_check)==0){
                    $query_insert="INSERT INTO games_stats (id_player,id_game,id_club,assist) VALUES (".$assist_1.",".$id_game.",".$id_club.",1)";
                    $con->RunQuery($query_insert);
                
                    if (!$boolStatsLock){
                      $games->WriteStats($id_stats,'games','+',1);
                    }
                    
                }else{
                    
                   $result_check = $con->SelectQuery($query_check);
                   $write_check = $result_check->fetch_array();
                   $intGoals=$con->GetSQLSingleResult("SELECT assist as item FROM games_stats WHERE id=".$write_check['id']);
                   $intGoals=$intGoals+1;
                   $query_update="UPDATE games_stats SET assist=".$intGoals." WHERE id=".$write_check['id'];
                   //echo $query_update; 
                   $con->RunQuery($query_update);
                   
                }
                
                if (!$boolStatsLock){
                    $games->WriteStats($id_stats,'assist','+',1);
                }
                
             }
             
             if (!empty($assist_2)){
                $id_stats=get_id_stats($id_game,$assist_2,$id_club);
                $query_check="SELECT id FROM games_stats WHERE id_player=".$assist_2." AND id_club=".$id_club." AND id_game=".$id_game;
                if ($con->GetQueryNum($query_check)==0){
                    $query_insert="INSERT INTO games_stats (id_player,id_game,id_club,assist) VALUES (".$assist_2.",".$id_game.",".$id_club.",1)";
                    $con->RunQuery($query_insert);
                
                    if (!$boolStatsLock){
                      $games->WriteStats($id_stats,'games','+',1);
                    }
                    
                }else{
                    
                   $result_check = $con->SelectQuery($query_check);
                   $write_check = $result_check->fetch_array();
                   $intGoals=$con->GetSQLSingleResult("SELECT assist as item FROM games_stats WHERE id=".$write_check['id']);
                   $intGoals=$intGoals+1;
                   $query_update="UPDATE games_stats SET assist=".$intGoals." WHERE id=".$write_check['id'];
                   //echo $query_update; 
                   $con->RunQuery($query_update);
                   
                }
                
                if (!$boolStatsLock){
                    $games->WriteStats($id_stats,'assist','+',1);
                }
                
             }
             
             $query_xml="SELECT id_season,id_league,id_stage FROM games WHERE id=".$id_game;
              if ($con->GetQueryNum($query_xml)>0){
                $result_xml = $con->SelectQuery($query_xml);
                $write_xml = $result_xml->fetch_array();
                $id_season=$write_xml['id_season'];
                $id_league=$write_xml['id_league'];
                $id_stage=$write_xml['id_stage'];
              }
              $xml->generate_xml_games($id_season,$id_league,$id_stage); 
              
           }
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: games_info.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: games_info.php".Odkaz."&message=4".$strReturnData);
    }
  }
  
//smazat zaznam / GOALS
    if ($_GET['action']=="delete_goal" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'].'&tab=1';
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        
        $id_game=$con->GetSQLSingleResult("SELECT id_game as item FROM games_goals WHERE id=".$id);
        
        $query_xml="SELECT id_season,id_league,id_stage FROM games WHERE id=".$id_game;
              if ($con->GetQueryNum($query_xml)>0){
                $result_xml = $con->SelectQuery($query_xml);
                $write_xml = $result_xml->fetch_array();
                $id_season=$write_xml['id_season'];
                $id_league=$write_xml['id_league'];
                $id_stage=$write_xml['id_stage'];
            }
            
        
        $query_stats="SELECT id_game,id_club,scorer,assist_1,assist_2 FROM games_goals WHERE id=".$id;
              if ($con->GetQueryNum($query_stats)>0){
                $result_stats = $con->SelectQuery($query_stats);
                $write_stats = $result_stats->fetch_array();
                
                $id_club=$write_stats['id_club'];
                $scorer=$write_stats['scorer'];
                $assist_1=$write_stats['assist_1'];
                $assist_2=$write_stats['assist_2'];
                
                //automaticke update scorers assist atd do podrobnych stats
                $id_season=$con->GetSQLSingleResult("SELECT id_season as item FROM games WHERE id=".$id_game);
                $id_league=$con->GetSQLSingleResult("SELECT id_league as item FROM games WHERE id=".$id_game);
                $boolStatsLock=get_lock($id_season,$id_league,$id_club);
              
                if (!empty($scorer)){
                
                    $id_game_stats=$con->GetSQLSingleResult("SELECT id as item FROM games_stats WHERE id_player=".$scorer." AND id_club=".$id_club." AND id_game=".$id_game);
                    $intGoals=$con->GetSQLSingleResult("SELECT goals as item FROM games_stats WHERE id=".$id_game_stats);
                    $intGoals=$intGoals-1;
                    $query_update="UPDATE games_stats SET goals=".$intGoals." WHERE id=".$id_game_stats;
                    $con->RunQuery($query_update);
                    
                    if (!$boolStatsLock){
                      $id_stats=get_id_stats($id_game,$scorer,$id_club);
                      $games->WriteStats($id_stats,'goals','-',1);
                    }
                    
                }
                
                if (!empty($assist_1)){
                
                    $id_game_stats=$con->GetSQLSingleResult("SELECT id as item FROM games_stats WHERE id_player=".$assist_1." AND id_club=".$id_club." AND id_game=".$id_game);
                    $intGoals=$con->GetSQLSingleResult("SELECT assist as item FROM games_stats WHERE id=".$id_game_stats);
                    $intGoals=$intGoals-1;
                    $query_update="UPDATE games_stats SET assist=".$intGoals." WHERE id=".$id_game_stats;
                    $con->RunQuery($query_update);
                    
                    if (!$boolStatsLock){
                      $id_stats=get_id_stats($id_game,$assist_1,$id_club);
                      $games->WriteStats($id_stats,'assist','-',1);
                    }
                    
                }
                
                if (!empty($assist_2)){
                
                    $id_game_stats=$con->GetSQLSingleResult("SELECT id as item FROM games_stats WHERE id_player=".$assist_2." AND id_club=".$id_club." AND id_game=".$id_game);
                    $intGoals=$con->GetSQLSingleResult("SELECT assist as item FROM games_stats WHERE id=".$id_game_stats);
                    $intGoals=$intGoals-1;
                    $query_update="UPDATE games_stats SET assist=".$intGoals." WHERE id=".$id_game_stats;
                    $con->RunQuery($query_update);
                    
                    if (!$boolStatsLock){
                      $id_stats=get_id_stats($id_game,$assist_2,$id_club);
                      $games->WriteStats($id_stats,'assist','-',1);
                    }
                    
                }
              
              }
            
        
        $query="DELETE FROM games_goals WHERE id=".$id;
        if ($con->RunQuery($query)==true){
              
              $xml->generate_xml_games($id_season,$id_league,$id_stage);
              header("Location: games_info.php".Odkaz."&message=5".$strReturnData);
        }
        else{
            header("Location: games_info.php".Odkaz."&message=98".$strReturnData);
        } 
      }else{header("Location: games_info.php".Odkaz."&message=99".$strReturnData);}
    }

//upravit zaznam  GOALS
    if ($_POST['action']=="update_goal" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $number=$_POST['number'];
    $id_game=$_POST['id_game'];
    $id_game=$input->check_number($id_game);
    
    for ($i=0; $i<=($number); $i++){
      
      $id=$_POST['id'][$i];
      $id=$input->check_number($id);
      
      $goal_time_min=$_POST['goal_time_min'][$i];
      $goal_time_min=$input->check_number($goal_time_min);
      $goal_time_sec=$_POST['goal_time_sec'][$i];
      $goal_time_sec=$input->check_number($goal_time_sec);
      
      $scorer=$_POST['scorer'][$i];
      $scorer=$input->check_number($scorer);
      $assist_1=$_POST['assist_1'][$i];
      if ($assist_1=="") $assist_1="NULL"; else $assist_1=$input->check_number($assist_1);
      $assist_2=$_POST['assist_2'][$i];
      if ($assist_2=="") $assist_2="NULL"; else $assist_2=$input->check_number($assist_2);
      $id_goal_type=$_POST['id_goal_type'][$i];
      $id_goal_type=$input->check_number($id_goal_type);
      $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_game.'&tab=1';
      if (!empty($id) and !empty($scorer) and !empty($id_goal_type)){
        
        $query_sub="SELECT * FROM games_goals WHERE id=".$id;
          if ($con->GetQueryNum($query_sub)>0){
            $result_sub = $con->SelectQuery($query_sub);
            $write_sub = $result_sub->fetch_array();
            
            $scorer_old=$write_sub['scorer'];
            $assist_1_old=$write_sub['assist_1'];
            $assist_2_old=$write_sub['assist_2'];
            $id_club=$write_sub['id_club'];
            
            $boolStatsLock=get_lock($id_season,$id_league,$id_club);
            
            if (!empty($scorer_old)){
              $query_stats="SELECT id,goals FROM games_stats WHERE id_player=".$scorer_old." AND id_club=".$id_club." AND id_game=".$id_game;
              $result_stats = $con->SelectQuery($query_stats);
              $write_stats = $result_stats->fetch_array();
              $intGoals=$write_stats['goals']-1;
              $query_update="UPDATE games_stats SET goals=".$intGoals." WHERE id=".$write_stats['id'];
              $con->RunQuery($query_update);
              
              $id_stats=get_id_stats($id_game,$scorer_old,$id_club);
              if (!$boolStatsLock){$games->WriteStats($id_stats,'goals','-',1);}
            }
            
            if (!empty($assist_1_old)){
              $query_stats="SELECT id,assist FROM games_stats WHERE id_player=".$assist_1_old." AND id_club=".$id_club." AND id_game=".$id_game;
              $result_stats = $con->SelectQuery($query_stats);
              $write_stats = $result_stats->fetch_array();
              $intGoals=$write_stats['assist']-1;
              $query_update="UPDATE games_stats SET assist=".$intGoals." WHERE id=".$write_stats['id'];
              $con->RunQuery($query_update);
              
              $id_stats=get_id_stats($id_game,$assist_1_old,$id_club);
              if (!$boolStatsLock){$games->WriteStats($id_stats,'assist','-',1);}
            }
            
            if (!empty($assist_2_old)){
              $query_stats="SELECT id,assist FROM games_stats WHERE id_player=".$assist_2_old." AND id_club=".$id_club." AND id_game=".$id_game;
              $result_stats = $con->SelectQuery($query_stats);
              $write_stats = $result_stats->fetch_array();
              $intGoals=$write_stats['assist']-1;
              $query_update="UPDATE games_stats SET assist=".$intGoals." WHERE id=".$write_stats['id'];
              $con->RunQuery($query_update);
              
              $id_stats=get_id_stats($id_game,$assist_2_old,$id_club);
              if (!$boolStatsLock){$games->WriteStats($id_stats,'assist','-',1);}
            }
            
        }
        
        if (!empty($scorer)){
          
          $id_stats=get_id_stats($id_game,$scorer,$id_club);
          $query_stats="SELECT id,goals FROM games_stats WHERE id_player=".$scorer." AND id_club=".$id_club." AND id_game=".$id_game;
          if ($con->GetQueryNum($query_stats)==0){
              $query_insert="INSERT INTO games_stats (id_player,id_game,id_club,goals) VALUES (".$scorer.",".$id_game.",".$id_club.",1)";
              $con->RunQuery($query_insert);
              if (!$boolStatsLock){
                  $games->WriteStats($id_stats,'games','+',1);
              }
          }else{
          
            $result_stats = $con->SelectQuery($query_stats);
            $write_stats = $result_stats->fetch_array();
            $intGoals=$write_stats['goals']+1;
            $query_update="UPDATE games_stats SET goals=".$intGoals." WHERE id=".$write_stats['id'];
            $con->RunQuery($query_update);
            
          }
          if (!$boolStatsLock){$games->WriteStats($id_stats,'goals','+',1);}
          
        }  
        
        if (!empty($assist_1)){
          
          $id_stats=get_id_stats($id_game,$assist_1,$id_club);
          $query_stats="SELECT id,assist FROM games_stats WHERE id_player=".$assist_1." AND id_club=".$id_club." AND id_game=".$id_game;
          if ($con->GetQueryNum($query_stats)==0){
              $query_insert="INSERT INTO games_stats (id_player,id_game,id_club,assist) VALUES (".$assist_1.",".$id_game.",".$id_club.",1)";
              $con->RunQuery($query_insert);
              if (!$boolStatsLock){
                  $games->WriteStats($id_stats,'games','+',1);
              }
          }else{
          
            $result_stats = $con->SelectQuery($query_stats);
            $write_stats = $result_stats->fetch_array();
            $intGoals=$write_stats['assist']+1;
            $query_update="UPDATE games_stats SET assist=".$intGoals." WHERE id=".$write_stats['id'];
            $con->RunQuery($query_update);
            
          }
          if (!$boolStatsLock){$games->WriteStats($id_stats,'assist','+',1);}
          
        } 
        
        if (!empty($assist_2)){
          
          $id_stats=get_id_stats($id_game,$assist_2,$id_club);
          $query_stats="SELECT id,assist FROM games_stats WHERE id_player=".$assist_2." AND id_club=".$id_club." AND id_game=".$id_game;
          if ($con->GetQueryNum($query_stats)==0){
              $query_insert="INSERT INTO games_stats (id_player,id_game,id_club,assist) VALUES (".$assist_2.",".$id_game.",".$id_club.",1)";
              $con->RunQuery($query_insert);
              if (!$boolStatsLock){
                  $games->WriteStats($id_stats,'games','+',1);
              }
          }else{
          
            $result_stats = $con->SelectQuery($query_stats);
            $write_stats = $result_stats->fetch_array();
            $intGoals=$write_stats['assist']+1;
            $query_update="UPDATE games_stats SET assist=".$intGoals." WHERE id=".$write_stats['id'];
            $con->RunQuery($query_update);
            
          }
          if (!$boolStatsLock){$games->WriteStats($id_stats,'assist','+',1);}
          
        }  
        
        
        
        $query="UPDATE games_goals SET goal_time_min=".$goal_time_min.",goal_time_sec=".$goal_time_sec.",scorer=".$scorer.",assist_1=".$assist_1.",assist_2=".$assist_2.",id_goal_type=".$id_goal_type." WHERE id=".$id;
            
        //echo $query; 
        if (!$con->RunQuery($query)){
              //spatna DB
              $intError++;
        }else{
              $intCorrect++;
              
              $query_xml="SELECT id_season,id_league,id_stage FROM games WHERE id=".$id_game;
              if ($con->GetQueryNum($query_xml)>0){
                $result_xml = $con->SelectQuery($query_xml);
                $write_xml = $result_xml->fetch_array();
                $id_season=$write_xml['id_season'];
                $id_league=$write_xml['id_league'];
                $id_stage=$write_xml['id_stage'];
              }
              $xml->generate_xml_games($id_season,$id_league,$id_stage); 
        }
        
        
      }else{
          //špatná nebo chybějící vstupní data
          //$intError++;
      }
    }
      header("Location: games_info.php".Odkaz."&message=6".$strReturnData."&intCorrect=".$intCorrect."&intError=".$intError);
}               


//upravit zaznam  INFO
    if ($_POST['action']=="update_info" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $id_arena=$_POST['id_arena'];
    $id_arena=$input->check_number($id_arena);
    $referees=$_POST['referees'];
    $referees=$input->valid_text($referees,true,true);
    $spectators=$_POST['spectators'];
    if ($spectators=="") $spectators="NULL"; else $spectators=$input->check_number($spectators);
    $id_game=$_POST['id_game'];
    $id_game=$input->check_number($id_game);
    
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_game.'&tab=3';
    
    if (!empty($id_game)){
          $query="UPDATE games SET id_arena=".$id_arena.",referees='".$referees."',spectators=".$spectators." WHERE id=".$id_game;
           // echo $query;
           if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=98;
           }else{
              
              $info=$_POST['info'];
              $info=$input->valid_text($info,true,false);
              if (!empty($info)){
                
                $id_info=$con->GetSQLSingleResult("SELECT id_game as item FROM games_detail WHERE id_game=".$id_game);
                if (empty($id_info)){
                  //zapis reportu
                  $query="INSERT INTO games_detail (id_game,text) VALUES (".$id_game.",'".$info."')";
                  $con->RunQuery($query);
                }else{
                  $query="UPDATE games_detail SET text='".$info."' WHERE id_game=".$id_game;
                  $con->RunQuery($query);
                }
                
              }
              
              
           }
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: games_info.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: games_info.php".Odkaz."&message=7".$strReturnData);
    }
  }

  //pridat zaznam  ROSTER
    if ($_POST['action']=="add_roster" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $number=$_POST['number'];
    $id_game=$_POST['id_game'];
    $id_game=$input->check_number($id_game);
    
    $id_season=$_POST['id_season'];
    $id_league=$_POST['id_league'];
    $id_club_home=$_POST['id_club_home'];
    $id_club_visiting=$_POST['id_club_visiting'];
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_game.'&tab=2';
    //echo $strReturnData; 
    for ($i=0; $i<$number; $i++){
      
      $id_post=$_POST['id'][$i];
      $id_post=explode("_",$id_post);
      
      $id=$input->check_number($id_post[0]);
      $id_club=$input->check_number($id_post[1]);
      
      if (!empty($id_game) and !empty($id) and !empty($id_club)){
      
      $query="INSERT INTO games_stats (id_player,id_game,id_club)
                  VALUES (".$id.",".$id_game.",".$id_club.")
            ";      
        //echo $query; 
        if (!$con->RunQuery($query)){
              //spatna DB
              $intError++;
        }else{
              $intCorrect++;
              
              $boolStatsLock=get_lock($id_season,$id_league,$id_club);
              if (!$boolStatsLock){
              
                $id_stats=get_id_stats($id_game,$id,$id_club);
                $games->WriteStats($id_stats,'games','+',1);
                $games->WriteStats($id_stats,'games_dressed','+',1);
              
                if ($games->GetShotout($id_game,$id_club)==true){
                  $games->WriteStats($id_stats,'shotouts','+',1);
                }
              
              }
              
        }
        
        
      }else{
          //špatná nebo chybějící vstupní data
          //$intError++;
      }
    }
      header("Location: games_info.php".Odkaz."&message=8".$strReturnData."&intCorrect=".$intCorrect."&intError=".$intError);
}               


//smazat zaznam / STATS
    if ($_GET['action']=="delete_stats" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'].'&tab=2';
    
      if (isset($_GET['id'])){
        
        $id=$_GET['id'];
        
        //$intIDstageDefault=$con->GetSQLSingleResult("SELECT id_detault as item FROM games_stages WHERE id=".$id_stage);
        
         $query_sub="SELECT * FROM games_stats WHERE id=".$id;
         $result_sub = $con->SelectQuery($query_sub);
         $write_sub = $result_sub->fetch_array();
         $id_game=$write_sub['id_game'];
         $id_club=$write_sub['id_club'];
         $id_player=$write_sub['id_player'];
         $goals=$write_sub['goals'];
         $assist=$write_sub['assist'];
         $plusminus=$write_sub['plusminus'];
         $games_dressed=$write_sub['games_dressed'];
         $shoots_against=$write_sub['shoots_against'];
         $goals_against=$write_sub['goals_against'];
         $penalty=$write_sub['penalty'];
         $minutes=$write_sub['minutes'];
         
         $query_sub="SELECT id_league,id_season,id_stage FROM games WHERE id=".$id_game;
         $result_sub = $con->SelectQuery($query_sub);
         $write_sub = $result_sub->fetch_array();
         $id_season=$write_sub['id_season'];
         $id_league=$write_sub['id_league'];
         $id_stage=$write_sub['id_stage'];
         $id_stage_orig=$id_stage;
         
         $boolStatsLock=get_lock($id_season,$id_league,$id_club);
         if (!$boolStatsLock){
         
          //AUTOMATICKE POCITANI STATS
          $id_stats=get_id_stats($id_game,$id_player,$id_club);
          if ($goals<>""){$games->WriteStats($id_stats,'goals','-',$goals);}
          if ($assist<>""){$games->WriteStats($id_stats,'assist','-',$assist);}
          if ($plusminus<>""){$games->WriteStats($id_stats,'plusminus','-',$plusminus);}
          if ($penalty<>""){$games->WriteStats($id_stats,'penalty','-',$penalty);}
          //if ($games_dressed<>""){$games->WriteStats($id_stats,'games_dressed','-',$games_dressed);}
          if ($minutes<>""){$games->WriteStats($id_stats,'minutes','-',$minutes);}
          $games->WriteStats($id_stats,'games_dressed','-',1);
          
          if ($games->GetShotout($id_game,$id_club)==true and $games_dressed==0){
            $games->WriteStats($id_stats,'shotouts','-',1); 
          }
          if ($games_dressed==0){
            $games->WriteStats($id_stats,'games','-',1);
          }
          
         }
         
         $intIDstageDefault=$con->GetSQLSingleResult("SELECT id_detault as item FROM games_stages WHERE id=".$id_stage);
         if (!empty($intIDstageDefault)) {$id_stage=$intIDstageDefault;}
        
        $query="DELETE FROM games_stats WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           
              if (!$boolStatsLock){
              $avg="";
              $percent="";
              //counting AVG a PER
              $query_sub="SELECT sum(minutes) as minutes,sum(shoots_against) as shoots_against,sum(goals_against) as goals_against FROM games_stats 
                    INNER JOIN games ON games_stats.id_game=games.id
                    WHERE id_player=".$id_player." AND id_season=".$id_season." AND id_league=".$id_league." AND id_club=".$id_club." AND id_stage=".$id_stage_orig."
              "; 
              $result_sub = $con->SelectQuery($query_sub);
              $write_sub = $result_sub->fetch_array();
              
              $intGame=$con->GetSQLSingleResult("SELECT count(*) as item FROM games_stats INNER JOIN games ON games_stats.id_game=games.id WHERE id_player=".$id_player." AND id_season=".$id_season." AND id_league=".$id_league." AND id_club=".$id_club." AND id_stage=".$id_stage_orig);
              
              if (!empty($intGame) and !empty($write_sub['shoots_against']) and !empty($write_sub['goals_against'])){
                
                if (empty($write_sub['minutes'])) $minutes=60*$intGame; else  $minutes=$write_sub['minutes'];
                
                
                if ($write_sub['shoots_against']-$write_sub['goals_against']<>0){
                  $percent=100/($write_sub['shoots_against']/($write_sub['shoots_against']-$write_sub['goals_against']));
                  $percent=$percent/100;
                   
                }else{
                   $percent=0;
                }
                
                $avg=($write_sub['goals_against']/($minutes/60));
              }
              else{
                
                if ($write_sub['goals_against']==0 and $write_sub['shoots_against']>0) {
                  $percent=1;
                  $avg=0;
                 } 
                else {
                  $percent=0;
                  $avg="null";
                }
                
              }
              $query="UPDATE stats SET AVG=".$avg.",PCE=".$percent." WHERE id=".$id_stats;
              $con->RunQuery($query);
   
              }
           
           
           header("Location: games_info.php".Odkaz."&message=9".$strReturnData);
        }
        else{header("Location: games_info.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: games_info.php".Odkaz."&message=99".$strReturnData);}
    }

//upravit zaznam  STATS
    if ($_POST['action']=="update_stats" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $number=$_POST['number'];
    $id_game=$_POST['id_game'];
    $id_game=$input->check_number($id_game);
    
    $id_season=$_POST['id_season'];
    $id_season=$input->check_number($id_season);
    $id_league=$_POST['id_league'];
    $id_league=$input->check_number($id_league);
    $id_stage=$_POST['id_stage'];
    $id_stage=$input->check_number($id_stage);
    $id_stage_orig=$id_stage;
    
    for ($i=0; $i<$number; $i++){
      
      $id=$_POST['id'][$i];
      $id=$input->check_number($id);
      
      $id_club=$_POST['id_club'][$i];
      $id_club=$input->check_number($id_club);
      
      $goals=$_POST['goals'][$i];
      if ($goals=="") $goals="NULL"; else $goals=$input->check_number($goals);
      $assist=$_POST['assist'][$i];
      if ($assist=="") $assist="NULL"; else $assist=$input->check_number($assist);
      $plusminus=$_POST['plusminus'][$i];
      if ($plusminus=="") $plusminus="NULL"; else $plusminus=$input->check_number($plusminus);
      $games_dressed=$_POST['games_dressed'][$i];
      $games_dressed=$input->check_number($games_dressed);
      $shoots_against=$_POST['shoots_against'][$i];
      if ($shoots_against=="") $shoots_against="NULL"; else $shoots_against=$input->check_number($shoots_against);
      $goals_against=$_POST['goals_against'][$i];
      if ($goals_against=="") $goals_against="NULL"; else $goals_against=$input->check_number($goals_against);
      $penalty=$_POST['penalty'][$i];
      if ($penalty=="") $penalty="NULL"; else $penalty=$input->check_number($penalty);
      $minutes=$_POST['minutes'][$i];
      if ($minutes=="") $minutes="NULL"; else $minutes=$input->check_number($minutes);
            
      $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_game.'&tab=2';
      if (!empty($id) and !empty($id_club) and !empty($id_season) and !empty($id_league) and !empty($id_stage)){
        
        $boolStatsLock=get_lock($id_season,$id_league,$id_club);
      
        if (!$boolStatsLock){
          
          //if default stage set stage as default
          $query_sub="SELECT * FROM games_stats WHERE id=".$id;
          if ($con->GetQueryNum($query_sub)>0){
            $result_sub = $con->SelectQuery($query_sub);
            $write_sub = $result_sub->fetch_array();
        
            $goals_old=$write_sub['goals'];
            $assist_old=$write_sub['assist'];
            $plusminus_old=$write_sub['plusminus'];
            $games_dressed_old=$write_sub['games_dressed'];
            $penalty_old=$write_sub['penalty'];
            $minutes_old=$write_sub['minutes'];
            $id_player=$write_sub['id_player'];
          }
          
          $id_stats=get_id_stats($id_game,$id_player,$id_club);
          
          //odecteni starych stats
          if ($goals_old<>""){$games->WriteStats($id_stats,'goals','-',$goals_old);}
          if ($assist_old<>""){$games->WriteStats($id_stats,'assist','-',$assist_old);}
          if ($plusminus_old<>""){$games->WriteStats($id_stats,'plusminus','-',$plusminus_old);}
          if ($penalty_old<>""){$games->WriteStats($id_stats,'penalty','-',$penalty_old);}
          //if ($games_dressed_old<>""){$games->WriteStats($id_stats,'games_dressed','-',$games_dressed_old);}
          if ($minutes_old<>""){$games->WriteStats($id_stats,'minutes','-',$minutes_old);}
          
          //zmena shotoutu
          if ($games_dressed<>$games_dressed_old){
            
            if ($games_dressed==1){
              $games->WriteStats($id_stats,'games','-',1);
            }else{
              $games->WriteStats($id_stats,'games','+',1);
            }
            
            $boolShotOut=$games->GetShotout($id_game,$id_club);
            if ($games_dressed==0 and $boolShotOut){
                $games->WriteStats($id_stats,'shotouts','+',1);  
            }  
            
            if ($games_dressed==1 and $boolShotOut){
                $games->WriteStats($id_stats,'shotouts','-',1);  
            }
          }
          
          //pricteni novych stats
          if ($goals<>""){$games->WriteStats($id_stats,'goals','+',$goals);}
          if ($assist<>""){$games->WriteStats($id_stats,'assist','+',$assist);}
          if ($plusminus<>""){$games->WriteStats($id_stats,'plusminus','+',$plusminus);}
          if ($penalty<>""){$games->WriteStats($id_stats,'penalty','+',$penalty);}
          //if ($games_dressed<>""){$games->WriteStats($id_stats,'games_dressed','+',$games_dressed);}
          if ($minutes<>""){$games->WriteStats($id_stats,'minutes','+',$minutes);}
          
          
          $intIDstageDefault=$con->GetSQLSingleResult("SELECT id_detault as item FROM games_stages WHERE id=".$id_stage);
          if (!empty($intIDstageDefault)) {$id_stage=$intIDstageDefault;}
          
          
          
        }
        
        $query="UPDATE games_stats SET goals=".$goals.",assist=".$assist.",plusminus=".$plusminus.",games_dressed=".$games_dressed.",shoots_against=".$shoots_against.",goals_against=".$goals_against.",penalty=".$penalty.",minutes=".$minutes." WHERE id=".$id;
            
        //echo $query; 
        if (!$con->RunQuery($query)){
              //spatna DB
              $intError++;
        }else{
              $intCorrect++;
              
              if (!$boolStatsLock){
              $avg="";
              $percent="";
              //counting AVG a PER
              $query_sub="SELECT sum(minutes) as minutes,sum(shoots_against) as shoots_against,sum(goals_against) as goals_against FROM games_stats 
                    INNER JOIN games ON games_stats.id_game=games.id
                    WHERE id_player=".$id_player." AND id_season=".$id_season." AND id_league=".$id_league." AND id_club=".$id_club." AND id_stage=".$id_stage_orig."
              ";
              
              //echo $query_sub; 
              $result_sub = $con->SelectQuery($query_sub);
              $write_sub = $result_sub->fetch_array();
              $intGame=$con->GetSQLSingleResult("SELECT count(*) as item FROM games_stats INNER JOIN games ON games_stats.id_game=games.id WHERE id_player=".$id_player." AND id_season=".$id_season."  AND id_club=".$id_club." AND id_league=".$id_league." AND id_stage=".$id_stage_orig);
              if (!empty($intGame) and !empty($write_sub['shoots_against']) and !empty($write_sub['goals_against'])){
                
                if (empty($write_sub['minutes'])) $minutes=60*$intGame; else  $minutes=$write_sub['minutes'];
                
                
                if ($write_sub['shoots_against']-$write_sub['goals_against']<>0){
                  $percent=100/($write_sub['shoots_against']/($write_sub['shoots_against']-$write_sub['goals_against']));
                  $percent=$percent/100;
                   
                }else{
                   $percent=0;
                }
                
                $avg=($write_sub['goals_against']/($minutes/60));
              }
              else{
                
                if ($write_sub['goals_against']==0 and $write_sub['shoots_against']>0) {
                  $percent=1;
                  $avg=0;
                 } 
                else {
                  $percent=0;
                  $avg="null";
                }
                
              }
              $query="UPDATE stats SET AVG=".$avg.",PCE=".$percent." WHERE id=".$id_stats;
              $con->RunQuery($query);
              
              }
              
              
        }
        
        
      }else{
          //špatná nebo chybějící vstupní data
          //$intError++;
      }
    }
      header("Location: games_info.php".Odkaz."&message=10".$strReturnData."&intCorrect=".$intCorrect."&intError=".$intError);
}               


?>