<?php
/** @class: Users (PHP5)
  * @project: CMS
  * @date: 4-11-2010
  * @version: 1.0.0
  * @author: Martin Formánek
  * @copyright: Martin Formánek
  * @email: martin.formanek@gmail.com
  */

class xml_generator{
  private $con,$games;
  
  //konstruktor
  public function xml_generator($con)		{	
    $this->con=$con;
    $this->games = new games($this->con);
  }
  
  //destruktor
  public function __destruct(){ } 
   
  
  //---------------vygenerovani XML STANDINGS
  function generate_xml_standings($id){
  
    $strXML="";
    
    $query="SELECT * FROM standings WHERE id=".$id."";
    //echo $query; 
    if ($this->con->GetQueryNum($query)>0){
        $result = $this->con->SelectQuery($query);
        $write = $result->fetch_array();
        
        $strLeagueName=$this->con->GetSQLSingleResult("SELECT name as item FROM leagues WHERE id=".$write['id_league']);
        $strStageName=$this->con->GetSQLSingleResult("SELECT name as item FROM games_stages WHERE id=".$write['id_stage']);
        $strXML.='<standings last_update="'.$write['last_update'].'" name="'.$write['name'].'" league="'.$strLeagueName.'" stage="'.$strStageName.'" season="'.($write['id_season']-1).'-'.$write['id_season'].'">';
        $query_groups="SELECT * FROM standings_groups WHERE id_standings=".$id." ORDER BY order_group ASC";
          if ($this->con->GetQueryNum($query_groups)>0){
            $result_groups = $this->con->SelectQuery($query_groups);
            while($write_groups = $result_groups->fetch_array()){
              $strXML.='<group id="'.$write_groups['id'].'" name="'.$write_groups['name'].'">';
              $strXML.=$this->get_standings_row($write_groups['id'],$write['id_type'],$write['id'],$write['id_season']);
              $strXML.='</group>';
            }
          }
          else{
              $strXML.='<group id="'.$write_groups['id'].'" name="'.$write_groups['name'].'">';
              $strXML.=$this->get_standings_row(0,$write['id_type'],$write['id'],$write['id_season']);
              $strXML.='</group>';
          }
     $strXML.='</standings>';
     }
    
    $myFile = "../../xml/standings/standings_".$id.".xml";
    $fh = fopen($myFile, 'w') or die("can't open file");
    fwrite($fh, $strXML);
    fclose($fh);
    
  }
  
  function get_standings_row($id_group,$id_type,$id_standings,$id_season){
         $strXMLitem="";
         $query="SELECT id FROM standings_teams WHERE int_order=0 AND id_table_type=".$id_type." AND id_group=".$id_group." AND id_standings=".$id_standings;
         $intPocet=$this->con->GetQueryNum($query);
         if ($intPocet>0){
             $strOrder=" (points+bonus_points) DESC, (score1-score2) DESC,score1 DESC, id DESC";
         }else{
             $strOrder=" int_order ASC";
         }
         
         $query="SELECT * FROM  standings_teams WHERE id_group=".$id_group." AND id_table_type=".$id_type." AND id_standings=".$id_standings." ORDER BY ".$strOrder;
         //echo $query;
         $intPocet=$this->con->GetQueryNum($query);
          
         $i = 0;
         if ($intPocet>0){
            $result = $this->con->SelectQuery($query);
            while($write = $result->fetch_array())
	          {
	             $i++;
	             $StrClubName=$this->games->GetActualClubName($write['id_club'],$id_season);
	             $StrClubNameOriginal=$this->con->GetSQLSingleResult("SELECT name_original as item FROM clubs WHERE id=".$write['id_club']);
	             $strXMLitem.='<club pos="'.($i).'" games="'.$write['games'].'" wins="'.$write['wins'].'" wins_ot="'.$write['wins_ot'].'" ties="'.$write['draws'].'" losts_ot="'.$write['losts_ot'].'" losts="'.$write['losts'].'" score1="'.$write['score1'].'" score2="'.$write['score2'].'" points="'.($write['points']+$write['bonus_points']).'" team_original="'.$StrClubNameOriginal.'">'.$StrClubName.'</club>';
             }
          }
        return $strXMLitem;
    } 
  
  
  //---------------vygenerovani XML s prehledem zapasu pro celou ligu
  function generate_xml_games($id_season,$id_league,$id_stage){
  
    $strXML="";
    
    $query="SELECT * FROM games WHERE id_season=".$id_season." AND id_league=".$id_league." AND id_stage=".$id_stage." ORDER by date ASC, time ASC, id ASC";
    if ($this->con->GetQueryNum($query)>0){
        $result = $this->con->SelectQuery($query);
        $strLeagueName=$this->con->GetSQLSingleResult("SELECT name as item FROM leagues WHERE id=".$id_league);
        $strStageName=$this->con->GetSQLSingleResult("SELECT name as item FROM games_stages WHERE id=".$id_stage);
        $strSeasonLastUpdate=$this->con->GetSQLSingleResult("SELECT last_update as item FROM games WHERE id_season=".$id_season." AND id_league=".$id_league." AND id_stage=".$id_stage."  ORDER by last_update DESC");
        
        $strXMLSeason.='<season last_update="'.$strSeasonLastUpdate.'" league="'.$strLeagueName.'" stage="'.$strStageName.'" season="'.($id_season-1).'-'.$id_season.'">';
        $strXML.=$strXMLSeason;
        while($write = $result->fetch_array()){
              $id_game=$write['id'];
              $StrClubHome=$this->games->GetActualClubName($write['id_club_home'],$id_season);
              $StrClubHomeOriginal=$this->con->GetSQLSingleResult("SELECT 	name_original as item FROM clubs WHERE id=".$write['id_club_home']);
              $StrClubVisiting=$this->games->GetActualClubName($write['id_club_visiting'],$id_season);
              $StrClubVisitingOriginal=$this->con->GetSQLSingleResult("SELECT name_original as item FROM clubs WHERE id=".$write['id_club_visiting']);
              $StrArena=$this->games->GetActualArenaName($write['id_arena'],$id_season);
              $strXMLGame='<game id="'.$write['id'].'" date="'.$write['date'].'" time="'.$write['time'].'" round="'.$write['round'].'" team_home="'.$StrClubHome.'" team_home_original="'.$StrClubHomeOriginal.'" team_visitors="'.$StrClubVisiting.'"  team_visitors_original="'.$StrClubVisitingOriginal.'" arena="'.$StrArena.'"  home_score="'.$write['home_score'].'"  visiting_score="'.$write['visiting_score'].'"  game_status="'.$write['games_status'].'">';
              $strXML.=$strXMLGame;
              $strXML.='</game>';
        }
        
        $strXML.='</season>';
        
        
        
        
     }
    
    $myFile = "../../xml/games/games_".$id_season."_".$id_league."_".$id_stage.".xml";
    $fh = fopen($myFile, 'w') or die("can't open file");
    fwrite($fh, $strXML);
    fclose($fh);
    //echo $strXML;
  
  }
  
  //---------------vygenerovani XML s detailem zapasu
  function generate_xml_game_detail($id_game){
    
    header ("Content-Type:text/xml");  
    $strXML="";
    
    $query="SELECT * FROM games WHERE id=".$id_game." LIMIT 1";
    //echo $query; 
    if ($this->con->GetQueryNum($query)>0){
        $result = $this->con->SelectQuery($query);
        $write = $result->fetch_array();
                
        $id_stage=$write['id_stage'];                
        $id_league=$write['id_league'];
        $id_season=$write['id_season'];
        
        $strLeagueName=$this->con->GetSQLSingleResult("SELECT name as item FROM leagues WHERE id=".$id_league);
        $strStageName=$this->con->GetSQLSingleResult("SELECT name as item FROM games_stages WHERE id=".$id_stage);
        $strSeasonLastUpdate=$this->con->GetSQLSingleResult("SELECT last_update as item FROM games WHERE id_season=".$id_season." AND id_league=".$id_league." AND id_stage=".$id_stage."  ORDER by last_update DESC");
                
        $strXMLSeason.='<season last_update="'.$strSeasonLastUpdate.'" league="'.$strLeagueName.'" stage="'.$strStageName.'" season="'.($id_season-1).'-'.$id_season.'">';
        $strXML.=$strXMLSeason;
              

              $strXMLextended=$strXMLSeason;
              $StrClubHome=$this->games->GetActualClubName($write['id_club_home'],$id_season);
              $StrClubHomeOriginal=$this->con->GetSQLSingleResult("SELECT 	name_original as item FROM clubs WHERE id=".$write['id_club_home']);
              $StrClubVisiting=$this->games->GetActualClubName($write['id_club_visiting'],$id_season);
              $StrClubVisitingOriginal=$this->con->GetSQLSingleResult("SELECT name_original as item FROM clubs WHERE id=".$write['id_club_visiting']);
              $StrArena=$this->games->GetActualArenaName($write['id_arena'],$id_season);
              $strXMLGame='<game id="'.$write['id'].'" date="'.$write['date'].'" time="'.$write['time'].'" round="'.$write['round'].'" team_home="'.$StrClubHome.'" team_home_original="'.$StrClubHomeOriginal.'" team_visitors="'.$StrClubVisiting.'"  team_visitors_original="'.$StrClubVisitingOriginal.'" arena="'.$StrArena.'"  home_score="'.$write['home_score'].'"  visiting_score="'.$write['visiting_score'].'"  game_status="'.$write['games_status'].'">';
              $strXML.=$strXMLGame;
              $strXMLextended.=$strXMLGame;
              
                $strXMLextended.='<referees>'.$write['referees'].'</referees>';
                $strXMLextended.='<spectators>'.$write['spectators'].'</spectators>';
                $strXMLextended.='<periods>';
                      $query_sub="SELECT * FROM games_periods WHERE id_game=".$write['id']." ORDER by periods_order ASC";
                      if ($this->con->GetQueryNum($query_sub)>0){
                        $result_sub = $this->con->SelectQuery($query_sub);
                         while($write_sub = $result_sub->fetch_array()){
                           $strXMLextended.='<period id="'.$write_sub['id'].'" order="'.$write_sub['periods_order'].'" id_type="'.$write_sub['id_type'].'" score_home="'.$write_sub['score_home'].'"  score_visiting="'.$write_sub['score_visiting'].'"  shoots_home="'.$write_sub['shoots_home'].'" shoots_visiting="'.$write_sub['shoots_visiting'].'" />';
                         }
                      }
                $strXMLextended.='</periods>';
                $strXMLextended.='<goals>';
                      $query_sub2="SELECT * FROM games_goals WHERE id_game=".$write['id']." ORDER by goal_time_min ASC, goal_time_sec ASC";
                      if ($this->con->GetQueryNum($query_sub2)>0){
                        $result_sub2 = $this->con->SelectQuery($query_sub2);
                         while($write_sub2 = $result_sub2->fetch_array()){
                           $StrClub=$this->games->GetActualClubName($write_sub2['id_club'],$id_season);
                           $StrClubNameOriginal=$this->con->GetSQLSingleResult("SELECT 	name_original as item FROM clubs WHERE id=".$write_sub2['id_club']);
                           $strScorerName=$this->con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$write_sub2['scorer']);
                           $strAssist1Name=$this->con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$write_sub2['assist_1']);
                           $strAssist2Name=$this->con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$write_sub2['assist_2']);
                           $strXMLextended.='<goal id="'.$write_sub2['id'].'" team="'.$StrClub.'"  team_original="'.$StrClubNameOriginal.'" minute="'.$write_sub2['goal_time_min'].'" second="'.$write_sub2['goal_time_sec'].'" goal_type="'.$write_sub2['goal_time_sec'].'" scorer="'.$strScorerName.'"  assist_1="'.$strAssist1Name.'" assist_2="'.$strAssist2Name.'" />';
                         }
                      }
                $strXMLextended.='</goals>';
              $strXMLextended.='</game>';
              
          $strXMLextended.='</season>';
          
          if (!empty($id_game)){
            $myFile = "../../xml/games/detail/game_".$id_game.".xml";
            $fh = fopen($myFile, 'w') or die("can't open file");
            fwrite($fh, $strXMLextended);
            fclose($fh);
          }
        
        
     }
    
  }
  
  
  
  
}

?>