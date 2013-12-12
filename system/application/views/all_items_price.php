<div id="item_category"><h1><?=$category->category_title?></h1></div>
<?php
	mb_internal_encoding("UTF-8");
	$str = '';
	foreach ($items as $item) {
		$item_preview = "&nbsp;";
		if(mb_strlen($item->item_preview) > 83) $item_preview = mb_substr($item->item_preview,0,80)."..";
		else $item_preview = $item->item_preview;

		$style = 'height:105px;';
		if(isset($item->attach->attach_preview_path) && !empty($item->attach->attach_preview_path)) $style .= 'background:url(\''.base_url().$item->attach->attach_preview_path.'\');';
		$style .= 'background-repeat:no-repeat;background-position:center;';

		$str .= '<div class="product_preview">
		            	<div class="pt"><div class="pb">
		            		<div class="pl"><div class="pr"><div class="pbl"><div class="pbr"><div class="ptl"><div class="ptr_partner" style="height:160px;">
		                <div style='.$style.'"><img style="width: 95px;" src="' . base_url() . 'images/icons/rar.png" /></div>
		                <div style="position:relative;bottom:0px;">
		                	<h3 style="padding-bottom:3px;font-size: 12px;">'.$item->item_title.'</h3>
		                	<a href="'.base_url().'information/download/'.$item->item_id.'/category/'.$category->category_id.'" style="font-size:14px;">Скачать файл</a>
		                </div>
		            </div></div></div></div></div></div></div></div>
		        </div>';
	}
	echo $str;
?>