<?php
//trida pro praci s databazi
class database extends mysqli{
  public $statement;
	public $latestdb;
  
  public function database( $hostname = 'localhost',  $dbname = null, $username = 'root', $password = '',$port = null, $socket = null )
		{
			$this->mysqli($hostname, $username, $password, $dbname, $port, $socket);
		}
  
  public function __destruct(){
    $this->close;
  } 
  
  public function select_db( $dbname )
		{
			if( parent::select_db($dbname) )
			{
				$this->latestdb = $dbname;
				return true;
			}
			else
				return false;
		}
	
	public function query( $query, $checkdb = true )
		{
			if( $checkdb && stripos( $query, 'use ' ) !== false )
			{
				if( parent::query( $query ) )
				{
					$this->latestdb = substr( trim($query), 4 );
				}
				else
					parent::query( $query );
			}
			else
				return parent::query( $query );
		}
  
  //nastavi znakovou sadu
  function setCharset($charset){
     $this->set_charset($charset);
  }
  
  //vrati pocet vysledku dotazu
  function GetQueryNum($query){
    $this->statement=$query;
    $result = $this->query($query);
    if ($result){
      $row=$result->num_rows;
      $result->close();
      return $row; 
    }
    return 0;
  }
  
  //provede sql dotaz / vraci boolean
  function RunQuery($query){
      $this->statement=$query;
      if ($this->query( $this->statement)){
        return true;
      }
      else return false;
  }
  
  //provede sql dotaz a vraci pole s vysledky
  function SelectQuery($query){
    $this->statement = $query;
	  $result=$this->query( $this->statement );
	  if ($result){return $result;} else {return false;}
	  
  }
  
 function GetSQLSingleResult($query){
  if ($this->GetQueryNum($query)>0){
    $result_sub = $this->SelectQuery($query);
    $write_sub = $result_sub->fetch_array();
    $result=$write_sub['item'];
  }
  return $result;
}

  
  
}
?>