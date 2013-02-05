<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(32,1,1,0,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Add standings");
require_once("../inc/tinymce.inc");
echo $head->setJavascriptExtendFile("../inc/js/leagues_box.js");
echo $head->setJavascriptExtendFile("js/functions.js");

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
$strAdminMenu="standings";
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
    
	  <h1>Add new standings table</h1>
    <p class="box">
    	 <a href="standings.php<?echo Odkaz;?>&amp;id=<?echo $_GET['filter'];?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of standings</span></a>
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
	  	  
<form action="standings_action.php" method="post" name="form-01" id="form-01">
<fieldset>
	<legend>Add standings</legend>
	
	
	
	<p class="nomt">
	<label for="inp-1" class="req">Season:</label><br />
	      <select name="id_season" id="id_season" class="input-text-02 required">
			  <option value="">select season</option>
			  <?php
        for ($i=(ActSeason+1);$i>=1900;$i--) { 
	               echo '<option value="'.$i.'" '.write_select($_GET['id_season'],$i).'>'.($i-1).'/'.$i.'</option>';
	      }
        ?>
        </select> 
	</p>
	
	<p class="nomt">
	<label for="inp-16" class="req">League:</label><br />
  <? show_league_select_box($con,1,$_GET['id_league'],"id_league",$users->getSesid(),$users->getIdPageRight());?>
	</p>
	
	<div id="stage">
	      <? if (empty($_GET['id_stage']) and empty($_GET['id_league'])) {?>
	       <input type="hidden" name="id_stage" class="required" />
	      <? }else { ?>
	         
           <p class="nomt"><label for="inp-2" class="req">Stage:</label><br />
            <select id="id_stage" name="id_stage" class="input-text-02 required">
			      <option value="">select stage</option>
			     <?php
            $query_sub="SELECT * FROM games_stages WHERE id_league=".$_GET['id_league']." ORDER by id";
            $result_sub = $con->SelectQuery($query_sub);
            while($write_sub = $result_sub->fetch_array()){
              echo '<option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$_GET['id_stage']).'>'.$write_sub['name'].'</option>';
            }
            ?>
            </select>
            </p>
              
	      <? } ?>
	</div
	
	<p class="nomt">
	<label for="inp-1" class="req">Name:</label><br />
	<input type="text" size="50" name="name" maxlength="255" class="input-text-02 required" id="name" value="<?php echo $_GET['name']; ?>" />
	</p>
	
  <p class="nomt">
	<label for="inp-2" class="req">Standings creating:</label><br />
  <select name="standings_type" class="input-text-02 required">
			  <option value="">select type</option>
			  <?php
        $query_sub="SELECT * FROM standings_type_list ORDER by id";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$_GET['standings_type']).'>'.$write_sub['name'].'</option>
        ';
        }
        ?>
  </select>
  </p>
	
	
	<p class="box-01">
			<input type="submit" value="Add new standings"  class="input-submit" />
			</p>
	<input type="hidden" name="action" value="add" />
	<input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	<input type="hidden" name="filter2" value="<?php echo $_GET['filter2'];?>" />
	<input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
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