<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(29,1,0,0,0);
$games = new games($con);
$input = new Input_filter();

//pridat novy zaznam
if ($_POST['action']=="add" and $users->checkUserRight(2)){
    
    $intError=0; $intCorrect=0;
    $bollError=false;
    
    $number=$_POST['number'];
    for ($i=1; $i<=($number+1); $i++){
      
      $id_player=$_POST['id_player'][$i];
      $id_player=$input->check_number($id_player);
      if (!empty($id_player) and !empty($_POST['date_time'][$i])){
      
      $date_time=$_POST['date_time'][$i];
      $date_time=date("Y-m-d H:i:s",strtotime($date_time));
      $id_position=$_POST['id_position'][$i];
      $id_position=$input->check_number($id_position);
      $id_retire_status=$_POST['id_retire_status'][$i];
      $id_retire_status=$input->check_number($id_retire_status);
      $is_from=$_POST['is_from'][$i];
      $is_from=$input->check_number($is_from);
      $id_club_from=$_POST['id_club_from'][$i];
      $id_club_from=$input->check_number($id_club_from);
      $id_country_from=$_POST['id_country_from'][$i];
      $id_country_from=$input->check_number($id_country_from);
      $id_league_from=$_POST['id_league_from'][$i];
      $id_league_from=$input->check_number($id_league_from);
      $id_league_to=$_POST['id_league_to'][$i];
      $id_league_to=$input->check_number($id_league_to);
      
      switch ($is_from){
        case 1:
          $id_country_from=0;
        break;  
        case 2:
          $id_club_from=0;
        break;
        default:
          $id_club_from=0;
          $id_country_from=0;
        break;
      }
      $is_to=$_POST['is_to'][$i];
      $is_to=$input->check_number($is_to);
      $id_club_to=$_POST['id_club_to'][$i];
      $id_club_to=$input->check_number($id_club_to);
      $id_country_to=$_POST['id_country_to'][$i];
      $id_country_to=$input->check_number($id_country_to);
      switch ($is_to){
        case 1:
          $id_country_to=0;
        break;  
        case 2:
          $id_club_to=0;
        break;
        default:
          $id_club_to=0;
          $id_country_to=0;
        break;
      }
      $note=$_POST['note'][$i];
      $note=$input->valid_text($note,true,true);
      $source=$_POST['source'][$i];
      $source=$input->valid_text($source,true,true);
      $id_source_note=$_POST['id_source_note'][$i];
      $id_source_note=$input->check_number($id_source_note);
      
      $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'];
          $query="INSERT INTO transfers (last_update_user,date_time,id_player,id_position,id_retire_status,id_club_from,id_club_to,id_country_from,id_country_to,id_league_from,id_league_to,note,source,id_source_note)
                      VALUES (".$users->getIdUser().",'".$date_time."',".$id_player.",".$id_position.",".$id_retire_status.",".$id_club_from.",".$id_club_to.",".$id_country_from.",".$id_country_to.",".$id_league_from.",".$id_league_to.",'".$note."','".$source."',".$id_source_note.")
                  ";
           //echo $query;
           if (!$con->RunQuery($query)){
              //spatna DB
              $intError++;
           }else{
              $intCorrect++;
           }
      
    }
    }
    
    header("Location: transfers.php".Odkaz."&message=1".$strReturnData."&intCorrect=".$intCorrect."&intError=".$intError);
  }


//smazat zaznam
    if ($_GET['action']=="delete" and $users->checkUserRight(4)){
    $strReturnData='&filter='.$_GET['filter'].'&filter2='.$_GET['filter2'].'&list_number='.$_GET['list_number'];
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM transfers WHERE id=".$id;
        if ($con->RunQuery($query)==true){
           header("Location: transfers.php".Odkaz."&message=2".$strReturnData);
        }
        else{header("Location: transfers.php".Odkaz."&message=99".$strReturnData);} 
      }else{header("Location: transfers.php".Odkaz."&message=99".$strReturnData);}
    }
    
    
//upravit zaznam
    if ($_POST['action']=="update" and $users->checkUserRight(3)){
    
    $bollError=false;
    
      $id_player=$_POST['id_player'];
      $id_player=$input->check_number($id_player);
      $date_time=$_POST['date_time'];
      $date_time=date("Y-m-d H:i:s",strtotime($date_time));
      $id_position=$_POST['id_position'];
      $id_position=$input->check_number($id_position);
      $id_retire_status=$_POST['id_retire_status'];
      $id_retire_status=$input->check_number($id_retire_status);
      $is_from=$_POST['is_from'];
      $is_from=$input->check_number($is_from);
      $id_club_from=$_POST['id_club_from'];
      $id_club_from=$input->check_number($id_club_from);
      $id_country_from=$_POST['id_country_from'];
      $id_country_from=$input->check_number($id_country_from);
      $id_league_from=$_POST['id_league_from'];
      $id_league_from=$input->check_number($id_league_from);
      $id_league_to=$_POST['id_league_to'];
      $id_league_to=$input->check_number($id_league_to);
      
      switch ($is_from){
        case 1:
          $id_country_from=0;
        break;  
        case 2:
          $id_club_from=0;
          $id_league_from=0;
        break;
        default:
          $id_club_from=0;
          $id_league_from=0;
          $id_country_from=0;
        break;
      }
      
      $is_to=$_POST['is_to'];
      $is_to=$input->check_number($is_to);
      $id_club_to=$_POST['id_club_to'];
      $id_club_to=$input->check_number($id_club_to);
      $id_country_to=$_POST['id_country_to'];
      $id_country_to=$input->check_number($id_country_to);
      switch ($is_to){
        case 1:
          $id_country_to=0;
        break;  
        case 2:
          $id_club_to=0;
          $id_league_to=0;
        break;
        default:
          $id_club_to=0;
          $id_league_to=0;
          $id_country_to=0;
        break;
      }
      $note=$_POST['note'];
      $note=$input->valid_text($note,true,true);
      $source=$_POST['source'];
      $source=$input->valid_text($source,true,true);
      $id_source_note=$_POST['id_source_note'];
      $id_source_note=$input->check_number($id_source_note);
        
      $id=$_POST['id'];
      $strReturnData='&filter='.$_POST['filter'].'&filter2='.$_POST['filter2'].'&list_number='.$_POST['list_number'].'&id='.$id.'&id_player='.$id_player.'&date_time='.$_POST['date_time'].'&id_position='.$id_position.'&id_retire_status='.$id_retire_status.'&id_club_from='.$id_club_from.'&id_country_from='.$id_country_from.'&id_club_to='.$id_club_to.'&id_country_to='.$id_country_to.'&id_league_from='.$id_league_from.'&id_league_to='.$id_league_to.'&id_source_note='.$id_source_note.'&note='.urlencode($note).'&source='.urlencode($source);
    
      if (!empty($id_player) and !empty($date_time) and !empty($id_position) and !empty($id_retire_status) and !empty($id_source_note) and !empty($id)){
        
          $query="UPDATE transfers SET 
                    last_update_user=".$users->getIdUser().",date_time='".$date_time."',id_player=".$id_player.",id_position=".$id_position.",id_retire_status=".$id_retire_status.",id_club_from=".$id_club_from.",id_club_to=".$id_club_to.",id_country_from=".$id_country_from.",id_country_to=".$id_country_to.",id_league_from=".$id_league_from.",id_league_to=".$id_league_to.",note='".$note."',source='".$source."',id_source_note=".$id_source_note."
                  WHERE id=".$id;
          
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
         header("Location: transfers_update.php".Odkaz."&message=".$strError.$strReturnData);
    }else{
         header("Location: transfers.php".Odkaz."&message=3".$strReturnData);
    }
  }


?>