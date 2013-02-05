<?php
Define(IncludeAdminPath, $_SERVER['DOCUMENT_ROOT']."/"); //cesta k includum pro admin
require_once(IncludeAdminPath."/inc/config.inc");
require_once(IncludeAdminPath."/admin/inc/class/class_HTML_code.php");
require_once(IncludeAdminPath."/admin/inc/class/class_database.php");
require_once(IncludeAdminPath."/admin/inc/class/class_users.php");
require_once(IncludeAdminPath."/admin/inc/class/class_inputfilter.php");
require_once(IncludeAdminPath."/admin/inc/class/class_listing.php");
require_once(IncludeAdminPath."/admin/inc/class/class_files.php");
require_once(IncludeAdminPath."/admin/inc/class/class_games.php");
require_once(IncludeAdminPath."/admin/inc/class/class_xml_generator.php");
require_once(IncludeAdminPath."/inc/phpmailer/class.phpmailer.php");


$con = new database(MySQLServer, MySQLDatabase, MySQLUser, MySQLPassword);
$con->setCharset(MySQLCharset);

function write_select($id1,$id2){
  if ($id1==$id2) return ' selected="selected"'; else return '';
}

//podle true false zobrazi ANO nebo NE
function show_boolean($id)
{
  if ($id==1){$text="yes";}else {$text="no";};
  return $text; 
}

function show_active_menu($strMenu1,$strMenu2){
  if ($strMenu1==$strMenu2) return ' id="submenu-active"'; else  return '';
}

//----------------- URL conversion

function get_url_text($q,$id){

  global $input;

  

  $id=$id+123;

  if (strlen($q) > 0)

  {

   $hint=strip_tags(str_replace(" ","-",$q));

   $hint=$input->get_clean_text($hint);

   $hint=str_replace(".","",$hint);

   $hint=str_replace(",","-",$hint);

   $hint=str_replace(":","-",$hint);

   $hint=str_replace("/","",$hint);

   $hint=str_replace("!","",$hint);

   $hint=str_replace(";","",$hint);

   $hint=str_replace("'","",$hint);

   $hint=str_replace('"',"",$hint);

   $hint=str_replace(" - ", " ", $hint);

   $hint=str_replace("- ", " ", $hint);

   $hint=str_replace("/", "-", $hint);

   $hint=str_replace(" -", " ", $hint);

   $hint=str_replace("-", " ", $hint);

   $hint=str_replace(":", "-", $hint);

   $hint=str_replace("&quot;", "", $hint);

   $hint=str_replace("%22", "", $hint);

   $hint=str_replace("(", "-", $hint);

   $hint=str_replace(")", "-", $hint);

   $hint=str_replace(",", "", $hint);

   $hint=str_replace(".", "", $hint);

   $hint=str_replace("!", "", $hint);

   $hint=str_replace("?", "", $hint);

   $hint=str_replace("&", "", $hint);

   $hint=str_replace("+", "-plus", $hint);

   $hint=str_replace("°", "", $hint);

   $hint=str_replace(" ", "-", $hint);

   $hint=strtolower($hint);

   $hint=$id.'-'.$hint.'.html';

   $hint=str_replace('--',"-",$hint);

   $hint=str_replace('---',"-",$hint);

  }

  return $hint;

}


function show_date_difference ($date1,$date2){
  $dateDiff = $date1-$date2;
  $fullDays = floor($dateDiff/(60*60*24));
  $fullHours = floor(($dateDiff-($fullDays*60*60*24))/(60*60));
  $fullMinutes = floor(($dateDiff-($fullDays*60*60*24)-($fullHours*60*60))/60);
  return $fullDays.'d '.$fullHours.'h:'.$fullMinutes.'m';
}

function get_foto_path($con,$id_img){
  $query_path="SELECT file_name,name, (SELECT folder_name from photo_catg WHERE photo_catg.id=photo.id_catg) as folder from photo where id=".$id_img;
  $result_path = $con->SelectQuery($query_path);
  $write_path = $result_path->fetch_array();
  $imgPath='../../'.PhotoFolder."/".$write_path['folder'].'/'.$write_path['file_name'];
  return $imgPath;
}

function sendEmail($strReplyEmail,$strReplyName,$strFromEmail,$strFromName,$strEmail,$StrSubject,$strContent){

          $mail = new PHPMailer(true); //New instance, with exceptions enabled
          
          $mail->IsSMTP();                           // tell the class to use SMTP
          $mail->SMTPAuth   = true;                  // enable SMTP authentication
          //$mail->Port       = 26;                    // set the SMTP server port
          $mail->Host       = Mail_SMTP_Server; // SMTP server
          $mail->Username   = Mail_SMTP_Server_User;     // SMTP server username
          $mail->Password   = Mail_SMTP_Server_Psw;            // SMTP server password

          //$mail->IsSendmail();  // tell the class to use Sendmail
          if (empty($strReplyEmail)) $strReplyEmail=strEmailFrom;
          if (empty($strFromEmail)) $strFromEmail=strEmailFrom;
          $mail->AddReplyTo($strReplyEmail,$strReplyName);
          $mail->From       = $strFromEmail;
          $mail->FromName   = $strFromName;
          
          $mail->AddAddress($strEmail);
          $mail->CharSet = "UTF-8";
          $mail->Subject  = $StrSubject;
          $mail->Body = $strContent;
          if ($mail->Send()) { return true;} else {return false;}
}


//funkce na vypis image boxu
function show_image_select_box ($con,$intIdBox,$intID,$strInputName){
	    
	    echo '
      <div style="position:relative;">
	    <span class="label label-04">Image name</span>
      ';
	    
      if (!empty($intID)){  
      	$query_sub="SELECT id,description,file_name FROM photo WHERE id=".$intID;
      	if ($con->GetQueryNum($query_sub)>0){
	       $result_sub = $con->SelectQuery($query_sub);
	       $write_sub = $result_sub->fetch_array();
	       $intID=$write_sub['id'];
	       $intName=''.$write_sub['description'].' <a onclick="return !window.open(this.href);" href="http://'.$_SERVER["SERVER_NAME"].'/'.PhotoFolder.'/'.$write_sub["file_name"].'" class="lightwindow page-options ico-show" rel="Picture">'.$write_sub['file_name'].'</a>';
	     }else{
         $intID="";
	       $intName="none";
       }
	    }else{
        $intID="";
	      $intName="none";
      }
        echo '<span id="image_name'.$intIdBox.'">'.$intName.'</span>';
        echo'&nbsp;<a href="javascript:cancel_image('.$intIdBox.')" title="Cancel"><img src="../inc/design/ico-delete.gif" class="ico" alt="Cancel"></a>&nbsp;|&nbsp;';
        echo'<a class="ico-list" href="javascript:show_image_window('.$intIdBox.')" title="Set picture">search picture</a>';
        echo'<div id="image_livesearch_window'.$intIdBox.'" class="livesearch_image_window">';
          echo '<div style="float:left"><span class="label label-03">Type name of image</span></div>';
          echo '<div style="float:right"><a href="javascript:close_image_window('.$intIdBox.')" class="ico-delete">close</a></div>';
          echo '
          <div style="clear:both; position:relative; padding:10px 0px 10px 0px; z-index:90">
            <input type="text" name="filter_box" id="filter_image'.$intIdBox.'" autocomplete="off" value="" class="input-text" style="width:140px;" onkeyup="send_image_livesearch_data(this.value,'.$intIdBox.')" />
            <div id="image_livesearch'.$intIdBox.'" class="livesearch_image"></div>
          </div>
         ';
        echo '</div>';
      echo '</div>';
      echo '<input type="hidden" id="image_input'.$intIdBox.'" name="'.$strInputName.'" value="'.$intID.'" /><br />';
      }

//funkce na vypis image boxu
function show_image_folder_select_box ($con,$intIdBox,$intID,$strInputName){
	    
	    echo '
      <div style="position:relative;">
	    <span class="label label-04">Image folder name</span>
      ';
	    
      if (!empty($intID)){  
      	$query_sub="SELECT id,name,(SELECT count(*) from photo WHERE photo.id_photo_folder=photo_folder.id) as pocet FROM photo_folder WHERE id=".$intID;
      	if ($con->GetQueryNum($query_sub)>0){
	       $result_sub = $con->SelectQuery($query_sub);
	       $write_sub = $result_sub->fetch_array();
	       $intID=$write_sub['id'];
	       $intName=''.$write_sub['name'].' <a onclick="return !window.open(this.href);" href="../mod_photogallery/photogallery.php'.Odkaz.'&amp;filter2='.$write_sub["id"].'">'.$write_sub['name'].' ('.$write_sub['pocet'].')</a>';
	     }else{
         $intID="";
	       $intName="none";
       }
	    }else{
        $intID="";
	      $intName="none";
      }
        echo '<span id="image_folder_name'.$intIdBox.'">'.$intName.'</span>';
        echo'&nbsp;<a href="javascript:cancel_image_folder('.$intIdBox.')" title="Cancel"><img src="../inc/design/ico-delete.gif" class="ico" alt="Cancel"></a>&nbsp;|&nbsp;';
        echo'<a class="ico-list" href="javascript:show_image_folder_window('.$intIdBox.')" title="Set picture folder">search folder</a>';
        echo'<div id="image_folder_livesearch_window'.$intIdBox.'" class="livesearch_image_folder_window">';
          echo '<div style="float:left"><span class="label label-03">Type name of folder</span></div>';
          echo '<div style="float:right"><a href="javascript:close_image_folder_window('.$intIdBox.')" class="ico-delete">close</a></div>';
          echo '
          <div style="clear:both; position:relative; padding:10px 0px 10px 0px; z-index:90">
            <input type="text" name="filter_box" id="filter_image_folder'.$intIdBox.'" autocomplete="off" value="" class="input-text" style="width:140px;" onkeyup="send_image_folder_livesearch_data(this.value,'.$intIdBox.')" />
            <div id="image_folder_livesearch'.$intIdBox.'" class="livesearch_image"></div>
          </div>
         ';
        echo '</div>';
      echo '</div>';
      echo '<input type="hidden" id="image_folder_input'.$intIdBox.'" name="'.$strInputName.'" value="'.$intID.'" /><br />';
      }


//funkce na vypis players boxiku
function show_player_select_box ($con,$intIdBox,$intID,$strInputName){
	    
	    echo '
      <div style="position:relative;">
	    <span class="label label-04">Player name</span>
      ';
	    
      if (!empty($intID)){  
      	$query_sub="SELECT * FROM players WHERE id=".$intID;
      	if ($con->GetQueryNum($query_sub)>0){
	       $result_sub = $con->SelectQuery($query_sub);
	       $write_sub = $result_sub->fetch_array();
	       $intID=$write_sub['id'];
	       $intName=''.$write_sub['name'].' '.$write_sub['surname'].' '.$write_sub['nationality'].'';
	     }else{
         $intID="";
	       $intName="none";
       }
	    }else{
        $intID="";
	      $intName="none";
      }
        echo '<span id="player_name'.$intIdBox.'">'.$intName.'</span>';
        echo'&nbsp;<a href="javascript:cancel_player('.$intIdBox.')" title="Cancel"><img src="../inc/design/ico-delete.gif" class="ico" alt="Cancel"></a>&nbsp;|&nbsp;';
        echo'<a class="ico-list" href="javascript:show_player_window('.$intIdBox.')" title="Set player">search player</a>';
        echo'<div id="player_livesearch_window'.$intIdBox.'" class="livesearch_player_window">';
          echo '<div style="float:left"><span class="label label-03">Type name of player</span></div>';
          echo '<div style="float:right"><a href="javascript:close_player_window('.$intIdBox.')" class="ico-delete">close</a></div>';
          echo '
          <div style="clear:both; position:relative; padding:10px 0px 10px 0px; z-index:90">
            <input type="text" name="filter_box" id="filter_player'.$intIdBox.'" autocomplete="off" value="" class="input-text" style="width:140px;" onkeyup="send_player_livesearch_data(this.value,'.$intIdBox.')" />
            <div id="player_livesearch'.$intIdBox.'" class="livesearch_player"></div>
          </div>
         ';
        echo '</div>';
      echo '</div>';
      echo '<input type="hidden" id="player_input'.$intIdBox.'" name="'.$strInputName.'" value="'.$intID.'" /><br />';
      }

//funkce na vypis game boxiku
function show_game_select_box ($con,$intIdBox,$intID,$strInputName){
	    global $games; 
	    echo '
      <div style="position:relative;">
	    <span class="label label-04">Game date</span>
      ';
	    
      if (!empty($intID)){  
      	$query_sub="SELECT id,id_club_home,id_club_visiting,date,home_score,visiting_score FROM games WHERE id=".$intID;
      	if ($con->GetQueryNum($query_sub)>0){
	       $result_sub = $con->SelectQuery($query_sub);
	       $write_sub = $result_sub->fetch_array();
	       
         $intID=$write_sub['id'];
	       $strDate=date("d.m.Y",strtotime($write_sub['date']));
	       $strHome=$games->GetActualClubName($write_sub['id_club_home'],ActSeason);
	       $strVisiting=$games->GetActualClubName($write_sub['id_club_visiting'],ActSeason);
	       $strScore=$games->GetScore($write_sub['id'],1);
	       $intName=''.$strDate.' '.$strHome.' vs. '.$strVisiting.' ('.$strScore.')';
	       
	       
	     }else{
         $intID="";
	       $intName="none";
       }
	    }else{
        $intID="";
	      $intName="none";
      }
        echo '<span id="game_name'.$intIdBox.'">'.$intName.'</span>';
        echo'&nbsp;<a href="javascript:cancel_game('.$intIdBox.')" title="Cancel"><img src="../inc/design/ico-delete.gif" class="ico" alt="Cancel"></a>&nbsp;|&nbsp;';
        echo'<a class="ico-list" href="javascript:show_game_window('.$intIdBox.')" title="Set game">search game</a>';
        echo'<div id="game_livesearch_window'.$intIdBox.'" class="livesearch_game_window">';
          echo '<div style="float:left"><span class="label label-03">Type game date</span></div>';
          echo '<div style="float:right"><a href="javascript:close_game_window('.$intIdBox.')" class="ico-delete">close</a></div>';
          echo '
          <div style="clear:both; position:relative; padding:10px 0px 10px 0px; z-index:90">
            <input type="text" name="filter_box" id="filter_game'.$intIdBox.'" autocomplete="off" value="" class="input-text" style="width:140px;" onkeyup="send_game_livesearch_data(this.value,'.$intIdBox.')" />
            <div id="game_livesearch'.$intIdBox.'" class="livesearch_game"></div>
          </div>
         ';
        echo '</div>';
      echo '</div>';
      echo '<input type="hidden" id="game_input'.$intIdBox.'" name="'.$strInputName.'" value="'.$intID.'" /><br />';
      }



//funkce na vypis league boxiku
function show_league_select_box ($con,$intIdBox,$intID,$strInputName,$sesid,$idPageRight){
	    
	    echo '
      <div style="position:relative;">
	    <span class="label label-04">League name</span>
      ';
	    
      if (!empty($intID)){  
      	$query_sub="SELECT * FROM leagues WHERE id=".$intID;
      	if ($con->GetQueryNum($query_sub)>0){
	       $result_sub = $con->SelectQuery($query_sub);
	       $write_sub = $result_sub->fetch_array();
	       $intID=$write_sub['id'];
	       $intName=''.$write_sub['name'].'';
	     }else{
         $intID="";
	       $intName="none";
       }
	    }else{
        $intID="";
	      $intName="none";
      }
        echo '<span id="league_name'.$intIdBox.'">'.$intName.'</span>';
        echo'&nbsp;<a href="javascript:cancel_league('.$intIdBox.')" title="Cancel"><img src="../inc/design/ico-delete.gif" class="ico" alt="Cancel"></a>&nbsp;|&nbsp;';
        echo'<a class="ico-list" href="javascript:show_league_window('.$intIdBox.')" title="Set league">search league</a>';
        echo'<div id="league_livesearch_window'.$intIdBox.'" class="livesearch_league_window">';
          echo '<div style="float:left"><span class="label label-03">Type name of league</span></div>';
          echo '<div style="float:right"><a href="javascript:close_league_window('.$intIdBox.')" class="ico-delete">close</a></div>';
          echo '
          <div style="clear:both; position:relative; padding:10px 0px 10px 0px; z-index:90">
            <input type="text" name="filter_box" id="filter_league'.$intIdBox.'" autocomplete="off" value="" class="input-text" style="width:140px;" onkeyup="send_league_livesearch_data(this.value,'.$intIdBox.',\''.$sesid.'\','.$idPageRight.')" />
            <div id="league_livesearch'.$intIdBox.'" class="livesearch_league"></div>
          </div>
         ';
        echo '</div>';
      echo '</div>';
      echo '<input type="hidden" id="league_input'.$intIdBox.'" name="'.$strInputName.'" value="'.$intID.'" /><br />';
      }
      
//funkce na vypis club boxiku
function show_club_select_box ($con,$intIdBox,$intID,$strInputName){
	    
	    echo '
      <div style="position:relative;">
	    <span class="label label-04">Club name</span>
      ';
	    
      if (!empty($intID)){  
      	$query_sub="SELECT * FROM clubs WHERE id=".$intID;
      	if ($con->GetQueryNum($query_sub)>0){
	       $result_sub = $con->SelectQuery($query_sub);
	       $write_sub = $result_sub->fetch_array();
	       $intID=$write_sub['id'];
	       $intName=''.$write_sub['name'].'';
	     }else{
         $intID="";
	       $intName="none";
       }
	    }else{
        $intID="";
	      $intName="none";
      }
        echo '<span id="club_name'.$intIdBox.'">'.$intName.'</span>';
        echo'&nbsp;<a href="javascript:cancel_club('.$intIdBox.')" title="Cancel"><img src="../inc/design/ico-delete.gif" class="ico" alt="Cancel"></a>&nbsp;|&nbsp;';
        echo'<a class="ico-list" href="javascript:show_club_window('.$intIdBox.')" title="Set club">search club</a>';
        echo'<div id="club_livesearch_window'.$intIdBox.'" class="livesearch_club_window">';
          echo '<div style="float:left"><span class="label label-03">Type name of club</span></div>';
          echo '<div style="float:right"><a href="javascript:close_club_window('.$intIdBox.')" class="ico-delete">close</a></div>';
          echo '
          <div style="clear:both; position:relative; padding:10px 0px 10px 0px; z-index:90">
            <input type="text" name="filter_box" id="filter_club'.$intIdBox.'" autocomplete="off" value="" class="input-text" style="width:140px;" onkeyup="send_club_livesearch_data(this.value,'.$intIdBox.')" />
            <div id="club_livesearch'.$intIdBox.'" class="livesearch_club"></div>
          </div>
         ';
        echo '</div>';
      echo '</div>';
      echo '<input type="hidden" id="club_input'.$intIdBox.'" name="'.$strInputName.'" value="'.$intID.'" /><br />';
      }
      

//funkce na vypis ARENA boxiku
function show_arena_select_box ($con,$intIdBox,$intID,$strInputName){
	    
	    echo '
      <div style="position:relative;">
	    <span class="label label-04">Arena name</span>
      ';
	    
      if (!empty($intID)){  
      	$query_sub="SELECT * FROM arenas WHERE id=".$intID;
      	if ($con->GetQueryNum($query_sub)>0){
	       $result_sub = $con->SelectQuery($query_sub);
	       $write_sub = $result_sub->fetch_array();
	       $intID=$write_sub['id'];
	       $intName=''.$write_sub['name'].'';
	     }else{
         $intID="";
	       $intName="none";
       }
	    }else{
        $intID="";
	      $intName="none";
      }
        echo '<span id="arena_name'.$intIdBox.'">'.$intName.'</span>';
        echo'&nbsp;<a href="javascript:cancel_arena('.$intIdBox.')" title="Cancel"><img src="../inc/design/ico-delete.gif" class="ico" alt="Cancel"></a>&nbsp;|&nbsp;';
        echo'<a class="ico-list" href="javascript:show_arena_window('.$intIdBox.')" title="Set arena">search arena</a>';
        echo'<div id="arena_livesearch_window'.$intIdBox.'" class="livesearch_arena_window">';
          echo '<div style="float:left"><span class="label label-03">Type name of arena</span></div>';
          echo '<div style="float:right"><a href="javascript:close_arena_window('.$intIdBox.')" class="ico-delete">close</a></div>';
          echo '
          <div style="clear:both; position:relative; padding:10px 0px 10px 0px; z-index:90">
            <input type="text" name="filter_box" id="filter_arena'.$intIdBox.'" autocomplete="off" value="" class="input-text" style="width:140px;" onkeyup="send_arena_livesearch_data(this.value,'.$intIdBox.')" />
            <div id="arena_livesearch'.$intIdBox.'" class="livesearch_arena"></div>
          </div>
         ';
        echo '</div>';
      echo '</div>';
      echo '<input type="hidden" id="arena_input'.$intIdBox.'" name="'.$strInputName.'" value="'.$intID.'" /><br />';
      }
      

//funkce na vypis CLUB boxiku PRO STATISTIKY
function show_club_stats_select_box ($con,$games,$intIdBox,$intID,$intIdSeason,$strInputName){
	    echo '
      <div style="position:relative;">
	    ';
	    
      if (!empty($intID)){  
      	$query_sub="SELECT id FROM clubs WHERE id=".$intID;
      	if ($con->GetQueryNum($query_sub)>0){
	       $result_sub = $con->SelectQuery($query_sub);
	       $write_sub = $result_sub->fetch_array();
	       $intID=$write_sub['id'];
	       $intName= '<a class="ico-show" href="../mod_players/players_stats_club.php'.Odkaz.'&amp;id='.$intID.'&amp;id_season='.$intIdSeason.'" title="Show CLUB stats">'.$games->GetActualClubName($write_sub['id'],$intIdSeason).'</a>';
	      
	     }else{
         $intID="";
	       $intName="none";
       }
	    }else{
        $intID="";
	      $intName="none";
      }
      
      
        echo '<span id="club_stats_name'.$intIdBox.'">'.$intName.'</span>';
        echo '&nbsp;&nbsp;<a href="javascript:show_club_stats_window('.$intIdBox.')" title="Edit club"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';        
        echo'<div id="club_stats_livesearch_window'.$intIdBox.'" class="livesearch_club_stats_window">';
          echo '<div style="float:left"><span class="label label-03">Type name of club</span></div>';
          echo '<div style="float:right"><a href="javascript:close_club_stats_window('.$intIdBox.')" class="ico-delete">close</a></div>';
          echo '
          <div style="clear:both; position:relative; padding:10px 0px 10px 0px; z-index:90">
            <input type="text" name="filter_box" id="filter_club_stats'.$intIdBox.'" autocomplete="off" value="" class="input-text" style="width:140px;" onkeyup="send_club_stats_livesearch_data(this.value,'.$intIdBox.','.$intIdSeason.')" />
            <div id="club_stats_livesearch'.$intIdBox.'" class="livesearch_club_stats"></div>
          </div>
         ';
        echo '</div>';
      echo '</div>';
      echo '<input type="hidden" id="club_stats_input'.$intIdBox.'" name="'.$strInputName.'" value="'.$intID.'" />';
      }
      
//funkce na vypis LEAGUE boxiku PRO STATISTIKY
function show_league_stats_select_box ($con,$games,$intIdBox,$intID,$intIdSeason,$strInputName,$strStageBox,$intStageBoxID){
      global $boolNoAnchor;
	    echo '
      <div style="position:relative;">
	    ';
	    
      if (!empty($intID)){  
      	$query_sub="SELECT id,youth_league,youth_league_id FROM leagues WHERE id=".$intID;
      	if ($con->GetQueryNum($query_sub)>0){
	       $result_sub = $con->SelectQuery($query_sub);
	       $write_sub = $result_sub->fetch_array();
	       $intID=$write_sub['id'];
	       $intName= $games->GetActualLeagueName($write_sub['id'],$intIdSeason);
	       
	       if ($write_sub['youth_league']==1){
    
          $query_sub2="SELECT name FROM leagues_youth_list WHERE id=".$write_sub['youth_league_id'];
          $result_sub2 = $con->SelectQuery($query_sub2);
          $write_sub2 = $result_sub2->fetch_array();
          $strYouth_league=" (".$write_sub2['name'].")";
          }else{
            $strYouth_league="";
          }
	     }else{
         $intID="";
	       $intName="none";
	       $strYouth_league="";
       }
	    }else{
        $strYouth_league="";
        $intID="";
	      $intName="none";
      }
      
      
        echo '<span id="league_stats_name'.$intIdBox.'">';
        if(!$boolNoAnchor) echo '<a class="ico-show" href="../mod_players/players_stats_league.php'.Odkaz.'&amp;id='.$intID.'&amp;id_season='.$intIdSeason.'" title="Show LEAGUE stats">';
        echo ''.$intName.$strYouth_league.'';
        if(!$boolNoAnchor) echo '</a>';
        echo '</span>';
        echo '&nbsp;&nbsp;<a href="javascript:show_league_stats_window('.$intIdBox.')" title="Edit league"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';        
        echo'<div id="league_stats_livesearch_window'.$intIdBox.'" class="livesearch_league_stats_window">';
          echo '<div style="float:left"><span class="label label-03">Type name of league</span></div>';
          echo '<div style="float:right"><a href="javascript:close_league_stats_window('.$intIdBox.')" class="ico-delete">close</a></div>';
          echo '
          <div style="clear:both; position:relative; padding:10px 0px 10px 0px; z-index:90">
            <input type="text" name="filter_box" id="filter_league_stats'.$intIdBox.'" autocomplete="off" value="" class="input-text" style="width:140px;" onkeyup="send_league_stats_livesearch_data(this.value,'.$intIdBox.','.$intIdSeason.',\''.$strStageBox.'\','.$intStageBoxID.')" />
            <div id="league_stats_livesearch'.$intIdBox.'" class="livesearch_league_stats"></div>
          </div>
         ';
        echo '</div>';
      echo '</div>';
      echo '<input type="hidden" id="league_stats_input'.$intIdBox.'" name="'.$strInputName.'" value="'.$intID.'" />';
      }

//funkce na vypis PLAYER boxiku PRO STATISTIKY
function show_player_stats_select_box ($con,$games,$intIdBox,$intID,$strInputName){
	    echo '
      <div style="position:relative;">
	    ';
	    
      if (!empty($intID)){  
      	$query_sub="SELECT surname,name,id,nationality FROM players WHERE id=".$intID;
      	if ($con->GetQueryNum($query_sub)>0){
	       $result_sub = $con->SelectQuery($query_sub);
	       $write_sub = $result_sub->fetch_array();
	       $intID=$write_sub['id'];
	       $intName= '<a class="ico-show" href="../mod_players/players_stats.php'.Odkaz.'&amp;id='.$intID.'" title="Show PLAYER stats">'.$write_sub['surname'].' '.$write_sub['name'].' ('.$write_sub['nationality'].')</a>';
	      
	     }else{
         $intID="";
	       $intName="none";
       }
	    }else{
        $intID="";
	      $intName="none";
      }
      
      
        echo '<span id="players_stats_name'.$intIdBox.'">'.$intName.'</span>';
        echo '&nbsp;&nbsp;<a href="javascript:show_players_stats_window('.$intIdBox.')" title="Edit player"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';        
        echo'<div id="players_stats_livesearch_window'.$intIdBox.'" class="livesearch_players_stats_window">';
          echo '<div style="float:left"><span class="label label-03">Type name of player</span></div>';
          echo '<div style="float:right"><a href="javascript:close_players_stats_window('.$intIdBox.')" class="ico-delete">close</a></div>';
          echo '
          <div style="clear:both; position:relative; padding:10px 0px 10px 0px; z-index:90">
            <input type="text" name="filter" id="filter_players_stats'.$intIdBox.'" autocomplete="off" value="" class="input-text" style="width:140px;" onkeyup="send_players_stats_livesearch_data(this.value,'.$intIdBox.')" />
            <div id="players_stats_livesearch'.$intIdBox.'" class="livesearch_players_stats"></div>
          </div>
         ';
        echo '</div>';
      echo '</div>';
      echo '<input type="hidden" id="players_stats_input'.$intIdBox.'" name="'.$strInputName.'" value="'.$intID.'" />';
      }
      
      
//funkce pro automaticke generovani tabulky

//create new automatic table
  function add_club($id_standings,$id_club){
      global $con;
      $intCountClub=$con->GetSQLSingleResult("SELECT count(*) as item FROM standings_teams WHERE id_table_type=1 AND id_standings=".$id_standings." AND id_club=".$id_club);
      
      if ($intCountClub==0){  
        $query="INSERT INTO standings_teams (id_standings,id_club,id_table_type) VALUES (".$id_standings.",".$id_club.",1)";
        $con->RunQuery($query);
      }
  }



function generate_auto_standings($id_standings){
  global $con;
  $query="SELECT * FROM standings WHERE id=".$id_standings;
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
	$id_season=$write['id_season'];
	$id_league=$write['id_league'];
	$id_stage=$write['id_stage'];
	$p_wins=$write['p_wins'];
	$p_wins_pp=$write['p_wins_pp'];
	$p_losts_pp=$write['p_losts_pp'];
	$p_draws=$write['p_draws'];
	
  
  $sqlWhere="id_season=".$id_season." AND id_league=".$id_league." AND id_stage=".$id_stage." AND games_status<>4 AND (home_score IS NOT NULL AND visiting_score IS NOT NULL)";  
  $query="SELECT id_club_home,id_club_visiting FROM games WHERE ".$sqlWhere;
  $result = $con->SelectQuery($query);
  if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	  {
	     add_club($id_standings,$write['id_club_home']);
	     add_club($id_standings,$write['id_club_visiting']);
	  }
  }
   
  $query="SELECT id,id_club FROM standings_teams WHERE id_table_type=1 AND id_standings=".$id_standings;
  $result = $con->SelectQuery($query);
    while($write = $result->fetch_array())
	  {
	   
	   $intPoints=0;
	   
	   $intGames=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE (id_club_home=".$write['id_club']." OR id_club_visiting=".$write['id_club'].") AND ".$sqlWhere);
	   
     $intWins1=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE id_club_home=".$write['id_club']." AND home_score>visiting_score AND (games_status=1 OR games_status=5) AND ".$sqlWhere);
     $intWins2=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE id_club_visiting=".$write['id_club']." AND home_score<visiting_score AND (games_status=1 OR games_status=5) AND ".$sqlWhere);
     $intWinsRegular=$intWins1+$intWins2;
     
     $intWins1=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE id_club_home=".$write['id_club']." AND home_score>visiting_score AND (games_status=2 OR games_status=3) AND ".$sqlWhere);
     $intWins2=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE id_club_visiting=".$write['id_club']." AND home_score<visiting_score AND (games_status=2 OR games_status=3) AND ".$sqlWhere);
     $intWinsPP=$intWins1+$intWins2;
     
     $intLosts1=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE id_club_home=".$write['id_club']." AND home_score<visiting_score AND (games_status=1 OR games_status=5) AND ".$sqlWhere);
     $intLosts2=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE id_club_visiting=".$write['id_club']." AND home_score>visiting_score AND (games_status=1 OR games_status=5) AND ".$sqlWhere);
     $intLostsRegular=$intLosts1+$intLosts2;
     
     $intLosts1=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE id_club_home=".$write['id_club']." AND home_score<visiting_score AND (games_status=2 OR games_status=3) AND ".$sqlWhere);
     $intLosts2=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE id_club_visiting=".$write['id_club']." AND home_score>visiting_score AND (games_status=2 OR games_status=3) AND ".$sqlWhere);
     $intLostsPP=$intLosts1+$intLosts2;
     
     $intDraws1=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE id_club_home=".$write['id_club']." AND home_score=visiting_score AND (games_status=1 OR games_status=5) AND ".$sqlWhere);
     $intDraws2=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE id_club_visiting=".$write['id_club']." AND home_score=visiting_score AND (games_status=1 OR games_status=5) AND ".$sqlWhere);
     $intDraws=$intDraws1+$intDraws2;
     
     $intScoreHome=$con->GetSQLSingleResult("SELECT sum(home_score) as item FROM games WHERE id_club_home=".$write['id_club']." AND ".$sqlWhere);
     $intScoreVisiting=$con->GetSQLSingleResult("SELECT sum(visiting_score) as item FROM games WHERE id_club_visiting=".$write['id_club']." AND ".$sqlWhere);
     $intScore1=$intScoreHome+$intScoreVisiting;
     
     $intScoreHome=$con->GetSQLSingleResult("SELECT sum(visiting_score) as item FROM games WHERE id_club_home=".$write['id_club']." AND ".$sqlWhere);
     $intScoreVisiting=$con->GetSQLSingleResult("SELECT sum(home_score) as item FROM games WHERE id_club_visiting=".$write['id_club']." AND ".$sqlWhere);
     $intScore2=$intScoreHome+$intScoreVisiting;
     
     $intPoints=$intPoints+($intWinsRegular*$p_wins);
     $intPoints=$intPoints+($intWinsPP*$p_wins_pp);
     $intPoints=$intPoints+($intLostsPP*$p_losts_pp);
     $intPoints=$intPoints+($intDraws*$p_draws);
     
     //echo $write['id_club'].': '.$intWinsRegular.' '.$intWinsPP.' '.$intLostsRegular.' '.$intLostsPP.' '.$intDraws.'<br />';
     
     $query="UPDATE standings_teams SET games=".$intGames.",wins=".$intWinsRegular.",wins_ot=".$intWinsPP.",losts=".$intLostsRegular.",losts_ot=".$intLostsPP.",draws=".$intDraws.",score1=".$intScore1.",score2=".$intScore2.",points=".$intPoints." WHERE id=".$write['id'];
     $con->RunQuery($query);
      
	     
	 }           
  
   $query="UPDATE standings SET last_update=NOW() WHERE id=".$id_standings;
   $con->RunQuery($query);
}

?>