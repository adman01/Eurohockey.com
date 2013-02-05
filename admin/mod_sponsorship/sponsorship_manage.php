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
<style>
  tr.line-through td {text-decoration:line-through; color:gray;}
</style>
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
    
	<h1>Sponsorship - manage</h1>
    <p class="box">
    <a href="sponsorship.php<?echo Odkaz;?>"  class="btn-list"><span>back to list of payments</span></a>
    	 
	  </p>

     <form action="<? echo $_SERVER["SCRIPT_NAME"]; ?>" name="filter_form" method="get">
    <fieldset>
     <legend>Datafilter</legend>
     <table class="nostyle">
      <tr>
       <td><span class="label label-05">search&nbsp;customer</span></td>
       <td>
         <div style="position:relative">
          <input type="text" name="filter" id="filter" autocomplete="off" value="<?php echo $_GET['filter']; ?>" class="input-text" onkeyup="send_livesearch_data(this.value)" />
          <div id="livesearch"></div>
         </div>
         
       </td>
       
        <td>
        <span class="label label-04">status</span>
        <select name="filter2" class="input-text">
        <?php
        echo '<option value=""'.write_select("",$_GET['filter2']).'>actual only</option>';
        echo '<option value="1"'.write_select("1",$_GET['filter2']).'>expired only</option>';
        echo '<option value="2"'.write_select("2",$_GET['filter2']).'>unpaid</option>';
        ?>
        </select>
       </td>
       <td><input type="submit" class="input-submit" value="filter" /></td>
      </tr>
   </table>
   <input type="hidden" name="order" value="<?echo $_GET['order'];?>" />
   <?echo OdkazForm;?>
  </fieldset> 
  </form>
    
	  
	  	<? 
	  	switch ($_GET['message']){
         case 2:
          echo '<p class="msg done">sponsorship has been removed.</p>';
        break;
        case 99:
           echo '<p class="msg error">wrong or missing input data.</p>';
        break;
      }
      ?>
	  
   
	  
	   <?php
            If (!empty($_GET['filter']))  $filter=" AND (web_users.name LIKE '%".$_GET['filter']."%' OR web_users.surname LIKE '%".$_GET['filter']."%' OR CONCAT_WS(\" \",web_users.name,web_users.surname) LIKE '%".$_GET['filter']."%')"; else $filter="";
            If (empty($_GET['filter2']))  $filter2=" AND (date_expire>=NOW())"; else $filter2=" AND (date_expire<NOW())";
            If ($_GET['filter2']==2){
              $query="SELECT sponsorship.*,web_users.name,web_users.surname,web_users.email from sponsorship INNER JOIN web_users ON sponsorship.id_customer=web_users.id WHERE 1 ". $filter." AND 
                      (SELECT id_status FROM sponsorship_payments INNER JOIN sponsorship_payments_items ON sponsorship_payments.id=sponsorship_payments_items.id_payment WHERE sponsorship_payments_items.id_sponsorship=sponsorship.id LIMIT 1)=0
                      ORDER BY date_add ASC";
            }else{
              $query="SELECT sponsorship.*,web_users.name,web_users.surname,web_users.email from sponsorship INNER JOIN web_users ON sponsorship.id_customer=web_users.id WHERE 1 ". $filter2." ". $filter." ORDER BY date_expire ASC";
            }
            $listovani = new listing($con,$_SERVER["SCRIPT_NAME"].Odkaz."&amp;filter=".$_GET['filter']."&amp;filter2=".$_GET['filter2']."&amp;",100,$query,3,"",$_GET['list_number']);
            $query=$listovani->updateQuery();
            if ($con->GetQueryNum($query)>0){

            echo '<table style="width:100%">';
              echo '<tr>';
                echo '<thead>';
                  if ($_GET['filter2']==2){
                    echo '<th>Created</th>';
                  }else{
                    echo '<th>Expire</th>';                    
                  }
                  echo '<th>Type</th>';
                  echo '<th>Item name</th>';
                  echo '<th>Customer</th>';
                  echo '<th>Fee</th>';
                  echo '<th>Duration</th>';
                  echo '<th>Ads name</th>';
                  echo '<th>Status</th>';
                  echo '<th></th>';
                echo '</thead>';
              echo '</tr>';
              $counter=0;
              $result_sub = $con->SelectQuery($query);
              while($write_sub = $result_sub->fetch_array()){

                  $counter++;
                  if ($counter%2) $style=""; else $style="bg";

                  if ($_GET['filter2']==2){

                    $strDateExired=date("d M Y",strtotime($write_sub['date_add']));

                  }else{

                  $strExpired="";
                  if ($write_sub['id_status']==2){
                  
                    $strDateExired='<span>';
                    $strDateNow=strtotime(date("Y-m-d"));
                    $strDatePaymentExpire=strtotime(date("Y-m-d",strtotime($write_sub['date_expire'])));
                    if ($strDatePaymentExpire<$strDateNow){
                      $strExpired=' line-through';
                      $strDateExired='<span style="color:red;font-weight:bold">';
                    }else{
                      $strDatePaymentExpire14=strtotime(date("Y-m-d"))+(86400*14);
                      if ($strDatePaymentExpire<$strDatePaymentExpire14){
                        $strDateExired='<span style="color:red;font-weight:bold">';
                      }
                    }
                    $strDateExired.=date("d M Y",strtotime($write_sub['date_expire']));

                    if (empty($strExpired)){



                      $intDayLeft=strtotime($write_sub['date_expire'])-strtotime(date("d.m.Y"));
                      $strDateExired.=' <small>('.(round($intDayLeft/86400)).' days left)</small>';
                    }

                    $strDateExired.='</span>';
                  }else{
                    $strDateExired="-";
                  }

                  echo '<tr class="'.$style.$strExpired.'">';

                  }
                  echo '<td>'.$strDateExired.'</td>';
                  
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
                  echo '<td valign="top"><a href="mailto:'.$write_sub['email'].'">'.$write_sub['name'].' '.$write_sub['surname'].'</a></td>';
                  $strCurrencyName=$con->GetSQLSingleResult("SELECT name as item FROM sponsorship_currency_list WHERE id=".$write_sub['id_curency']);
                  echo '<td valign="top">'.number_format($write_sub['price'], 2, ',', ' ').' '.$strCurrencyName.'</td>';
                  echo '<td valign="top">'.$write_sub['duration_months'].' <b class="smaller low">months</b></td>';


                  $strAdsName=$con->GetSQLSingleResult("SELECT name as item FROM sponsorship_ads WHERE id=".$write_sub['id_ads']);
                  
                  echo '<td>'.$strAdsName.'</td>';
                  
                  if ($write_sub['id_status']==2 AND strtotime($write_sub["date_expire"])<date("U")){
                   $strStatus='<b style="color:red">expired</b>'; 
                  }else{
                    $strPaymentStatusID=$con->GetSQLSingleResult("SELECT id_status as item FROM sponsorship_payments INNER JOIN sponsorship_payments_items ON sponsorship_payments.id=sponsorship_payments_items.id_payment WHERE sponsorship_payments_items.id=".$write_sub['id']." LIMIT 1");  
                    switch ($strPaymentStatusID){
                     case 0:
                      $status_name="NOT YET paid";
                      break;
                    case 1:
                      $status_name="Paid";
                    break;
        }
                    $strAdsStatus=$con->GetSQLSingleResult("SELECT sponsorship_approving_list.name as item FROM sponsorship_approving_list INNER JOIN sponsorship_ads ON sponsorship_approving_list.id=sponsorship_ads.is_approved WHERE sponsorship_ads.id=".$write_sub['id_ads']);  
                    $intAdsStatusId=$con->GetSQLSingleResult("SELECT sponsorship_approving_list.id as item FROM sponsorship_approving_list INNER JOIN sponsorship_ads ON sponsorship_approving_list.id=sponsorship_ads.is_approved WHERE sponsorship_ads.id=".$write_sub['id_ads']);  
                    $strStatus='
                    <b class="smaller"><span class="low">Payment</span>: '.$status_name.'</b><br />
                    <b class="smaller"><span class="low">Ads:</span> '.$strAdsStatus.'</b>
                    '; 
                    if ($intAdsStatusId==2 AND $strPaymentStatusID==1) $strStatus='<span class="label label-03">SHOWN</span>';
                    
                  }
                  echo '<td>'.$strStatus.'</td>';
                  echo'<td valign="top">';
                  if ($_GET['filter2']==2){
                    echo'<a href="javascript:delete_sponsorship(\''.$users->getSesid().'\','.$write_sub["id"].',\''.$write_sub["name"].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
                  }else{
                    echo '<a class="ico-settings"  href="#"><b style="color:red">Extend duration</b></a>';  
                  }
                  echo'
                  &nbsp;&nbsp;<a class="ico-info" onclick="return !window.open(this.href);" href="/'.$strTypeName.'/'.$strURL.'?preview=1&id_ads='.$write_sub['id_ads'].'">Preview</a>
                  </td>';

                echo '</tr>';    
              }
           
               echo '<tfoot>';
     //listovani
      $listovani->show_list();
    echo '</tfoot>';
              
            echo '</table><br />';
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
