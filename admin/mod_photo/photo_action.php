<?php
//set_time_limit(0);
require_once("../inc/global.inc");
require_once("lang/".Web_language."/photo.php");
require_once("inc/config.inc");
require_once("../inc/init.inc");


// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(10,1,0,0,0);

//kostruktor pro tridu files
$files = new files();


//pokud je zvolena catg vraci nazev adresare
function getCatg($id_catg,$con){
  if ($id_catg<>0){
       $query="SELECT folder_name FROM photo_catg WHERE id=".$id_catg;
       $result = $con->SelectQuery($query);
       $write = $result->fetch_array();
       return "/".$write['folder_name'];
  }else{
    return "";
  }
}

//prepnuti
if ($_GET['action']=="switch" and $users->checkUserRight(3)){
    $Odkaz2="&order=".$_GET['order']."&filter=".$_GET['filter']."&filter_catg=".$_GET['filter_catg'].'&list_number='.$_GET['list_number'];

    $id=$_GET['id'];
    $query = "SELECT show_item FROM photo WHERE id=".$id."";
    $result = $con->SelectQuery($query);
    if ($con->GetQueryNum($query)>0){
      $write = $result->fetch_array();
      $zobraz=$write["show_item"];
        if ($zobraz==1) $query = "UPDATE photo set show_item=0 WHERE id=".$id."";
        if ($zobraz==0) $query = "UPDATE photo set show_item=1 WHERE id=".$id."";
        $con->RunQuery($query);
        header("Location: photo.php".Odkaz."".$Odkaz2);      
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
    //pridat novou fotografii
    if ($_POST['action']=="add" and $users->checkUserRight(2)){
    $number=$_POST['number'];
    for ($i=1; $i<=$number; $i++){
    
    $signature=$_POST['signature'][$i];
    $signature=$input->valid_text($signature,true,true);
    $name=$_POST['name'][$i];
    $name=$input->valid_text($name,true,true);
    $description=$_POST['description'][$i];
    $description=$input->valid_text($description,true,true);
    $id_catg=$_POST['id_catg'][$i];
    $id_catg=$input->check_number($id_catg);
    $keywords=$_POST['keywords'][$i];
    $keywords=$input->valid_text($keywords,true,true);
    $show=$_POST['show'][$i];
    $show=$input->check_number($show);
    $files->setFile($_FILES["file_name"],$i);
    $filename=$files->getFileName();
    
    if (!empty($filename)){
    if (isset($name) AND isset($description)  AND isset($filename)){
              
              $FolderPath=IncludeAdminPath.PhotoFolder."".getCatg($id_catg,$con);
                  switch ($files->uploadFile($FolderPath,PhotoMaxFileSize,$PhotoAlowedFileType)){
                    case 1:
                      //nastaveni prav
                      $files->setFileRules($FolderPath,0777);    
                      //soubor byl nahran
                      $query="INSERT INTO photo (date_time,id_user,file_name,signature,id_catg,name,description,keywords,show_item)
                          VALUES (NOW(),".$users->id_user.",'".$files->getFileName()."','".$signature."',".$id_catg.",'".$name."','".$description."','".$keywords."',".$show.")
                      ";
                      //echo $query;
                      $con->RunQuery($query);
                      echo '<p><b>'.langFileName.' '.$i.':</b> '.langActInsert.'</p>';
                    break;
                    case 2:
                      //chyba v uploadu
                      echo '<p><b>'.langFileName.' '.$i.':</b> '.langFileUplError1.'<p>';
                    break;
                    case 3:
                      //prilis velky soubor
                      echo '<p><b>'.langFileName.' '.$i.':</b> '.langFileUplError2.'</p>';
                    break;
                    case 4:
                      //nepovoleny typ souboru
                      echo '<p><b>'.langFileName.' '.$i.':</b> '.langFileUplError3.'</p>';
                    break;
                    case 5:
                      //slozka nenalezena
                      echo '<p><b>'.langFileName.' '.$i.':</b> '.langNoFolder1.'</p>';
                    break;
                    case 6:
                      //soubor jiz existuje
                      echo '<p><b>'.langFileName.' '.$i.':</b> '.langNoFolder2.'</p>';
                    break;
                  }
                
        }else{
          echo '<p>'.langUpdNodata.'</p>';
        }
      }
    }
       echo '<p><a href="photo.php'.Odkaz.'">'.langActOKanchor.'</a></p>';
    }
    
    //upravit fotografii
    if ($_POST['action']=="update" and $users->checkUserRight(3)){
        $signature=$_POST['signature'];
        $signature=$input->valid_text($signature,true,true);
        $name=$_POST['name'];
        $name=$input->valid_text($name,true,true);
        $description=$_POST['description'];
        $description=$input->valid_text($description,true,true);
        $id_catg=$_POST['id_catg'];
        $id_catg=$input->check_number($id_catg);
        $id_catg_old=$_POST['id_catg_old'];
        $id_catg_old=$input->check_number($id_catg_old);
        $keywords=$_POST['keywords'];
        $keywords=$input->valid_text($keywords,true,true);
        $show=$_POST['show'];
        $show=$input->check_number($show);
        $id=$_POST['id'];
        $Odkaz2="&order=".$_POST['order']."&filter=".$_POST['filter']."&filter_catg=".$_POST['filter_catg'].'&list_number='.$_POST['list_number'];
        
        if (isset($name) AND isset($description) AND isset($id)){
            
            if ($id_catg_old<>$id_catg){
                $FolderOldPath=IncludeAdminPath.PhotoFolder."/".getCatg($id_catg_old,$con);
                $FolderNewPath=IncludeAdminPath.PhotoFolder."/".getCatg($id_catg,$con);
                
                $query="SELECT file_name FROM photo WHERE id=".$id;
                $result = $con->SelectQuery($query);
                $write = $result->fetch_array();
                if ($files->moveFile($FolderOldPath,$FolderNewPath,$write['file_name'])){
                  //nastaveni prav
                  $files->setFileRules($FolderNewPath,0777);
                  $move=true;                   
                }
                else{$move=false;}
            }
            else{$move=true;}
            if ($move==true){
              $query="UPDATE photo SET name='".$name."',description='".$description."',id_catg=".$id_catg.",signature='".$signature."',keywords='".$keywords."',show_item=".$show." WHERE id=".$id."";
              $con->RunQuery($query);
              echo '<p>'.langActUpdate.' <a href="photo.php'.Odkaz.$Odkaz2.'">'.langActOKanchor.'</a></p>';
            }else{
              echo '<p>'.langActError.' <a href="photo.php'.Odkaz.$Odkaz2.'">'.langActOKanchor.'</a></p>';
            }
            
        }
        else{
          echo '<p>'.langActError.' <a href="photo.php'.Odkaz.$Odkaz2.'">'.langActOKanchor.'</a></p>';
        }
     } 
     
     
     //upravit VICE fotografii
    if ($_POST['action']=="update_all" and $users->checkUserRight(3)){
    $number=$_POST['number'];
      for ($i=1; $i<=$number; $i++){
        $signature=$_POST['signature'][$i];
        $signature=$input->valid_text($signature,true,true);
        $name=$_POST['name'][$i];
        $name=$input->valid_text($name,true,true);
        $description=$_POST['description'][$i];
        $description=$input->valid_text($description,true,true);
        $id_catg=$_POST['id_catg'][$i];
        $id_catg=$input->check_number($id_catg);
        $id_catg_old=$_POST['id_catg_old'][$i];
        $id_catg_old=$input->check_number($id_catg_old);
        $keywords=$_POST['keywords'][$i];
        $keywords=$input->valid_text($keywords,true,true);
        $show=$_POST['show'][$i];
        $show=$input->check_number($show);
        $id=$_POST['id'][$i];
        $Odkaz2="&order=".$_POST['order']."&filter=".$_POST['filter']."&filter_catg=".$_POST['filter_catg'].'&list_number='.$_POST['list_number'];
        
        if (isset($name) AND isset($description) AND isset($id)){
            
            if ($id_catg_old<>$id_catg){
                $FolderOldPath=IncludeAdminPath.PhotoFolder."/".getCatg($id_catg_old,$con);
                $FolderNewPath=IncludeAdminPath.PhotoFolder."/".getCatg($id_catg,$con);
                
                $query="SELECT file_name FROM photo WHERE id=".$id;
                $result = $con->SelectQuery($query);
                $write = $result->fetch_array();
                if ($files->moveFile($FolderOldPath,$FolderNewPath,$write['file_name'])){
                  //nastaveni prav
                  $files->setFileRules($FolderNewPath,0777);    
                  $move=true;                   
                }
                else{$move=false;}
            }
            else{$move=true;}
            if ($move==true){
              $query="UPDATE photo SET name='".$name."',description='".$description."',id_catg=".$id_catg.",signature='".$signature."',keywords='".$keywords."',show_item=".$show." WHERE id=".$id."";
              $con->RunQuery($query);
              
              echo '<p><b>'.langFileName.' '.($i).':</b> '.langUpdateAll.'</p>';
            }else{
               echo '<p><b>'.langFileName.' '.($i).':</b> '.langNoUpdateAll.'</p>';
            }
            
        }
        else{
          echo '<p><b>'.langFileName.' '.($i).':</b> '.langNoUpdateAll.'</p>';
        }
         
        }
        echo '<p><a href="photo.php'.Odkaz.$Odkaz2.'">'.langActOKanchor.'</a></p>';  
     }
      
    //smazat fotografii
    if ($_GET['action']=="delete" and $users->checkUserRight(4)){
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="SELECT file_name,id_catg FROM photo WHERE id=".$id;
        $result = $con->SelectQuery($query);
        $write = $result->fetch_array();
        
        $FolderPath=IncludeAdminPath.PhotoFolder."/".getCatg($write['id_catg'],$con);
        if ($files->deleteFile($FolderPath,$write['file_name'])){
        $query="DELETE FROM photo WHERE id=".$id;
        if ($con->RunQuery($query)==true){
                echo '<p>'.langActDelete.' <a href="photo.php'.Odkaz.'">'.langActOKanchor.'</a></p>';
             }
           
        }
        else{
          echo '<p>'.langActNoDelete.' <a href="photo.php'.Odkaz.'">'.langActOKanchor.'</a></p>';
        }
        
      }
    }
    
    //smazat HROMADNE fotografie
    if ($_POST['action_all']=="delete_all" and $users->checkUserRight(4)){
      $Odkaz2="&order=".$_POST['order']."&filter=".$_POST['filter']."&filter_catg=".$_POST['filter_catg'].'&list_number='.$_POST['list_number'];
      if (isset($_POST['number'])){
        
        $number=$_POST['number'];
        for ($i=0; $i<$number; $i++){
          $id=$_POST['id'][$i];
          if (!empty($id)){
          $query="SELECT file_name,id_catg FROM photo WHERE id=".$id;
          $result = $con->SelectQuery($query);
          $write = $result->fetch_array();
          $FolderPath=IncludeAdminPath.PhotoFolder."/".getCatg($write['id_catg'],$con);
          if ($files->deleteFile($FolderPath,$write['file_name'])){
          $query="DELETE FROM photo WHERE id=".$id;
          if ($con->RunQuery($query)==true){
                echo '<p><b>'.langFileName.' '.($i+1).':</b> '.langDeleteAll.'</p>';
             }else{
              echo '<p><b>'.langFileName.' '.($i+1).':</b> '.langNoDeleteAll.'</p>';
             }
           
        }else{
            echo '<p><b>'.langFileName.' '.($i+1).':</b> '.langNoDeleteAll.'</p>';
        }
         } 
        }
        echo '<p><a href="photo.php'.Odkaz.$Odkaz2.'">'.langActOKanchor.'</a></p>';
        }
        else{
          echo '<p>'.langActNoDelete.' <a href="photo.php'.Odkaz.$Odkaz2.'">'.langActOKanchor.'</a></p>';
        }
        
    }
    
    //presunout fotografie
    if ($_POST['action_all']=="remove_all" and $users->checkUserRight(3)){
      $Odkaz2="&order=".$_POST['order']."&filter=".$_POST['filter']."&filter_catg=".$_POST['filter_catg'].'&list_number='.$_POST['list_number'];
      $remove_list=$_POST['remove_list'];
      $number=$_POST['number'];
      
      if (isset($remove_list) and isset($number)){
        for ($i=0; $i<$number; $i++){
          $id=$_POST['id'][$i];
          if (!empty($id)){
          $query="SELECT file_name,name,id_catg FROM photo WHERE id=".$id;
          $result = $con->SelectQuery($query);
          $write = $result->fetch_array();
          $FolderOldPath=IncludeAdminPath.PhotoFolder."/".getCatg($write['id_catg'],$con);
          $FolderNewPath=IncludeAdminPath.PhotoFolder."/".getCatg($remove_list,$con);
          
           if ($files->moveFile($FolderOldPath,$FolderNewPath,$write['file_name'])){
                $files->setFileRules($FolderNewPath,0777);
                echo '<p>'.langMoveFile.' <b>'.($write['name']).'</b> '.langMoveFile2.'</p>';
                $query="UPDATE photo SET id_catg='".$remove_list."' WHERE id=".$id."";
                $con->RunQuery($query);
           }else{
              echo '<p>'.langMoveFile.' <b class="red">'.($write['name']).' '.langMoveFileBad.'</b></p>';
           }
           
        }
        
      }
      echo '<p><a href="photo.php'.Odkaz.$Odkaz2.'">'.langActOKanchor.'</a></p>';
      }
      }
    ?>
  
  </div>
</div>
</body>
</html>
