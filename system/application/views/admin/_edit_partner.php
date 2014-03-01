<script type="text/javascript">
$(function(){
    productsObj.initImageTitleUploader();
});
</script>
<div>
	<input type="text" id="item_title" name="item_title" value="<?=$item->item_title?>" style="width: 500px;" />
</div>
<div style="width:960px;">
	<div style="float:left;width:720px;">
		<div>
			<input type="hidden" name="item_type" id="item_type" value="<?=$item->item_type?>" />
			<input type="hidden" name="item_id" id="item_id" value="<?=$item->item_id?>" />
			<textarea name="post_content" id="post_content" style="width: 100%;"><?=$item->item_content?></textarea>
		</div>
		<div>
			<a href="#" onclick="javascript:save_item();return false;">Сохранить</a>
		</div>
	</div>
	<div style="float:right;width:225px;margin-right:5px;">
		<div id="item_title_img">
		<?php if(isset($item->attach_preview_path)) { ?>
			<img alt="" border="0" src="<?=base_url().$item->attach_preview_path?>" />
		<?php } ?>
		</div>
		<div style="float:left;margin-bottom:10px;">
			<a href="#" id="imgtitle_<?=$item->item_id?>">
				<img class="verticalMiddle" alt="" border="0" src="<?=base_url()?>images/upload-green-arrow.gif"/>
				<img class="marLeft5 verticalMiddle" alt="" border="0" onclick="javascript:$('#imgtitle_<?=$item->item_id?>').fileUploadStart()" src="<?=base_url()?>images/image-icon.jpg"/>
			<span>Image title</span>
			</a>
		</div>
	</div>
</div>
