<?php
require_once("../inc/global.inc");
require_once("lang/".Web_language."/menu.php");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(5,1,0,1,0);
require_once("inc/head.inc");
echo $head->setTitle(langTitle);
echo $head->setJavascriptExtendFile("lang/".Web_language."/menu.js");
echo $head->setJavascriptExtendFile("js/menu.js");
echo $head->setEndHead();

function show_menu($id,$uroven,$id_select,$con,$users){
    for ($i=0;$i<=$uroven;$i++){$predpona=$predpona."&mdash;&mdash;";}
    $uroven=$uroven+1;
    $query="SELECT * FROM main_menu WHERE id_up=".$id." ORDER BY id_up, order_item";
     $result = $con->SelectQuery($query);
    while($write = $result->fetch_array()){
        if ($id_select==$write['id']){$sel=' selected="selected"';}else{$sel='';}
        echo '<option value="'.$write['id'].'"'.$sel.'>'. $predpona.'&raquo; '.$write['name'].'</option><br />';
        show_menu($write['id'],$uroven,$id_select,$con,$users);
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
    	 <a href="menu.php<?echo Odkaz;?>"><?echo langMenu4;?></a>
	     <div class="clear">&nbsp;</div>
	  </div>
	  
<form action="menu_action.php" method="post" onsubmit="return validate(this)">
<fieldset>
	<legend><?echo langUpdLegend;?></legend>
	<?php
  if (isset($_GET['id'])) {
  $query="SELECT * FROM main_menu WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  ?>
	<table class="form">
		<tr>
			<td class="item"><?php echo langAddLabel1; ?></td>
			<td><input type="text" name="name" maxlength="255" class="long" value="<?php echo $write['name']; ?>" /></td>
			<td class="small"><?php echo langAddHelp1; ?></td>
		</tr>
		<tr>
			<td class="item"><?php echo langAddLabel2; ?></td>
			<td><input type="text" name="url" maxlength="255" class="long" value="<?php echo $write['url']; ?>" /></td>
			<td class="small"><?php echo langAddLabel2; ?></td>
		</tr>
			<tr>
			<td class="item"><?php echo langAddLabel3; ?></td>
			<td>
			<select name="id_level">
			  <option value="0"><?php echo langAddSelect3; ?></option>
			  <?php
        show_menu(0,0,$write['id_up'],$con,$users);
        ?>
      /select>
      </td>
			<td class="small"><?php echo langAddHelp3; ?></td>
		</tr>
		
		<tr>
			<td class="item"><?php echo langAddLabel4; ?></td>
			<td><input type="text" name="order_item" maxlength="2" class="short"  value="<?php echo $write['order_item']; ?>" /></td>
      <td class="small"><?php echo langAddHelp4; ?></td>
		</tr>
		<tr>
			<td class="item">Jazyková verze</td>
			<td>
        <select name="id_language">
			   <option value="1" <?php if($write['id_language']==1) echo 'selected="selected"'; ?>>CZE</option>
			   <option value="2" <?php if($write['id_language']==2) echo 'selected="selected"'; ?>>ENG</option>
        </select>
      </td>
      <td class="small">zvolte do které jazykové verze menu patří</td>
		</tr>
		
		<tr>
			<td class="item"><?php echo langAddLabel5; ?></td>
			<td><input type="checkbox" name="show" value="1" <?php if ($write['show_item']==1) echo 'checked="checked"';?> /></td>
			<td class="small"><?php echo langAddHelp5; ?></td>
		</tr>
	
		<tr><td colspan="3" class="odesilaci"><input type="submit" value="<?php echo langUpdSubmit; ?>" /></td></tr>
	</table>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="id" value="<?php echo $write['id'];?>" />
	<?echo OdkazForm;?>
	<?php
	}
	else{echo '<p>'.langUpdNodata.' <a href="menu.php'.Odkaz.'">'.langUpdNodataAnchor.'</a></p>';}
  }
  else{echo '<p>'.langUpdNodata.' <a href="menu.php'.Odkaz.'">'.langUpdNodataAnchor.'</a></p>';}
  ?>
</fieldset>	
</form>
    
  </div>
</div>
</body>
</html>
