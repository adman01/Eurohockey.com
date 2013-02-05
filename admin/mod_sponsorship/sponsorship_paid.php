<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(33,1,1,0,0);
$input = new Input_filter();

require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Set sponsorship as paid");
require_once("../inc/tinymce.inc");
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
$strAdminMenu="sponsorship";
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
    
	<h1>Set sponsorship as paid</h1>
    <p class="box">
    	 <a href="sponsorship.php<?echo Odkaz;?>"  class="btn-list"><span>back to list of payments</span></a>
	  </p>
	  
	  
	  	<? 
	  	if (!empty($_GET['message'])){
	  	echo '<p class="msg error">';
			switch ($_GET['message']){
			 case 1:
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
	  	  
<form action="sponsorship_action.php" method="post" id="form-01">
<fieldset>
	<legend>Set payment data</legend>
	
	<?php
	$query_sub="SELECT sponsorship.* from sponsorship INNER JOIN sponsorship_payments_items ON sponsorship.id=sponsorship_payments_items.id_sponsorship WHERE id_payment=".$_GET['id'];
            if ($con->GetQueryNum($query_sub)>0){

            echo '<table>';
              echo '<tr>';
                echo '<thead>';
                  echo '<th>Type</th>';
                  echo '<th>Item name</th>';
                  echo '<th>Fee</th>';
                  echo '<th>Duration</th>';
                  echo '<th>Ads name</th>';
                  echo '<th>Status</th>';
                  echo '<th></th>';
                echo '</thead>';
              echo '</tr>';
              $counter=0;
              $result_sub = $con->SelectQuery($query_sub);
              while($write_sub = $result_sub->fetch_array()){

                  $counter++;
                  if ($counter%2) $style=""; else $style="bg";
                  echo '<tr class="'.$style.'">';
                  
                  switch ($write_sub['id_type']) {
                  case 1:
                    $ItemName=$games->GetActualLeagueName($write_sub['id_item'],ActSeason);
                    $strTypeName="league";
                  break;
                  case 2:
                    $ItemName=$games->GetActualClubName($write_sub['id_item'],ActSeason);
                    $strTypeName="club";
                  break;
                  case 3:
                    $ItemName=$con->GetSQLSingleResult("SELECT CONCAT(name,' ',surname) as item FROM players WHERE id=".$write_sub['id_item']);
                    $strTypeName="player";
                  break;
                  }
                  echo '<td>'.$strTypeName.'</td>';
                  $strURL=get_url_text($ItemName,$write_sub['id_item']);
                  echo '<td><a onclick="return !window.open(this.href);" class="bold" href="/'.$strTypeName.'/'.$strURL.'">'.$ItemName.'</a></td>';
                  $strCurrencyName=$con->GetSQLSingleResult("SELECT name as item FROM sponsorship_currency_list WHERE id=".$write_sub['id_curency']);
                  echo '<td valign="top">'.number_format($write_sub['price'], 2, ',', ' ').' '.$strCurrencyName.'</td>';
                  echo '<td valign="top">'.$write_sub['duration_months'].' <b class="smaller low">months</b></td>';

                  $strAdsName=$con->GetSQLSingleResult("SELECT name as item FROM sponsorship_ads WHERE id=".$write_sub['id_ads']);
                  echo '<td>'.$strAdsName.'</td>';
                  
                    $strAdsStatus=$con->GetSQLSingleResult("SELECT sponsorship_approving_list.name as item FROM sponsorship_approving_list INNER JOIN sponsorship_ads ON sponsorship_approving_list.id=sponsorship_ads.is_approved WHERE sponsorship_ads.id=".$write_sub['id_ads']);  
                    $intAdsStatusId=$con->GetSQLSingleResult("SELECT sponsorship_approving_list.id as item FROM sponsorship_approving_list INNER JOIN sponsorship_ads ON sponsorship_approving_list.id=sponsorship_ads.is_approved WHERE sponsorship_ads.id=".$write_sub['id_ads']);  
                    $strStatus='
                    <b class="smaller"><span class="low">Ads:</span> '.$strAdsStatus.'</b>
                    '; 
                    if ($intAdsStatusId==2 AND $write['id_status']==1) $strStatus='<span class="label label-03">SHOWN</span>';
                    
                  echo '<td>'.$strStatus.'</td>';
                  echo'<td valign="top"><a  class="ico-info" onclick="return !window.open(this.href);" href="/'.$strTypeName.'/'.$strURL.'?preview=1&id_ads='.$write_sub['id_ads'].'">Preview</a></td>';

                echo '</tr>';    
              }

            $query_sub="SELECT * FROM sponsorship_payments  WHERE id=".$_GET['id'];
            if ($con->GetQueryNum($query_sub)>0){
              $result_sub = $con->SelectQuery($query_sub);
              $write_sub = $result_sub->fetch_array();
            	echo '<tr class="bg">';     
           	 	echo '<td colspan="2" class="t-right"><b>Total:</b></td>';
           	 	echo '<td colspan="2"><b>'.number_format($write_sub['price_total'], 2, ',', ' ').' '.$strCurrencyName.'</b>, ('.$write_sub['id_discount'].'% discount)</td>';
           	 	echo '<td colspan="5" class="t-right"></td>';
				echo '</tr>';     
           	}
           	echo '</table><br />';
          }

	?>

	<p class="nomt">
	<label class="req">Payment method</label><br />
	  <select name="id_payment_method" class="input-text-02 required">
			  <?php
        $query_sub="SELECT * FROM sponsorship_payments_methods_list ORDER by id";
        $result_sub = $con->SelectQuery($query_sub);
        echo '<option value="">select method</option>';
        while($write_sub = $result_sub->fetch_array()){
        echo '<option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$id_type).'>'.$write_sub['name'].'</option>';
        }
        ?>
        </select>
  	</p>

  	<p class="nomt">
	<label for="date" class="req">Pay date:</label><br />
	<input type="text" size="10" name="date_paid"  id="date" maxlength="255" class="input-text-02 required" value="<?php echo date("d.m.Y");  ?>" />
	</p>
	
	<p class="box-01">
			<input type="submit" value="Set to paid"  class="input-submit" />
			</p>
	<input type="hidden" name="action" value="add_paid" />
	<input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />
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
    datedit("date","d.m.yyyy");
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