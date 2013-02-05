<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(29,1,0,1,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
require_once("../inc/tinymce.inc");
echo $head->setTitle(langGlogalTitle."Edit transfer");
echo $head->setJavascriptExtendFile("js/functions.js");
echo $head->setJavascriptExtendFile("../inc/js/players_box.js");
echo $head->setJavascriptExtendFile("../inc/js/clubs_box.js");
echo $head->setJavascriptExtendFile("../inc/js/leagues_box.js");
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
$strAdminMenu="transfers";
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
    
	<h1>Edit transfer</h1>
    <p class="box">
    	 <a href="transfers.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of transfers</span></a>
	  </p>
	  
	  
	  <? 
	  	if (!empty($_GET['message'])){
	  	echo '<p class="msg error">';
			switch ($_GET['message']){
			 case 98:
          echo 'database error';
        break;
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
	
<?php
  if (!empty($_GET['id'])) {
  $query="SELECT * FROM transfers WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
    if (!empty($_GET['date_time'])) $date_time=$_GET['date_time']; else  $date_time=date("d.m.Y",strtotime($write['date_time']));
    if (!empty($_GET['id_player'])) $id_player=$_GET['id_player']; else  $id_player=$write['id_player'];
    if (!empty($_GET['id_position'])) $id_position=$_GET['id_position']; else $id_position=$write['id_position'];
    if (!empty($_GET['id_retire_status'])) $id_retire_status=$_GET['id_retire_status']; else $id_retire_status=$write['id_retire_status'];
    if ($_GET['id_club_from']<>"") $id_club_from=$_GET['id_club_from']; else  $id_club_from=$write['id_club_from'];
    if ($_GET['id_club_to']<>"") $id_club_to=$_GET['id_club_to']; else $id_club_to=$write['id_club_to'];
    if ($_GET['id_country_from']<>"") $id_country_from=$_GET['id_country_from']; else $id_country_from=$write['id_country_from'];
    if ($_GET['id_country_to']<>"") $id_country_to=$_GET['id_country_to']; else $id_country_to=$write['id_country_to'];
    if ($_GET['id_league_from']<>"") $id_league_from=$_GET['id_league_from']; else $id_league_from=$write['id_league_from'];
    if ($_GET['id_league_to']<>"") $id_league_to=$_GET['id_league_to']; else $id_league_to=$write['id_league_to'];
    if (!empty($_GET['note'])) $note=$_GET['note']; else $note=$write['note'];
    if (!empty($_GET['source'])) $source=$_GET['source']; else $source=$write['source'];
    if (!empty($_GET['id_source_note'])) $id_source_note=$_GET['id_source_note']; else $id_source_note=$write['id_source_note'];
    
  ?>
	  
<form action="transfers_action.php" method="post" id="form-01">
<fieldset>
	<legend>Update transfer</legend>
	
	<p class="nomt">
	<label for="inp-1" class="req">Date:</label><br />
	<input type="text" size="10" name="date_time" maxlength="10" class="input-text-02 required" id="inp-1" value="<?php echo $date_time; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Player:</label><br />
	<? show_player_select_box($con,0,$id_player,"id_player");
  ?>
	</p>
	
	<p class="nomt">
	<label for="inp-7" class="req">Position:</label><br />
  <select id="inp-7" name="id_position" class="input-text-02 required">
			  <?php
			  $query_sub="SELECT * FROM transfers_position_list ORDER by id";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$id_position).'>'.$write_sub['name'].'</option>
        ';
        }
        ?>
        </select>
  </p>
  
  <p class="nomt">
	<label for="inp-7" class="req">Retire:</label><br />
  <select id="inp-7" name="id_retire_status" class="input-text-02 required">
			  <?php
			  $query_sub="SELECT * FROM transfers_retire_list ORDER by id";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$id_retire_status).'>'.$write_sub['name'].'</option>
        ';
        }
        ?>
        </select>
  </p>
  
  <p class="nomt">
	<label for="inp-4"  class="req">Transfer from:</label><br />
	 <span style="padding-top:8px; display:block">
	 <input type="radio" name="is_from" value="1" <? if (!empty($id_club_from)) echo 'checked="checked"'  ?> class="input-text-02" onclick="document.getElementById('from_box_1').style.display=''; document.getElementById('from_box_2').style.display='none'; document.getElementById('from_box_3').style.display='none';"> Club
   <input type="radio" name="is_from" value="2" <? if (!empty($id_country_from)) echo 'checked="checked"'  ?> class="input-text-02" onclick="document.getElementById('from_box_1').style.display='none'; document.getElementById('from_box_2').style.display=''; document.getElementById('from_box_3').style.display='none';"> Country
   <input type="radio" name="is_from" value="3" <? if (empty($id_club_from) and empty($id_country_from)) echo 'checked="checked"'  ?> class="input-text-02" onclick="document.getElementById('from_box_1').style.display='none'; document.getElementById('from_box_2').style.display='none'; document.getElementById('from_box_3').style.display='';"> ?
   </span>
  </p> 
	
	<div id="from_box_1" <? if (empty($id_club_from)) echo 'style="display:none"'  ?>>
	 	<p class="nomt">
    <? show_club_select_box($con,1,$id_club_from,"id_club_from");?>
    </p>
    
    <p class="nomt">
    <? show_league_select_box($con,3,$id_league_from,"id_league_from",$users->getSesid(),$users->getIdPageRight());?>
    </p>
    
	</div>
	<div id="from_box_2" <? if (empty($id_country_from)) echo 'style="display:none"'  ?>>
		<select name="id_country_from" class="input-text-02">
			  <option value="">select country</option>
			  <?php
        $query_sub="SELECT * FROM countries ORDER by name";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$id_country_from).'>'.$games->GetActualCountryName($write_sub['id'],ActSeason).'</option>
        ';
        }
        ?>
        </select>
        <br /><br />
	
	</div>
	<div id="from_box_3" <? if (!empty($id_club_from) or !empty($id_country_from)) echo 'style="display:none"'  ?>>
	 <p class="nomt">
	   <b>destination unknown</b>
	  </p>
	</div>
  
  <p class="nomt">
	<label for="inp-4"  class="req">Transfer to:</label><br />
	 <span style="padding-top:8px; display:block">
	 <input type="radio" name="is_to" value="1" <? if (!empty($id_club_to)) echo 'checked="checked"'  ?> class="input-text-02" onclick="document.getElementById('to_box_1').style.display=''; document.getElementById('to_box_2').style.display='none'; document.getElementById('to_box_3').style.display='none';"> Club
   <input type="radio" name="is_to" value="2" <? if (!empty($id_country_to)) echo 'checked="checked"'  ?> class="input-text-02" onclick="document.getElementById('to_box_1').style.display='none'; document.getElementById('to_box_2').style.display=''; document.getElementById('to_box_3').style.display='none';"> Country
   <input type="radio" name="is_to" value="3" <? if (empty($id_club_to) and empty($id_country_to)) echo 'checked="checked"'  ?> class="input-text-02" onclick="document.getElementById('to_box_1').style.display='none'; document.getElementById('to_box_2').style.display='none'; document.getElementById('to_box_3').style.display='';"> ?
   </span>
  </p> 
	
	
	<div id="to_box_1" <? if (empty($id_club_to)) echo 'style="display:none"'  ?>>
	 	<p class="nomt">
    <? show_club_select_box($con,2,$id_club_to,"id_club_to");?>
  </p>
  <p class="nomt">
    <? show_league_select_box($con,4,$id_league_to,"id_league_to",$users->getSesid(),$users->getIdPageRight());?>
    </p>
	</div>
	<div id="to_box_2" <? if (empty($id_country_to)) echo 'style="display:none"'  ?>>
		<select name="id_country_to" class="input-text-02">
			  <option value="">select country</option>
			  <?php
        $query_sub="SELECT * FROM countries ORDER by name";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$id_country_to).'>'.$games->GetActualCountryName($write_sub['id'],ActSeason).'</option>
        ';
        }
        ?>
        </select>
        <br /><br />
	
	</div>
	<div id="to_box_3" <? if (!empty($id_club_to) or !empty($id_country_to)) echo 'style="display:none"'  ?>>
	 <p class="nomt">
	   <b>destination unknown</b>
	  </p>
	</div>
  
  <p class="nomt">
	<label for="inp-1" class="req">Note:</label><br />
	<input type="text" size="50" name="note" class="input-text-02" id="inp-1" value="<?php echo $note; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-1" class="req">Source:</label><br />
	<input type="text" size="50" name="source" class="input-text-02" id="inp-1" value="<?php echo $source; ?>" />
	</p>
  
  <p class="nomt">
	<label for="inp-7" class="req">Credibility:</label><br />
  <select id="inp-7" name="id_source_note" class="input-text-02 required">
			  <?php
			  $query_sub="SELECT * FROM transfers_source_list ORDER by id";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$id_source_note).'>'.$write_sub['name'].'</option>
        ';
        }
        ?>
        </select>
  </p>
	
	<p class="box-01">
			<input type="submit" value="Edit transfer"  class="input-submit" />
			</p>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="id" value="<?php echo $write['id'];?>" />
	<input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	<input type="hidden" name="filter2" value="<?php echo $_GET['filter2'];?>" />
	<input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
	<?echo OdkazForm;?>
</fieldset>	
</form>
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