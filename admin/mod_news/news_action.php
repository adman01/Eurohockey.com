<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(2,1,0,0,0);
$games = new games($con);
$input = new Input_filter();

//pridat novy zaznam
    if ($_POST['action']=="add" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $header=$_POST['header'];
    $header=$input->valid_text($header,true,true);
    $text=$_POST['text'];
    $text=$input->valid_text($text,true,false);
    $is_link=$_POST['is_link'];
    $is_link=$input->check_number($is_link);
    if ($is_link==1){
        $link=$_POST['link'];
        $link=$input->valid_text($link,true,true);
    }
    $date_time=date("Y-m-d H:i:s",strtotime($_POST['date']." ".$_POST['time']));
    $pub_date_time=date("Y-m-d H:i:s",strtotime($_POST['pub_date']." ".$_POST['pub_time']));
    if (!empty($_POST['expire_date'])){
      $expire_date_time=date("Y-m-d H:i:s",strtotime($_POST['expire_date']." ".$_POST['expire_time']));
    }
    $show_item=$_POST['show_item'];
    $show_item=$input->check_number($show_item);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&show_item='.$show_item.'&expire_date_time='.$expire_date_time.'&pub_date_time='.$pub_date_time.'&date_time='.$date_time.'&link='.$link.'&is_link='.$is_link.'&header='.$header.'&text='.urlencode($text);
    
    if (!empty($header) and !empty($text)){
          $query="INSERT INTO news (id_user,header,text,date_time,pub_date,expire_date,is_link,link,show_item)
                  VALUES (".$users->getIdUser().",'".$header."','".$text."','".$date_time."','".$pub_date_time."','".$expire_date_time."',".$is_link.",'".$link."',".$show_item.")
            ";
           // echo $query;
           if (!$con->RunQuery($query)){
              //spatna DB
              $bollError=true;
              $strError=98;
           }else{
              
              
           }
      }else{
          //špatná nebo chybějící vstupní data
          $bollError=true;
          $strError=99;
      }
    
    if ($bollError){
         header("Location: news_add.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: news.php".Odkaz."&message=1".$strReturnData);
    }
  }


//smazat zaznam
    if ($_GET['action']=="delete" and $users->checkUserRight(4)){
    $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM news WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: news.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: news.php".Odkaz."&message=99".$strReturnData);} 
      }else{header("Location: news.php".Odkaz."&message=99".$strReturnData);}
    }
    
    
//upravit zaznam
    if ($_POST['action']=="update" and $users->checkUserRight(3)){
    
    $bollError=false;
    $header=$_POST['header'];
    $header=$input->valid_text($header,true,true);
    $text=$_POST['text'];
    $text=$input->valid_text($text,true,false);
    $is_link=$_POST['is_link'];
    $is_link=$input->check_number($is_link);
    if ($is_link==1){
        $link=$_POST['link'];
        $link=$input->valid_text($link,true,true);
    }
    $date_time=date("Y-m-d H:i:s",strtotime($_POST['date']." ".$_POST['time']));
    $pub_date_time=date("Y-m-d H:i:s",strtotime($_POST['pub_date']." ".$_POST['pub_time']));
    if (!empty($_POST['expire_date'])){
      $expire_date_time=date("Y-m-d H:i:s",strtotime($_POST['expire_date']." ".$_POST['expire_time']));
    }
    $show_item=$_POST['show_item'];
    $show_item=$input->check_number($show_item);
        
    $id=$_POST['id'];
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id.'&show_item='.$show_item.'&expire_date_time='.$expire_date_time.'&pub_date_time='.$pub_date_time.'&date_time='.$date_time.'&link='.$link.'&is_link='.$is_link.'&header='.$header.'&text='.urlencode($text);
    
    if (!empty($header) and !empty($text) and !empty($id)){
        
            $query="UPDATE news SET id_user=".$users->getIdUser().",header='".$header."',text='".$text."',date_time='".$date_time."',pub_date='".$pub_date_time."',expire_date='".$expire_date_time."',is_link=".$is_link.",link='".$link."',show_item=".$show_item." WHERE id=".$id;
        
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
         header("Location: news_update.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: news.php".Odkaz."&message=3".$strReturnData);
    }
  }

//switch status
if ($_GET['action']=="switch" and $users->checkUserRight(3)){
      $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        
        $query = "SELECT show_item FROM news WHERE id=".$id."";
        $result = $con->SelectQuery($query);
        $write = $result->fetch_array();
        $intStatus=$write['show_item'];
        $query = "SELECT id FROM show_status_list WHERE id=".($intStatus+1)."";
        if ($con->GetQueryNum($query)==0){
          $intStatus=0;
        }else{
          $intStatus++;
        }
        
        $query="UPDATE news SET show_item=".$intStatus." WHERE id=".$id;
        $con->RunQuery($query);
        
           header("Location: news.php".Odkaz."".$strReturnData);
        }
        else{header("Location: news.php".Odkaz."&message=99".$strReturnData);} 
}


//pridat novy zaznam / prirazeni news k item
    if ($_POST['action']=="assign_add" and $users->checkUserRight(2)){
    
    $bollError=false;
    
    $id_country=$_POST['id_country'];
    $id_country=$input->check_number($id_country);
    $id_league=$_POST['id_league'];
    $id_league=$input->check_number($id_league);
    $id_club=$_POST['id_club'];
    $id_club=$input->check_number($id_club);
    $id_player=$_POST['id_player'];
    $id_player=$input->check_number($id_player);
    $is_item=$_POST['is_item'];
    $is_item=$input->check_number($is_item);
    $id=$_POST['id'];
    $id=$input->check_number($id);
    
    $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id_country='.$id_country.'&id_league='.$id_league.'&id_club='.$id_club.'&id_player='.$id_player.'&is_item='.$is_item.'&id='.$id;
    
     if (!empty($is_item) and (!empty($id_country) or !empty($id_league) or !empty($id_club)  or !empty($id_player))){
          
           switch ($is_item){
            case 1:
              $id_assign=$id_country;
            break;
            case 2:
              $id_assign=$id_league;
            break;
            case 3:
              $id_assign=$id_club;
            break;
            case 4:
              $id_assign=$id_player;
            break;
          }
          
          $query="INSERT INTO news_items (id_news,id_item,id_item_type)
                  VALUES (".$id.",".$id_assign.",".$is_item.")
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
         header("Location: news_assign.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: news_assign.php".Odkaz."&message=1".$strReturnData);
    }
  }

//smazat zaznam / prirazeni news k item
    if ($_GET['action']=="assign_delete" and $users->checkUserRight(4)){
    $strReturnData='&id='.$_GET['id_item'].'&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM news_items WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: news_assign.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: news_assign.php".Odkaz."&message=98".$strReturnData);} 
      }else{header("Location: news_assign.php".Odkaz."&message=99".$strReturnData);}
    }

      

?>