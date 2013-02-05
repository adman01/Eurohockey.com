<?php
require_once("../inc/global.inc");
require_once("lang/".Web_language."/photo.php");
require_once("inc/config.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(10,1,1,0,0);

require_once("inc/head.inc");
echo $head->setTitle(langTitle);
echo $head->setJavascriptExtendFile("lang/".Web_language."/photo.js");
echo $head->setJavascriptExtendFile("js/photo.js");
echo $head->setEndHead();

if (empty($_GET['files_num'])) {$files_num=2;} else {$files_num=$_GET['files_num'];}
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

<form action="photo_add.php" method="get">
<fieldset>
	<legend><?php echo langAddNumLegend; ?></legend>
	<table class="form">
		<tr>
			<td class="item"><?php echo langAddNumLabel1; ?></td>
			<td>
         <select name="files_num" size="1" style="width:200px">
		      <?php
		      for ($i=1; $i<=PhotoFileMaxUpload; $i++){
		        ?>
			       <option value="<?php echo $i;?>" <?php echo write_select($i,$files_num);?>><?php echo $i;?></option>
  		      <?php
	 	       }
	 	     ?>
	   	   </select>      
      </td>
			<td class="small"><?php echo langAddNumHelp1; ?></td>
		</tr>
		<tr><td colspan="3" class="small"><?php echo langAddTip; ?></td></tr>
		
	  <tr><td colspan="3" class="odesilaci"><input type="submit" value="<?php echo langNumSubmit; ?>" /></td></tr>
	</table>
	<input type="hidden" name="action" value="add" />
	
	<?echo OdkazForm;?>
</fieldset>	
</form>

<form action="photo_action.php" enctype="multipart/form-data" method="post" onsubmit="return validate(this)">
<fieldset>
	<legend><?php echo langAddLegend; ?></legend>
	
	<table class="form">
	 <?php
	for ($i=1; $i<=$files_num; $i++){
	?>
	<tr>
			<td class="item" style="text-align:left" colspan="3"><b class="yellow"><?php echo langAddLabeFile." ".$i; ?></b></td>
		</tr>
		<tr>
			<td class="item"><?php echo langAddLabel8; ?></td>
			<td><input type="text" name="signature[<?php echo $i; ?>]" maxlength="70" class="long" value="<?echo $users->GetUserSignature($users->sesid); ?>" /></td>
			<td class="small"><?php echo langAddHelp8; ?></td>
		</tr>
		<tr>
			<td class="item"><?php echo langAddLabel1; ?></td>
			<td>
        <input type="text" name="name[<?php echo $i; ?>]" id="name[<?php echo $i; ?>]" maxlength="50" class="short" /></td>
			<td class="small"><?php echo langAddHelp1; ?></td>
		</tr>
		<tr>
			<td class="item"><?php echo langAddLabel2; ?></td>
			<td><input type="file" name="file_name[<?php echo $i; ?>]" id="file_name[<?php echo $i; ?>]" class="long" /></td>
			<td class="small"><?php echo langAddHelp2; ?></td>
		</tr>
		<tr>
		<tr>
			<td class="item"><?php echo langAddLabel7; ?></td>
			<td>
			  <select name="id_catg[<?php echo $i; ?>]">
			  <option value="0"><?php echo langAddSelect7; ?></option>
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
      </td>
      <td class="small"><?php echo langAddHelp7; ?></td>
		</tr>
			<td class="item"><?php echo langAddLabel3; ?></td>
			<td><input type="text" name="description[<?php echo $i; ?>]" id="description[<?php echo $i; ?>]" maxlength="255" class="long" /></td>
			<td class="small"><?php echo langAddHelp3; ?></td>
		</tr>
		<tr>
			<td class="item"><?php echo langAddLabel4; ?></td>
			    <td><textarea name="keywords[<?php echo $i; ?>]" id="keywords[<?php echo $i; ?>]" class="small"></textarea></td>
      <td class="small"><?php echo langAddHelp4; ?></td>
		</tr>
		<tr>
			<td class="item"><?php echo langAddLabel6; ?></td>
			<td>
			    <input type="checkbox" name="show[<?php echo $i; ?>]" value="1" checked="checked" />
      </td>
			<td class="small"><?php echo langAddHelp6; ?></td>
		</tr>
	<?php
	}
	?>
		<tr><td colspan="3" class="odesilaci"><input type="submit" value="<?php echo langAddSubmit; ?>" /></td></tr>
	</table>
	<input type="hidden" name="action" value="add" />
	<input type="hidden" name="number" id="number" value="<?php echo $files_num; ?>" />
	
	<?echo OdkazForm;?>
</fieldset>	
</form>
    
  </div>
</div>
</body>
</html>
