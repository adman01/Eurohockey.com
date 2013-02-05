<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(33,1,0,0,0);
$input = new Input_filter();
$files = new files();

require_once("sponsorship_send_activate.php");

//switch status
if ($_GET['action']=="switch" and $users->checkUserRight(3)){
      $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        
        
        $query = "SELECT * FROM sponsorship_ads WHERE id=".$id."";
        $result = $con->SelectQuery($query);
        $write = $result->fetch_array();
        $intStatus=$write['is_approved'];
        $id_ads_update=$write['id_ads_update'];
        
        if ($intStatus==1) {
          $intStatus=2;
          if (!empty($id_ads_update)){
              //update pri zmene reklamy
              $query="UPDATE sponsorship_ads SET 
                  last_update_user=".$users->getIdUser().",last_update=NOW(),
                  id_type=".$write['id_type'].",name='".$write['name']."',url='".$write['url']."',html_text='".$write['html_text']."',id_image=".$write['id_image']."
                  WHERE id=".$id_ads_update."
              ";
              //echo $query;
              $con->RunQuery($query);

              if ($write['id_image']==1){
                    
                    
                    $filenameOld='sponsorship_'.$id.'.jpg';
                    $FolderOldPath=IncludeAdminPath.'/'.PhotoFolder.'/sponsorship/';
                    $FilePathOld=$FolderOldPath."/".$filenameOld;

                    $filenameNew='sponsorship_'.$id_ads_update.'.jpg';
                    $FolderNewPath=IncludeAdminPath.'/'.PhotoFolder.'/sponsorship/';
                    $FilePathNew=$FolderNewPath."/".$filenameNew;

                    if ($files->moveFile($FolderOldPath,$FolderNewPath,$filenameOld,$filenameNew)){
                        
                    }
              }
              
              $query="DELETE FROM sponsorship_ads WHERE id=".$id;
              $con->RunQuery($query);
              $FilePath=IncludeAdminPath.'/'.PhotoFolder.'/sponsorship/sponsorship_'.$id.'.jpg';
              if ($files->checkFile($FilePath)){
                    unlink($FilePath);
              }

              
              $id=$id_ads_update;

          }
          

        }elseif ($intStatus==2) {
          $intStatus=3;
        }elseif ($intStatus==3) {
          $intStatus=2;
        }

        $query="UPDATE sponsorship_ads SET last_update_user=".$users->getIdUser().", is_approved  =".$intStatus." WHERE id=".$id;
        $con->RunQuery($query);

        //zkontrolova payment - pokud je zaplacen - aktivovat vsechny sponsorship
        $query="SELECT sponsorship_payments_items.id_payment,sponsorship.id,sponsorship.id_item,sponsorship.id_type,sponsorship.id_customer FROM sponsorship INNER JOIN  sponsorship_payments_items ON sponsorship.id=sponsorship_payments_items.id_sponsorship WHERE id_ads=".$id;
        //echo $query; 
        $result = $con->SelectQuery($query);
        if ($con->GetQueryNum($query)>0){
          $boolActivate=false;
          while($write = $result->fetch_array())
          {
           
            $isPayed=$con->GetSQLSingleResult("SELECT id_status as item FROM sponsorship_payments WHERE id=".$write['id_payment']);
            
            if ($intStatus==2 AND $isPayed==1){
              //nastavit sponsorshipy na aktivni - pokud je zaplaceno
              $query_update="UPDATE sponsorship SET last_update_user=".$users->getIdUser().", id_status=2, date_expire=(NOW() + INTERVAL duration_months MONTH)  WHERE id=".$write['id'];
              $con->RunQuery($query_update); 

              $boolActivate=true;
              $email=$con->GetSQLSingleResult("SELECT email as item FROM web_users WHERE id=".$write['id_customer']);              
              $arraySponsorshipItem[$write['id']]['id_item']=$write['id_item'];
              $arraySponsorshipItem[$write['id']]['id_type']=$write['id_type'];


            }else{
              //nastavit sponsorshipy na neaktivni
              $query_update="UPDATE sponsorship SET last_update_user=".$users->getIdUser().", id_status=1, date_expire=NULL  WHERE id=".$write['id'];
              $con->RunQuery($query_update);              
            }
          }
          if ($boolActivate) send_activate_email($email,$arraySponsorshipItem);
        }

        header("Location: sponsorship_ads.php".Odkaz."".$strReturnData);
    }
    else{header("Location: sponsorship_ads.php".Odkaz."&message=99".$strReturnData);} 
}

//upravit zaznam
    if ($_POST['action']=="update" and $users->checkUserRight(3)){
        $bollError=false;
        

    $id=$input->check_number($_POST['id']);
    $id_type=$input->check_number($_POST['id_type']);
    $name=$input->valid_text($_POST['name'],true,true);
    $url=$input->valid_text($_POST['url'],true,true);
    $html_text=$input->valid_text($_POST['html_text'],true,true);
    $id_image=$input->check_number($_POST['id_image']);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id.'&id_type='.$_POST['id_type'].'&name='.$_POST['name'].'&url='.$_POST['url'].'&html_text='.$_POST['html_text'];
    
    if (!empty($id) and !empty($id_type) and !empty($name)){
        
            switch ($id_type) {
                case 1:
                 $id_image=0;
                  $FilePath=IncludeAdminPath.'/'.PhotoFolder.'/sponsorship/sponsorship_'.$id.'.jpg';
                  if ($files->checkFile($FilePath)){
                    unlink($FilePath);
                  }

                break;
              
                case 2:
                 $html_text="";
                break; 
              
             }     
        $query="UPDATE sponsorship_ads SET 
                  last_update_user=".$users->getIdUser().",last_update=NOW(),
                  id_type=".$id_type.",name='".$name."',url='".$url."',html_text='".$html_text."',id_image=".$id_image."
                  WHERE id=".$id."
            ";
            //echo  $query;
           if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=98;
           }else{
              
               if ($id_image==1){
                  $strUploadifyDataType="sponsorship"; 
                  $strUploadifyDataExt="jpg";
                  
                  $filename=$strUploadifyDataType."_".$users->getSesid().'.'.$strUploadifyDataExt;
                  $FolderOldPath=IncludeAdminPath."/admin/inc/uploadify/uploads_temp/".$users->getSesid()."/";
                  $FilePath=$FolderOldPath."/".$filename;
                  if ($files->checkFile($FilePath)){
                    $filenameNew='sponsorship_'.$id.'.jpg';
                    $FolderNewPath=IncludeAdminPath.'/'.PhotoFolder.'/sponsorship/';
                    $FilePathNew=$FolderNewPath."/".$filenameNew;
                    if ($files->moveFile($FolderOldPath,$FolderNewPath,$filename,$filenameNew)){
                         $query_update="UPDATE sponsorship_ads SET id_image=1 WHERE id=".$id;
                         $con->RunQuery($query_update);  
                    }
                  }
              }

              if ($id_image==3){
                  
                 $FilePath=IncludeAdminPath.'/'.PhotoFolder.'/sponsorship/sponsorship_'.$id.'.jpg';
                 if ($files->checkFile($FilePath)){
                    unlink($FilePath);
                    $query_update="UPDATE sponsorship_ads SET id_image=0 WHERE id=".$id;
                    $con->RunQuery($query_update);   
                 }
              }
              
           }
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: sponsorship_ads_update.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: sponsorship_ads.php".Odkaz."&message=3".$strReturnData);
    }
  }

  //smazat zaznam
    if ($_GET['action']=="delete" and $users->checkUserRight(4)){
      $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM sponsorship_ads WHERE id=".$id;
        if ($con->RunQuery($query)==true){
            $FilePath=IncludeAdminPath.'/'.PhotoFolder.'/sponsorship/sponsorship_'.$id.'.jpg';
            if ($files->checkFile($FilePath)){
                    unlink($FilePath);
            }
            header("Location: sponsorship_ads.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: sponsorship_ads.php".Odkaz."&message=99".$strReturnData);} 
      }else{header("Location: sponsorship_ads.php".Odkaz."&message=99".$strReturnData);}
    }
    




?>
