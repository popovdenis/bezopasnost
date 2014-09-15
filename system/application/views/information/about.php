<?php
    $pathToViews = realpath(APPPATH . 'views');
?>
<?php require_once($pathToViews . '\_head.php'); ?>
<?php modules::load_file('ajax_information.php',APPPATH.'/js_ajax/'); ?>
    <!-- Header implementation -->
<?php require_once($pathToViews . '\_header.php'); ?>
    <!-- Content implementation -->
    <div class="content">
        <!-- Содержание -->
        <div class="infocontent">
            <h1><?php echo $item->item_title ?></h1>
            <div>
                <?php $next_link = "#";
                    if (!empty($next)) {
                        $next_link = base_url() . 'information/about/' . $current_cat->category_id . '/' . $next;
                        ?>
                        <a href="<?= $next_link ?>">
                            <div class="next_product">>></div>
                        </a>
                    <?php
                    } ?>
                <?php $prev_link = "#";
                    if (!empty($prev)) {
                        $prev_link = base_url() . 'information/about/' . $current_cat->category_id . '/' . $prev;
                        ?>
                        <a href="<?= $prev_link ?>">
                            <div class="next_product"><<</div>
                        </a>
                    <?php
                    }
                ?>
            </div>
            <div id="items_block">
                <div class="article_preview">
                    <p><?php echo $item->item_content ?></p>
                </div>
            </div>
            <div>
                <?php $next_link = "#";
                    if (!empty($next)) {
                        $next_link = base_url() . 'information/about/' . $current_cat->category_id . '/' . $next;
                        ?>
                        <a href="<?= $next_link ?>">
                            <div class="next_product">>></div>
                        </a>
                    <?php
                    } ?>
                <?php $prev_link = "#";
                    if (!empty($prev)) {
                        $prev_link = base_url() . 'information/about/' . $current_cat->category_id . '/' . $prev;
                        ?>
                        <a href="<?= $prev_link ?>">
                            <div class="next_product"><<</div>
                        </a>
                    <?php
                    }
                ?>
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
        <?php require_once($pathToViews . '\_search_block.php'); ?>
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
<?php require_once($pathToViews . '\_footer.php'); ?>