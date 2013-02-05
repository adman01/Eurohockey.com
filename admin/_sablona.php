<?php
require_once("inc/global.inc");
require_once("inc/lang/".Web_language."/admin.php");
require_once("inc/init.inc");
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("inc/tiny_mce/tiny_mce.js");
echo $head->setJavascriptExtendFile("inc/lang/".Web_language."/admin.js");
echo $head->setJavascriptExtendFile("inc/js/admin.js");
require_once("inc/tinymce.inc");
echo $head->setTitle(langGlogalTitle."Unauthorized access");
echo $head->setEndHead();
$strAdminMenu="messages";
?>
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

