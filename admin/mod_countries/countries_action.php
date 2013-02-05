<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(23,1,0,0,0);
$input = new Input_filter();

//switch status
if ($_GET['action']=="switch" and $users->checkUserRight(3)){
      $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        
        $query = "SELECT is_important FROM countries WHERE id=".$id."";
        $result = $con->SelectQuery($query);
        $write = $result->fetch_array();
        $intStatus=$write['is_important'];
        
        if ($intStatus==1) $intStatus=0; else $intStatus=1;
        
        $query="UPDATE countries SET is_important=".$intStatus." WHERE id=".$id;
        $con->RunQuery($query);
           header("Location: countries.php".Odkaz."".$strReturnData);
        }
        else{header("Location: countries.php".Odkaz."&message=99".$strReturnData);} 
}

//pridat novy zaznam
    if ($_POST['action']=="add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    $shortcut=$_POST['shortcut'];
    $shortcut=$input->valid_text($shortcut,true,true);
    $image_flag=$_POST['image_flag'];
    $image_flag=$input->check_number($image_flag);
    $image_asociation_logo=$_POST['image_asociation_logo'];
    $image_asociation_logo=$input->check_number($image_asociation_logo);
    $hockey_asociation=$_POST['hockey_asociation'];
    $hockey_asociation=$input->valid_text($hockey_asociation,true,true);
    $address=$_POST['address'];
    $address=$input->valid_text($address,true,true);
    $telephone=$_POST['telephone'];
    $telephone=$input->valid_text($telephone,true,true);
    $fax=$_POST['fax'];
    $fax=$input->valid_text($fax,true,true);
    $email=$_POST['email'];
    $email=$input->valid_text($email,true,true);
    $link_1=$_POST['link_1'];
    $link_1=$input->valid_text($link_1,true,true);
    $link_2=$_POST['link_2'];
    $link_2=$input->valid_text($link_2,true,true);
    $link_3=$_POST['link_3'];
    $link_3=$input->valid_text($link_3,true,true);
    $year_founded=$_POST['year_founded'];
    $year_founded=$input->check_number($year_founded);
    $year_incorporated=$_POST['year_incorporated'];
    $year_incorporated=$input->check_number($year_incorporated);
    $brief_history=$_POST['brief_history'];
    $brief_history=$input->valid_text($brief_history,true,false);
    $best_achievments=$_POST['best_achievments'];
    $best_achievments=$input->valid_text($best_achievments,true,false);
    $registered_players=$_POST['registered_players'];
    $registered_players=$input->check_number($registered_players);
    $placement_at_IHWC=$_POST['placement_at_IHWC'];
    $placement_at_IHWC=$input->valid_text($placement_at_IHWC,true,true);
    $world_ranking=$_POST['world_ranking'];
    $world_ranking=$input->check_number($world_ranking);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&name='.$name.'&shortcut='.$shortcut.'&image_flag='.$image_flag.'&image_asociation_logo='.$image_asociation_logo.'&hockey_asociation='.$hockey_asociation.'&address='.urlencode($address).'&telephone='.$telephone.'&fax='.$fax.'&email='.$email.'&link_1='.$link_1.'&link_2='.$link_2.'&link_3='.$link_3.'&year_founded='.$year_founded.'&year_incorporated='.$year_incorporated.'&registered_players='.$registered_players.'&placement_at_IHWC='.$placement_at_IHWC.'&world_ranking='.$world_ranking.'&brief_history='.urlencode($brief_history).'&best_achievments='.urlencode($best_achievments);
    
    if (!empty($name) AND !empty($shortcut)){
          $query="INSERT INTO countries (last_update_user,name,shortcut,image_flag,image_asociation_logo,hockey_asociation,address,telephone,fax,email,link_1,link_2,link_3,year_founded,year_incorporated,brief_history,best_achievments,registered_players,placement_at_IHWC,world_ranking
                  )
                  VALUES (".$users->getIdUser().",'".$name."','".$shortcut."',".$image_flag.",".$image_asociation_logo.",'".$hockey_asociation."','".$address."','".$telephone."','".$fax."','".$email."','".$link_1."','".$link_2."','".$link_3."',".$year_founded.",".$year_incorporated.",'".$brief_history."','".$best_achievments."',".$registered_players.",'".$placement_at_IHWC."',".$world_ranking.")
            ";
          //echo $query; 
           if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=1;
           }
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: countries_add.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: countries.php".Odkaz."&message=1");
    }
  }


//smazat zaznam
    if ($_GET['action']=="delete" and $users->checkUserRight(4)){
    
      if (isset($_GET['id'])){
        $id_user=$_GET['id'];
        $query="DELETE FROM countries WHERE id=".$id_user;
        if ($con->RunQuery($query)==true){
           header("Location: countries.php".Odkaz."&message=2");
        }
        else{header("Location: countries.php".Odkaz."&message=99");} 
      }else{header("Location: countries.php".Odkaz."&message=99");}
    }
    
    
//upravit
   if ($_POST['action']=="update" and $users->checkUserRight(3)){
        $bollError=false;
        $name=$_POST['name'];
        $name=$input->valid_text($name,true,true);
        $shortcut=$_POST['shortcut'];
        $shortcut=$input->valid_text($shortcut,true,true);
        $id=$_POST['id'];
        $image_flag=$_POST['image_flag'];
        $image_flag=$input->check_number($image_flag);
        $image_asociation_logo=$_POST['image_asociation_logo'];
        $image_asociation_logo=$input->check_number($image_asociation_logo);
        $hockey_asociation=$_POST['hockey_asociation'];
        $hockey_asociation=$input->valid_text($hockey_asociation,true,true);
        $address=$_POST['address'];
        $address=$input->valid_text($address,true,true);
        $telephone=$_POST['telephone'];
        $telephone=$input->valid_text($telephone,true,true);
        $fax=$_POST['fax'];
        $fax=$input->valid_text($fax,true,true);
        $email=$_POST['email'];
        $email=$input->valid_text($email,true,true);
        $link_1=$_POST['link_1'];
        $link_1=$input->valid_text($link_1,true,true);
        $link_2=$_POST['link_2'];
        $link_2=$input->valid_text($link_2,true,true);
        $link_3=$_POST['link_3'];
        $link_3=$input->valid_text($link_3,true,true);
        $year_founded=$_POST['year_founded'];
        $year_founded=$input->check_number($year_founded);
        $year_incorporated=$_POST['year_incorporated'];
        $year_incorporated=$input->check_number($year_incorporated);
        $brief_history=$_POST['brief_history'];
        $brief_history=$input->valid_text($brief_history,true,false);
        $best_achievments=$_POST['best_achievments'];
        $best_achievments=$input->valid_text($best_achievments,true,false);
        $registered_players=$_POST['registered_players'];
        $registered_players=$input->check_number($registered_players);
        $placement_at_IHWC=$_POST['placement_at_IHWC'];
        $placement_at_IHWC=$input->valid_text($placement_at_IHWC,true,true);
        $world_ranking=$_POST['world_ranking'];
        $world_ranking=$input->check_number($world_ranking);
        
        $strReturnData='&id='.$id.'&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&name='.$name.'&shortcut='.$shortcut.'&image_flag='.$image_flag.'&image_asociation_logo='.$image_asociation_logo.'&hockey_asociation='.$hockey_asociation.'&address='.urlencode($address).'&telephone='.$telephone.'&fax='.$fax.'&email='.$email.'&link_1='.$link_1.'&link_2='.$link_2.'&link_3='.$link_3.'&year_founded='.$year_founded.'&year_incorporated='.$year_incorporated.'&registered_players='.$registered_players.'&placement_at_IHWC='.$placement_at_IHWC.'&world_ranking='.$world_ranking.'&brief_history='.urlencode($brief_history).'&best_achievments='.urlencode($best_achievments);
        
        if (!empty($name) AND !empty($shortcut)){
            
             $query="UPDATE countries SET last_update_user=".$users->getIdUser().",name='".$name."',shortcut='".$shortcut."',image_flag=".$image_flag.",image_asociation_logo=".$image_asociation_logo.",hockey_asociation='".$hockey_asociation."',address='".$address."',telephone='".$telephone."',fax='".$fax."',email='".$email."',link_1='".$link_1."',link_2='".$link_2."',link_3='".$link_3."',year_founded=".$year_founded.",year_incorporated=".$year_incorporated.",brief_history='".$brief_history."',best_achievments='".$best_achievments."',registered_players=".$registered_players.",placement_at_IHWC='".$placement_at_IHWC."',world_ranking=".$world_ranking."
                  WHERE id=".$id
            ;
            
            
           if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=1;
           }
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: countries_update.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: countries.php".Odkaz."&message=3".$strReturnData);
    }
  }

//pridat novy zaznam / jmeno země
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
          $query="INSERT INTO countries_names (id_country,name,int_year)
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
         header("Location: countries_names.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: countries_names.php".Odkaz."&message=1".$strReturnData);
    }
  }

//smazat zaznam / jmeno země
    if ($_GET['action']=="name_delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM countries_names WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: countries_names.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: countries_names.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: countries_names.php".Odkaz."&message=99".$strReturnData);}
    }

//upravit zaznam / jmeno země
    if ($_POST['action']=="name_update" and $users->checkUserRight(3)){
        $bollError=false;
        $id_season=$_POST['id_season'];
        $id_season=$input->check_number($id_season);
        $name=$_POST['name'];
        $name=$input->valid_text($name,true,true);
        $id=$_POST['id'];
        $id=$input->check_number($id);
        $id_country=$_POST['id_country'];
        $id_country=$input->check_number($id_country);
        $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&name='.$name.'&shortcut='.$shortcut.'&id_country='.$id_country.'&id='.$id;
        
        if (!empty($id_season) and !empty($name)){
            $query="UPDATE countries_names SET 
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
         header("Location: countries_names_update.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: countries_names.php".Odkaz."&message=3&filter=".$_POST['filter']."&filter2=".$_POST['filter2']."&list_number=".$_POST['list_number']."&id=".$id_country);
    }
  }

//pridat novy zaznam zeme/hraci
    if ($_POST['action']=="player_add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $id_player=$_POST['id_player'];
    $id_player=$input->check_number($id_player);
    $id=$_POST['id'];
    $id=$input->check_number($id);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_player='.$id_player.'&id='.$id;
    
    if (!empty($id) and !empty($id_player)){
          $query="INSERT INTO countries_players_items (id_country,id_player)
                  VALUES (".$id.",".$id_player.")
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
         header("Location: countries_players.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: countries_players.php".Odkaz."&message=1".$strReturnData);
    }
  }

//smazat zaznam / zeme-hrac
    if ($_GET['action']=="player_delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM countries_players_items WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: countries_players.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: countries_players.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: countries_players.php".Odkaz."&message=99".$strReturnData);}
    }

//upravit hromadne contries
    if ($_POST['action']=="update_mass_countries" and $users->checkUserRight(2)){
    
    $intError=0; $intCorrect=0;
    $bollError=false;
    
    $number=$_POST['number'];
    //echo $number; 
    
    for ($i=1; $i<=($number+1); $i++){
      $id_country=$_POST['id_country'][$i];
      $id_country=$input->check_number($id_country);
    
      if (!empty($id_country)){
      
      
      $placement_at_IHWC=$_POST['placement_at_IHWC'][$i];
      $placement_at_IHWC=$input->valid_text($placement_at_IHWC,true,true);
      $world_ranking=$_POST['world_ranking'][$i];
      $world_ranking=$input->valid_text($world_ranking,true,true);
      $registered_players=$_POST['registered_players'][$i];
      $registered_players=$input->valid_text($registered_players,true,true);
      
      $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'];
      if (!empty($id_country)){
       //last_update_user=".$users->getIdUser()."
           $query="UPDATE countries SET placement_at_IHWC='".$placement_at_IHWC."',world_ranking='".$world_ranking."',registered_players='".$registered_players."' WHERE id=".$id_country;
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
    
    header("Location: countries_mass_update.php".Odkaz."&message=3".$strReturnData."&intCorrect=".$intCorrect."&intError=".$intError);
}




?>