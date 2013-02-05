<?php
require_once("inc/global.inc");

function getDiscount($intItemCount){

  $intDiscount=0;
  if ($intItemCount>=3 AND $intItemCount<5){
    $intDiscount=15;
  }elseif ($intItemCount>=5 AND $intItemCount<10) {
    $intDiscount=20;
  }elseif ($intItemCount>=10 AND $intItemCount<25) {
    $intDiscount=30;
  }elseif ($intItemCount>=25 AND $intItemCount<50) {
    $intDiscount=35;
  }elseif ($intItemCount>=50) {
    $intDiscount=40;
  }

  return $intDiscount;
}

function getDiscountMonths($intItemCount){
  switch ($intItemCount) {
    case 4:
      $intDiscount=15;
      break;
    case 6:
      $intDiscount=20;
      break;
    case 12:
      $intDiscount=30;
      break;
    case 24:
      $intDiscount=40;
      break;
    case 60:
      $intDiscount=50;
      break;
    case 120:
      $intDiscount=60;
      break;
    
    default:
       $intDiscount=0;
      break;
  }

  return $intDiscount;
}

function send_add_email ($strEmail,$strFirstName,$strEmailText){
  
          $strMailTo = $strEmail;
          //$strMailTo="martin.formanek@gmail.com";
          $strMailHeader = "From: ".strEmailFrom;
          $strMailSubject = "New sponsorship";
          if (empty($strFirstName)) $strMailSalutation=$strEmail; else $strMailSalutation=$strFirstName; 
$strMailContents = "
Dear ".$strMailSalutation.",";
$strMailContents.= $strEmailText;

$strMailContents.= "
-------------------------------------

Thank you for your order!
The Eurohockey.com Team
";
sendEmail(strEmailFrom,"Eurohockey.com",strEmailFrom,"Eurohockey.com",$strMailTo,$strMailSubject,$strMailContents);
  
}

//new ads
if ($_POST['action']=="add_sponsorship"){

  $boolError=true;
  $strUserArray=check_login(0);
  $intCustomerID=$strUserArray['id'];
  $intCustomerID=$input->check_number($intCustomerID);

  //
  if (empty($intCustomerID)){
      $boolError=false;
      $message=1;
  }else{
    
    $intPrice=$input->check_number($_POST['price']);
    $intDuration=$input->check_number($_POST['duration']);
    
    $intIdAds=$input->check_number($_POST['id_ads']);
    $strAdsName=$input->valid_text($_POST['ads_name'],true,true);
    $strAdsUrl=$input->valid_text($_POST['ads_url'],true,true);
    $strAdsHtml=$input->valid_text($_POST['ads_html_text'],true,true);
    $id_image=$_POST['id_image'];
    $id_image=$input->check_number($id_image); 

    $intItemId=$_SESSION['sponsorship_id'];
    $intItemType=$_SESSION['sponsorship_type'];


    if (!empty($intItemId) AND !empty($intItemType) AND !empty($intPrice) AND !empty($intDuration) AND ( (!empty($intIdAds)) OR (!empty($strAdsName)) )){
      
      if (!empty($intIdAds)){
        $strAdsName="";
        $strAdsHtml="";
        $strAdsUrl="";
      }else{

        if ($id_image==1){
          $files = new files();

          $strUploadifyDataType="sponsorship"; 
          $strUploadifyDataExt="jpg";
          $filename=$strUploadifyDataType."_".md5($strUserArray['id']).'.'.$strUploadifyDataExt;
          $FolderOldPath=IncludeAdminPath."/inc/uploadify/uploads_temp/";
          $FilePath=$FolderOldPath."/".$filename;

          $IntImageSize = getimagesize($FilePath);
          if ($IntImageSize[0]>600 OR $IntImageSize[1]>125){
            $boolError=false;
            $message=5;
          }

        }
        
        $intIdAds=0;

        if ($boolError){

        $query="INSERT INTO  sponsorship_ads (id_customer,id_type,name,url,html_text) VALUES (".$intCustomerID.",1,'".$strAdsName."','".$strAdsUrl."','".$strAdsHtml."')";
        //echo $query; 
        $con->RunQuery($query);
        $intIdAds=$con->GetSQLSingleResult("SELECT id as item FROM sponsorship_ads WHERE id_customer=".$intCustomerID." ORDER by id DESC LIMIT 1");

        if ($id_image==1){
                
                  if ($files->checkFile($FilePath)){
                    $filenameNew='sponsorship_'.$intIdAds.'.jpg';
                    $FolderNewPath=IncludeAdminPath.'/'.PhotoFolder.'/sponsorship/';
                    $FilePathNew=$FolderNewPath."/".$filenameNew;
                    if ($files->moveFile($FolderOldPath,$FolderNewPath,$filename,$filenameNew)){
                         $query_update="UPDATE sponsorship_ads SET id_image=1, id_type=2 WHERE id=".$intIdAds;
                         $con->RunQuery($query_update);  
                    }
                  }
        }
        }
      }
      
      
      if (!empty($intIdAds) AND $boolError){

          $query_sub="SELECT  id_currency,price_".$intItemType." as price from sponsorship_prices  WHERE id=".$intPrice;
          if ($con->GetQueryNum($query_sub)>0){
                $result_sub = $con->SelectQuery($query_sub);
                $write_sub = $result_sub->fetch_array();
                $intPriceAmount=$write_sub['price'];
                $intPriceCurrency=$write_sub['id_currency'];
          }
          if ($intPriceAmount<>"" AND !empty($intPriceCurrency)){

              //pripadna sleva dle mesicu

              $intMontDiscount=getDiscountMonths($intDuration);
              $intPriceAmount=round(($intPriceAmount-(($intPriceAmount/100)*$intMontDiscount)),2);

              //vytvoreni sponsorsip
              $query="INSERT INTO sponsorship
                                  (id_item,id_type,id_customer,date_add,date_expire,id_ads,id_status,is_approved,duration_months,price,id_curency) VALUES 
                                  (".$intItemId.",".$intItemType.",".$intCustomerID.",NOW(),'',".$intIdAds.",1,0,".$intDuration.",".$intPriceAmount.",".$intPriceCurrency.")";
              //echo $query; 
              if ($con->RunQuery($query)){

                $intIdSponsorship=$con->GetSQLSingleResult("SELECT id as item FROM sponsorship WHERE id_customer=".$intCustomerID." AND id_ads=".$intIdAds." ORDER by id DESC LIMIT 1");
                if (!empty($intIdSponsorship)){

                    //zjisteni existujiciho nezaplaceneho paymentu stejne meny
                    $intIdActualPayment=$con->GetSQLSingleResult("SELECT id as item FROM sponsorship_payments WHERE id_customer=".$intCustomerID." AND id_status=0 AND id_curency=".$intPriceCurrency." ORDER BY id DESC LIMIT 1");
                    if (empty($intIdActualPayment)){
                      //payment neexituje, vytvorim novy
                      $query="INSERT INTO sponsorship_payments 
                                  (id_customer,date_add,id_status,id_curency) VALUES 
                                  (".$intCustomerID.",NOW(),0,".$intPriceCurrency.")";
                      $con->RunQuery($query);
                      $intIdActualPayment=$con->GetSQLSingleResult("SELECT id as item FROM sponsorship_payments WHERE id_customer=".$intCustomerID." AND id_status=0 AND id_curency=".$intPriceCurrency." ORDER BY id DESC LIMIT 1");
                    }

                    if (!empty($intIdActualPayment)){

                        //pokud je payment udelam propojim ho se sponsorship
                        $query="INSERT INTO sponsorship_payments_items 
                                  (id_sponsorship,id_payment) VALUES 
                                  (".$intIdSponsorship.",".$intIdActualPayment.")";
                        $con->RunQuery($query);

                        //zjistim pocet sponsorship a jejich celkovou cenu a pripadne pouziju slevy
                        $query_discount="SELECT price,duration_months from sponsorship WHERE id_customer=".$intCustomerID." AND id_status=1 AND id_curency=".$intPriceCurrency;
                        $intSponsorshipTotal=$con->GetQueryNum($query_discount);
                        $intSponsorshipTotalPrice=0;
                        if ($intSponsorshipTotal>0){
                          $result_discount = $con->SelectQuery($query_discount);
                          while($write_discount = $result_discount->fetch_array()){
                            $intSponsorshipTotalPrice=$intSponsorshipTotalPrice+($write_discount['price']*$write_discount['duration_months']);
                          }

                          //tady budou slevy
                          $intTotalDiscount=getDiscount($intSponsorshipTotal);
                          $intSponsorshipTotalPrice=round(($intSponsorshipTotalPrice-(($intSponsorshipTotalPrice/100)*$intTotalDiscount)),2);
                          
                          $query="UPDATE sponsorship_payments SET price_total=".$intSponsorshipTotalPrice.",id_discount=".$intTotalDiscount." WHERE id=".$intIdActualPayment;
                          //echo $query;
                          $con->RunQuery($query);
                        }

                        //odeslat email

              $strCurrency=$con->GetSQLSingleResult("SELECT name as item FROM sponsorship_currency_list WHERE id=".$intPriceCurrency);
              switch ($intItemType) {
                  case 1:
                    $ItemName=$games->GetActualLeagueName($intItemId,ActSeason);
                    $strTypeName="league";
                  break;
                  case 2:
                    $ItemName=$games->GetActualClubName($intItemId,ActSeason);
                    $strTypeName="club";
                  break;
                  case 3:
                    $ItemName=$con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$intItemId);
                    $strTypeName="player";
                  break;
              }

$strEmailText="
thank you for your sponsorship order:

You chose the following sponsorship:

-------------------------------------
".$strTypeName." name: ".$ItemName."
Fee: ".number_format($intPriceAmount, 2, ',', ' ').": ".$strCurrency."
Duration: ".$intDuration." month(s)

More details: 
http://".strWWWurl."/sponsorship-item/".get_url_text($ItemName,$intIdSponsorship)."

Payment options can be found here:
http://".strWWWurl."/sponsorship-payments.html
";
//echo $strEmailText;
send_add_email ($strUserArray['email'],$strUserArray['firstname'],$strEmailText);


                        $message=2;
                        $_SESSION['sponsorship_id']="";
                        $_SESSION['sponsorship_type']="";
                    }else{
                        $message=1;
                        $boolError=false;
                    }

                }else{
                    $message=1;
                    $boolError=false;
                }

                
              }else{
                $message=1;
                $boolError=false;
              }

          }else{
            $message=1;
            $boolError=false;
          }



      }
    }else{
     $boolError=false;
    $message=1;
  }

  }
  
  if ($boolError){
    header("Location: /sponsorship.html?message=".$message);
  }else{
    header("Location: /sponsorship.html?message=".$message);
  }

}

//update sponsorship
if ($_GET['action']=="update-sponsorship"){
  $boolError=true;
  $strUserArray=check_login(1);
  $intCustomerID=$strUserArray['id'];
  $intCustomerID=$input->check_number($intCustomerID);
  $intDuration=$input->check_number($_POST['duration']);
  $intIdAds=$input->check_number($_POST['id_ads']);

  $intID=$input->check_number($_POST['id']);
  //echo $intID;
  if (!empty($intID) AND !empty($intDuration) AND !empty($intIdAds)){

    $intPaymentID=$con->GetSQLSingleResult("SELECT id_payment as item FROM sponsorship_payments_items WHERE id_sponsorship=".$intID);
    $intPaymentItemsCount=$con->GetSQLSingleResult("SELECT count(*) as item FROM sponsorship_payments_items WHERE id_payment=".$intPaymentID);

    $query_sub="SELECT  id_item,id_type,id_curency from sponsorship  WHERE id=".$intID;
    if ($con->GetQueryNum($query_sub)>0){
      $result_sub = $con->SelectQuery($query_sub);
      $write_sub = $result_sub->fetch_array();
      $intIdCategory=$con->GetSQLSingleResult("SELECT id_category as item FROM sponsorship_items WHERE id_item=".$write_sub['id_item']." AND id_type=".$write_sub['id_type']."");
      $intPriceAmount=$con->GetSQLSingleResult("SELECT price_".$write_sub['id_type']." as item FROM sponsorship_prices WHERE id_category=".$intIdCategory." AND id_currency=".$write_sub['id_curency']."");
    }
    
    //prepocitani slevy
    $intMontDiscount=getDiscountMonths($intDuration);
    $intPriceAmount=round(($intPriceAmount-(($intPriceAmount/100)*$intMontDiscount)),2);

    $query="UPDATE sponsorship SET id_ads=".$intIdAds.",duration_months=".$intDuration.",price=".$intPriceAmount." WHERE id_status=1 AND id_customer=".$intCustomerID." AND id=".$intID;
    //echo  $query;
    $con->RunQuery($query);

            //prepocitat payment
              $query_discount="SELECT price,duration_months from sponsorship INNER JOIN sponsorship_payments_items ON sponsorship_payments_items.id_sponsorship=sponsorship.id WHERE id_customer=".$intCustomerID." AND id_payment=".$intPaymentID;
                        $intSponsorshipTotal=$con->GetQueryNum($query_discount);
                        $intSponsorshipTotalPrice=0;
                        if ($intSponsorshipTotal>0){
                          $result_discount = $con->SelectQuery($query_discount);
                          while($write_discount = $result_discount->fetch_array()){
                            $intSponsorshipTotalPrice=$intSponsorshipTotalPrice+($write_discount['price']*$write_discount['duration_months']);
                          }

                          //tady budou slevy
                          $intTotalDiscount=getDiscount($intSponsorshipTotal);
                          $intSponsorshipTotalPrice=round(($intSponsorshipTotalPrice-(($intSponsorshipTotalPrice/100)*$intTotalDiscount)),2);
                          
                          $query="UPDATE sponsorship_payments SET price_total=".$intSponsorshipTotalPrice.",id_discount=".$intTotalDiscount." WHERE id=".$intPaymentID;
                          //echo $query;
                          $con->RunQuery($query);
                        }

  }
  
   header("Location: /sponsorship.html?message=4");
}

//delete sponsorship
if ($_GET['action']=="delete-sponsorship"){
  $boolError=true;
  $strUserArray=check_login(1);
  $intCustomerID=$strUserArray['id'];
  $intCustomerID=$input->check_number($intCustomerID);

  $intID=$input->check_number($_GET['id']);
  if (!empty($intID)){

    $intPaymentID=$con->GetSQLSingleResult("SELECT id_payment as item FROM sponsorship_payments_items WHERE id_sponsorship=".$intID);
    $intPaymentItemsCount=$con->GetSQLSingleResult("SELECT count(*) as item FROM sponsorship_payments_items WHERE id_payment=".$intPaymentID);

    $query="DELETE FROM sponsorship WHERE id_customer=".$intCustomerID." AND id=".$intID;
    //echo  $query;
    $con->RunQuery($query);

    $query="DELETE FROM sponsorship_payments_items WHERE id_sponsorship=".$intID;
    $con->RunQuery($query);
    
    //echo $intPaymentItemsCount;
    if ($intPaymentItemsCount>1){
            //prepocitat payment a smazat item
              $query_discount="SELECT price,duration_months from sponsorship INNER JOIN sponsorship_payments_items ON sponsorship_payments_items.id_sponsorship=sponsorship.id WHERE id_customer=".$intCustomerID." AND id_payment=".$intPaymentID;
                        $intSponsorshipTotal=$con->GetQueryNum($query_discount);
                        $intSponsorshipTotalPrice=0;
                        if ($intSponsorshipTotal>0){
                          $result_discount = $con->SelectQuery($query_discount);
                          while($write_discount = $result_discount->fetch_array()){
                            $intSponsorshipTotalPrice=$intSponsorshipTotalPrice+($write_discount['price']*$write_discount['duration_months']);
                          }

                          //tady budou slevy
                          $intTotalDiscount=getDiscount($intSponsorshipTotal);
                          $intSponsorshipTotalPrice=round(($intSponsorshipTotalPrice-(($intSponsorshipTotalPrice/100)*$intTotalDiscount)),2);
                          
                          $query="UPDATE sponsorship_payments SET price_total=".$intSponsorshipTotalPrice.",id_discount=".$intTotalDiscount." WHERE id=".$intPaymentID;
                          //echo $query;
                          $con->RunQuery($query);
                        }


    }else{
      //smazat payment

      $query="DELETE FROM sponsorship_payments WHERE id=".$intPaymentID;
      //echo $query;
      $con->RunQuery($query);
    }

    

    
    

  }
  
   header("Location: /sponsorship.html?message=3");
}

//delete order
if ($_GET['action']=="cancel-order"){

  $_SESSION['sponsorship_id']="";
  $_SESSION['sponsorship_type']="";
  header("Location: /sponsorship.html");

}

//update ADS
if ($_POST['action']=="update-ads"){
   $files = new files();

   $strUserArray=check_login(1);
    $intCustomerID=$strUserArray['id'];
    $intCustomerID=$input->check_number($intCustomerID);

   $id=$input->check_number($_POST['id']);
   $id_type=$input->check_number($_POST['ads_id_type']);
   $name=$input->valid_text($_POST['ads_name'],true,true);
   $url=$input->valid_text($_POST['ads_url'],true,true);
   $html_text=$input->valid_text($_POST['html_text'],true,true);
   $id_image=$input->check_number($_POST['id_image']);
   if (!empty($id) and !empty($id_type) and !empty($name)){
        
        $boolError=false;

        $is_approved=$con->GetSQLSingleResult("SELECT is_approved as item FROM sponsorship_ads WHERE id=".$id);
        if ($is_approved==1){
          //normalni update - neni potvrzeno
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


            if ($id_image==1){
                $strUploadifyDataType="sponsorship"; 
                $strUploadifyDataExt="jpg";
                $filename=$strUploadifyDataType."_".md5($strUserArray['id']).'.'.$strUploadifyDataExt;
                $FolderOldPath=IncludeAdminPath."/inc/uploadify/uploads_temp/";
                $FilePath=$FolderOldPath."/".$filename;
                  
                if ($files->checkFile($FilePath)){

                  $IntImageSize = getimagesize($FilePath);
                  if ($IntImageSize[0]<=600 AND $IntImageSize[1]<=125){
                    $filenameNew='sponsorship_'.$id.'.jpg';
                    $FolderNewPath=IncludeAdminPath.'/'.PhotoFolder.'/sponsorship/';
                    $FilePathNew=$FolderNewPath."/".$filenameNew;
                    if ($files->moveFile($FolderOldPath,$FolderNewPath,$filename,$filenameNew)){
                      $query_update="UPDATE sponsorship_ads SET id_image=1, id_type=2 WHERE id=".$id;
                      $con->RunQuery($query_update);  
                    }
                  }else{
                   $boolError=true;
                }
                }
            }


            if ($boolError==false){

              $query="UPDATE sponsorship_ads SET 
                  id_type=".$id_type.",name='".$name."',url='".$url."',html_text='".$html_text."',id_image=".$id_image."
                  WHERE id=".$id."";
              //echo  $query;
              $con->RunQuery($query);

            }



        }else{
          //update pres schvalovani  - je potrvrzeno
          $query="INSERT INTO  sponsorship_ads (id_customer,id_type,name,url,html_text,id_ads_update) VALUES (".$intCustomerID.",1,'".$name."','".$url."','".$html_text."',".$id.")";
          //echo $query; 
          $con->RunQuery($query);
          $intIdAds=$con->GetSQLSingleResult("SELECT id as item FROM sponsorship_ads WHERE id_customer=".$intCustomerID." ORDER by id DESC LIMIT 1");

          if ($id_image==1){
            $files = new files();
                 
              $strUploadifyDataType="sponsorship"; 
                  $strUploadifyDataExt="jpg";
                  $filename=$strUploadifyDataType."_".md5($strUserArray['id']).'.'.$strUploadifyDataExt;
                  $FolderOldPath=IncludeAdminPath."/inc/uploadify/uploads_temp/";
                  $FilePath=$FolderOldPath."/".$filename;
                  
                  if ($files->checkFile($FilePath)){
                    $IntImageSize = getimagesize($FilePath);
                    if ($IntImageSize[0]<=600 AND $IntImageSize[1]<=125){
                      $filenameNew='sponsorship_'.$intIdAds.'.jpg';
                      $FolderNewPath=IncludeAdminPath.'/'.PhotoFolder.'/sponsorship/';
                      $FilePathNew=$FolderNewPath."/".$filenameNew;
                      if ($files->moveFile($FolderOldPath,$FolderNewPath,$filename,$filenameNew)){
                           $query_update="UPDATE sponsorship_ads SET id_image=1, id_type=2 WHERE id=".$intIdAds;
                           $con->RunQuery($query_update);  
                      }
                    }else{
                      $boolError=true;
                      $query_delete="DELETE FROM sponsorship_ads WHERE id=".$intIdAds;
                      $con->RunQuery($query_delete);
                    }
                  }
          }



        }
    }

    if ($boolError==false){
        header("Location: /sponsorship-ads.html?message=1");   
    }else{
        header("Location: /sponsorship-ads-update/".($id+123)."-ad.html?message=1");   
    }

}  

//delete sponsorship
if ($_GET['action']=="delete-ads"){
  $boolError=true;
  $strUserArray=check_login(1);
  $intCustomerID=$strUserArray['id'];
  $intCustomerID=$input->check_number($intCustomerID);

  $intID=$input->check_number($_GET['id']);
  if (!empty($intID)){

    $query="DELETE FROM sponsorship_ads WHERE id=".$intID;
    if ($con->RunQuery($query)==true){
        $files = new files();
        $FilePath=IncludeAdminPath.'/'.PhotoFolder.'/sponsorship/sponsorship_'.$intID.'.jpg';
        if ($files->checkFile($FilePath)){
          unlink($FilePath);
        }
    }

  }

  header("Location: /sponsorship-ads.html?message=2");
}  

?>
   
