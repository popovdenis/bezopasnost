<script type="text/javascript">
$(function(){
    productsObj.initImageGalleryUploader();
});
</script>
<script type="text/javascript" src="<?=base_url()?>js/highslide/highslide.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>js/highslide/highslide.css" />
<script type="text/javascript">
hs.registerOverlay({
	html: '<div class="closebutton" onclick="return hs.close(this)" title="Close"></div>',
	position: 'top right',
	fade: 2 // fading the semi-transparent overlay looks bad in IE
});

hs.graphicsDir = '<?=base_url()?>js/highslide/graphics/';
hs.wrapperClassName = 'borderless';
</script>
<div>
	<input type="text" id="item_title" name="item_title" value="<?=$item->item_title?>" style="width: 500px;" />
</div>
<div>
	<input type="hidden" name="item_type" id="item_type" value="<?=$item->item_type?>" />
	<input type="hidden" name="item_id" id="item_id" value="<?=$item->item_id?>" />
	<textarea name="post_content" id="post_content" style="width: 100%;"><?=$item->item_content?></textarea>
</div>
<div>
	<a href="#" onclick="javascript:save_item();return false;">Сохранить</a>
</div>
<div class="gallery_block" style="width:950px;">
	<div class="innerTableHeaderGreen">
		<div id="" class="left padAll5">Галлерея статьи</div>
		<div class="padAll5 right">
			<img class="marRight5" src="<?=base_url()?>images/big-plus.gif" alt=""/>
			<a id="" onclick="javascript: return add_form('gallery');" href="#">Добавить Новую Картинку</a>
		</div>
	</div>
	<div id="new_gallery_block" style="float:left;width:700px;margin-bottom:10px;display:none;">
		<div style="width:100%;float:left;">
			<div style="float:left; margin-bottom: 0px;margin-top:0;">
				Описание к картинке:<br />
				<textarea id="new_img_gal_title" style="width:500px;"></textarea>
			</div>
			<div style="float:right;margin-bottom: 0px;">
				<a href="#" id="imggallery_<?=$item->item_id?>">
					<img class="verticalMiddle" alt="" border="0" src="<?=base_url()?>images/upload-green-arrow.gif"/>
					<img class="marLeft5 verticalMiddle" alt="" border="0" onclick="javascript:$('#imggallery_<?=$item->item_id?>').fileUploadStart()" src="<?=base_url()?>images/image-icon.jpg"/>
					<span>Загрузить картинку</span>
				</a><br/>
				<img id="loader" alt="loading..." border="0" src="<?php echo base_url() ?>images/add-note-loader.gif" style="display:none;" />
			</div>
		</div>
	</div>
	<div id="imggallery_img" style="float: left; width: 100%;">
	<?php if(isset($gallery)) echo $gallery; ?>
	</div>
</div>
