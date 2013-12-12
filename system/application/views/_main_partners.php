<!-- Партнеры -->
<div class="partners_box">
	<div class="t">
	    <div class="b">
	        <div class="l">
	            <div class="r">
	                <div class="bl">
	                    <div class="br">
	                        <div class="tl">
	                            <div class="tr">
	                                <div class="tags">
	                                <?php if(isset($partners) && !empty($partners)) { foreach ($partners as $partner) { ?>
	                                	<a href="<?=base_url().'partners/about/'.$partner->item_id?>"><img src="<?=base_url().$partner->attach_preview_path?>"/></a>
	                                <?php } } ?>
									</div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</div>