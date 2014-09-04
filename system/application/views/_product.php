<?php require_once("_head.php"); ?>
<script type="text/javascript" src="<?= base_url() ?>js/highslide/highslide.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/highslide/highslide-with-gallery.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>js/highslide/highslide.css"/>
<script type="text/javascript">
    hs.graphicsDir = '<?=base_url()?>js/highslide/graphics/';
    hs.wrapperClassName = 'wide-border';
</script>
<!-- Header implementation -->
<?php require_once("_header.php"); ?>
<!-- Content implementation -->
<div class="content">
    <!-- Содержание -->
    <div class="infocontent">
        <?= $header_links ?>
        <div style="float:left;margin-top:5px;text-align:right;width:100%;"><a href="<?= base_url(
            ) . 'products/subcat/' . $current_catid . '/about/' . $product->item_id . '/print' ?>" target="_blank">Версия
                для печати и PDA</a></div>
        <div class="product_title"><?= $product->item_title ?></div>
        <div>
            <?php $next_link = "#";
                if (!empty($next)) {
                    $next_link = base_url() . 'products/subcat/' . $current_catid . '/about/' . $next;
            ?>
                    <a href="<?= $next_link ?>">
                        <div class="next_product">>></div>
                    </a>
            <?php
                } ?>
            <?php $prev_link = "#";
                if (!empty($prev)) {
                    $prev_link = base_url() . 'products/subcat/' . $current_catid . '/about/' . $prev;
            ?>
                <a href="<?= $prev_link ?>">
                    <div class="next_product"><<</div>
                </a>
            <?php
                }
            ?>
        </div>

        <div class="product_description">
            <p><?= $product->item_preview ?></p>
        </div>

        <div class="product_info">
            <div class="product_photo">
                <?php
                    if (isset($product->attach) && !empty($product->attach)) {
                        ?>
                        <a href="<?= base_url() . $product->attach->attach_path ?>" class="highslide"
                           onclick="return hs.expand(this)">
                            <img src="<?= base_url() . $product->attach->attach_preview_path ?>"
                                 title="Click to enlarge"/>
                        </a>
                    <?php } ?>
            </div>
            <div class="product_details">
                <?= $product->item_characters ?>
                <div>
                    <?php if (!empty($product->item_price)) { ?>
                        <div style="float: left; font-size: 17px; margin-top: 10px;color:#CC6600;">
                            <div class="floatL" style="bottom:7px;position:relative;"><img
                                    src="<?= base_url() ?>images/icons/money.png"/></div>
                            <div class="floatL">
                                <span>Цена:</span>
                                <span><?= $product->item_price ?>грн.</span>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($product->item_title != 'АКЦИЯ') { ?>
                        <div style="float:right;margin-top:10px;text-align:right;">
                            <a onclick="productObj.open_compare('<?= $current_catid ?>', '<?= $product->item_id ?>');return false;"
                               href="#">Сравнить</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="floatL"><?= $product->item_content ?></div>
        <div class="floatR">
            <?php $next_link = "#";
            if (!empty($next)) {
                $next_link = base_url() . 'products/subcat/' . $current_catid . '/about/' . $next;
                ?>
                <a href="<?= $next_link ?>">
                    <div class="next_product">>></div>
                </a>
            <?php
            } ?>
            <?php $prev_link = "#";
            if (!empty($prev)) {
                $prev_link = base_url() . 'products/subcat/' . $current_catid . '/about/' . $prev;
                ?>
                <a href="<?= $prev_link ?>">
                    <div class="next_product"><<</div>
                </a>
            <?php
            }
            ?>
        </div>

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
            #sortable_gallery {
                list-style-type: none;
                margin: 0;
                padding: 0;
            }

            #sortable_gallery li {
                padding: 1px;
                float: left;
                font-size: 4em;
                text-align: center;
            }

            .sortable_img {
                cursor: pointer;
                height: 15px;
                margin-bottom: 25px;
                margin-left: 85px;
                margin-top: 3px;
                width: 15px;
            }
        </style>
        <div id="galleria" class="highslide-gallery">
            <ul id="sortable_gallery" class="gallery_block">
                <?php
                    if (isset($gallery)) {
                        $gal_str = "";
                        foreach ($gallery as $attach) {
                            if ($attach->attach_is_image) {
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
                            } else {
                                $gal_str .= '<li id="' . $attach->attach_id . '">
                                    <div class="product_preview">
                                            <div class="pt"><div class="pb"><div class="pl"><div class="pr"><div class="pbl"><div class="pbr"><div class="ptl"><div style="height:160px;" class="ptr_partner">
                                            <div style="height:105px;background-repeat:no-repeat;background-position:center;&quot;">
                                            <img src="' . base_url() . $attach->attach_preview_path . '" style="width: 86px;"></div>
                                            <div style="position:relative;bottom:0px;">
                                                <h3 style="padding-bottom:3px;font-size: 12px;">' . $attach->attach_title . '</h3>
                                                <a style="font-size:14px;" href="' . base_url(
                                    ) . 'products/download/' . $product->item_id . '/category/' . $current_catid . '/attach_id/' . $attach->attach_id . '">Скачать файл</a>
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
    <?= $categories_tree ?>
    <?= $information ?>
    <?php require_once('_search_block.php'); ?>
    <?php require_once('_partners_block.php'); ?>
    <div style="clear:both;">&nbsp;</div>
</div>
<?php require_once('_footer.php'); ?>
</body>
</html>
