<?php
/** @class: Users (PHP5)
  * @project: CMS
  * @date: 10-02-2008
  * @version: 1.0.0
  * @author: Martin Formánek
  * @copyright: Martin Formánek
  * @email: martin.formanek@gmail.com
  */

class users{
  private $con;
  public $user;
  public $password;
  public $id_user;
  public $sesid;
  public $id_group_page,$bolRead,$bolAdd,$bolUpdate,$bolDelete;
  
  //konstruktor
  public function users($con)		{	
    $this->con=$con;
  }
  
  //destruktor
  public function __destruct(){ } 
  
  function setUser($data){$this->user=$data;}
  function setPassword($data){$this->password=$data;}
  function setSesid($data,$data2){
    if (isset($data)){$this->sesid=$data;}
      else{$this->sesid=$data2;}
  }
  function setIdUser($data){$this->id_user=$data;}
  
  function getUser(){return $this->user;}
  function getPassword(){return $this->password;}
  function getSesid(){return $this->sesid;}
  function getIdUser(){return $this->id_user;}
  function getIdPageRight(){return $this->id_group_page;}
  
  function session () {
      session_start();
      return session_id();
  }
  
  // zapis prihlaseni do LOGU
  function WriteLogin($user_name,$id_status) {
       //id_status
       //1 uspesne prihlasen
       //2 nesouhlasi prih. udaje
       //3 nezadana vstupni data
       //4 automaticke odhlaseni - vyprselo pripojeni
       //5 odhlaseni
       $sql = "INSERT INTO  users_log (user_name, date_time,ip,id_status) ";
		   $sql = $sql."VALUES ('".$user_name."',NOW(),'".$_SERVER['REMOTE_ADDR']."',".$id_status.")";
		   $this->con->RunQuery($sql); 
  }
  
  // vrati jmeno uzivatele podle sesid
  function GetUserName($sesid) {
    $query="select (select user_name from users where users_session.id_user=users.id LIMIT 1)as name from users_session WHERE session_id='".$sesid."'";
    $result = $this->con->SelectQuery($query);
    $write = $result->fetch_array();
    return $write['name'];
  }
  
  // vrati podpis uzivatele nebo nick podle ID
  function GetUserSignatureID($id) {
    $query="select name, surname,user_name from users WHERE id='".$id."'";
    $result = $this->con->SelectQuery($query);
    $write = $result->fetch_array();
    if (empty($write['name']) and empty($write['surname'])){
      return $write['user_name'];
    }else{
      return $write['name'].' '.$write['surname'];
    }
  }
  
  // vrati podpis uzivatele podle sesid
  function GetUserSignature($sesid) {
    $query="select (select name from users where users_session.id_user=users.id LIMIT 1)as name,(select surname from users where users_session.id_user=users.id LIMIT 1)as surname from users_session WHERE session_id='".$sesid."'";
    $result = $this->con->SelectQuery($query);
    $write = $result->fetch_array();
    return $write['name']." ".$write['surname'];
  }
  
  // vrati datum posl. prihlaseni uzivatele podle sesid
  function GetUserLastLogin($sesid) {
    $query="select (select login_date_last from users where users_session.id_user=users.id LIMIT 1)as login_date_last from users_session WHERE session_id='".$sesid."'";
    $result = $this->con->SelectQuery($query);
    $write = $result->fetch_array();
    return $write['login_date_last'];
  }
  
  // vrati skupinu uzivatele podle ID
  function GetUserGroup($id) {
    $query="select id_group from users WHERE id=".$id."";
    $result = $this->con->SelectQuery($query);
    $write = $result->fetch_array();
    return $write['id_group'];
  }
  
  //prihlaseni uzivatele
  function loginUser(){
      if (isset($this->user) and isset($this->password)){
          $query="select id from users WHERE user_name='".$this->user."' AND password='".md5($this->password)."'";
          if ($this->con->GetQueryNum($query)==1){
            //je OK - probiha prihlaseni
            $this->con->SelectQuery($query);
            //ohlasi/vymaze jiz vyprsene sessions
            $sql = "DELETE FROM users_session WHERE date_time < Now()- INTERVAL ".MaxTimeConnection." MINUTE";
            $this->con->RunQuery($sql);
            
            $result = $this->con->SelectQuery($query);
            $write = $result->fetch_array();
            $userid=$write['id'];
            
            $sql = "UPDATE users SET login_date_last=login_date_actual WHERE id=".$userid."";
            $this->con->RunQuery($sql);
            
            $sql = "UPDATE users SET login_date_actual=NOW() WHERE id=".$userid."";
            $this->con->RunQuery($sql);
            
            //nastaveni sessid
            $strDatum = Date("d.m.Y");
		        $strCas = Date("G:i:s");
		        $sesid = md5($userid.$strCas.$strDatum.$_SERVER['REMOTE_ADDR']);
		        $sql = "INSERT INTO users_session (session_id, id_user, date_time, ip, session) ";
		        $sql = $sql."VALUES ('".$sesid."', '".$userid."', NOW(),'".$_SERVER['REMOTE_ADDR']."', '".$this->session()."')";
		        $this->con->RunQuery($sql);
		        $this->WriteLogin($this->user,1);
		        
		        header("Location: /admin/admin.php?sesid=".$sesid);
          }else
          {
            //nesouhlasi prihlaseni
            $this->WriteLogin($this->user,2);
            header("Location: /admin/index.php?error=2");
          }
      }
      else{
        //chybi udaje
        $this->WriteLogin($this->user,3);
        header("Location: /admin/index.php?error=1");
      }
  }
  
  //odhlaseni uzivatele
  function logoutUser($sesid,$message=4){
    $this->WriteLogin($this->GetUserName($sesid),5);
    $query="DELETE FROM users_session WHERE session_id='".$sesid."' AND session='".$this->session()."'";
    $this->con->RunQuery($query);
    header("Location: /admin/index.php?error=".$message);
  }
  
  //je prihlaseni aktivni ?
  function checkUser(){
    $query="select id_user from users_session WHERE session_id='".$this->sesid."' AND session='".$this->session()."' AND (date_time >= (Now()- INTERVAL ".MaxTimeConnection." MINUTE))";
    //echo $query; 
    if ($this->con->GetQueryNum($query)==1){
      //je normalne prihlasen
         $result = $this->con->SelectQuery($query);
         $write = $result->fetch_array();
         $this->setIdUser($write['id_user']);
         //obonoveni session
         $query="UPDATE users_session SET date_time=NOW() WHERE session_id='".$this->sesid."' AND session='".$this->session()."'";
         $this->con->RunQuery($query); 
         
    }else{
       //neni prihlasen
       $query="select (select user_name from users where users_session.id_user=users.id LIMIT 1)as name from users_session WHERE session_id='".$this->sesid."'";
       $this->WriteLogin($this->GetUserName($this->sesid),4);
       header("Location: /admin/index.php?error=3");
    }
  }
  
  //nastaveni uz. prav 
  function setUserRight($id_group,$bolRead,$bolAdd,$bolUpdate,$bolDelete){
      if (!isset($id_group) or $id_group<0) $this->badAcces(); else {$this->id_group_page=$id_group;};
      if (!isset($bolRead)) $bolRead=0; 
      if (!isset($bolAdd)) $bolAdd=0;
      if (!isset($bolUpdate)) $bolUpdate=0;
      if (!isset($bolDelete)) $bolDelete=0;
      $this->bolRead=$bolRead;
      $this->bolAdd=$bolAdd;
      $this->bolUpdate=$bolUpdate;
      $this->bolDelete=$bolDelete;
      $this->checkUserRight(0);
  }
  
  //presmerovat na erorr page
  function badAcces(){
    header("Location: /admin/access.php?sesid=".$this->sesid);
  }
  
 
  //celkova kontrola pristupu na stranku
  function checkUserRight($id_item){
    $id_group_user=$this->GetUserGroup($this->id_user);
    $query="select * from users_rights_items WHERE id_users_group='".$id_group_user."' AND id_users_rights='".$this->id_group_page."'";
    if ($this->con->GetQueryNum($query)==1){
      $result = $this->con->SelectQuery($query);
      $write = $result->fetch_array();
      
      if($id_item==0){
          //kontrola nataveni uz. prav pro celou stranku
          $read=$this->bolRead;
          $add=$this->bolAdd;
          $update=$this->bolUpdate;
          $delete=$this->bolDelete;
          if ($read==1)  {if ($write['user_read']==0){$this->badAcces();} }
          if ($add==1)   {if ($write['user_add']==0){$this->badAcces();} }
          if ($update==1){if ($write['user_update']==0){$this->badAcces();} }
          if ($delete==1){if ($write['user_delete']==0){$this->badAcces();} }
      }else{
          //kontrola nataveni uz. prav pro urcity prvek/blok stranky
          if ($id_item==1) {if ($write['user_read']==1){return true;} }
          if ($id_item==2) {if ($write['user_add']==1){return true;} }
          if ($id_item==3) {if ($write['user_update']==1){return true;} }
          if ($id_item==4) {if ($write['user_delete']==1){return true;} }
      }
      
    }
    else{$this->badAcces();}
  }
  
  //kontrola konkretniho uzivatelskeho prava /bollean 
  function checkThisUserRight($id_group,$rule){
    $id_group_user=$this->GetUserGroup($this->id_user);
    $query="select user_read,user_add,user_update,user_delete from users_rights_items WHERE id_users_group='".$id_group_user."' AND id_users_rights='".$id_group."'";  
    if ($this->con->GetQueryNum($query)==1){
        $result = $this->con->SelectQuery($query);
        $write = $result->fetch_array();
        switch ($rule){
          case 1:
            if($write['user_read']==1) return true; else return false;
          break;
          case 2:
            if($write['user_add']==1) return true; else return false;
          break;
          case 3:
            if($write['user_update']==1) return true; else return false;
          break;
          case 4:
            if($write['user_delete']==1) return true; else return false;
          break;
        }
    }
    else{return false;}
  }
  
  //vypis omezeni pro SPECIAL RIGHTS. Fce vraci array
  function getSpecialRightsSQL(){
    $id_group_page=$this->id_group_page;
    $query="SELECT * FROM users_special_rights WHERE id_right='".$id_group_page."' and id_user=".$this->id_user;  
    if ($this->con->GetQueryNum($query)>0){
        $result = $this->con->SelectQuery($query);
        $i1=1;
        $i2=1;
        $i3=1;
        $i4=1;
        while($write = $result->fetch_array()){
          
          if (!empty($write['id_country'])){
            if ($i1==1) $strArrCountry.='(id_country='.$write['id_country']; else  $strArrCountry.=' OR id_country='.$write['id_country'];
            $i1++;    
          }
          if (!empty($write['id_league'])){
            if ($i2==1) $strArrLeague.='(id_league='.$write['id_league']; else  $strArrLeague.=' OR id_league='.$write['id_league'];
            $i2++;    
          }
          if (!empty($write['id_club'])){
            if ($i3==1) $strArrClub.='(id_club='.$write['id_club']; else  $strArrClub.=' OR id_club='.$write['id_club'];
            $i3++;    
          }
          if (!empty($write['id_country'])){
            
            $query_sub="SELECT shortcut FROM countries WHERE id='".$write['id_country']."'";
            $result_sub = $this->con->SelectQuery($query_sub); 
            $write_sub = $result_sub->fetch_array();
            if ($i4==1) $strArrClubShort.='(id_country=\''.$write_sub['shortcut'].'\''; else  $strArrClubShort.=' OR id_country=\''.$write_sub['shortcut'].'\'';
            $i4++;    
          }
        }
        if ($i1>1) $arayRights[1]=$strArrCountry.=')';
        if ($i2>1) $arayRights[2]=$strArrLeague.=')';
        if ($i3>1) $arayRights[3]=$strArrClub.=')';
        if ($i4>1) $arayRights[4]=$strArrClubShort.=')';
      return $arayRights;
    }
    else{return false;}
  }
  
  
   //fce na kontrolu public prava uzivatele
  function checkPublicRight(){
    $query="select boolShowArticle from users WHERE id='".$this->id_user."'";
    if ($this->con->GetQueryNum($query)==1){
      $result = $this->con->SelectQuery($query);
      $write = $result->fetch_array();
      return $write['boolShowArticle']; 
    } else{
      return 0;
    }
    
  }
  
  
  
  
  
}

?>