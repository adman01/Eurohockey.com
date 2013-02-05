<?php
require_once("../inc/global.inc");
require_once("lang/".Web_language."/photo.php");
require_once("inc/config.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
//$users->setUserRight(8,1,1,0,0);

require_once("inc/head.inc");
echo $head->setTitle(langTitle);
echo $head->setJavascriptExtendFile("lang/".Web_language."/photo.js");
echo $head->setJavascriptExtendFile("js/photo.js");
echo $head->setEndHead();
$id_input=$_GET['id_input'];

//pokud je zvolena catg vraci nazev adresare
function getCatg($id_catg,$con){
  if ($id_catg<>0){
       $query="SELECT folder_name FROM photo_catg WHERE id=".$id_catg;
       $result = $con->SelectQuery($query);
       $write = $result->fetch_array();
       return $write['folder_name'];
  }else{
    return "";
  }
}

?>
<body class="window">
<div id="layout" class="window">
  <div id="main" class="window">
  <p class="center"><a href="#" onclick="self.close()">[<?php echo langWindow5;?>]</a></p>
  <hr />
  <h3><?php echo langWindow1;?></h3>
  
  <form action="photo_window_select.php" method="get">
    <fieldset>
	   <legend><?php echo langFiltr; ?></legend>
	   <table class="form" style="width:auto">
		  <tr>
		   <td>
        <select name="filter_catg" style="width:150px">
			  <option value="0"><?php echo langFiltrOption; ?></option>
			  <?php
			   $query="SELECT *,(select count(*) from photo WHERE photo.id_catg=photo_catg.id) as number FROM photo_catg ORDER BY id DESC";
        $result = $con->SelectQuery($query);
        while($write = $result->fetch_array()){
        echo '
        <option value="'.$write['id'].'" '.write_select($write['id'],$_GET['filter_catg']).'>'.$write['name'].' ('.$write['number'].')</option>
        ';
        }
        ?>
        </select> 
       </td>
			 <td>
         <input type="text" name="filter" class="short" value="<?php echo $_GET['filter']; ?>" />
         <input type="hidden" name="id_input" value="<?php echo $_GET['id_input']; ?>" />
         
       </td>
       <td class="odesilaci"><input type="submit" value="<?php echo langFiltrSubmit; ?>" /></td>
		  </tr>
	 </table>
	 
	 <?echo OdkazForm;?>
  </fieldset>	
  </form>
  
    <table cellpadding="2" cellspacing=1 class="nice">
	  <?php
    If (!IsSet($_GET['order']) OR ($_GET['order'] == ""))	$order="date_time DESC, id DESC"; else $order=$_GET['order'];
    If (IsSet($_GET['filter']) and empty($_GET['filter'])==false)	$filter=" AND (name LIKE '%".$_GET['filter']."%' or description LIKE '%".$_GET['filter']."%'  or keywords LIKE '%".$_GET['filter']."%')"; else $filter="";
    If (IsSet($_GET['filter_catg']) and empty($_GET['filter_catg'])==false)	$filter_catg=" AND id_catg=".$_GET['filter_catg']; else $filter_catg="";
    $query="SELECT * from photo WHERE id>0 ".$filter." ".$filter_catg." ORDER BY ".$order."";
    //echo $query;
    $Odkaz2="&amp;filter=".$_GET['filter']."&amp;filter_catg=".$_GET['filter_catg'].'&amp;list_number='.$_GET['list_number'].'&amp;id_input='.$_GET['id_input']; 
     //listovani
    $listovani = new listing($con,"photo_window_select.php".Odkaz.$Odkaz2."&amp;order=".$order."&amp;",30,$query,1,"",$_GET['list_number']);
    $query=$listovani->updateQuery();
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    
    echo '<tr>';
      echo '<th><a href="photo_window_select.php'.Odkaz.$Odkaz2.'&amp;order=';
      If ($order<>"date_time ASC") {
				echo "date_time ASC";
			} else {
				echo "date_time DESC";
			}
      echo '">'.langTH1.'</a></th>';
      
      echo '<th><a href="photo_window_select.php'.Odkaz.$Odkaz2.'&amp;order=';
      If ($order<>"name ASC") {
				echo "name ASC";
			} else {
				echo "name DESC";
			}
      echo '">'.langTH2.'</a></th>';
      echo '<th>'.langTH5.'</a></th>';
      echo '<th colspan="3"></th></tr>';
      echo '<tr>';
  		  echo '<td colspan="4" class="center bold">'.langWindow7.'</td>';
        echo'<td class="select"><a href="#" onclick="self.opener.chooseItem(0,\''.langWindow7.'\',\''.$id_input.'\'); self.close();" title="'.langWindow3.'">&nbsp;</a></td>';
      echo "</tr>\n";
    while($write = $result->fetch_array())
	   {
	     if ($a%2) $style="color1"; else $style="color2";
	     $imgPath='../..'.PhotoFolder."/".getCatg($write['id_catg'],$con).'/'.$write['file_name'];
  		 echo '<tr onmouseover="ZmenTridu(\'zvyraznene\',\'row_'.$write['id'].'\')"" onmouseout="ZmenTridu(\''.$style.'\',\'row_'.$write['id'].'\')" id="row_'.$write['id'].'" class="'.$style.'">';
  		  echo '<td style="width:30px;">'.date("d.m.",strtotime($write['date_time'])).'</td>';
  		  echo '<td class="bold"><a href="javascript:show_item_check(\'box'.$write["id"].'\');" title="">'.$write['name'].'</td>';
  		      $imgSize=getimagesize($imgPath);
          echo '<td class="update">'.$imgSize[0].'x'.$imgSize[1].'</td>';
          echo'<td class="image"><a href="javascript:show_item_check(\'box'.$write["id"].'\');" title="'.langWindow2.'">&nbsp;</a></td>';
          echo'<td class="select"><a href="#" onclick="self.opener.chooseItem('.$write["id"].',\''.$write["name"].'\',\''.$id_input.'\'); self.close();" title="'.langWindow3.'">&nbsp;</a></td>';
        echo "</tr>\n";
        echo "<tr>\n";
        echo '<td colspan="10" class="hidden" id="box'.$write["id"].'">
          <div class="center"><a href="javascript:okno(\''.$imgPath.'\')" title="'.langWindow4.'"><img src="show_img.php?filename='.$imgPath.'&amp;width=300&amp;height=200" class="border" /></a></div>
          <p><strong>'.langWindow6.'</strong> '.$write["description"].'</p>
          ';
          
          echo'
        </td>';
        echo "</tr>\n";
  		 $a++;
	   }
	  }else{
      echo '<tr><td colspan="10" class="center">'.langNodata.'</td></tr>';
    }
    ?>
    </table>
     <?
    //listovani
    $listovani->show_list();
    ?>
  
  <hr />
  <p class="center"><a href="#" onclick="self.close()">[<?php echo langWindow5;?>]</a></p>
  </div>
</div>
</body>
</html>
