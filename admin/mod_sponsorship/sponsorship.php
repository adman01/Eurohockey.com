<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(33,1,0,0,0);
$games = new games($con);
$input = new Input_filter();
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("js/functions.js");

echo $head->setTitle(langGlogalTitle."Sponsorship");
?>
<script type="text/javascript">
	$(document).ready(function(){

      $('.show_item').click(function () {
      intID=$(this).attr('id');
      $('#'+intID+'_box').toggle();
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
    
	<h1>Sponsorship - payments</h1>
    <p class="box">
        <?php 
            $intNotApproved=$con->GetSQLSingleResult("SELECT count(*) as item FROM sponsorship_ads WHERE is_approved=1");
            if (!empty($intNotApproved)) $strStyle='color:red';

            $intExpiring=$con->GetSQLSingleResult("SELECT count(*) as item FROM sponsorship WHERE id_status=2 AND (date_expire>=NOW()) AND (date(date_expire)<=(NOW()+ INTERVAL 14 DAY)) ");
            if (!empty($intExpiring)) $strStyle2='color:red';
        ?>
       <a href="sponsorship_manage.php<?echo Odkaz;?>"  class="btn-list"><span>Manage sponsorships <b style="<?php echo $strStyle2;?>">(<?php echo $intExpiring;?> before expiring !)</b></span></a>
       <a href="sponsorship_ads.php<?echo Odkaz;?>"  class="btn-list"><span>Ads management <b style="<?php echo $strStyle;?>">(<?php echo $intNotApproved;?> not approved !)</b></span></a>
       <a href="sponsorship_items.php<?echo Odkaz;?>"  class="btn-list"><span>Manage leagues/teams/players</span></a>
       
    	 
    	 
	  </p>
	  
	  	<? 
	  	switch ($_GET['message']){
        case 1:
          echo '<p class="msg done">payment has been set to paid.</p>';
        break;
        case 2:
          echo '<p class="msg done">sponsorship has been removed.</p>';
        break;
        case 99:
           echo '<p class="msg error">wrong or missing input data.</p>';
        break;
      }
      ?>
	  
   
	  
	  <table>
	  	<table class="tablesorter"> <!-- Sort this TABLE -->
				<thead>
					<tr>
            <th>Create date</th>
					  <th>Customer</th>
            <th>Discount</th>
            <th>Total Fee</th>
            <th>Pay status</th>
            <th>Paid date</th>
            <th>Method</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
					</tr>
				</thead>
	  
	  <?php
    If (!IsSet($_GET['order']) OR ($_GET['order'] == ""))	$order="sponsorship_payments.id DESC"; else $order=$_GET['order'];
    If (!empty($_GET['filter']))	$filter=" AND (name LIKE '%".$_GET['filter']."%' OR shortcut LIKE '%".$_GET['filter']."%')"; else $filter="";
    //If (!empty($_GET['filter2']) AND $_GET['filter2']>0)	$filter2=" AND id_group=".$_GET['filter2']; else $filter2="";
    $query="SELECT sponsorship_payments.*,web_users.name,web_users.surname,web_users.email,web_users.address,web_users.city,web_users.postal_code,web_users.id_country,web_users.phone,web_users.ico,web_users.dic FROM sponsorship_payments INNER JOIN web_users ON sponsorship_payments.id_customer=web_users.id WHERE sponsorship_payments.id>0 ".$filter." ".$filter2." ORDER BY ".$order."";
    //echo $query; 
    
    //listovani
    $listovani = new listing($con,$_SERVER["SCRIPT_NAME"].Odkaz."&amp;filter=".$_GET['filter']."&amp;filter2=".$_GET['filter2']."&amp;",100,$query,3,"",$_GET['list_number']);
    $query=$listovani->updateQuery();
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	  if ($a%2) $style=""; else $style="";
  		 echo '<tr class="'.$style.'">';
       echo '<td valign="top">'.date("d M Y",strtotime($write['date_add'])).'</td>';
       echo '<td class="high-bg"><b>'.$write['name'].' '.$write['surname'].'</b></td>';
       echo '<td valign="top">'.$write['id_discount'].'%</td>';
       $strCurrencyName=$con->GetSQLSingleResult("SELECT name as item FROM sponsorship_currency_list WHERE id=".$write['id_curency']);
       echo '<td valign="top" class="bold">'.number_format($write['price_total'], 2, ',', ' ').' '.$strCurrencyName.'</td>';
       echo '<td class="t-left" style="white-space:nowrap">';
        
        switch ($write['id_status']){
          case 0:
            $status_id_color=1;
            $status_name="NOT YET paid";
          break;
          case 1:
            $status_id_color=3;
            $status_name="Paid";
          break;
        }

        echo '<span class="label label-0'.$status_id_color.'">'.$status_name.'</span>';
        if ($write['id_status']==0){
          if ($users->checkUserRight(3)) echo'&nbsp;&nbsp;<a class="ico-settings" href="sponsorship_paid.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><b>SET TO PAID</b></a>';
        }
        echo'</td>';
        echo '<td valign="top">';
        if (!empty($write['date_paid'])){
          echo date("d M Y",strtotime($write['date_paid']));
        }else{
          echo 'NOT YET paid';
        }
          
        echo '</td>';
        $strPayMethod=$con->GetSQLSingleResult("SELECT name as item FROM sponsorship_payments_methods_list WHERE id=".$write['id_payment_method']);
        echo '<td valign="top" class="bold">'.$strPayMethod.'</td>';
        echo'<td valign="top"><a id="item_'.$write['id'].'" class="ico-info show_item" href="javascript:void(0);" title="Details"><b>DETAILS</b></a></td>';
        
        $last_update=$write["last_update"];
        if ($last_update=='0000-00-00 00:00:00') $last_update='-'; else $last_update=date("d.m.y",strtotime($last_update));
        echo '<td style="white-space:nowrap" class="smaller low">'.$last_update.' | '.$users->GetUserSignatureID($write["last_update_user"]).'</td>';
       
        echo "
        </tr>\n";
        echo '<tr id="item_'.$write['id'].'_box" style="display:none">';
          echo '<td colspan="10">';
            
            echo '<b class="smaller low" style="font-weight:bold">customer info</b>:<br />';
            echo '<b>'.$write['name'].' '.$write['surname'].'</b> | <a href="mailto:'.$write['email'].'">'.$write['email'].'</a> | <b class="smaller low">phone:</b> '.$write['phone'].'<br />';
            
            $strCountryName=$con->GetSQLSingleResult("SELECT name as item FROM countries WHERE id=".$write['id_country']);
            echo '<b class="smaller low">address:</b> '.$write['address'].' | '.$write['city'].' | '.$write['postal_code'].' | '.$strCountryName.'<br />';
            echo '<b class="smaller low">trade reg. number:</b> '.$write['ico'].' | <b class="smaller low">tax number:</b> '.$write['dic'].'<br />';
            echo '<br /><b class="smaller low" style="font-weight:bold">sponsorship info</b>:<br />';

            $query_sub="SELECT sponsorship.* from sponsorship INNER JOIN sponsorship_payments_items ON sponsorship.id=sponsorship_payments_items.id_sponsorship WHERE id_payment=".$write['id'];
            if ($con->GetQueryNum($query_sub)>0){

            echo '<table style="width:100%">';
              echo '<tr>';
                echo '<thead>';
                  echo '<th>Type</th>';
                  echo '<th>Item name</th>';
                  echo '<th>Fee</th>';
                  echo '<th>Duration</th>';
                  echo '<th>Expire</th>';
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

                  if ($write_sub['id_status']==2){
                    $strDateExired=date("d M Y",strtotime($write_sub['date_expire']));
                  }else{
                    $strDateExired="-";
                  }
                  $strAdsName=$con->GetSQLSingleResult("SELECT name as item FROM sponsorship_ads WHERE id=".$write_sub['id_ads']);
                  echo '<td>'.$strDateExired.'</td>';
                  echo '<td>'.$strAdsName.'</td>';
                  
                  if ($write_sub['id_status']==2 AND strtotime($write_sub["date_expire"])<date("U")){
                   $strStatus='<b style="color:red">expired</b>'; 
                  }else{
                    $strAdsStatus=$con->GetSQLSingleResult("SELECT sponsorship_approving_list.name as item FROM sponsorship_approving_list INNER JOIN sponsorship_ads ON sponsorship_approving_list.id=sponsorship_ads.is_approved WHERE sponsorship_ads.id=".$write_sub['id_ads']);  
                    $intAdsStatusId=$con->GetSQLSingleResult("SELECT sponsorship_approving_list.id as item FROM sponsorship_approving_list INNER JOIN sponsorship_ads ON sponsorship_approving_list.id=sponsorship_ads.is_approved WHERE sponsorship_ads.id=".$write_sub['id_ads']);  
                    $strStatus='
                    <b class="smaller"><span class="low">Payment</span>: '.$status_name.'</b><br />
                    <b class="smaller"><span class="low">Ads:</span> '.$strAdsStatus.'</b>
                    '; 
                    if ($intAdsStatusId==2 AND $write['id_status']==1) $strStatus='<span class="label label-03">SHOWN</span>';
                    
                  }
                  echo '<td>'.$strStatus.'</td>';
                  echo'<td valign="top"><a  class="ico-info" onclick="return !window.open(this.href);" href="/'.$strTypeName.'/'.$strURL.'?preview=1&id_ads='.$write_sub['id_ads'].'">Preview</a></td>';

                echo '</tr>';    
              }
               $query_sub="SELECT * FROM sponsorship_payments  WHERE id=".$write['id'];
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


          echo '</td>';
        echo '</tr>';
  		 $a++;
	   }
	   
	  echo '<tfoot>';
	   //listovani
      $listovani->show_list();
    echo '</tfoot>';
	   
	  }else{
      echo '<tr><td colspan="10" class=""><p class="msg warning">No data found</p></td></tr>';
    }
    
    ?>
    </table>
    
   <!-- /hlavni text -->
			

		</div> <!-- /content -->

	</div> <!-- /cols -->

	<hr class="noscreen" />

	<!-- Footer -->
	<?php require_once("../inc/footer.inc");  ?> 
  <!-- /footer -->

</div> <!-- /main -->

</body>
