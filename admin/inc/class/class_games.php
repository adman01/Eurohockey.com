<?php
/** @class: Users (PHP5)
  * @project: CMS
  * @date: 13-10-2008
  * @version: 1.0.0
  * @author: Martin Formánek
  * @copyright: Martin Formánek
  * @email: martin.formanek@gmail.com
  */

class games{
  private $con;
  public $id_club,$id_season;
  
  //konstruktor
  public function games($con)		{	
    $this->con=$con;
  }
  
  //destruktor
  public function __destruct(){ } 
  
  
  // vrati aktualni jmeno klubu 
  function GetActualClubName($id_club,$id_season) {
    //$id_season=$id_season-1;
    $query="select name from clubs_names WHERE id_club='".$id_club."'";
    if ($this->con->GetQueryNum($query)>0){
    
      //alternativni nazvy
      $query="select name from clubs_names WHERE id_club='".$id_club."' AND int_year='".$id_season."'";
      if ($this->con->GetQueryNum($query)>0){
        $result = $this->con->SelectQuery($query);
        $write = $result->fetch_array();
        $name=$write['name'];
      }else{
        $query="select name from clubs_names WHERE id_club='".$id_club."' AND int_year<'".$id_season."' order by int_year DESC";
        if ($this->con->GetQueryNum($query)>0){
          $result = $this->con->SelectQuery($query);
          $write = $result->fetch_array();
          $name=$write['name'];
        }else{
          $query="select name from clubs WHERE id='".$id_club."'";
          $result = $this->con->SelectQuery($query);
          $write = $result->fetch_array();
          $name=$write['name'];
        }
      }
      
    }else{
      $query="select name from clubs WHERE id='".$id_club."'";
      $result = $this->con->SelectQuery($query);
      $write = $result->fetch_array();
      $name=$write['name'];
    }
      
    return $name;
  }
  
  // vrati aktualni ID loga klubu 
  function GetActualClubLogo($id_club,$id_season) {
    //$id_season=$id_season-1;
    $query="select id_image from clubs_images WHERE id_club='".$id_club."'";
    if ($this->con->GetQueryNum($query)>0){
    
      //alternativni nazvy
      $query="select id_image from clubs_images WHERE id_club='".$id_club."' AND int_year='".$id_season."'";
      if ($this->con->GetQueryNum($query)>0){
        $result = $this->con->SelectQuery($query);
        $write = $result->fetch_array();
        $name=$write['id_image'];
      }else{
        $query="select id_image from clubs_images WHERE id_club='".$id_club."' AND int_year<'".$id_season."' order by int_year DESC";
        if ($this->con->GetQueryNum($query)>0){
          $result = $this->con->SelectQuery($query);
          $write = $result->fetch_array();
          $name=$write['id_image'];
        }else{
          $query="select id_image from clubs_images WHERE id_club='".$id_club."'";
          $result = $this->con->SelectQuery($query);
          $write = $result->fetch_array();
          $name=$write['id_image'];
        }
      }
      
    }else{
      $name=0;
    }
      
    return $name;
  }
  
  // vrati aktualni fotku hrace
  function GetActualPlayerFoto($id_player,$id_season) {
    //$id_season=$id_season-1;
    $query="select id_photo from players_photo WHERE id_player='".$id_player."'";
    if ($this->con->GetQueryNum($query)>0){
    
      //fotka k sezone
      $query="select id_photo from players_photo WHERE id_player='".$id_player."' AND id_season='".$id_season."'";
      if ($this->con->GetQueryNum($query)>0){
        $result = $this->con->SelectQuery($query);
        $write = $result->fetch_array();
        $name=$write['id_photo'];
      }else{
        $query="select id_photo from players_photo WHERE id_player='".$id_player."' AND id_season<'".$id_season."' order by id_season DESC";
        if ($this->con->GetQueryNum($query)>0){
          $result = $this->con->SelectQuery($query);
          $write = $result->fetch_array();
          $name=$write['id_photo'];
        }else{
          $query="select id_photo from players_photo WHERE id_player='".$id_player."' AND id_season>'".$id_season."' order by id_season DESC";
          $result = $this->con->SelectQuery($query);
          $write = $result->fetch_array();
          $name=$write['id_photo'];
        }
      }
      return $name;
    }else{
      return false;
    }
  }
  
  // vrati zkratku klubu 
  function GetActualClubShortcut($id_club,$id_season) {
    $query="select short_name from clubs WHERE id='".$id_club."'";
    if ($this->con->GetQueryNum($query)>0){
        $result = $this->con->SelectQuery($query);
        $write = $result->fetch_array();
        $name=$write['short_name'];
    }ELSE{
      GetActualClubName($id_club,$id_season);
    }
    return $name;
  }
  
  // vrati aktualni jmeno ligy 
  function GetActualLeagueName($id_league,$id_season) {
 
    $query="select name from leagues_names WHERE id_league='".$id_league."'";
    if ($this->con->GetQueryNum($query)>0){
    
      //alternativni nazvy
      $query="select name from leagues_names WHERE id_league='".$id_league."' AND   	int_year='".$id_season."'";
      if ($this->con->GetQueryNum($query)>0){
        $result = $this->con->SelectQuery($query);
        $write = $result->fetch_array();
        $name=$write['name'];
      }else{
        $query="select name from leagues_names WHERE id_league='".$id_league."' AND   	int_year<'".$id_season."' order by   	int_year DESC";
        if ($this->con->GetQueryNum($query)>0){
          $result = $this->con->SelectQuery($query);
          $write = $result->fetch_array();
          $name=$write['name'];
        }else{
          $query="select name from leagues_names WHERE id_league='".$id_league."' AND   	int_year>'".$id_season."' order by   	int_year ASC";
          $result = $this->con->SelectQuery($query);
          $write = $result->fetch_array();
          $name=$write['name'];
        }
      }
      
    }else{
      $query="select name from leagues WHERE id='".$id_league."'";   
      $result = $this->con->SelectQuery($query);
      $write = $result->fetch_array();
      $name=$write['name'];
    }
     return $name;
    
  }
  
  function GetActualLeagueShortcut($id_league,$id_season) {
 
    $query="select name from leagues_names WHERE id_league='".$id_league."'";
    if ($this->con->GetQueryNum($query)>0){
    
      //alternativni nazvy
      $query="select name from leagues_names WHERE id_league='".$id_league."' AND   	int_year='".$id_season."'";
      if ($this->con->GetQueryNum($query)>0){
        $result = $this->con->SelectQuery($query);
        $write = $result->fetch_array();
        $name=$write['name'];
      }else{
        $query="select name from leagues_names WHERE id_league='".$id_league."' AND   	int_year<'".$id_season."' order by   	int_year DESC";
        if ($this->con->GetQueryNum($query)>0){
          $result = $this->con->SelectQuery($query);
          $write = $result->fetch_array();
          $name=$write['name'];
        }else{
          $query="select name from leagues_names WHERE id_league='".$id_league."' AND   	int_year>'".$id_season."' order by   	int_year ASC";
          $result = $this->con->SelectQuery($query);
          $write = $result->fetch_array();
          $name=$write['name'];
        }
      }
      
    }else{
      $query="select name_short from leagues WHERE id='".$id_league."'";   
        $result = $this->con->SelectQuery($query);
        $write = $result->fetch_array();
        $name=$write['name_short'];
      if($name==""){
         $query="select name from leagues WHERE id='".$id_league."'";  
         $result = $this->con->SelectQuery($query);
         $write = $result->fetch_array();
         $name=$write['name'];
      }

    }
     return $name;
    
  }
  
  // vrati aktualni jmeno zame 
  function GetActualCountryName($id_country,$id_season) {
 
    $query="select name from countries_names WHERE id_country='".$id_country."'";
    if ($this->con->GetQueryNum($query)>0){
    
      //alternativni nazvy
      $query="select name from countries_names WHERE id_country='".$id_country."' AND   	int_year='".$id_season."'";
      if ($this->con->GetQueryNum($query)>0){
        $result = $this->con->SelectQuery($query);
        $write = $result->fetch_array();
        $name=$write['name'];
      }else{
        $query="select name from countries_names WHERE id_country='".$id_country."' AND   	int_year<'".$id_season."' order by   	int_year DESC";
        if ($this->con->GetQueryNum($query)>0){
          $result = $this->con->SelectQuery($query);
          $write = $result->fetch_array();
          $name=$write['name'];
        }else{
          $query="select name from countries_names WHERE id_country='".$id_country."' AND   	int_year>'".$id_season."' order by   	int_year ASC";
          $result = $this->con->SelectQuery($query);
          $write = $result->fetch_array();
          $name=$write['name'];
        }
      }
      
    }else{
      $query="select name from countries WHERE id='".$id_country."'";
      $result = $this->con->SelectQuery($query);
      $write = $result->fetch_array();
      $name=$write['name'];
    }
     return $name;
    
  }
  
  // vrati aktualni jmeno ARENY
  function GetActualArenaName($id_arena,$id_season) {
    //$id_season=$id_season-1;
    $query="select name from arenas_names WHERE id_arena='".$id_arena."'";
    if ($this->con->GetQueryNum($query)>0){
    
      //alternativni nazvy
      $query="select name from arenas_names WHERE id_arena='".$id_arena."' AND   	int_year='".$id_season."'";
      if ($this->con->GetQueryNum($query)>0){
        $result = $this->con->SelectQuery($query);
        $write = $result->fetch_array();
        $name=$write['name'];
      }else{
        $query="select name from arenas_names WHERE id_arena='".$id_arena."' AND   	int_year<'".$id_season."' order by   	int_year DESC";
        if ($this->con->GetQueryNum($query)>0){
          $result = $this->con->SelectQuery($query);
          $write = $result->fetch_array();
          $name=$write['name'];
        }else{
          $query="select name from arenas_names WHERE id_arena='".$id_arena."' AND   	int_year>'".$id_season."' order by   	int_year ASC";
          $result = $this->con->SelectQuery($query);
          $write = $result->fetch_array();
          $name=$write['name'];
        }
      }
      
    }else{
      $query="select name from arenas WHERE id='".$id_arena."'";
      $result = $this->con->SelectQuery($query);
      $write = $result->fetch_array();
      $name=$write['name'];
    }
     return $name;
    
  }
  
  // vrati mladeznickou zkratku pokud je liga YOUTH
  function GetYouthLeague($id_league) {
    $query="SELECT youth_league_id FROM leagues WHERE youth_league=1 AND id=".$id_league;
    if ($this->con->GetQueryNum($query)>0){
    
        $result = $this->con->SelectQuery($query);
        $write = $result->fetch_array();
        $youth_league_id=$write['youth_league_id'];
        
        $query="SELECT name FROM leagues_youth_list WHERE id=".$youth_league_id;
        
        $result = $this->con->SelectQuery($query);
        $write = $result->fetch_array();
        $name=$write['name'];
      
    }else{
      $name="";
    }
     return $name;
  }
  
  //zapise atribut statistiky 
  function WriteStats($id_stats,$id_atribute,$direction,$value) {
      $change=false;
      if (!empty($id_stats)){
        
        $query="SELECT ".$id_atribute." FROM stats WHERE id=".$id_stats;
        if ($this->con->GetQueryNum($query)>0){
        $result = $this->con->SelectQuery($query);
          $write = $result->fetch_array();
          $old_value=$write[$id_atribute];
        }
        
        if ($direction=="+"){
            $old_value=($old_value)+($value);
        }else{
          $old_value=($old_value)-($value);
        }   
        
        $query="UPDATE stats SET ".$id_atribute."=".$old_value." WHERE id=".$id_stats;
        //echo $query; 
        if ($this->con->RunQuery($query)) {$change=true;}
      }
    return $change;
  }
  
  // vrati true pokud je shotout
  function GetShotout($id_game,$id_club) {
    $query="SELECT home_score,visiting_score,	id_club_home,	id_club_visiting FROM games WHERE id=".$id_game;
    $boolShotout=false;
    if ($this->con->GetQueryNum($query)>0){
        $result = $this->con->SelectQuery($query);
        $write = $result->fetch_array();
        if ($write['id_club_home']==$id_club){
            if ($write['visiting_score']==0) $boolShotout=true;
        }
        
        if ($write['id_club_visiting']==$id_club){
            if ($write['home_score']==0) $boolShotout=true;
        }
      
    }
     return $boolShotout;
  }
  
  // vrati spravne skore - text 
  function GetScore($id_game,$type) {
    
      $query="select home_score,visiting_score, (SELECT shortcut FROM games_status_list WHERE games_status_list.id=games.games_status) as 	games_status from games WHERE home_score is NOT null AND visiting_score is NOT null AND id=".$id_game;
      if ($this->con->GetQueryNum($query)>0){
        $result = $this->con->SelectQuery($query);
        $write = $result->fetch_array();
    
        $score=$write['home_score']."-".$write['visiting_score'];
        if (!empty($write['games_status'])) $score.=' <small>'.$write['games_status'].'</small>';
        
      }else{
        $score="-";
      }
    return $score;
  }
  
  function showRelatedAnchors($text){
    
    $text=split(" ",$text);
    for ($i=0;$i<count($text);$i++) {
      if (!empty($text[$i])){
      //$term=trim(str_replace("&nbsp;","",$text[$i]));
    $text[$i] = Str_Replace("*1*",'<img src="img/smiles/01.png" class="smile" alt="smajlik" />',$text[$i]);
    $text[$i] = Str_Replace("*2*",'<img src="img/smiles/02.png" class="smile" alt="" />',$text[$i]);
    $text[$i] = Str_Replace("*3*",'<img src="img/smiles/03.png" class="smile" alt="" />',$text[$i]);
    $text[$i] = Str_Replace("*4*",'<img src="img/smiles/04.png" class="smile" alt="" />',$text[$i]);
    $text[$i] = Str_Replace("*5*",'<img src="img/smiles/05.png" class="smile" alt="" />',$text[$i]);
    $text[$i] = Str_Replace("*6*",'<img src="img/smiles/06.png" class="smile" alt="" />',$text[$i]);
    $text[$i] = Str_Replace("*7*",'<img src="img/smiles/07.png" class="smile" alt="" />',$text[$i]);
    $text[$i] = Str_Replace("*8*",'<img src="img/smiles/08.png" class="smile" alt="" />',$text[$i]);
    $text[$i] = Str_Replace("*9*",'<img src="img/smiles/09.png" class="smile" alt="" />',$text[$i]);
    $text[$i] = Str_Replace("*10*",'<img src="img/smiles/10.png" class="smile" alt="" />',$text[$i]);
    $text[$i] = Str_Replace("*11*",'<img src="img/smiles/11.png" class="smile" alt="" />',$text[$i]);
    $text[$i] = Str_Replace("*12*",'<img src="img/smiles/12.png" class="smile" alt="" />',$text[$i]);
    $text[$i] = Str_Replace("*13*",'<img src="img/smiles/13.png" class="smile" alt="" />',$text[$i]);
    $text[$i] = Str_Replace("*14*",'<img src="img/smiles/14.png" class="smile" alt="" />',$text[$i]);
    $text[$i] = Str_Replace("*15*",'<img src="img/smiles/15.png" class="smile" alt="" />',$text[$i]);
    $text[$i] = Str_Replace("*16*",'<img src="img/smiles/16.png" class="smile" alt="" />',$text[$i]);
  
      
     }
    }
    
        
    $text=join(" ",$text);

    return $text;
  }
}

?>