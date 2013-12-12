<?php require_once("_head.php"); ?>
<script type="text/javascript" src="<?=base_url()?>js/highslide/highslide.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/highslide/highslide-with-gallery.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>js/highslide/highslide.css" />
	<!-- Header implementation -->
	<?php require_once("_header.php"); ?>
	<!-- Content implementation -->
	<div class="content">
		<!-- Содержание -->
		<div class="infocontent">
            <div style="float:left;margin-bottom:10px;"><?=$head_links?></div>
            <div style="clear:both;">
                <h2><?=$item->item_title?></h2>
                <?=html_entity_decode($item->item_content, null, 'UTF-8')?>
            </div>
            <div class="gallery_block">
				<script type="text/javascript">
hs.graphicsDir = '<?=base_url()?>js/highslide/graphics/';
hs.align = 'center';
hs.transitions = ['expand', 'crossfade'];
hs.outlineType = 'rounded-white';
hs.fadeInOut = true;
//hs.dimmingOpacity = 0.75;

// Add the controlbar
hs.addSlideshow({
	//slideshowGroup: 'group1',
	interval: 5000,
	repeat: false,
	useControls: true,
	fixedControls: 'fit',
	overlayOptions: {
		opacity: .75,
		position: 'bottom center',
		hideOnMouseOut: true
	}
});
</script>
<style type="text/css">
#sortable_gallery { list-style-type: none; margin: 0; padding: 0; }
#sortable_gallery li { padding: 1px; float: left; font-size: 4em; text-align: center; }
.sortable_img {cursor:pointer;height:15px;margin-bottom:25px;margin-left:85px;margin-top:3px;width:15px;}
</style>
                <div id="galleria" class="highslide-gallery">
                    <ul id="sortable_gallery" class="gallery_block">
                    <?php
                        if ( isset( $gallery ) )
                        {
                            $gal_str = "";
                            foreach ( $gallery as $attach )
                            {
                                if( $attach->attach_is_image )
                                {
                                    $gal_str .= '<li class="ui-state-default" id="' . $attach->attach_id . '">
                                        <div class="product_preview">
                                                <div class="pt"><div class="pb"><div class="pl"><div class="pr"><div class="pbl"><div class="pbr"><div class="ptl"><div style="height:180px;" class="ptr_partner">
                                                <div style="height:105px;background-repeat:no-repeat;background-position:center;&quot;">
                                                    <a href="' . base_url() . $attach->attach_path . '" class="highslide" onclick="return hs.expand(this)">
                                                        <img src="' . base_url() . $attach->attach_preview_path . '">
                                                    </a>
                                                    <div style="position:relative;bottom:0px;">
                                                        <h3 style="padding-top: 5px;padding-bottom:3px;font-size: 14px;">' . $attach->attach_title . '</h3>
                                                        <h3 style="padding-bottom:3px;font-size: 12px;">' . $attach->attach_desc . '</h3>
                                                    </div>
                                                </div>
                                                <div class="highslide-caption">
                                                    <div><strong>Название: </strong>' . $attach->attach_title . '</div>
                                                    <div><strong>Описание: </strong>' . $attach->attach_desc . '</div>
                                                </div>
                                            </div></div></div></div></div></div></div></div>
                                        </div>
                                    </li>';
                                }
                                else
                                {
                                    $gal_str .= '<li id="' . $attach->attach_id . '">
                                        <div class="product_preview">
                                                <div class="pt"><div class="pb"><div class="pl"><div class="pr"><div class="pbl"><div class="pbr"><div class="ptl"><div style="height:160px;" class="ptr_partner">
                                                <div style="height:105px;background-repeat:no-repeat;background-position:center;&quot;">
                                                <img src="' . base_url() . $attach->attach_preview_path . '" style="width: 86px;"></div>
                                                <div style="position:relative;bottom:0px;">
                                                    <h3 style="padding-bottom:3px;font-size: 12px;">' . $attach->attach_title . '</h3>
                                                    <a style="font-size:14px;" href="'.base_url().'products/download/'.$item->item_id.'/category/'.$current_cat->category_id.'/attach_id/'. $attach->attach_id .'">Скачать файл</a>
                                                </div>
                                            </div></div></div></div></div></div></div></div>
                                        </div>
                                    </li>';
                                }
                            }
                            echo $gal_str;
                        }
                    ?>
                    </ul>
                </div>
			</div>
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
	                                    	if($cat->category_id == $current_cat->category_id) $cat_str .= '<div class="menubox_item_selected">'.$cat->category_title.'</div>';
	                                    	else $cat_str .= '<div class="menubox_item"><a class="link" href="'.base_url().$main_slug.'/category/'.$cat->category_id.'">'.$cat->category_title.'</a></div>';
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
		<?php require_once('_search_block.php');?>
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