<script type="text/javascript">
    productsObj.initImageGalleryUploader();
    productsObj.initCategoryUploader();
</script>
<!--<script type="text/javascript" src="--><?//= base_url() ?><!--js/ui/ui.sortable.js"></script>-->
<style type="text/css">
    #sortable {
        list-style-type: none;
        margin: 10px 0 0 0;
        padding: 0;
        width: 400px;
    }

    #sortable li {
        margin: 0 5px 5px 5px;
        padding: 5px;
        font-size: 1.2em;
        height: 1.5em;
        width: 100%;
    }

    html > body #sortable li {
        height: 1.5em;
        line-height: 1.2em;
    }

    .ui-state-highlight {
        height: 1.5em;
        line-height: 1.2em;
    }
</style>
<script type="text/javascript">
//    $(function () {
//        $("#sortable").sortable({
//            placeholder: 'ui-state-highlight'
//        });
//        $("#sortable").disableSelection();
//    });
</script>
<div style="float:left;width:917px;margin-bottom:10px;">
    <div class="left padAll5 clientFoundRow">
        <div class="left">
            Найденная Категория:
            <span><?= $category->category_title ?></span>
        </div>
        <div class="right">
            <input type="button"
                   onclick="if(confirm('Вы уверены, что хотите удалить эту категоррию?')) adminObj.delete_category('<?= $category->category_id ?>');"
                   value="Удалить"/>
        </div>
    </div>
    <div style="width:100%;float:left;margin-top:20px;">
        <div style="float:left;">
            Имя категории:
            <input type="text" id="found_category_title" style="width:320px;" value="<?= $category->category_title ?>"/>
        </div>
        <div style="float:right;">
            Родительская категория:
            <select id="found_categories_parent">
                <option value="0">Выберите имя категории</option>
                <?= $categories ?>
            </select>
        </div>
    </div>
    <div style="clear:both;"/>
    <div style="float: left; width: 100%;">
        <div style="float:left;">
            <div>
                Описание категории:<br/>
                <textarea id="found_cat_desc" style="width:450px;height:200px;"><?= $category->category_desc ?></textarea>
            </div>
            <div>
                <span class="seo_params">Seo параметры</span>
                <div class="seo_title">
                    <span><strong>Title (название)</strong>&nbsp;<i>(50-80 знаков)</i></span>
                    <input type="text" style="width:450px;" value="<?= $category->item_seo_title ?>" name="item_seo_title" id="item_seo_title">
                </div>
                <div class="seo_keywords">
                    <span><strong>Keywords (ключевые слова)</strong>&nbsp;<i>(до 250 знаков)</i></span>
                    <input type="text" style="width:450px;" value="<?= $category->item_seo_keywords ?>" name="item_seo_keywords" id="item_seo_keywords">
                </div>
                <div class="seo_description">
                    <span><strong>Description (описание)</strong>&nbsp;<i>(150-200 знаков)</i></span>
                    <textarea style="width:450px;" rows="7" name="item_seo_description" id="item_seo_description" type="text" class="editor"><?= $category->item_seo_description ?></textarea>
                </div>
            </div>
        </div>
        <div style="float:right;position:relative;top:17px;width:425px;">
            <div style="float:left;">
                <div id="category_img" style="margin-left:10%;">
                    <?php
                    if (isset($category->attach->attach_path)) {
                        ?>
                        <img class="img_partner" alt="" border="0"
                             src="<?= base_url() . $category->attach->attach_path ?>"/>
                    <?php } ?>
                </div>
                <div style="margin-bottom:10px;padding-left:10%;">
                    <a href="#" id="categoryid_<?= $category->category_id ?>">
                        <img class="verticalMiddle" alt="" border="0"
                             src="<?= base_url() ?>images/upload-green-arrow.gif"/>
                        <img class="marLeft5 verticalMiddle" alt="" border="0"
                             onclick="$('#categoryid_<?= $category->category_id ?>').fileUploadStart()"
                             src="<?= base_url() ?>images/image-icon.jpg"/>
                        <span>Логотип категории</span>
                    </a>
                </div>
            </div>
            <input style="float:right;" type="button"
                   onclick="adminObj.update_category('<?= $category->category_id ?>');" value="Обновить"/>

            <div style="float:left;margin-top:20px;font-size:12px;margin-left:10px;">
                <div style="float:left;">
                    Партнеры:
                    <select id="category_partner_list">
                        <option value="0">Выберите имя партнера</option>
                        <?php if ($partners && ! empty($partners)) {
                            foreach ($partners as $partner) { ?>
                                <option value="<?= $partner->item_id ?>"><?= $partner->item_title ?></option>
                            <?php }
                        } ?>
                    </select>
                    <a href="#" onclick="adminObj.add_category_partner('<?= $category->category_id ?>');return false;">Прикрепить</a>
                </div>
                <div id="category_partners_img"></div>
                <div id="category_partners">
                    <?php
                    if ($cat_partners && ! empty($cat_partners)) {
                        foreach ($cat_partners as $cpartner) {
                            ?>
                            <div id="partner_<?= $cpartner->item_id ?>">
                                <span><?= $cpartner->item_title ?></span>
                                <a href="#"
                                   onclick="adminObj.delete_category_partner('<?= $category->category_id ?>','<?= $cpartner->item_id ?>');return false;">Удалить</a>
                            </div>
                        <?php }
                    } ?>
                </div>
            </div>
            <div style="float:left;margin-top:20px;font-size:12px;margin-left:10px;">
                <span>Подкатегории:	</span>
                <a href="#" onclick="adminObj.reorder_categories('<?= $category->category_id ?>');return false;">Применить</a>
                <img id="category_subcats_img" border="0" src="<?php echo base_url(); ?>images/add-note-loader.gif"
                     alt="loading..." style="padding-top: 7px;text-align:center;display:none;"/>

                <div id="category_subcats">
                    <?php if ($sub_cats && ! empty($sub_cats)) {
                        $str = '<ul id="sortable">';
                        foreach ($sub_cats as $sub_cat) {
                            $str .= '<li id="' . $sub_cat->category_id . '" class="ui-state-default">' . $sub_cat->category_title . '</li>';
                        }
                        $str .= '</ul>';
                        echo $str;
                    }?>
                </div>
            </div>
        </div>
    </div>
</div>
