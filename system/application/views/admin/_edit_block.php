<script type="text/javascript">
$(function(){
	new AjaxUpload('#imggallery_<?=$item->item_id?>', {
		// Location of the server-side upload script
		action: '<?=base_url()?>admin/home/upload',
		// File upload name
		name: 'userfile',
		// Additional data to send
		data: {
			item_id : '<?=$item->item_id?>',
			upload_type: 'item_gallery'
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
		    	$("#loader").show();
		    }
		} ,
	  onComplete: function(file, response) {
	  	var result = '';
	  	if(response) {
		  	result = window["eval"]("(" + response + ")");
		  	
		  	var img_del_gal = '<img title="Удалить картинку из текущей галереи" style="cursor:pointer;width:15px;height:15px;" src="<?=base_url()?>images/icons/cancel.png" onclick="javascript:if(confirm(\'Картинка будет удалена из текущей галереи. Вы уверены, что хотите удалить этот файл?\')) delete_img(\''+result.attach_id+'\', \''+result.item_id+'\', \'false\');return false;" /><img title="Удалить картинку из всех галерей" style="cursor:pointer;width:21px;height:21px;" src="<?=base_url()?>images/icons/trash.png" onclick="javascript:if(confirm(\'Картинка будет удалена из всех галерей. Вы уверены, что хотите удалить этот файл?\')) delete_img(\''+result.attach_id+'\', \'null\', \'true\');return false;" />';
		  	
		  	var file = '<div id="gallery_img_id_'+result.attach_id+'" class="gallery_image_block"><a href="<?=base_url()?>'+result.file_full_path+'" class="highslide" onclick="return hs.expand(this)"><img src="<?=base_url()?>'+result.file_path+'" title="Click to enlarge" /></a>'+img_del_gal+'</div>';
		  	$("#loader").hide();
		  	$("#new_gallery_block").hide();		  	
		  	$('#imggallery_img').append(file);
	  	}
	  	$.post(
			"<?=base_url()?>admin/home/upload",
			{ new_img_gal_title: $('#new_img_gal_title').val(), attach_id: result.attach_id, item_id : '<?=$item->item_id?>', upload_type: 'item_gallery' },
			function(data){
				$('#new_img_gal_title').val('');
			}
		)
	  }
	});
});
</script>
<script type="text/javascript">
	var oFCKeditor = new FCKeditor("post_content"); // привязка к textarea с id="body"
    oFCKeditor.ToolbarSet="Default"; // число кнопочек на инструментальной панели
    oFCKeditor.BasePath="<?=base_url()?>js/fckeditor/"; //путь к fckeditor
    oFCKeditor.Height = "225";
    oFCKeditor.ReplaceTextarea(); 
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