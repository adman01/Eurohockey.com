	    <div class="padding box">
				<!-- Logo (Max. width = 200px) -->
				<p id="logo"><a onclick="return !window.open(this.href);" href="http://<?php echo strWWWurl ;?>"><img src="http://<?echo $_SERVER['HTTP_HOST']?>/admin/inc/tmp/logo.gif" alt="Our logo" title="Visit Site" /></a></p>
				<p id="btn-create" class="box"><a onclick="return !window.open(this.href);" href="http://<?echo $_SERVER['HTTP_HOST']?>" ><span>Show homepage</span></a></p>
			</div> <!-- /padding -->
				
			<ul class="box">
			   
         <li<? echo show_active_menu("messages",$strAdminMenu);?>><a href="/admin/admin.php<?echo Odkaz;?>" title="Messages">Messages</a></li>
         <? if ($users->checkThisUserRight(2,1)==true){ ?>
         <li<? echo show_active_menu("news",$strAdminMenu);?>><a href="/admin/mod_news/news.php<?echo Odkaz;?>" title="News">News</a>
            <ul>
              <li><a href="/admin/mod_news/news_add.php<?echo Odkaz;?>" title="Add news">add news</a></li>
            </ul>
         </li>
         <? } ?>
         
         <? if ($users->checkThisUserRight(3,1)==true){ ?>
         <li<? echo show_active_menu("articles",$strAdminMenu);?>><a href="/admin/mod_articles/articles.php<?echo Odkaz;?>" title="Articles">Articles</a>
            <ul>
              <li><a href="/admin/mod_articles/articles_add.php<?echo Odkaz;?>" title="Add article">add article</a></li>
            </ul>
         </li>
         <? } ?>
         <? if ($users->checkThisUserRight(4,1)==true){ ?>
         <li<? echo show_active_menu("static_texts",$strAdminMenu);?>><a href="/admin/mod_static_texts/static_texts.php<?echo Odkaz;?>" title="Static texts">Static texts</a>
         </li>
         <? } ?>
         
         <? if ($users->checkThisUserRight(26,1)==true){ ?>
         <li<? echo show_active_menu("players",$strAdminMenu);?>><a href="/admin/mod_players/players.php<?echo Odkaz;?>" title="Players">Players</a>
         </li>
         <? } ?>
         <? if ($users->checkThisUserRight(28,1)==true){ ?>
         <li<? echo show_active_menu("players_stats",$strAdminMenu);?>><a href="/admin/mod_players/players_stats_club.php<?echo Odkaz;?>" title="Statistics - clubs">Statistics - clubs</a></li>
         <? } ?>
         <? if ($users->checkThisUserRight(30,1)==true){ ?>
         <li<? echo show_active_menu("players_stats_league",$strAdminMenu);?>><a href="/admin/mod_players/players_stats_league.php<?echo Odkaz;?>" title="Statistics - leagues">Statistics - leagues</a></li>
         <? } ?>
         
         <? if ($users->checkThisUserRight(31,1)==true){ ?>
         <li<? echo show_active_menu("games",$strAdminMenu);?>><a href="/admin/mod_games/games.php<?echo Odkaz;?>" title="Games">Games</a>
          <ul>
              <li><a href="/admin/mod_games/games_add.php<?echo Odkaz;?>" title="Add new game">add new game</a></li>
              <li><a href="/admin/mod_games/games_import.php<?echo Odkaz;?>" title="Import games (CSV)">import games (CSV)</a></li>
            </ul>
         </li>
         <? } ?>
         
         <? if ($users->checkThisUserRight(32,1)==true){ ?>
         <li<? echo show_active_menu("standings",$strAdminMenu);?>><a href="/admin/mod_standings/standings.php<?echo Odkaz;?>" title="Standings">Standings</a></li>
         <? } ?>
         
         <? if ($users->checkThisUserRight(29,1)==true){ ?>
         <li<? echo show_active_menu("transfers",$strAdminMenu);?>><a href="/admin/mod_transfers/transfers.php<?echo Odkaz;?>" title="Transfers">Transfers</a>
          <ul>
              <li><a href="/admin/mod_transfers/transfers_add.php<?echo Odkaz;?>" title="Add new transfer">add new transfer</a></li>
            </ul>
         </li>
         <? } ?>
         
         <li<? echo show_active_menu("clubs",$strAdminMenu);?>>
         <? if ($users->checkThisUserRight(25,1)==true){ ?>
         <a href="/admin/mod_clubs/clubs.php<?echo Odkaz;?>" title="Clubs">
         <? } ?>
         Clubs
         <? if ($users->checkThisUserRight(25,1)==true){ ?>
         </a>
         <? } ?>
            <? if ($users->checkThisUserRight(27,1)==true){ ?>
            <ul>
              <li><a href="/admin/mod_arenas/arenas.php<?echo Odkaz;?>" title="Arenas">Arenas</a></li>
            </ul>
           <? } ?>
         </li>
         <? if ($users->checkThisUserRight(24,1)==true){ ?>
         <li<? echo show_active_menu("leagues",$strAdminMenu);?>><a href="/admin/mod_leagues/leagues.php<?echo Odkaz;?>" title="Leagues">Leagues</a></li>
         <? } ?>
			   <? if ($users->checkThisUserRight(23,1)==true){ ?>
			   <li<? echo show_active_menu("countries",$strAdminMenu);?>><a href="/admin/mod_countries/countries.php<?echo Odkaz;?>" title="Countries">Countries</a></li>
			   <? } ?>
			   <? if ($users->checkThisUserRight(10,1)==true){ ?>
			   <li<? echo show_active_menu("photogallery",$strAdminMenu);?>><a href="/admin/mod_photogallery/photogallery.php<?echo Odkaz;?>" title="Photogallery">Photogallery</a>
			     <ul>
            <li><a href="/admin/mod_photogallery/photogallery_folders.php<?echo Odkaz;?>" title="Folders">Folders</a></li>
					</ul>
			   </li>
			   
			   <? } ?>
			  
            <? if ($users->checkThisUserRight(33,1)==true){ ?>
            <li<? echo show_active_menu("sponsorship",$strAdminMenu);?>><a href="/admin/mod_sponsorship/sponsorship.php<?echo Odkaz;?>" title="Sponsorship">Sponsorship</a></li>
            <? } ?>

			   <li<? echo show_active_menu("users",$strAdminMenu);?>><a href="/admin/mod_user/users.php<?echo Odkaz;?>" title="Users">Users</a>
					<ul>
            <li><a href="/admin/mod_user/users_add.php<?echo Odkaz;?>" title="Add new user">add new user</a></li>
            <li><a href="/admin/mod_user/users_rules.php<?echo Odkaz;?>" title="User rights">user rights</a></li>
            <li><a href="/admin/mod_user/users_logs.php<?echo Odkaz;?>" title="User logs">user logs</a></li>
					</ul>
        </li>
        
         <? if ($users->checkThisUserRight(3,1)==true){ ?>
         <li<? echo show_active_menu("polls",$strAdminMenu);?>><a href="/admin/mod_polls/polls.php<?echo Odkaz;?>" title="Polls">Polls</a>
            <ul>
              <li><a href="/admin/mod_polls/polls_add.php<?echo Odkaz;?>" title="Add poll">add poll</a></li>
            </ul>
         </li>
         <? } ?>
         
			</ul>
    
