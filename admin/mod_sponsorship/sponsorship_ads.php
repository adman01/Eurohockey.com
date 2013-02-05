<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(33,1,0,1,0);
$games = new games($con);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Ads management");
echo $head->setJavascriptExtendFile("js/functions.js");

if ($_GET['filter2']=="") $filter2="1"; else  $filter2=$_GET['filter2'];
?>
<script type="text/javascript">
$(document).ready(function(){
  
  
  	  
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
		  <div id="content" class="box" style="min-height: 1000px;">

    <!-- hlavni text -->
	<h1>Ads management</h1>
    <p class="box">
    	 <a href="sponsorship.php<?echo Odkaz;?>"  class="btn-list"><span>back to sponsorship</span></a>
	 </p>
	  
	  
	  <? 
	  	if (!empty($_GET['message'])){
	  	switch ($_GET['message']){
	  	  case 1:
          echo '<p class="msg done">ads has been added.</p>';
        break;
        case 2:
          echo '<p class="msg done">ads has been removed.</p>';
        break;
         
        case 3:
          echo '<p class="msg done">ads has been changed. <a href="sponsorship_ads_update.php'.Odkaz.'&id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'">Change ads again</a>.</p>';
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
	

	  <form action="<? echo $_SERVER["SCRIPT_NAME"]; ?>" method="get">
    <fieldset>
	   <legend>Data filter</legend>
	   <table class="nostyle">
		  <tr>
			 <td><span class="label label-05">search&nbsp;text</span></td>
			 <td>
         <input type="text" name="filter" value="<?php echo $_GET['filter']; ?>" class="input-text" />
       </td>
         <td>
       <select name="filter2" class="input-text">
			
			<?php
			
       	  	$query="SELECT * FROM  sponsorship_approving_list ORDER by id";
       		$result = $con->SelectQuery($query);
       		echo '<option value="0"'.write_select(0,$filter2).'>all</option>';
        	while($write = $result->fetch_array()){
        		echo '<option value="'.$write['id'].'"'.write_select($write['id'],$filter2).'>'.$write['name'].'</option>';
        	}
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
	  

  <p class="msg warning">Before approving, <b style="font-size:15px">check ADS on front-end</b>.</p>
	
	  <table>
	  	<table class="tablesorter"> <!-- Sort this TABLE -->
				<thead>
					<tr>
						<th>Name</th>
						<th>Type</th>
					    <th>Approved</th>
					    <th>User</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
	  <?php
    If (!IsSet($_GET['order']) OR ($_GET['order'] == ""))	$order="sponsorship_ads.id DESC"; else $order=$_GET['order'];
    If (!empty($_GET['filter']))	$filter=" AND (sponsorship_ads.name LIKE '%".$_GET['filter']."%' OR web_users.name LIKE '%".$_GET['filter']."%' OR web_users.surname LIKE '%".$_GET['filter']."%' OR CONCAT_WS(\" \",web_users.name,web_users.surname) LIKE '%".$_GET['filter']."%')"; else $filter="";
    If (!empty($filter2))	$filter2=" AND is_approved=".$filter2; else $filter2="";
    $query="SELECT sponsorship_ads.*,web_users.name as u_name,web_users.surname as u_surname FROM sponsorship_ads INNER JOIN web_users ON sponsorship_ads.id_customer=web_users.id WHERE sponsorship_ads.id>0 ".$filter." ".$filter2." ORDER BY ".$order."";
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
  		 echo '<tr class="'.$style.'">
  		  <td class="high-bg" valign="top">';
         //if ($users->checkUserRight(3)) echo'<a href="countries_update.php'.Odkaz.'&amp;id='.$write["id"].'" title="Edit country">';
         echo '<b>'.$write['name'].'</b>';
         //if ($users->checkUserRight(3)) echo'</a>';
         echo '</td>';
         switch ($write['id_type']) {
                    case 2:
                      $strType="Banner";
                      break;
                    case 1:
                      $strType="Text";
                      break;
                  }
        //$strStatus=$con->GetSQLSingleResult("SELECT name as item FROM sponsorship_approving_list WHERE id=".$write['is_approved']);
        echo '<td valign="top">'.$strType.'</td>';
         echo '<td class="t-left" style="white-space:nowrap">';
        if ($users->checkUserRight(3)) echo '<a href="sponsorship_ads_action.php'.Odkaz.'&amp;action=switch&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Switch status">';
        
       
        $query_sub="SELECT id,name FROM sponsorship_approving_list WHERE id=".$write["is_approved"];
        $result_sub = $con->SelectQuery($query_sub);
	      $write_sub = $result_sub->fetch_array();
  	    $status_name=$write_sub['name'];
	      $status_id=$write_sub['id'];
        
        switch ($status_id){
          case 1:
            $status_id_color=1;
          break;
          case 2:
            $status_id_color=3;
          break;
          case 3:
            $status_id_color=4;
          break;
        }

        echo '<span class="label label-0'.$status_id_color.'">'.$status_name.'</span>';
        if ($users->checkUserRight(3)) echo '</a>';
        echo'</td>';
        echo '<td valign="top">'.$write["u_name"].' '.$write["u_surname"].'</td>';

        $last_update=$write["last_update"];
        if ($last_update=='0000-00-00 00:00:00') $last_update='-'; else $last_update=date("d.m.y",strtotime($last_update));
        echo '<td style="white-space:nowrap" class="smaller low">'.$last_update.' | '.$users->GetUserSignatureID($write["last_update_user"]).'</td>';
        echo '
        <td style="white-space:nowrap">';
          echo'<a onclick="return !window.open(this.href);" href="/player/10689-jaromir-jagr.html?preview=1&id_ads='.$write["id"].'" title="Preview"><img src="../inc/design/ico-show.gif" class="ico" alt="Preview"></a>';
          echo '&nbsp;&nbsp;';
          if ($users->checkUserRight(3)) echo'<a href="sponsorship_ads_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          $intUsedAds=$con->GetSQLSingleResult("SELECT count(*) as item FROM sponsorship WHERE id_ads=".$write["id"]);
          if ($users->checkUserRight(4) and empty($intUsedAds)){ 
            echo'&nbsp;&nbsp;<a href="javascript:delete_ads(\''.$users->getSesid().'\','.$write["id"].',\''.$write["name"].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
          }
        echo "
        </td>
        </tr>\n";
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
</html>