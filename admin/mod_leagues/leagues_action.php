<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(24,1,0,0,0);
$input = new Input_filter();

//pridat novy zaznam
    if ($_POST['action']=="add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    $shortcut=$_POST['shortcut'];
    $shortcut=$input->valid_text($shortcut,true,true);
    $id_country=$_POST['id_country'];
    $english_name=$_POST['english_name'];
    $english_name=$input->valid_text($english_name,true,true);
    $id_country=$input->check_number($id_country);
    $id_order=$_POST['id_order'];
    $id_order=$input->check_number($id_order);
    $id_image=$_POST['id_image'];
    $id_image=$input->check_number($id_image);
    $name_short=$_POST['name_short'];
    $name_short=$input->valid_text($name_short,true,true);
    $administered_by=$_POST['administered_by'];
    $administered_by=$input->valid_text($administered_by,true,true);
    $head_manager=$_POST['head_manager'];
    $head_manager=$input->valid_text($head_manager,true,true);
    $link_1=$_POST['link_1'];
    $link_1=$input->valid_text($link_1,true,true);
    $link_2=$_POST['link_2'];
    $link_2=$input->valid_text($link_2,true,true);
    $link_3=$_POST['link_3'];
    $link_3=$input->valid_text($link_3,true,true);
    $league_format=$_POST['league_format'];
    $league_format=$input->valid_text($league_format,true,false);
    $promotion=$_POST['promotion'];
    $promotion=$input->valid_text($promotion,true,false);
    $playoff=$_POST['playoff'];
    $playoff=$input->check_number($playoff);
    $year_of_start=$_POST['year_of_start'];
    $year_of_start=$input->check_number($year_of_start);
    $brief_history=$_POST['brief_history'];
    $brief_history=$input->valid_text($brief_history,true,false);
    $youth_league=$_POST['youth_league'];
    $youth_league=$input->check_number($youth_league);
    if ($youth_league==1){
      $youth_league_id=$_POST['youth_league_id'];
      $youth_league_id=$input->check_number($youth_league_id);
    }else{
      $youth_league_id=0;
    }
    $league_status=$_POST['league_status'];
    $league_status=$input->check_number($league_status);
    $is_tournament=$_POST['is_tournament'];
    $is_tournament=$input->check_number($is_tournament);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_order='.$id_order.'&is_tournament='.$is_tournament.'&name='.$name.'$english_name='.$english_name.'&shortcut='.$shortcut.'&id_country='.$id_country.'&id_image='.$id_image.'&name_short='.$name_short.'&administered_by='.$administered_by.'&head_manager='.$head_manager.'&link_1='.$link_1.'&link_2='.$link_2.'&link_3='.$link_3.'&playoff='.$playoff.'&year_of_start='.$year_of_start.'&youth_league='.$youth_league.'&youth_league_id='.$youth_league_id.'&league_status='.$league_status.'&brief_history='.urlencode($brief_history).'&promotion='.urlencode($promotion).'&league_format='.urlencode($league_format);
    $english_name=$input->valid_text($english_name,true,true);
    if (!empty($name) and !empty($id_country) and !empty($league_status)){
          $query="INSERT INTO leagues (last_update_user,name,shortcut,id_image,name_short,english_name,administered_by,head_manager,link_1,link_2,link_3,league_format,promotion,playoff,year_of_start,brief_history,youth_league,youth_league_id,league_status,is_tournament,id_order)
                  VALUES (".$users->getIdUser().",'".$name."','".$shortcut."','".$id_image."','".$name_short."','".$english_name."','".$administered_by."','".$head_manager."','".$link_1."','".$link_2."','".$link_3."','".$league_format."','".$promotion."','".$playoff."','".$year_of_start."','".$brief_history."','".$youth_league."','".$youth_league_id."','".$league_status."','".$is_tournament."',".$id_order.")
            ";
           if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=98;
           }else{
              //zapis prirazeni liga->zeme
              $query_sub="SELECT id FROM leagues ORDER by id DESC LIMIT 1";
              $result_sub = $con->SelectQuery($query_sub);
              $write_sub = $result_sub->fetch_array();
              $id_league=$write_sub['id'];
              
              $query="INSERT INTO leagues_countries_items (id_league,id_country)
                  VALUES (".$id_league.",".$id_country.")
              ";
              if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=98;
              }
              
              //vytvoreni default stages
              $query_stages="SELECT name,id FROM stats_types_list ORDER by id";
              $result_stages = $con->SelectQuery($query_stages);
              while($write_stages = $result_stages->fetch_array())
	            {
	             if ($write_stages['name']==1) $is_table=1; else {$is_table=0;}
	             $query="INSERT INTO games_stages (id_league,name,id_detault)
                  VALUES (".$id_league.",'".$write_stages['name']."',".$write_stages['id'].")
               ";
               $con->RunQuery($query);
              }
              
           }
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: leagues_add.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: leagues.php".Odkaz."&message=1".$strReturnData);
    }
  }


//smazat zaznam
    if ($_GET['action']=="delete" and $users->checkUserRight(4)){
    $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id_user=$_GET['id'];
        $query="DELETE FROM leagues WHERE id=".$id_user;
        if ($con->RunQuery($query)==true){
           header("Location: leagues.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: leagues.php".Odkaz."&message=99".$strReturnData);} 
      }else{header("Location: leagues.php".Odkaz."&message=99".$strReturnData);}
    }
    
    
//upravit zaznam
    if ($_POST['action']=="update" and $users->checkUserRight(3)){
        $bollError=false;
        $id=$_POST['id'];
         $name=$_POST['name'];
        $name=$input->valid_text($name,true,true);
        $shortcut=$_POST['shortcut'];
        $shortcut=$input->valid_text($shortcut,true,true);
        $id_country=$_POST['id_country'];
        $id_country=$input->check_number($id_country);
        $id_order=$_POST['id_order'];
        $id_order=$input->check_number($id_order);
        $id_image=$_POST['id_image'];
        $id_image=$input->check_number($id_image);
        $name_short=$_POST['name_short'];
        $name_short=$input->valid_text($name_short,true,true);
        $english_name=$_POST['english_name'];
        $english_name=$input->valid_text($english_name,true,true);
        $administered_by=$_POST['administered_by'];
        $administered_by=$input->valid_text($administered_by,true,true);
        $head_manager=$_POST['head_manager'];
        $head_manager=$input->valid_text($head_manager,true,true);
        $link_1=$_POST['link_1'];
        $link_1=$input->valid_text($link_1,true,true);
        $link_2=$_POST['link_2'];
        $link_2=$input->valid_text($link_2,true,true);
        $link_3=$_POST['link_3'];
        $link_3=$input->valid_text($link_3,true,true);
        $league_format=$_POST['league_format'];
        $league_format=$input->valid_text($league_format,true,false);
        $promotion=$_POST['promotion'];
        $promotion=$input->valid_text($promotion,true,false);
        $playoff=$_POST['playoff'];
        $playoff=$input->check_number($playoff);
        $year_of_start=$_POST['year_of_start'];
        $year_of_start=$input->check_number($year_of_start);
        $brief_history=$_POST['brief_history'];
        $brief_history=$input->valid_text($brief_history,true,false);
        $youth_league=$_POST['youth_league'];
        $youth_league=$input->check_number($youth_league);
        if ($youth_league==1){
          $youth_league_id=$_POST['youth_league_id'];
          $youth_league_id=$input->check_number($youth_league_id);
        }else{
          $youth_league_id=0;
        }
        $league_status=$_POST['league_status'];
        $league_status=$input->check_number($league_status);
        $is_tournament=$_POST['is_tournament'];
        $is_tournament=$input->check_number($is_tournament);
    
        $strReturnData='&id='.$id.'&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_order='.$id_order.'&is_tournament='.$is_tournament.'&name='.$name.'$english_name='.$english_name.'&shortcut='.$shortcut.'&id_country='.$id_country.'&id_image='.$id_image.'&name_short='.$name_short.'&administered_by='.$administered_by.'&head_manager='.$head_manager.'&link_1='.$link_1.'&link_2='.$link_2.'&link_3='.$link_3.'&playoff='.$playoff.'&year_of_start='.$year_of_start.'&youth_league='.$youth_league.'&youth_league_id='.$youth_league_id.'&league_status='.$league_status.'&brief_history='.urlencode($brief_history).'&promotion='.urlencode($promotion).'&league_format='.urlencode($league_format);
        
        if (!empty($name) or !empty($shortcut) or !empty($league_status)){
            $query="UPDATE leagues SET last_update_user=".$users->getIdUser().",name='".$name."',shortcut='".$shortcut."',id_image='".$id_image."',name_short='".$name_short."',english_name='".$english_name."',administered_by='".$administered_by."',
                  head_manager='".$head_manager."',link_1='".$link_1."',link_2='".$link_2."',link_3='".$link_3."',league_format='".$league_format."',promotion='".$promotion."',
                  id_order='.$id_order.',is_tournament='".$is_tournament."',playoff='".$playoff."',year_of_start='".$year_of_start."',brief_history='".$brief_history."',youth_league='".$youth_league."',youth_league_id='".$youth_league_id."',league_status='".$league_status."'
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
         header("Location: leagues_update.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: leagues.php".Odkaz."&message=3".$strReturnData);
    }
  }

//pridat novy zaznam / prirazeni ligy k zemi
    if ($_POST['action']=="assign_add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $id_country=$_POST['id_country'];
    $id_country=$input->check_number($id_country);
    $id=$_POST['id'];
    $id=$input->check_number($id);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_country='.$id_country.'&id='.$id;
    
    if (!empty($id) and !empty($id_country)){
          $query="INSERT INTO leagues_countries_items (id_league,id_country)
                  VALUES (".$id.",".$id_country.")
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
         header("Location: leagues_assign.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: leagues_assign.php".Odkaz."&message=1".$strReturnData);
    }
  }

//smazat zaznam / prirazeni ligy k zemi
    if ($_GET['action']=="assign_delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM leagues_countries_items WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: leagues_assign.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: leagues_assign.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: leagues_assign.php".Odkaz."&message=99".$strReturnData);}
    }

      
//pridat novy zaznam / jmeno ligy
    if ($_POST['action']=="name_add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $id_season=$_POST['id_season'];
    $id_season=$input->check_number($id_season);
    $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    
    $place=$_POST['place'];
    $place=$input->valid_text($place,true,true);
    $link=$_POST['link'];
    $link=$input->valid_text($link,true,true);
    $standings=$_POST['standings'];
    $standings=$input->valid_text($standings,true,false);
    $additional_data=$_POST['additional_data'];
    $additional_data=$input->valid_text($additional_data,true,false);
    
    $id=$_POST['id'];
    $id=$input->check_number($id);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&name='.$name.'&place='.$place.'&link='.$link.'&id_season='.$id_season.'&id='.$id;
    
    if (!empty($id) and !empty($name)and !empty($id_season)){
          $query="INSERT INTO leagues_names (id_league,name,int_year,place,link,standings,additional_data)
                  VALUES (".$id.",'".$name."',".$id_season.",'".$place."','".$link."','".$standings."','".$additional_data."')
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
         header("Location: leagues_names.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: leagues_names.php".Odkaz."&message=1".$strReturnData);
    }
  }

//smazat zaznam / jmeno ligy
    if ($_GET['action']=="name_delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM leagues_names WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: leagues_names.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: leagues_names.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: leagues_names.php".Odkaz."&message=99".$strReturnData);}
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
        $id_league=$_POST['id_league'];
        $id_league=$input->check_number($id_league);
        
        $place=$_POST['place'];
    $place=$input->valid_text($place,true,true);
    $link=$_POST['link'];
    $link=$input->valid_text($link,true,true);
    $standings=$_POST['standings'];
    $standings=$input->valid_text($standings,true,false);
    $additional_data=$_POST['additional_data'];
    $additional_data=$input->valid_text($additional_data,true,false);
        
        $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&name='.$name.'&place='.$place.'&link='.$link.'&shortcut='.$shortcut.'&id_league='.$id_league.'&id='.$id;
        
        if (!empty($id_season) and !empty($name)){
            $query="UPDATE leagues_names SET 
                    name='".$name."',int_year='".$id_season."' ,place='".$place."',link='".$link."',standings='".$standings."',additional_data='".$additional_data."'
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
         header("Location: leagues_names_update.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: leagues_names.php".Odkaz."&message=3&filter=".$_POST['filter']."&filter2=".$_POST['filter2']."&list_number=".$_POST['list_number']."&id=".$id_league);
    }
  }
  
  

  //pridat novy zaznam / stages
    if ($_POST['action']=="stage_add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    $description=$_POST['description'];
    $description=$input->valid_text($description,true,true);

    
    $id=$_POST['id'];
    $id=$input->check_number($id);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&name='.$name.'&id='.$id;
    
    if (!empty($id) and !empty($name)){
          $query="INSERT INTO games_stages (id_league,name,description)
                  VALUES (".$id.",'".$name."','".$description."')
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
         header("Location: leagues_stages.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: leagues_stages.php".Odkaz."&message=1".$strReturnData);
    }
  }

//smazat zaznam / stages
    if ($_GET['action']=="stage_delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM games_stages WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: leagues_stages.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: leagues_stages.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: leagues_stages.php".Odkaz."&message=99".$strReturnData);}
    }

//upravit zaznam / stages
    if ($_POST['action']=="stage_update" and $users->checkUserRight(3)){
        $bollError=false;
        
        $name=$_POST['name'];
        $name=$input->valid_text($name,true,true);
        $description=$_POST['description'];
        $description=$input->valid_text($description,true,true);
        
        $id=$_POST['id'];
        $id=$input->check_number($id);
        $id_league=$_POST['id_league'];
        $id_league=$input->check_number($id_league);
        
                
        $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&name='.$name.'&id_league='.$id_league.'&id='.$id;
        
        if (!empty($name)){
        
         $query="UPDATE games_stages SET name='".$name."',description='".$description."' WHERE id=".$id;
         // echo $query;
            
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
         header("Location: leagues_stages_update.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: leagues_stages.php".Odkaz."&message=3&filter=".$_POST['filter']."&filter2=".$_POST['filter2']."&list_number=".$_POST['list_number']."&id=".$id_league);
    }
  }




//pridat novy zaznam / winner
    if ($_POST['action']=="winner_add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $id_season=$_POST['id_season'];
    $id_season=$input->check_number($id_season);
    $id_club=$_POST['id_club'];
    $id_club=$input->check_number($id_club);
    $id=$_POST['id'];
    $id=$input->check_number($id);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_club='.$id_club.'&id_season='.$id_season.'&id='.$id;
    
    if (!empty($id) and !empty($id_club)and !empty($id_season)){
          $query="INSERT INTO leagues_past_winners (id_league,id_club,int_year)
                  VALUES (".$id.",".$id_club.",".$id_season.")
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
         header("Location: leagues_winners.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: leagues_winners.php".Odkaz."&message=1".$strReturnData);
    }
  }

//smazat zaznam / winner
    if ($_GET['action']=="winner_delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM leagues_past_winners WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: leagues_winners.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: leagues_winners.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: leagues_winners.php".Odkaz."&message=99".$strReturnData);}
    }

//upravit zaznam / winners
    if ($_POST['action']=="winners_update" and $users->checkUserRight(3)){
        $bollError=false;
        $id_season=$_POST['id_season'];
        $id_season=$input->check_number($id_season);
        $id_club=$_POST['id_club'];
        $id_club=$input->check_number($id_club);
        $id=$_POST['id'];
        $id=$input->check_number($id);
        $id_league=$_POST['id_league'];
        $id_league=$input->check_number($id_league);
        $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_club='.$id_club.'&id_league='.$id_league.'&id='.$id;
        
        if (!empty($id_season) and !empty($id_club)){
            $query="UPDATE leagues_past_winners SET 
                    id_club='".$id_club."',int_year='".$id_season."'
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
         header("Location: leagues_winners_update.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: leagues_winners.php".Odkaz."&message=3&filter=".$_POST['filter']."&filter2=".$_POST['filter2']."&list_number=".$_POST['list_number']."&id=".$id_league);
    }
  }

//switch status
if ($_GET['action']=="switch" and $users->checkUserRight(3)){
      $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        
        $query = "SELECT league_status FROM leagues WHERE id=".$id."";
        $result = $con->SelectQuery($query);
        $write = $result->fetch_array();
        $intStatus=$write['league_status'];
        
        $query = "SELECT id FROM leagues_status_list WHERE id=".($intStatus+1)."";
        if ($con->GetQueryNum($query)==0){
          $intStatus=1;
        }else{
          $intStatus++;
        }
        
        $query="UPDATE leagues SET league_status=".$intStatus." WHERE id=".$id;
        $con->RunQuery($query);
        
           header("Location: leagues.php".Odkaz."".$strReturnData);
        }
        else{header("Location: leagues.php".Odkaz."&message=99".$strReturnData);} 
}

//switch status  TOURNAMENT
if ($_GET['action']=="switch_tournament" and $users->checkUserRight(3)){
      $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        
        $query = "SELECT is_tournament  FROM leagues WHERE id=".$id."";
        $result = $con->SelectQuery($query);
        $write = $result->fetch_array();
        $intStatus=$write['is_tournament'];
        
        if ($intStatus==2){
          $intStatus=1;
        }else{
          $intStatus=2;
        }
        
        $query="UPDATE leagues SET is_tournament=".$intStatus." WHERE id=".$id;
        $con->RunQuery($query);
        
           header("Location: leagues.php".Odkaz."".$strReturnData);
        }
        else{header("Location: leagues.php".Odkaz."&message=99".$strReturnData);} 
}
            
?>
