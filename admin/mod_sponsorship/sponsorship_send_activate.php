<?php
function send_activate_email ($strEmail,$arrayItems){
          
          global $con;
          $games = new games($con);
          $strMailTo = $strEmail;
          //$strMailTo="martin.formanek@gmail.com";
          $strMailHeader = "From: ".strEmailFrom;
          $strMailSubject = "Your sponsorships has been activated";

          foreach ($arrayItems as $key => $value) {
                  switch ($value['id_type']) {
                  case 1:
                    $ItemName=$games->GetActualLeagueName($value['id_item'],ActSeason);
                    $strTypeName="league";
                  break;
                  case 2:
                    $ItemName=$games->GetActualClubName($value['id_item'],ActSeason);
                    $strTypeName="club";
                  break;
                  case 3:
                    $ItemName=$con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$value['id_item']);
                    $strTypeName="player";
                  break;
                  }
$strEmailText.="
-".$ItemName." (".$strTypeName.")";
}


$strMailContents = "Dear customer,

your sponsorships has been activated:
";
$strMailContents.= $strEmailText;

$strMailContents.= "

more details: http://".strWWWurl."/sponsorship.html

-------------------------------------

Thank you for your order!
The Eurohockey.com Team
";
//echo $strMailContents;
sendEmail(strEmailFrom,"Eurohockey.com",strEmailFrom,"Eurohockey.com",$strMailTo,$strMailSubject,$strMailContents);
  
}    
?>