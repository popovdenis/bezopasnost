<?php
    if(!empty($gallery)) {
        if(isset($gallery->attachments) && !empty($gallery->attachments)) {

        	$gallery_str = '<div id="gallery_reorder_apply"><a href="#" onclick="adminObj.reorder_attach_gallery('.$gallery->gallery_id.');return false;">Применить</a>
			</div><ul id="sortable_gallery">';

        	foreach($gallery->attachments as $attachment) {
                $gallery_str .= '<li class="ui-state-default" id="'.$attachment->attach_id.'">
                    <a href="'.base_url().$attachment->attach_path.'" class="highslide" onclick="return hs.expand(this)">
                        <img style="width:100px;height:90px;" src="'.base_url().$attachment->attach_path.'">
                    </a>
                    <div class="highslide-caption">
                    	<div><strong>Название: </strong>'.$attachment->attach_title.'</div>
                    	<div><strong>Описание: </strong>'.$attachment->attach_desc.'</div>
                    </div>
                    <img class="sortable_img" src="'.base_url().'images/icons/cancel.png" onclick="adminObj.delete_attach_gallery('.$gallery->gallery_id.','.$attachment->attach_id.');">
                </li>';
            }
            $gallery_str .= '</ul>';

        } else {
            $gallery_str = '<div><ul id="sortable_gallery"></ul></div>';
        }
?>

<script type="text/javascript">
$(function(){
    new AjaxUpload('#imggallery_<?=$gallery->gallery_id?>', {
        action: '<?=base_url()?>admin/home/upload',
        name: 'userfile',
        data: {
        upload_type: 'gallery'
    },
    responseType: false,
    onChange: function(file, extension){},
    onSubmit : function(file , ext){
        if (! (ext && /^(<?=$allowed_types?>)$/.test(ext))){
            alert('Error: invalid file extension');
            return false;
        } else {
            $("#loader_gallery").show();
        }
    },
    onComplete: function(file, response) {
          var result = '';
          if(response) {
              result = window["eval"]("(" + response + ")");
              $.post(
                "<?=base_url()?>admin/home/upload",
                {
                	new_gal_title: $('#gal_img_title').val(),
                	new_gal_desc: $('#gal_img_desc').val(),
                	attach_id: result.attach_id,
                	gallery_id : '<?=$gallery->gallery_id?>',
                	upload_type: 'gallery'
                },
                function(data){
                    var result = window["eval"]("(" + data + ")");

                    $('#gal_img_title').val('');
                    $('#gal_img_desc').val('');
                    $("#new_gallery_file_block").hide();
                    $('#gallery_reorder_apply').show();

                    var file = '<li class="ui-state-default" id="'+result.attach_id+
                    '"><a href="<?=base_url()?>'+result.file_full_path+'" class="highslide" onclick="return hs.expand(this)">'+
                    '<img style="width:100px;height:90px;" src="<?=base_url()?>'+result.file_full_path+
                    '"></a><div class="highslide-caption"><div><strong>Название: </strong>'+ result.attach_title +'</div><div><strong>Описание: </strong>'+ result.attach_desc +'</div></div><img class="sortable_img" src="<?=base_url()?>images/icons/cancel.png" onclick="delete_attach_gallery(\'<?=$gallery->gallery_id?>\', \''+ result.attach_id +'\');"></li>';
                    $('#sortable_gallery').append(file);
                    $("#loader_gallery").hide();
                }
            )
        } else {
            alert('Ошибка! Файл не был загружен или загружен с ошибкой!');
        }
    }
    });
});
</script>
<script type="text/javascript" src="<?=base_url()?>js/ui/ui.sortable.js"></script>
<style type="text/css">
#sortable_gallery { list-style-type: none; margin: 0; padding: 0; }
#sortable_gallery li { margin: 3px 3px 3px 0; padding: 1px; float: left; width: 104px; height: 120px; font-size: 4em; text-align: center; }
.sortable_img {cursor:pointer;height:15px;margin-bottom:25px;margin-left:85px;margin-top:3px;width:15px;}
</style>
<script type="text/javascript">
    $(function() {
        $("#sortable_gallery").sortable();
        $("#sortable_gallery").disableSelection();
    });
</script>

<div id="galleria" class="highslide-gallery"><?=$gallery_str?></div>
<div id="set_gallery_block" style="float: left; width: 100%;margin-bottom:15px;">
    <div class="innerTableHeaderGreen">
        <div id="" class="left padAll5">
            <img class="marRight5" src="<?=base_url()?>images/big-plus.gif" alt=""/>
            <a id="" onclick="return adminObj.add_form('gallery_file');" href="#">Добавить файл в галлерею</a>
        </div>
    </div>
    <div id="gallery_block_header" style="float: left; width: 100%;">
        <div id="new_gallery_file_block" style="float:left;width:700px;margin-bottom:10px;display:none;">
            <div style="width:100%;float:left;">
                <div style="float:left; margin-bottom: 0px;margin-top:0;">
                    Название:<br />
                    <input type="text" id="gal_img_title" value="" /><br />
                    Описание:<br />
                    <textarea id="gal_img_desc" style="width:500px;"></textarea>
                </div>
                <div style="float:left;margin:0 0 0 5px;">
                    <a href="#" id="imggallery_<?=$gallery->gallery_id?>">
                        <img class="verticalMiddle" alt="" border="0" src="<?=base_url()?>images/upload-green-arrow.gif"/>
                        <img class="marLeft5 verticalMiddle" alt="" border="0" onclick="$('#imggallery_<?=$gallery->gallery_id?>').fileUploadStart()" src="<?=base_url()?>images/image-icon.jpg"/>
                        <span>Загрузить</span>
                    </a><br/>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    } else {
         $gallery_str = "<div>Галлерей пока нет</div>";
    }
?>
