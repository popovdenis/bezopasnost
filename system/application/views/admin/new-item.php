<?php require_once("header.php"); ?>
    <script type="text/javascript">
        $(document).ready(function(){
            productsObj.init();
            productsObj.initCKeditors();
            productsObj.initDatePicker();
            adminObj.initGetCategoriesList();
        });
    </script>
    <script type="text/javascript" src="<?= base_url() ?>js/highslide/highslide-with-html.js"></script>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>js/highslide/highslide.css"/>
    <script type="text/javascript">
        hs.graphicsDir = '<?=base_url()?>js/highslide/graphics/';
        hs.outlineType = 'rounded-white';
        hs.wrapperClassName = 'draggable-header';

    </script>
<input type="hidden" name="item_type" value="">
<div style="width:100%;float:left;margin-bottom:35px;border:1px solid #CCCCCC;border-top:none;">
    <div style="float:left;width:780px; margin-left:10px;margin-top:10px;">
        <div>
            <div class="product_element">
                <span style="float: left; width: 100%;"><strong>Название статьи</strong></span>
                <input type="text" id="item_title" name="item_title" value=""
                       style="width:500px;"/>
            </div>
            <div class="product_element">
                <span><strong>Краткое описание статьи</strong></span><br/>
                <textarea class="editor" name="item_preview" id="item_preview" style="min-height:70px;width:775px;"></textarea>
            </div>
                <div class="product_element">
                    <span><strong>Характеристики</strong></span><br/>
                    <textarea class="editor" name="item_charecters" id="item_charecters" style="min-height:70px;width:775px;"></textarea>
                </div>
            <div>
                <span><strong>Описание статьи</strong></span>
                <textarea class="editor" name="post_content" id="post_content" style="min-height:330px;"></textarea>
            </div>
            <div class="product_element">
                <div class="seo_params_block">
                    <span class="seo_params">Seo параметры</span>
                    <div class="seo_title">
                        <span><strong>Title (название)</strong>&nbsp;<i>(50-80 знаков)</i></span>
                        <input type="text" id="item_seo_title" name="item_seo_title" value=""
                               style="width:500px;"/>
                    </div>
                    <div class="seo_keywords">
                        <span><strong>Keywords (ключевые слова)</strong>&nbsp;<i>(до 250 знаков)</i></span>
                        <input type="text" id="item_seo_keywords" name="item_seo_keywords"
                               value="" style="width:500px;"/>
                    </div>
                    <div class="seo_description">
                        <span><strong>Description (описание)</strong>&nbsp;<i>(150-200 знаков)</i></span>
                        <textarea class="editor" type="text" id="item_seo_description" name="item_seo_description" cols="97" rows="7"></textarea>
                    </div>
                </div>
                <div>
                    <span><strong>Метки</strong>&nbsp;<i>(для быстрого поиска статей)</i></span><br/>
                    <input type="text" id="item_marks" name="item_marks" value="" style="width:500px;"/>
                </div>
            </div>
        </div>
    </div>
    <div style="float: right; margin-right: 5px; width: 250px;padding-right:15px;">
        <div>
            <div style="font-weight:bold;font-size:16px;margin-bottom:15px;color:red;">
                <input type="hidden" id="item_id" value=""/>
                <div class="delete_all_btn"
                     onclick="adminObj.save_item('new', '<?=base_url()?>admin/newitem/save');return false;"
                     style="width:200px;">
                    <span>Сохранить</span>
                </div>
                <div class="delete_btn" onclick="adminObj.refreshPage();" style="float:left;">
                    <span class="delete_btn_span">Обновить</span>
                </div>
            </div>
            <div style="font-weight:bold;margin-bottom:5px;float:left;"><b>Режим просмотра статьи:</b></div>
            <div style="float:left;margin-bottom:7px;">
                <div style="float:left;margin-left:7px;bottom:7px;">
                    <select id="item_mode_new">
                        <option value="open">Опубликована</option>
                        <option value="close">Закрыта</option>
                    </select>
                </div>
            </div>
            <div style="font-weight:bold;margin-bottom:5px;float:left;"><b>Дата опубликования статьи:</b></div>
            <div style="float:left;margin-bottom:7px;">
                <div class="datepicker_block">
                    <input type="text" id="datepicker_new"
                           value=""><br/>
                    <input style="width:20px;" type="text" id="hour_new"
                           value=""> -
                    <input style="width:20px;" type="text" id="minute_new"
                           value="">
                </div>
            </div>
            <div id="item_title_img" style="float:left;"></div>
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
                <select class="select-list" name="change_categories_list">
                <?php
                    $str_cat = '';
                    foreach ($mainCategories as $category) {
                        $selected = $category->category_title == 'Продукция' ? 'selected' : '';
                        $str_cat .= '<option data-item-type="'. $category->item_type .'"
                                        value = "' . $category->category_id . '" ' . $selected . '>'
                            . $category->category_title;
                    }
                    echo $str_cat;
                ?>
                </select>
                <!--list of categories-->
                <div id="chboxes" style="float: left;margin-left:10px;">
                    <?php
                        $cat_str = '';
                        $cat_str .= '<div style="position:relative;right:12px;">
                                <input type="checkbox" value="' . $category_main->category_id . '" />' . $category_main->category_title .
                            '</div>';
                        $level = null;
                        foreach ($categories as $index => $category) {
                            $margin = 10 * $category->level;
                            $style  = 'style="margin-left:' . $margin . 'px;"';
                            $cat_str .= '<div ' . $style . '><input type="checkbox" id="ch_door" value="' . $category->category_id . '" />
                                ' . $category->category_title . '</div>';
                        }
                        echo $cat_str;
                    ?>
                </div>
            <?php } ?>
        </div>
    </div>
    </div>
<?php require_once("footer.php"); ?>