<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(27,1,0,0,0);
$games = new games($con);
$input = new Input_filter();

//pridat novy zaznam
    if ($_POST['action']=="add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $id_club=$_POST['id_club'];
    $id_club=$input->check_number($id_club);
    $id_status=$_POST['id_status'];
    $id_status=$input->check_number($id_status);
    $id_status_info=$_POST['id_status_info'];
    $id_status_info=$input->valid_text($id_status_info,true,true);
    $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    $also_known_as=$_POST['also_known_as'];
    $also_known_as=$input->valid_text($also_known_as,true,true);
    $id_country=$_POST['id_country'];
    $id_country=$input->check_number($id_country);
    $id_photo_folder=$_POST['id_photo_folder'];
    $id_photo_folder=$input->check_number($id_photo_folder);
    $address=$_POST['address'];
    $address=$input->valid_text($address,true,true);
    $telephone=$_POST['telephone'];
    $telephone=$input->valid_text($telephone,true,true);
    $fax=$_POST['fax'];
    $fax=$input->valid_text($fax,true,true);
    $email=$_POST['email'];
    $email=$input->valid_text($email,true,true);
    $GPS_location=$_POST['GPS_location'];
    $GPS_location=$input->valid_text($GPS_location,true,true);
    $capacity_overall=$_POST['capacity_overall'];
    $capacity_overall=$input->check_number($capacity_overall);
    $capacity_seating=$_POST['capacity_seating'];
    $capacity_seating=$input->check_number($capacity_seating);
    $rink_size=$_POST['rink_size'];
    $rink_size=$input->valid_text($rink_size,true,true);
    $year_built=$_POST['year_built'];
    $year_built=$input->check_number($year_built);
    $roofed=$_POST['roofed'];
    $roofed=$input->check_number($roofed);
    $year_roofed=$_POST['year_roofed'];
    $year_roofed=$input->check_number($year_roofed);
    $last_major_reconstruction=$_POST['last_major_reconstruction'];
    $last_major_reconstruction=$input->valid_text($last_major_reconstruction,true,true);
    $also_used_for=$_POST['also_used_for'];
    $also_used_for=$input->valid_text($also_used_for,true,false);
    $most_notable_games=$_POST['most_notable_games'];
    $most_notable_games=$input->valid_text($most_notable_games,true,false);
    $link_1=$_POST['link_1'];
    $link_1=$input->valid_text($link_1,true,true);
    $link_2=$_POST['link_2'];
    $link_2=$input->valid_text($link_2,true,true);
    $link_3=$_POST['link_3'];
    $link_3=$input->valid_text($link_3,true,true);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_status='.$id_status.'&id_status_info='.$id_status_info.'&name='.$name.'&also_known_as='.$also_known_as.'&id_country='.$id_country.'&id_photo_folder='.$id_photo_folder.'&telephone='.$telephone.'&fax='.$fax.'&email='.$email.'&GPS_location='.$GPS_location.'&capacity_overall='.$capacity_overall.'&capacity_seating='.$capacity_seating.'&rink_size='.$rink_size.'&year_built='.$year_built.'&roofed='.$roofed.'&year_roofed='.$year_roofed.'&last_major_reconstruction='.$last_major_reconstruction.'&link_1='.$link_1.'&link_2='.$link_2.'&link_3='.$link_3.'&address='.urlencode($address).'&also_used_for='.urlencode($also_used_for).'&most_notable_games='.urlencode($most_notable_games);
    
    if (!empty($name) and !empty($id_country) and !empty($id_status) and !empty($roofed)){
          $query="INSERT INTO arenas (last_update_user,name,also_known_as,address,id_country,telephone,fax,email,GPS_location,capacity_overall,capacity_seating,rink_size,roofed,year_built,year_roofed,last_major_reconstruction,also_used_for,most_notable_games,link_1,link_2,link_3,id_photo_folder,id_status,id_status_info)
                  VALUES (".$users->getIdUser().",'".$name."','".$also_known_as."','".$address."',".$id_country.",'".$telephone."','".$fax."','".$email."','".$GPS_location."',".$capacity_overall.",".$capacity_seating.",'".$rink_size."',".$roofed.",".$year_built.",".$year_roofed.",'".$last_major_reconstruction."','".$also_used_for."','".$most_notable_games."','".$link_1."','".$link_2."','".$link_3."',".$id_photo_folder.",".$id_status.",'".$id_status_info."')
            ";
           // echo $query;
           if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=98;
           }else{
              
              $query_sub="SELECT id FROM arenas ORDER by id DESC LIMIT 1";
              $result_sub = $con->SelectQuery($query_sub);
              $write_sub = $result_sub->fetch_array();
              $id_arena=$write_sub['id'];
              
              //zapis prirazeni arena->klub
              if (!empty($id_club)){
              
              $query="INSERT INTO clubs_arenas_items (id_arena,id_club)
                  VALUES (".$id_arena.",".$id_club.")
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
         header("Location: arenas_add.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: arenas.php".Odkaz."&message=1&id=".$id_arena.$strReturnData);
    }
  }


//smazat zaznam
    if ($_GET['action']=="delete" and $users->checkUserRight(4)){
    $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM arenas WHERE id=".$id;
        if ($con->RunQuery($query)==true){
            $query="DELETE FROM clubs_arenas_items WHERE id_arena=".$id;
            $con->RunQuery($query);
            $query="DELETE FROM arenas_names WHERE id_arena=".$id;
            $con->RunQuery($query);
           header("Location: arenas.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: arenas.php".Odkaz."&message=99".$strReturnData);} 
      }else{header("Location: arenas.php".Odkaz."&message=99".$strReturnData);}
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
    $also_known_as=$_POST['also_known_as'];
    $also_known_as=$input->valid_text($also_known_as,true,true);
    $id_country=$_POST['id_country'];
    $id_country=$input->check_number($id_country);
    $id_photo_folder=$_POST['id_photo_folder'];
    $id_photo_folder=$input->check_number($id_photo_folder);
    $address=$_POST['address'];
    $address=$input->valid_text($address,true,true);
    $telephone=$_POST['telephone'];
    $telephone=$input->valid_text($telephone,true,true);
    $fax=$_POST['fax'];
    $fax=$input->valid_text($fax,true,true);
    $email=$_POST['email'];
    $email=$input->valid_text($email,true,true);
    $GPS_location=$_POST['GPS_location'];
    $GPS_location=$input->valid_text($GPS_location,true,true);
    $capacity_overall=$_POST['capacity_overall'];
    $capacity_overall=$input->check_number($capacity_overall);
    $capacity_seating=$_POST['capacity_seating'];
    $capacity_seating=$input->check_number($capacity_seating);
    $rink_size=$_POST['rink_size'];
    $rink_size=$input->valid_text($rink_size,true,true);
    $year_built=$_POST['year_built'];
    $year_built=$input->check_number($year_built);
    $roofed=$_POST['roofed'];
    $roofed=$input->check_number($roofed);
    $year_roofed=$_POST['year_roofed'];
    $year_roofed=$input->check_number($year_roofed);
    $last_major_reconstruction=$_POST['last_major_reconstruction'];
    $last_major_reconstruction=$input->valid_text($last_major_reconstruction,true,true);
    $also_used_for=$_POST['also_used_for'];
    $also_used_for=$input->valid_text($also_used_for,true,false);
    $most_notable_games=$_POST['most_notable_games'];
    $most_notable_games=$input->valid_text($most_notable_games,true,false);
    $link_1=$_POST['link_1'];
    $link_1=$input->valid_text($link_1,true,true);
    $link_2=$_POST['link_2'];
    $link_2=$input->valid_text($link_2,true,true);
    $link_3=$_POST['link_3'];
    $link_3=$input->valid_text($link_3,true,true);
        
    $id=$_POST['id'];
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id.'&id_status='.$id_status.'&id_status_info='.$id_status_info.'&name='.$name.'&also_known_as='.$also_known_as.'&id_country='.$id_country.'&id_photo_folder='.$id_photo_folder.'&telephone='.$telephone.'&fax='.$fax.'&email='.$email.'&GPS_location='.$GPS_location.'&capacity_overall='.$capacity_overall.'&capacity_seating='.$capacity_seating.'&rink_size='.$rink_size.'&year_built='.$year_built.'&roofed='.$roofed.'&year_roofed='.$year_roofed.'&last_major_reconstruction='.$last_major_reconstruction.'&link_1='.$link_1.'&link_2='.$link_2.'&link_3='.$link_3.'&address='.urlencode($address).'&also_used_for='.urlencode($also_used_for).'&most_notable_games='.urlencode($most_notable_games);
    
    if (!empty($name) and !empty($id_country) and !empty($id) and !empty($id_status) and !empty($roofed)){
        
        $query="UPDATE arenas SET
                  last_update_user=".$users->getIdUser().",name='".$name."',also_known_as='".$also_known_as."',address='".$address."',id_country=".$id_country.",telephone='".$telephone."',
                  fax='".$fax."',email='".$email."',GPS_location='".$GPS_location."',capacity_overall=".$capacity_overall.",capacity_seating=".$capacity_seating.",rink_size='".$rink_size."',
                  roofed=".$roofed.",year_built=".$year_built.",year_roofed=".$year_roofed.",last_major_reconstruction='".$last_major_reconstruction."',also_used_for='".$also_used_for."',
                  most_notable_games='".$most_notable_games."',link_1='".$link_1."',link_2='".$link_2."',link_3='".$link_3."',id_photo_folder=".$id_photo_folder.",id_status=".$id_status.",id_status_info='".$id_status_info."'
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
         header("Location: arenas_update.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: arenas.php".Odkaz."&message=3".$strReturnData);
    }
  }

//switch status
if ($_GET['action']=="switch" and $users->checkUserRight(3)){
      $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        
        $query = "SELECT id_status FROM arenas WHERE id=".$id."";
        $result = $con->SelectQuery($query);
        $write = $result->fetch_array();
        $intStatus=$write['id_status'];
        
        $query = "SELECT id FROM arenas_status_list WHERE id=".($intStatus+1)."";
        if ($con->GetQueryNum($query)==0){
          $intStatus=1;
        }else{
          $intStatus++;
        }
        
        $query="UPDATE arenas SET id_status=".$intStatus." WHERE id=".$id;
        $con->RunQuery($query);
        
           header("Location: arenas.php".Odkaz."".$strReturnData);
        }
        else{header("Location: arenas.php".Odkaz."&message=99".$strReturnData);} 
}

//switch status Important
if ($_GET['action']=="switch_important" and $users->checkUserRight(3)){
      $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        
        $query = "SELECT important_arenas FROM arenas WHERE id=".$id."";
        $result = $con->SelectQuery($query);
        $write = $result->fetch_array();
        $intStatus=$write['important_arenas'];
        
        if ($intStatus==0) $intStatus=1; else $intStatus=0;
        
        $query="UPDATE arenas SET important_arenas=".$intStatus." WHERE id=".$id;
        $con->RunQuery($query);
        
           header("Location: arenas.php".Odkaz."".$strReturnData);
        }
        else{header("Location: arenas.php".Odkaz."&message=99".$strReturnData);} 
}


//pridat novy zaznam / prirazeni klubu k arene
    if ($_POST['action']=="assign_add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $id_club=$_POST['id_club'];
    $id_club=$input->check_number($id_club);
    $id=$_POST['id'];
    $id=$input->check_number($id);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_club='.$id_club.'&id='.$id;
    
    if (!empty($id) and !empty($id_club)){
          $query="INSERT INTO clubs_arenas_items (id_club,id_arena)
                  VALUES (".$id_club.",".$id.")
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
         header("Location: arenas_assign.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: arenas_assign.php".Odkaz."&message=1".$strReturnData);
    }
  }

//smazat zaznam / prirazeni klubu k arene
    if ($_GET['action']=="assign_delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM clubs_arenas_items WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: arenas_assign.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: arenas_assign.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: arenas_assign.php".Odkaz."&message=99".$strReturnData);}
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
          $query="INSERT INTO arenas_names (id_arena,name,int_year)
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
         header("Location: arenas_names.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: arenas_names.php".Odkaz."&message=1".$strReturnData);
    }
  }

//smazat zaznam / jmeno ligy
    if ($_GET['action']=="name_delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM arenas_names WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: arenas_names.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: arenas_names.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: arenas_names.php".Odkaz."&message=99".$strReturnData);}
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
        $id_arena=$_POST['id_arena'];
        $id_arena=$input->check_number($id_arena);
        $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&name='.$name.'&shortcut='.$shortcut.'&id_arena='.$id_arena.'&id='.$id;
        
        if (!empty($id_season) and !empty($name)){
            $query="UPDATE arenas_names SET 
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
         header("Location: arenas_names.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: arenas_names.php".Odkaz."&message=3&filter=".$_POST['filter']."&filter2=".$_POST['filter2']."&list_number=".$_POST['list_number']."&id=".$id_arena);
    }
  }
  
      
?>