<?php
require_once("../inc/global.inc");
require_once("lang/".Web_language."/photo_catg.php");
require_once("inc/config.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(10,1,0,1,0);

require_once("inc/head.inc");
echo $head->setTitle(langTitle);
echo $head->setJavascriptExtendFile("lang/".Web_language."/photo_catg.js");
echo $head->setJavascriptExtendFile("js/photo_catg.js");
echo $head->setEndHead();

//kostruktor pro tridu games
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
    	 <a href="photo_catg.php<?echo Odkaz;?>"><?echo langMenu4;?></a>
	     <div class="clear">&nbsp;</div>
	  </div>
	  
<form action="photo_catg_action.php" method="post" onsubmit="return validate2(this)">
<fieldset>
	<legend><?echo langUpdLegend;?></legend>
	<?php
  if (isset($_GET['id'])) {
  $query="SELECT * FROM photo_catg WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  ?>
  
  <table class="form">
		<tr>
			<td class="item"><?php echo langAddLabel1; ?></td>
			<td>
        <input type="text" name="name" maxlength="50" class="short" value="<?php echo $write['name']; ?>" /></td>
			<td class="small"><?php echo langAddHelp1; ?></td>
		</tr>
		<tr>
			<td class="item"><?php echo langAddLabel2; ?></td>
			<td><input type="text" name="description" maxlength="255" value="<?php echo $write['description']; ?>" class="long" /></td>
			<td class="small"><?php echo langAddHelp2; ?></td>
		</tr>
		<?php if (PhotoGame==true) { ?>
		<tr>
			<td class="item"><?php echo langAddLabel7; ?></td>
			<td>
			  <input class="readonly" type="text" name="id_game_name" id="id_game_name" maxlength="255" class="small"  value="<?php echo $games->GetGameClubNames($write['id_game'],ActSeason,2," vs. "); ?> (<?php echo $write['id_game']; ?>)" readonly="readonly" />
        <input type="hidden" name="id_game" id="id_game" maxlength="15" class="small" readonly="readonly" value="<?php echo $write['id_game']; ?>" />
        <a href="javascript:select_games('id_game','<?php echo $users->getSesid(); ?>');" class="game" title="<?php echo langAddChoose2; ?>"><?php echo langAddChoose2; ?></a>
      </td>
			<td class="small"><?php echo langAddHelp7; ?></td>
		</tr>
		<?php } ?>
		<?php if (PhotoPassword==true) { ?>
		<tr>
			<td class="item"><?php echo langAddLabel3; ?></td>
			<td>
			    <input type="checkbox" name="is_password" value="1" <?php if($write['is_password']==1) echo 'checked="checked"'; ?> />
      </td>
			<td class="small"><?php echo langAddHelp3; ?></td>
		</tr>
		<tr>
			<td class="item"><?php echo langUpdLabel3; ?></td>
			<td>
			    <input type="checkbox" name="is_check" value="1" onclick="show_item_check('password'); show_item_check('password2');" />
      </td>
			<td class="small"><?php echo langUpdHelp3; ?></td>
		</tr>
		<tr id="password"  <?php echo 'class="hidden"'; ?>>
			<td class="item"><?php echo langAddLabel4; ?></td>
		  <td><input type="password" name="password" maxlength="50" class="short" /></td>
			<td class="small"><?php echo langAddHelp4; ?></td>
		</tr>
		<tr id="password2"  <?php echo 'class="hidden"'; ?>>
			<td class="item"><?php echo langAddLabel5; ?></td>
			<td><input type="password" name="password_check" maxlength="50" class="short" /></td>
			<td class="small"><?php echo langAddHelp5; ?></td>
		</tr>
		<?php } ?>
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
	<input type="hidden" name="autor" value="<?echo $users->GetUserName($users->sesid); ?>" />
	<input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	<input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
	<input type="hidden" name="order" value="<?php echo $_GET['order'];?>" />
	<?echo OdkazForm;?>
	<?php
	}
	else{echo '<p>'.langUpdNodata.' <a href="photo_catg.php'.Odkaz.'">'.langUpdNodataAnchor.'</a></p>';}
  }
  else{echo '<p>'.langUpdNodata.' <a href="photo_catg.php'.Odkaz.'">'.langUpdNodataAnchor.'</a></p>';}
  ?>
</fieldset>	
</form>
    
  </div>
</div>
</body>
</html>
