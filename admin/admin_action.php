<?php
require_once("inc/global.inc");
require_once("inc/init.inc");
$input = new Input_filter();

if ($_POST['action']=="add"){
    $id_user=$users->id_user;
    $message=$_POST['message'];
    $message=$input->valid_text($message,true,false);
    $to_id_user=$_POST['to_id_user'];
    $to_id_user=$input->check_number($to_id_user);
    
    //if (empty($message))
    
    $query="INSERT into messages (id_user,to_id_user,date_time,message) VALUES (".$id_user.",".$to_id_user.",NOW(),'".$message."')";
    $con->RunQuery($query); 
    header("Location: admin.php".Odkaz."&message=1");
 }
       
if ($_GET['action']=="delete"){
        $id=$_GET['id'];
        $query="DELETE FROM messages WHERE id=".$id;
        $con->RunQuery($query);
        header("Location: admin.php".Odkaz."&list_number=".$_GET['list_number']."&message=2");
}
?>