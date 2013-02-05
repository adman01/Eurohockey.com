<?php
 function delete_directory($dirname) {
    if (is_dir($dirname))
       $dir_handle = opendir($dirname);
    if (!$dir_handle)
       return false;
    while($file = readdir($dir_handle)) {
       if ($file != "." && $file != "..") {
          if (!is_dir($dirname."/".$file))
             unlink($dirname."/".$file);
          else
             delete_directory($dirname.'/'.$file);    
       }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
 }

require_once("inc/global.inc");

$users = new users($con);

if ($_GET["action"]=="logout"){
  //odhlaseni
  $users->logoutUser($_GET["sesid"]);
}
else{
  //prihlaseni
  $user=$_POST["username"];
  $password=$_POST["heslo"];
  
  $input = new Input_filter();
  $user=$input->valid_text($user,true,true);
  $password=$input->valid_text($password,true,true);
  if ($input->one_word($user)==true AND $input->one_word($password)==true){
  
    
    //promazani vsech adresaru s temp files
    $handle=opendir("inc/uploadify/uploads_temp/photos/");
    $i=0; 
    while (false!==($file = readdir($handle))) 
    { 
      if ($file != "." && $file != "..") 
      {
        $intCountActiveUsers=$con->GetSQLSingleResult("SELECT count(*) as item FROM users_session WHERE session_id='".$file."'");
        if ($intCountActiveUsers==0)delete_directory("inc/uploadify/uploads_temp/photos/".$file);
      }
    } 
  
  
    $users->setUser($user);
    $users->setPassword($password);
    $users->loginUser();
  }
  else{
    header("Location: index.php?error=1");
  }
}
?>