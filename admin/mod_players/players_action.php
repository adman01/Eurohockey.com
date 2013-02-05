<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(26,1,0,0,0);
$games = new games($con);
$input = new Input_filter();

//pridat novy zaznam
    if ($_POST['action']=="add" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    $surname=$_POST['surname'];
    $surname=$input->valid_text($surname,true,true);
    $maiden_name=$_POST['maiden_name'];
    $maiden_name=$input->valid_text($maiden_name,true,true);
    $name_cyrrilic=$_POST['name_cyrrilic'];
    $name_cyrrilic=$input->valid_text($name_cyrrilic,true,true);
    $nationality=$_POST['nationality'];
    $nationality=$input->valid_text($nationality,true,true);
    $nationality2=$_POST['nationality2'];
    $nationality2=$input->valid_text($nationality2,true,true);
    $birth_date=$_POST['birth_date'];
    $birth_date=$input->valid_text($birth_date,true,true);
    $birth_date_old=$birth_date;
    if (!empty($birth_date)){
      $birth_date=explode(".",$birth_date);
      if (!empty($birth_date[0])) $date_bith_day=$birth_date[0]; else $date_bith_day='??';
      if (!empty($birth_date[1])) $date_bith_month=$birth_date[1]; else $date_bith_month='??';
      if (!empty($birth_date[2])) $date_bith_year=$birth_date[2]; else $date_bith_year='????';
      $birth_date=$date_bith_year.'-'.$date_bith_month.'-'.$date_bith_day;
    }
    
    $birth_place=$_POST['birth_place'];
    $birth_place=$input->valid_text($birth_place,true,true);
    $height=$_POST['height'];
    $height=$input->check_number($height);
    $weight=$_POST['weight'];
    $weight=$input->check_number($weight);
    $id_position=$_POST['id_position'];
    $id_position=$input->check_number($id_position);
    $id_shoot=$_POST['id_shoot'];
    $id_shoot=$input->check_number($id_shoot);
    $id_status=$_POST['id_status'];
    $id_status=$input->check_number($id_status);
    $gender=$_POST['gender'];
    $gender=$input->check_number($gender);
    $id_photo_folder=$_POST['id_photo_folder'];
    $id_photo_folder=$input->check_number($id_photo_folder);
    
    $draft_1_team=$_POST['draft_1_team'];
    $draft_1_team=$input->valid_text($draft_1_team,true,true);
    $draft_1_year=$_POST['draft_1_year'];
    $draft_1_year=$input->check_number($draft_1_year);
    $draft_1_round=$_POST['draft_1_round'];
    $draft_1_round=$input->check_number($draft_1_round);
    $draft_1_position=$_POST['draft_1_position'];
    $draft_1_position=$input->check_number($draft_1_position);
    
    $draft_2_team=$_POST['draft_2_team'];
    $draft_2_team=$input->valid_text($draft_2_team,true,true);
    $draft_2_year=$_POST['draft_2_year'];
    $draft_2_year=$input->check_number($draft_2_year);
    $draft_2_round=$_POST['draft_2_round'];
    $draft_2_round=$input->check_number($draft_2_round);
    $draft_2_position=$_POST['draft_2_position'];
    $draft_2_position=$input->check_number($draft_2_position);
    
    $draft_3_team=$_POST['draft_3_team'];
    $draft_3_team=$input->valid_text($draft_3_team,true,true);
    $draft_3_year=$_POST['draft_3_year'];
    $draft_3_year=$input->check_number($draft_3_year);
    $draft_3_round=$_POST['draft_3_round'];
    $draft_3_round=$input->check_number($draft_3_round);
    $draft_3_position=$_POST['draft_3_position'];
    $draft_3_position=$input->check_number($draft_3_position);
    
    $info=$_POST['info'];
    $info=$input->valid_text($info,true,false);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'];
    $strReturnData2='&name='.$name.'&surname='.$surname.'&maiden_name='.$maiden_name.'&nationality='.$nationality.'&nationality2='.$nationality2.'&id_photo_folder='.$id_photo_folder.'&gender='.$gender.'&birth_date='.$birth_date.'&birth_place='.$birth_place.'&height='.$height.'&weight='.$weight.'&id_position='.$id_position.'&id_shoot='.$id_shoot.'&id_status='.$id_status.'&draft_1_team='.$draft_1_team.'&draft_1_year='.$draft_1_year.'&draft_1_round='.$draft_1_round.'&draft_1_position='.$draft_1_position.'&draft_2_team='.$draft_2_team.'&draft_2_year='.$draft_2_year.'&draft_2_round='.$draft_2_round.'&draft_2_position='.$draft_2_position.'&draft_3_team='.$draft_3_team.'&draft_3_year='.$draft_3_year.'&draft_3_round='.$draft_3_round.'&draft_3_position='.$draft_3_position.'&info='.urlencode($info);
    
    if (!empty($name) and !empty($surname) and !empty($nationality)and !empty($id_position)and !empty($id_shoot)and !empty($id_status)){
          $query="INSERT INTO players (last_update_user,name,surname,maiden_name,name_cyrrilic,nationality,nationality_2,gender,id_photo_folder,birth_date,birth_place,height,weight,id_position,id_shoot,id_status)
                  VALUES (".$users->getIdUser().",'".$name."','".$surname."','".$maiden_name."','".$name_cyrrilic."','".$nationality."','".$nationality2."',".$gender.",".$id_photo_folder.",'".$birth_date."','".$birth_place."',".$height.",".$weight.",".$id_position.",".$id_shoot.",".$id_status.")
            ";
            //echo $query;
           if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=98;
           }else{
              
              $query_sub="SELECT id FROM players ORDER by id DESC LIMIT 1";
              $result_sub = $con->SelectQuery($query_sub);
              $write_sub = $result_sub->fetch_array();
              $id_player=$write_sub['id'];
              
              //zapis detailu hrace
              $query="INSERT INTO players_details (id_player,detail)
                  VALUES (".$id_player.",'".$info."')
              ";
              if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=98;
              }
              
              if (!empty($draft_1_team) or !empty($draft_1_year) or !empty($draft_1_round) or !empty($draft_1_position)){
                //zapis draftu
                $query="INSERT INTO players_draft (id_player,team_name,d_year,d_round,d_position)
                    VALUES (".$id_player.",'".$draft_1_team."',".$draft_1_year.",".$draft_1_round.",".$draft_1_position.")
                ";
                if (!$con->RunQuery($query)){
                //spatna DB
                $bollError=true;
                $strError=98;
                }
              }
              
              if (!empty($draft_2_team) or !empty($draft_2_year) or !empty($draft_2_round) or !empty($draft_2_position)){
                //zapis draftu
                $query="INSERT INTO players_draft (id_player,team_name,d_year,d_round,d_position)
                    VALUES (".$id_player.",'".$draft_2_team."',".$draft_2_year.",".$draft_2_round.",".$draft_2_position.")
                ";
                if (!$con->RunQuery($query)){
                //spatna DB
                $bollError=true;
                $strError=98;
                }
              }
              
              if (!empty($draft_3_team) or !empty($draft_3_year) or !empty($draft_3_round) or !empty($draft_3_position)){
                //zapis draftu
                $query="INSERT INTO players_draft (id_player,team_name,d_year,d_round,d_position)
                    VALUES (".$id_player.",'".$draft_3_team."',".$draft_3_year.",".$draft_3_round.",".$draft_3_position.")
                ";
                if (!$con->RunQuery($query)){
                //spatna DB
                $bollError=true;
                $strError=98;
                }
              }
           
           }
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
        //echo "Location: players_add.php".Odkaz."&message=".$strError.$strReturnData.$strReturnData2;
         header("Location: players_add.php".Odkaz."&message=".$strError.$strReturnData.$strReturnData2);
    }else{
         
         
         
         
         header("Location:  players.php".Odkaz."&id=".$id_player."&message=1".$strReturnData);
    }
  }
  
  
  

//zkontrolovat aktivni a neaktivni hrace dle poslednich zaznamu sezon
    if ($_GET['action']=="check_status" and $users->checkUserRight(3)){
    $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
    //$query="UPDATE players SET id_status=1 WHERE id_status=2";
    //$con->RunQuery($query);
    
    $boolPlayer=true;
    if ($boolPlayer){
    $query="SELECT id FROM players WHERE id_status=1 ORDER by id DESC";                                               
    $result = $con->SelectQuery($query);
    while($write = $result->fetch_array()){
        $id_player=$write['id'];
        
        $query_sub="SELECT count(*) as pocet FROM stats WHERE id_player=".$id_player." AND id_season>=".(ActSeason-2);
        $result_sub = $con->SelectQuery($query_sub);
        $write_sub = $result_sub->fetch_array();
        $intPocet=$write_sub['pocet'];
        if (empty($intPocet)){
          $query="UPDATE players SET id_status=2 WHERE id=".$id_player;
          $con->RunQuery($query);
        }
    }
	  }
	  header("Location:  players.php".Odkaz."".$strReturnData);
	   
     
    
}

//switch status
if ($_GET['action']=="switch" and $users->checkUserRight(3)){
      $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        
        $query = "SELECT id_status FROM players WHERE id=".$id."";
        $result = $con->SelectQuery($query);
        $write = $result->fetch_array();
        $intStatus=$write['id_status'];
        
        $query = "SELECT id FROM players_status_list WHERE id=".($intStatus+1)."";
        if ($con->GetQueryNum($query)==0){
          $intStatus=1;
        }else{
          $intStatus++;
        }
        
        $query="UPDATE players SET id_status=".$intStatus." WHERE id=".$id;
        $con->RunQuery($query);
        
           header("Location: players.php".Odkaz."".$strReturnData);
        }
        else{header("Location: players.php".Odkaz."&message=99".$strReturnData);} 
}

//switch status foto
if ($_GET['action']=="switch_photo" and $users->checkUserRight(3)){
      $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        
        $query = "SELECT id_photo_status FROM players WHERE id=".$id."";
        $result = $con->SelectQuery($query);
        $write = $result->fetch_array();
        $intStatus=$write['id_photo_status'];
        
        if ($intStatus==1){
          $intStatus=0;
        }else{
          $intStatus=1;
        }
        
        $query="UPDATE players SET id_photo_status=".$intStatus." WHERE id=".$id;
        $con->RunQuery($query);
        
           header("Location: players.php".Odkaz."".$strReturnData);
        }
        else{header("Location: players.php".Odkaz."&message=99".$strReturnData);} 
}

//smazat zaznam
    if ($_GET['action']=="delete" and $users->checkUserRight(4)){
    $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM players WHERE id=".$id;
        if ($con->RunQuery($query)==true){
            $query="DELETE FROM stats WHERE id_player=".$id;
            $con->RunQuery($query);
            $query="DELETE FROM players_draft WHERE id_player=".$id;
            $con->RunQuery($query);
            $query="DELETE FROM players_details WHERE id_player=".$id;
            $con->RunQuery($query);
           header("Location: players.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: players.php".Odkaz."&message=99".$strReturnData);} 
      }else{header("Location: players.php".Odkaz."&message=99".$strReturnData);}
    }
    
    
//upravit zaznam
    if ($_POST['action']=="update" and $users->checkUserRight(3)){
        $bollError=false;
        
        $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    $surname=$_POST['surname'];
    $surname=$input->valid_text($surname,true,true);
    $maiden_name=$_POST['maiden_name'];
    $maiden_name=$input->valid_text($maiden_name,true,true);
    $name_cyrrilic=$_POST['name_cyrrilic'];
    $name_cyrrilic=$input->valid_text($name_cyrrilic,true,true);
    $nationality=$_POST['nationality'];
    $nationality=$input->valid_text($nationality,true,true);
    $nationality2=$_POST['nationality2'];
    $nationality2=$input->valid_text($nationality2,true,true);
    $birth_date=$_POST['birth_date'];
    $birth_date=$input->valid_text($birth_date,true,true);
    $birth_date_old=$birth_date;
    if (!empty($birth_date)){
      $birth_date=explode(".",$birth_date);
      if (!empty($birth_date[0])) $date_bith_day=$birth_date[0]; else $date_bith_day='??';
      if (!empty($birth_date[1])) $date_bith_month=$birth_date[1]; else $date_bith_month='??';
      if (!empty($birth_date[2])) $date_bith_year=$birth_date[2]; else $date_bith_year='????';
      $birth_date=$date_bith_year.'-'.$date_bith_month.'-'.$date_bith_day;
    }
    
    $birth_place=$_POST['birth_place'];
    $birth_place=$input->valid_text($birth_place,true,true);
    $height=$_POST['height'];
    $height=$input->check_number($height);
    $weight=$_POST['weight'];
    $weight=$input->check_number($weight);
    $id_position=$_POST['id_position'];
    $id_position=$input->check_number($id_position);
    $id_shoot=$_POST['id_shoot'];
    $id_shoot=$input->check_number($id_shoot);
    $id_status=$_POST['id_status'];
    $id_status=$input->check_number($id_status);
    $gender=$_POST['gender'];
    $gender=$input->check_number($gender);
    $id_photo_folder=$_POST['id_photo_folder'];
    $id_photo_folder=$input->check_number($id_photo_folder);
    
    $draft_1_id=$_POST['draft_1_id'];
    $draft_1_id=$input->check_number($draft_1_id);
    $draft_1_team=$_POST['draft_1_team'];
    $draft_1_team=$input->valid_text($draft_1_team,true,true);
    $draft_1_year=$_POST['draft_1_year'];
    $draft_1_year=$input->check_number($draft_1_year);
    $draft_1_round=$_POST['draft_1_round'];
    $draft_1_round=$input->check_number($draft_1_round);
    $draft_1_position=$_POST['draft_1_position'];
    $draft_1_position=$input->check_number($draft_1_position);
    
    $draft_2_id=$_POST['draft_2_id'];
    $draft_2_id=$input->check_number($draft_2_id);
    $draft_2_team=$_POST['draft_2_team'];
    $draft_2_team=$input->valid_text($draft_2_team,true,true);
    $draft_2_year=$_POST['draft_2_year'];
    $draft_2_year=$input->check_number($draft_2_year);
    $draft_2_round=$_POST['draft_2_round'];
    $draft_2_round=$input->check_number($draft_2_round);
    $draft_2_position=$_POST['draft_2_position'];
    $draft_2_position=$input->check_number($draft_2_position);
    
    $draft_3_id=$_POST['draft_3_id'];
    $draft_3_id=$input->check_number($draft_3_id);
    $draft_3_team=$_POST['draft_3_team'];
    $draft_3_team=$input->valid_text($draft_3_team,true,true);
    $draft_3_year=$_POST['draft_3_year'];
    $draft_3_year=$input->check_number($draft_3_year);
    $draft_3_round=$_POST['draft_3_round'];
    $draft_3_round=$input->check_number($draft_3_round);
    $draft_3_position=$_POST['draft_3_position'];
    $draft_3_position=$input->check_number($draft_3_position);
    
    $info=$_POST['info'];
    $info=$input->valid_text($info,true,false);
    
    $id=$_POST['id'];
    
    $strReturnData='&id='.$id.'&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'];
    $strReturnData2='&name='.$name.'&surname='.$surname.'&maiden_name='.$maiden_name.'&nationality='.$nationality.'&nationality2='.$nationality2.'&id_photo_folder='.$id_photo_folder.'&gender='.$gender.'&birth_date='.$birth_date.'&birth_place='.$birth_place.'&height='.$height.'&weight='.$weight.'&id_position='.$id_position.'&id_shoot='.$id_shoot.'&id_status='.$id_status.'&draft_1_team='.$draft_1_team.'&draft_1_year='.$draft_1_year.'&draft_1_round='.$draft_1_round.'&draft_1_position='.$draft_1_position.'&draft_2_team='.$draft_2_team.'&draft_2_year='.$draft_2_year.'&draft_2_round='.$draft_2_round.'&draft_2_position='.$draft_2_position.'&draft_3_team='.$draft_3_team.'&draft_3_year='.$draft_3_year.'&draft_3_round='.$draft_3_round.'&draft_3_position='.$draft_3_position.'&info='.urlencode($info);
        
        if (!empty($name) and !empty($surname) and !empty($nationality)and !empty($id_position)and !empty($id_shoot)and !empty($id_status)){
            $query="UPDATE players SET last_update_user=".$users->getIdUser().",name='".$name."',surname='".$surname."',maiden_name='".$maiden_name."',name_cyrrilic='".$name_cyrrilic."',nationality='".$nationality."',nationality_2='".$nationality2."',gender=".$gender.",id_photo_folder=".$id_photo_folder.",birth_date='".$birth_date."',birth_place='".$birth_place."',height=".$height.",weight=".$weight.",id_position=".$id_position.",id_shoot=".$id_shoot.",id_status=".$id_status." WHERE id=".$id;
           //echo $query;
           if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=98;
              
           }else{
            
              //zapis detailu hrace
              $query_sub="SELECT id_player FROM players_details WHERE id_player=".$id;
              //echo $query_sub;
              if ($con->GetQueryNum($query_sub)==0){
                  $query="INSERT INTO players_details (id_player,detail)
                  VALUES (".$id.",'".$info."')
                  ";
                  if (!$con->RunQuery($query)){
                    //spatna DB
                    $bollError=true;
                    $strError=98;
                    
                  }
              }else{
                $query="UPDATE players_details SET detail='".$info."' WHERE id_player=".$id;
                $con->RunQuery($query);
              }
              
              //draft 1
                if (empty($draft_1_id)){
                  //zapis draftu
                  $query="INSERT INTO players_draft (id_player,team_name,d_year,d_round,d_position)
                    VALUES (".$id.",'".$draft_1_team."',".$draft_1_year.",".$draft_1_round.",".$draft_1_position.")
                  ";
                   $con->RunQuery($query);
                }else{
                  $query="UPDATE players_draft SET team_name='".$draft_1_team."',d_year=".$draft_1_year.",d_round=".$draft_1_round.",d_position=".$draft_1_position." WHERE id=".$draft_1_id;
                  $con->RunQuery($query);
                }
              
              //draft 2
                if (empty($draft_2_id)){
                  //zapis draftu
                  $query="INSERT INTO players_draft (id_player,team_name,d_year,d_round,d_position)
                    VALUES (".$id.",'".$draft_2_team."',".$draft_2_year.",".$draft_2_round.",".$draft_2_position.")
                  ";
                  $con->RunQuery($query);
                }else{
                  $query="UPDATE players_draft SET team_name='".$draft_2_team."',d_year=".$draft_2_year.",d_round=".$draft_2_round.",d_position=".$draft_2_position." WHERE id=".$draft_2_id;
                  $con->RunQuery($query);
                }
              
              //draft 3
                if (empty($draft_3_id)){
                  //zapis draftu
                  $query="INSERT INTO players_draft (id_player,team_name,d_year,d_round,d_position)
                    VALUES (".$id.",'".$draft_3_team."',".$draft_3_year.",".$draft_3_round.",".$draft_3_position.")
                  ";
                  $con->RunQuery($query);
                }else{
                  $query="UPDATE players_draft SET team_name='".$draft_3_team."',d_year=".$draft_3_year.",d_round=".$draft_3_round.",d_position=".$draft_3_position." WHERE id=".$draft_3_id;
                  $con->RunQuery($query);
                }
              
              
            
           }
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: players_update.php".Odkaz."&message=".$strError.$strReturnData.$strReturnData2);
    }else{
         header("Location: players.php".Odkaz."&message=3".$strReturnData);
    }
  }


//upravit hromadne hrace
    if ($_POST['action']=="update_mass_players" and $users->checkUserRight(2)){
    
    $intError=0; $intCorrect=0;
    $bollError=false;
    
    $id_club=$_POST['id_club'];
    $id_club=$input->check_number($id_club);
    $id_season=$_POST['id_season'];
    $id_season=$input->check_number($id_season);
    $id_league_select=$_POST['id_league_select'];
    $id_league_select=$input->check_number($id_league_select);
    
    $number=$_POST['number'];
    //echo $number; 
    
    for ($i=1; $i<=($number+1); $i++){
      $id_player=$_POST['id_player'][$i];
      $id_player=$input->check_number($id_player);
    
      if (!empty($id_player)){
      
      
      $name_cyrrilic=$_POST['name_cyrrilic'][$i];
      $name_cyrrilic=$input->valid_text($name_cyrrilic,true,true);
      $nationality=$_POST['nationality'][$i];
      $nationality=$input->valid_text($nationality,true,true);
      $birth_date=$_POST['birth_date'][$i];
      $birth_date=$input->valid_text($birth_date,true,true);
      if (!empty($birth_date)){
        $birth_date=explode(".",$birth_date);
        if (!empty($birth_date[0])) $date_bith_day=$birth_date[0]; else $date_bith_day='??';
        if (!empty($birth_date[1])) $date_bith_month=$birth_date[1]; else $date_bith_month='??';
        if (!empty($birth_date[2])) $date_bith_year=$birth_date[2]; else $date_bith_year='????';
        $birth_date=$date_bith_year.'-'.$date_bith_month.'-'.$date_bith_day;
      } 
      $birth_place=$_POST['birth_place'][$i];
      $birth_place=$input->valid_text($birth_place,true,true);
      $height=$_POST['height'][$i];
      $height=$input->check_number($height);
      $weight=$_POST['weight'][$i];
      $weight=$input->check_number($weight);
      $id_position=$_POST['id_position'][$i];
      $id_position=$input->check_number($id_position);
      $id_shoot=$_POST['id_shoot'][$i];
      $id_shoot=$input->check_number($id_shoot);
      
      $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_club.'&id_season='.$id_season.'&id_league='.$id_league_select;
      if (!empty($id_season) and !empty($id_league_select) and !empty($id_club) and !empty($id_player)){
       
           $query="UPDATE players SET last_update_user=".$users->getIdUser().",name_cyrrilic='".$name_cyrrilic."',nationality='".$nationality."',birth_date='".$birth_date."',birth_place='".$birth_place."',height=".$height.",weight=".$weight.",id_position=".$id_position.",id_shoot=".$id_shoot." WHERE id=".$id_player;
           //echo $query."<br />";
           if (!$con->RunQuery($query)){
              //spatna DB
              $intError++;
           }else{
              $intCorrect++;
           }
              
           
      }else{
          //špatná nebo chybějící vstupní data
          $intError++;
      }
    }
    }
    
    header("Location: players_mass_update.php".Odkaz."&message=3".$strReturnData."&intCorrect=".$intCorrect."&intError=".$intError);
}



?>