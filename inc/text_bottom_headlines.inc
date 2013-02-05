          <div id="headlines_left">
            
            <div class="header headlines"><span>Headlines</span></div>
            
            <?php
              $query="SELECT id,header, perex FROM articles WHERE (show_item=1 OR is_top=1) AND pub_date<=NOW() AND pub_date<=NOW() AND (expire_date>=NOW() OR expire_date='0000-00-00 00:00:00') order by is_top DESC,date_time DESC  LIMIT 2";
              //echo $query; 
              if ($con->GetQueryNum($query)>0){
                  echo '<div class="box">'; 
                  $result = $con->SelectQuery($query);
                  $counter=0;
                  while($write = $result->fetch_array()){
                    if($counter==0) echo '<div class="article middle">'; else echo '<div class="article">';
                      echo '<h4><a href="/article/'.get_url_text($write['header'],$write['id']).'" title="Read article: '.$write['header'].'">'.$write['header'].'</a></h4>';
                      echo '<p>';
                      if (strlen($write['perex'])>170){
	                     $strPerex=strip_tags($write['perex']);
                       $strPerex=split(" ",$strPerex);
                       for ($i=0;$i<=40;$i++){
                          echo $strPerex[$i].' ';
                      }
                      echo '...';
                    }else{
                      echo  $write['perex'];  
                    } 
                    echo ' <span><a href="/article/'.get_url_text($write['header'],$write['id']).'" title="Read article: '.$write['header'].'">Read&nbsp;more&raquo;</a></span></p>';
                    echo '</div>';
                    $counter++;
                  }
                  echo '</div>';
              }
              ?>
            
            
          </div>
          
          <div id="headlines_right">
            
          <div id="box_search">
          <form action="/players.html">
		        <div>
		          <input type="text" name="player_search" id="search_input" value="Type in player name" onclick="this.form.player_search.value='';" />
        		  <input type="submit" id="search_submit" value="" />
        		  <input type="hidden" name="write_player_search" value="1" />
        		  <div class="clear">&nbsp;</div>
        		  <a href="/players.html?advanced=1" title="Advanced player search">Advanced player search</a>
	         </div>
		      </form>
          </div>
            
          <div class="header folow_us"><span>Folow_us</span></div>
          <div class="box">
            <a onclick="return !window.open(this.href);" href="http://www.facebook.com/pages/Eurohockeycom/125512654164494" title="Facebook"><img src="/img/logo_bottom_facebook.jpg" alt="Facebook" height="25" width="121" class="toleft" /></a>
            <a onclick="return !window.open(this.href);" href="http://www.twitter.com/eurohockey" title="Twitter"><img src="/img/logo_bottom_twitter.jpg" alt="Twitter" height="25" width="110" class="toleft" /></a>
          </div>
          
          </div>