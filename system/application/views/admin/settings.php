<?php require_once("_head.php"); ?>
<script type="text/javascript" src="<?php echo base_url(); ?>js/js_ajax/adminObj.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/js_ajax/ajax_gallery.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$(".btn-slide").click(function(){
		$("#panel").slideToggle("slow");
		$(this).toggleClass("active"); return false;
	});
	get_page('about');
});
</script>
<script type="text/javascript">
$(function() {
	$("#datepicker_new").datepicker({showOn: 'button', buttonImage: '<?=base_url()?>images/icons/calendar.png', buttonImageOnly: true});
});
</script>
<script type="text/javascript" src="<?=base_url()?>js/highslide/highslide.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/highslide/highslide-with-gallery.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>js/highslide/highslide.css" />
<script type="text/javascript">
	hs.graphicsDir = '<?=base_url()?>js/highslide/graphics/';
	hs.wrapperClassName = 'wide-border';
</script>

<style type="text/css">
	a:hover{text-decoration: underline;}
	.acc_block{width: 250px;float: right;}
	.acc_content{text-align:left;margin-left:10px;}
</style>
		<?php require_once("help.php"); ?>
        <div style="margin: 0 auto;width: 1000px;">
            <?php require_once("_tabs.php"); ?>
            <?=$page?></div>
	</body>
</html>
