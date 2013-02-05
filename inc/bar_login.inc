 <div id="bar_login">
    <?php
    if (empty($strUserArray)){
      echo '
        <div class="toright">
        <a href="/user-login.html" title="Log in">Log in</a>
        <span>|</span>
        <a href="/user-login.html" title="Sign Up">Sign Up</a>
        
        </div>
      ';
    }else{
      echo '<div class="toright">
      Hi, <a href="/user-update.html" title="Update my profile">'.$strUserArray['name'].' '.$strUserArray['surname'].'</a>
      <span>|</span>
      <a href="/sponsorship.html" title="My sponsorship">My sponsorship</a>
      <span>|</span>
      <a href="/user-logout.html" title="Log out" class="borderLeft">Log out</a></div> 
      ';
      
      
    }
  ?>
  <div class="clear"></div>
  </div>