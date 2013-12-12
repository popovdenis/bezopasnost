<script type="text/javascript">
	var oFCKeditor = new FCKeditor("new_post_content"); // привязка к textarea с id="body"
	oFCKeditor.ToolbarSet="Default"; // число кнопочек на инструментальной панели
	oFCKeditor.BasePath="<?=base_url()?>js/fckeditor/"; //путь к fckeditor
	oFCKeditor.Height = "200";
	oFCKeditor.ReplaceTextarea();
<?php if($item_type == 'products') { ?>
	var oFCKeditor = new FCKeditor("new_item_charecters"); // привязка к textarea с id="body"
	oFCKeditor.ToolbarSet="Default"; // число кнопочек на инструментальной панели
	oFCKeditor.BasePath="<?=base_url()?>js/fckeditor/"; //путь к fckeditor
	oFCKeditor.Height = "200";
	oFCKeditor.ReplaceTextarea();
<?php } ?>
</script>
<script type="text/javascript">
$(function() {
	$("#datepicker_<?=$item_type?>").datepicker({
		showOn: 'button', 
		buttonImage: '<?=base_url()?>images/icons/calendar.png', 
		buttonImageOnly: true
	});
});
</script>

<input type="hidden" id="item_type" name="item_type" value="<?=$item_type?>" />
<div style="float:left;margin-bottom:25px;width:995px;">
	<div style="border-left:1px solid #CCCCCC;float:left;padding-left:10px;padding-top:10px;width:720px;padding:10px 0 10px 10px;">
		<div>
			<div style="margin:0 0 10px 0;">
				<span><strong>Название статьи</strong></span>
				<input type="text" id="new_item_title" name="new_item_title" value="" style="width:500px;" />
			</div>
			<div>
				<span><strong>Краткое описание статьи</strong></span>
				<textarea name="new_post_preview" id="new_post_preview" style="min-height:70px;width:715px;">&nbsp;</textarea>
			</div>
			<?php if($item_type == 'products') { ?>
			<div class="product_element">
				<span><strong>Характеристики</strong></span><br />
				<textarea name="new_item_charecters" id="new_item_charecters" style="min-height:70px;width:775px;"></textarea>
			</div>
			<?php } ?>
			<div>
				<span><strong>Описание статьи</strong></span>
				<textarea name="new_post_content" id="new_post_content" style="min-height:330px;">&nbsp;</textarea>
			</div>			
			<div style="margin:10px 0 10px 0;">
				<div class="seo_params_block">
                    <span class="seo_params">Seo параметры</span>
                    <div class="seo_title">
                        <span><strong>Title (название)</strong>&nbsp;<i>(50-80 знаков)</i></span>
                        <input type="text" id="item_seo_title" name="item_seo_title" value="" style="width:500px;" />
                    </div>
                    <div class="seo_keywords">
                        <span><strong>Keywords (ключевые слова)</strong>&nbsp;<i>(до 250 знаков)</i></span>
                        <input type="text" id="item_seo_keywords" name="item_seo_keywords" value="" style="width:500px;" />
                    </div>
                    <div class="seo_description">
                        <span><strong>Description (описание)</strong>&nbsp;<i>(150-200 знаков)</i></span>
                        <textarea type="text" id="item_seo_description" name="item_seo_description" cols="97" rows="7"></textarea>
                    </div>
				</div>
				<div>
					<span><strong>Метки</strong>&nbsp;<i>(для быстрого поиска статей)</i></span><br />
					<input type="text" id="new_item_marks" name="new_item_marks" value="" style="width:500px;" />
				</div>				
			</div>
		</div>
		<div style="background-color:#D2691E;font-weight:bold;padding-bottom:5px;padding-left:12px;padding-top:5px;width:75px;">
			<a href="#" onclick="javascript:add_item('<?=$item_type?>');return false;" style="color:#FFFFFF;">Сохранить</a>
		</div>
	</div>	
	<div style="float: right; margin-right: 5px; overflow-y: auto; height: 570px; overflow-x: hidden; width: 235px;padding-right:15px;padding-top:10px;">
		<div>
			<div style="font-weight:bold;margin-bottom:5px;"><b>Режим просмотра статьи:</b></div>
			<div style="float:left;margin-bottom:7px;">
				<div style="float:left;margin-left:7px;bottom:7px;"><select id="new_item_mode">
					<option value="open">Опубликована</option>
					<option value="close">Закрыта</option>
				</select></div>				
			</div>
			<div style="font-weight:bold;margin-bottom:5px;"><b>Дата опубликования статьи:</b></div>
			<div style="float:left;margin-bottom:7px;">				
				<div class="datepicker_block">
					<input type="text" id="datepicker_<?=$item_type?>"><br />
					<input style="width:20px;" type="text" id="hour_<?=$item_type?>" value="00"> - 
					<input style="width:20px;" type="text" id="minute_<?=$item_type?>" value="00">
				</div>				
			</div>
			<?php
			/*echo "<pre>";
				var_dump($categories);
			echo "</pre>";*/
			if(isset($categories) && $categories != null) { ?>
			<div style="margin: 0pt auto; width: 185px; float: left;">
				<span>Категория продукта</span>				
			</div>
			<div id="chboxes" style="float: left;margin-left:10px;">
			<?php
				
				$cat_str = '';				
				$cat_str .= '<div style="position:relative;right:12px;"><input type="checkbox" id="ch_door" value="'.$category_main->category_id.'" />'.$category_main->category_title.'</div>';
				
				$level = null;
				foreach ($categories as $index=>$category) {
					$margin = 10*$category->level; 
					$style = 'style="margin-left:'.$margin.'px;"';
					$cat_str .= '<div '.$style.'><input type="checkbox" id="ch_door" value="'.$category->category_id.'" />
							'.$category->category_title.'</div>';
				}
				echo $cat_str;			
			?>
			</div>
			<?php } ?>
		</div>
	</div>	
</div>