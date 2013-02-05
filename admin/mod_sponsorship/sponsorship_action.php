<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(33,1,0,0,0);
$input = new Input_filter();

require_once("sponsorship_send_activate.php");

//pridat novy item
    if ($_POST['action']=="assign_add" and $users->checkUserRight(2)){
    
    $bollError=false;
    $id_category_1=$input->check_number($_POST['id_category_1']);
    $id_category_2=$input->check_number($_POST['id_category_2']);
    $id_category_3=$input->check_number($_POST['id_category_3']);

    if (!empty($id_category_1)){
      $id_category=$id_category_1;
      $id_item=$input->check_number($_POST['id_item_1']);      
      $id_type=1;
    }
    if (!empty($id_category_2)){
      $id_category=$id_category_2;
      $id_item=$input->check_number($_POST['id_item_2']);      
      $id_type=2;
    }
    if (!empty($id_category_3)){
      $id_category=$id_category_3;
      $id_item=$input->check_number($_POST['id_item_3']);      
      $id_type=3;
    }
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_type='.$id_type;    ;
    //&id_category_1='.$_POST['id_category_1'].'&id_category_2='.$_POST['id_category_2'].'&id_category_3='.$_POST['id_category_3'].'&id_item_1='.$_POST['id_item_1'].'&id_item_2='.$_POST['id_item_2'].'&id_item_3='.$_POST['id_item_3'].'
    
    if (!empty($id_category) and !empty($id_item)and !empty($id_type)){

          $isInSponsorship=$con->GetSQLSingleResult("SELECT count(*) as item FROM sponsorship_items WHERE id_item=".$id_item." AND id_type=".$id_type."");
          if ($isInSponsorship==0){

          $query="INSERT INTO  sponsorship_items (id_item,id_type,id_category)
                  VALUES (".$id_item.",'".$id_type."',".$id_category.")
            ";
            //echo $query; 
           if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=98;
           }
          }else{
            $bollError=true;
              $strError=97;
          }
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: sponsorship_items.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: sponsorship_items.php".Odkaz."&message=1".$strReturnData);
    }
  }

//smazat zaznam item
    if ($_GET['action']=="item_delete" and $users->checkUserRight(4)){
    $strReturnData='&id_type='.$_GET['id_type'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM sponsorship_items WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: sponsorship_items.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: sponsorship_items.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: sponsorship_items.php".Odkaz."&message=99".$strReturnData);}
    }



//upravit zaznam
if ($_POST['action']=="assign_mass" and $users->checkUserRight(3)){
    
    $bollError=false;
    
    
    $strReturnData="&filter=".$_POST['filter']."&id_type=".$_POST['id_type'];
    
    
    if (!empty($_POST['id'])){
        
           foreach ($_POST['id'] as $key => $value) {
             $intID=$value;
             $intIDcategory=$_POST['id_category'];
             $intIDcategory=$input->check_number($intIDcategory);
             if (!empty($intID) and !empty($intIDcategory)){
                 $query="UPDATE sponsorship_items SET id_category='".$intIDcategory."' WHERE id=".$intID;
               $con->RunQuery($query);
             }

           }


           
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: sponsorship_items.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: sponsorship_items.php".Odkaz."&message=3".$strReturnData);
    }
  }

  //nastavit na zaplaceno
if ($_POST['action']=="add_paid" and $users->checkUserRight(3)){
    
    $bollError=false;
    
    $id=$input->check_number($_POST['id']);
    $id_payment_method=$input->check_number($_POST['id_payment_method']);
    $date_paid=$input->valid_text($_POST['date_paid'],true,true);
        
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id;
    
    
    if (!empty($id) AND !empty($id_payment_method) AND !empty($date_paid)){
        $date_paid=date("Y-m-d",strtotime($date_paid));
        
        $query="UPDATE sponsorship_payments SET last_update_user=".$users->getIdUser().",date_paid='".$date_paid."',id_status=1,id_payment_method=".$id_payment_method." WHERE id=".$id;
        //echo $query; 
        if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=98;
        }else{

         //zkontrolovat ads - pokud je zaplacen - aktivovat vsechny sponsorship
        $query="SELECT sponsorship.id_ads,sponsorship.id,sponsorship.id_item,sponsorship.id_type,sponsorship.id_customer FROM sponsorship INNER JOIN  sponsorship_payments_items ON sponsorship.id=sponsorship_payments_items.id_sponsorship WHERE id_payment=".$id;
        //echo $query; 
        $result = $con->SelectQuery($query);
        if ($con->GetQueryNum($query)>0){
          $boolActivate=false;
          while($write = $result->fetch_array())
          {
            $isApproved=$con->GetSQLSingleResult("SELECT is_approved as item FROM sponsorship_ads WHERE id=".$write['id_ads']);
            if ($isApproved==2){
              //aktivovat sponsorship
              $query_update="UPDATE sponsorship SET last_update_user=".$users->getIdUser().", id_status=2, date_expire=('".$date_paid."' + INTERVAL duration_months MONTH)  WHERE id=".$write['id'];
              $con->RunQuery($query_update);              
              //echo $query_update; 
              $boolActivate=true;
                            
              $arraySponsorshipItem[$write['id']]['id_item']=$write['id_item'];
              $arraySponsorshipItem[$write['id']]['id_type']=$write['id_type'];
              
            }
            $email=$con->GetSQLSingleResult("SELECT email as item FROM web_users WHERE id=".$write['id_customer']);
          }
          if ($boolActivate) send_activate_email($email,$arraySponsorshipItem);
        }
      }
       

           
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: sponsorship_paid.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: sponsorship.php".Odkaz."&message=1".$strReturnData);
    }
  }

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

  //smazat nezaplaceny sponsorship
    if ($_GET['action']=="delete_sponsorship" and $users->checkUserRight(4)){
      $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
      if (isset($_GET['id'])){
        $intID=$_GET['id'];

        $intPaymentID=$con->GetSQLSingleResult("SELECT id_payment as item FROM sponsorship_payments_items WHERE id_sponsorship=".$intID);
        $intPaymentItemsCount=$con->GetSQLSingleResult("SELECT count(*) as item FROM sponsorship_payments_items WHERE id_payment=".$intPaymentID);

    $query="DELETE FROM sponsorship WHERE id=".$intID;
    //echo  $query;
    $con->RunQuery($query);

    $query="DELETE FROM sponsorship_payments_items WHERE id_sponsorship=".$intID;
    $con->RunQuery($query);
    
    //echo $intPaymentItemsCount;
    if ($intPaymentItemsCount>1){
            //prepocitat payment a smazat item
              $query_discount="SELECT price,duration_months from sponsorship INNER JOIN sponsorship_payments_items ON sponsorship_payments_items.id_sponsorship=sponsorship.id WHERE id_payment=".$intPaymentID;
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
     
       header("Location: sponsorship_manage.php".Odkaz."&message=2".$strReturnData);


      }else{header("Location: sponsorship_manage.php".Odkaz."&message=99".$strReturnData);}
    }
    




?>