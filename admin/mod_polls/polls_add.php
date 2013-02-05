<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(3,1,1,0,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Add poll");
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
 

    var counter = 3;  
    $("#addButton").click(function () {
 
 
    	var newTextBoxDiv = $(document.createElement('div'))
    	     .attr("id", 'TextBoxDiv' + counter);
     
    	newTextBoxDiv.html('<label>Answer #'+ counter + ' : </label>' +
    	      '<input type="text" name="text' + counter + 
    	      '" id="textbox' + counter + '" value="" class="input-text-02"><br><br>');
     
    	newTextBoxDiv.appendTo("#TextBoxesGroup");
     
     
    	counter++;
     });
 
     $("#removeButton").click(function () {
	if(counter==1){
          alert("No more textbox to remove");
          return false;
       }   
 
	counter--;
 
        $("#TextBoxDiv" + counter).remove();
 
     });
 
     $("#submit").click(function() { 
        $('fieldset').append('<input type="hidden" name="counter" value="' + counter + '"/>');
     });  
});
</script>
<?php
echo $head->setEndHead();
$strAdminMenu="polls";
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
    
	<h1>Add new poll</h1>
    <p class="box">
    	 <a href="polls.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of polls</span></a>
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
	  	  
<form action="polls_action.php" method="post" id="form-01">
<fieldset>
	<legend>Add poll</legend>
	
	<p class="nomt">
	<label for="inp-1" class="req">Question:</label><br />
	<input type="text" size="50" name="question" maxlength="255" class="input-text-02 required" id="inp-1" value="<?php echo $_GET['question']; ?>" />
	</p>

	
	<p class="nomt">
	<label for="elm1" class="req">Date and time:</label><br />
	 <input type="text" size="8" name="date" maxlength="15" class="input-text-02 required" id="datum1" value="<?php if (empty($_GET['date_time'])) echo date("d.m.Y"); else echo date("d.m.Y",strtotime($_GET['date_time'])); ?>" />
	 <input type="text" size="3" name="time" maxlength="15" class="input-text-02 required" id="inp-1" value="<?php if (empty($_GET['date_time'])) echo date("H:i"); else echo date("H:i",strtotime($_GET['date_time'])) ?>" />
	</p>
	
	<p class="nomt">
	<label for="elm1" class="req">Pub date:</label><br />
	 <input type="text" size="8" name="pub_date" maxlength="15" class="input-text-02 required" id="datum2" value="<?php if (empty($_GET['pub_date_time'])) echo date("d.m.Y"); else echo date("d.m.Y",strtotime($_GET['pub_date_time'])); ?>" />
	 <input type="text" size="3" name="pub_time" maxlength="15" class="input-text-02 required" id="inp-1" value="<?php if (empty($_GET['pub_date_time'])) echo date("H:i"); else echo date("H:i",strtotime($_GET['pub_date_time'])) ?>" />
	 <br /><span class="smaller low">date and time when news will be automatically published</span>
	</p>
	
	<p class="nomt">
	<label for="elm1" class="req">Expire date:</label><br />
	 <input type="text" size="8" name="expire_date" maxlength="15" class="input-text-02" id="datum3" value="<?php if (empty($_GET['expire_date_time'])) echo "";else echo date("d.m.Y",strtotime($_GET['expire_date_time'])); ?>" />
	 <input type="text" size="3" name="expire_time" maxlength="15" class="input-text-02" id="inp-1" value="<?php if (empty($_GET['expire_date_time'])) echo ""; else echo date("H:i",strtotime($_GET['expire_date_time'])) ?>" />
	 <br /><span class="smaller low">date and time when news will be automatically disabled</span>
	</p>
	
	<p class="nomt">
	<label for="inp-7" class="req">Active:</label><br />
    <input type="checkbox" name="active" id="inp-11" value="1" />
  </p>
  
  <p class="nomt">
	  <label for="inp-7" class="req">Answers:</label>&nbsp;&nbsp;&nbsp;<input type='button' value='Add answer' id='addButton' class="input-submit">&nbsp;&nbsp;&nbsp;<input type='button' value='Remove answer' id='removeButton' class="input-submit"><br />  
      <div id='TextBoxesGroup'>
	      <div id="TextBoxDiv1">
		      <label>Answer #1 : </label>
          <input type='text' id='textbox1' class="input-text-02 required" name="text1">
          <br><br>
          <label>Answer #2 : </label>
          <input type='text' id='textbox2' class="input-text-02 required" name="text2" >
          <br><br>
      	</div>
      </div>
   </p>

  
  	
	<p class="box-01">
			<input type="submit" value="Add new poll"  class="input-submit" id="submit"/>
	</p>
  
	<input type="hidden" name="action" value="add" />
	<input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	<input type="hidden" name="filter2" value="<?php echo $_GET['filter2'];?>" />
	<input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
	<?echo OdkazForm;?>
</fieldset>	
</form>

<!-- Datedit by Ivo Skalicky - ITPro CZ - http://www.itpro.cz -->
  <link rel="stylesheet" href="../inc/datedit/datedit.css" type="text/css" media="screen" />
  <script type="text/javascript" charset="iso-8859-1" src="../inc/datedit/datedit.js"></script>
  <script type="text/javascript" charset="utf-8" src="../inc/datedit/lang/cz.js"></script>
  <script type="text/javascript">
    datedit("datum1","d.m.yyyy");
    datedit("datum2","d.m.yyyy");
    datedit("datum3","d.m.yyyy");
  </script>


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