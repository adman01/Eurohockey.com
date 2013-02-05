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
if (empty($strHeaderKeywords)) $strHeaderKeywords=HeaderKeywords;
if (empty($strHeaderDescription)) $strHeaderDescription=HeaderDescription;
echo $head->setKeywords(strip_tags($strHeaderKeywords));
echo $head->setDescription(strip_tags($strHeaderDescription));
?>
<link rel="stylesheet" href="/inc/styles.css" type="text/css" media="screen, projection" />
<script src="/inc/global.js" type="text/javascript"></script>
<script src="/inc/ajax.js" type="text/javascript"></script>
<script src="/inc/jquery/jquery-1.4.2.min.js" type="text/javascript" ></script>
<?php if ($boolIndexSlider) { ?>                       
  <script src="/inc/jquery/_jquery-ui-full-1.5.2.min.js" type="text/javascript" charset="utf-8"></script>
<?php }else{ ?>
  <script src="/inc/jquery/jquery-ui-1.8.6.custom.min.js" type="text/javascript" charset="utf-8"></script>
<?php } ?>
<script src="/inc/table_sorter/jquery.tablesorter.min.js" type="text/javascript"></script>
<script src="/admin/inc/js/toggle.js" type="text/javascript"></script>
<script src="/inc/menu_blend/jquery.blend.js" type="text/javascript"></script>
<script type="text/javascript">
        
        $(document).ready(function(){
            $("#menu_ul a").blend();
            
        });
       
</script>
<script src="/inc/cufon/cufon-yui.js" type="text/javascript"></script>
<script src="/inc/cufon/Aeroportal_700.font.js" type="text/javascript"></script>
<script src="/inc/cufon/cufon.js" type="text/javascript"></script>

<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
{lang: 'en'}
</script>
<link rel="image_src" href="/img/default.jpg" />