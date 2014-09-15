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
            <h1><?php echo $current_cat->category_title ?></h1>
            <div id="items_block">
            <?php
                foreach ($items as $item) {
                    $url = 'information/about/' . $current_cat->category_id . '/'  . $item->item_id;
            ?>
                <div class="article_preview">
                    <a href="<?php echo base_url() . $url ?>"><?php echo $item->item_title ?></a>
                    <p><?php echo $item->item_preview ?></p>
                </div>
            <?php
                }
            ?>
            </div>
        </div>
        <div class="page_container"><?php echo paginate_ajax($paginate_args); ?></div>
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