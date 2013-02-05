<? 

  $query="SELECT * FROM main_menu WHERE id_up=1 or id_up=0 ORDER BY order_item, id ASC";

  $intCount=$con->GetQueryNum($query);

  $i=0;

  if ($intCount>0){

 	  $result = $con->SelectQuery($query);

    while($write = $result->fetch_array())

	 {

	   echo '<a href="'.$write['url'].'">'.$write['name'].'</a>';

	   $i++;

	   if ($i<$intCount) echo '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';

       

	 }

	 

	}

?> | <a href="http://eurohockey.com/text/156-test.html">Social</a>