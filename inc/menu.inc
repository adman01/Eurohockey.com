<div class="menu_ul" id="menu_ul">
		<? 
    $query="SELECT * FROM main_menu WHERE id_up=1 or id_up=0 ORDER BY order_item, id ASC";
    $intCount=$con->GetQueryNum($query);
    $i=1;
    if ($intCount>0){
 	  $result = $con->SelectQuery($query);
    while($write = $result->fetch_array())
	   {
	     echo '<a id="menu'.$i.'" href="'.$write['url'].'" title="'.$write['name'].'"><span>'.$write['name'].'</span></a>';
	     $i++;
  	 }
	}
?>
</div>