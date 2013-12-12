<script type="text/javascript">
    function change_gallery(){
        var gallery_tipe = $("#gallery_file_tyles option:selected").val();

        var gallery_tipe_show = '';
        var gallery_tipe_hide = '';
        if(gallery_tipe == 'image') gallery_tipe_hide = 'price';
        else if(gallery_tipe == 'price') gallery_tipe_hide = 'image';        
                 
        $('.'+gallery_tipe_hide+'_gal').each(function () {
            $(this).hide('slow');
        });
        $('.'+gallery_tipe+'_gal').each(function () {
            $(this).show('slow');
        });
        
    }
</script>
<?php
	if($gallery) {
		$attach_str = '';
		$img_delete = '';
			
		foreach ($gallery as $attach){
			if($is_admin) {
				$img_delete = '<img title="Редактировать" style="cursor:pointer;width:15px;height:15px;margin-right:3px;" src="'.base_url().'images/icons/edit.png" onclick="javascript:edit_img(\''.$attach->attach_id.'\');return false;" />';
				$img_delete .= '<img title="Удалить картинку из текущей галереи" style="cursor:pointer;width:15px;height:15px;margin-right:3px;" src="'.base_url().'images/icons/cancel.png" onclick="javascript:if(confirm(\'Картинка будет удалена из текущей галереи. Вы уверены, что хотите удалить этот файл?\')) delete_img(\''.$attach->attach_id.'\', \''.$attach->item_id.'\', \'false\');return false;" />';
				$img_delete .= '<img title="Удалить картинку из всех галерей" style="cursor:pointer;width:21px;height:21px;" src="'.base_url().'images/icons/trash.png" onclick="javascript:if(confirm(\'Картинка будет удалена из всех галерей. Вы уверены, что хотите удалить этот файл?\')) delete_img(\''.$attach->attach_id.'\', \'null\', \'true\');return false;" />';
			}
			if($attach->item_attach_type == 'gallery_price') {
				$attach_str .= '<div id="gallery_img_id_'.$attach->attach_id.'" class="gallery_image_block price_gal" style="display:none;">
				<div class="heading">'.$attach->attach_title.'</div>
					<span><img src="'.base_url().'images/icons/excel_48.png" /></span>
				<br /><div>'.$attach->attach_desc.'</div><br />'.$img_delete.'
				</div>';
				
			} else {
				$attach_str .= '
				<div id="gallery_img_id_'.$attach->attach_id.'" class="gallery_image_block image_gal">
					<div class="heading">'.$attach->attach_title.'</div>
					<a href="'.base_url().$attach->attach_path.'" class="highslide" onclick="return hs.expand(this)">
						<img src="'.base_url().$attach->attach_preview_path.'" title="Click to enlarge" />
					</a>'.$img_delete.'<div class="highslide-caption">'.$attach->attach_desc.'</div>
				</div>';
			}
		}
		echo $attach_str;
	}
?>
