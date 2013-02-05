<?php
require_once("../inc/global.inc");
require_once("lang/".Web_language."/photo.php");
require_once("inc/config.inc");
require_once("../inc/init.inc");
// nastaveni uz. prav stranky = IDskupinyPrav/read/add/update/delete
$users->setUserRight(10,1,1,0,0);

require_once("inc/head.inc");
echo $head->setTitle(langTitle);
echo $head->setJavascriptExtendFile("lang/".Web_language."/photo.js");
echo $head->setJavascriptExtendFile("js/photo.js");
echo $head->setEndHead();
$id_input=$_GET['id_input'];
?>
<body class="index">
<p class="center"><a href="#" onclick="self.close()">[zavřít okno]</a></p>
<hr />
<h2>prehled sezon a zapasu...</h2>
<ul>
<?php
echo '
<li><a href="#" onclick="self.opener.chooseItem(20,\'Vitkovice - Vsetin\',\''.$id_input.'\')">Vitkovice - Vsetin</a></li>
<li><a href="#" onclick="self.opener.chooseItem(30,\'Sparta - Slavie\',\''.$id_input.'\')">Sparta - Slavie</a></li>
';
?>
</ul>
<hr />
</body>
</html>
