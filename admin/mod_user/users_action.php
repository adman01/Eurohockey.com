<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(1,1,0,0,0);
$input = new Input_filter();

//pridat noveho uzivatele
    if ($_POST['action']=="add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $user_name=$_POST['user_name'];
    $user_name=$input->valid_text($user_name,true,true);
    $password=$_POST['password'];
    $password=$input->valid_text($password,true,true);
    $password_check=$_POST['password_check'];
    $password_check=$input->valid_text($password_check,true,true);
    $id_group=$_POST['id_group'];
    $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    $surname=$_POST['surname'];
    $surname=$input->valid_text($surname,true,true);
    $email=$_POST['email'];
    $email=$input->valid_text($email,true,true);
    $phone=$_POST['phone'];
    $phone=$input->valid_text($phone,true,true);
    
    $strReturnData='&user_name='.$user_name.'&password='.$password.'&password_check='.$password_check.'&id_group='.$id_group.'&name='.$name.'&surname='.$surname.'&email='.$email.'&phone='.$phone;
    
    if ($input->one_word($user_name)==true AND $input->one_word($password)==true){
    if (!empty($user_name) AND !empty($password) AND !empty($email) AND !empty($password_check) AND !empty($id_group) AND ($password==$password_check)){
        $query="SELECT id FROM users WHERE user_name='".$user_name."'";
        if ($con->GetQueryNum($query)==0){
            
              $query="INSERT INTO users (id_group,user_name,password,name,surname,email,phone)
                      VALUES (".$id_group.",'".$user_name."','".md5($password)."','".$name."','".$surname."','".$email."','".$phone."')
              ";
              $con->RunQuery($query);
        }else{
           //uzivatel existuje
            $bollError=true;
            $strError=3;
        }
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    }
    else{
          //jmeno a heslo musi by bez mezer
          $bollError=true;
          $strError=1;
    }
    
    if ($bollError){
         header("Location: users_add.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: users.php".Odkaz."&message=1");
    }
  }


//smazat uzivatel
    if ($_GET['action']=="delete" and $users->checkUserRight(4)){
    
      if (isset($_GET['id'])){
        $id_user=$_GET['id'];
        $query="DELETE FROM users WHERE id=".$id_user;
        if ($con->RunQuery($query)==true){
           header("Location: users.php".Odkaz."&message=2");
        }
        else{header("Location: users.php".Odkaz."&message=99");} 
      }else{header("Location: users.php".Odkaz."&message=99");}
    }
    
    
//upravit uzivatele
    if ($_POST['action']=="update" and ($users->checkUserRight(3) or $_POST['id']==$users->id_user)){
        $bollError=false;
        $user_name=$_POST['user_name'];
        $user_name=$input->valid_text($user_name,true,true);
        $user_name_old=$_POST['user_name_old'];
        $user_name_old=$input->valid_text($user_name_old,true,true);
        $password=$_POST['password'];
        $password=$input->valid_text($password,true,true);
        $password_check=$_POST['password_check'];
        $password_check=$input->valid_text($password_check,true,true);
        $id_group=$_POST['id_group'];
        $name=$_POST['name'];
        $name=$input->valid_text($name,true,true);
        $surname=$_POST['surname'];
        $surname=$input->valid_text($surname,true,true);
        $email=$_POST['email'];
        $email=$input->valid_text($email,true,true);
        $phone=$_POST['phone'];
        $phone=$input->valid_text($phone,true,true);
        $id=$_POST['id'];
        $activate=$_POST['activate'];
        $strReturnData='&id='.$id.'&user_name='.$user_name.'&password='.$password.'&password_check='.$password_check.'&id_group='.$id_group.'&name='.$name.'&surname='.$surname.'&email='.$email.'&phone='.$phone;
      
      if ($input->one_word($user_name)==true){
      
      if (!empty($user_name) AND !empty($id) AND $id_group>0 AND !empty($email)){
        
        //pokud se meni uz. jmeno
        $change_name_go=true;
        if ($user_name==$user_name_old)$change_name=false; else $change_name=true;  
        if ($change_name==true){
          $query="SELECT id FROM users WHERE user_name='".$user_name."'";
          if ($con->GetQueryNum($query)==0){
            $change_name_go=true;
          }else{
            $change_name_go=false;
            //uzivatel existuje
            $bollError=true;
            $strError=3;
          }
        }
        
        if ($change_name_go==true){
            if (!empty($password) AND !empty($password_check)  AND $activate==1  AND ($password==$password_check)){
              if ($input->one_word($password)==true){
                $SQLpassword=", password='".md5($password)."'";
              }
              else{
                //heslo musi by bez mezer
                $bollError=true;
                $strError=1; 
              }
            }
            
            if (!$bollError){
            $query="UPDATE users SET 
                        id_group=".$id_group.",user_name='".$user_name."',name='".$name."',surname='".$surname."',email='".$email."',phone='".$phone."'
                        ".$SQLpassword."
                        WHERE id=".$id."
            ";
            $con->RunQuery($query);
            }
        }
        
      }else{
        //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    }
    else{
          //jmeno a heslo musi by bez mezer
          $bollError=true;
          $strError=1; 
        }
        
        if ($bollError){
          header("Location: users_update.php".Odkaz."&message=".$strError.$strReturnData);
        }else{
          //pokud se edituje prihlaseny uzivatel tak ho odhlasime
          if ($id==$users->id_user and $change_name){
             $users->logoutUser($_GET["sesid"],5);
          }
          else{
            header("Location: users.php".Odkaz."&message=3&id=".$id."");
          }
        }
     }

//switch status  PUBLIC
if ($_GET['action']=="switch_public" and $users->checkUserRight(3)){
      $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        
        $query = "SELECT boolShowArticle  FROM users WHERE id=".$id."";
        $result = $con->SelectQuery($query);
        $write = $result->fetch_array();
        $intStatus=$write['boolShowArticle'];
        
        if ($intStatus==1){
          $intStatus=0;
        }else{
          $intStatus=1;
        }
        
        $query="UPDATE users SET boolShowArticle=".$intStatus." WHERE id=".$id;
        $con->RunQuery($query);
        
           header("Location: users.php".Odkaz."".$strReturnData);
        }
        else{header("Location: users.php".Odkaz."&message=99".$strReturnData);} 
}     
     
     