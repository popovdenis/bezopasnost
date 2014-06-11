<?php require_once("header.php"); ?>
<script type="text/javascript">
    $(document).ready(function(){
        productsObj.init();
        productsObj.initCKeditors();
        productsObj.initDatePicker();
    });
</script>
    <script type="text/javascript" src="<?= base_url() ?>js/highslide/highslide-with-html.js"></script>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>js/highslide/highslide.css"/>
    <script type="text/javascript">
        hs.graphicsDir = '<?=base_url()?>js/highslide/graphics/';
        hs.outlineType = 'rounded-white';
        hs.wrapperClassName = 'draggable-header';

    </script>
<input type="hidden" name="item_type" value="<?=$item_type?>">
<div style="width:100%;float:left;margin-bottom:35px;border:1px solid #CCCCCC;border-top:none;">
    <div style="float:left;width:780px; margin-left:10px;margin-top:10px;">
        <div>
            <div class="product_element">
                <span style="float: left; width: 100%;"><strong>Название статьи</strong></span>
                <input type="text" id="item_title" name="item_title" value="<?= $item->item_title ?>"
                       style="width:500px;"/>
            </div>
            <div class="product_element">
                <span><strong>Краткое описание статьи</strong></span><br/>
                <textarea class="editor" name="item_preview" id="item_preview"
                          style="min-height:70px;width:775px;"><?= $item->item_preview ?></textarea>
            </div>
            <?php
            if ($item_type == 'products') { ?>
                <div class="product_element">
                    <span><strong>Характеристики</strong></span><br/>
                    <textarea class="editor" name="item_charecters" id="item_charecters"
                              style="min-height:70px;width:775px;"><?= $item->item_characters ?></textarea>
                </div>
            <?php } ?>
            <div>
                <span><strong>Описание статьи</strong></span>
                <textarea class="editor" name="post_content" id="post_content"
                          style="min-height:330px;"><?= $item->item_content ?></textarea>
            </div>
            <div class="product_element">
                <div class="seo_params_block">
                    <span class="seo_params">Seo параметры</span>
                    <div class="seo_title">
                        <span><strong>Title (название)</strong>&nbsp;<i>(50-80 знаков)</i></span>
                        <input type="text" id="item_seo_title" name="item_seo_title" value="<?= $item->item_seo_title ?>"
                               style="width:500px;"/>
                    </div>
                    <div class="seo_keywords">
                        <span><strong>Keywords (ключевые слова)</strong>&nbsp;<i>(до 250 знаков)</i></span>
                        <input type="text" id="item_seo_keywords" name="item_seo_keywords"
                               value="<?= $item->item_seo_keywords ?>" style="width:500px;"/>
                    </div>
                    <div class="seo_description">
                        <span><strong>Description (описание)</strong>&nbsp;<i>(150-200 знаков)</i></span>
                        <textarea class="editor" type="text" id="item_seo_description" name="item_seo_description" cols="97"
                                  rows="7"><?= $item->item_seo_description ?></textarea>
                    </div>
                </div>
                <div>
                    <span><strong>Метки</strong>&nbsp;<i>(для быстрого поиска статей)</i></span><br/>
                    <input type="text" id="item_marks" name="item_marks" value="<?= $item->item_marks ?>"
                           style="width:500px;"/>
                </div>
            </div>
        </div>
        <div class="gallery_block">
            <div class="innerTableHeaderGreen">
                <div class="left padAll5">Галлерея статьи</div>
                <div class="padAll5 right">
                    <img class="marRight5" src="<?= base_url() ?>images/big-plus.gif" alt=""/>
                    <a onclick="adminObj.add_form('gallery');return false;" href="#">Добавить</a>
                </div>
            </div>
            <div id="new_gallery_block" style="float:left;width:700px;margin-bottom:10px;display:none;">
                <div style="width:100%;float:left;">
                    <div style="float:left; margin-bottom: 0px;margin-top:0;">
                        <div class="left padAll5">
                            <?php if (!empty($galleries)) {
                                ?>
                                <select id="galleries">
                                    <?php
                                        $gallery_str = '<option value="0">выберите галерею</option>';
                                        foreach ($galleries as $gallery) {
                                            $gallery_str .= '<option value="' . $gallery->gallery_id . '">' . $gallery->gallery_title . '</option>';
                                        }
                                        echo $gallery_str;
                                    ?>
                                </select>
                            <?php
                            }
                            ?>
                            <input type="button" value="Привязать галерею к статье" onclick="adminObj.assign_gallery_to_item('<?= $item->item_id ?>');"/>
                        </div>
                    </div>
                </div>
            </div>
            <div id="imggallery_img"><?= $gallery_item ?></div>
        </div>
    </div>
    <div style="float: right; margin-right: 5px; width: 250px;padding-right:15px;">
        <div>
            <div style="font-weight:bold;font-size:16px;margin-bottom:15px;color:red;">
                <input type="hidden" id="item_id" value="<?= $item->item_id ?>"/>
                <div class="delete_all_btn"
                     onclick="adminObj.save_item('<?= $item->item_id ?>', '<?=base_url()?>admin/<?= $item_type ?>/save');return false;"
                     style="width:200px;">
                    <span>Сохранить</span>
                </div>
                <div class="delete_btn"
                     onclick="if(confirm('Статья удалится вместе с прикрепленным к ней материалом. Вы уверены, что хотите удалить эту статью?')) adminObj.delete_item('<?= $item->item_id ?>', true);"
                     style="float:left;">
                    <span class="delete_btn_span">Удалить</span>
                </div>
                <div class="delete_btn" onclick="adminObj.refreshPage();" style="float:left;">
                    <span class="delete_btn_span">Обновить</span>
                </div>
            </div>
            <?php if ($item_type == 'products') {
                $current_currency_id    = $currency_rate->currency_id;
                $current_currency_value = $currency_rate->currency_value;
                unset($currency_rate->currency_id);
                unset($currency_rate->currency_value);
                $currency_rate = (array)$currency_rate;
                foreach ($currency_all as $c) {
                    $crate   = strtolower($c->currency_value);
                    $c->rate = round($currency_rate[$crate], 2);
                }
                ?>
                <div style="float:left;margin:7px 0 7px 5px;width:150px;">
                    <div style="font-weight:bold;margin-bottom:5px;">Текущий курс:</div>
                    <div style="margin-bottom:5px;margin-left:10px;">
                        <div><strong>UAH</strong> = </span></div>
                        <?php
                            foreach ($currency_all as $currency) {
                                if ($currency->currency_value == 'UAH') {
                                    continue;
                                } ?>
                                <div>
                                    <span><?= $currency->currency_value ?></span> =
                                <span id="cr_<?= strtolower($currency->currency_value) ?>">
                                    <?= $currency->rate ?>
                                </span>
                                </div>
                            <?php } ?>
                    </div>
                    <div style="font-weight:bold;margin-bottom:5px;">Цена на товар:</div>
                    <div style="margin-bottom:5px;margin-left:10px;">
                        <div class="price_head">
                            <div>
                                <div class="price_name_head_cost price_name"
                                     onclick="adminObj.change_price_value('<?= $item->item_id ?>', 'hs_set'); return hs.htmlExpand(this, {contentId:'hs_<?= $item->item_id ?>'})">
                                    <span id="price_item_<?= $item->item_id ?>"><?= $item->item_price ?></span>
                                    <input type="hidden" id="item_price_<?= $item->item_id ?>"
                                           value="<?= $item->item_price ?>"/>
                                </div>
                                <div class="price_name_head_value">
                                    <select id="price_select_<?= $item->item_id ?>"
                                            onchange="adminObj.change_price_value('<?= $item->item_id ?>', 'display')">
                                        <option value="uah">UAH</option>
                                        <option value="usd">USD</option>
                                        <option value="eur">EUR</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="highslide-html-content" id="hs_<?= $item->item_id ?>">
                    <div class="highslide-header">
                        <ul>
                            <li class="highslide-move"><a href="#" onclick="return false">Move</a></li>
                            <li class="highslide-close"><a href="#" onclick="return hs.close(this)"></a></li>
                        </ul>
                    </div>
                    <div class="highslide-body">
                        <div style="margin:5px 0;">
                            <input size="10" id="cr_val_<?= $item->item_id ?>" value=""
                                   onkeyup="adminObj.change_price_value('<?= $item->item_id ?>', 'change');">&nbsp;
                            <select id="price_select_change_<?= $item->item_id ?>"
                                    onchange="adminObj.change_price_value('<?= $item->item_id ?>', 'change')"
                                    style="margin:0;width:87px;">
                                <option value="uah">UAH</option>
                                <option value="usd">USD</option>
                                <option value="eur">EUR</option>
                            </select>
                        </div>
                        <div>
                            <div style="margin:5px auto;"><strong>UAH</strong> - <span id="cr_uah_<?= $item->item_id ?>"></span>
                            </div>
                            <div style="margin:5px auto;"><strong>USD</strong> - <span id="cr_usd_<?= $item->item_id ?>"></span>
                            </div>
                            <div style="margin:5px auto;"><strong>EUR</strong> - <span id="cr_eur_<?= $item->item_id ?>"></span>
                            </div>
                        </div>
                        <div>
                            <input type="button" value="Применить" onclick="adminObj.change_price('<?= $item->item_id ?>');"/>
                            <img id="loader_<?= $item->item_id ?>" src="<?= base_url() ?>images/ajax-loader.gif"
                                 style="display:none;"/>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div style="font-weight:bold;margin-bottom:5px;float:left;"><b>Режим просмотра статьи:</b></div>
            <div style="float:left;margin-bottom:7px;">
                <?php if (isset($item->item_mode)) {
                    echo '<div style="float:left;position:relative;bottom:7px;"><img src="' . base_url(
                        ) . 'images/icons/' . $item->item_mode . '.png" /></div>';
                } ?>
                <div style="float:left;margin-left:7px;bottom:7px;">
                    <select id="item_mode_<?= $item->item_id ?>">
                        <option value="open" <?= ($item->item_mode == 'open' ? 'selected' : ''); ?>>Опубликована</option>
                        <option value="close" <?= ($item->item_mode == 'close' || $item->item_mode == 'draft' ? 'selected' : ''); ?>>Закрыта</option>
                    </select>
                </div>
            </div>
            <div style="font-weight:bold;margin-bottom:5px;float:left;"><b>Дата опубликования статьи:</b></div>
            <div style="float:left;margin-bottom:7px;">
                <div class="datepicker_block">
                    <input type="text" id="datepicker_<?= $item->item_id ?>"
                           value="<?php echo date("d.m.Y", strtotime($item->item_production)); ?>"><br/>
                    <input style="width:20px;" type="text" id="hour_<?= $item->item_id ?>"
                           value="<?php echo date("H", strtotime($item->item_production)); ?>"> -
                    <input style="width:20px;" type="text" id="minute_<?= $item->item_id ?>"
                           value="<?php echo date("i", strtotime($item->item_production)); ?>">
                </div>
            </div>
            <div id="item_title_img" style="float:left;">
                <?php
                    if (!empty($item->attach) && isset($item->attach->attach_preview_path)) {
                        ?>
                        <img width="235" alt="" border="0" src="<?= base_url() . $item->attach->attach_preview_path ?>"/>
                    <?php } ?>
            </div>
            <div style="float:left;margin-bottom:10px;margin-top:10px;">
                <a href="#" id="imgtitle_<?= $item->item_id ?>">
                    <img class="verticalMiddle" alt="" border="0" src="<?= base_url() ?>images/upload-green-arrow.gif"/>
                    <img class="marLeft5 verticalMiddle" alt="" border="0"
                         onclick="$('#imgtitle_<?= $item->item_id ?>').fileUploadStart()"
                         src="<?= base_url() ?>images/image-icon.jpg"/>
                    <span>Логотип статьи</span>
                </a>
            </div>
            <br/>
            <?php if (isset($categories) && !empty($categories)) { ?>
                <div style="margin: 0pt auto; width: 185px; float: left;">
                    <span>Новая категория</span><br/>
                    <select id="categories_new">
                        <option value="0">Родительская категория</option>
                        <?php
                            $str_cat = '';
                            $indention = '';
                            foreach ($categories as $category) {
                                $indention = str_repeat("&nbsp;&nbsp;", $category->level);
                                $str_cat .= '<option value="' . $category->category_id . '">' . $indention . $category->category_title . '</option>';
                            }
                            echo $str_cat;
                        ?>
                    </select>
                    <input type="text" id="category_title" style="width:180px;"/><br/>
                    <a href="#" onclick="adminObj.add_category();" style="float:right;">Добавить</a>
                </div>
                <div id="chboxes"
                     style="float: left;overflow-y: auto; height: 850px; overflow-x: hidden; width: 230px;padding-right:15px;margin-top:10px;margin-bottom:20px;">
                    <?php
                        $cat_str = '';
                        $level = null;
                        foreach ($categories as $index => $category) {
                            $checked = "";
                            $level   = $category->level;
                            unset($category->level);
                            if (in_array($category, $items_cats)) {
                                $checked = "checked";
                            }
                            $margin = 10 * $level;
                            $style  = 'style="margin-left:' . $margin . 'px;"';
                            $cat_str .= '<div ' . $style . '>
                                <input type="checkbox" value="' . $category->category_id . '" ' . $checked . ' />'
                                . $category->category_title .
                                '</div>';
                        }
                        echo $cat_str;
                    ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php require_once("footer.php"); ?>