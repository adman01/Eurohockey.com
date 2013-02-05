<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(10,1,0,0,0);
$input = new Input_filter();
$files = new files();

//switch status
if ($_GET['action']=="switch" and $users->checkUserRight(3)){
      $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        
        $query = "SELECT show_item FROM photo_folder WHERE id=".$id."";
        $result = $con->SelectQuery($query);
        $write = $result->fetch_array();
        $intStatus=$write['show_item'];
        
        if ($intStatus==1) $intStatus=0; else $intStatus=1;
        
        $query="UPDATE photo_folder SET show_item=".$intStatus." WHERE id=".$id;
        $con->RunQuery($query);
        
           header("Location: photogallery_folders.php".Odkaz."".$strReturnData);
        }
        else{header("Location: photogallery_folders.php".Odkaz."&message=99".$strReturnData);} 
}


//pridat novy zaznam
    if ($_POST['action']=="add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    $description=$_POST['description'];
    $description=$input->valid_text($description,true,true);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&name='.$name.'&description='.$description;
    
    if (!empty($name)){
              $query="INSERT INTO photo_folder (name,description,show_item)
                          VALUES ('".$name."','".$description."',1)
                      ";
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
         header("Location: photogallery_folders_add.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: photogallery_folders.php".Odkaz."&message=1");
    }
  }

//upravit zaznam
    if ($_POST['action']=="update" and $users->checkUserRight(3)){
        $bollError=false;
        $name=$_POST['name'];
        $name=$input->valid_text($name,true,true);
        $description=$_POST['description'];
        $description=$input->valid_text($description,true,true);
        $id=$_POST['id'];
        $id=$input->check_number($id);
        $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&name='.$name.'&description='.$description.'&id='.$id;
        
        if (!empty($name)){
            $query="UPDATE photo_folder SET 
                    name='".$name."',description='".$description."'
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
         header("Location: photogallery_folders_update.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: photogallery_folders.php".Odkaz."&message=3&filter=".$_POST['filter']."&filter2=".$_POST['filter2']."&list_number=".$_POST['list_number']."&id=".$id);
    }
  }


//smazat zaznam
    if ($_GET['action']=="delete" and $users->checkUserRight(4)){
    
      if (isset($_GET['id'])){
        $id_user=$_GET['id'];
        $query="DELETE FROM photo_folder WHERE id=".$id_user;
        if ($con->RunQuery($query)==true){
           header("Location: photogallery_folders.php".Odkaz."&message=2");
        }
        else{header("Location: photogallery_folders.php".Odkaz."&message=99");} 
      }else{header("Location: photogallery_folders.php".Odkaz."&message=99");}
    }

//pridat novy zaznam / prirazeni folder k item
    if ($_POST['action']=="assign_add" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $id_league=$_POST['id_league'];
    $id_league=$input->check_number($id_league);
    $id_game=$_POST['id_game'];
    $id_game=$input->check_number($id_game);
    $is_item=$_POST['is_item'];
    $is_item=$input->check_number($is_item);
    $id_season=$_POST['id_season'];
    $id_season=$input->check_number($id_season);
    $id=$_POST['id'];
    $id=$input->check_number($id);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_league='.$id_league.'&id_club='.$id_club.'&id_game='.$id_game.'&is_item='.$is_item.'&id='.$id.'&id_season='.$id_season;
    
     if (!empty($is_item) and (!empty($id_league) or !empty($id_game))){
          
           switch ($is_item){
            case 1:
              $id_assign=$id_league;
            break;
            case 2:
              $id_assign=$id_game;
            break;
          }
          
          $query="INSERT INTO photo_folder_assign (id_folder,id_item,id_item_type,int_year)
                  VALUES (".$id.",".$id_assign.",".$is_item.",".$id_season.")
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
         header("Location: photogallery_folders_assign.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: photogallery_folders_assign.php".Odkaz."&message=1".$strReturnData);
    }
  }


//smazat zaznam / prirazeni 
    if ($_GET['action']=="assign_delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM photo_folder_assign WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: photogallery_folders_assign.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: photogallery_folders_assign.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: photogallery_folders_assign.php".Odkaz."&message=99".$strReturnData);}
    }

    
?>