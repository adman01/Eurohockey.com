<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(10,1,0,0,0);
$input = new Input_filter();
$files = new files();

//pridat novy zaznam
    if ($_POST['action']=="add" and $users->checkUserRight(2)){
    
    $intCorrect=0;
    $intError=0;
    
    $number=$_POST['number'];
    $number=$input->check_number($number);
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'];
    
    for ($i=0; $i<=$number; $i++){
      $description=$_POST['description'][$i];
      $description=$input->valid_text($description,true,true);
      $keywords=$_POST['keywords'][$i];
      $keywords=$input->valid_text($keywords,true,true);
      $filename=$_POST['filename'][$i];
      $filename=$input->valid_text($filename,true,true);
      $id_photo_folder=$_POST['id_photo_folder'][$i];
      $id_photo_folder=$input->check_number($id_photo_folder);
      
      if (!empty($description) AND !empty($keywords) AND !empty($filename)){
        $FolderOldPath="../inc/uploadify/uploads_temp/photos/".$users->getSesid();
        $FilePath=$FolderOldPath."/".$filename;
        if ($files->checkFile($FilePath)){
          
          $query="INSERT INTO photo (id_user,date_time,file_name,id_photo_folder,description,keywords,show_item) VALUES 
                                    (".$users->id_user.",'".date("Y-m-d H:i:s")."','".$filename."',".$id_photo_folder.",'".$description."','".$keywords."',1)
          ";
          if (!$con->RunQuery($query)){
            //chyba DB
            $intError++;
          }else{
            $query="SELECT id FROM photo WHERE file_name='".$filename."' ORDER by id DESC LIMIT 1";
            $result = $con->SelectQuery($query);
            $write = $result->fetch_array();
            $intID=$write['id'];
            if(!empty($intID)){
              $FolderNewPath=IncludeAdminPath.PhotoFolder;
              $FilePathNew=$FolderNewPath."/".$filename;
              
              //pokud existuje soubor stejneho jmena
              if ($files->checkFile($FilePathNew)){
                $filenameNew=$intID."-".$filename;
                $FilePathNew=IncludeAdminPath.PhotoFolder."/".$filenameNew;
                
                $query_update="UPDATE photo SET file_name='".$filenameNew."' WHERE id=".$intID;
                $con->RunQuery($query_update);
              }else{
                $filenameNew=$filename;
              }
               
              if ($files->moveFile($FolderOldPath,$FolderNewPath,$filename,$filenameNew)){
                $intCorrect++;
              }else{
                $intError++;
              }
              
            }else{
              //chyba DB
              $intError++;
            }
            
          }
          
          
        }else{
          //špatná nebo chybějící vstupní data
          $intError++;
        }
      }else{
          //špatná nebo chybějící vstupní data
          $intError++;
      }
    }
    
    header("Location: photogallery.php".Odkaz."&message=1&intCorrect=".$intCorrect."&intError=".$intError.$strReturnData);
}


//smazat zaznam
    if ($_GET['action']=="delete" and $users->checkUserRight(4)){
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        
        $query="SELECT file_name FROM photo WHERE id=".$id;
        $result = $con->SelectQuery($query);
        $write = $result->fetch_array();
        if (!empty($write['file_name'])){
           
           $FolderPath=IncludeAdminPath.PhotoFolder;
           if ($files->deleteFile($FolderPath,$write['file_name'])){
              $query="DELETE FROM photo WHERE id=".$id;
              if ($con->RunQuery($query)){
                header("Location: photogallery.php".Odkaz."&message=2");
              }else{
                header("Location: photogallery.php".Odkaz."&message=99");
              }
           }else{
            header("Location: photogallery.php".Odkaz."&message=99");
           }
           
        }
        else{header("Location: photogallery.php".Odkaz."&message=99");} 
      }else{header("Location: photogallery.php".Odkaz."&message=99");}
    }
    
    
//upravit zaznam
   if ($_POST['action']=="update" and $users->checkUserRight(3)){
        $bollError=false;
        
        $description=$_POST['description'];
        $description=$input->valid_text($description,true,true);
        $keywords=$_POST['keywords'];
        $keywords=$input->valid_text($keywords,true,true);
        $id_photo_folder=$_POST['id_photo_folder'];
        $id_photo_folder=$input->check_number($id_photo_folder);
        $id=$_POST['id'];
        
        $filename_old=$_POST['filename_old'];
        $filename_old=$input->valid_text($filename_old,true,true);
        
        $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id.'&description='.$description.'&keywords='.$keywords.'&id_photo_folder='.$id_photo_folder;
        if (!empty($description) AND !empty($keywords) AND !empty($id)  AND !empty($filename_old)){
            
            $files->setFile($_FILES["filename"],0);
            $filename=$files->getFileName();
            if (!empty($filename)){
              
              $FolderPath=IncludeAdminPath.PhotoFolder."/temp_upload";
              $FileNewPath=$FolderPath."/".$filename;
              //pokud jiz je ulozeno na disku
              if (file_exists(IncludeAdminPath.PhotoFolder."/".$filename)){
                $filename_new=$id."-".$filename;
              }else{
                $filename_new=$filename;
              }
              //$files->setFileName($filename_new);
              switch ($files->uploadFile($FolderPath,PhotoMaxFileSize,$PhotoAlowedFileType)){
          case 1:
          //vse OK
          //nastaveni prav
          $files->setFileRules($FolderPath,0777);
          
          $FolderOldPath=IncludeAdminPath.PhotoFolder;
          //presunuti noveho souboru
          $files->moveFile($FolderPath,$FolderOldPath,$filename,$filename_new);
          $sqlUpdate=",file_name='".$filename_new."'";
          
          //odstraneni stareho souboru
          $files->deleteFile($FolderOldPath,$filename_old);
          
          
          
         break;
         case 2:
            //chyba v uploadu
            $strErrorText='<li><b>file '.$filename.'</b>: file could not be upload</li>';
            $strError=2;
            $bollError=true;
         break;
         case 3:
            //prilis velky soubor
            $strErrorText='<li><b>file '.$filename.'</b>: file is too large</li>';
            $strError=2;
            $bollError=true;
         break;
         case 4:
            //nepovoleny typ souboru
            $strErrorText='<li><b>file '.$filename.'</b>: type not allowed</li>';
            $strError=2;
            $bollError=true;
         break;
         case 5:
            //slozka nenalezena
            $strErrorText='<li><b>file '.$filename.'</b>: folder is not found</li>';
            $strError=2;
            $bollError=true;
         break;
         case 6:
            //soubor jiz existuje
            $strErrorText='<li><b>file '.$filename.'</b>: file already exists</li>';
            $strError=2;
            $bollError=true;
         break;
       } 
       //echo $strErrorText;
               
            }
      
        
            $query="UPDATE photo SET 
                    keywords='".$keywords."',description='".$description."',id_photo_folder=".$id_photo_folder.$sqlUpdate."
                    WHERE id=".$id."
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
         header("Location: photogallery_update.php".Odkaz."&message=".$strError.$strReturnData."&strErrorText=".$strErrorText);
    }else{
         header("Location: photogallery.php".Odkaz."&message=3".$strReturnData);
    }
  }

?>