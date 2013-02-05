<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");
$strHeaderKeywords='fan forum, list';
$strHeaderDescription='Fan forum';

if ($_COOKIE['eurohockey_permanent']=="1"){
 
    $username=$_COOKIE['eurohockey_permanent_id'];
    $username=$input->check_number($username);
    $password=$_COOKIE['eurohockey_permanent_login'];
    $password=$input->valid_text($password,true,true);
    if (!empty($username) and !empty($password) and $input->one_word($username)==true){
        
      $query="SELECT id FROM web_users WHERE id=".$username." AND md5(email)='".$password."' AND id_status=1";
      if ($con->GetQueryNum($query)==1){
           $result = $con->SelectQuery($query);
           $write = $result->fetch_array();
           $_SESSION['login_web_user']=true;
           $_SESSION['login_web_user_id']=$write['id'];
      }
    }
}
            
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("admin/inc/js/jquery.validate.js");
?>
<script type="text/javascript">
function insert_smile(type) {
	document.getElementById("text_forum").value=document.getElementById("text_forum").value+""+type+" ";
	document.getElementById("text_forum").value.focus();

}

$(document).ready(function(){
  
  
  jQuery.validator.messages.required = "";
  
  $("#form-01").validate({
		invalidHandler: function(e, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				var message = errors == 1
					? 'You missed <b>1 field</b>. It has been highlighted below'
					: 'You missed <b>' + errors + ' fields</b>..  They have been highlighted below';
				$("div.error span").html(message);
				$("div.error").show();
			} else {
				$("div.error").hide();
			}
		},
		onkeyup: false,


	});
  
});
</script>
<?php
echo $head->setTitle($strHeaderDescription." - ".strProjectName);
echo $head->setEndHead();
?>
<body>

<div id="ads_top"><?php show_ads("top_main"); ?></div>
<div id="layout" class="longer">
  
  <?php require_once("inc/bar_login.inc"); ?>
  <div id="logo"><?php require_once("inc/logo.inc"); ?></div>
  <div id="menu"><?php require_once("inc/menu.inc"); ?></div>
  <div id="main">
      
      <div id="text">
         <!-- main text -->
         
         <h1>Fan forum</h1>

        <div id="forum">
          
         
            <?php
            
            if ($boolForumDynamic==false){
            
               echo '<div class="box normal center bold">You can discuss about world of ice hockey on <a href="http://forums.internationalhockey.net/">http://forums.internationalhockey.net</a></div>';
            }
            
            else{
            
            echo ' <div class="box normal">';
            
            if ($_SESSION['login_web_user']==true){
              
              $query="SELECT id,username FROM web_users WHERE id=".$_SESSION['login_web_user_id']." AND  id_status=1";
              if ($con->GetQueryNum($query)==1){
                $result = $con->SelectQuery($query);
                $write = $result->fetch_array();
                
                $id_user=$write['id'];
                $username=$write['username'];
              }
              echo '<a href="/user-update.html" class="ico_reg" title="Update user profile">'.$username.'</a> | <a href="/forum_action.php?action=logout" title="Log out">log out</a>';
            }else {
              echo '<span style="color:#A90D0E">User is not loged</span> | <a href="/login.html" class="ico_log_in" title="Log in">Log in</a> or <a class="ico_reg" href="registration.html" title="Create new account">create new account</a>';
            }
            ?>
          </div>
          
          <?php
            if ($_SESSION['login_web_user']==true){
            
            echo'
            <br />
              <form action="forum_action.php" method="post" id="form-01">
              <fieldset>
                <legend>Add new post to the fan forum</legend>
                <div id="forum_form">
                  <div id="smiles">
                    <a href="javascript:insert_smile(\'*1*\');" title="Insert smile"><img src="/img/smiles/01.png" alt="" /></a>
                    <a href="javascript:insert_smile(\'*2*\');" title="Insert smile"><img src="/img/smiles/02.png" alt="" /></a>
                    <a href="javascript:insert_smile(\'*3*\');" title="Insert smile"><img src="/img/smiles/03.png" alt="" /></a>
                    <a href="javascript:insert_smile(\'*4*\');" title="Insert smile"><img src="/img/smiles/04.png" alt="" /></a>
                    <a href="javascript:insert_smile(\'*5*\');" title="Insert smile"><img src="/img/smiles/05.png" alt="" /></a>
                    <a href="javascript:insert_smile(\'*6*\');" title="Insert smile"><img src="/img/smiles/06.png" alt="" /></a>
                    <a href="javascript:insert_smile(\'*7*\');" title="Insert smile"><img src="/img/smiles/07.png" alt="" /></a>
                    <a href="javascript:insert_smile(\'*8*\');" title="Insert smile"><img src="/img/smiles/08.png" alt="" /></a>
                    <a href="javascript:insert_smile(\'*9*\');" title="Insert smile"><img src="/img/smiles/09.png" alt="" /></a>
                    <a href="javascript:insert_smile(\'*10*\');" title="Insert smile"><img src="/img/smiles/10.png" alt="" /></a>
                    <a href="javascript:insert_smile(\'*11*\');" title="Insert smile"><img src="/img/smiles/11.png" alt="" /></a>
                    <a href="javascript:insert_smile(\'*12*\');" title="Insert smile"><img src="/img/smiles/12.png" alt="" /></a>
                    <a href="javascript:insert_smile(\'*13*\');" title="Insert smile"><img src="/img/smiles/13.png" alt="" /></a>
                    <a href="javascript:insert_smile(\'*14*\');" title="Insert smile"><img src="/img/smiles/14.png" alt="" /></a>
                    <a href="javascript:insert_smile(\'*15*\');" title="Insert smile"><img src="/img/smiles/15.png" alt="" /></a>
                    <a href="javascript:insert_smile(\'*16*\');" title="Insert smile"><img src="/img/smiles/16.png" alt="" /></a>
                  </div>
                  ';  
                  $error=$_GET['error'];
          $error=$input->valid_text($error,true,true);
          if (!empty($error)){
            switch ($error){
              case 1:
                $msg="The post was not added, you must be logged in and enter any text of the post";
              break;
              
            }
          }
          if (empty($msg)) $strError="display:none;"; else  $strError="";
            echo'
            <div class="msg info error" style="'.$strError.'">
            <span>'.$msg.'</span>.
            </div>
                
                  <textarea cols="" rows="" name="text_forum" id="text_forum" class="required"></textarea>
                
                  <div class="center">
                    <input type="submit" value="Add new post" class="submit" />
                    <input type="hidden" name="action" value="add_post" />
                  </div>
                
                </div>
                
              </fieldset>
              </form>
              <div class="space">&nbsp;</div>
            ';
              
            }
            echo '<div class="box normal center bold">You can also discuss about world of ice hockey on <a href="http://forums.internationalhockey.net/">http://forums.internationalhockey.net</a></div>';
            
            echo '<div class="space">&nbsp;</div>';
            
            $query="SELECT * FROM forum WHERE show_item=1 ORDER by date_time DESC";
            $listovani = new listing($con,"?".$StrLink."&amp;",100,$query,3,"",$_GET['list_number']);
            $query=$listovani->updateQuery();
	          $result = $con->SelectQuery($query);
	          if ($con->GetQueryNum($query)>0){
            while($write = $result->fetch_array()){
               $strUser=$con->GetSQLSingleResult("SELECT username as item FROM web_users WHERE id=".$write['id_user']);
               $idCountry=$con->GetSQLSingleResult("SELECT id_country as item FROM web_users WHERE id=".$write['id_user']);
               $strState=$con->GetSQLSingleResult("SELECT name as item FROM countries_list WHERE id=".$idCountry);
               echo '<div class="forum_header">'.date("d.m.Y H:i",strtotime($write['date_time'])).' | <b>'.$strUser.'</b> | '.$strState.'</div>';
              echo '<div class="forum_text">';
                echo $games->showRelatedAnchors($write['text_forum']);
              echo "</div>";
              
            }
            $listovani->show_list();
            }
            
              }
          ?>
          
          
        </div>
          
                   
         <!-- main text end -->
      </div>
      
      <div id="col_right">
        <!--column right -->
        
        <?php require_once("inc/col_right_default.inc"); ?>
        
        <!-- column right end -->
      </div>
      
      <div class="clear">&nbsp;</div>
      <div id="text_space">&nbsp;</div>
      <div id="headlines_box"><?php require_once("inc/text_bottom_headlines.inc"); ?></div>
      
  </div>
  <?php if ($BoolBottomInfo){ ?>
  <!-- info box bottom -->
  <div class="corners top"><div class="toleft corner">&nbsp;</div><div class="toright corner">&nbsp;</div></div>
  <div id="bottom_info"><?php require_once("inc/bottom_info.inc"); ?></div>
  <div class="corners bottom"><div class="toleft corner">&nbsp;</div><div class="toright corner">&nbsp;</div></div>
  <!-- info box bottom end -->
  <?php } ?>
  
  <!-- bottom -->
  <div id="bottom_links"><?php require_once("inc/bottom_links.inc"); ?></div>
  <div id="bottom"><?php require_once("inc/bottom.inc"); ?></div>
  
</div>

</body>
</html>