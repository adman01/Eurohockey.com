<?php
require_once("inc/global.inc");
require_once("inc/init.inc");
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("inc/tiny_mce/tiny_mce.js");
require_once("inc/tinymce.inc");
echo $head->setJavascriptExtendFile("inc/js/admin.js");
echo $head->setTitle(langGlogalTitle."Welcome");
echo $head->setEndHead();
$strAdminMenu="messages";

//promazání adresáøe s doèasnýma fotkama
if (ModulePhoto==true){
  $handle=opendir('../temp'); 
  while (false!==($file = readdir($handle))) 
  { 
    if ($file != "." && $file != "..") 
    { 
        $date_add = strtotime(date("Y-m-d H:i", filectime('../temp/'.$file)));
        $date_now = strtotime(date('Y-m-d H:i'));
        if ($date_add<=($date_now-(3600*24*7))){
          unlink('../temp/'.$file);
        }else{
          //echo $file." OK";
        }
    } 
  }
  closedir($handle); 
}

?>
<script type="text/javascript">
$(document).ready(function(){
  
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
		}
	});
  
});
</script>

<body onload="javascript:logout('<?php echo $users->sesid ;?>', '<?php echo MaxTimeConnection ;?>')">


<div id="main">

	<!-- Tray -->
	<?php require_once("inc/tray.inc");  ?>
  <!--  /tray -->

	<hr class="noscreen" />

	
	<!-- Columns -->
	<div id="cols" class="box">

		<!-- Aside (Left Column) -->
		<div id="aside" class="box">
      <?php require_once("inc/menu.inc"); ?>
		</div> <!-- /aside -->

		<hr class="noscreen" />

		<!-- Content (Right Column) -->
		<div id="content" class="box">

    <!-- hlavni text -->
    
			 <h1>Welcome <b><?echo $users->GetUserName($users->sesid);?></b></h1>
			 
       <p class="msg info">
         Your last login:  <?php echo date("d.m.Y H:i",strtotime($users->GetUserLastLogin($users->sesid)))?>
       </p>

			<h3>Messages</h3>
			
			<p class="box">
			<a href="javascript:toggle('add_message');" class="btn-create"><span>Add new message</span></a>
			</p>
			<? 
			switch ($_GET['message']){
        case 1:
          echo ' <p class="msg done">Message was added</p>';
        break;
        case 2:
          echo ' <p class="msg done">Message was deleted</p>';
        break;
      }
      ?>
    
    <div class="msg info error" style="display:none;">
      <span></span>.
    </div>

    <div id="add_message" style="display:none;">
	  <form action="admin_action.php" method="post" id="form-01">
    <fieldset>
	   <legend>Add Message</legend>
	  
     <p class="nomt">
	     <label for="inp-1" class="req">Send message to:</label><br />
			 <select name="to_id_user" size="1" class="input-text required" id="inp-1">
        <option value="0">all users</option>
        <?php
          $query="SELECT * from users ORDER by surname, name DESC";
          $result = $con->SelectQuery($query);
          while($write = $result->fetch_array())
	         {
	           echo '<option value="'.$write['id'].'">'.$write['surname'].' '.$write['name'].' ('.$write['user_name'].')</option>';
           } 
          ?>
          </select><br />
			</p>
      <p class="nomt">
      <label for="inp-2" class="req">Message:</label><br />
			<textarea id="elm1" name="message" cols="70" rows="7" style="width:880px; height:150px" class="input-text"></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').hide();">[Hide editor]</a>
  		</span>

			</p>
			<p>
			<input type="submit" value="Send message"  class="input-submit" />
			</p>
      
      
	    <input type="hidden" name="action" value="add" />
	    <?echo OdkazForm;?>
	    
	    
  </fieldset>	
  </form>
  </div>
   
  <?php
    $query="SELECT * from messages WHERE to_id_user=0 or to_id_user=".$users->id_user." or id_user=".$users->id_user." ORDER BY date_time DESC";
    
    //listovani
    $listovani = new listing($con,"admin.php".Odkaz."&amp;",30,$query,3,"",$_GET['list_number']);
    $query=$listovani->updateQuery();
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    
    while($write = $result->fetch_array())
	   {
      echo '<table style="width:900px; margin-bottom:10px;">'; 
      echo '<tr>';
      if (!empty($write['to_id_user'])) echo '<td class="t-center high-bg" style="width:100px;"><b>PRIVATE</b></td>';
      
      echo '<th>'.date("d.m.Y, H:i",strtotime($write['date_time'])).': <strong>'.$users->GetUserSignatureID($write['id_user']).'</strong></th>';
      if (empty($_GET['list_number'])) $_GET['list_number']=0;
      if ($write['id_user']==$users->id_user OR $users->GetUserGroup($users->id_user)==3) echo'<td class="t-center high-bg" style="width:20px;"><a href="javascript:delete_message(\''.$users->getSesid().'\','.$write["id"].','.$_GET['list_number'].')" title="Delete message"><img src="inc/design/ico-delete.gif" class="ico" alt="Delete"></a></td>';
      echo'</tr>';
      echo '<tr class=""><td colspan="3">'.$write['message'].'</td></tr>';
      echo '</table>';
      }
      
	  }else{
      echo '<p class="msg warning">No message was found</p>';
    }
	  
	  ?>
	  
	   <?
    //listovani
    $listovani->show_list();
    ?>
  
   <!-- /hlavni text -->
			

		</div> <!-- /content -->

	</div> <!-- /cols -->

	<hr class="noscreen" />

	<!-- Footer -->
	<?php require_once("inc/footer.inc");  ?> 
  <!-- /footer -->

</div> <!-- /main -->

</body>
</html>

