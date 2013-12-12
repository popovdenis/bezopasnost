<div class="infobox" style="clear:left; margin-top:15px;">
    <div class="t">
        <div class="b">
            <div class="l">
                <div class="r">
                    <div class="bl">
                        <div class="br">
                            <div class="tl">
                                <div class="tr">
                                    <div class="tags">
                                    <?php 
	                                    $partner_str = '';
	                                    foreach ($partners as $partner) {
	                                    	if(isset($partner->attach_preview_path)) {
												$img_src = '<img class="img_partner" alt="" border="0" src="'.base_url().$partner->attach_preview_path.'" />';
											} else
												$img_src = '&nbsp;';
	                                    	$partner_str .= '<a title="'.$partner->item_title.'" href="'.base_url().'partners/about/'.$partner->item_id.'">'.$img_src.'</a>';
	                                    }
	                                    echo $partner_str;
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
</div>