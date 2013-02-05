<link href="/inc/pirobox/css_pirobox/demo3/style.css" class="piro_style" media="screen" title="white" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/inc/pirobox/js/pirobox.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$().piroBox({
			my_speed: 400, //animation speed
			bg_alpha: 0.1, //background opacity
			slideShow : true, // true == slideshow on, false == slideshow off
			slideSpeed : 4, //slideshow duration in seconds(3 to 6 Recommended)
			close_all : '.piro_close,.piro_overlay'// add class .piro_overlay(with comma)if you want overlay click close piroBox

	});
});
</script>