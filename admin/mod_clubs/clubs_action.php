<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(25,1,0,0,0);
$games = new games($con);
$input = new Input_filter();

//switch status
if ($_GET['action']=="switch" and $users->checkUserRight(3)){
      $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        
        $query = "SELECT id_status FROM clubs WHERE id=".$id."";
        $result = $con->SelectQuery($query);
        $write = $result->fetch_array();
        $intStatus=$write['id_status'];
        
        $query = "SELECT id FROM clubs_status_list WHERE id=".($intStatus+1)."";
        if ($con->GetQueryNum($query)==0){
          $intStatus=1;
        }else{
          $intStatus++;
        }
        
        $query="UPDATE clubs SET id_status=".$intStatus." WHERE id=".$id;
        $con->RunQuery($query);
        
           header("Location: clubs.php".Odkaz."".$strReturnData);
        }
        else{header("Location: clubs.php".Odkaz."&message=99".$strReturnData);} 
}

//switch arena assign status
if ($_GET['action']=="switch_arena" and $users->checkUserRight(3)){
      $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'].'&id='.$_GET['id_item'];
    
      if (!empty($_GET['id']) and !empty($_GET['id_item'])){
        $id=$_GET['id'];
        $id_club=$_GET['id_item'];
        
        $query="UPDATE clubs_arenas_items SET bool_main_arena=0 WHERE id_club=".$id_club;
        $con->RunQuery($query);
        
        $query="UPDATE clubs_arenas_items SET bool_main_arena=1 WHERE id=".$id;
        $con->RunQuery($query);
        
           header("Location: clubs_arenas.php".Odkaz."".$strReturnData);
        }
        else{header("Location: clubs_arenas.php".Odkaz."&message=99".$strReturnData);} 
}

//switch status NATIONAL
if ($_GET['action']=="switch_national" and $users->checkUserRight(3)){
      $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        
        $query = "SELECT is_national_team FROM clubs WHERE id=".$id."";
        $result = $con->SelectQuery($query);
        $write = $result->fetch_array();
        $intStatus=$write['is_national_team'];
        
        if ($intStatus==2){
          $intStatus=1;
        }else{
          $intStatus=2;
        }
        
        $query="UPDATE clubs SET is_national_team=".$intStatus." WHERE id=".$id;
        $con->RunQuery($query);
        
           header("Location: clubs.php".Odkaz."".$strReturnData);
        }
        else{header("Location: clubs.php".Odkaz."&message=99".$strReturnData);} 
}


//pridat novy zaznam
    if ($_POST['action']=="add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $id_status=$_POST['id_status'];
    $id_status=$input->check_number($id_status);
    $id_status_info=$_POST['id_status_info'];
    $id_status_info=$input->valid_text($id_status_info,true,true);
    $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    $short_name=$_POST['short_name'];
    $short_name=$input->valid_text($short_name,true,true);
    $nickname=$_POST['nickname'];
    $nickname=$input->valid_text($nickname,true,true);
    $name_original=$_POST['name_original'];
    $name_original=$input->valid_text($name_original,true,true);
    $id_country=$_POST['id_country'];
    $id_country=$input->valid_text($id_country,true,true);
    $id_league=$_POST['id_league'];
    $id_league=$input->check_number($id_league);
    $year_founded=$_POST['year_founded'];
    $year_founded=$input->check_number($year_founded);
    $address=$_POST['address'];
    $address=$input->valid_text($address,true,true);
    $city=$_POST['city'];
    $city=$input->valid_text($city,true,true);
    $telephone=$_POST['telephone'];
    $telephone=$input->valid_text($telephone,true,true);
    $fax=$_POST['fax'];
    $fax=$input->valid_text($fax,true,true);
    $email_1=$_POST['email_1'];
    $email_1=$input->valid_text($email_1,true,true);
    $email_2=$_POST['email_2'];
    $email_2=$input->valid_text($email_2,true,true);
    $email_3=$_POST['email_3'];
    $email_3=$input->valid_text($email_3,true,true);
    $email_1_note=$_POST['email_1_note'];
    $email_1_note=$input->valid_text($email_1_note,true,true);
    $email_2_note=$_POST['email_2_note'];
    $email_2_note=$input->valid_text($email_2_note,true,true);
    $email_3_note=$_POST['email_3_note'];
    $email_3_note=$input->valid_text($email_3_note,true,true);
    $colours=$_POST['colours'];
    $colours=$input->valid_text($colours,true,true);
    $brief_history=$_POST['brief_history'];
    $brief_history=$input->valid_text($brief_history,true,false);
    $achievments=$_POST['achievments'];
    $achievments=$input->valid_text($achievments,true,false);
    $team_management=$_POST['team_management'];
    $team_management=$input->valid_text($team_management,true,false);
    $is_national=$_POST['is_national'];
    $is_national=$input->check_number($is_national);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_status='.$id_status.'&id_status_info='.$id_status_info.'&name='.$name.'&short_name='.$short_name.'&nickname='.$nickname.'&name_original='.$name_original.'&id_country='.$id_country.'&id_league='.$id_league.'&year_founded='.$year_founded.'&city='.$city.'&telephone='.$telephone.'&fax='.$fax.'&email_1='.$email_1.'&email_2='.$email_2.'&email_3='.$email_3.'&email_1_note='.$email_1_note.'&email_2_note='.$email_2_note.'&email_3_note='.$email_3_note.'&colours='.$colours.'&is_national='.$is_national.'&address='.urlencode($address).'&brief_history='.urlencode($brief_history).'&achievments='.urlencode($achievments).'&team_management='.urlencode($team_management);
    
    if (!empty($name) and !empty($id_league) and !empty($id_status)){
          $query="INSERT INTO clubs (last_update_user,id_status,name,short_name,nickname,name_original,id_country,address,city,year_founded,telephone,fax,email_1,email_2,email_3,email_1_note,email_2_note,email_3_note,colours,brief_history,achievments,team_management,is_national_team,id_status_info)
                  VALUES (".$users->getIdUser().",".$id_status.",'".$name."','".$short_name."','".$nickname."','".$name_original."','".$id_country."','".$address."','".$city."',".$year_founded.",'".$telephone."','".$fax."','".$email_1."','".$email_2."','".$email_3."','".$email_1_note."','".$email_2_note."','".$email_3_note."','".$colours."','".$brief_history."','".$achievments."','".$team_management."','".$is_national."','".$id_status_info."')
            ";
            //echo $query;
           if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=98;
           }else{
              
              //zapis prirazeni liga->zeme
              $query_sub="SELECT id FROM clubs ORDER by id DESC LIMIT 1";
              $result_sub = $con->SelectQuery($query_sub);
              $write_sub = $result_sub->fetch_array();
              $id_club=$write_sub['id'];
              $query="INSERT INTO clubs_leagues_items (id_league,id_club)
                  VALUES (".$id_league.",".$id_club.")
              ";
              if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=98;
              }
           
           }
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: clubs_add.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: clubs.php".Odkaz."&message=1".$strReturnData);
    }
  }


//smazat zaznam
    if ($_GET['action']=="delete" and $users->checkUserRight(4)){
    $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM clubs WHERE id=".$id;
        if ($con->RunQuery($query)==true){
            $query="DELETE FROM clubs_leagues_items WHERE id_club=".$id;
            $con->RunQuery($query);
            $query="DELETE FROM clubs_names WHERE id_club=".$id;
            $con->RunQuery($query);
           header("Location: clubs.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: clubs.php".Odkaz."&message=99".$strReturnData);} 
      }else{header("Location: clubs.php".Odkaz."&message=99".$strReturnData);}
    }
    
    
//upravit zaznam
    if ($_POST['action']=="update" and $users->checkUserRight(3)){
        $bollError=false;
        
    $id_status=$_POST['id_status'];
    $id_status=$input->check_number($id_status);
    $id_status_info=$_POST['id_status_info'];
    $id_status_info=$input->valid_text($id_status_info,true,true);
    $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    $short_name=$_POST['short_name'];
    $short_name=$input->valid_text($short_name,true,true);
    $nickname=$_POST['nickname'];
    $nickname=$input->valid_text($nickname,true,true);
    $name_original=$_POST['name_original'];
    $name_original=$input->valid_text($name_original,true,true);
    $id_country=$_POST['id_country'];
    $id_country=$input->valid_text($id_country,true,true);
    $id_league=$_POST['id_league'];
    $id_league=$input->check_number($id_league);
    $year_founded=$_POST['year_founded'];
    $year_founded=$input->valid_text($year_founded,true,true);
    $address=$_POST['address'];
    $address=$input->valid_text($address,true,true);
    $city=$_POST['city'];
    $city=$input->valid_text($city,true,true);
    $telephone=$_POST['telephone'];
    $telephone=$input->valid_text($telephone,true,true);
    $fax=$_POST['fax'];
    $fax=$input->valid_text($fax,true,true);
    $email_1=$_POST['email_1'];
    $email_1=$input->valid_text($email_1,true,true);
    $email_2=$_POST['email_2'];
    $email_2=$input->valid_text($email_2,true,true);
    $email_3=$_POST['email_3'];
    $email_3=$input->valid_text($email_3,true,true);
    $email_1_note=$_POST['email_1_note'];
    $email_1_note=$input->valid_text($email_1_note,true,true);
    $email_2_note=$_POST['email_2_note'];
    $email_2_note=$input->valid_text($email_2_note,true,true);
    $email_3_note=$_POST['email_3_note'];
    $email_3_note=$input->valid_text($email_3_note,true,true);
    $colours=$_POST['colours'];
    $colours=$input->valid_text($colours,true,true);
    $brief_history=$_POST['brief_history'];
    $brief_history=$input->valid_text($brief_history,true,false);
    $achievments=$_POST['achievments'];
    $achievments=$input->valid_text($achievments,true,false);
    $team_management=$_POST['team_management'];
    $team_management=$input->valid_text($team_management,true,false);
    $id=$_POST['id'];
    $is_national=$_POST['is_national'];
    $is_national=$input->check_number($is_national);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id.'&is_national='.$is_national.'&id_status='.$id_status.'&id_status_info='.$id_status_info.'&name='.$name.'&short_name='.$short_name.'&nickname='.$nickname.'&name_original='.$name_original.'&id_country='.$id_country.'&id_league='.$id_league.'&year_founded='.$year_founded.'&city='.$city.'&telephone='.$telephone.'&fax='.$fax.'&email_1='.$email_1.'&email_2='.$email_2.'&email_3='.$email_3.'&email_1_note='.$email_1_note.'&email_2_note='.$email_2_note.'&email_3_note='.$email_3_note.'&colours='.$colours.'&address='.urlencode($address).'&brief_history='.urlencode($brief_history).'&achievments='.urlencode($achievments).'&team_management='.urlencode($team_management);
        
        if (!empty($name) and !empty($id) and  !empty($id_status)){
        
        $query="UPDATE clubs SET 
                  last_update_user=".$users->getIdUser().",id_status=".$id_status.",name='".$name."',short_name='".$short_name."',nickname='".$nickname."',name_original='".$name_original."',id_country='".$id_country."',address='".$address."',city='".$city."',
                  is_national_team=".$is_national.",year_founded=".$year_founded.",telephone='".$telephone."',fax='".$fax."',email_1='".$email_1."',email_2='".$email_2."',email_3='".$email_3."',
                  email_1_note='".$email_1_note."',email_2_note='".$email_2_note."',email_3_note='".$email_3_note."',colours='".$colours."',brief_history='".$brief_history."',
                  achievments='".$achievments."',team_management='".$team_management."',id_status_info='".$id_status_info."'
                   WHERE id=".$id."
            ";
            //echo   $query;
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
         header("Location: clubs_update.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: clubs.php".Odkaz."&message=3".$strReturnData);
    }
  }

//pridat novy zaznam / prirazeni klubu k lize
    if ($_POST['action']=="assign_add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $id_league=$_POST['id_league'];
    $id_league=$input->check_number($id_league);
    $id=$_POST['id'];
    $id=$input->check_number($id);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_league='.$id_league.'&id='.$id;
    
    if (!empty($id) and !empty($id_league)){
          $query="INSERT INTO clubs_leagues_items (id_league,id_club)
                  VALUES (".$id_league.",".$id.")
            ";
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
         header("Location: clubs_assign.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: clubs_assign.php".Odkaz."&message=1".$strReturnData);
    }
  }

//smazat zaznam / prirazeni ligy k klubu
    if ($_GET['action']=="assign_delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM clubs_leagues_items WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: clubs_assign.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: clubs_assign.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: clubs_assign.php".Odkaz."&message=99".$strReturnData);}
    }

      
//pridat novy zaznam / jmeno ligy
    if ($_POST['action']=="name_add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $id_season=$_POST['id_season'];
    $id_season=$input->check_number($id_season);
    $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    $id=$_POST['id'];
    $id=$input->check_number($id);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&name='.$name.'&id_season='.$id_season.'&id='.$id;
    
    if (!empty($id) and !empty($name)and !empty($id_season)){
          $query="INSERT INTO clubs_names (id_club,name,int_year)
                  VALUES (".$id.",'".$name."',".$id_season.")
            ";
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
         header("Location: clubs_names.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: clubs_names.php".Odkaz."&message=1".$strReturnData);
    }
  }

//smazat zaznam / jmeno ligy
    if ($_GET['action']=="name_delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM clubs_names WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: clubs_names.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: clubs_names.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: clubs_names.php".Odkaz."&message=99".$strReturnData);}
    }

//upravit zaznam / jmeno ligy
    if ($_POST['action']=="name_update" and $users->checkUserRight(3)){
        $bollError=false;
        $id_season=$_POST['id_season'];
        $id_season=$input->check_number($id_season);
        $name=$_POST['name'];
        $name=$input->valid_text($name,true,true);
        $id=$_POST['id'];
        $id=$input->check_number($id);
        $id_club=$_POST['id_club'];
        $id_club=$input->check_number($id_club);
        $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&name='.$name.'&shortcut='.$shortcut.'&id_club='.$id_club.'&id='.$id;
        
        if (!empty($id_season) and !empty($name)){
            $query="UPDATE clubs_names SET 
                    name='".$name."',int_year='".$id_season."'
                    WHERE id=".$id."
            ";
            
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
         header("Location: clubs_names.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: clubs_names.php".Odkaz."&message=3&filter=".$_POST['filter']."&filter2=".$_POST['filter2']."&list_number=".$_POST['list_number']."&id=".$id_club);
    }
  }
  
//hromadne priradit kluby k ligam
if ($_POST['action']=="assign_league_grouped" and $users->checkUserRight(3)){
     $bollError=false;
        
     $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&filter3='.$_GET['filter3'].'&list_number='.$_GET['list_number'];
     $id_league=$_POST['id_league'];
     $id_league=$input->check_number($id_league);
      
     if (!empty($id_league)){
        $intCorrect=0;
        $intError=0;
        $number=$_POST['number'];
        for ($i=0; $i<$number; $i++){
           $id=$_POST['id'][$i];
           if (!empty($id)){
              
              $query="INSERT INTO clubs_leagues_items (id_league,id_club)
                  VALUES (".$id_league.",".$id.")
              ";
              //echo $query;
              if (!$con->RunQuery($query)){
              //spatna DB
                $intError++;
              }else{
                $intCorrect++;
              }
              
           }
        
        }
        
    }else{
    //špatná nebo chybějící vstupní data
    $bollError=true;
    $strError=99;
  }
  
    if ($bollError){
         header("Location: clubs.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: clubs.php".Odkaz."&message=4".$strReturnData."&intCorrect=".$intCorrect."&intError=".$intError);
    }

  
}


//pridat novy zaznam / IMAGE
    if ($_POST['action']=="image_add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $id_season=$_POST['id_season'];
    $id_season=$input->check_number($id_season);
    $id_image=$_POST['id_image'];
    $id_image=$input->check_number($id_image);
    $id=$_POST['id'];
    $id=$input->check_number($id);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_image='.$id_image.'&id_season='.$id_season.'&id='.$id;
    
    if (!empty($id) and !empty($id_image)and !empty($id_season)){
          $query="INSERT INTO clubs_images (id_club,id_image,int_year)
                  VALUES (".$id.",'".$id_image."',".$id_season.")
            ";
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
         header("Location: clubs_images.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: clubs_images.php".Odkaz."&message=1".$strReturnData);
    }
  }

//smazat zaznam / IMAGE
    if ($_GET['action']=="image_delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM clubs_images WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: clubs_images.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: clubs_images.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: clubs_images.php".Odkaz."&message=99".$strReturnData);}
    }

//upravit zaznam / IMAGE
    if ($_POST['action']=="image_update" and $users->checkUserRight(3)){
        $bollError=false;
        $id_season=$_POST['id_season'];
        $id_season=$input->check_number($id_season);
        $id_image=$_POST['id_image'];
        $id_image=$input->check_number($id_image);
        $id=$_POST['id'];
        $id=$input->check_number($id);
        $id_club=$_POST['id_club'];
        $id_club=$input->check_number($id_club);
        $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_image='.$id_image.'&id_club='.$id_club.'&id='.$id;
        
        if (!empty($id_season) and !empty($id_image)){
            $query="UPDATE clubs_images SET 
                    id_image='".$id_image."',int_year='".$id_season."'
                    WHERE id=".$id."
            ";
            
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
         header("Location: clubs_images_update.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: clubs_images.php".Odkaz."&message=3&filter=".$_POST['filter']."&filter2=".$_POST['filter2']."&list_number=".$_POST['list_number']."&id=".$id_club);
    }
  }
  
//pridat novy zaznam / PLAYER
    if ($_POST['action']=="player_add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $id_season=$_POST['id_season'];
    $id_season=$input->check_number($id_season);
    $id_player=$_POST['id_player'];
    $id_player=$input->check_number($id_player);
    $id=$_POST['id'];
    $id=$input->check_number($id);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_player='.$id_player.'&id_season='.$id_season.'&id='.$id;
    
    if (!empty($id) and !empty($id_player)){
          $query="INSERT INTO clubs_players_items (id_club,id_player)
                  VALUES (".$id.",'".$id_player."')
            ";
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
         header("Location: clubs_players.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: clubs_players.php".Odkaz."&message=1".$strReturnData);
    }
  }

//smazat zaznam / PLAYER
    if ($_GET['action']=="player_delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM clubs_players_items WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: clubs_players.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: clubs_players.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: clubs_players.php".Odkaz."&message=99".$strReturnData);}
    }

//pridat novy zaznam / IMAGE
    if ($_POST['action']=="farm_add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $id_farm=$_POST['id_farm'];
    $id_farm=$input->check_number($id_farm);
    $id_farm_statut=$_POST['id_farm_statut'];
    $id_farm_statut=$input->check_number($id_farm_statut);
    $id=$_POST['id'];
    $id=$input->check_number($id);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_farm='.$id_farm.'&id_farm_statut='.$id_farm_statut.'&id='.$id;
    
    if (!empty($id) and !empty($id_farm)and !empty($id_farm_statut)){
          $query="INSERT INTO clubs_farm_items (id_club,id_farm,id_farm_statut)
                  VALUES (".$id.",'".$id_farm."',".$id_farm_statut.")";
            
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
         header("Location: clubs_farms.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: clubs_farms.php".Odkaz."&message=1".$strReturnData);
    }
  }

//smazat zaznam / FARM
    if ($_GET['action']=="farm_delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM clubs_farm_items WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: clubs_farms.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: clubs_farms.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: clubs_farms.php".Odkaz."&message=99".$strReturnData);}
    }

//upravit zaznam / FARM
    if ($_POST['action']=="farm_update" and $users->checkUserRight(3)){
        $bollError=false;
        $id_farm=$_POST['id_farm'];
        $id_farm=$input->check_number($id_farm);
        $id_farm_statut=$_POST['id_farm_statut'];
        $id_farm_statut=$input->check_number($id_farm_statut);
        $id=$_POST['id'];
        $id=$input->check_number($id);
        $id_club=$_POST['id_club'];
        $id_club=$input->check_number($id_club);
        $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_farm='.$id_farm.'&id_farm_statut='.$id_farm_statut.'&id_club='.$id_club.'&id='.$id;
        
        if (!empty($id_season) and !empty($id_image)){
            $query="UPDATE clubs_farm_items SET 
                    id_farm='".$id_farm."',id_farm_statut='".$id_farm_statut."'
                    WHERE id=".$id."
            ";
            
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
         header("Location: clubs_farms_update.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: clubs_farms.php".Odkaz."&message=3&filter=".$_POST['filter']."&filter2=".$_POST['filter2']."&list_number=".$_POST['list_number']."&id=".$id_club);
    }
  }

//pridat novy zaznam / prirazeni klubu k arene
    if ($_POST['action']=="arena_add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $id_arena=$_POST['id_arena'];
    $id_arena=$input->check_number($id_arena);
    $id=$_POST['id'];
    $id=$input->check_number($id);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_arena='.$id_arena.'&id='.$id;
    
    if (!empty($id) and !empty($id_arena)){
          $query="INSERT INTO clubs_arenas_items (id_arena,id_club)
                  VALUES (".$id_arena.",".$id.")
            ";
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
         header("Location: clubs_arenas.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: clubs_arenas.php".Odkaz."&message=1".$strReturnData);
    }
  }

//smazat zaznam / prirazeni klubu k arene
    if ($_GET['action']=="arena_delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM clubs_arenas_items WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: clubs_arenas.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: clubs_arenas.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: clubs_arenas.php".Odkaz."&message=99".$strReturnData);}
    }



    //pridat novy zaznam / links
    if ($_POST['action']=="links_add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $id_type=$_POST['id_type'];
    $id_type=$input->check_number($id_type);
    $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    $link_name=$_POST['link_name'];
    $link_name=$input->valid_text($link_name,true,true);
    $id=$_POST['id'];
    $id=$input->check_number($id);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&name='.$name.'&link_name='.$link_name.'&id_type='.$id_type.'&id='.$id;
    
    if (!empty($id) and !empty($name)and !empty($id_type)){
          $query="INSERT INTO clubs_links (id_club,link_name,name,id_type)
                  VALUES (".$id.",'".$link_name."','".$name."',".$id_type.")
            ";
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
         header("Location: clubs_links.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: clubs_links.php".Odkaz."&message=1".$strReturnData);
    }
  }

//smazat zaznam / jmeno links
    if ($_GET['action']=="links_delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM clubs_links WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: clubs_links.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: clubs_links.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: clubs_links.php".Odkaz."&message=99".$strReturnData);}
    }

//upravit zaznam / jmeno links
    if ($_POST['action']=="links_update" and $users->checkUserRight(3)){
        $bollError=false;
        $id_type=$_POST['id_type'];
        $id_type=$input->check_number($id_type);
        $link_name=$_POST['link_name'];
        $link_name=$input->valid_text($link_name,true,true);
        $name=$_POST['name'];
        $name=$input->valid_text($name,true,true);
        $id=$_POST['id'];
        $id=$input->check_number($id);
        $id_club=$_POST['id_club'];
        $id_club=$input->check_number($id_club);
        $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&link_name='.$link_name.'&name='.$name.'&id_type='.$id_type.'&id_club='.$id_club.'&id='.$id;
        
        if (!empty($id_type) and !empty($name)){
            $query="UPDATE clubs_links SET 
                    link_name='".$link_name."',name='".$name."',id_type='".$id_type."'
                    WHERE id=".$id."
            ";
            
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
         header("Location: clubs_links.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: clubs_links.php".Odkaz."&message=3&filter=".$_POST['filter']."&filter2=".$_POST['filter2']."&list_number=".$_POST['list_number']."&id=".$id_club);
    }
  }

?>