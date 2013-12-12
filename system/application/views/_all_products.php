<?php
	mb_internal_encoding("UTF-8");
	$product_str = '';
	foreach ($products as $index=>$product){
		$item_preview = "&nbsp;";
		if(mb_strlen($product->item_preview) > 83) $item_preview = mb_substr($product->item_preview,0,80)."..";
		else $item_preview = $product->item_preview;
		
		$style = 'height:240px;';
		if(isset($product->attach) && !empty($product->attach)) $style .= 'background:url(\''.base_url().$product->attach->attach_preview_path.'\');';
		$style .= 'background-repeat:no-repeat;background-position:center;';
		
		$product_str .= '
			<div class="product_preview">
            	<div class="pt"><div class="pb">
            		<div class="pl"><div class="pr"><div class="pbl"><div class="pbr"><div class="ptl"><div class="ptr">
                <a href="'.base_url().'products/subcat/'.$category_id.'/about/'.$product->item_id.'"><div style='.$style.'"></div></a>
                <div style="position:relative;bottom:0px;">
                	<a href="'.base_url().'products/subcat/'.$category_id.'/about/'.$product->item_id.'" style="text-decoration:none;"><h3>'.$product->item_title.'</h3></a>
                	<a href="'.base_url().'products/subcat/'.$category_id.'/about/'.$product->item_id.'" style="text-decoration:none;"><p>'.$item_preview.'</p></a>
                	<a href="'.base_url().'products/subcat/'.$category_id.'/about/'.$product->item_id.'">Подробности</a>
                </div>
            </div></div></div></div></div></div></div></div>
        </div>
        <input type="hidden" id="category_id" value="'.$category_id.'">';
	}
	echo $product_str;
?>