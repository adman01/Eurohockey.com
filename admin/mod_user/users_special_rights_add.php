<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(1,1,1,0,0);
require_once("inc/head.inc");
$games = new games($con);
echo $head->setTitle(langGlogalTitle."Add special user right");
echo $head->setJavascriptExtendFile("js/users_rules.js");
echo $head->setJavascriptExtendFile("../inc/js/leagues_box.js");
echo $head->setJavascriptExtendFile("../inc/js/clubs_box.js");
?>
<script type="text/javascript">
$(document).ready(function(){
  
  jQuery.validator.messages.required = "";
  
  $("#form-01").validate({
		invalidHandler: function(e, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				var message = errors == 1
					? 'You missed <b>1 field</b>. It has been highlighted below'
					: 'You missed <b>' + errors + ' fields</b>..  They have been highlighted below';
				$("div.error span").html(message);
				$("div.error").show();
			} else {
				$("div.error").hide();
			}
		},
		onkeyup: false,
	});
  
});
</script>
<?php
echo $head->setEndHead();
$strAdminMenu="users";
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
		<div id="content" class="box">

    <!-- hlavni text -->
    
	<h1>Add special right</h1>
    <p class="box">
       <a href="users_special_rights.php<?echo Odkaz;?>&amp;id_user=<?echo $_GET['id_user'];?>"  class="btn-list"><span>Special user rights</span></a>
       <a href="users.php<?echo Odkaz;?>"  class="btn-list"><span>Users</span></a>
       <?php if ($users->checkUserRight(2)){?><a href="users_add.php<?echo Odkaz;?>"  class="btn-create"><span>Add new user</span></a><?php } ?>
    	 <a href="users_logs.php<?echo Odkaz;?>"  class="btn-info"><span>User logs</span></a>
	  </p>
	  
	  
	  	<? 
	  	if (!empty($_GET['message'])){
	  	echo '<p class="msg error">';
			switch ($_GET['message']){
        case 99:
          echo 'wrong or missing input data';
        break;
      }
      echo '</p>';
      }
      ?>
    
    <div class="msg info error" style="display:none;">
      <span></span>.
    </div>
	  	  
<form action="users_special_rights_action.php" method="post" id="form-01">
<fieldset>
	<legend>Add special right</legend>
	
	<p class="nomt">
	<label for="inp-4" class="req">Module name:</label><br />
	 <select id="inp-4" name="id_right" class="input-text-02 required">
			  <option value="">select module</option>
			  <?php
        $query="SELECT * FROM users_rights WHERE show_item=1 and special_right=1 ORDER by name";
        $result = $con->SelectQuery($query);
        while($write = $result->fetch_array()){
        echo '
        <option value="'.$write['id'].'"'.write_select($write['id'],$_GET['id_right']).'>'.$write['name'].'</option>
        ';
        }
        ?>
    </select> 
    <br /><span class="smaller low">select only those modules for which is settings applicable</span>
	</p>
	
	
		<p class="nomt">
	<label for="inp-4"  class="req">Right alowed acces to:</label><br />
	 <span style="padding-top:8px; display:block">
	 <input type="radio" name="is_right" value="1" class="input-text-02" onclick="document.getElementById('right_box_1').style.display=''; document.getElementById('right_box_2').style.display='none'; document.getElementById('right_box_3').style.display='none';"> Country
   <input type="radio" name="is_right" value="2" class="input-text-02" onclick="document.getElementById('right_box_1').style.display='none'; document.getElementById('right_box_2').style.display=''; document.getElementById('right_box_3').style.display='none';"> League
   <input type="radio" name="is_right" value="3" class="input-text-02" onclick="document.getElementById('right_box_1').style.display='none'; document.getElementById('right_box_2').style.display='none'; document.getElementById('right_box_3').style.display='';"> Club
   <br /><span class="smaller low">select only those choices for which is settings applicable</span>
   </span>
   
	</p>
	
	<div id="right_box_1" style="display:none">
	 	<p class="nomt">
	<select name="id_country" class="input-text-02">
			  <option value="">select country</option>
			  <?php
        $query_sub="SELECT * FROM countries ORDER by name";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$_GET['id_country']).'>'.$games->GetActualCountryName($write_sub['id'],ActSeason).'</option>
        ';
        }
        ?>
        </select>
  </p>
	</div>
	<div id="right_box_2" style="display:none">
	<? show_league_select_box($con,1,$_GET['id_league'],"id_league",$users->getSesid(),$users->getIdPageRight());?>
	</div>
	<div id="right_box_3" style="display:none">
	<? show_club_select_box($con,1,$_GET['id_club'],"id_club");?>
	</div>
	
	<p class="box-01">
			<input type="submit" value="Add new special right"  class="input-submit" />
			</p>
	<input type="hidden" name="action" value="add" />
	<input type="hidden" name="id_user" value="<? echo $_GET['id_user'] ?>" />
	<?echo OdkazForm;?>
</fieldset>	
</form>


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