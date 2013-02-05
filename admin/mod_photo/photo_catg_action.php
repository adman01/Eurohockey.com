<?php
require_once("../inc/global.inc");
require_once("lang/".Web_language."/photo_catg.php");
require_once("../inc/init.inc");
require_once("inc/config.inc");

// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(10,1,0,0,0);

//kostruktor pro tridu files
$files = new files();

//prepnuti
if ($_GET['action']=="switch" and $users->checkUserRight(3)){
    $id=$_GET['id'];
    $list_number=$_GET['list_number'];
    $query = "SELECT show_item FROM photo_catg WHERE id=".$id."";
    $result = $con->SelectQuery($query);
    if ($con->GetQueryNum($query)>0){
      $write = $result->fetch_array();
      $zobraz=$write["show_item"];
        if ($zobraz==1) $query = "UPDATE photo_catg set show_item=0 WHERE id=".$id."";
        if ($zobraz==0) $query = "UPDATE photo_catg set show_item=1 WHERE id=".$id."";
        $con->RunQuery($query);
        header("Location: photo_catg.php".Odkaz."&list_number=".$_GET['list_number']."&filter=".$_GET['filter']."&order=".$_GET['order']."");      
    }
}

require_once("inc/head.inc");
echo $head->setTitle(langTitle);
echo $head->setEndHead();

$input = new Input_filter();

?>
<body onload="javascript:logout('<?php echo $users->sesid ;?>', '<?php echo MaxTimeConnection ;?>')">

<div id="layout">
  <div id="mainmenu">
    <?php require_once("../inc/menu.inc"); ?>
  </div>
  <div id="main">
  
    <h1><?php echo langH1; ?></h1>
    
    <?php
    //pridat novou kategorii
    if ($_POST['action']=="add" and $users->checkUserRight(2)){
    
    $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    $description=$_POST['description'];
    $description=$input->valid_text($description,true,true);
    $is_password=$_POST['is_password'];
    $is_password=$input->check_number($is_password);
    $password=$_POST['password'];
    $password=$input->valid_text($password,true,true);
    $password_check=$_POST['password_check'];
    $password_check=$input->valid_text($password_check,true,true);
    $id_game=$_POST['id_game'];
    $id_game=$input->check_number($id_game);
    $show=$_POST['show'];
    $show=$input->check_number($show);
    
    if (isset($name) AND isset($description)){
              if ($is_password==1) {$password=md5($password);}else{$password="";}
              
              $NewFolderPath=IncludeAdminPath.PhotoFolder;
              if ($files->checkFolder($NewFolderPath)) {
                $folder_name=$files->getCorrectName($name);
                $NewFolderPath=$NewFolderPath."/".$folder_name;
                if (!$files->checkFolder($NewFolderPath)) {
                  if (mkdir($NewFolderPath, 0755)){
                      $query="INSERT INTO photo_catg (folder_name,name,description,is_password,password,id_game,show_item)
                          VALUES ('".$folder_name."','".$name."','".$description."',".$is_password.",'".$password."',".$id_game.",".$show.")
                      ";
                      $con->RunQuery($query);
                      echo '<p>'.langActInsert.' <a href="photo_catg.php'.Odkaz.'">'.langActOKanchor.'</a></p>';
                    
                  }else{
                     echo '<p>'.langNoFolder2.' <a href="photo_catg.php'.Odkaz.'">'.langActOKanchor.'</a></p>';
                  }
                }else{
                 echo '<p>'.langNoFolder2.' <a href="photo_catg.php'.Odkaz.'">'.langActOKanchor.'</a></p>';
                }
              }else{
                echo '<p>'.langNoFolder1.' <a href="photo_catg.php'.Odkaz.'">'.langActOKanchor.'</a></p>';
            }
              
               
        }else{
          echo '<p>'.langActUser.' <a href="photo_catg.php'.Odkaz.'">'.langActOKanchor.'</a></p>';
        }
    }
    
    //upravit kategorii
    if ($_POST['action']=="update" and $users->checkUserRight(3)){
      $name=$_POST['name'];
      $name=$input->valid_text($name,true,true);
      $description=$_POST['description'];
      $description=$input->valid_text($description,true,true);
      $is_check=$_POST['is_check'];
      $is_check=$input->check_number($is_check);
      $is_password=$_POST['is_password'];
      $is_password=$input->check_number($is_password);
      $password=$_POST['password'];
      $password=$input->valid_text($password,true,true);
      $password_check=$_POST['password_check'];
      $password_check=$input->valid_text($password_check,true,true);
      $id_game=$_POST['id_game'];
      $id_game=$input->check_number($id_game);
      $show=$_POST['show'];
      $show=$input->check_number($show);
      $id=$_POST['id'];
    
      if (isset($name) AND isset($description)){
            if ($is_check==1) {$passwordSQL=",password='".md5($password)."'";}else{$passwordSQL="";}
            $query="UPDATE photo_catg SET name='".$name."',description='".$description."',is_password=".$is_password."".$passwordSQL.",id_game=".$id_game.",show_item=".$show." WHERE id=".$id;
            $con->RunQuery($query);
            echo '<p>'.langActUpdate.' <a href="photo_catg.php'.Odkaz.'&list_number='.$_GET['list_number'].'&filter='.$_GET['filter'].'&order='.$_GET['order'].'">'.langActOKanchor.'</a></p>';
        }
        else{
          echo '<p>'.langActSpace.' <a href="photo_catg.php'.Odkaz.'&list_number='.$_GET['list_number'].'&filter='.$_GET['filter'].'&order='.$_GET['order'].'"'.langActOKanchor.'</a></p>';
        }
     } 
      
    //smazat kategorii
    if ($_GET['action']=="delete" and $users->checkUserRight(4)){
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="SELECT folder_name FROM photo_catg WHERE id=".$id;
        $result = $con->SelectQuery($query);
        $write = $result->fetch_array();
        
        $FolderPath=IncludeAdminPath.PhotoFolder."/".$write['folder_name'];
        if ($files->deleteFolder($FolderPath)){
          $query="DELETE FROM photo_catg WHERE id=".$id;
          if ($con->RunQuery($query)==true){
                echo '<p>'.langActDelete.' <a href="photo_catg.php'.Odkaz.'">'.langActOKanchor.'</a></p>';
          } 
        }
        }
      }
    ?>
  
  </div>
</div>
</body>
</html>
