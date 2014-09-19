<?php mb_internal_encoding("UTF-8");
    require_once("_head.php"); ?>
    <!-- Header implementation -->
<?php require_once("_header.php"); ?>
    <!-- Content implementation -->
    <div class="content">
        <!-- Содержание -->
        <div class="infocontent">
            <?= $header_links ?>
            <!-- Каталог продукции -->
            <div class="products_catalog">
                <div style="float:left;margin-bottom:10px;width:100%;">
                    <div style="float:right;">
                        <img id="filter_img" border="0" src="<?php echo base_url(); ?>images/ajax-loader.gif" alt="loading..."/>
                        <input id="quick_search_field" type="text" onkeyup="productsObj.quick_search();" value="Быстрый поиск"/>
                    </div>
                </div>

                <div style="float:left;" id="products_block"><?= $main_content ?></div>
            </div>
            <!-- Навигация по страницам -->
            <div class="page_container"><?php echo paginate_ajax($paginate_args); ?></div>
            <input type="hidden" id="category_id" value="<?= $current_catid ?>"/>
        </div>
        <?= $categories_tree ?>
        <?= $information ?>
        <?php require_once('_search_block.php'); ?>
        <?php require_once('_partners_block.php'); ?>
        <div style="clear:both;">&nbsp;</div>
    </div>
<?php require_once('_footer.php'); ?>
