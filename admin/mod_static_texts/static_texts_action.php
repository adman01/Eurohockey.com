<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(4,1,0,0,0);
$games = new games($con);
$input = new Input_filter();

//pridat novy zaznam
    if ($_POST['action']=="add" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $header=$_POST['header'];
    $header=$input->valid_text($header,true,true);
    $text=$_POST['text'];
    $text=$input->valid_text($text,true,false);
    $keywords=$_POST['keywords'];
    $keywords=$input->valid_text($keywords,true,false);
    $show_item=$_POST['show_item'];
    $show_item=$input->check_number($show_item);
    $_SESSION['edited_text']=$text;
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&show_item='.$show_item.'&header='.$header.'&keywords='.urlencode($keywords);
    
    if (!empty($header) and !empty($text)){
          $query="INSERT INTO static_texts (id_user,header,text,pub_date,keywords,show_item)
                  VALUES (".$users->getIdUser().",'".$header."','".$text."',NOW(),'".$keywords."',".$show_item.")
            ";
            //echo $query;
           $con->RunQuery($query);
           //if (!$con->RunQuery($query)){
              //spatna DB
              //$bollError=true;
              //$strError=98;
           //}
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: static_texts_add.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         $_SESSION['edited_text']="";
           
         header("Location: static_texts.php".Odkaz."&message=1".$strReturnData);
    }
  }


//smazat zaznam
    if ($_GET['action']=="delete" and $users->checkUserRight(4)){
    $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM static_texts WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: static_texts.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: static_texts.php".Odkaz."&message=99".$strReturnData);} 
      }else{header("Location: static_texts.php".Odkaz."&message=99".$strReturnData);}
    }
    
    
//upravit zaznam
    if ($_POST['action']=="update" and $users->checkUserRight(3)){
    
    $bollError=false;
    $header=$_POST['header'];
    $header=$input->valid_text($header,true,true);
    $text=$_POST['text'];
    $text=$input->valid_text($text,true,false);
    $keywords=$_POST['keywords'];
    $keywords=$input->valid_text($keywords,true,false);
    $show_item=$_POST['show_item'];
    $show_item=$input->check_number($show_item);
        
    $id=$_POST['id'];
    $_SESSION['edited_text']=$text;
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id.'&show_item='.$show_item.'&header='.$header.'&keywords='.urlencode($keywords);
    
    if (!empty($header) and !empty($text) and !empty($id)){
        
            $query="UPDATE static_texts SET id_user=".$users->getIdUser().",header='".$header."',text='".$text."',keywords='".$keywords."',show_item=".$show_item." WHERE id=".$id;
            $con->RunQuery($query);
           //if (!$con->RunQuery($query)){
              //spatna DB
             // $bollError=true;
              //$strError=98;
           //}
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: static_texts_update.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         $_SESSION['edited_text']="";
         header("Location: static_texts.php".Odkaz."&message=3".$strReturnData);
    }
  }

//switch status
if ($_GET['action']=="switch" and $users->checkUserRight(3)){
      $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        
        $query = "SELECT show_item FROM static_texts WHERE id=".$id."";
        $result = $con->SelectQuery($query);
        $write = $result->fetch_array();
        $intStatus=$write['show_item'];
        $query = "SELECT id FROM show_status_list WHERE id=".($intStatus+1)."";
        if ($con->GetQueryNum($query)==0){
          $intStatus=0;
        }else{
          $intStatus++;
        }
        
        $query="UPDATE static_texts SET show_item=".$intStatus." WHERE id=".$id;
        $con->RunQuery($query);
        
           header("Location: static_texts.php".Odkaz."".$strReturnData);
        }
        else{header("Location: static_texts.php".Odkaz."&message=99".$strReturnData);} 
}

?>