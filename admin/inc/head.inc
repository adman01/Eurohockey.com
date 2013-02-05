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

echo $head->setCSSExtendFile("inc/css/reset.css",1);
echo $head->setCSSExtendFile("inc/css/main.css",1);
echo $head->setExtendline('<link rel="stylesheet" media="screen,projection" type="text/css" href="inc/css/2col.css" title="2col" /> <!-- DEFAULT: 2 COLUMNS -->');
echo $head->setExtendline('<link rel="alternate stylesheet" media="screen,projection" type="text/css" href="inc/css/1col.css" title="1col" /> <!-- ALTERNATE: 1 COLUMN -->');
echo $head->setExtendline('<!--[if lte IE 6]><link rel="stylesheet" media="screen,projection" type="text/css" href="inc/css/main-ie6.css" /><![endif]--> <!-- MSIE6 -->');
echo $head->setCSSExtendFile("inc/css/style.css",1);
echo $head->setCSSExtendFile("inc/css/mystyle.css",1);

echo $head->setJavascriptExtendFile("inc/js/global.js");
echo $head->setJavascriptExtendFile("inc/js/toggle.js");
echo $head->setJavascriptExtendFile("inc/js/jquery.js");
echo $head->setJavascriptExtendFile("inc/js/jquery.tablesorter.js");
echo $head->setJavascriptExtendFile("inc/js/jquery.switcher.js");
echo $head->setJavascriptExtendFile("inc/js/ui.core.js");
echo $head->setJavascriptExtendFile("inc/js/ui.tabs.js");
echo $head->setJavascriptExtendFile("inc/js/jquery.validate.js");
echo $head->setJavascriptExtendFile("inc/lang/".Web_language."/global.js");

echo $head->setFavicon("../img/favicon.png");
?>


