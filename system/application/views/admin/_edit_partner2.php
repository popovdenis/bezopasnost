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
			upload_type: 'partner_title'
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
<script type="text/javascript">
	var oFCKeditor = new FCKeditor("post_content"); // привязка к textarea с id="body"
    oFCKeditor.ToolbarSet="Default"; // число кнопочек на инструментальной панели
    oFCKeditor.BasePath="<?=base_url()?>js/fckeditor/"; //путь к fckeditor
    oFCKeditor.Height = "245";
    oFCKeditor.ReplaceTextarea(); 
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