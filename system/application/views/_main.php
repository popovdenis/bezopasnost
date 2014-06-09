<?php mb_internal_encoding("UTF-8");
    require_once("_head.php"); ?>
<!-- Header implementation -->
<?php require_once("_header.php"); ?>
<!-- Content implementation -->
<script type="text/javascript">
    function strstr(haystack, needle, bool) {
        var pos = 0;
        haystack += '';
        pos = haystack.indexOf(needle);
        if (pos == - 1) {
            return false;
        } else {
            if (bool) {
                return haystack.substr(0, pos);
            } else {
                return haystack.slice(pos);
            }
        }
    }
    $(function ($) {
        var img_path = '';
        var img_ext = '';
        $("div.categories a img").mouseover(function () {
            var ext = $(this).attr('src').indexOf(".png");
            img_path = $(this).attr('src').substr(0, ext);
            img_ext = $(this).attr('src').substr(ext);
            $(this).attr("src", img_path + '_hover' + img_ext);
        }).mouseout(function () {
            $(this).attr("src", img_path + img_ext);
            img_path = '';
            img_ext = '';
        });
    });
</script>
<div class="content">
    <div>
        <div class="text">
            <div class="categories">
                <a href="<?php if (! empty($links_cat['safes'])) {
                    echo base_url() . 'products/category/' . $links_cat['safes'][0]->category_id;
                } ?>"><img src="<?= base_url() ?>images/categories/safes.png"/></a>
                <a href="<?php if (! empty($links_cat['locks'])) {
                    echo base_url() . 'products/category/' . $links_cat['locks'][0]->category_id;
                } ?>"><img src="<?= base_url() ?>images/categories/locks.png"/></a>
                <a href="<?php if (! empty($links_cat['cylinders'])) {
                    echo base_url() . 'products/category/' . $links_cat['cylinders'][0]->category_id;
                } ?>"><img src="<?= base_url() ?>images/categories/cylinders.png"/></a>
                <a href="<?php if (! empty($links_cat['doors'])) {
                    echo base_url() . 'products/category/' . $links_cat['doors'][0]->category_id;
                } ?>"><img src="<?= base_url() ?>images/categories/doors.png"/></a>
                <a href="<?php if (! empty($links_cat['skd'])) {
                    echo base_url() . 'products/category/' . $links_cat['skd'][0]->category_id;
                } ?>"><img src="<?= base_url() ?>images/categories/skd.png"/></a>
                <a href="<?php if (! empty($links_cat['other'])) {
                    echo base_url() . 'products/category/' . $links_cat['other'][0]->category_id;
                } ?>"><img src="<?= base_url() ?>images/categories/misc.png"/></a>
            </div>
        </div>
        <div style="float: right; width: 280px;">
            <?php require_once('_contacts_block.php'); ?>
            <?php require_once('_new_products.php'); ?>
            <?php //require_once('_new_info.php'); ?>
        </div>
    </div>
    <div class="main-content">
        <?=$main->item_content?>
    </div>
    <?php require_once('_main_partners.php'); ?>
    <div style="clear:both;">&nbsp;</div>
</div>
<?php require_once('_footer.php'); ?>
