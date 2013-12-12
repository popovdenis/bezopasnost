<?php mb_internal_encoding("UTF-8"); require_once("_head.php"); ?>
	<!-- Header implementation -->
	<?php require_once("_header.php"); ?>
	<!-- Content implementation -->
<div class="content">	
    <!-- Содержание -->
    <div class="infocontent">
    	<?=$header_links?><br />
        <h1><?=$current_cat->category_title?></h1>
        <p><?=$current_cat->category_desc?></p>
        <?php
        	$subcats_str = '';
        	foreach ($subcats as $subcat){
        		$img = '';
        		if(!empty($subcat->attach)) $img = base_url().$subcat->attach->attach_preview_path;
        		
        		$subcats_str .= '<div class="preview">
		            <div class="previewImage">
		                <a href="'.base_url().'products/subcat/'.$subcat->category_id.'"><img width="150" src="'.$img.'" alt="'.$subcat->category_title.'"></a>
		            </div>
		            <div class="previewText">
		                <h3><a href="'.base_url().'products/subcat/'.$subcat->category_id.'"><b>'.$subcat->category_title.'</b></a></h3>
		                <p>'.$subcat->category_desc.'</p>
		            </div>
		        </div>';        		
        	}
        	echo $subcats_str;
        ?>
    </div>
    <!-- Продукция меню -->
    <div class="menubox">
        <div class="t">
            <div class="b">
                <div class="l">
                    <div class="r">
                        <div class="bl">
                            <div class="br">
                                <div class="tl">
                                    <div class="tr">
                                    <?php 
                                    $cat_str = '';
                                    $class = '';
                                    foreach ($main_categories as $cat) {
                                    	if($cat->category_id == $current_cat->category_id) $cat_str .= '<div class="menubox_item_selected">'.$cat->category_title.'</div>';
                                    	else $cat_str .= '<div class="menubox_item"><a class="link" href="'.base_url().'products/category/'.$cat->category_id.'">'.$cat->category_title.'</a></div>';
                                    }
                                    echo $cat_str;
                                    ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Меню сервисы -->
    <?=$items_block?>
    <?php require_once('_search_block.php'); ?>
    <!-- Тэги -->
    <?=$partners_block?>
    <div style="clear:both;">&nbsp;</div>
</div>
<?php require_once('_footer.php'); ?>