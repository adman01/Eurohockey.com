<?php
require_once("inc/global.inc");
require_once("inc/ads.inc");

$strUserArray=check_login(1);
$intRegistrationType=$strUserArray["registration_type"];
if ($intRegistrationType==1){$strEmailRequired=false;}else {$strEmailRequired=true;}

    $strUserData["name"]=$strUserArray["name"];
    $strUserData["surname"]=$strUserArray["surname"];
    $strUserData["address"]=$strUserArray["address"];
    $strUserData["city"]=$strUserArray["city"];
    $strUserData["postal_code"]=$strUserArray["postal_code"];
    $strUserData["phone"]=$strUserArray["phone"];
    $strUserData["id_country"]=$strUserArray["id_country"];
    $strUserData["ico"]=$strUserArray["ico"];
    $strUserData["dic"]=$strUserArray["dic"];
    $strUserData["id_user"]=$strUserArray["id"];
    

if (empty($_GET['firstname'])) $strFirstname=$strUserData["name"]; else $strFirstname=$_GET['firstname']; 
if (empty($_GET['lastname'])) $strLastname=$strUserData["surname"]; else $strLastname=$_GET['lastname'];
if (empty($_GET['address'])) $strAddress=$strUserArray["address"]; else $strAddress=$_GET['address'];
if (empty($_GET['city'])) $strCity=$strUserData["city"]; else $strCity=$_GET['city'];
if (empty($_GET['postal_code'])) $strPostalCode=$strUserArray["postal_code"]; else $strPostalCode=$_GET['postal_code'];
if (empty($_GET['phone'])) $strPhone=$strUserArray["phone"]; else $strPhone=$_GET['phone'];
if (empty($_GET['id_country'])) $intIdCountry=$strUserArray["id_country"]; else $intIdCountry=$_GET['id_country'];
if (empty($_GET['is_password'])) $StrIsPassword=0; else   $StrIsPassword=1;
if (empty($_GET['email'])) $strEmail=$strUserArray["email"]; else $strEmail=$_GET['email'];
if (empty($_GET['ico'])) $strIco=$strUserArray["ico"]; else $strIco=$_GET['ico'];
if (empty($_GET['dic'])) $strDic=$strUserArray["dic"]; else $strDic=$_GET['dic'];



$strHeader="User profile update";

$strHeaderKeywords=$strHeader;
$strHeaderDescription=$strHeader;
require_once("inc/head.inc");
echo $head->setTitle($strHeader." - ".strProjectName);


?>
<script src="/inc/jquery/jquery-date.js" type="text/javascript"></script>
<script type="text/javascript" src="/inc/jquery/jquery.validate.min.js"></script>
  <script type="text/javascript">
  $(document).ready(function(){
      $("#form1").validate({
        rules: {
          password: { 
                required: true, minlength: 5
          }, 
          password_confirm: { 
                required: true, equalTo: "#password", minlength: 5
          } 
        }

      });
      
      
       $('#is_password').click(function() {
          
          $("#is_password_box").toggle();
           
          
      });
  

      
      
  });
  </script>
<?php
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

         <div id="login" style="border:0px;">
         <?php  
         echo '<h1>'.$strHeader.'</h1>';
         
       


        ?>

       <form id="form1" action="/user-register-action/update-user.html" method="post">
  
<?php
  switch ($_GET['message']){
    case 1:
      $strMessage="<span style=\"font-size:1.1em\">Please add personal data about <strong>yourself</strong>. You will be able continue to sponsorship section.</span>";
    break;
    case 2:
      $strMessage="Error: You haven't entered all the required fields.";
    break;
    case 3:
      $strMessage="Error: Passwords don't match.";
    break;
    case 4:
      $strMessage="Error: ".$strEmail." is not valid.";
    break;
    case 5:
      $strMessage="Error: Email ".$strEmail." is already registred.";
    break;
    case 6:
      $strMessage="Error: Database error. Please contact webmaster.";
    break;
    case 7:
      $strMessage="Error: Facebook connection failed.";
    break;
    case 8:
      $strMessage="Error: You already have Facebook registration on Ballhockey.net.";
    break;
  }
  if (!empty($strMessage))echo '<p class="message">'.$strMessage.'</p>';

 ?>
  
    <div class="space"></div>

    
        <div class="input_holder">
              <label for="email">Your email *:</label>
              <input type="text" class="required email long" name="email" id="email" value="<?php echo $strEmail ?>" maxlength="50" />
              <div class="clear"></div>
        </div>

        <div class="input_holder">
              <label for="firstname">Your first name *:</label>
              <input type="text" class="required" name="firstname" id="firstname" value="<?php echo $strFirstname ?>" size="50" maxlength="50" />
              <div class="clear"></div>
        </div>

        <div class="input_holder">
              <label for="lastname">Your last name *:</label>
              <input type="text" class="required" name="lastname" id="lastname" value="<?php echo $strLastname ?>" size="50" maxlength="50" />
              <div class="clear"></div>
        </div>

        <div class="input_holder">
              <label for="address">Your address *:</label>
              <input type="text" class="required" name="address" id="address" value="<?php echo $strAddress ?>" size="50" maxlength="50" />
              <div class="clear"></div>
        </div>

        <div class="input_holder">
              <label for="city">City *:</label>
              <input type="text" class="required" name="city" id="city" value="<?php echo $strCity ?>" size="50" maxlength="50" />
              <div class="clear"></div>
        </div>

        <div class="input_holder">
              <label for="postal_code">ZIP *:</label>
              <input type="text" class="required" name="postal_code" id="postal_code" value="<?php echo $strPostalCode ?>" size="50" maxlength="50" />
              <div class="clear"></div>
        </div>

        <div class="input_holder">
              <label for="id_country">Country *:</label>
              <select id="id_country" name="id_country" class="required">
              <?php
                $query_sub="SELECT id,name FROM countries ORDER by name ASC";
                $result_sub = $con->SelectQuery($query_sub);
                echo '<option value="">--select country--</option>';
                while($write_sub = $result_sub->fetch_array()){
                  echo '<option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$intIdCountry).'>'.$write_sub['name'].'</option>';
                }
              ?>
              </select>
              <div class="clear"></div>
        </div>

        

        <div class="input_holder">
              <label for="postal_code">Phone:</label>
              <input type="text" class="" name="phone" id="phone" value="<?php echo $strPhone ?>" size="50" maxlength="50" />
              <div class="clear"></div>
        </div>

         <div class="space"></div>
          <div class="line"></div>
          <div class="space"></div>

         <div class="input_holder">
              <label for="ico">Trade reg. number:</label>
              <input type="text" class="" name="ico" id="ico" value="<?php echo $strIco ?>" size="50" maxlength="50" />
              <div class="clear"></div>
        </div>

        <div class="input_holder">
              <label for="dic">VAT number:</label>
              <input type="text" class="" name="dic" id="phone" value="<?php echo $strDic ?>" size="50" maxlength="50" />
              <div class="clear"></div>
        </div>

        

        <?php
        if ($strEmailRequired){
          echo '
          <div class="space"></div>
          <div class="line"></div>
          <div class="space"></div>
          <div class="input_holder"><label for="is_password">Change password</label>';
          echo '<input type="checkbox" class="checkbox" name="is_password" id="is_password" '.write_checked(1,$StrIsPassword).' value="1" />';
          echo '<div class="clear"></div></div>';
          
          echo '<div id="is_password_box"'; 
          if ($StrIsPassword==0) echo 'style="display:none"';
          echo'>';
            echo '<div class="input_holder"><label for="password">Password</label><input type="password" name="password" id="password" class="required long" value="" size="50" maxlength="50" /><div class="clear"></div></div>';
            echo '<div class="input_holder"><label for="password_confirm">Password confirm</label><input type="password" class="required long" name="password_confirm" id="password_confirm" value="" size="50" maxlength="50" /><div class="clear"></div></div>';         
          echo '</div>';
          }    
        ?>
  
  
         <div class="space"></div>
          <div class="line"></div>
          <div class="space"></div>

         <div class="inputSubmit center">
            <input type="submit" name="submit" value="Update Account" class="submit"/>
            </div>
  
       
        </form>

  </div>
       
        
         
         <!-- main text end -->
      </div>
      
      <div id="col_right">
        <?php require_once("inc/col_right_default.inc"); ?>
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