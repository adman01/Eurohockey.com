<?php
session_start();
$znak = $_SESSION['captcha']; //převedení kódu ze session
header("Content-type: image/png"); //hlavička souboru - soubor se bude tvářit jako obrázek png
        $podklad = ImageCreateFromPNG("inc/captcha/podklad.png"); // obrázek se bude tvořit z našeho obrázku
        $color = ImageColorAllocate($podklad, rand(0,30), rand(10,20), rand(0,255)); // vygenerujeme si náhodnou barvu (R,G,B)
        $color1 = ImageColorAllocate($podklad, rand(0,10), rand(40,60), rand(0,255)); // vygenerujeme si jinou náhodnou barvu (R,G,B)
        $font = "inc/captcha/font.ttf"; //cesta k soboru písma
        $barva = $color;
/*následující řádek popíšu slovy:
ImageTTFText(obrazek, velikost pisma, úhel pod kterým se zobrazí, vzdálenost z leva, vzdálenost z hora, barva, písmo, znak); */
        ImageTTFText($podklad, rand(14,16), rand(0,20), 5, 20, $barva, $font, $znak[0]); 
        $barva = $color1;
        ImageTTFText($podklad, rand(14,16), rand(0,20), 25, 20, $barva, $font, $znak[1]);
        $barva = $color;
        ImageTTFText($podklad, rand(14,16), rand(0,20), 45, 20, $barva, $font, $znak[2]);
        $barva = $color1;
        ImageTTFText($podklad, rand(14,16), rand(0,20), 65, 20, $barva, $font, $znak[3]);

imagecopy($podklad, $podklad, 0, 0, 0, 0, 220, 30); // složíme a zobrazíme obrázek s kódem
ImagePNG($podklad);
ImageDestroy($podklad);
?>

