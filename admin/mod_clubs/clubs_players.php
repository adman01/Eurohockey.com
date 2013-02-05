<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(25,1,0,1,0);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Add most notable players");
echo $head->setJavascriptExtendFile("../inc/js/players_box.js");
require_once("../inc/lightwindow.inc");
echo $head->setJavascriptExtendFile("js/functions.js");
?>
<?php
echo $head->setEndHead();
$strAdminMenu="clubs";
?>
<body onload="javascript:logout('<?php echo $users->sesid ;?>', '<?php echo MaxTimeConnection ;?>')">

<div id="main">

	<!-- Tray -->
	<?php require_once("../inc/tray.inc");  ?>
  <!--  /tray -->
	<hr class="noscreen" />
	
	<!-- Columns -->
	<div id="cols" class="box">

		<!-- Aside (Left Column) -->
		<div id="aside" class="box">
      <?php require_once("../inc/menu.inc"); ?>
		</div> <!-- /aside -->
		<hr class="noscreen" />

		<!-- Content (Right Column) -->
	  <div id="content" class="box" style="min-height: 1000px;">

    <!-- hlavni text -->

<?php
  if (!empty($_GET['id'])) {
  $query="SELECT * FROM clubs WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
    if (!empty($_GET['id'])) $id=$_GET['id']; else  $id=$write['id'];
  ?>
    
	<h1>Add most notable player</h1>
    <p class="box">
    	 <a href="clubs.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of clubs</span></a>
    	 <a href="clubs_update.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>&amp;id=<?echo $write['id'];?>"  class="btn-list"><span>back to editing default informations about "<? echo $write['name'];?>"</span></a>
	  </p>
	  
	  
	  <? 
	  	if (!empty($_GET['message'])){
	  	switch ($_GET['message']){
	  	  case 1:
          echo '<p class="msg done">player has been assigned.</p>';
        break;
        case 2:
          echo '<p class="msg done">player assign has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">player assign has been updated.</p>';
        break;
        case 98:
          echo '<p class="msg error">database error</p>';
        break;
        case 99:
          echo '<p class="msg error">wrong or missing input data</p>';
        break;
      }
      }
      ?>
    
    <div class="msg info error" style="display:none;">
      <span></span>.
    </div>
	

  
  <h2>You editing club: <b><?php echo $write['name'];?></b></h2>
<form action="clubs_action.php" method="post" id="form-01">
<fieldset>
	<legend>Add most notable player</legend>
	
	<table class="nostyle">
		  <tr>
			  <td>
          <? show_player_select_box($con,0,$_GET['id_player'],"id_player");?>
        </td>
     	 <td><input type="submit" class="input-submit" value="assign player" /></td>
		  </tr>
	 </table>
	<input type="hidden" name="action" value="player_add" />
	<input type="hidden" name="id" value="<?php echo $write['id'];?>" />
	<input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	<input type="hidden" name="filter2" value="<?php echo $_GET['filter2'];?>" />
	<input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
	<?echo OdkazForm;?>
</fieldset>	
</form>

 	  		<table class="tablesorter"> <!-- Sort this TABLE -->
				<thead>
					<tr>
						<th>Player</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
<?
    $query="SELECT clubs_players_items.id as id,players.id as id_player,name,surname FROM clubs_players_items INNER JOIN players ON players.id=clubs_players_items.id_player WHERE id_club=".$_GET['id']." ORDER BY surname";
    
    //echo $query; 
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	  if ($a%2) $style=""; else $style="";
  		 echo '<tr class="'.$style.'">
        <td class="high-bg">';
         echo'<a onclick="return !window.open(this.href);" href="../mod_players/players.php'.Odkaz.'&amp;filter='.$write["id_player"].'" title="Show player">';
         echo '<b>'.$write['name'].' '.$write['surname'].'</b>';
         echo'</a>';
        echo'</td>';
        echo'<td>';
          if ($users->checkUserRight(4)) echo'<a href="javascript:delete_player(\''.$users->getSesid().'\','.$write["id"].',\''.$write["name"].' '.$write["surname"].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\',\''.$_GET['id'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
        echo "
        </td>
        </tr>\n";
  		 $a++;
	   }
	   
	   
	  }else{
      echo '<tr><td colspan="10" class=""><p class="msg warning">No players found</p></td></tr>';
    }
    
    ?>
</table>

<?php
	}else{echo '<p class="msg warning">No data found</p>';}
}else{echo '<p class="msg warning">No data found</p>';}
?>

   <!-- /hlavni text -->
			

		</div> <!-- /content -->

	</div> <!-- /cols -->

	<hr class="noscreen" />

	<!-- Footer -->
	<?php require_once("../inc/footer.inc");  ?> 
  <!-- /footer -->

</div> <!-- /main -->

</body>
</html>