<?php
	if($gallery) {
		$attach_str = '';
		$img_delete = '';		
		foreach ($gallery as $attach){
			if($is_admin) {
				$img_delete = '<img title="Удалить картинку из текущей галереи" style="cursor:pointer;width:15px;height:15px;" src="'.base_url().'images/icons/cancel.png" onclick="if(confirm(\'Картинка будет удалена из текущей галереи. Вы уверены, что хотите удалить этот файл?\')) delete_img(\''.$attach->attach_id.'\', \''.$attach->item_id.'\', \'false\');return false;" />';
				$img_delete .= '<img title="Удалить картинку из всех галерей" style="cursor:pointer;width:21px;height:21px;" src="'.base_url().'images/icons/trash.png" onclick="if(confirm(\'Картинка будет удалена из всех галерей. Вы уверены, что хотите удалить этот файл?\')) delete_img(\''.$attach->attach_id.'\', \'null\', \'true\');return false;" />';
			}
			if(isset($attach->item_attach_type) && $attach->item_attach_type == 'gallery_price') {
				$attach_str .= '<div id="gallery_img_id_'.$attach->attach_id.'" class="gallery_image_block">
				<span>'.base_url().$attach->attach_path.'</span>'.$img_delete.'</div>';
				
			} else {
				$attach_str .= '<div id="gallery_img_id_'.$attach->attach_id.'" class="gallery_image_block">
							<a href="'.base_url().$attach->attach_path.'" class="highslide" onclick="return hs.expand(this)">
								<img src="'.base_url().$attach->attach_preview_path.'" title="Click to enlarge" /></a>'.$img_delete.'</div>';
			}
		}
		echo $attach_str;
	}
?>