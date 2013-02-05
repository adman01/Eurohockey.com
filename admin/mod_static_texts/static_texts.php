<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(4,1,0,0,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("js/functions.js");
?>
<script type="text/javascript">
	$(document).ready(function(){
	 
	    $.tablesorter.addParser({
	            id: 'dd.mm.yyyy',
	            is: function(s) {
	                return false;
	            },
	            format: function(s) {
	                s = '' + s; //Make sure it's a string
	                var hit = s.match(/(\d{1,2})\.(\d{1,2})\.(\d{4})/);
	                if (hit && hit.length == 4) {
	                    return hit[3] + hit[2] + hit[1];
	                }
	                else {
	                    return s;
	                }
	            },
	            type: 'text'
     });
	   
		$(".tabs > ul").tabs();
		$.tablesorter.defaults.sortList = [[2,0]];
		$("table").tablesorter({
			headers: {
			  0: { sorter: 'dd.mm.yyyy'},
				1: { sorter: false },
				3: { sorter: false },
				4: { sorter: false }
			}
		});
	});
</script>
<?php
echo $head->setTitle(langGlogalTitle."Static texts");
echo $head->setEndHead();
$strAdminMenu="static_texts";
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
    
	<h1>Static texts</h1>
    <p class="box">
       <?php if ($users->checkUserRight(2)){?><a href="static_texts_add.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-create"><span>Add new text</span></a><?php } ?>
	  </p>
	  
	  	<? 
	  	switch ($_GET['message']){
        case 1:
          echo '<p class="msg done">text has been added. <a href="static_texts_add.php'.Odkaz.'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'">Add another text</a> ?</p>';
        break;
        case 2:
          echo '<p class="msg done">text has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">text has been changed. <a href="static_texts_update.php'.Odkaz.'&id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'">Change text again</a>.</p>';
        break;
        case 99:
           echo '<p class="msg error">wrong or missing input data.</p>';
        break;
      }
      ?>
	  
	  <form action="<? echo $_SERVER["SCRIPT_NAME"]; ?>" name="filter_form" method="get">
    <fieldset>
	   <legend>Datafilter</legend>
	   <table class="nostyle">
		  <tr>
			 <td><span class="label label-05">search&nbsp;text</span></td>
			 <td>
          <input type="text" class="input-text" name="filter" id="filter" autocomplete="off" value="<?php echo $_GET['filter']; ?>" />
       </td>
     	 <td><input type="submit" class="input-submit" value="filter" /></td>
		  </tr>
	 </table>
	 <input type="hidden" name="order" value="<?echo $_GET['order'];?>" />
	 <?echo OdkazForm;?>
  </fieldset>	
  </form>
	  
	  
	   <table>
	  	<table class="tablesorter"> <!-- Sort this TABLE -->
				<thead>
					<tr>
					  <th>Pub date</th>
					  <th>&nbsp;</th>
						<th>Header</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
	  
	  <?php
    If (!IsSet($_GET['order']) OR ($_GET['order'] == ""))	$order="header ASC"; else $order=$_GET['order'];
     If (!empty($_GET['filter']))	{$filter=" AND (header LIKE '%".$_GET['filter']."%' OR text LIKE '%".$_GET['filter']."%')";} else {$filter="";}
    $query="SELECT * FROM static_texts WHERE id>0 ".$filter." ".$filter2." ORDER BY ".$order."";
   //echo $query; 
    
    //listovani
    $listovani = new listing($con,$_SERVER["SCRIPT_NAME"].Odkaz."&amp;filter=".$_GET['filter']."&amp;filter2=".$_GET['filter2']."&amp;",25,$query,3,"",$_GET['list_number']);
    $query=$listovani->updateQuery();
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){
    while($write = $result->fetch_array())
	   {
	  if ($a%2) $style=""; else $style="";
  		 echo '<tr class="'.$style.'">
  		  <td>'.date("d.m.Y H:i",strtotime($write["pub_date"])).'</td>';
        echo '<td class="t-left">';
        if ($users->checkUserRight(3) and $users->GetUserGroup($users->getIdUser())==3) echo '<a href="static_texts_action.php'.Odkaz.'&amp;action=switch&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Switch status">';
       
       $query_sub="SELECT id,name2 FROM show_status_list WHERE id=".$write["show_item"];
       $result_sub = $con->SelectQuery($query_sub);
	     $write_sub = $result_sub->fetch_array();
  	   $status_name=$write_sub['name2'];
	     $status_id=$write_sub['id'];
        
        switch ($status_id){
          case 0:
            $status_id_color=1;
          break;
          case 1:
            $status_id_color=2;
          break;
          case 2:
            $status_id_color=4;
          break;
          default:
           $status_id_color=1;
          break;
        }
        echo '<span class="label label-0'.$status_id_color.'">'.$status_name.'</span>';
        if ($users->checkUserRight(3) and $users->GetUserGroup($users->getIdUser())==3) echo '</a>';
        echo'</td>
        <td class="high-bg">';
         if ($users->checkUserRight(3)) echo'<a href="static_texts_update.php'.Odkaz.'&amp;id='.$write["id"].'" title="Edit news">';
         if (strlen($write['header'])>50) $strText=substr($write['header'],0,50)."..."; else $strText=$write['header'];
         echo '<b>'.$strText.'</b>';
         if ($users->checkUserRight(3)) echo'</a>';
         echo'</td>';

        echo'<td>';
          echo'<a onclick="return !window.open(this.href);" href="../../text/'.($write["id"]+123).'-test.html?passw='.md5("eurohockey.com").'" title="Show article"><img src="../inc/design/ico-show.gif" class="ico" alt="Show"></a>';
          if ($users->checkUserRight(3)) echo'&nbsp;&nbsp;<a href="static_texts_update.php'.Odkaz.'&amp;id='.$write["id"].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          if ($users->checkUserRight(4) and $write["is_lock"]==0){ 
            echo'&nbsp;&nbsp;<a href="javascript:delete_text(\''.$users->getSesid().'\','.$write["id"].',\''.$strText.'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
          }
        echo "
        </td>";
        $last_update=$write["last_update"];
        if ($last_update=='0000-00-00 00:00:00') $last_update='-'; else $last_update=date("d.m.Y H:i",strtotime($last_update));
        echo '<td class="smaller low">'.$last_update.' | '.$users->GetUserSignatureID($write["id_user"]).'</td>';
        echo "</tr>\n";
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
