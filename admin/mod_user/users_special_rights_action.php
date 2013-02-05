<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(1,1,0,0,0);
$input = new Input_filter();
 $bollError=false;

    //pridat nove pravo
    if ($_POST['action']=="add" and $users->checkUserRight(2)){
    
    $id_user=$_POST['id_user'];
    $id_user=$input->check_number($id_user);
    $id_right=$_POST['id_right'];
    $id_right=$input->check_number($id_right);
    $id_country=$_POST['id_country'];
    $id_country=$input->check_number($id_country);
    $id_league=$_POST['id_league'];
    $id_league=$input->check_number($id_league);
    $id_club=$_POST['id_club'];
    $id_club=$input->check_number($id_club);
    $is_right=$_POST['is_right'];
    $is_right=$input->check_number($is_right);
        
    switch ($is_right){
      case 1:
        $id_country=$id_country;
        $id_league=0;
        $id_club=0;
      break;
      case 2:
        $id_country=0;
        $id_league=$id_league;
        $id_club=0;
      break;
      case 3:
        $id_country=0;
        $id_league=0;
        $id_club=$id_club;
      break;
    
    }
    
    $strReturnData='&id_right='.$_POST['id_right'].'&id_user='.$_POST['id_user'].'&id_country='.$_POST['$id_country'].'&id_league='.$_POST['$id_league'].'&id_club='.$_POST['$id_club'];
    
    if (!empty($id_user) and !empty($id_right) and (!empty($id_country) or !empty($id_league) or !empty($id_club))){
        
        $query="INSERT into users_special_rights (id_user,id_right,id_country,id_league,id_club) VALUES (".$id_user.",".$id_right.",".$id_country.",".$id_league.",".$id_club.")";
        if (!$con->RunQuery($query)){ 
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
        }
      
      if ($bollError){
         header("Location: users_special_rights_add.php".Odkaz."&message=".$strError.$strReturnData);
      }else{
         header("Location: users_special_rights.php".Odkaz."&message=1".$strReturnData);
      }
    }
    }
    
    //upravit pravo
    if ($_POST['action']=="update" and $users->checkUserRight(3)){
    
    $id=$_POST['id'];
    $id=$input->check_number($id);
    $id_user=$_POST['id_user'];
    $id_user=$input->check_number($id_user);
    $id_right=$_POST['id_right'];
    $id_right=$input->check_number($id_right);
    $id_country=$_POST['id_country'];
    $id_country=$input->check_number($id_country);
    $id_league=$_POST['id_league'];
    $id_league=$input->check_number($id_league);
    $id_club=$_POST['id_club'];
    $id_club=$input->check_number($id_club);
    $is_right=$_POST['is_right'];
    $is_right=$input->check_number($is_right);
        
    switch ($is_right){
      case 1:
        $id_country=$id_country;
        $id_league=0;
        $id_club=0;
      break;
      case 2:
        $id_country=0;
        $id_league=$id_league;
        $id_club=0;
      break;
      case 3:
        $id_country=0;
        $id_league=0;
        $id_club=$id_club;
      break;
    
    }
    
    $strReturnData='&id_right='.$_POST['id_right'].'&id_user='.$_POST['id_user'].'&id_country='.$_POST['$id_country'].'&id_league='.$_POST['$id_league'].'&id_club='.$_POST['$id_club'];
    
    if (!empty($id_user) and !empty($id_right) and (!empty($id_country) or !empty($id_league) or !empty($id_club))){
        
        $query="UPDATE users_special_rights SET id_user=".$id_user.",id_right=".$id_right.",id_country=".$id_country.",id_league=".$id_league.",id_club=".$id_club." WHERE id=".$id;
        //echo $query; 
        if (!$con->RunQuery($query)){ 
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
        }
      
      if ($bollError){
         header("Location: users_special_rights_update.php".Odkaz."&id=".$id."&message=".$strError.$strReturnData);
      }else{
         header("Location: users_special_rights.php".Odkaz."&message=1".$strReturnData);
      }
    }
      }
      
       //smazat pravo
    if ($_GET['action']=="delete" and $users->checkUserRight(4)){
      
      $id_right=$_GET['id'];
      $id_user=$_GET['id_user'];
      $strReturnData='&id_user='.$id_user;
      
      if (isset($id_right)){
        
              $query="DELETE FROM users_special_rights WHERE id=".$id_right; 
              if ($con->RunQuery($query)==false){
                //špatná nebo chybějící vstupní data
                $bollError=true;
                $strError=99;
              }
        }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
        }
        
        if ($bollError){
         header("Location: users_special_rights.php".Odkaz."&message=".$strError.$strReturnData);
        }else{
         header("Location: users_special_rights.php".Odkaz."&message=2".$strReturnData);
        }
        
      }
?>