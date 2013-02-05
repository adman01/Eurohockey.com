<?php
require_once("../inc/global.inc");
require_once("lang/".Web_language."/menu.php");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(5,1,0,0,0);

$input = new Input_filter();

//prepnuti
if ($_GET['action']=="switch" and $users->checkUserRight(3)){
    $id=$_GET['id'];
    $list_number=$_GET['list_number'];
    $query = "SELECT show_item FROM main_menu WHERE id=".$id."";
    $result = $con->SelectQuery($query);
    if ($con->GetQueryNum($query)>0){
      $write = $result->fetch_array();
      $zobraz=$write["show_item"];
        if ($zobraz==1) $query = "UPDATE main_menu set show_item=0 WHERE id=".$id."";
        if ($zobraz==0) $query = "UPDATE main_menu set show_item=1 WHERE id=".$id."";
        $con->RunQuery($query);
        header("Location: menu.php".Odkaz."&list_number=".$list_number);      
    }
}

require_once("inc/head.inc");
echo $head->setTitle(langTitle);
echo $head->setEndHead();


?>
<body onload="javascript:logout('<?php echo $users->sesid ;?>', '<?php echo MaxTimeConnection ;?>')">

<div id="layout">
  <div id="mainmenu">
    <?php require_once("../inc/menu.inc"); ?>
  </div>
  <div id="main">
  
    <h1><?php echo langH1; ?></h1>
    
    <?php
    //pridat nove menu
    if ($_POST['action']=="add" and $users->checkUserRight(2)){
    
    $name=$_POST['name'];
    $name=$input->valid_text($name,true,true);
    $url=$_POST['url'];
    $url=$input->valid_text($url,true,true);
    $id_level=$_POST['id_level'];
    $order_item=$_POST['order_item'];
    $order_item=$input->check_number($order_item);
    $id_language=$_POST['id_language'];
    $id_language=$input->check_number($id_language);
    $show=$_POST['show'];
    $show=$input->check_number($show);
    
    if (isset($name) AND isset($url) AND isset($order_item)){
              $query="INSERT INTO main_menu (name,url,id_up,order_item,id_language,show_item)
                      VALUES ('".$name."','".$url."',".$id_level.",".$order_item.",".$id_language.",".$show.")
              ";
              $con->RunQuery($query);
              echo '<p>'.langActInsert.' <a href="menu.php'.Odkaz.'">'.langActOKanchor.'</a></p>'; 
        }else{
          echo '<p>'.langUpdNodata.' <a href="menu.php'.Odkaz.'">'.langActOKanchor.'</a></p>';
        }
    }
    
    //upravit menu
    if ($_POST['action']=="update" and $users->checkUserRight(3)){
        $name=$_POST['name'];
        $name=$input->valid_text($name,true,true);
        $url=$_POST['url'];
        $url=$input->valid_text($url,true,true);
        $id_level=$_POST['id_level'];
        $order_item=$_POST['order_item'];
        $order_item=$input->check_number($order_item);
        $id_language=$_POST['id_language'];
        $id_language=$input->check_number($id_language);
        $show=$_POST['show'];
        $show=$input->check_number($show);
        $id=$_POST['id'];
        
        if (isset($id) AND isset($name) AND isset($url) AND isset($order_item)){
          
            $query="UPDATE main_menu SET
                        name='".$name."',url='".$url."',id_up=".$id_level.",order_item=".$order_item.",id_language=".$id_language.",show_item=".$show."
                        WHERE id=".$id."
            ";
            //echo $query; 
            $con->RunQuery($query);
            echo '<p>'.langActUpdate.' <a href="menu.php'.Odkaz.'">'.langActOKanchor.'</a></p>';
        }
        else{
          echo '<p>'.langUpdNodata.' <a href="menu.php'.Odkaz.'">'.langActOKanchor.'</a></p>';
        }
     } 
      
    //smazat menu
    if ($_GET['action']=="delete" and $users->checkUserRight(4)){
    
      if (isset($_GET['id'])){
        $id=$_GET['id'];
        $query="DELETE FROM main_menu WHERE id=".$id;
        if ($con->RunQuery($query)==true){
                echo '<p>'.langActDelete.' <a href="menu.php'.Odkaz.'">'.langActOKanchor.'</a></p>';
              } 
        }
      }
    ?>
  
  </div>
</div>
</body>
</html>
