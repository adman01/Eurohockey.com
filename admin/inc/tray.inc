	<div id="tray" class="box">

		<p class="f-left box">

			<!-- Switcher -->
			<span class="f-left" id="switcher">
				<a href="#" rel="1col" class="styleswitch ico-col1" title="Display one column"><img src="http://<?echo $_SERVER['HTTP_HOST']?>/admin/inc/design/switcher-1col.gif" alt="1 Column" /></a>
				<a href="#" rel="2col" class="styleswitch ico-col2" title="Display two columns"><img src="http://<?echo $_SERVER['HTTP_HOST']?>/admin/inc/design/switcher-2col.gif" alt="2 Columns" /></a>
			</span>

			Project: <strong><?php echo strProjectName ;?></strong>

		</p>
		<p class="f-right">User: <strong><a href="/admin/mod_user/users_update.php<?echo Odkaz;?>&amp;id=<?echo $users->id_user; ?>"><?echo $users->GetUserName($users->sesid);?></a></strong> | <span id="autologout"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong><a href="javascript:logout2('<?echo $users->getSesid();?>')" id="logout">Log out</a></strong></p>

	</div> 