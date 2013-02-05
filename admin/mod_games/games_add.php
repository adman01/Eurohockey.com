<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(31,1,1,0,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
echo $head->setTitle(langGlogalTitle."Add games");
echo $head->setJavascriptExtendFile("js/functions.js");
require_once("../inc/tinymce.inc");
echo $head->setJavascriptExtendFile("../inc/js/images_box.js");
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
$strAdminMenu="games";
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
		<div id="content" class="box" style="width:1200px">

    <!-- hlavni text -->
    
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
    
    <?php
  
  $id_league=$_GET['id'];         
  if (empty($id_league)) $id_league=0; else $id_league=$id_league;
  if (empty($_GET['id_season'])) $id_season=ActSeason; else $id_season=$_GET['id_season'];
  
  if(!empty($ArSpecialRight[1])) $sqlRightWhereCountry=" AND (SELECT count(*) FROM leagues_countries_items WHERE (".str_replace("id_country","leagues_countries_items.id_country",$ArSpecialRight[1]).") AND leagues_countries_items.id_league=leagues.id)>0";
  if(!empty($ArSpecialRight[2])) $sqlRightWhereLeague=" AND ".str_replace("id_league","id",$ArSpecialRight[2]); else $sqlRightWhereLeague="";
  $query2="SELECT id FROM leagues WHERE id=".$id_league." ".$sqlRightWhereClub." ".$sqlRightWhereCountry." ".$sqlRightWhereLeague." LIMIT 1";
  //echo $query2;
  $result2 = $con->SelectQuery($query2);
  $write2 = $result2->fetch_array();
  $intLeagueClub=$con->GetQueryNum($query2);
  if ($intLeagueClub>0){
?>    
<h1>Add games for league: <span style="color:#FFF5CC"><? echo $games->GetActualLeagueName($id_league,($id_season)); ?></span> and season <span style="color:#FFF5CC"><? echo ($id_season-1)."/".$id_season; ?></span></h1>
<?php
  }else{
?>
<h1>Add games for league</span></h1>
<?php
  }
?>

<p class="box">
    	 <a href="games.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>&amp;id_season=<?echo $_GET['id_season'];?>&amp;id=<?echo $_GET['id'];?>"  class="btn-list"><span>back to list of games</span></a>
	  </p>
    
    <form action="<? echo $_SERVER["SCRIPT_NAME"]; ?>" name="filter_form" method="get">
    <fieldset>
	   <legend>First select season and league</legend>
	   <table class="nostyle">
		  <tr>
		  <td><span class="label label-05">selected&nbsp;season</span></td>
      <td>
       <select name="id_season" class="input-text required">
			  <option value="0">select season</option>
			  <?php
        for ($i=(ActSeason+1);$i>=1900;$i--) { 
	               echo '<option value="'.$i.'" '.write_select(($id_season),$i).'>'.($i-1).'/'.$i.'</option>';
	      }
        ?>
        </select> 
       </td>
			 <td><span class="label label-05">selected&nbsp;league</span></td>
			 <td>
			   <div style="position:relative">
          <input type="text" name="filter_name" autocomplete="off" value="<?php echo $games->GetActualLeagueName($id_league,($id_season));; ?>" class="input-text required" onkeyup="send_livesearch_leagues_data(this.value,'<? echo $users->getSesid(); ?>',<? echo $users->getIdPageRight();?>)" />
          <div id="livesearch" style="z-index:99"></div>
         </div>
         
       </td>
       
     	 <td><input type="submit" class="input-submit" value="filter" /></td>
		  </tr>
	 </table>
	 <input type="hidden" name="order" value="<?echo $_GET['order'];?>" />
	 <input type="hidden" name="id" id="id_league_filter" value="<?echo $_GET['id'];?>" />
	 
	 <?echo OdkazForm;?>
  </fieldset>	
  </form>
	
	<div class="msg info error" style="display:none;">
      <span></span>.
    </div><br />
	
	          
  <?php
if (!empty($id_league)){    	  
echo '
<form action="games_action.php" method="post" id="form-01"  style="">
<table>
<tr>
';
      echo '<th>Date</th>';
      echo '<th>Time</th>';
      echo '<th>Stage</th>';
      echo '<th>Round</th>';
      echo '<th>Home</th>';
      echo '<th>Visiting</th>';
      echo '<th>Score H</th>';
      echo '<th>Score V</th>';
      echo '<th>End of game</th>';
      echo '<th></th>';
echo '</tr>';

              $strClubsSelect.='<option value=""  class="">select team</option>';    
	            $query="SELECT DISTINCT id_club as item FROM clubs_leagues_items WHERE id_league=".$id_league;
	            if ($con->GetQueryNum($query)>0){
              $result = $con->SelectQuery($query);
              $counter=0;
              while($write = $result->fetch_array()){
                $strClubArray[$counter][0]=$games->GetActualClubName($write['item'],$id_season); 
                $strClubArray[$counter][1]=$write['item'];
                $counter++;
              }
              sort($strClubArray);
              foreach ($strClubArray as $key => $val) {
                 $strClubsSelect.='<option value="'.$val[1].'">'.$val[0].'</option>';
              }
              }
              
              $query="SELECT DISTINCT id as item FROM clubs WHERE is_national_team=2";
	            if ($con->GetQueryNum($query)>0){
              $result = $con->SelectQuery($query);
              $counter=0;
              $strClubsSelect.='<option value="" style="font-weight:bold">------national teams------</option>';
              
              while($write = $result->fetch_array()){
                $strClubArray2[$counter][0]=$games->GetActualClubName($write['item'],$id_season); 
                $strClubArray2[$counter][1]=$write['item'];
                $counter++;
              }
              sort($strClubArray2);
              foreach ($strClubArray2 as $key => $val) {
                 $strClubsSelect.='<option value="'.$val[1].'">'.$val[0].'</option>';
              }
              }
              
$strClubsSelect.='</select>';

for ($i=0;$i<20;$i++) {
echo '<tr>';
         echo '<td><input type="text" name="date['.$i.']" id="date'.$i.'" size="6" value="" maxlength="10" class="input-text" onchange="get_required('.$i.')" /></td>';
         echo '<td><input type="text" name="time['.$i.']" id="time'.$i.'" size="2" value="" maxlength="5" class="input-text" /></td>';
         echo'<td>';
	        echo '<select name="id_stage['.$i.']" id="id_stage'.$i.'" class="input-text" onchange="get_required('.$i.')">';   
              $query="SELECT * FROM games_stages WHERE id_league=".$id_league." ORDER by id ASC";
	            $result = $con->SelectQuery($query);
              while($write = $result->fetch_array()){
                echo '<option value="'.$write['id'].'">'.$write['name'].'</option>';
              }
          echo '</select>';   
         echo '</td>';
         echo'<td><input type="text" name="round['.$i.']" id="round'.$i.'" size="5" maxlength="15" value="" class="input-text" /></td>';
	       echo'<td>';
	        echo '<select name="id_home_team['.$i.']" id="id_home_team'.$i.'" class="input-text" onchange="get_required('.$i.')">';   
            
          echo $strClubsSelect;    
         echo '</td>'; 
         echo'<td>';
	        echo '<select name="id_visiting_team['.$i.']" id="id_visiting_team'.$i.'" class="input-text" onchange="get_required('.$i.')">';   
          echo $strClubsSelect;    
         echo '</td>';
              echo'
              <td><input type="text" name="home_score['.$i.']" size="1" maxlength="2" value="" class="input-text" /></td>
              <td><input type="text" name="visiting_score['.$i.']" size="1" maxlength="2" value="" class="input-text" /></td>
              ';
             echo'<td>';
	        echo '<select name="games_status['.$i.']" id="games_status'.$i.'" class="input-text" onchange="get_required('.$i.')">';   
              $query="SELECT * FROM games_status_list ORDER by id";
              $result = $con->SelectQuery($query);
              while($write = $result->fetch_array()){
                echo '<option value="'.$write['id'].'">'.$write['name'].'</option>';
              }
          echo '</select>';   
         echo '</td>';
         echo '<td id="anchor'.$i.'"><a href="javascript:copy_down('.$i.')" class="ico-arrow-right">copy down</a></td>';
echo '</tr>
';
}
echo'
</table>


        	<p class="box-01">
			     <input type="submit" value="Add new games"  class="input-submit" />
			   </p>
  
        <input type="hidden" name="action" value="add" />
	      <input type="hidden" name="filter" value="'. $_GET['filter'].'" />
	      <input type="hidden" name="filter2" value="'. $_GET['filter2'].'" />
	      <input type="hidden" name="list_number" value="'. $_GET['list_number'].'" />
	      <input type="hidden" name="id_season" value="'. $id_season.'" />
	      <input type="hidden" name="id_league" value="'. $id_league.'" />
	      <input type="hidden" name="number" value="'. $i.'" />
	      '. OdkazForm.'
	      </form>
';
?>
<!-- Datedit by Ivo Skalicky - ITPro CZ - http://www.itpro.cz -->
  <link rel="stylesheet" href="../inc/datedit/datedit.css" type="text/css" media="screen" />
  <script type="text/javascript" charset="iso-8859-1" src="../inc/datedit/datedit.js"></script>
  <script type="text/javascript" charset="utf-8" src="../inc/datedit/lang/cz.js"></script>
  <script type="text/javascript">
  <?php 
  for ($i=0;$i<20;$i++) {
  ?>
    datedit("date<?php echo $i ?>","d.m.yyyy");
  <?php } ?>
  </script>
<?php

}else{
  echo '<p class="msg warning">No data found, please select league and season. Maybe you have not necessary user rights.</p>';
}
?>




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