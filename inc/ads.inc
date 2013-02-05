<?php
//---- show ads code by ID
function show_ads($id){
  
  switch ($id){
    case "top_main":
      //---- hlavni pozice nahore nad logem
      echo get_static_text(21);
    
    break;
    
    case "ads_index_middle":
      //---- na hlavni strance POD recent articles
       echo get_static_text(22);
      
    break;
    
    case "ads_col_right":
      //---- v pravem sloupci
       echo get_static_text(23);
    
    break;
    
    case "ads_col_right_player":
      //---- v pravem sloupci v hrackem profilu nahore
       echo get_static_text(28);
    
    break;
    
    case "ads_col_right_player2":
      //---- v pravem sloupci v hrackem profilu dole
       echo get_static_text(41);
    
    break;
    
    case "ads_col_right_subpages":
      //---- v pravem sloupci ligove, klubove atd podstrance
       echo get_static_text(42);
    
    break;
    
    
    case "ads_article_perex":
      //---- pod perexem
       echo get_static_text(43);
    
    break;
    
    
    
  }

}
?>