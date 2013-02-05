<?php
if (TinyMCE==true){
  echo $head->setJavascriptExtendFile("../inc/tiny_mce/tiny_mce.js");
	echo $head->setExtendline('<script language="javascript" type="text/javascript">');
	echo $head->setExtendline('tinyMCE.init({');
	echo $head->setExtendline('mode : "exact",');
	echo $head->setExtendline('	elements : "elm1,elm2,elm3,elm4,elm5,elm6,elm7",');
  //echo $head->setExtendline('mode : "textareas",');
	echo $head->setExtendline('theme : "advanced",');
	echo $head->setExtendline('plugins : "paste,table",');
	echo $head->setExtendline('table_cell_styles : "Pekna vyplnene=item;",');
	echo $head->setExtendline('table_styles : "Pekna tabulka=nice;",');
	echo $head->setExtendline('theme_advanced_buttons1 : "removeformat,|,formatselect,bold,|,bullist,numlist,|,hr,|,undo,redo,link,unlink,image,|,code,|,pastetext,|,myimagebuttonleft,myimagebuttonright",');
	echo $head->setExtendline('theme_advanced_buttons2 : "fontsizeselect,forecolor,backcolor,|,tablecontrols,|",');
	echo $head->setExtendline('theme_advanced_buttons3 : "",');
	echo $head->setExtendline('theme_advanced_toolbar_location : "top",');
	echo $head->setExtendline('theme_advanced_toolbar_align : "left",');
	echo $head->setExtendline('theme_advanced_path_location : "bottom",');
	echo $head->setExtendline('theme_advanced_resizing : "true",');
	echo $head->setExtendline("
	     setup : function(ed) {
        ed.addButton('myimagebuttonleft', {
        title : 'Insert image code (left align)',
        image : '/admin/inc/design/ico-arrow-left.gif',
        onclick : function() {
          ed.focus();
          ed.selection.setContent('<span class=\"image_toleft\"><img src=\"/admin/inc/img/ico-img.gif\" alt=\"image\" /></span>');
        }
        });
        ed.addButton('myimagebuttonright', {
        title : 'Insert image code (right align)',
        image : '/admin/inc/design/ico-arrow-right.gif',
        onclick : function() {
          ed.focus();
          ed.selection.setContent('<span class=\"image_right\"><img src=\"/admin/inc/img/ico-img.gif\" alt=\"image\" /></span>');
        }
        });
    },
	");
	echo $head->setExtendline('language : "en",');
	echo $head->setExtendline('apply_source_formatting : true,');
	echo $head->setExtendline('entity_encoding : "raw",');
	echo $head->setExtendline('width : "890",');
	echo $head->setExtendline('height : "150",');
	echo $head->setExtendline('paste_use_dialog : false,');
	echo $head->setExtendline('paste_auto_cleanup_on_paste : true,');
	echo $head->setExtendline('paste_strip_class_attributes : "none"');
	//echo $head->setExtendline('theme_advanced_resizing : true');
	
	//file_browser_callback : "fileBrowserCallBack",
	//echo $head->setExtendline('content_css : "../../styles_admin.css"');
	echo $head->setExtendline('})');
	echo $head->setExtendline('</script>');
}
?>
