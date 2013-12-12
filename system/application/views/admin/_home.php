<?php require_once("_head.php"); ?>
<script type="text/javascript" src="<?php echo base_url(); ?>js/js_ajax/ajax_admin.js"></script>
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
		<div id="tabs" style="margin:0 auto;width:1100px;">
			<?php require_once("_tabs.php"); ?>
			<div id="tabs-1">
				<div id="about"></div>		
			</div>
			<div id="tabs-2">
				<div id="information"></div>
			</div>
			<div id="tabs-3">
				<div id="partners"></div>
			</div>
			<div id="tabs-4">
				<div id="products"></div>
			</div>
			<div id="tabs-5">
				<div id="contacts"></div>
			</div>
            <div id="tabs-6">
                <div id="gallery"></div>
            </div>
			<div id="tabs-7">
				<div id="settings"></div>
			</div>
            <div id="tabs-8">
				<div id="tools"></div>
			</div>
		</div>		
	</body>
</html>
