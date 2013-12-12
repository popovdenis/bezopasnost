<?php require_once("_head.php"); ?>
<?php modules::load_file('ajax_information.php',APPPATH.'/js_ajax/'); ?>
	<!-- Header implementation -->
	<?php require_once("_header.php"); ?>
	<!-- Content implementation -->
	<div class="content">
		<!-- Содержание -->
		<div class="infocontent">
			<div style="float:left;margin-bottom:10px;width:100%;"><?=$head_links?></div>
			<div id="items_block"><?=$items_block?></div>
			<div class="page_container"><?php echo paginate_ajax($paginate_args); ?></div>
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
		<?php require_once('_search_block.php'); ?>
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