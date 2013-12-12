<script type="text/javascript">
	var oFCKeditor = new FCKeditor("post_content"); // привязка к textarea с id="body"
    oFCKeditor.ToolbarSet="Default"; // число кнопочек на инструментальной панели
    oFCKeditor.BasePath="<?=base_url()?>js/fckeditor/"; //путь к fckeditor
    oFCKeditor.Height = "345";
    oFCKeditor.ReplaceTextarea(); 
</script>
<div style="margin-bottom:10px;">
	<label><strong>Полное название</strong></label><input type="text" id="item_title" name="item_title" value="" style="width:500px;" />
</div>
<div style="margin-bottom:20px;">
	<label><strong>Короткое название</strong></label><input type="text" id="item_shot_title" name="item_shot_title" value="" style="width:500px;" />
</div>
<div>
	<input type="hidden" name="item_type" id="item_type" value="<?=$item_type?>" />
	<textarea name="post_content" id="post_content" style="width: 100%;min-height:330px;">&nbsp;</textarea>
</div>
<?php
	if(isset($save_btn) && $save_btn == true) {
	?>
	<div>
		<a href="#" onclick="javascript:add_item();return false;">Сохранить</a>
	</div>
	<?php
	}
?>