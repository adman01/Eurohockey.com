<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(33,1,0,1,0);
$games = new games($con);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Add new item to ");
echo $head->setJavascriptExtendFile("js/functions.js");
echo $head->setJavascriptExtendFile("../inc/js/leagues_box.js");
echo $head->setJavascriptExtendFile("../inc/js/clubs_box.js");
echo $head->setJavascriptExtendFile("../inc/js/players_box.js");
if (!empty($_GET['id_type'])) $tab=($_GET['id_type']-1); else $tab=0;
?>
<script type="text/javascript">
$(document).ready(function(){
  
  var $tabs = $('#tabs').tabs(); // first tab selected
  $tabs.tabs('select',<?php echo $tab; ?>); // switch to third tab

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

  $("#form_kategorie_1").validate({
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

  $("#form_kategorie_2").validate({
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

  $("#form_kategorie_3").validate({
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

  

  $('#id_category_1').removeClass("required");
  $('#id_category_2').removeClass("required");
  $('#id_category_3').removeClass("required");

  $('.is_item').click(function() {
  	
  	intID=$(this).val();
  	
	$('#id_category_1').removeClass("required");
  $('#id_category_2').removeClass("required");
  $('#id_category_3').removeClass("required");  	
  

  	if (intID==1){
  		$('#right_box_1').show();
  		$('#right_box_2').hide();
  		$('#right_box_3').hide();	
  		$('#id_category_1').addClass("required");
  	}
  	if (intID==2){
  		$('#right_box_1').hide();
  		$('#right_box_2').show();
  		$('#right_box_3').hide();	
  		$('#id_category_2').addClass("required");
  	}
  	if (intID==3){
  		$('#right_box_1').hide();
  		$('#right_box_2').hide();
  		$('#right_box_3').show();	
  		$('#id_category_3').addClass("required");
  	}

  	
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
		  <div id="content" class="box" style="min-height: 1000px;">

    <!-- hlavni text -->
	<h1>Add new items</h1>
    <p class="box">
    	 <a href="sponsorship.php<?echo Odkaz;?>"  class="btn-list"><span>back to sponsorship</span></a>
	 </p>
	  
	  
	  <? 
	  	if (!empty($_GET['message'])){
	  	switch ($_GET['message']){
	  	  case 1:
          echo '<p class="msg done">item has been added.</p>';
        break;
        case 2:
          echo '<p class="msg done">item has been removed.</p>';
        break;
        case 3:
          echo '<p class="msg done">item has been updated.</p>';
        break;
        case 97:
          echo '<p class="msg error">item is already in database.</p>';
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
	

	  


<div class="tabs box" id="tabs">
			<ul>

          <?php 
            $intLeagues=$con->GetSQLSingleResult("SELECT count(*) as item FROM sponsorship_items WHERE id_type=1");
            if (empty($intLeagues)) $intLeagues=0;
            
            $intClubs=$con->GetSQLSingleResult("SELECT count(*) as item FROM sponsorship_items WHERE id_type=2");
            if (empty($intClubs)) $intClubs=0;
            
            $intPlayers=$con->GetSQLSingleResult("SELECT count(*) as item FROM sponsorship_items WHERE id_type=3");
            if (empty($intPlayers)) $intPlayers=0;
            
            
          ?>
					<li><a href="#tab01"><span>Leagues (<?php echo $intLeagues; ?>)</span></a></li>
					<li><a href="#tab02"><span>Clubs (<?php echo $intClubs; ?>)</span></a></li>
					<li><a href="#tab03"><span>Players (<?php echo $intPlayers; ?>)</span></a></li>
				</ul>
			</div> <!-- /tabs -->

<?php
function showTable($id_type){
	global $con,$games,$users;

	
	echo '<form action="sponsorship_action.php" method="post" id="form-01">';
	echo '<fieldset>';
	echo '<legend>Add item to higher category</legend>';
	
	echo '<p class="msg info">All item are automatically entered into lowest category.</p>';
	switch ($id_type) {
		case 1:
			echo show_league_select_box($con,1,$_GET['id_item_'.$id_type],"id_item_".$id_type,$users->getSesid(),$users->getIdPageRight());
			break;
		case 2:
			echo show_club_select_box($con,1,$_GET['id_item_'.$id_type],"id_item_".$id_type,$users->getSesid(),$users->getIdPageRight());
			break;
		case 3:
			echo show_player_select_box($con,1,$_GET['id_item_'.$id_type],"id_item_".$id_type,$users->getSesid(),$users->getIdPageRight());
			break;
	}
	

	       echo '<p class="nomt">';
	       	   echo '<span class="label label-04">Select price level</span>';
		       echo '<select name="id_category_'.$id_type.'" id="id_category_'.$id_type.'" class="input-text required">';
			   echo '<option value="">select category</option>';
			   
			   $query_sub="SELECT * FROM sponsorship_category WHERE id<>6 ORDER by id";
        	   $result_sub = $con->SelectQuery($query_sub);
        	   while($write_sub = $result_sub->fetch_array()){
        			echo '<option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$_GET['id_category_'.$id_type]).'>'.$write_sub['name_'.$id_type].'</option>';
        		}
        		echo '</select>';
        		echo '<input type="hidden" name="is_item" value="'.$id_type.'" />';

       		echo '</p>';
       		echo '<p class="nomt" style="padding-top:15px">';
       		echo '<input type="submit" class="input-submit" value="add new" />';
       		echo '</p>';

	   
	echo '	    
	<input type="hidden" name="action" value="assign_add" />
	
	<input type="hidden" name="filter" value="'.$_GET['filter'].'" />
	<input type="hidden" name="filter2" value="'.$_GET['filter2'].'" />
	<input type="hidden" name="list_number" value="'.$_GET['list_number'].'" />
	'.OdkazForm.'
</fieldset>	
</form>

';

	echo '<form action="'.$_SERVER["SCRIPT_NAME"].'" method="get">';	
    echo '<fieldset>';	
	   echo '<legend>Data filter</legend>';	
	   echo '<table class="nostyle">';	
		  echo '<tr>';	
			 echo '<td><span class="label label-05">select category</span></td>';	
			 echo '<td>';	
        	  echo '<select name="filter" class="input-text">';
			    echo '<option value="">all categories</option>';
			   $query_sub="SELECT * FROM sponsorship_category WHERE id<>6 ORDER by id";
        	   $result_sub = $con->SelectQuery($query_sub);
        	   while($write_sub = $result_sub->fetch_array()){
        		 echo '<option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$_GET['filter']).'>'.$write_sub['name_'.$id_type].'</option>';
        		}
        		 echo '</select>';
       echo '</td>';	
     	 echo '<td><input type="submit" class="input-submit" value="filter" /></td>';	
		  echo '</tr>';	
	 echo '</table>';	
	 echo OdkazForm;
  echo '</fieldset>';	
  echo '</form>';	
	
  	if (!empty($_GET['filter'])) $strWhere=' AND id_category='.$_GET['filter']; else  $strWhere='';
	$query="SELECT * FROM sponsorship_items WHERE id_type=".$id_type." ".$strWhere." ORDER BY id DESC";
    //echo $query; 
    
    $result = $con->SelectQuery($query);
    $a = 0;
    if ($con->GetQueryNum($query)>0){

    $strTable.='
    <form action="sponsorship_action.php" id="form_kategorie_'.$id_type.'" method="post">
	<table>
	<thead>
		<tr>
			<th>[x]</th>
			<th>Name</th>
			<th>Category</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	';
    while($write = $result->fetch_array())
	{
		
		switch ($id_type) {
			case 1:
				$StrName=$games->GetActualLeagueName($write['id_item'],ActSeason);;
			break;
			case 2:
				$StrName=$games->GetActualClubName($write['id_item'],ActSeason);;
			break;
			case 3:
				$query_sub="SELECT name,surname FROM players WHERE id=".$write['id_item'];
              	$result_sub = $con->SelectQuery($query_sub);
              	$write_sub = $result_sub->fetch_array();
              	$StrName=$write_sub['surname'].' '.$write_sub['name'];
			break;
			
		}
		$arrayItems[$a]['name']=$StrName;
		$arrayItems[$a]['id']=$write['id'];
		$arrayItems[$a]['id_category']=$write['id_category'];

		$a++;
	}
	foreach ($arrayItems as $key => $value) {
		$strTable.='<tr>';
		$strTable.='<td valing="top" class="t-center">
                 <input type="checkbox" id="checkbox" name="id[]" value="'.$value["id"].'" />
                 </td>';
  		  $strTable.='<td class="high-bg">';
  		  	$strTable.=$value['name'];
  		  $strTable.='</td>';
  		  $strTable.='<td>';
  		  	$strTable.=$con->GetSQLSingleResult("SELECT name_".$id_type." as item FROM sponsorship_category WHERE id=".$value['id_category']);
  		  $strTable.='</td>';
  		  $strTable.='<td>';
  		  	 $strTable.='<a href="javascript:delete_item(\''.$users->getSesid().'\','.$value["id"].',\''.$value['name'].'\',\''.$_GET['filter'].'\',\''.$_GET['filter2'].'\',\''.$_GET['list_number'].'\',\''.$id_type.'\')" title="Delete item"><img src="../inc/design/ico-delete.gif" class="ico" alt="Delete"></a>';
  		  $strTable.='</td>';
  		  
  		$strTable.='</tr>';
	}
	 $strTable.= '<tfoot>';
            $strTable.= '
            <tr class="bg">
              <td class="t-center">
              <input type="checkbox" id="check_all" name="check_all" onclick="CheckAll(this.form,\'checkbox\');" />
              </td>
              <td colspan="12" class="arrow-01">
              select all items
              ';      
            $strTable.= '</td></tr></tfoot> 



            '; 


	$strTable.='</table>';

	$strTable.='<p class="box-01">';
	$strTable.='<select name="id_category" class="input-text-02 required">';
			   $strTable.='<option value="">select category</option>';
			   $query_sub="SELECT * FROM sponsorship_category WHERE id<>6 ORDER by id";
        	   $result_sub = $con->SelectQuery($query_sub);
        	   while($write_sub = $result_sub->fetch_array()){
        		 $strTable.= '<option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$_GET['filter']).'>'.$write_sub['name_'.$id_type].'</option>';
        		}
    $strTable.= '</select>';
    $strTable.=' <input type="submit" value="change category for all selected items"  class="input-submit" />';
    $strTable.='</p>';
    $strTable.=' <input type="hidden" name="action" value="assign_mass" />
    <input type="hidden" name="filter" value="'.$_GET['filter'].'" />
	<input type="hidden" name="filter2" value="'.$_GET['filter2'].'" />
	<input type="hidden" name="list_number" value="'.$_GET['list_number'].'" />
	<input type="hidden" name="id_type" value="'.$id_type.'" />
	';
    $strTable.=OdkazForm;

	$strTable.='</form>';
	}
	if (empty($strTable)) $strTable.='<p class="msg warning">No items found</p>';


	return $strTable;
}
 

?>

<div id="tab01">	  
	<?php echo showTable(1); ?>
</div>	  	

<div id="tab02">	  
	<?php echo showTable(2); ?>
</div>	  	

<div id="tab03">	  
	<?php echo showTable(3); ?>
</div>	  	


 	  


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