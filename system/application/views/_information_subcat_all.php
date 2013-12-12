<?php
	/*echo "<pre>";
		print_r($subcats);
	echo "</pre>";exit;*/
?>
<?php mb_internal_encoding("UTF-8"); require_once("_head.php"); ?>
	<!-- Header implementation -->
	<?php require_once("_header.php"); ?>
	<!-- Content implementation -->
<div class="content">	
    <!-- Содержание -->
    <div class="infocontent">
        <h1><?=$main[0]->category_title?></h1>
        <p><?=$main[0]->category_desc?></p>
        <?php
        	$subcats_str = '';
        	foreach ($cats as $subcat){
        		$img = '';
        		if(!empty($subcat->attach)) $img = base_url().$subcat->attach->attach_preview_path;
        		
        		$subcats_str .= '<div class="preview">
		            <div class="previewImage">
		                <a href="'.base_url().$main_slug.'/category/'.$subcat->category_id.'"><img src="'.$img.'" alt="'.$subcat->category_title.'"></a>
		            </div>
		            <div class="previewText">
		                <h3><a href="'.base_url().$main_slug.'/category/'.$subcat->category_id.'"><b>'.$subcat->category_title.'</b></a></h3>
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
                                    foreach ($cats as $cat) {
                                    	$cat_str .= '<div class="menubox_item"><a class="link" href="'.base_url().$main_slug.'/category/'.$cat->category_id.'">'.$cat->category_title.'</a></div>';
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
    <?php require_once('_search_block.php'); ?>
    <!-- Тэги -->
    <!-- Тэги -->
	<div class="infobox" style="clear:left; margin-top:15px;">
		<div class="t">
			<div class="b">
				<div class="l">
					<div class="r">
						<div class="bl">
							<div class="br">
								<div class="tl">
									<div class="tr">
										<div class="tags"><ul><?=$tagclouds?></ul></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div style="clear:both;">&nbsp;</div>
</div>
<?php require_once('_footer.php'); ?>