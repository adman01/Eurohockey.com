<?php
require_once("../inc/global.inc");
require_once("lang/".Web_language."/photo.php");
require_once("inc/config.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(10,1,0,1,0);

require_once("inc/head.inc");
echo $head->setTitle(langTitle);
echo $head->setJavascriptExtendFile("lang/".Web_language."/photo.js");
echo $head->setJavascriptExtendFile("js/photo.js");
echo $head->setEndHead();

?>
<body onload="javascript:logout('<?php echo $users->sesid ;?>', '<?php echo MaxTimeConnection ;?>')">

<div id="layout">
  <div id="mainmenu">
    <?php require_once("../inc/menu.inc"); ?>
  </div>
  <div id="main">
  
    <h1><?php echo langH1; ?></h1>
    <div class="menicko">
    	 <a href="photo.php<?echo Odkaz;?>"><?echo langMenu4;?></a>
	     <div class="clear">&nbsp;</div>
	  </div>

<form action="photo_action.php" method="post" onsubmit="return validate2(this)">
<fieldset>
	<legend><?php echo langUpdLegend; ?></legend>
	<?php
  if (isset($_GET['id'])) {
  $query="SELECT *,(SELECT folder_name FROM photo_catg WHERE photo_catg.id=photo.id_catg) as folder FROM photo WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  ?>
	<table class="form">
	   <tr>
			<td class="item"><?php echo langAddLabel8; ?></td>
			<td><input type="text" name="signature" maxlength="70" class="long" value="<?php echo $write['signature']; ?>" /></td>
			<td class="small"><?php echo langAddHelp8; ?></td>
		</tr>
		<tr>
			<td class="item"><?php echo langAddLabel1; ?></td>
			<td>
        <input type="text" name="name" value="<?php echo $write['name']; ?>" maxlength="50" class="short" /></td>
			<td class="small"><?php echo langAddHelp1; ?></td>
		</tr>
		<tr>
			<td class="item"><?php echo langAddLabel2; ?></td>
			<td><input type="text" disabled="disabled" class="long" value="<?php echo PhotoFolder."/".$write['folder']."/".$write['file_name']; ?>" /></td>
			<td class="small"><?php echo langAddHelp2; ?></td>
		</tr>
		<tr>
		<tr>
			<td class="item"><?php echo langAddLabel7; ?></td>
			<td>
			  <select name="id_catg">
			  <option value="0"><?php echo langAddSelect7; ?></option>
			  <?php
        $query="SELECT *,(select count(*) from photo WHERE photo.id_catg=photo_catg.id) as number FROM photo_catg ORDER by id DESC";
        $result2 = $con->SelectQuery($query);
        while($write2 = $result2->fetch_array()){
        echo '
        <option value="'.$write2['id'].'" '.write_select($write2['id'],$write['id_catg']).'>'.$write2['name'].' ('.$write2['number'].')</option>
        ';
        }
        ?>
        </select> 
      </td>
      <td class="small"><?php echo langAddHelp7; ?></td>
		</tr>
			<td class="item"><?php echo langAddLabel3; ?></td>
			<td><input type="text" name="description" value="<?php echo $write['description']; ?>" maxlength="255" class="long" /></td>
			<td class="small"><?php echo langAddHelp3; ?></td>
		</tr>
		<tr>
			<td class="item"><?php echo langAddLabel4; ?></td>
			    <td><textarea name="keywords" class="small"><?php echo $write['keywords']; ?></textarea></td>
      <td class="small"><?php echo langAddHelp4; ?></td>
		</tr>
		<tr>
			<td class="item"><?php echo langAddLabel6; ?></td>
			<td>
			    <input type="checkbox" name="show" value="1" <?php if($write['show_item']==1) echo 'checked="checked"'; ?> />
      </td>
			<td class="small"><?php echo langAddHelp6; ?></td>
		</tr>
		<tr><td colspan="3" class="odesilaci"><input type="submit" value="<?php echo langUpdSubmit; ?>" /></td></tr>
	</table>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="id" value="<?php echo $write['id'];?>" />
	<input type="hidden" name="id_catg_old" value="<?php echo $write['id_catg'];?>" />
	<input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	<input type="hidden" name="filter_catg" value="<?php echo $_GET['filter_catg'];?>" />
	<input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
	<input type="hidden" name="order" value="<?php echo $_GET['order'];?>" />
	
	<?echo OdkazForm;?>
		<?php
	}
	else{echo '<p>'.langUpdNodata.' <a href="photo.php'.Odkaz.'">'.langUpdNodataAnchor.'</a></p>';}
  }
  else{echo '<p>'.langUpdNodata.' <a href="photo.php'.Odkaz.'">'.langUpdNodataAnchor.'</a></p>';}
  ?>
</fieldset>	
</form>
    
  </div>
</div>
</body>
</html>
