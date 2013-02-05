<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(28,1,0,0,0);
$games = new games($con);
$input = new Input_filter();


//pridat novy zaznam statistik PLAYER
    if ($_POST['action']=="add_stats" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $id_player=$_POST['id_player'];
    $id_player=$input->check_number($id_player);
    $id_season=$_POST['id_season'];
    $id_season=$input->check_number($id_season);
    $id_type=$_POST['id_type'];
    $id_type=$input->check_number($id_type);
    $games=$_POST['games'];
    if ($games<>"") $games=$input->check_number($games); else $games="NULL";
    $games_dressed=$_POST['games_dressed'];
    if ($games_dressed<>"") $games_dressed=$input->check_number($games_dressed); else $games_dressed="NULL";
    $minutes=$_POST['minutes'];
    if ($minutes<>"") $minutes=$input->check_number($minutes); else $minutes="NULL";
    $id_league=$_POST['id_league'];
    $id_league=$input->check_number($id_league);
    $id_club=$_POST['id_club'];
    $id_club=$input->check_number($id_club);
    $goals=$_POST['goals'];
    if ($goals<>"") $goals=$input->check_number($goals); else $goals="NULL";
    $assist=$_POST['assist'];
    if ($assist<>"") $assist=$input->check_number($assist); else $assist="NULL";
    $penalty=$_POST['penalty'];
    if ($penalty<>"") $penalty=$input->check_number($penalty); else $penalty="NULL";
    $plusminus=$_POST['plusminus'];
    if ($plusminus<>"") $plusminus=$input->check_number($plusminus); else $plusminus="NULL";
    $AVG=$_POST['AVG'];
    if ($AVG<>"") $AVG=$input->check_number($AVG); else $AVG="NULL";
    $PCE=$_POST['PCE'];
    //bezpecny zapis procent
      if ($PCE<>""){
        $PCE=str_replace(",",".",$PCE);
        $PCE=str_replace("%","",$PCE);
        if (substr($PCE,0,1)==".") $PCE="0".$PCE;
        if (substr($PCE,2,1)==".") {
          $PCE=str_replace(".","",$PCE);
          $PCE="0.".$PCE;
        }
        
        $PCE=$input->check_number($PCE);
      }else{$PCE="NULL"; }
    
    $shotouts=$_POST['shotouts'];
    if ($shotouts<>"") $shotouts=$input->check_number($shotouts); else $shotouts="NULL";
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_player.'&id_season='.$id_season.'&id_type='.$id_type.'&games_dressed='.$_POST['games_dressed'].'&minutes='.$_POST['minutes'].'&games='.$_POST['games'].'&id_league='.$_POST['id_league'].'&id_club='.$_POST['id_club'].'&goals='.$_POST['goals'].'&assist='.$_POST['assist'].'&penalty='.$_POST['penalty'].'&plusminus='.$_POST['plusminus'].'&AVG='.$_POST['AVG'].'&PCE='.$_POST['PCE'].'&AVG='.$_POST['AVG'].'&shotouts='.$_POST['shotouts'];
    $strReturnData2='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_player.'&id_season='.($id_season+1).'&id_type='.$id_type.'&id_league='.$id_league.'&id_club='.$id_club;
    
    if (!empty($id_season) and !empty($id_type) and !empty($id_league) and !empty($id_club) and !empty($id_player)){
          $query="INSERT INTO stats (id_stats_type,id_player,id_club,id_league,id_season,id_season_type,games,games_dressed,minutes,goals,assist,plusminus,shoots,power_play_goals,shorthanded_goals,penalty,wins,losts,draws,shotouts,inc_goals,shoots_on_goal,AVG,PCE)
                  VALUES (1,".$id_player.",".$id_club.",".$id_league.",".$id_season.",".$id_type.",".$games.",".$games_dressed.",".$minutes.",".$goals.",".$assist.",".$plusminus.",NULL,NULL,NULL,".$penalty.",NULL,NULL,NULL,".$shotouts.",NULL,NULL,".$AVG.",".$PCE.")
            ";
            //echo $query;
           if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=98;
           }else{
              
              $query_last_update="UPDATE players SET last_update_user=".$users->getIdUser().",	last_update=NOW() WHERE id=".$id_player;
              $con->RunQuery($query_last_update);
              
           }
              
           
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: players_stats.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: players_stats.php".Odkaz."&message=1".$strReturnData2);
    }
  }
  
//pridat novy zaznam statistik COACH
    if ($_POST['action']=="add_stats_coach" and $users->checkUserRight(2)){
    
    $bollError=false;
    $id_player=$_POST['id_player'];
    $id_player=$input->check_number($id_player);
    $id_season=$_POST['id_season'];
    $id_season=$input->check_number($id_season);
    $id_type=$_POST['id_type'];
    $id_type=$input->check_number($id_type);
    $id_league=$_POST['id_league'];
    $id_league=$input->check_number($id_league);
    $id_club=$_POST['id_club'];
    $id_club=$input->check_number($id_club);
    
    $id_coach_position=$_POST['id_coach_position'];
    $id_coach_position=$input->check_number($id_coach_position);
    $league_position=$_POST['league_position'];
    if ($league_position<>"") $league_position=$input->check_number($league_position); else $league_position="NULL";
    $wins=$_POST['wins'];
    if ($wins<>"") $wins=$input->check_number($wins); else $wins="NULL";
    $losts=$_POST['losts'];
    if ($losts<>"") $losts=$input->check_number($losts); else $losts="NULL";
    $draws=$_POST['draws'];
    if ($draws<>"") $draws=$input->check_number($draws); else $draws="NULL";
    $coach_level_reached=$_POST['coach_level_reached'];
    $coach_level_reached=$input->valid_text($coach_level_reached,true,true);
    
    $coach_hired=$_POST['coach_hired'];
    $coach_hired=$input->valid_text($coach_hired,true,true);
    if (!empty($coach_hired)){
      $coach_hired=explode(".",$coach_hired);
      $coach_hired=$coach_hired[2].'-'.$coach_hired[1].'-'.$coach_hired[0];
    }
    
    $coach_left=$_POST['coach_left'];
    $coach_left=$input->valid_text($coach_left,true,true);
    if (!empty($coach_left)){
      $coach_left=explode(".",$coach_left);
      $coach_left=$coach_left[2].'-'.$coach_left[1].'-'.$coach_left[0];
    }
    
    
    $reason_of_leaving=$_POST['reason_of_leaving'];
    $reason_of_leaving=$input->valid_text($reason_of_leaving,true,true);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_player.'&id_season='.$id_season.'&id_type='.$id_type.'&league_position='.$_POST['league_position'].'&wins='.$_POST['wins'].'&losts='.$_POST['losts'].'&id_league='.$_POST['id_league'].'&id_club='.$_POST['id_club'].'&draws='.$_POST['draws'].'&coach_hired='.$_POST['coach_hired'].'&coach_level_reached='.$_POST['coach_level_reached'].'&coach_left='.$_POST['coach_left'].'&reason_of_leaving='.$_POST['reason_of_leaving'];
    $strReturnData2='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_player.'&id_season='.($id_season+1).'&id_type='.$id_type.'&id_league='.$id_league.'&id_club='.$id_club.'&id_coach_position='.$id_coach_position;
    
    if (!empty($id_season) and !empty($id_type) and !empty($id_league) and !empty($id_club) and !empty($id_player)){
          //$id_coach_position se v DB uklada do PENALTY
          //$league_position se v DB uklada do MINUTES
          $query="INSERT INTO stats (id_stats_type,id_player,id_club,id_league,id_season,id_season_type,penalty,minutes,wins,losts,draws,coach_level_reached,coach_hired,coach_left,reason_of_leaving)
                  VALUES (2,".$id_player.",".$id_club.",".$id_league.",".$id_season.",".$id_type.",".$id_coach_position.",".$league_position.",".$wins.",".$losts.",".$draws.",'".$coach_level_reached."','".$coach_hired."','".$coach_left."','".$reason_of_leaving."')
            ";
            //echo $query;
           if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=98;
           }else{
              
              $query_last_update="UPDATE players SET last_update_user=".$users->getIdUser().",	last_update=NOW() WHERE id=".$id_player;
              $con->RunQuery($query_last_update);
              
           }
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: players_stats_coach.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: players_stats_coach.php".Odkaz."&message=1".$strReturnData2);
    }
  }
  

//upravit zaznam statistik
    if ($_POST['action']=="update_stats" and $users->checkUserRight(2)){
    
    $intError=0; $intCorrect=0;
    $bollError=false;
    $id_player=$_POST['id_player'];
    $id_player=$input->check_number($id_player);
    
    $number=$_POST['number'];
    for ($i=1; $i<=($number+1); $i++){
      $id_stats=$_POST['id'][$i];
      $id_stats=$input->check_number($id_stats);

      if (!empty($id_stats)){  
            
      $id_season=$_POST['id_season'][$i];
      $id_season=$input->check_number($id_season);
      $id_type=$_POST['id_type'][$i];
      $id_type=$input->check_number($id_type);
      $games=$_POST['games'][$i];
      if ($games=="") $games="NULL"; else $games=$input->check_number($games); 
      $games_dressed=$_POST['games_dressed'][$i];
      if ($games_dressed=="") $games_dressed="NULL"; else $games_dressed=$input->check_number($games_dressed);
      $minutes=$_POST['minutes'][$i];
      if ($minutes=="") $minutes="NULL"; else $minutes=$input->check_number($minutes);
      $id_league=$_POST['id_league'][$i];
      $id_league=$input->check_number($id_league);
      $id_club=$_POST['id_club'][$i];
      $id_club=$input->check_number($id_club);
      $goals=$_POST['goals'][$i];
      if ($goals=="") $goals="NULL"; else $goals=$input->check_number($goals);
      $assist=$_POST['assist'][$i];
      if ($assist=="") $assist="NULL"; else $assist=$input->check_number($assist);
      $penalty=$_POST['penalty'][$i];
      if ($penalty=="") $penalty="NULL"; else $penalty=$input->check_number($penalty);
      $plusminus=$_POST['plusminus'][$i];
      if ($plusminus=="") $plusminus="NULL"; else $plusminus=$input->check_number($plusminus);
      $AVG=$_POST['AVG'][$i];
      if ($AVG=="") $AVG="NULL"; else $AVG=$input->check_number($AVG);
      $PCE=$_POST['PCE'][$i];
      //bezpecny zapis procent
      if ($PCE<>""){
        $PCE=str_replace(",",".",$PCE);
        $PCE=str_replace("%","",$PCE);
        if (substr($PCE,0,1)==".") $PCE="0".$PCE;
        if (substr($PCE,2,1)==".") {
          $PCE=str_replace(".","",$PCE);
          $PCE="0.".$PCE;
        }
        $PCE=$input->check_number($PCE);
      }else{$PCE="NULL"; }
      
      $shotouts=$_POST['shotouts'][$i];
      if ($shotouts=="") $shotouts="NULL"; else $shotouts=$input->check_number($shotouts);
      
      $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_player;
      //echo $strReturnData.'<br />';
      //echo $id_type.'<br />';
      if (!empty($id_season) and !empty($id_type) and !empty($id_league) and !empty($id_club) and !empty($id_player)){
       
          $query="UPDATE stats SET 
                      id_club=".$id_club.",id_league=".$id_league.",id_season=".$id_season.",id_season_type=".$id_type.",
                      games=".$games.",games_dressed=".$games_dressed.",minutes=".$minutes.",goals=".$goals.",assist=".$assist.",plusminus=".$plusminus.",penalty=".$penalty.",
                      shotouts=".$shotouts.",AVG=".$AVG.",PCE=".$PCE."
                  WHERE id=".$id_stats;
           //echo $query;
           if (!$con->RunQuery($query)){
              //spatna DB
              $intError++;
           }else{
              $intCorrect++;
              
              $query_last_update="UPDATE players SET last_update_user=".$users->getIdUser().",	last_update=NOW() WHERE id=".$id_player;
              $con->RunQuery($query_last_update);
              
           }
              
           
      }else{
          //špatná nebo chybějící vstupní data
          $intError++;
      }
    }
    }
    
    header("Location: players_stats.php".Odkaz."&message=3".$strReturnData."&intCorrect=".$intCorrect."&intError=".$intError);
    }
  


//upravit zaznam statistik COACH
    if ($_POST['action']=="update_stats_coach" and $users->checkUserRight(2)){
    
    $intError=0; $intCorrect=0;
    $bollError=false;
    $id_player=$_POST['id_player'];
    $id_player=$input->check_number($id_player);
    
    $number=$_POST['number'];
    for ($i=1; $i<=($number+1); $i++){
      
      $id_stats=$_POST['id'][$i];
      $id_stats=$input->check_number($id_stats);

      if (!empty($id_stats)){
      
    $id_season=$_POST['id_season'][$i];
    $id_season=$input->check_number($id_season);
    $id_type=$_POST['id_type'][$i];
    $id_type=$input->check_number($id_type);
    $id_league=$_POST['id_league'][$i];
    $id_league=$input->check_number($id_league);
    $id_club=$_POST['id_club'][$i];
    $id_club=$input->check_number($id_club);
    
    $id_coach_position=$_POST['id_coach_position'][$i];
    $id_coach_position=$input->check_number($id_coach_position);
    $league_position=$_POST['league_position'][$i];
    if ($league_position<>"") $league_position=$input->check_number($league_position); else $league_position="NULL";
    $wins=$_POST['wins'][$i];
    if ($wins<>"") $wins=$input->check_number($wins); else $wins="NULL";
    $losts=$_POST['losts'][$i];
    if ($losts<>"") $losts=$input->check_number($losts); else $losts="NULL";
    $draws=$_POST['draws'][$i];
    if ($draws<>"") $draws=$input->check_number($draws); else $draws="NULL";
    $coach_level_reached=$_POST['coach_level_reached'][$i];
    $coach_level_reached=$input->valid_text($coach_level_reached,true,true);
    $coach_hired=$_POST['coach_hired'][$i];
    $coach_hired=$input->valid_text($coach_hired,true,true);
    if (!empty($coach_hired)){
      $coach_hired=explode(".",$coach_hired);
      $coach_hired=$coach_hired[2].'-'.$coach_hired[1].'-'.$coach_hired[0];
    }
    $coach_left=$_POST['coach_left'][$i];
    $coach_left=$input->valid_text($coach_left,true,true);
    if (!empty($coach_left)){
      $coach_left=explode(".",$coach_left);
      $coach_left=$coach_left[2].'-'.$coach_left[1].'-'.$coach_left[0];
    }
    $reason_of_leaving=$_POST['reason_of_leaving'][$i];
    $reason_of_leaving=$input->valid_text($reason_of_leaving,true,true);
      
      $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_player;
      if (!empty($id_season) and !empty($id_type) and !empty($id_league) and !empty($id_club) and !empty($id_player)){
       
          $query="UPDATE stats SET 
                      id_club=".$id_club.",id_league=".$id_league.",id_season=".$id_season.",id_season_type=".$id_type.",
                      penalty=".$id_coach_position.",minutes=".$league_position.",wins=".$wins.",losts=".$losts.",draws=".$draws.",coach_level_reached='".$coach_level_reached."'
                      ,coach_hired='".$coach_hired."',coach_left='".$coach_left."',reason_of_leaving='".$reason_of_leaving."'
                  WHERE id=".$id_stats;
           //echo $query;
           if (!$con->RunQuery($query)){
              //spatna DB
              $intError++;
           }else{
              $intCorrect++;
              
              $query_last_update="UPDATE players SET last_update_user=".$users->getIdUser().",	last_update=NOW() WHERE id=".$id_player;
              $con->RunQuery($query_last_update);
              
           }
              
           
      }else{
          //špatná nebo chybějící vstupní data
          $intError++;
      }
      }
    }
    header("Location: players_stats_coach.php".Odkaz."&message=3".$strReturnData."&intCorrect=".$intCorrect."&intError=".$intError);
    }


//smazat statistiku
    if ($_GET['action']=="delete_stats" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id_item'])){
        $id_item=$_GET['id_item'];
        
        $id_player=$con->GetSQLSingleResult("SELECT id_player as item FROM stats WHERE id=".$id_item);
        
        $query="DELETE FROM stats WHERE id=".$id_item;
        if ($con->RunQuery($query)==true){
           
           $query_last_update="UPDATE players SET last_update_user=".$users->getIdUser().",	last_update=NOW() WHERE id=".$id_player;
           $con->RunQuery($query_last_update);
           
           header("Location: players_stats.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: players_stats.php".Odkaz."&message=99".$strReturnData);} 
      }else{header("Location: players_stats.php".Odkaz."&message=99".$strReturnData);}
    }

//smazat statistiku COACH
    if ($_GET['action']=="delete_stats_coach" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id_item'])){
        $id_item=$_GET['id_item'];
        
        $id_player=$con->GetSQLSingleResult("SELECT id_player as item FROM stats WHERE id=".$id_item);
        
        $query="DELETE FROM stats WHERE id=".$id_item;
        if ($con->RunQuery($query)==true){
           
           $query_last_update="UPDATE players SET last_update_user=".$users->getIdUser().",	last_update=NOW() WHERE id=".$id_player;
           $con->RunQuery($query_last_update);
           
           header("Location: players_stats_coach.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: players_stats_coach.php".Odkaz."&message=99".$strReturnData);} 
      }else{header("Location: players_stats_coach.php".Odkaz."&message=99".$strReturnData);}
    }




//pridat novy zaznam statistik PLAYER-CLUB nebo PLAYER-LEAGUE
    if ($_POST['action']=="add_stats_club" and $users->checkUserRight(2)){
    
    $bollError=false;
    $stats_admin_type=$_POST['stats_admin_type'];
    $stats_admin_type=$input->check_number($stats_admin_type);
    $id_player=$_POST['id_player'];
    $id_player=$input->check_number($id_player);
    $id_season=$_POST['id_season'];
    $id_season=$input->check_number($id_season);
    $id_type=$_POST['id_type'];
    $id_type=$input->check_number($id_type);
    $games=$_POST['games'];
    if ($games<>"") $games=$input->check_number($games); else $games="NULL";
    $games_dressed=$_POST['games_dressed'];
    if ($games_dressed<>"") $games_dressed=$input->check_number($games_dressed); else $games_dressed="NULL";
    $minutes=$_POST['minutes'];
    if ($minutes<>"") $minutes=$input->check_number($minutes); else $minutes="NULL";
    $id_league=$_POST['id_league'];
    $id_league=$input->check_number($id_league);
    $id_club=$_POST['id_club'];
    $id_club=$input->check_number($id_club);
    $id_stats_type=$_POST['id_stats_type'];
    $id_stats_type=$input->check_number($id_stats_type);
    $goals=$_POST['goals'];
    if ($goals<>"") $goals=$input->check_number($goals); else $goals="NULL";
    $assist=$_POST['assist'];
    if ($assist<>"") $assist=$input->check_number($assist); else $assist="NULL";
    $penalty=$_POST['penalty'];
    if ($penalty<>"") $penalty=$input->check_number($penalty); else $penalty="NULL";
    $plusminus=$_POST['plusminus'];
    if ($plusminus<>"") $plusminus=$input->check_number($plusminus); else $plusminus="NULL";
    $AVG=$_POST['AVG'];
    if ($AVG<>"") $AVG=$input->check_number($AVG); else $AVG="NULL";
    $PCE=$_POST['PCE'];
    //bezpecny zapis procent
      if ($PCE<>""){
        $PCE=str_replace(",",".",$PCE);
        $PCE=str_replace("%","",$PCE);
        if (substr($PCE,0,1)==".") $PCE="0".$PCE;
        if (substr($PCE,2,1)==".") {
          $PCE=str_replace(".","",$PCE);
          $PCE="0.".$PCE;
        }
        
        $PCE=$input->check_number($PCE);
      }else{$PCE="NULL"; }
    $shotouts=$_POST['shotouts'];
    if ($shotouts<>"") $shotouts=$input->check_number($shotouts); else $shotouts="NULL";
    
    switch ($stats_admin_type){
      case 0:
        $id_location=$id_club;
      break;
      case 1:
        $id_location=$id_league;
      break;
      case 2:
        $id_location=$id_club;
      break;
    }
    
    
    $is_coach=$_POST['is_coach'];
    $is_coach=$input->check_number($is_coach);
    
    //add coach
    if ($is_coach==1){

    $id_coach_position=$_POST['id_coach_position'];
    $id_coach_position=$input->check_number($id_coach_position);
    $league_position=$_POST['league_position'];
    if ($league_position<>"") $league_position=$input->check_number($league_position); else $league_position="NULL";
    $wins=$_POST['wins'];
    if ($wins<>"") $wins=$input->check_number($wins); else $wins="NULL";
    $losts=$_POST['losts'];
    if ($losts<>"") $losts=$input->check_number($losts); else $losts="NULL";
    $draws=$_POST['draws'];
    if ($draws<>"") $draws=$input->check_number($draws); else $draws="NULL";
    $coach_level_reached=$_POST['coach_level_reached'];
    $coach_level_reached=$input->valid_text($coach_level_reached,true,true);
    
    $coach_hired=$_POST['coach_hired'];
    $coach_hired=$input->valid_text($coach_hired,true,true);
    if (!empty($coach_hired)){
      $coach_hired=explode(".",$coach_hired);
      $coach_hired=$coach_hired[2].'-'.$coach_hired[1].'-'.$coach_hired[0];
    }
    
    $coach_left=$_POST['coach_left'];
    $coach_left=$input->valid_text($coach_left,true,true);
    if (!empty($coach_left)){
      $coach_left=explode(".",$coach_left);
      $coach_left=$coach_left[2].'-'.$coach_left[1].'-'.$coach_left[0];
    }
    
    
    $reason_of_leaving=$_POST['reason_of_leaving'];
    $reason_of_leaving=$input->valid_text($reason_of_leaving,true,true);
    
    }
    
         

    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_stats_type='.$id_stats_type.'&id='.$id_club.'&id_player='.$id_player.'&id_season='.$id_season.'&id_type='.$id_type.'&games_dressed='.$_POST['games_dressed'].'&minutes='.$_POST['minutes'].'&games='.$_POST['games'].'&id_league='.$_POST['id_league'].'&id_club='.$_POST['id_club'].'&goals='.$_POST['goals'].'&assist='.$_POST['assist'].'&penalty='.$_POST['penalty'].'&plusminus='.$_POST['plusminus'].'&AVG='.$_POST['AVG'].'&PCE='.$_POST['PCE'].'&AVG='.$_POST['AVG'].'&shotouts='.$_POST['shotouts'];
    $strReturnData2='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_location.'&id_player='.$id_player.'&id_season='.$id_season.'&id_type='.$id_type.'&id_league='.$id_league.'&id_club='.$id_club;
    
    if (!empty($id_season) and !empty($id_type) and !empty($id_league) and !empty($id_club) and !empty($id_player)){
          
          
          if ($is_coach==0){
          $query="INSERT INTO stats (id_stats_type,id_player,id_club,id_league,id_season,id_season_type,games,games_dressed,minutes,goals,assist,plusminus,shoots,power_play_goals,shorthanded_goals,penalty,wins,losts,draws,shotouts,inc_goals,shoots_on_goal,AVG,PCE)
                  VALUES (1,".$id_player.",".$id_club.",".$id_league.",".$id_season.",".$id_type.",".$games.",".$games_dressed.",".$minutes.",".$goals.",".$assist.",".$plusminus.",NULL,NULL,NULL,".$penalty.",NULL,NULL,NULL,".$shotouts.",NULL,NULL,".$AVG.",".$PCE.")
            ";
          }
          //add coach  
          if ($is_coach==1){
           $query="INSERT INTO stats (id_stats_type,id_player,id_club,id_league,id_season,id_season_type,penalty,minutes,wins,losts,draws,coach_level_reached,coach_hired,coach_left,reason_of_leaving)
                  VALUES (2,".$id_player.",".$id_club.",".$id_league.",".$id_season.",".$id_type.",".$id_coach_position.",".$league_position.",".$wins.",".$losts.",".$draws.",'".$coach_level_reached."','".$coach_hired."','".$coach_left."','".$reason_of_leaving."')
            ";
          }
            
          //echo $query;
            //echo $query;
           if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=98;
           } else{
              
              $query_last_update="UPDATE players SET last_update_user=".$users->getIdUser().",	last_update=NOW() WHERE id=".$id_player;
              $con->RunQuery($query_last_update);
               
          }
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    switch ($stats_admin_type){
      case 0:
        $strLocation="players_stats_club";
      break;
      case 1:
        $strLocation="players_stats_league";
      break;
      case 2:
        $strLocation="players_stats_club_all_times";
      break;
    }
    
    if ($bollError){
         header("Location: ".$strLocation.".php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: ".$strLocation.".php".Odkaz."&message=1".$strReturnData2);
    }
  }
  
  //upravit zaznam statistik PLAYER-CLUB nebo PLAYER-league
    if ($_POST['action']=="update_stats_club" and $users->checkUserRight(2)){
    
    $intError=0; $intCorrect=0;
    $bollError=false;
    
    $stats_admin_type=$_POST['stats_admin_type'];
    $stats_admin_type=$input->check_number($stats_admin_type);
    
    switch ($stats_admin_type){
      case 0:
        $id_club=$_POST['id_club'];
        $id_club=$input->check_number($id_club);
        $id_season=$_POST['id_season'];
        $id_season=$input->check_number($id_season);
      break;
      case 1:
        $id_league=$_POST['id_league'];
        $id_league=$input->check_number($id_league);
        $id_season=$_POST['id_season'];
        $id_season=$input->check_number($id_season);
      break;
      case 2:
        $id_club=$_POST['id_club'];
        $id_club=$input->check_number($id_club);
      break;
    }
    
    
    $id_league_select=$_POST['id_league_select'];
    $id_league_select=$input->check_number($id_league_select);
    
    $number=$_POST['number'];
    //echo $number; 
    
    for ($i=1; $i<=($number+1); $i++){
      $id_stats=$_POST['id_stats'][$i];
      $id_stats=$input->check_number($id_stats);
      $is_coach=$_POST['is_coach'][$i];
      $is_coach=$input->check_number($is_coach);
    
      if (!empty($id_stats)){  
      $id_type=$_POST['id_type'][$i];
      $id_type=$input->check_number($id_type);
      $games=$_POST['games'][$i];
      if ($games=="") $games="NULL"; else $games=$input->check_number($games); 
      $games_dressed=$_POST['games_dressed'][$i];
      if ($games_dressed=="") $games_dressed="NULL"; else $games_dressed=$input->check_number($games_dressed);
      $minutes=$_POST['minutes'][$i];
      if ($minutes=="") $minutes="NULL"; else $minutes=$input->check_number($minutes);
      
      switch ($stats_admin_type){
        case 0:
          $id_league=$_POST['id_league'][$i];
          $id_league=$input->check_number($id_league);
        break;
        case 1:
          $id_club=$_POST['id_club'][$i];
          $id_club=$input->check_number($id_club);
        break;
        case 2:
          $id_league=$_POST['id_league'][$i];
          $id_league=$input->check_number($id_league);
          $id_season=$_POST['id_season'][$i];
          $id_season=$input->check_number($id_season);
        break;
      }
      
      $id_player=$_POST['id_player'][$i];
      $id_player=$input->check_number($id_player);
      $goals=$_POST['goals'][$i];
      if ($goals=="") $goals="NULL"; else $goals=$input->check_number($goals);
      $assist=$_POST['assist'][$i];
      if ($assist=="") $assist="NULL"; else $assist=$input->check_number($assist);
      $penalty=$_POST['penalty'][$i];
      if ($penalty=="") $penalty="NULL"; else $penalty=$input->check_number($penalty);
      $plusminus=$_POST['plusminus'][$i];
      if ($plusminus=="") $plusminus="NULL"; else $plusminus=$input->check_number($plusminus);
      $AVG=$_POST['AVG'][$i];
      if ($AVG=="") $AVG="NULL"; else $AVG=$input->check_number($AVG);
      $PCE=$_POST['PCE'][$i];
      //bezpecny zapis procent
      if ($PCE<>""){
        $PCE=str_replace(",",".",$PCE);
        $PCE=str_replace("%","",$PCE);
        if (substr($PCE,0,1)==".") $PCE="0".$PCE;
        if (substr($PCE,2,1)==".") {
          $PCE=str_replace(".","",$PCE);
          $PCE="0.".$PCE;
        }
        
        $PCE=$input->check_number($PCE);
      }else{$PCE="NULL"; }
      $shotouts=$_POST['shotouts'][$i];
      if ($shotouts=="") $shotouts="NULL"; else $shotouts=$input->check_number($shotouts);
      
      
      //coach
      if ($is_coach==1){
                   
    $id_coach_position=$_POST['id_coach_position'][$i];
    $id_coach_position=$input->check_number($id_coach_position);
    $league_position=$_POST['league_position'][$i];
    if ($league_position<>"") $league_position=$input->check_number($league_position); else $league_position="NULL";
    $wins=$_POST['wins'][$i];
    if ($wins<>"") $wins=$input->check_number($wins); else $wins="NULL";
    $losts=$_POST['losts'][$i];
    if ($losts<>"") $losts=$input->check_number($losts); else $losts="NULL";
    $draws=$_POST['draws'][$i];
    if ($draws<>"") $draws=$input->check_number($draws); else $draws="NULL";
    $coach_level_reached=$_POST['coach_level_reached'][$i];
    $coach_level_reached=$input->valid_text($coach_level_reached,true,true);
    $coach_hired=$_POST['coach_hired'][$i];
    $coach_hired=$input->valid_text($coach_hired,true,true);
    if (!empty($coach_hired)){
      $coach_hired=explode(".",$coach_hired);
      $coach_hired=$coach_hired[2].'-'.$coach_hired[1].'-'.$coach_hired[0];
    }
    $coach_left=$_POST['coach_left'][$i];
    $coach_left=$input->valid_text($coach_left,true,true);
    if (!empty($coach_left)){
      $coach_left=explode(".",$coach_left);
      $coach_left=$coach_left[2].'-'.$coach_left[1].'-'.$coach_left[0];
    }
    $reason_of_leaving=$_POST['reason_of_leaving'][$i];
    $reason_of_leaving=$input->valid_text($reason_of_leaving,true,true);
      
   
          
      }
      
     
      switch ($stats_admin_type){
        case 0:
           $id_location=$id_club;
           $strLocation="players_stats_club";
        break;
        case 1:
          $id_location=$id_league;
          $strLocation="players_stats_league";
        break;
        case 2:
           $id_location=$id_club;
           $strLocation="players_stats_club_all_times";
        break;
      }
      
      $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_location.'&id_season='.$id_season.'&id_league='.$id_league_select;
      //echo $strReturnData; 
      if (!empty($id_season) and !empty($id_type) and !empty($id_league) and !empty($id_club) and !empty($id_player)){
       
          
          if ($is_coach==0){
       
          $query="UPDATE stats SET 
                      id_club=".$id_club.",id_league=".$id_league.",id_season=".$id_season.",id_season_type=".$id_type.",
                      games=".$games.",games_dressed=".$games_dressed.",minutes=".$minutes.",goals=".$goals.",assist=".$assist.",plusminus=".$plusminus.",penalty=".$penalty.",
                      shotouts=".$shotouts.",AVG=".$AVG.",PCE=".$PCE."
                  WHERE id=".$id_stats; 
          }
          
          //coach
          if ($is_coach==1){
            $query="UPDATE stats SET 
                      id_club=".$id_club.",id_league=".$id_league.",id_season=".$id_season.",id_season_type=".$id_type.",
                      penalty=".$id_coach_position.",minutes=".$league_position.",wins=".$wins.",losts=".$losts.",draws=".$draws.",coach_level_reached='".$coach_level_reached."'
                      ,coach_hired='".$coach_hired."',coach_left='".$coach_left."',reason_of_leaving='".$reason_of_leaving."'
                  WHERE id=".$id_stats;
          }
          
           //echo $query;
           if (!$con->RunQuery($query)){
              //spatna DB
              $intError++;
           }else{
              $intCorrect++;
              
              $query_last_update="UPDATE players SET last_update_user=".$users->getIdUser().",	last_update=NOW() WHERE id=".$id_player;
              $con->RunQuery($query_last_update);
              
           }
              
           
      }else{
          //špatná nebo chybějící vstupní data
          $intError++;
      }
    }
    }
    
   
    header("Location: ".$strLocation.".php".Odkaz."&message=3".$strReturnData."&intCorrect=".$intCorrect."&intError=".$intError);
    }



//smazat statistiku CLUBS
    if ($_GET['action']=="delete_stats_clubs" and $users->checkUserRight(4)){
    
     switch ($_GET['id_location']){
      case 0:
        $strLocation="players_stats_club";
        $id_location=$_GET['id'];
        $strURL='&id_league='.$_GET['id_league'];
      break;
      case 1:
        $strLocation="players_stats_league";
        $id_location=$_GET['id_league'];
      break;
      case 2:
        $strLocation="players_stats_club_all_times";
        $id_location=$_GET['id'];
      break;
    }
    
    $strReturnData='&id='.$id_location.'&id_season='.$_GET['id_season'].$strURL.'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    //echo $strReturnData; 
      if (isset($_GET['id_item'])){
        $id_item=$_GET['id_item'];
        
        $id_player=$con->GetSQLSingleResult("SELECT id_player as item FROM stats WHERE id=".$id_item);
        
        $query="DELETE FROM stats WHERE id=".$id_item;
                
        if ($con->RunQuery($query)==true){
           
           $query_last_update="UPDATE players SET last_update_user=".$users->getIdUser().",	last_update=NOW() WHERE id=".$id_player;
           $con->RunQuery($query_last_update);
           
           header("Location: ".$strLocation.".php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: ".$strLocation.".php".Odkaz."&message=99".$strReturnData);} 
      }else{header("Location: ".$strLocation.".php".Odkaz."&message=99".$strReturnData);}
    }




//KOPIROVAT  vybrany zaznam statistik
    if ($_POST['action']=="copy_stats" and $users->checkUserRight(2)){
    
    $intError=0; $intCorrect=0;
    $bollError=false;
    
    $id_club=$_POST['id_club'];
    $id_club=$input->check_number($id_club);
    $id_season=$_POST['id_season'];
    $id_season=$input->check_number($id_season);
    $id_season_copy=$_POST['id_season_copy'];
    $id_season_copy=$input->check_number($id_season_copy);
    $id_league_select=$_POST['id_league_select'];
    $id_league_select=$input->check_number($id_league_select);
    $id_league_copy=$_POST['id_league_copy'];
    $id_league_copy=$input->check_number($id_league_copy);
    $id_type_copy=$_POST['id_type_copy'];
    $id_type_copy=$input->check_number($id_type_copy);
    $number=$_POST['number'];
    for ($i=1; $i<=($number+1); $i++){
      $id_stats=$_POST['id'][$i];
      $id_stats=$input->check_number($id_stats);
      if (!empty($id_stats)){  
      
      //$id_type=$_POST['id_type'][$i];
      //$id_type=$input->check_number($id_type);
      $id_type=$id_type_copy;
      
      
      
      $is_coach=$_POST['is_coach'][$i];
      $is_coach=$input->check_number($is_coach);
      if ($is_coach==0){
        $id_stats_type=1;
      }
      if ($is_coach==1){
        $id_stats_type=2;
      }
      
      $id_player=$_POST['id_player'][$i];
      $id_player=$input->check_number($id_player);
      $id_league=$_POST['id_league'][$i];
      $id_league=$input->check_number($id_league);
      if (!empty($id_league_copy)) $id_league=$id_league_copy;
            ;
      //$games=$_POST['games'][$i];
      $games="";
      if ($games=="") $games="NULL"; else $games=$input->check_number($games); 
      //$games_dressed=$_POST['games_dressed'][$i];
      $games_dressed="";
      if ($games_dressed=="") $games_dressed="NULL"; else $games_dressed=$input->check_number($games_dressed);
      //$minutes=$_POST['minutes'][$i];
      $minutes="";
      if ($minutes=="") $minutes="NULL"; else $minutes=$input->check_number($minutes);
      //$goals=$_POST['goals'][$i];
      $goals="";
      if ($goals=="") $goals="NULL"; else $goals=$input->check_number($goals);
      //$assist=$_POST['assist'][$i];
      $assist="";
      if ($assist=="") $assist="NULL"; else $assist=$input->check_number($assist);
      //$penalty=$_POST['penalty'][$i];
      $penalty="";
      if ($penalty=="") $penalty="NULL"; else $penalty=$input->check_number($penalty);
      //$plusminus=$_POST['plusminus'][$i];
      $plusminus="";
      if ($plusminus=="") $plusminus="NULL"; else $plusminus=$input->check_number($plusminus);
      //$AVG=$_POST['AVG'][$i];
      $AVG="";
      if ($AVG=="") $AVG="NULL"; else $AVG=$input->check_number($AVG);
      //$PCE=$_POST['PCE'][$i];
      $PCE="";
      if ($PCE=="") $PCE="NULL"; else $PCE=$input->check_number($PCE);
      //$shotouts=$_POST['shotouts'][$i];
      $shotouts="";
      if ($shotouts=="") $shotouts="NULL"; else $shotouts=$input->check_number($shotouts);
      
      
      $id_coach_position=$_POST['id_coach_position'][$i];
      if ($id_coach_position!="") $penalty=$input->check_number($id_coach_position);
      
      $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_club.'&id_season='.$id_season.'&id_season_copied='.$id_season_copy.'&id_league='.$id_league_select;
      if (!empty($id_season) and !empty($id_type) and !empty($id_league) and !empty($id_club) and !empty($id_player)){
       
           $query="INSERT INTO stats (id_stats_type,id_player,id_club,id_league,id_season,id_season_type,games,games_dressed,minutes,goals,assist,plusminus,shoots,power_play_goals,shorthanded_goals,penalty,wins,losts,draws,shotouts,inc_goals,shoots_on_goal,AVG,PCE)
                  VALUES (".$id_stats_type.",".$id_player.",".$id_club.",".$id_league.",".$id_season_copy.",".$id_type.",".$games.",".$games_dressed.",".$minutes.",".$goals.",".$assist.",".$plusminus.",NULL,NULL,NULL,".$penalty.",NULL,NULL,NULL,".$shotouts.",NULL,NULL,".$AVG.",".$PCE.")
            ";
           //echo $query;
           if (!$con->RunQuery($query)){
              //spatna DB
              $intError++;
           }else{
              $intCorrect++;
              
               $query_last_update="UPDATE players SET last_update_user=".$users->getIdUser().",	last_update=NOW() WHERE id=".$id_player;
               $con->RunQuery($query_last_update);
              
           }
              
           
      }else{
          //špatná nebo chybějící vstupní data
          $intError++;
      }
    }
    }
    
    header("Location: players_stats_club.php".Odkaz."&message=4".$strReturnData."&intCorrect=".$intCorrect."&intError=".$intError);
    }


//ZAMKNUTI statistik
    if ($_GET['action']=="lock" and $users->checkUserRight(2)){
    
    $intError=0; $intCorrect=0;
    $bollError=false;
    
    $id_club=$_GET['id_club'];
    $id_club=$input->check_number($id_club);
    $id_season=$_GET['id_season'];
    $id_season=$input->check_number($id_season);
    $id_league=$_GET['id_league'];
    $id_league=$input->check_number($id_league);
    $is_lock=$_GET['is_lock'];
    $is_lock=$input->check_number($is_lock);
    $lock_season=$_GET['lock_season'];
    $lock_season=$input->check_number($lock_season);
      
      if (!empty($id_season) and !empty($id_league) and (!empty($id_club) or!empty($lock_season))){
          
          if ($lock_season==1){
            $strLocation="players_stats_league";
            $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'].'&id_season='.$id_season.'&id='.$id_league;
          }else{
            $strLocation="players_stats_club";
            $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'].'&id='.$id_club.'&id_season='.$id_season.'&id_league='.$id_league;
          }
           
          if ($is_lock==0){
           $query="INSERT INTO stats_lock (id_club,id_league,id_season) VALUES (".$id_club.",".$id_league.",".$id_season.")";
            $message=5;
          }
          if ($is_lock==1){
            $query="DELETE FROM stats_lock WHERE id_club=".$id_club." AND id_league=".$id_league." AND id_season=".$id_season."";
            $message=6;
          }
          //echo $query;
          if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=98;
           }
          }else{
            //špatná nebo chybějící vstupní data
            $bollError=true;
            $strError=99;
          }
     if ($bollError){
         header("Location: ".$strLocation.".php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: ".$strLocation.".php".Odkaz."&message=".$message.$strReturnData);
    }
 }  

?>
