<?php
//nastaveni modulu fotogalerie
Define(PhotoFolder,"/photo");   //slozka ve ktere budou fotky
Define(PhotoPassword,false);     //pouzit moznost zvolit zaheslovani slozky ? 
Define(PhotoGame,false);         //moznost priradit k fotoslozce urcity zapas
Define(PhotoFileMaxUpload,5);   //max pocet zaroven uploadovanych fotek
Define(PhotoMaxFileSize,"");    //max velikost jedne uploadovane fotky
//povolene typy souboru
$PhotoAlowedFileType[0]="image/png"; 
$PhotoAlowedFileType[1]="image/gif";
$PhotoAlowedFileType[2]="image/jpeg";
$PhotoAlowedFileType[3]="image/jpg";
?>
