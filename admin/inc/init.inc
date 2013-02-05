<?php
$users = new users($con);
$users->setSesid($_GET['sesid'],$_POST['sesid']);
$users->checkUser();

Define(Odkaz, "?sesid=".$users->getSesid());
Define(OdkazForm, "<input type=\"hidden\" name=\"sesid\" value=\"".$users->getSesid()."\" />");

?>