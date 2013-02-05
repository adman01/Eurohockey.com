<?php
/** @class: web users (PHP5)
  * @project: eurohockey.com
  * @date: 11-22-2011
  * @version: 1.0.0
  * @author: Martin Formánek
  * @copyright: Martin Formánek
  * @email: martin.formanek@gmail.com
  */

class web_users{
  private $con,$input,$FacebookTooken;
  public $UserEmail,$UserID,$UserPassword,$UserAllDataArray;
  
  //konstruktor
  public function web_users($con)		{
    global $input;	
    $this->input=$input;
    $this->con=$con;
  }
  
  //destruktor
  public function __destruct(){ } 
  
  function session () {
      session_start();
      return session_id();
  }
  function getActualUserID(){ return $this->UserID;}
  function getActualFacebookTooken(){ return $this->FacebookTooken;}
  
  function get_facebook_tooken($app_id, $app_secret,$code,$url) {
    
    $my_url="http://".strWWWurl.$url;
     $token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
       . "&client_secret=" . $app_secret . "&code=" . $code;
     $response = @file_get_contents($token_url);
     $params = null;
     parse_str($response, $params);
     if (empty($params['access_token'])){
        return false;
     }else{
        $this->FacebookTooken=$params['access_token'];
        $_SESSION['FacebookTooken']=$params['access_token'];
        return true; 
     }
    
    
  }
  
  function get_facebook_data() {
  
  if (!empty($this->FacebookTooken)){
    $FacebookTooken=$this->FacebookTooken;
  }else{
    $FacebookTooken=$_SESSION['FacebookTooken'];  
  }
    if (!empty($FacebookTooken)){
      $graph_url = "https://graph.facebook.com/me?access_token=" 
       . $FacebookTooken;
      $user = json_decode(file_get_contents($graph_url));
      //echo $graph_url; 
      return $user;
    }
  }
  
 
  function is_loged() {
  
    if (!empty($_SESSION['is_loged']) and !empty($_SESSION['userID']) and !empty($_SESSION['userTooken'])){
       if ($_SESSION['is_loged']){
          $strCheckUserID=$_SESSION['userID'];
          $strCheckUserID=$this->input->valid_text($strCheckUserID,true,true);
          $strCheckUserTooken=$_SESSION['userTooken'];
          $strCheckUserTooken=$this->input->valid_text($strCheckUserTooken,true,true);
          $query="select id,email,password from web_users WHERE id=".$strCheckUserID;
          if ($this->con->GetQueryNum($query)==1){
              $result = $this->con->SelectQuery($query);
              $write = $result->fetch_array();
              $usrTooken=md5($write['id'].$write['email']);
              if ($usrTooken==$strCheckUserTooken){
                  
                  $this->UserID=$write['id'];
                  $this->UserPassword=$write['password'];
                  $this->UserEmail=$write['email'];
                  return true;
                  
              }else{
                return false;
              }
          }else{
            return false;
          }
       }else{
        return false;
      }        
          
    }else{
      
      $strCheckIsLoged=$_COOKIE["permanent_is_loged"];
      $strCheckIsLoged=$this->input->valid_text($strCheckIsLoged,true,true);
      $strCheckUserID=$_COOKIE["permanent_userID"];
      $strCheckUserID=$this->input->valid_text($strCheckUserID,true,true);
      $strCheckUserTooken=$_COOKIE["permanent_userTooken"];
      $strCheckUserTooken=$this->input->valid_text($strCheckUserTooken,true,true);
      
      // cookie automatic login  
      if (!empty($strCheckIsLoged) and !empty($strCheckUserID) and !empty($strCheckUserTooken)){
        if ($strCheckIsLoged){
          
          $query="select id,email,password,registration_type,id_facebook from web_users WHERE id=".$strCheckUserID;
          if ($this->con->GetQueryNum($query)==1){
              $result = $this->con->SelectQuery($query);
              $write = $result->fetch_array();
              $usrTooken=md5($write['id'].$write['email']);
              if ($usrTooken==$strCheckUserTooken){
                if ($write['registration_type']==1) {
                  $strPassword=md5(strWWWurl."facebook");
                  $strEmail=$write['id_facebook'];
                }else {
                  $strEmail=$write['email'];
                  $strPassword=$write['password'];
                }
                $this->login($strEmail,$strPassword,1);
                return true;
                
              }else{
                return false;
              }
          }else{
            return false;
          }
          
          
        }else{
          return false;
        }
        
      }else{
        return false;
      }  
      
    }
    
  }
  
  //ballhockey login
  function login($userEmail,$userPassword,$intRememberMe) {
    if (!empty($userEmail) and !empty($userPassword)){
    if ($userPassword==md5(strWWWurl."facebook")){
      $query="select id,password,email from web_users WHERE id_facebook=".$userEmail."";
    }else{
      $query="select id,password,email from web_users WHERE lcase(email)=lcase('".$userEmail."') AND password='".$userPassword."'";
    }
     // echo $userEmail;
    
    if ($this->con->GetQueryNum($query)==1){
          $result = $this->con->SelectQuery($query);
          $write = $result->fetch_array();
          $this->UserID=$write['id'];
          $this->UserPassword=$write['password'];
          $this->UserEmail=$write['email'];
          $_SESSION['is_loged']=true;
          $_SESSION['userID']=$this->UserID;
          $usrTooken=md5($this->UserID.$this->UserEmail);
          $_SESSION['userTooken']=$usrTooken;
          if ($intRememberMe){
            $CookieDuration = 2592000 + time();
            setcookie("permanent_is_loged",1,$CookieDuration,"/");
            setcookie("permanent_userID",$this->UserID,$CookieDuration,"/");
            setcookie("permanent_userTooken",$usrTooken,$CookieDuration,"/");
          }else{
            setcookie("permanent_is_loged",0,$CookieDuration,"/");
            setcookie("permanent_userID","",$CookieDuration,"/");
            setcookie("permanent_userTooken","",$CookieDuration,"/");  
          }
          return 1;
    }else{
      return 3;
    }
    }else{
      return 2;
    }
   
  }
  

 
  //check All requied data
  function checkRequiredData() {
    $bollRequired=true;
    $UserAllDataArray=$this->UserAllDataArray;
    if (!empty($UserAllDataArray)){
    
      if (empty($UserAllDataArray['registration_status'])){
          $bollEmailRequired=true;
      }else{ 
        
        if (empty($UserAllDataArray['name']) OR empty($UserAllDataArray['surname']) OR empty($UserAllDataArray['address']) OR empty($UserAllDataArray['city']) OR empty($UserAllDataArray['postal_code']) OR empty($UserAllDataArray['id_country'])){
          $bollRequired=false;        
        }else{
          $bollRequired=true;      
        }
      }
      
       
    }else{
       $bollRequired=false;      
    }
    
    if ($bollEmailRequired){
      header("Location: /user-update.html?message=1&limited=1");
    }else{
    if (!$bollRequired) 
      header("Location: /user-update.html?message=1&limited=1");
    }
  }
  
  
 
  
  //get all users data
  function get_users_data($id_user) {
    if (!empty($id_user)){
       $query="select * from web_users WHERE id=".$id_user."";
        
       if ($this->con->GetQueryNum($query)==1){
          $result = $this->con->SelectQuery($query);
          $write = $result->fetch_array();
          $this->UserAllDataArray=$write;
          return $write;
       }
    }
  }
  

  
  
  //check email  
  function checkEmailAddress($email) {
    if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email))
    {return true;}else{return false;}
  }


  
}

?>