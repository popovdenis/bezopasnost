<!-- Новинки -->
<div class="new_products">
    <div class="new_products_title">
        <a href="<?=base_url()?>products"><h3 class="new_products">Новые поступления</h3></a>                
    </div>
    <div class="new_products_list">
    	<?php if(isset($products) && !empty($products)) {
    		foreach ($products as $index=>$product) {
    			$item_desc = "";
    			if(!empty($product->item_content)) {
    				$product->item_content = html_entity_decode($product->item_content, ENT_QUOTES, 'UTF-8');
					if(mb_strlen($product->item_preview) > 85) $item_desc = mb_substr($product->item_preview,0,82)."...";
					else $item_desc = $product->item_preview;
					$item_desc = preg_replace("/<p><img(.*?)\/><\/p>/si", "", $item_desc);
					$item_desc = str_replace(array('<p>', '</p>'), '', $item_desc);
				}
				$style_border = "";
				if($index == 1) $style_border = "border-bottom:none;"
    	?>
        <div class="new_product" style="<?=$style_border?>">
            <div class="new_product_desc">
            	<?php $link_src = ""; if(isset($product->category)) $link_src = base_url().'products/subcat/'.$product->category->category_id.'/about/'.$product->item_id; ?>
                <a href="<?=$link_src?>"><h4><?=$product->item_title?></h4></a>
                <p><?=$item_desc?></p>
            </div>
            <div class="new_procuts_img">
            <?php if(isset($product->attach) && !empty($product->attach)) { ?>
                <a href="<?=base_url().'products/subcat/'.$product->category->category_id.'/about/'.$product->item_id?>"><img src="<?=base_url().$product->attach->attach_preview_main_path?>"/></a>
            <?php } ?>
            </div>
        </div>
        <?php } } unset($style_border); ?>
    </div>
</div>