<?php if ($boolGamesTodayBox){ ?>

        <div class="header games_today"><span>Games today</span><span class="toright"><a href="/games.html" title="Show all games">Show all games&raquo;</a></span></div>
        <div class="box countries_leagues">
            
            <p>
            <?php 
            $strLink="&amp;id_show=2&amp;date_from=".date("Y-m-d")."&amp;date_to=".date("Y-m-d");
            echo get_flag(strtolower('cze'),15,11);
            ?>
              <strong>Czech rep.:&nbsp;</strong> 
              <?php $intGamesCount=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE date=date(NOW()) AND id_league=7"); ?>
              <span><a href="/games.html?id_league=130-extraliga-cze-.html<?php echo $strLink;?>" title="Extraliga">Extraliga (<?php echo $intGamesCount;?>)</a> | </span>
              <?php $intGamesCount=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE date=date(NOW()) AND id_league=76"); ?>
              <span><a href="/games.html?id_league=199-1liga-cze-.html<?php echo $strLink;?>" title="1.liga">1.liga (<?php echo $intGamesCount;?>)</a> | </span>
              <?php $intGamesCount=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE date=date(NOW()) AND id_league=85"); ?>
              <span><a href="/games.html?id_league=208-2liga-cze-.html<?php echo $strLink;?>" title="2.liga">2.liga (<?php echo $intGamesCount;?>)</a></span>
            </p>
            
            <p>
            <?php echo get_flag(strtolower('fin'),15,11);?>
              <strong>Finland:&nbsp;</strong>
              <?php $intGamesCount=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE date=date(NOW()) AND id_league=5"); ?> 
              <span><a href="/games.html?id_league=128-sm-liiga.html<?php echo $strLink;?>" title="SM-Liiga">SM-Liiga (<?php echo $intGamesCount;?>)</a> | </span>
              <?php $intGamesCount=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE date=date(NOW()) AND id_league=246"); ?>
              <span><a href="/games.html?id_league=369-mestis.html<?php echo $strLink;?>" title="Mestis">Mestis (<?php echo $intGamesCount;?>)</a> | </span>
              <?php $intGamesCount=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE date=date(NOW()) AND id_league=70"); ?>
              <span><a href="/games.html?id_league=193-suomi-sarja.html<?php echo $strLink;?>" title="Sarja">Sarja (<?php echo $intGamesCount;?>)</a></span>
            </p>
            
            <p>
            <?php echo get_flag(strtolower('ger'),15,11);?>
              <strong>Germany:&nbsp;</strong>
              <?php $intGamesCount=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE date=date(NOW()) AND id_league=6"); ?> 
              <span><a href="/games.html?id_league=129-del.html<?php echo $strLink;?>" title="DEL">DEL (<?php echo $intGamesCount;?>)</a> | </span>
              <?php $intGamesCount=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE date=date(NOW()) AND id_league=22"); ?>
              <span><a href="/games.html?id_league=145-2-bundesliga.html<?php echo $strLink;?>" title="2.Bundesliga">2.Bundesliga (<?php echo $intGamesCount;?>)</a></span>
            </p>
            
            <p>
            <?php echo get_flag(strtolower('rus'),15,11);?>
              <strong>Russia:&nbsp;</strong>
              <?php $intGamesCount=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE date=date(NOW()) AND id_league=272"); ?> 
              <span><a href="/games.html?id_league=395-khl.html<?php echo $strLink;?>" title="KHL">KHL (<?php echo $intGamesCount;?>)</a> | </span>
              <?php $intGamesCount=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE date=date(NOW()) AND id_league=65"); ?>
              <span><a href="/games.html?id_league=188-vysshaya-liga-rus-2-.html<?php echo $strLink;?>" title="Vysshaya liga">Vysshaya liga (<?php echo $intGamesCount;?>)</a></span>
            </p>
            
            <p>
            <?php echo get_flag(strtolower('svk'),15,11);?>
              <strong>Slovakia:&nbsp;</strong>
              <?php $intGamesCount=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE date=date(NOW()) AND id_league=8"); ?> 
              <span><a href="/games.html?id_league=131-extraliga-svk-.html<?php echo $strLink;?>" title="Extraliga">Extraliga (<?php echo $intGamesCount;?>)</a> | </span>
              <?php $intGamesCount=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE date=date(NOW()) AND id_league=77"); ?>
              <span><a href="/games.html?id_league=200-1liga-svk-.html<?php echo $strLink;?>" title="1.liga">1.liga (<?php echo $intGamesCount;?>)</a></span>
            </p>
            
            <p>
            <?php echo get_flag(strtolower('swe'),15,11);?>
              <strong>Sweden:&nbsp;</strong>
              <?php $intGamesCount=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE date=date(NOW()) AND id_league=4"); ?> 
              <span><a href="/games.html?id_league=127-elitserien.html<?php echo $strLink;?>" title="Elitserien">Elitserien (<?php echo $intGamesCount;?>)</a> | </span>
              <?php $intGamesCount=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE date=date(NOW()) AND id_league=232"); ?>
              <span><a href="/games.html?id_league=355-hockeyallsvenskan.html<?php echo $strLink;?>" title="Allsvenskan">Allsvenskan (<?php echo $intGamesCount;?>)</a></span>
            </p>
            
            <p>
            <?php echo get_flag(strtolower('sui'),15,11);?>
              <strong>Switzerland:&nbsp;</strong>
              <?php $intGamesCount=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE date=date(NOW()) AND id_league=1"); ?> 
              <span><a href="/games.html?id_league=124-nationalliga-a.html<?php echo $strLink;?>" title="NLA">NLA (<?php echo $intGamesCount;?>)</a> | </span>
              <?php $intGamesCount=$con->GetSQLSingleResult("SELECT count(*) as item FROM games WHERE date=date(NOW()) AND id_league=2"); ?>
              <span><a href="/games.html?id_league=125-nationalliga-b.html<?php echo $strLink;?>" title="NLB">NLB (<?php echo $intGamesCount;?>)</a></span>
            </p>
          
         </div>
<?php } ?>