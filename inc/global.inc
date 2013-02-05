<?php

if ($_SERVER["HTTP_HOST"]=="eurohockey.esports.cz") {header("Location: http://www.eurohockey.com".$_SERVER["REQUEST_URI"]);}

Define(IncludeAdminPath, $_SERVER['DOCUMENT_ROOT']."/"); //cesta k includum pro admin

require_once(IncludeAdminPath."/inc/config.inc");

require_once(IncludeAdminPath."/admin/inc/class/class_HTML_code.php");

require_once(IncludeAdminPath."/admin/inc/class/class_database.php");

require_once(IncludeAdminPath."/admin/inc/class/class_users.php");

require_once(IncludeAdminPath."/admin/inc/class/class_inputfilter.php");

require_once(IncludeAdminPath."/admin/inc/class/class_games.php");

require_once(IncludeAdminPath."/admin/inc/class/class_listing.php");

require_once(IncludeAdminPath."/admin/inc/class/class_files.php");
require_once(IncludeAdminPath."/inc/phpmailer/class.phpmailer.php");



$con = new database(MySQLServer, MySQLDatabase, MySQLUser, MySQLPassword);

$con->setCharset(MySQLCharset);



date_default_timezone_set(SetTimezone);



function write_select($id1,$id2){

  if ($id1==$id2) return ' selected="selected"'; else return '';

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



//----------------- get ID from URL

function get_ID_from_url($id){

  $id=(substr($id,0,stripos($id,"-")))-123;

  return $id;

}



function get_photo_name($id_img){

  global $con;

  $query_path="SELECT file_name from photo where id=".$id_img;

  $result_path = $con->SelectQuery($query_path);

  $write_path = $result_path->fetch_array();

  $imgPath=$write_path['file_name'];

  

  return $imgPath;

}



//----------------- show flag

function get_flag($country,$width,$height){

  

  if (!empty($country) and !empty($width) and !empty($height)){

    $strFlagLocation="img/logos/flags/".strtolower($country).".gif";

    if (file_exists($strFlagLocation)){

      $strFlagImage='<img src="/flag/'.$width.'-'.$height.'-1-'.$country.'.gif" class="flag" title="'.strtoupper($country).'" />';

    }

  }

  

  return $strFlagImage;

  

}



function curPageURL() {

 $pageURL = 'http';

 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

 $pageURL .= "://";

 if ($_SERVER["SERVER_PORT"] != "80") {

  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];

 } else {

  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

 }

 return $pageURL;

}



function get_static_text($id){

   global $con;

   $query="SELECT text FROM static_texts WHERE id=".$id;  

   if ($con->GetQueryNum($query)>0){

          $result = $con->SelectQuery($query);

          $write = $result->fetch_array();

          return $write['text'];

    }

}



function get_substr($str, $length, $minword = 3)

{

    $sub = '';

    $len = 0;

   

    foreach (explode(' ', $str) as $word)

    {

        $part = (($sub != '') ? ' ' : '') . $word;

        $sub .= $part;

        $len += strlen($part);

       

        if (strlen($word) > $minword && strlen($sub) >= $length)

        {

            break;

        }

    }

   

    return $sub . (($len < strlen($str)) ? '...' : '');

}





function translit($string,$smer = 1) {


$rus_alp = array ('й','ц','у','к','е','н','г',' ш','щ','з','х','и','ф','ы','в','а','п','р','о','л','д','ж','э','я',
'ч','с','м','и','т','ь','б','ю','Й','Ц','У','К','Е','Н','Г','Ш','Щ','З','Х','И','Ф','Ы','В','А','П','Р','О','Л', 'Д','Ж','Э','Я','Ч','С','М','И','Т','Ь','Б','Ю');

$eng_alp = array ('y','c','u','k','e','n','g','sh','sh','z',
        'kh','i','f','i','v','a','p','r','o','l','d','zh','ai','ya',
        'ch','s','m','i','t',"i",'b','yu','J','C','U','K','E','N',
        'G','Sh','Sh','Z','Kh','i','F','Y','V','A','P','R','O','L',
        'D','Zh','E','Ya','Sh','S','M','I','T','i','B','Yu');


      for ($i = 0; $i <count($rus_alp); $i++){

        if ($smer == 1) {

            //$smer = 1 -> z azbuky do latinky

          $string = ereg_replace($rus_alp[$i], $eng_alp[$i], $string);

        } elseif ($smer == 2) {

            //$smer = 2 -> z latinky do azbuky

          $string = ereg_replace($eng_alp[$i], $rus_alp[$i], $string);

        }

      }

      return $string;

}

function socials($type) {
  echo'
  <div id="socials">
      <div id="goggle_plus"><g:plusone></g:plusone></div>                                                                                                           
      <div id="facebok"><fb:like href="'.curPageURL().'" send="true" width="360" show_faces="false" colorscheme="light" font="arial"></fb:like></div>
  </div>
  ';
}  

function write_checked($id1,$id2){
  if ($id1==$id2) return ' checked="checked"'; else return '';
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


function check_login($boolRedirect) {
  global $web_users;
  $boolIsLoged=$web_users->is_loged();
  if (!$boolIsLoged) {
    $strUserArray="";
    if ($boolRedirect) header("Location: /user-login.html?message=4");
  }else{
    $strUserArray=$web_users->get_users_data($web_users->getActualUserID());
  }
  return $strUserArray; 
}     

function show_sponsorship ($id,$StrName,$intIdType){

            global $con,$input;

            if ($_GET['preview']==1){
              echo '<div id="sponsorship_show_header">This profile is sponsored by:</div>
              <div id="sponsorship_show">';
              $id_ads=$input->check_number($_GET['id_ads']);
              $query="SELECT * from sponsorship_ads WHERE id=".$id_ads."";
              if ($con->GetQueryNum($query)>0){
                $result = $con->SelectQuery($query);
                $write = $result->fetch_array();
                $strAds="";
                      switch ($write['id_type']) {
                        case 2:
                          if (!empty($write['id_image'])){
                            $strImagePath=PhotoFolder."/sponsorship/sponsorship_".$write['id'].".jpg";
                            if (file_exists($strImagePath)) {
                               $strAds= '<img src="/'.$strImagePath.'" alt="'.$write['name'].'" />';
                            }
                          }
                        break;
                        case 1:
                          $strAds=$write['html_text'];
                        break;
                      }
                    if (!empty($write['url'])){ echo '<a onclick="return !window.open(this.href);" href="'.$input->check_external_link($write['url']).'">';}
                    echo $strAds;
                    if (!empty($write['url'])){ echo '</a>';}
              }
              echo '</div>';

            }else{

              $query="SELECT * from sponsorship WHERE id_type=".$intIdType." AND id_item=".$id." AND ( (date_expire>=NOW() AND id_status=2) OR (id_status=1) )";
              //echo $query;
              if ($con->GetQueryNum($query)>0){

              $query="SELECT sponsorship_ads.* from sponsorship INNER JOIN sponsorship_ads ON sponsorship.id_ads=sponsorship_ads.id WHERE sponsorship.id_type=".$intIdType." AND sponsorship.id_item=".$id." AND ( (sponsorship.date_expire>=NOW() AND sponsorship.id_status=2))";
              if ($con->GetQueryNum($query)>0){
                $result = $con->SelectQuery($query);
                $write = $result->fetch_array();
                $strAds="";
                 echo '<div id="sponsorship_show_header">This profile is sponsored by:</div>';
                 echo '<div id="sponsorship_show">';
                      switch ($write['id_type']) {
                        case 2:
                          if (!empty($write['id_image'])){
                            $strImagePath=PhotoFolder."/sponsorship/sponsorship_".$write['id'].".jpg";
                            if (file_exists($strImagePath)) {
                               $strAds= '<img src="/'.$strImagePath.'" alt="'.$write['name'].'" />';
                            }
                          }
                        break;
                        case 1:
                          $strAds=$write['html_text'];
                        break;
                      }
                    if (!empty($write['url'])){ echo '<a onclick="return !window.open(this.href);" href="'.$input->check_external_link($write['url']).'">';}
                    echo $strAds;
                    if (!empty($write['url'])){ echo '</a>';}
                 echo '</div>';                    
              }
              
            }else{
              echo '<div id="sponsorship_show">';
              echo '<div class="center bold">Want your name or logo seen here? <a href="/sponsorship/'.get_url_text($StrName,$id).'?id_type='.$intIdType.'" title="Sponsor this profile">Sponsor this profile</a>.</div>';
              echo '</div>';                    
            }

            }
         

         }


session_start();

$input = new Input_filter();
$games = new games($con);
require_once(IncludeAdminPath."/inc/class/class_web_users.php");
$web_users = new web_users($con);
$strUserArray=check_login(0);

?>