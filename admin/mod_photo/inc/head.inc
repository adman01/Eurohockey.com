<?php
//nastaveni hlavicky
$head = new HTMLHead();
echo $head->setStartHead();
echo $head->setCharset(HeaderCharset);
echo $head->setLanguage(HeaderLanguage);
echo $head->setProject(HeaderProject);
echo $head->setGraphic(HeaderGraphic);
echo $head->setCoding(HeaderCoding);
echo $head->setPrograming(HeaderPrograming);
echo $head->setCopyright(HeaderCopyright);
echo $head->setKeywords(HeaderKeywords);
echo $head->setDescription(HeaderDescription);
echo $head->setJavascriptExtendFile("../inc/js/global.js");
echo $head->setJavascriptExtendFile("../inc/lang/".Web_language."/global.js");
echo $head->setCSSExtendFile("../inc/styles.css",1);
echo $head->setCSSExtendFile("../inc/print.css",2);
echo $head->setRSSFile("../inc/rss.php","RSS");
echo $head->setFavicon("../img/favicon.ico");
?>