<?php
require_once("../inc/global.inc");
require_once("lang/".Web_language."/photo.php");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(10,1,0,0,0);

require_once("inc/head.inc");
echo $head->setTitle(langTitle);
echo $head->setJavascriptExtendFile("lang/".Web_language."/photo.js");
echo $head->setJavascriptExtendFile("js/photo.js");
echo $head->setEndHead();

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
<body onload="javascript:logout('<?php echo $users->sesid ;?>', '<?php echo MaxTimeConnection ;?>')">

<div id="layout">
  <div id="mainmenu">
    <?php require_once("../inc/menu.inc"); ?>
  </div>
  <div id="main">
   
    <h1><?php echo langH1; ?></h1>
    <div class="menicko">
       <?php if ($users->checkUserRight(2)){?> <a href="photo_add.php<?echo Odkaz;?>"><?php echo langMenu1; ?></a> <?php } ?>
    	 <a href="photo_catg.php<?echo Odkaz;?>"><?php echo langMenu3; ?></a>
    	 <?php if ($users->checkUserRight(2)){?> <a href="photo_catg_add.php<?echo Odkaz;?>"><?php echo langMenu2; ?></a> <?php } ?>
	     <div class="clear">&nbsp;</div>
	  </div>
	 
    <form action="photo.php" method="get">
    <fieldset>
	   <legend><?php echo langFiltr; ?></legend>
	   <table class="form">
		  <tr>
		  <td class="item"><?php echo langFiltrName2; ?></td>
			 <td>
        <select name="filter_catg">
			  <option value="0"><?php echo langFiltrOption; ?></option>
			  <?php
			   $query="SELECT *,(select count(*) from photo WHERE photo.id_catg=photo_catg.id) as number FROM photo_catg ORDER by id DESC";
        $result = $con->SelectQuery($query);
        while($write = $result->fetch_array()){
        echo '
        <option value="'.$write['id'].'" '.write_select($write['id'],$_GET['filter_catg']).'>'.$write['name'].' ('.$write['number'].')</option>
        ';
        }
        ?>
        </select> 
       </td>
			<td class="item"><?php echo langFiltrName1; ?></td>
			 <td>
         <input type="text" name="filter" class="short" value="<?php echo $_GET['filter']; ?>" />
       </td>
       <td class="odesilaci"><input type="submit" value="<?php echo langFiltrSubmit; ?>" /></td>
		  </tr>
	 </table>
	 <?echo OdkazForm;?>
  </fieldset>	
  </form>
	 
	 <form action="photo_action.php" method="post" onsubmit="return validate3(this)">
    <table cellpadding="2" cellspacing=1 class="nice">
	  <?php
    If (!IsSet($_GET['order']) OR ($_GET['order'] == ""))	$order="last_update DESC"; else $order=$_GET['order'];
    If (IsSet($_GET['filter']) and empty($_GET['filter'])==false)	$filter=" AND (name LIKE '%".$_GET['filter']."%' or description LIKE '%".$_GET['filter']."%'  or keywords LIKE '%".$_GET['filter']."%')"; else $filter="";
    If (IsSet($_GET['filter_catg']) and empty($_GET['filter_catg'])==false)	$filter_catg=" AND id_catg=".$_GET['filter_catg']; else $filter_catg="";
    $query="SELECT * from photo WHERE id>0 ".$filter." ".$filter_catg." ORDER BY ".$order."";
    //echo $query;
    $Odkaz2="&amp;filter=".$_GET['filter']."&amp;filter_catg=".$_GET['filter_catg'].'&amp;list_number='.$_GET['list_number']; 
     //listovani
    $listovani = new listing($con,"photo.php".Odkaz.$Odkaz2."&amp;order=".$order."&amp;",50,$query,2,"",$_GET['list_number']);
    $query=$listovani->updateQuery();
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    
    echo '<tr>';
      echo '<th></th>';
      echo '<th><a href="photo.php'.Odkaz.$Odkaz2.'&amp;order=';
      If ($order<>"date_time ASC") {
				echo "date_time ASC";
			} else {
				echo "date_time DESC";
			}
      echo '">'.langTH1.'</a></th>';
      
      echo '<th><a href="photo.php'.Odkaz.$Odkaz2.'&amp;order=';
      If ($order<>"name ASC") {
				echo "name ASC";
			} else {
				echo "name DESC";
			}
      echo '">'.langTH2.'</a></th>';
      
      echo '<th>'.langTH3.'</th>';
      
      echo '<th><a href="photo.php'.Odkaz.$Odkaz2.'&amp;order=';
      If ($order<>"id_catg ASC") {
				echo "id_catg ASC";
			} else {
				echo "id_catg DESC";
			}
			echo '">'.langTH4.'</a></th>';
			
			echo '<th>'.langTH5.'</th>';
			
      echo '<th colspan="3">&nbsp;</th></tr>';
    while($write = $result->fetch_array())
	   {
	     if ($a%2) $style="color1"; else $style="color2";
	     $imgPath='../..'.PhotoFolder."/".getCatg($write['id_catg'],$con).'/'.$write['file_name'];
  		 echo '<tr onmouseover="ZmenTridu(\'zvyraznene\',\'row_'.$write['id'].'\')"" onmouseout="ZmenTridu(\''.$style.'\',\'row_'.$write['id'].'\')" id="row_'.$write['id'].'" class="'.$style.'">';
  		  echo '<td class="update"><input type="checkbox" id="checkbox" name="id['.$a.']" value="'.$write['id'].'" /></td>';
  		  echo '<td style="width:30px;">'.date("d.m.",strtotime($write['date_time'])).'</td>';
  		  echo '<td class="bold"><a href="javascript:okno(\''.$imgPath.'\')" title="">'.$write['name'].'</a></td>';
  		    if ($write['show_item']==0) {$button=" red";} else{$button=" white";}
          echo'<td class="button'.$button.'">';
          if ($users->checkUserRight(3)) echo '<a href="photo_action.php'.Odkaz.$Odkaz2.'&amp;order='.$order.'&amp;id='.$write["id"].'&amp;action=switch&amp;list_number='.$_GET['list_number'].'" title="'.langAnchorSwitch.'">';
          echo show_boolean($write['show_item']);
          if ($users->checkUserRight(3)) echo '</a>';
          echo '</td>';
          if ($write["id_catg"]==0){echo'<td class="button"><a href="photo.php'.Odkaz.$Odkaz2.'&amp;order='.$order.'&amp;filter_catg='.$write["id_catg"].'" title="'.langAnchorFilter.'">?</a></td>';}
            else{echo'<td class="button"><a class="catg_number" href="photo.php'.Odkaz.$Odkaz2.'&amp;order='.$order.'&amp;filter_catg='.$write["id_catg"].'" title="'.langAnchorFilter.'">'.$write["id_catg"].'</a></td>';}
            $imgSize=getimagesize($imgPath);
          echo '<td class="download">'.$imgSize[0].'x'.$imgSize[1].'</td>';
          echo '<td class="image"><a href="javascript:okno(\''.$imgPath.'\')" title="">&nbsp;</a></td>';
        echo'
        <td class="update">';
          if ($users->checkUserRight(3)) echo'<a href="photo_update.php'.Odkaz.$Odkaz2.'&amp;order='.$order.'&amp;id='.$write["id"].'" title="'.langAnchorChange.'">&nbsp;</a>';
        echo '</td>';
        echo'<td class="delete">';
          if ($users->checkUserRight(4)) echo'<a href="javascript:delete_item(\''.$users->getSesid().'\','.$write["id"].',\''.$write["name"].'\')" title="'.langAnchorDelete.'">&nbsp;</a>';
        echo'</td>';
        echo "</tr>\n";
  		 $a++;
	   }
	  }else{
      echo '<tr><td colspan="10" class="center">'.langNodata.'</td></tr>';
    }
    ?>
    <tr>
    <td class="update"><input type="checkbox" id="check_all" name="check_all" onclick="CheckAll(this.form,'checkbox');" /></td>
    <td colspan="10">
      <?php echo langSelectAll;?> 
      <select name="action_all" id="action_all" class="small" onchange="whenIsSelected();">
        <option value=""><?php echo langSelectAllOption1;?></option>
        <?php if ($users->checkUserRight(3)){ ?>
        <option value="update_all"><?php echo langSelectAllOption2;?></option>
        <option value="remove_all"><?php echo langSelectAllOption3;?></option>
        <?php } 
        if ($users->checkUserRight(4)){
        ?>
        <option value="delete_all"><?php echo langSelectAllOption4;?></option>
        <?php } ?>
      </select>
      <select name="remove_list" id="remove_list" class="small hidden">
        <option value="0"><?php echo langSelectAllOption5;?></option>
        <?php
        $query="SELECT *,(select count(*) from photo WHERE photo.id_catg=photo_catg.id) as number FROM photo_catg ORDER by id DESC";
        $result = $con->SelectQuery($query);
        while($write = $result->fetch_array()){
        echo '
        <option value="'.$write['id'].'">'.$write['name'].' ('.$write['number'].')</option>
        ';
        }
        ?>
      </select>
       <input type="submit"  class="small" value="proveď akci" />
       <input type="hidden" name="id_catg_old" value="<?php echo $write['id_catg'];?>" />
       <input type="hidden" name="number" value="<?php echo $a; ?>" />
       <input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	     <input type="hidden" name="filter_catg" value="<?php echo $_GET['filter_catg'];?>" />
	     <input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
	     <input type="hidden" name="order" value="<?php echo $_GET['order'];?>" />
      <?echo OdkazForm;?>
    </td>
    </tr>
    </table>
  </form>
     <?
    //listovani
    $listovani->show_list();
    ?>
  </div>
</div>
</body>
</html>