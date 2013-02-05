<?php
set_time_limit(0);
require_once("../inc/global.inc");
require_once("../inc/init.inc");
require_once("inc/config.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(31,1,0,0,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
$input = new Input_filter();
require_once("inc/head.inc");
echo $head->setJavascriptExtendFile("js/functions.js");
echo $head->setJavascriptExtendFile("../inc/js/clubs_stats_box.js");
echo $head->setJavascriptExtendFile("../inc/js/leagues_stats_box.js");
echo $head->setTitle(langGlogalTitle."Import games from CSV file");
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
		<div id="content" class="box">

    <!-- hlavni text -->
    
	  <? 
	  if ($users->GetUserGroup($users->getIdUser())==3){
	  
	   if ($_POST['action']==""){
	        echo '<h1>Import games from CSV file</h1>';
	        echo '<p class="msg info">It is possible to import game data from CSV files. You can create CSV file from a typical Excel file (via "Save as" option). Set filter to a semicolon <b>-- ; ---</b> and text separator to an quotation marks <b>--- " ---</b>. <a href="sample_games_import.xls" onclick="return !window.open(this.href);">An example of XLS file is available here</a>.</p>';
          ?>
          <div id="upload" class="box-01">
          <form action="games_import.php" method="post" enctype="multipart/form-data">
				
					<p class="nom"><input type="file" size="90" name="filename[1]" class="input-text" /></p>
					
					<p class="nom">
						<input type="submit" value="Upload" class="input-submit" />
						<input type="reset" value="Clear" class="input-submit" />
					</p>
					<input type="hidden" name="action" value="upload" />
	        <?echo OdkazForm;?>
					
				</form>
				</div>
         <?php 
	   }
	  
    if ($_POST['action']=="upload"){
       $files = new files();
       $files->setFile($_FILES["filename"],1);
       $filename=$files->getFileName();
       
       if (!empty($filename)){
            $FolderPath=IncludeAdminPath."/admin/mod_games/import";
            switch ($files->uploadFile($FolderPath,10000000,$AlowedFileType)){
            case 1:
              //vse OK
              //nastaveni prav
              $files->setFileRules($FolderPath,0777); 
              //echo 'ok';   
            break;
            case 2:
              //chyba v uploadu
              $strError=$strError.'<li><b>file '.$filename.'</b>: file could not be upload</li>';
            break;
            case 3:
              //prilis velky soubor
              $strError=$strError.'<li><b>file '.$filename.'</b>: file is too large</li>';
            break;
            case 4:
              //nepovoleny typ souboru
              $strError=$strError.'<li><b>file '.$filename.'</b>: type not allowed</li>';
            break;
            case 5:
              //slozka nenalezena
              $strError=$strError.'<li><b>file '.$filename.'</b>: folder is not found</li>';
            break;
            case 6:
              //soubor jiz existuje
              $strError=$strError.'<li><b>file '.$filename.'</b>: file already exists</li>';
            break;
            }
            
            if (!empty($strError)){
              echo '<ul>';
              echo $strError;
              echo '</ul>';
            }else{
              
              //upload OK
               echo '
                <form action="games_action.php" method="post" id="form-01"  style="">
                <table>
                <tr>
                ';
                echo '<th>Season</th>';
                echo '<th>League</th>';
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
              
              $handle = fopen("import/".$filename, "r");
              while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $num = count($data);
                $i++;
                //$id_league=$input->valid_text($data[1],true,true);
               
               for ($c=0; $c < $num; $c++) {
                  if($c==0)$id_season=$input->check_number($data[$c]);
                  if($c==1)$id_league=$input->check_number($data[$c]);
                  if($c==2)$date=$input->valid_text($data[$c],true,true);
                  if($c==3)$time=$input->valid_text($data[$c],true,true);
                  if (empty($time)) $time="00:00";
                  if($c==4)$id_club_home=$input->check_number($data[$c]);
                  if($c==5)$id_club_visiting=$input->check_number($data[$c]);
                  if($c==6)$round=$input->valid_text($data[$c],true,true);
                  if($c==7)$home_score=$input->check_number($data[$c]);
                  if($c==8)$visiting_score=$input->check_number($data[$c]);
                  if($c==9)$games_status=$input->check_number($data[$c]);
                  
                  
                }
              if ($i<>1){ 
               
              $intDuplicity=$con->GetSQLSingleResult("SELECT id as item FROM games WHERE id_season=".$id_season." AND  id_league=".$id_league." AND date='".date("Y-m-d",strtotime($date))."' AND id_club_home=".$id_club_home." AND id_club_visiting=".$id_club_visiting."" );
               
              if ($intDuplicity==0) echo '<tr>'; else  echo '<tr class="bg">';
                echo '
                <td><select name="id_season['.$i.']" class="input-text required">
			           ';
                 for ($ix=(ActSeason+1);$ix>=1900;$ix--) { 
	               echo '<option value="'.$ix.'" '.write_select(($id_season),$ix).'>'.($ix-1).'/'.$ix.'</option>';
	               }
                echo '</select></td>';
                echo'<td>';
                show_league_stats_select_box ($con,$games,$i,$id_league,($id_season),"id_league[".$i."]","id_type[".$i."]",$i);
                echo '</td>';
               echo '<td><input type="text" name="date['.$i.']" id="date'.$i.'" size="6" value="'.$date.'" maxlength="10" class="input-text" /></td>';
                echo '<td><input type="text" name="time['.$i.']" id="time'.$i.'" size="2" value="'.$time.'" maxlength="5" class="input-text" /></td>';
              echo'<td>';
              echo'<div id="id_type['.$i.']_'.$i.'">';
	            echo '<select name="id_type['.$i.']" class="input-text">';   
                $query="SELECT * FROM games_stages WHERE id_league=".$id_league." ORDER by id ASC";
	              $result = $con->SelectQuery($query);
                while($write = $result->fetch_array()){
                  echo '<option value="'.$write['id'].'">'.$write['name'].'</option>';
                }
               echo '</select>';  
               echo '</div>';   
              echo '</td>';
              echo'<td><input type="text" name="round['.$i.']" id="round'.$i.'" size="5" maxlength="15" value="'.$round.'" class="input-text" /></td>';
	            echo'<td>';
                show_club_stats_select_box ($con,$games,$i,$id_club_home,($id_season),"id_club_home[".$i."]");
              echo '</td>'; 
              echo'<td>';
                show_club_stats_select_box ($con,$games,($i+1000),$id_club_visiting,($id_season),"id_club_visiting[".$i."]");
              echo '</td>';
              echo'
              <td><input type="text" name="home_score['.$i.']" size="1" maxlength="2" value="'.$home_score.'" class="input-text" /></td>
              <td><input type="text" name="visiting_score['.$i.']" size="1" maxlength="2" value="'.$visiting_score.'" class="input-text" /></td>
              ';
             echo'<td>';
	           echo '<select name="games_status['.$i.']"  class="input-text">';   
              $query="SELECT * FROM games_status_list ORDER by id";
              $result = $con->SelectQuery($query);
              while($write = $result->fetch_array()){
                echo '<option value="'.$write['id'].'" '.write_select(($games_status),$write['id']).'>'.$write['name'].'</option>';
              }
            echo '</select>';   
          echo '</td>';
          echo '<td>';
              if ($intDuplicity>0) echo '<a class="ico-warning" onclick="return !window.open(this.href);" href="games_info.php'.Odkaz.'&id='.$intDuplicity.'">Duplicity ID:'.$intDuplicity.'</a>';
          echo '</td>';
          echo '</tr>
            ';
               } 
              }
              echo '</table>
                	<p class="box-01">
			     <input type="submit" value="Add new games"  class="input-submit" />
			   </p>
  
        <input type="hidden" name="action" value="add_csv" />
	      <input type="hidden" name="number" value="'. $i.'" />
	      '. OdkazForm.'
	      </form>';
                  

              fclose($handle);
              
              $files->deleteFile($FolderPath,$filename);
              
            } 
            
            
            
    }
  }
  

    }else{
      echo '<p class="msg warning">Access denied. This section is only for administrators.</p>';
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

