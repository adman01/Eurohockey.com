<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(1,1,0,0,0);
$input = new Input_filter();
 $bollError=false;

    //pridat nove pravo
    if ($_POST['action']=="add" and $users->checkUserRight(2)){
    
    if (!empty($_POST['name'])){
        $name=$input->valid_text($_POST['name'],true,true);
        $query="INSERT into users_group (name) VALUES ('".$name."')";
        
        if ($con->RunQuery($query)==true){ 
            $query="SELECT * FROM users_group ORDER by id DESC LIMIT 1";
            $result = $con->SelectQuery($query);
            $write = $result->fetch_array();
            $id_group=$write['id'];
            
            $query="SELECT id FROM users_rights ORDER by name";
            $result = $con->SelectQuery($query);
            while($write = $result->fetch_array()){
              if (isset($_POST["read_".$write['id']])) $read=$_POST["read_".$write['id']]; else $read=0;
              if (isset($_POST["add_".$write['id']])) $add=$_POST["add_".$write['id']]; else $add=0;
              if (isset($_POST["update_".$write['id']])) $update=$_POST["update_".$write['id']]; else $update=0;
              if (isset($_POST["delete_".$write['id']])) $delete=$_POST["delete_".$write['id']]; else $delete=0;
              
              $query="INSERT INTO users_rights_items (id_users_group,id_users_rights,user_read,user_add,user_update,user_delete)
                      VALUES (".$id_group.",".$write["id"].",".$read.",".$add.",".$update.",".$delete.")
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
         header("Location: users_rules_add.php".Odkaz."&message=".$strError.$strReturnData);
      }else{
         header("Location: users_rules.php".Odkaz."&message=1");
      }
    }
    
    //upravit pravo
    if ($_POST['action']=="update" and $users->checkUserRight(3)){
      
      $strReturnData='&name='.$_POST['name'].'&id='.$_POST['id'];
      
      if (!empty($_POST['name']) and !empty($_POST['id'])){
        $id_group=$_POST['id'];
        $name=$input->valid_text($_POST['name'],true,true);
        
        $query="UPDATE users_group SET name='".$name."' WHERE id=".$id_group;
        if ($con->RunQuery($query)==true){ 
            
            $query="SELECT * FROM users_rights ORDER by name";
            $result = $con->SelectQuery($query);
            while($write = $result->fetch_array()){
              
              if (isset($_POST["read_".$write['id']])) $read=$_POST["read_".$write['id']]; else $read=0;
              if (isset($_POST["add_".$write['id']])) $add=$_POST["add_".$write['id']]; else $add=0;
              if (isset($_POST["update_".$write['id']])) $update=$_POST["update_".$write['id']]; else $update=0;
              if (isset($_POST["delete_".$write['id']])) $delete=$_POST["delete_".$write['id']]; else $delete=0;
              
              $query="SELECT id FROM users_rights_items WHERE id_users_group=".$id_group." AND id_users_rights=".$write['id']."";
              if ($con->GetQueryNum($query)==1){
                //if update
                $query="UPDATE users_rights_items SET 
                        user_read=".$read.",user_add=".$add.",user_update=".$update.",user_delete=".$delete."
                        WHERE id_users_group=".$id_group." AND id_users_rights=".$write["id"]."
                ";
              }
              else{
              //if insert
                $query="INSERT INTO users_rights_items (id_users_group,id_users_rights,user_read,user_add,user_update,user_delete)
                      VALUES (".$id_group.",".$write["id"].",".$read.",".$add.",".$update.",".$delete.")
                ";
              }
              $con->RunQuery($query);
                
            }
          }
            
        }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
        }
        
        if ($bollError){
         header("Location: users_rules_update.php".Odkaz."&message=".$strError.$strReturnData);
        }else{
         header("Location: users_rules.php".Odkaz."&message=3&id=".$_POST['id']);
        }
        
      }
      
       //smazat prava
    if ($_GET['action']=="delete" and $users->checkUserRight(4)){
    
      if (isset($_GET['id'])){
        $id_group=$_GET['id'];
        
        $query="DELETE FROM users_rights_items WHERE id_users_group=".$id_group;
        if ($con->RunQuery($query)==true){
              $query="DELETE FROM users_group WHERE id=".$id_group; 
              if ($con->RunQuery($query)==false){
                //špatná nebo chybějící vstupní data
                $bollError=true;
                $strError=99;
              } 
            }
        }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
        }
        
        if ($bollError){
         header("Location: users_rules.php".Odkaz."&message=".$strError.$strReturnData);
        }else{
         header("Location: users_rules.php".Odkaz."&message=2");
        }
        
      }
?>