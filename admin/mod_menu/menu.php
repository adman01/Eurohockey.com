<?php
require_once("../inc/global.inc");
require_once("lang/".Web_language."/menu.php");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(5,1,0,0,0);

require_once("inc/head.inc");
echo $head->setTitle(langTitle);
echo $head->setJavascriptExtendFile("lang/".Web_language."/menu.js");
echo $head->setJavascriptExtendFile("js/menu.js");
echo $head->setEndHead();

function show_menu($id,$uroven,$con,$users){
    for ($i=0;$i<=$uroven;$i++){$predpona=$predpona."&mdash;&mdash;";}
    $uroven=$uroven+1;
    $query="SELECT * FROM main_menu WHERE id_up=".$id." ORDER BY id_up, order_item";
    $result = $con->SelectQuery($query);
    while($write = $result->fetch_array()){
        echo '<tr>
          <td class="center">';
          if ($write['id_language']==2) echo "ENG"; else  echo "CZE";
          echo '</td>';
          echo '<td>'. $predpona.'&raquo; <a href="menu_update.php'.Odkaz.'&amp;id='.$write["id"].'" title="'.langAnchorChange.'">'.$write['name'].'</a></td>
          <td class="left">'.$write['url'].'</td>
          <td class="center">'.$write['order_item'].'</td>';
          
          if ($write['show_item']==0) {$button=" red";} else{$button=" white";}
          echo'<td class="button'.$button.'">';
          if ($users->checkUserRight(3)) echo'<a href="menu_action.php'.Odkaz.'&amp;id='.$write["id"].'&amp;action=switch&amp;list_number='.$_GET['list_number'].'" title="'.langAnchorSwitch.'">';
          echo show_boolean($write['show_item']); 
          if ($users->checkUserRight(3)) echo'</a>';
          echo'</td>';
          echo'<td class="update">';
            if ($users->checkUserRight(3)) echo'<a href="menu_update.php'.Odkaz.'&amp;id='.$write["id"].'" title="'.langAnchorChange.'">&nbsp;</a>';
            echo '</td>';
          $query2="SELECT id FROM main_menu WHERE id_up=".$write["id"];
          echo'<td class="delete">';
          if ($con->GetQueryNum($query2)==0){
            if ($users->checkUserRight(4)) echo'<a href="javascript:delete_item(\''.$users->getSesid().'\','.$write["id"].',\''.$write["name"].'\')" title="'.langAnchorDelete.'">&nbsp;</a>';
          }
          else {echo $con->GetQueryNum($query2)."x"; }
          echo'</td>';
          
         show_menu($write['id'],$uroven,$con,$users);
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
    	 <?php if ($users->checkUserRight(2)){?> <a href="menu_add.php<?echo Odkaz;?>"><?php echo langMenu1; ?></a> <?php } ?>
	     <div class="clear">&nbsp;</div>
	  </div>
	 
    <table cellpadding="2" cellspacing=1 class="nice">
    <tr>
      <th><?php echo langTH6 ;?></th>
      <th><?php echo langTH1 ;?></th> 
      <th><?php echo langTH3 ;?></th>
      <th><?php echo langTH4 ;?></th>
      <th><?php echo langTH5 ;?></th>
      <th colspan="2">&nbsp;</th></tr>
	  <?php
    show_menu(0,0,$con,$users);
    ?>
    </table>
  </div>
</div>
</body>
</html>