<?php
require_once("../inc/global.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(25,1,0,1,0);
$ArSpecialRight=$users->getSpecialRightsSQL();
$games = new games($con);
require_once("inc/head.inc");
require_once("../inc/tinymce.inc");
echo $head->setTitle(langGlogalTitle."Edit club");
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
$strAdminMenu="clubs";
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
    
	<h1>Edit club</h1>
    <p class="box">
    	 <a href="clubs.php<?echo Odkaz;?>&amp;filter=<?echo $_GET['filter'];?>&amp;filter2=<?echo $_GET['filter2'];?>&amp;list_number=<?echo $_GET['list_number'];?>"  class="btn-list"><span>back to list of clubs</span></a>
	  </p>
	  
	  
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
    
    <div class="msg info error" style="display:none;">
      <span></span>.
    </div>
	
<?php
  if (!empty($_GET['id'])) {
  $query="SELECT * FROM clubs WHERE id=".$_GET['id'];
  $result = $con->SelectQuery($query);
  $write = $result->fetch_array();
  if ($con->GetQueryNum($query)==1){
  
    if (!empty($_GET['name'])) $name=$_GET['name']; else  $name=$write['name'];
    if (!empty($_GET['short_name'])) $short_name=$_GET['short_name']; else $short_name=$write['short_name'];
    if (!empty($_GET['nickname'])) $nickname=$_GET['nickname']; else $nickname=$write['nickname'];
    if (!empty($_GET['name_original'])) $name_original=$_GET['name_original']; else $name_original=$write['name_original'];
    if (!empty($_GET['id_country'])) $id_country=$_GET['id_country']; else  $id_country=$write['id_country'];
    if (!empty($_GET['id_status'])) $id_status=$_GET['id_status']; else  $id_status=$write['id_status'];
    if (!empty($_GET['id_status_info'])) $id_status_info=$_GET['id_status_info']; else $id_status_info=$write['id_status_info'];
    if (!empty($_GET['id_league'])) $id_league=$_GET['id_league']; else  $id_league=$write['id_league'];
    if (!empty($_GET['year_founded'])) $year_founded=$_GET['year_founded']; else $year_founded=$write['year_founded'];
    if (!empty($_GET['address'])) $address=$_GET['address']; else $address=$write['address'];
    if (!empty($_GET['city'])) $city=$_GET['city']; else $city=$write['city'];
    if (!empty($_GET['telephone'])) $telephone=$_GET['telephone']; else $telephone=$write['telephone'];
    if (!empty($_GET['fax'])) $fax=$_GET['fax']; else $fax=$write['fax'];
    if (!empty($_GET['email_1'])) $email_1=$_GET['email_1']; else $email_1=$write['email_1'];
    if (!empty($_GET['email_2'])) $email_2=$_GET['email_2']; else $email_2=$write['email_2'];
    if (!empty($_GET['email_3'])) $email_3=$_GET['email_3']; else $email_3=$write['email_3'];
    if (!empty($_GET['email_1_note'])) $email_1_note=$_GET['email_1_note']; else $email_1_note=$write['email_1_note'];
    if (!empty($_GET['email_2_note'])) $email_2_note=$_GET['email_2_note']; else $email_2_note=$write['email_2_note'];
    if (!empty($_GET['email_3_note'])) $email_3_note=$_GET['email_3_note']; else $email_3_note=$write['email_3_note'];
    if (!empty($_GET['year_founded'])) $year_founded=$_GET['year_founded']; else $year_founded=$write['year_founded'];
    if (!empty($_GET['colours'])) $colours=$_GET['colours']; else $colours=$write['colours'];
    if (!empty($_GET['brief_history'])) $brief_history=$_GET['brief_history']; else $brief_history=$write['brief_history'];
    if (!empty($_GET['achievments'])) $achievments=$_GET['achievments']; else $achievments=$write['achievments'];
    if (!empty($_GET['team_management'])) $team_management=$_GET['team_management']; else $team_management=$write['team_management'];
    if (!empty($_GET['link_1'])) $link_1=$_GET['link_1']; else $link_1=$write['link_1'];
    if (!empty($_GET['link_2'])) $link_2=$_GET['link_2']; else $link_2=$write['link_2'];
    if (!empty($_GET['link_3'])) $link_3=$_GET['link_3']; else $link_3=$write['link_3'];
    if (!empty($_GET['link_1_status'])) $link_1_status=$_GET['link_1_status']; else $link_1_status=$write['link_1_status'];
    if (!empty($_GET['link_2_status'])) $link_2_status=$_GET['link_2_status']; else $link_2_status=$write['link_2_status'];
    if (!empty($_GET['link_3_status'])) $link_3_status=$_GET['link_3_status']; else $link_3_status=$write['link_3_status'];
    if (!empty($_GET['is_national'])) $is_national=$_GET['is_national']; else $is_national=$write['is_national_team'];
    
    
  ?>
	  
<form action="clubs_action.php" method="post" id="form-01">
<fieldset>
	<legend>Edit club</legend>
	
	<p class="nomt">
	<label for="inp-1" class="req">Name:</label><br />
	<input type="text" size="50" name="name" maxlength="255" class="input-text-02 required" id="inp-1" value="<?php echo $name; ?>" />
	<div class="fix"></div>

	 <?php
	 echo '<p><span class="label label-01">Alternative names</span>'; 
        if ($users->checkUserRight(2)) echo'  <a href="clubs_names.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Add new alternative name" class="ico-list">add new alternative name</a></p>';
        $query_sub="SELECT * FROM clubs_names WHERE id_club=".$_GET['id']." ORDER by name";
        echo '<ul style="margin:10px 0px 10px 0px">';
        if ($con->GetQueryNum($query_sub)>0){
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
          echo '<li>';
          if ($users->checkUserRight(3)) echo'<a href="clubs_names_update.php'.Odkaz.'&amp;id='.$write_sub["id"].'&amp;id_club='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item">';
          echo $write_sub['name'];
          if ($users->checkUserRight(3)) echo'</a>';
          if ($users->checkUserRight(3)) echo'&nbsp;&nbsp;<a href="clubs_names_update.php'.Odkaz.'&amp;id='.$write_sub["id"].'&amp;id_club='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '</li>';
        }
        
        }else{
          echo '<li>no alternative name has been found</li>';
        }
        echo '</ul>';
        ?>
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Short name:</label><br />
  <input type="text" size="50" name="short_name" maxlength="50" class="input-text-02" id="inp-2" value="<?php echo $short_name; ?>" />
  <br /><span class="smaller low">for using in fixtures or standings, Sparta for HC Sparta Praha for example</span>
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Nickname:</label><br />
  <input type="text" size="50" name="nickname" maxlength="50" class="input-text-02" id="inp-2" value="<?php echo $nickname; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-2" class="req">Name in original language:</label><br />
  <input type="text" size="50" name="name_original" maxlength="50" class="input-text-02" id="inp-2" value="<?php echo $name_original; ?>" />
  <br /><span class="smaller low">cyrilic etc</span>
	</p>
	
	<div class="fix"></div>
  
  <p class="nomt">
	<label for="inp-19" class="req">League assign:</label><br />
	 <?php
	 echo '<p><span class="label label-01">Leagues:</span>'; 
        if ($users->checkUserRight(2)) echo'  <a href="clubs_assign.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Add new club to league assign" class="ico-list">add new club to league assign</a></p>';
        $query_sub="SELECT * FROM clubs_leagues_items WHERE id_club=".$_GET['id']." ORDER by id ASC";
        echo '<ul style="margin:10px 0px 10px 0px">';
        if ($con->GetQueryNum($query_sub)>0){
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
          $league_name=$games->GetActualLeagueName($write_sub['id_league'],ActSeason);
          echo '<li>';
          if ($users->checkUserRight(2)) echo'<a href="clubs_assign.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item">';
          echo $league_name;
          if ($users->checkUserRight(2)) echo'</a>';
          if ($users->checkUserRight(2)) echo'&nbsp;&nbsp;<a href="clubs_assign.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '</li>';
        }
        
        }else{
          echo '<li>no leagues assing has been found</li>';
        }
        echo '</ul>';
        ?>
	</p>
	</p>
	
	<p class="nomt">
	<label for="inp-19" class="req">Logo or image:</label><br />
	<?php
	 echo '<p>'; 
        $query_sub="SELECT file_name,description FROM clubs_images INNER JOIN photo ON photo.id=clubs_images.id_image WHERE id_club=".$_GET['id']." ORDER BY description";
        echo '<span class="label label-01">Assigned images</span>';
        if ($users->checkUserRight(2)) echo'  <a onclick="return !window.open(this.href);" href="clubs_images.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign new image" class="ico-list">assign new image</a></p>';
        echo'
        <ul style="margin:10px 0px 10px 0px">';
        if ($con->GetQueryNum($query_sub)>0){
        $result_sub = $con->SelectQuery($query_sub);
        echo '<li>';
        while($write_sub = $result_sub->fetch_array()){
          echo '<a onclick="return !window.open(this.href);" href="http://'.$_SERVER["SERVER_NAME"].'/'.PhotoFolder.'/'.$write_sub["file_name"].'" title="Show image">'.$write_sub['description'].'</a>';
          if ($users->checkUserRight(3)) echo'&nbsp;<a onclick="return !window.open(this.href);"  href="clubs_images.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
        }
        echo '</li>';
        
        }else{
          echo '<li>no assigned images has been found</li>';
        }
        echo '</ul>';
        ?>
	</p>
	
	<p class="nomt">
	<label for="inp-6" class="req">Country:</label><br />
  <select id="inp-6" name="id_country" class="input-text-02 required">
			  <option value="">select country</option>
			  <?php
        if(!empty($ArSpecialRight[1])) $sqlRightWhere="WHERE ".str_replace("id_country","id",$ArSpecialRight[1]); else $sqlRightWhere="";
        $query_sub="SELECT * FROM countries ".$sqlRightWhere." ORDER by name";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['shortcut'].'"'.write_select($write_sub['shortcut'],$id_country).'>'.$games->GetActualCountryName($write_sub['id'],ActSeason).'</option>
        ';
        }
        ?>
        </select>
  </p>
	
	<p class="nomt">
	<label for="inp-7" class="req">Status:</label><br />
  <select id="inp-7" name="id_status" class="input-text-02 required">
			  <option value="">select status</option>
			  <?php
        $query_sub="SELECT * FROM clubs_status_list ORDER by id";
        $result_sub = $con->SelectQuery($query_sub);
        while($write_sub = $result_sub->fetch_array()){
        echo '
        <option value="'.$write_sub['id'].'"'.write_select($write_sub['id'],$id_status).'>'.$write_sub['name'].'</option>
        ';
        }
        ?>
        </select>
  </p>
		
	<p class="nomt">
	<label for="inp-10" class="req">Status text info:</label><br />
  <input type="text" size="50" name="id_status_info" maxlength="255" class="input-text-02" id="inp-10" value="<?php echo $id_status_info; ?>" />
  <br /><span class="smaller low">fill only if status is set to "not active". Otherwise, the text on the webpage is not showing.</span>
  </p>
 
 	<p class="nomt">
	<label for="inp-10" class="req">National team:</label><br />
  <select id="inp-6" name="is_national" class="input-text-02">
			  <option value="1" <?php if ($is_national==1)  echo 'selected="selected"' ; ?>>club is NOT national team</option>
			  <option value="2" <?php if ($is_national==2)  echo 'selected="selected"' ; ?>>club is national team</option>
  </select>
  </p> 
  
	<p class="nomt">
	<label for="inp-4" class="req">Year founded:</label><br />
	<input type="text" size="5" name="year_founded" maxlength="4" class="input-text-02" id="inp-4" value="<?php echo $year_founded; ?>" />
	<br /><span class="smaller low">fill only numbers, e.g. 1982</span>
	</p>
	
	<p class="nomt">
	<label for="inp-19" class="req">Arenas:</label><br />
	<?php
	 echo '<p>'; 
        $query_sub="SELECT clubs_arenas_items.id_arena as id,name FROM clubs_arenas_items INNER JOIN arenas ON arenas.id=clubs_arenas_items.id_arena WHERE id_club=".$_GET['id']." ORDER BY name";
        echo '<span class="label label-01">Assigned arenas</span>';
        if ($users->checkUserRight(2)) echo'  <a onclick="return !window.open(this.href);" href="clubs_arenas.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign new arena" class="ico-list">assign new arena</a></p>';
        echo'<ul style="margin:10px 0px 10px 0px">';
        if ($con->GetQueryNum($query_sub)>0){
        $result_sub = $con->SelectQuery($query_sub);
        echo '<li>';
        while($write_sub = $result_sub->fetch_array()){
          echo '<a onclick="return !window.open(this.href);" href="../mod_arenas/arenas.php'.Odkaz.'&amp;filter='.$write_sub["name"].'" title="Show arena">'.$write_sub['name'].'</a>';
          if ($users->checkUserRight(3)) echo'&nbsp;<a onclick="return !window.open(this.href);"  href="clubs_arenas.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
        }
        echo '</li>';
        
        }else{
          echo '<li>no notable players has been found</li>';
        }
        echo '</ul>';
        ?>
	</p>
	
	<p class="nomt">
	<label for="inp-4" class="req">Address:</label><br />
  <textarea id="inp-4" name="address" cols="70" rows="7" style="width:400px; height:100px" class="input-text-02"><?php echo $address; ?></textarea>
	</p>
	
	<p class="nomt">
	<label for="inp-3" class="req">City:</label><br />
	<input type="text" size="50" name="city" maxlength="50" class="input-text-02" id="inp-3" value="<?php echo $city; ?>" />
	</p>
	
	<p class="nomt">
	<label for="inp-5" class="req">Telephone:</label><br />
  <input type="text" size="20" name="telephone" maxlength="40" class="input-text-02" id="inp-5" value="<?php echo $telephone; ?>" />
  <br /><span class="smaller low">international format e.g. +420 123 456 789</span>
	</p>
	
	<p class="nomt">
	<label for="inp-6" class="req">Fax:</label><br />
  <input type="text" size="20" name="fax" maxlength="40" class="input-text-02" id="inp-6" value="<?php echo $fax; ?>" />
  <br /><span class="smaller low">international format e.g. +420 123 456 789</span>
	</p>
	
	<p class="nomt">
	<label for="inp-7" class="req">Email #1 and note:</label><br />
  <input type="text" size="35" name="email_1" maxlength="50" class="input-text-02" id="inp-7" value="<?php echo $email_1; ?>" />
  <b>note:</b><input type="text" size="30" name="email_1_note" maxlength="50" class="input-text-02" id="inp-7" value="<?php echo $email_1_note; ?>" />
  <br /><span class="smaller low">e.g. email@email.com and tickets</span>
	</p>
	
	<p class="nomt">
	<label for="inp-7" class="req">Email #2 and note:</label><br />
  <input type="text" size="35" name="email_2" maxlength="50" class="input-text-02" id="inp-7" value="<?php echo $email_2; ?>" />
  <b>note:</b><input type="text" size="30" name="email_2_note" maxlength="50" class="input-text-02" id="inp-7" value="<?php echo $email_2_note; ?>" />
  <br /><span class="smaller low">e.g. email@email.com and tickets</span>
	</p>
	
	<p class="nomt">
	<label for="inp-7" class="req">Email #3 and note:</label><br />
  <input type="text" size="35" name="email_3" maxlength="50" class="input-text-02" id="inp-7" value="<?php echo $email_3; ?>" />
  <b>note:</b><input type="text" size="30" name="email_3_note" maxlength="50" class="input-text-02" id="inp-7" value="<?php echo $email_3_note; ?>" />
  <br /><span class="smaller low">e.g. email@email.com and tickets</span>
	</p>
	
	<p class="nomt">
	<label for="inp-3" class="req">Colours:</label><br />
	<input type="text" size="50" name="colours" maxlength="255" class="input-text-02" id="inp-3" value="<?php echo $colours; ?>" />
	</p>
	
	<p class="nomt">
	<label for="elm1" class="req">Brief history:</label><br />
	<textarea id="elm1" name="brief_history" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"><?php echo $brief_history; ?></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm1').hide();">[Hide editor]</a>
  		</span>
	
	</p>
	
	<p class="nomt">
	<label for="elm1" class="req">Achievements:</label><br />
	<textarea id="elm2" name="achievments" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"><?php echo $achievments; ?></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm2').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm2').hide();">[Hide editor]</a>
  		</span>
	
	</p>
	
	<p class="nomt">
	<label for="elm1" class="req">Team management:</label><br />
	<textarea id="elm3" name="team_management" cols="70" rows="7" style="width:880px; height:150px" class="input-text-02"><?php echo $team_management; ?></textarea>
			<span class="smaller low timy_controls_bottom">
			  <a href="javascript:;" onmousedown="tinyMCE.get('elm3').show();">[Show editor]</a>
  		  <a href="javascript:;" onmousedown="tinyMCE.get('elm3').hide();">[Hide editor]</a>
  		</span>
	
	</p>
	
	<p class="nomt">
	<label for="inp-19" class="req">Most notable players:</label><br />
	<?php
	 echo '<p>'; 
        $query_sub="SELECT clubs_players_items.id_player as id,name,surname FROM clubs_players_items INNER JOIN players ON players.id=clubs_players_items.id_player WHERE id_club=".$_GET['id']." ORDER BY surname";
        echo '<span class="label label-01">Assigned most notable players</span>';
        if ($users->checkUserRight(2)) echo'  <a onclick="return !window.open(this.href);" href="clubs_players.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign new player" class="ico-list">assign new player</a></p>';
        echo'<ul style="margin:10px 0px 10px 0px">';
        if ($con->GetQueryNum($query_sub)>0){
        $result_sub = $con->SelectQuery($query_sub);
        echo '<li>';
        while($write_sub = $result_sub->fetch_array()){
          echo '<a onclick="return !window.open(this.href);" href="../mod_players/players.php'.Odkaz.'&amp;filter='.$write_sub["id"].'" title="Show player">'.$write_sub['name'].' '.$write_sub['surname'].'</a>';
          if ($users->checkUserRight(3)) echo'&nbsp;<a onclick="return !window.open(this.href);"  href="clubs_players.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
        }
        echo '</li>';
        
        }else{
          echo '<li>no notable players has been found</li>';
        }
        echo '</ul>';
        ?>
	</p>
	
	<p class="nomt">
	<label for="inp-19" class="req">Links:</label><br />
	<?php
	 echo '<p>'; 
        $query_sub="SELECT name FROM clubs_links WHERE id_club=".$_GET['id']." ORDER BY name";
        echo '<span class="label label-01">Assigned links</span>';
        if ($users->checkUserRight(2)) echo'  <a onclick="return !window.open(this.href);" href="clubs_links.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Assign new link" class="ico-list">assign new link</a></p>';
        echo'<ul style="margin:10px 0px 10px 0px">';
        if ($con->GetQueryNum($query_sub)>0){
        $result_sub = $con->SelectQuery($query_sub);
        echo '<li>';
        while($write_sub = $result_sub->fetch_array()){
          echo '<a onclick="return !window.open(this.href);"  href="clubs_links.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item">'.$write_sub['name'].'</a>';
          if ($users->checkUserRight(3)) echo'&nbsp;<a onclick="return !window.open(this.href);"  href="clubs_links.php'.Odkaz.'&amp;id='.$_GET['id'].'&amp;filter='.$_GET['filter'].'&amp;filter2='.$_GET['filter2'].'&amp;list_number='.$_GET['list_number'].'" title="Edit item"><img src="../inc/design/ico-edit.gif" class="ico" alt="Edit"></a>';
          echo '&nbsp;&nbsp;';
        }
        echo '</li>';
        
        }else{
          echo '<li>no notable players has been found</li>';
        }
        echo '</ul>';
        ?>
	</p>
	
	<p class="box-01">
			<input type="submit" value="Edit club"  class="input-submit" />
			</p>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="id" value="<?php echo $write['id'];?>" />
	<input type="hidden" name="filter" value="<?php echo $_GET['filter'];?>" />
	<input type="hidden" name="filter2" value="<?php echo $_GET['filter2'];?>" />
	<input type="hidden" name="list_number" value="<?php echo $_GET['list_number'];?>" />
	<?echo OdkazForm;?>
</fieldset>	
</form>
<?php
	}else{echo '<p class="msg warning">No data found</p>';}
}else{echo '<p class="msg warning">No data found</p>';}
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