<script type="text/javascript">
$(function(){
    productsObj.initImageGalleryUploader();
});
</script>
<script type="text/javascript">
$(function(){
	new AjaxUpload('#imgtitle_<?=$item->item_id?>', {
		// Location of the server-side upload script
		action: '<?=base_url()?>admin/home/upload',
		// File upload name
		name: 'userfile',
		// Additional data to send
		data: {
			item_id : '<?=$item->item_id?>',
			upload_type: 'product_title'
		},
	  responseType: false,
	  onChange: function(file, extension){},
	  onSubmit : function(file , ext){
		    if (! (ext && /^(jpeg|jpg|gif|bmp|png)$/.test(ext))){
		        // extension is not allowed
		        alert('Error: invalid file extension');
		        // cancel upload
		        return false;
		    } else {

		    	$("#item_title_img").html('<img alt="loading..." border="0" src="<?php echo base_url() ?>images/loading-blue.gif" />');
		    }
		} ,
	  onComplete: function(file, response) {
	  	if(response) {
		  	var result = window["eval"]("(" + response + ")");
		  	var file = '<img src="<?=base_url()?>'+result.file_path+'" />';
		  	$('#item_title_img').html(file);
	  	}
	  }
	});
});
</script>
<style type="text/css">
div.jqi{
	width:1000px;
}
</style>
<div style="width:960px;float:left;">
	<div style="float:left;width:700px;">
		<div>
			<div style="margin:0 0 10px 0;">
				<span><strong>Название статьи</strong></span>
				<input type="text" id="item_title" name="item_title" value="<?=htmlspecialchars($item->item_title)?>" style="width:500px;" />
			</div>
			<div style="margin-bottom:5px;">
				<span><strong>Краткое описание статьи</strong></span><br />
				<textarea name="post_preview" id="post_preview" style="min-height:70px;width:695px;"><?=$item->item_preview?></textarea>
			</div>
			<div>
				<span><strong>Описание статьи</strong></span>
				<textarea name="post_content" id="post_content" style="min-height:330px;"><?=$item->item_content?></textarea>
			</div>
			<div style="margin:10px 0 10px 0;">
				<div class="seo_params_block">
                    <span class="seo_params">Seo параметры</span>
                    <div class="seo_title">
                        <span><strong>Title (название)</strong>&nbsp;<i>(50-80 знаков)</i></span>
                        <input type="text" id="item_seo_title" name="item_seo_title" value="<?=$item->item_seo_title?>" style="width:500px;" />
                    </div>
                    <div class="seo_keywords">
                        <span><strong>Keywords (ключевые слова)</strong>&nbsp;<i>(до 250 знаков)</i></span>
                        <input type="text" id="item_seo_keywords" name="item_seo_keywords" value="<?=$item->item_seo_keywords?>" style="width:500px;" />
                    </div>
                    <div class="seo_description">
                        <span><strong>Description (описание)</strong>&nbsp;<i>(150-200 знаков)</i></span>
                        <textarea type="text" id="item_seo_description" name="item_seo_description" cols="97" rows="7"><?=$item->item_seo_description?></textarea>
                    </div>
				</div>
				<div>
					<span><strong>Метки</strong>&nbsp;<i>(для быстрого поиска статей)</i></span><br />
					<input type="text" id="item_marks" name="item_marks" value="<?=$item->item_marks?>" style="width:500px;" />
				</div>
			</div>
		</div>
		<div class="gallery_block">
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
			<div id="imggallery_img">
			<?php if(isset($gallery)) echo $gallery; ?>
			</div>
		</div>
	</div>
	<div style="float: right; margin-right: 5px; width: 235px;padding-right:15px;">
		<div>
			<div style="font-weight:bold;font-size:16px;margin-bottom:15px;color:red;">
				<input type="hidden" id="item_id" value="<?=$item->item_id?>" />
				<a style="color:red;" href="#" onclick="javascript:get_product_item('<?=$item->item_id?>');return false;">Обновить</a>
				<a style="margin-left:10px;color:red;" href="#" onclick="javascript:save_product('<?=$item->item_id?>');return false;">Сохранить</a>
			</div>
			<div style="font-weight:bold;margin-bottom:5px;"><b>Режим просмотра статьи:</b></div>
			<div style="float:left;margin-bottom:7px;">
				<?php if(isset($item->item_mode)) echo '<div style="float:left;position:relative;bottom:7px;"><img src="'.base_url().'images/icons/'.$item->item_mode.'.png" /></div>'; ?>
				<div style="float:left;margin-left:7px;bottom:7px;"><select id="item_mode_<?=$item->item_id?>">
					<option value="open" <?php if($item->item_mode == 'open') echo "selected"; ?>>Опубликована</option>
					<option value="close" <?php if($item->item_mode == 'close') echo "selected"; ?>>Закрыта</option>
				</select></div>
			</div>
			<div id="item_title_img">
			<?php
			if(isset($item->attach_preview_path)) { ?>
				<img alt="" border="0" src="<?=base_url().$item->attach_preview_path?>" />
			<?php } ?>
			</div>
			<div style="float:left;margin-bottom:10px;margin-top:10px;">
				<a href="#" id="imgtitle_<?=$item->item_id?>">
					<img class="verticalMiddle" alt="" border="0" src="<?=base_url()?>images/upload-green-arrow.gif"/>
					<img class="marLeft5 verticalMiddle" alt="" border="0" onclick="javascript:$('#imgtitle_<?=$item->item_id?>').fileUploadStart()" src="<?=base_url()?>images/image-icon.jpg"/>
				<span>Логотип статьи</span>
				</a>
			</div><br/>
			<?php if(isset($categories) && !empty($categories)) { ?>
			<div style="margin: 0pt auto; width: 185px; float: left;">
				<span>Новая категория</span><br/>
				<select id="categories_new">
					<option value="0">Родительская категория</option>
					<?php
						$str_cat = '';
						$indention = '';
						foreach ($categories as $category) {
							$indention = str_repeat("&nbsp;&nbsp;", $category->level);
							$str_cat .= '<option value="'.$category->category_id.'">'.$indention.$category->category_title.'</option>';
						}
						echo $str_cat;
					?>
				</select>
				<input type="text" id="category_title" style="width:180px;"/><br /><a href="#" onclick="javascript:add_category();" style="float:right;">Добавить</a>
			</div>
			<div id="chboxes" style="float: left;overflow-y: auto; height: 400px; overflow-x: hidden; width: 230px;padding-right:15px;margin-top:10px;">
			<?php
				$cat_str = '';
				$level = null;
				foreach ($categories as $index=>$category) {
					$checked = "";
					$level = $category->level;
					unset($category->level);

					if(in_array($category, $items_cats)) $checked = "checked";
					$margin = 10*$level;
					$style = 'style="margin-left:'.$margin.'px;"';
					$cat_str .= '<div '.$style.'><input type="checkbox" id="ch_door" value="'.$category->category_id.'" '.$checked.' />
							'.$category->category_title.'</div>';
				}
				echo $cat_str;
			?>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
