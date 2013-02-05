<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(32,1,0,0,0);
$input = new Input_filter();
$games = new games($con);
$xml = new xml_generator($con);

//pridat novy zaznam
    if ($_POST['action']=="add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    
    $id_league=$_POST['id_league'];
    $id_league=$input->check_number($id_league);
    $id_season=$_POST['id_season'];
    $id_season=$input->check_number($id_season);
    $id_stage=$_POST['id_stage'];
    $id_stage=$input->check_number($id_stage);
    $standings_type=$_POST['standings_type'];
    $standings_type=$input->check_number($standings_type);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_league='.$id_league.'&id_season='.$id_season.'&name='.$name.'&id_stage='.$id_stage.'&standings_type='.$standings_type;
    if (!empty($name) and !empty($id_league) and !empty($id_season) and !empty($id_stage) and !empty($standings_type)){
          $query="INSERT INTO standings (id_user,name,id_league,id_season,id_stage,id_type)
                  VALUES (".$users->getIdUser().",'".$name."',".$id_league.",".$id_season.",".$id_stage.",".$standings_type.")
            ";
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
         header("Location: standings_add.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: standings.php".Odkaz."&message=1".$strReturnData);
    }
  }

//upravit zaznam
    if ($_POST['action']=="update" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $id=$_POST['id'];
    $id=$input->check_number($id);
    
    $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    
    $id_league=$_POST['id_league'];
    $id_league=$input->check_number($id_league);
    $id_season=$_POST['id_season'];
    $id_season=$input->check_number($id_season);
    $id_stage=$_POST['id_stage'];
    $id_stage=$input->check_number($id_stage);
    $standings_type=$_POST['standings_type'];
    $standings_type=$input->check_number($standings_type);
    
    $p_wins=$_POST['p_wins'];
    $p_wins=$input->check_number($p_wins);
    $p_wins_pp=$_POST['p_wins_pp'];
    $p_wins_pp=$input->check_number($p_wins_pp);
    $p_losts_pp=$_POST['p_losts_pp'];
    $p_losts_pp=$input->check_number($p_losts_pp);
    $p_draws=$_POST['p_draws'];
    $p_draws=$input->check_number($p_draws);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_league='.$id_league.'&id_season='.$id_season.'&name='.$name.'&id_stage='.$id_stage.'&standings_type='.$standings_type.'&p_wins='.$p_wins.'&p_wins_pp='.$p_wins_pp.'&p_losts_pp='.$p_losts_pp.'&p_draws='.$p_draws.'&id2='.$id;
    if (!empty($id) and !empty($name) and !empty($id_league) and !empty($id_season) and !empty($id_stage) and !empty($standings_type) and !empty($p_wins) and !empty($p_wins_pp) and !empty($p_losts_pp) and !empty($p_draws)){
          $query="UPDATE standings SET id_user=".$users->getIdUser().",name='".$name."',id_league=".$id_league.",id_season=".$id_season.",id_stage=".$id_stage.",id_type=".$standings_type.",p_wins=".$p_wins.",p_wins_pp=".$p_wins_pp.",p_losts_pp=".$p_losts_pp.",p_draws=".$p_draws." WHERE id=".$id;
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
         header("Location: standings_update.php".Odkaz."&id='.$id.'&message=".$strError.$strReturnData);
    }else{
         header("Location: standings.php".Odkaz."&message=3".$strReturnData);
    }
  }          

//smazat zaznam / STANDINGS
    if ($_GET['action']=="delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM standings WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           
           $query="DELETE FROM standings_groups WHERE id_standings=".$id;
           $con->RunQuery($query);
           
           $query="DELETE FROM standings_teams WHERE id_standings=".$id;
           $con->RunQuery($query);
           
           $query="DELETE FROM standings_info WHERE id_standings=".$id;
           $con->RunQuery($query);
           
           $query="DELETE FROM standings_lines WHERE id_standings=".$id;
           $con->RunQuery($query);
           
           $xml->generate_xml_standings($id);
           
           header("Location: standings.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: standings.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: standings.php".Odkaz."&message=99".$strReturnData);}
    }
    
//switch status
if ($_GET['action']=="switch" and $users->checkUserRight(3)){
      $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        
        $query = "SELECT id_type FROM standings WHERE id=".$id."";
        $result = $con->SelectQuery($query);
        $write = $result->fetch_array();
        $intStatus=$write['id_type'];
        
        $query = "SELECT id FROM standings_type_list WHERE id=".($intStatus+1)."";
        if ($con->GetQueryNum($query)==0){
          $intStatus=1;
        }else{
          $intStatus++;
        }
        
        $query="UPDATE standings SET id_type=".$intStatus." WHERE id=".$id;
        $con->RunQuery($query);
        
           header("Location: standings.php".Odkaz."".$strReturnData);
        }
        else{header("Location: standings.php".Odkaz."&message=99".$strReturnData);} 
}

//pridat novy zaznam  GROUPS
    if ($_POST['action']=="group_add" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    $id_standings=$_POST['id_standings'];
    $id_standings=$input->check_number($id_standings);
    $order_group=$_POST['order_group'];
    $order_group=$input->check_number($order_group);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&order_group='.$order_group.'&id_standings='.$id_standings;
    if (!empty($name) and !empty($order_group) and !empty($id_standings)){
          $query="INSERT INTO standings_groups (name,id_standings,order_group)
                  VALUES ('".$name."',".$id_standings.",".$order_group.")
            ";
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
         header("Location: standings_groups.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         $xml->generate_xml_standings($id_standings);
         header("Location: standings_groups.php".Odkaz."&message=1".$strReturnData);
    }
  }

//upravit zaznam    GROUPS
    if ($_POST['action']=="group_update" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $id=$_POST['id'];
    $id=$input->check_number($id);
    
    $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    $order_group=$_POST['order_group'];
    $order_group=$input->check_number($order_group);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&order_group='.$order_group.'&id_standings='.$_POST['id_standings'];
    if (!empty($id) and !empty($name) and !empty($order_group)){
          
            $query="UPDATE standings_groups SET name='".$name."',order_group=".$order_group." WHERE id=".$id;
             
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
         header("Location: standings_groups_update.php".Odkaz."&id='.$id.'&message=".$strError.$strReturnData);
    }else{
         header("Location: standings_groups.php".Odkaz."&message=3".$strReturnData);
    }
  }          

//smazat zaznam / GROUPS
    if ($_GET['action']=="group_delete" and $users->checkUserRight(4)){
    $strReturnData='&id_standings='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM standings_groups WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: standings_groups.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: standings_groups.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: standings_groups.php".Odkaz."&message=99".$strReturnData);}
    }
    

//pridat novy zaznam  CLUB to TABLE
    if ($_POST['action']=="club_add" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $id_club=$_POST['id_club'];
    $id_club=$input->check_number($id_club);
    $id_standings=$_POST['id_standings'];
    $id_standings=$input->check_number($id_standings);
    $id_group=$_POST['id_group'];
    $id_group=$input->check_number($id_group);
    $tab=$_POST['tab'];
    $tab=$input->check_number($tab);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_standings.'&tab='.($tab-1);
    if (!empty($id_club) and !empty($id_standings)){
          $query="INSERT INTO standings_teams (id_group,id_standings,id_club,id_table_type)
                  VALUES (".$id_group.",".$id_standings.",".$id_club.",2)
            ";
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
         header("Location: standings_info.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: standings_info.php".Odkaz."&message=1".$strReturnData);
    }
  }

//upravit zaznam
    if ($_POST['action']=="standings_update" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $number=$_POST['number'];
    $id_standings=$_POST['id_standings'];
    $id_standings=$input->check_number($id_standings);
    $tab=$_POST['tab'];
    $tab=$input->check_number($tab);
    
    for ($i=0; $i<($number); $i++){
      
      $id=$_POST['id'][$i];
      $id=$input->check_number($id);
      $id_club=$_POST['id_club'][$i];
      $id_club=$input->check_number($id_club);
    
      $games=$_POST['games'][$i];
      if ($games<>"") $games=$input->check_number($games); else $games="NULL";
      $wins=$_POST['wins'][$i];
      if ($wins<>"") $wins=$input->check_number($wins); else $wins="NULL";
      $wins_ot=$_POST['wins_ot'][$i];
      if ($wins_ot<>"") $wins_ot=$input->check_number($wins_ot); else $wins_ot="NULL";
      $draws=$_POST['draws'][$i];
      if ($draws<>"") $draws=$input->check_number($draws); else $draws="NULL";
      $losts=$_POST['losts'][$i];
      if ($losts<>"") $losts=$input->check_number($losts); else $losts="NULL";
      $losts_ot=$_POST['losts_ot'][$i];
      if ($losts_ot<>"") $losts_ot=$input->check_number($losts_ot); else $losts_ot="NULL";
      $score1=$_POST['score1'][$i];
      if ($score1<>"") $score1=$input->check_number($score1); else $score1="NULL";
      $score2=$_POST['score2'][$i];
      if ($score2<>"") $score2=$input->check_number($score2); else $score2="NULL";
      $points=$_POST['points'][$i];
      if ($points<>"") $points=$input->check_number($points); else $points="NULL";
      $bonus_points=$_POST['bonus_points'][$i];
      if ($bonus_points<>"") $bonus_points=$input->check_number($bonus_points); else $bonus_points="NULL";
      $id_group=$_POST['id_group'][$i];
      $id_group=$input->check_number($id_group);
      
      
      $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_standings.'&tab='.($tab-1);
      if (!empty($id_standings) and !empty($id)){
        
        
        $query="UPDATE  standings_teams SET";
        $query.=" bonus_points=".$bonus_points.",id_group=".$id_group."";
        if (!empty($id_club)){ 
          $query.=",id_club=".$id_club.",games=".$games.",wins=".$wins.",wins_ot=".$wins_ot.",losts=".$losts.",losts_ot=".$losts_ot.",draws=".$draws.",score1=".$score1.",score2=".$score2.",points=".$points."";
        } 
        $query.=" WHERE id=".$id;
                          	 	 	 	 	 	 	
        //echo $query; 
        if (!$con->RunQuery($query)){
              //spatna DB
              $intError++;
        }else{
              $intCorrect++;
              
              $query="UPDATE standings SET last_update=NOW(),id_user=".$users->getIdUser()." WHERE id=".$id_standings;
              $con->RunQuery($query);
              
        }
        
        
      }else{
          //špatná nebo chybějící vstupní data
          //$intError++;
      }
    }
      $xml->generate_xml_standings($id_standings);
      header("Location: standings_info.php".Odkaz."&message=3".$strReturnData."&intCorrect=".$intCorrect."&intError=".$intError);
}       

//smazat zaznam / CLUB TABLE
    if ($_GET['action']=="standings_delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&tab='.$_GET['tab'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM standings_teams WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: standings_info.php".Odkaz."&message=4".$strReturnData);
        }
        else{header("Location: standings_info.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: standings_info.php".Odkaz."&message=99".$strReturnData);}
    }

//move row  CLUB to TABLE
    if ($_GET['action']=="move" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $id=$_GET['id'];
    $id=$input->check_number($id);
    $id_standings=$_GET['id_standings'];
    $id_standings=$input->check_number($id_standings);
    $id_group=$_GET['id_group'];
    $id_group=$input->check_number($id_group);
    $order=$_GET['order'];
    $order=$input->check_number($order);
    $tab=$_GET['tab'];
    $tab=$input->check_number($tab);
    $id_type=$_GET['id_type'];
    $id_type=$input->check_number($id_type);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_standings.'&tab='.($tab-1);
    if (!empty($id) and !empty($id_standings) and !empty($order)){
          
              $query="SELECT id FROM standings_teams WHERE int_order=0 AND id_table_type=".$id_type." AND id_group=".$id_group." AND id_standings=".$id_standings;
              $intPocet=$con->GetQueryNum($query);
              if ($intPocet>0){
                 //default serazeni
                 $query="SELECT id FROM  standings_teams WHERE id_group=".$id_group." AND id_table_type=".$id_type." and id_standings=".$id_standings." ORDER BY points DESC,wins DESC,games ASC, (score1-score2) DESC, id DESC";
                 $result = $con->SelectQuery($query);
                 $i = 1;
                 while($write = $result->fetch_array())
	               {
                       $query_update="UPDATE standings_teams SET int_order=".$i." WHERE id=".$write['id'];
                       $con->RunQuery($query_update);
                       $i++;
                 }
              }
              
              //move line
              $query="SELECT id,int_order FROM standings_teams WHERE id_group=".$id_group." AND id_table_type=".$id_type." and id_standings=".$id_standings." ORDER BY int_order";
              $result = $con->SelectQuery($query);
              while($write = $result->fetch_array())
	            {
                $strMoveArray[$write['int_order']]=$write['id'];
                //echo $write['int_order'].' '.$strMoveArray[$write['int_order']].'<br />'; 
              }
              
              $intOldIndex=$con->GetSQLSingleResult("SELECT int_order as item FROM standings_teams WHERE id=".$id);
              $intOldValue=$id;
              $intNewIndex=$order;
              $intNewValue=$strMoveArray[$order];
              $intBackupValue=$intOldValue;
              $intOldValue=$intNewValue;
              $intNewValue=$intBackupValue;
              $strMoveArray[$intOldIndex]=$intOldValue;
              $strMoveArray[$intNewIndex]=$intNewValue;
              
              foreach ($strMoveArray as $key => $val) {
                 $query_update="UPDATE standings_teams SET int_order=".$key." WHERE id=".$val;
                 //echo $query_update.'<br />'; 
                 $con->RunQuery($query_update);
                 
              }
              
          
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: standings_info.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         $xml->generate_xml_standings($id_standings);
         header("Location: standings_info.php".Odkaz."".$strReturnData);
    }
  }
  
//cancel manual sorting  CLUB to TABLE
    if ($_GET['action']=="move_cancel" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $id_standings=$_GET['id_standings'];
    $id_standings=$input->check_number($id_standings);
    $id_group=$_GET['id_group'];
    $id_group=$input->check_number($id_group);
    $tab=$_GET['tab'];
    $tab=$input->check_number($tab);
    $id_type=$_GET['id_type'];
    $id_type=$input->check_number($id_type);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_standings.'&tab='.($tab-1);
    if (!empty($id_standings)){
          
             $query_update="UPDATE standings_teams SET int_order=0 WHERE id_standings=".$id_standings." AND id_table_type=".$id_type." AND id_group=".$id_group;
             $con->RunQuery($query_update);
          
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: standings_info.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         $xml->generate_xml_standings($id_standings);
         header("Location: standings_info.php".Odkaz."".$strReturnData);
    }
  }


//add line
    if ($_POST['action']=="line_add" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $id_standings=$_POST['id_standings'];
    $id_standings=$input->check_number($id_standings);
    $id_group=$_POST['id_group'];
    $id_group=$input->check_number($id_group);
    $tab=$_POST['tab'];
    $tab=$input->check_number($tab);
    $id_type=$_POST['id_type'];
    $id_type=$input->check_number($id_type);
    $position=$_POST['position'];
    $position=$input->check_number($position);
    $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_standings.'&tab='.($tab-1);
    if (!empty($id_standings)){
          
             $query="INSERT INTO standings_lines (id_standings,id_group,id_table_type,position,name)
                  VALUES (".$id_standings.",".$id_group.",".$id_type.",".$position.",'".$name."')
             ";
             //echo $query;
             $con->RunQuery($query);
          
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: standings_info.php".Odkaz."".$strError.$strReturnData);
    }else{
         $xml->generate_xml_standings($id_standings);
         header("Location: standings_info.php".Odkaz."&message=5".$strReturnData);
    }
  }

  //smazat zaznam / LINE
    if ($_GET['action']=="line_delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&tab='.$_GET['tab'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM standings_lines WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: standings_info.php".Odkaz."&message=6".$strReturnData);
        }
        else{header("Location: standings_info.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: standings_info.php".Odkaz."&message=99".$strReturnData);}
    }

//update info
if ($_POST['action']=="info_update" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $id_standings=$_POST['id_standings'];
    $id_standings=$input->check_number($id_standings);
    $id_group=$_POST['id_group'];
    $id_group=$input->check_number($id_group);
    $tab=$_POST['tab'];
    $tab=$input->check_number($tab);
    $id_type=$_POST['id_type'];
    $id_type=$input->check_number($id_type);
    $info=$_POST['info'];
    $info=$input->valid_text($info,true,false);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_standings.'&tab='.($tab-1);
    if (!empty($id_standings)){
             
             $intInfo=$con->GetSQLSingleResult("SELECT id as item FROM standings_info WHERE id_group=".$id_group." AND id_table_type=".$id_type." AND id_standings=".$id_standings);
             if (empty($intInfo)){
              $query="INSERT INTO standings_info (id_standings,id_group,id_table_type,info) VALUES (".$id_standings.",".$id_group.",".$id_type.",'".$info."')";
             }else{
              $query="UPDATE standings_info SET info='".$info."' WHERE id=".$intInfo;
             }
             //echo $query;
             $con->RunQuery($query);
          
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: standings_info.php".Odkaz."".$strError.$strReturnData);
    }else{
         header("Location: standings_info.php".Odkaz."&message=7".$strReturnData);
    }
  }

//update info
if ($_POST['action']=="copy_standings" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $id_standings=$_POST['id_standings'];
    $id_standings=$input->check_number($id_standings);
    $id_season=$_POST['id_season'];
    $id_season=$input->check_number($id_season);
    $id_league=$_POST['id_league'];
    $id_league=$input->check_number($id_league);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id_standings;
    if (!empty($id_standings)){
            
            $query="SELECT * FROM standings WHERE id=".$id_standings;
            $result = $con->SelectQuery($query);
            $write = $result->fetch_array();
            $query_insert="INSERT INTO standings (id_user,id_type,id_league,id_season,id_stage,name,p_wins,p_wins_pp,p_losts_pp,p_draws) VALUES 
                    (".$write['id_user'].",".$write['id_type'].",".$id_league.",".$id_season.",".$write['id_stage'].",'".$write['name']."',".$write['p_wins'].",".$write['p_wins_pp'].",".$write['p_losts_pp'].",".$write['p_draws'].")";
            if ($con->RunQuery($query_insert)){
              $id_standings_new=$con->GetSQLSingleResult("SELECT id as item FROM standings ORDER by id DESC");
              
              $query_sub="SELECT * FROM standings_groups WHERE id_standings=".$id_standings;
              $result_sub = $con->SelectQuery($query_sub);
              while($write_sub = $result_sub->fetch_array()){
                    
                    $query_insert="INSERT INTO standings_groups (id_standings,order_group,name) VALUES 
                            (".$id_standings_new.",".$write_sub['order_group'].",'".$write_sub['name']."')";
                    $con->RunQuery($query_insert);
                    $id_group_new=$con->GetSQLSingleResult("SELECT id as item FROM standings_groups ORDER by id DESC");
                    $id_group_new=$input->check_number($id_group_new);
                    $id_group=$write_sub['id'];
                    $id_group=$input->check_number($id_group);
                    
                    
                    $query="SELECT * FROM standings_info WHERE id_group=".$id_group." AND id_standings=".$id_standings;
                    $result = $con->SelectQuery($query);
                    while($write = $result->fetch_array()){
                        $query_insert="INSERT INTO standings_info (id_standings,id_group,id_table_type,info) VALUES 
                                      (".$id_standings_new.",".$id_group_new.",".$write['id_table_type'].",'".$write['info']."')";
                        $con->RunQuery($query_insert);
                     }
                        
                    $query="SELECT * FROM standings_lines WHERE id_group=".$id_group." AND id_standings=".$id_standings;
                    $result = $con->SelectQuery($query);
                    while($write = $result->fetch_array()){
                    $query_insert="INSERT INTO standings_lines (id_standings,id_group,id_table_type,position,name) VALUES 
                            (".$id_standings_new.",".$id_group_new.",".$write['id_table_type'].",".$write['position'].",'".$write['name']."')";
                       $con->RunQuery($query_insert);
                       
                    }
              
              $query="SELECT * FROM standings_teams WHERE id_table_type=2 AND id_group=".$id_group."  AND id_standings=".$id_standings;
              //echo $query;
              $result = $con->SelectQuery($query);
              while($write = $result->fetch_array()){
                    
                    if ($write['games']<>"") $games=$write['games']; else $games="NULL";
                    if ($write['wins']<>"") $wins=$write['wins']; else $wins="NULL";
                    if ($write['wins_ot']<>"") $wins_ot=$write['wins_ot']; else $wins_ot="NULL";
                    if ($write['losts']<>"") $losts=$write['losts']; else $losts="NULL";
                    if ($write['losts_ot']<>"") $losts_ot=$write['losts_ot']; else $losts_ot="NULL";
                    if ($write['draws']<>"") $draws=$write['draws']; else $draws="NULL";
                    if ($write['score1']<>"") $score1=$write['score1']; else $score1="NULL";
                    if ($write['score2']<>"") $score2=$write['score2']; else $score2="NULL";
                    if ($write['points']<>"") $points=$write['points']; else $points="NULL";
                    
                    $query_insert="INSERT INTO standings_teams (id_standings,id_group,id_club,id_table_type,int_order,games,wins,wins_ot,losts,losts_ot,draws,score1,score2,points) VALUES 
                            (".$id_standings_new.",".$id_group_new.",".$write['id_club'].",".$write['id_table_type'].",".$write['int_order'].",".$games.",".$wins.",".$wins_ot.",".$losts.",".$losts_ot.",".$draws.",".$score1.",".$score2.",".$points.")";
                          
                    $con->RunQuery($query_insert);  
              }
                    
                      
             
                    
                    
              }
              
              
              
              
            }
           //echo $query;
             
          
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: standings_info.php".Odkaz."&".$strError.$strReturnData);
    }else{
         header("Location: standings_info.php".Odkaz."&message=8".$strReturnData);
    }
  }
  


?>