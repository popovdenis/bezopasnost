<?php if (! empty($items_info)) { ?>
    <div class="menubox" style="clear:left; margin-top:15px;">
        <div class="t">
            <div class="b">
                <div class="l">
                    <div class="r">
                        <div class="bl">
                            <div class="br">
                                <div class="tl">
                                    <div class="tr">
                                        <?php
                                        $item_str = '';
                                        foreach ($items_info as $item) {
                                            $item_str .= '<div class="menubox_item"><a class="link" href="' . base_url() . 'information/subcat/144/about/' . $item->item_id . '">' . $item->item_title . '</a></div>';
                                        }
                                        echo $item_str;
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
<?php } ?>
