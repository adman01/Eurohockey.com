<?php
header("Content-Type: text/xml"); 
require_once("inc/global.inc");
$input = new Input_filter();
//get the q parameter from URL
$q=$_POST["q"];
$q=$input->check_number($q);

echo '<livesearch stageBox="'.$_POST["stageBox"].'" stageID="'.$_POST["stageID"].'">';
if (strlen($q) > 0)
{
  $query="SELECT name,id FROM games_stages WHERE id_league=".$q." ORDER by id";
  $result = $con->SelectQuery($query);
  
  while($write = $result->fetch_array())
	{
	        $id_stage=$write["id"]; 
	        $intIDstageDefault=$con->GetSQLSingleResult("SELECT id_detault as item FROM games_stages WHERE id=".$id_stage);
          if (!empty($intIDstageDefault)) {$id_stage=$intIDstageDefault;}
	         
	        echo '<item id="'.$id_stage.'">'.$write["name"].'</item>';
  }
  }
echo "</livesearch>\n";
?>