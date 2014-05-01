<script type="text/javascript" src="<?= base_url() ?>js/highslide/highslide-with-html.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>js/highslide/highslide.css"/>
<script type="text/javascript">
    hs.graphicsDir = '<?=base_url()?>js/highslide/graphics/';
    hs.outlineType = 'rounded-white';
    hs.wrapperClassName = 'draggable-header';

</script>
<div id="products_block" style="border:1px solid #CCCCCC;border-top:none;">
    <input type="hidden" id="item_type" value="<?= $item_type ?>"/>

    <div id="new_item">
        <div class="left">
            <div class="left add_item"><span onclick="adminObj.get_new_page('<?= $item_type ?>');">Новая статья</span>
            </div>
            <div style="float:left;">
                <img id="add_item_img" style="position:relative;top:10px;display:none;"
                                          src="<?= base_url() ?>images/ajax-loader.gif"/>
            </div>
        </div>
        <div class="right">
            <div class="delete_all_btn" onclick="if(confirm('Выбранные статьи удалятся вместе ' +
             'с прикрепленным к ним материалом. ' +
             'Вы уверены, что хотите удалить выбранные статьи?')) adminObj.delete_items_checked();">
                <span>Удалить отмеченные</span>
            </div>
        </div>
    </div>
    <?php if (isset($categories) && !empty($categories)) {
        $current = array_pop($categories); ?>
        <div class="left" style="margin:0 0 10px 10px;">
            <span id="filter_items">Фильтр:</span>
            <select id="item_categories" onchange="adminObj.filter_items_category('<?= $item_type ?>');">
                <option id="<?= $current->category_id ?>" selected value="0"><?= $current->category_title ?></option>
                <?php foreach ($categories as $category) { ?>
                    <option value="<?= $category->category_id ?>"><?= $category->category_title ?></option>
                <?php } ?>
            </select>
            <span id="filter_items_loading"></span>
        </div>
    <?php } ?>
    <?php if ($item_type == 'products') { ?>
        <div id="rate_productpage_header">
            <span>Текущий курс: </span>
            <span><strong><span id="cr_uah">1</span> UAH</strong></span><span>=</span>
            <span>USD -</span><span id="cr_usd"><?= round($currency_rate->usd, 2) ?></span>;
            <span>EUR -</span><span id="cr_eur"><?= round($currency_rate->eur, 2) ?></span>
        </div>
    <?php } ?>
    <div id="item_title">
        <span class="spn_chb">&nbsp;</span>
        <span class="spn_num"><strong>№</strong></span>
        <span class="spn_mode"><strong>Статус</strong></span>
        <span class="spn_cat"><strong>Категория</strong></span>
        <span class="spn_title"><strong>Название</strong></span>
        <span class="spn_desc"><strong>Описание</strong></span>
        <?php if ($item_type == "products") { ?>
            <div class="price_head">
                <div><span class="spn_price"><strong>Цена</strong></span></div>
                <div>
                    <div class="price_name_head_cost"><span><strong>Стоимость</strong></span></div>
                    <div class="price_name_head_value"><span><strong>Валюта</strong></span></div>
                </div>
            </div>
        <?php } ?>
        <span class="spn_delete"><strong>Удалить</strong></span>
    </div>
    <div id="items_<?= $item_type ?>"><?= $items_block ?></div>
</div>
<?php
    $this->benchmark->mark('code_end');
    echo "<p> Time generation: " . $this->benchmark->elapsed_time('code_start', 'code_end') . '"</p> ';
    echo "<p> Memory usage: " . $this->benchmark->memory_usage() . '"</p> ';
?>