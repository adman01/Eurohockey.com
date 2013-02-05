<?php
require_once("../inc/global.inc");
require_once("lang/".Web_language."/photo_catg.php");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(10,1,0,0,0);

require_once("inc/head.inc");
echo $head->setTitle(langTitle);
echo $head->setJavascriptExtendFile("lang/".Web_language."/photo_catg.js");
echo $head->setJavascriptExtendFile("js/photo_catg.js");
echo $head->setEndHead();

$games = new games($con)
?>
<body onload="javascript:logout('<?php echo $users->sesid ;?>', '<?php echo MaxTimeConnection ;?>')">

<div id="layout">
  <div id="mainmenu">
    <?php require_once("../inc/menu.inc"); ?>
  </div>
  <div id="main">
   
    <h1><?php echo langH1; ?></h1>
    <div class="menicko">
       <?php if ($users->checkUserRight(2)){?> <a href="photo_catg_add.php<?echo Odkaz;?>"><?php echo langMenu1; ?></a> <?php } ?>
    	 <a href="photo.php<?echo Odkaz;?>"><?php echo langMenu3; ?></a>
    	 <?php if ($users->checkUserRight(2)){?> <a href="photo_add.php<?echo Odkaz;?>"><?php echo langMenu2; ?></a> <?php } ?>
	     <div class="clear">&nbsp;</div>
	  </div>
	 
    <form action="photo_catg.php" method="get">
    <fieldset>
	   <legend><?php echo langFiltr; ?></legend>
	   <table class="form">
		  <tr>
			<td class="item"><?php echo langFiltrName1; ?></td>
			 <td>
         <input type="text" name="filter" class="long" value="<?php echo $_GET['filter']; ?>" />
       </td>
       <td class="odesilaci"><input type="submit" value="<?php echo langFiltrSubmit; ?>" /></td>
			 <td class="small"><?php echo langFiltrInfo; ?></td>
		  </tr>
	 </table>
	 <?echo OdkazForm;?>
  </fieldset>	
  </form>
	 
    <table cellpadding="2" cellspacing=1 class="nice">
	  <?php
    If (!IsSet($_GET['order']) OR ($_GET['order'] == ""))	$order="name"; else $order=$_GET['order'];
    If (IsSet($_GET['filter']) and empty($_GET['filter'])==false)	$filter=" AND (name LIKE '%".$_GET['filter']."%' or description LIKE '%".$_GET['filter']."%')"; else $filter="";
    $query="SELECT *,(select count(*) from photo WHERE photo.id_catg=photo_catg.id) as number from photo_catg WHERE id>0 ".$filter." ORDER BY ".$order."";
    $Odkaz2="&amp;filter=".$_GET['filter']."&amp;list_number=".$_GET['list_number'];
    //echo $query; 
     //listovani
    $listovani = new listing($con,"photo_catg.php".Odkaz.$Odkaz2."&amp;order=".$order."&amp;",30,$query,1,"",$_GET['list_number']);
    $query=$listovani->updateQuery();
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    
    echo '<tr>';
      echo '<th><a href="photo_catg.php'.Odkaz.$Odkaz2.'&amp;order=';
      If ($order<>"name ASC") {
				echo "name ASC";
			} else {
				echo "name DESC";
			}
      echo '">'.langTH1.'</a></th>';
      
      echo '<th><a href="photo_catg.php'.Odkaz.$Odkaz2.'&amp;order=';
      If ($order<>"description ASC") {
				echo "description ASC";
			} else {
				echo "description DESC";
			}
      echo '">'.langTH2.'</a></th>';
      echo '<th>'.langTH3.'</th>';
      
      if (PhotoGame==true) {
      echo '<th><a href="photo_catg.php'.Odkaz.$Odkaz2.'&amp;order=';
      If ($order<>"id_game ASC") {
				echo "id_game ASC";
			} else {
				echo "id_game DESC";
			}
      echo '">'.langTH5.'</a></th>';
      }
      
      if (PhotoPassword==true){
      echo '<th><a href="photo_catg.php'.Odkaz.$Odkaz2.'&amp;order=';
      If ($order<>"is_password ASC") {
				echo "is_password ASC";
			} else {
				echo "is_password DESC";
			}
      echo '">'.langTH4.'</a></th>';
      }
      echo '<th colspan="2"></th></tr>';
    
    while($write = $result->fetch_array())
	   {
	     $number=$write['number'];
	     if ($a%2) $style="color1"; else $style="color2";
  		 echo '<tr onmouseover="ZmenTridu(\'zvyraznene\',\'row_'.$write['id'].'\')"" onmouseout="ZmenTridu(\''.$style.'\',\'row_'.$write['id'].'\')" id="row_'.$write['id'].'" class="'.$style.'">';
  		  echo '<td class="bold"><a href="photo.php'.Odkaz.'&amp;filter_catg='.$write["id"].'" title="'.langDocsDetails.'">'.$write['name'].' ('.$write['number'].')</a></td>';
  		  echo '<td>'.$write['description'].'</td>';
  		  if ($write['show_item']==0) {$button=" red";} else{$button=" white";}
          echo'<td class="button'.$button.'">';
          if ($users->checkUserRight(3)) echo'<a href="photo_catg_action.php'.Odkaz.$Odkaz2.'&amp;order='.$order.'&amp;id='.$write["id"].'&amp;action=switch&amp;list_number='.$_GET['list_number'].'" title="'.langAnchorSwitch.'">';
          echo show_boolean($write['show_item']);
          if ($users->checkUserRight(3)) echo'</a>';
          echo'</td>';
        if (PhotoGame==true) {
  		    if ($write["id_game"]==0){echo'<td class="button"><a href="'.$write["id_catg"].'.php'.Odkaz.$Odkaz2.'">?</a></td>';}
          else{echo'<td class="button"><a class="game small" href="../mod_games/games_detail.php'.Odkaz.$Odkaz2.'&amp;id='.$write["id_game"].'" title="'.langAnchorFilter1.' '.$games->GetGameClubNames($write['id_game'],ActSeason,1," vs. ").'">'.$games->GetGameClubNames($write['id_game'],ActSeason,2," vs. ").'</a></td>';}
        }
          if ($write['is_password']==0) {$button=" red";} else{$button=" white";}
          if (PhotoPassword==true) echo'<td class="button'.$button.'">'.show_boolean($write['is_password']).'</td>';
        echo'  
        <td class="update">';
          if ($users->checkUserRight(3)) echo'<a href="photo_catg_update.php'.Odkaz.$Odkaz2.'&amp;order='.$order.'&amp;id='.$write["id"].'" title="'.langAnchorChange.'">&nbsp;</a>';
        echo '</td>';
        echo'<td class="delete">';
        if ($number<1){
          if ($users->checkUserRight(4)) echo'<a href="javascript:delete_item(\''.$users->getSesid().'\','.$write["id"].',\''.$write["name"].'\')" title="'.langAnchorDelete.'">&nbsp;</a>';
          }else{
          echo $number.' '.langDocsNumber;
        }
        echo'</td>';
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
  </div>
</div>
</body>
</html>