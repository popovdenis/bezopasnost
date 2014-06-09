<style type="text/css">
    div.jqi {
        width: 1000px;
    }
</style>
<div style="width:980px;">
    <div style="float:left;width:720px;">
        <div>
            <div style="margin:0 0 10px 0;">
                <span><strong>Название статьи</strong></span>
                <input type="text" id="item_title" name="item_title" value="" style="width:500px;"/>
            </div>
            <div>
                <span><strong>Краткое описание статьи</strong></span>
                <textarea name="post_preview" id="post_preview" style="min-height:70px;width:715px;">&nbsp;</textarea>
            </div>
            <div>
                <span><strong>Описание статьи</strong></span>
                <textarea name="post_content" id="post_content" style="min-height:330px;">&nbsp;</textarea>
            </div>
            <div style="margin:10px 0 10px 0;">
                <div class="seo_params_block">
                    <span class="seo_params">Seo параметры</span>
                    <div class="seo_title">
                        <span><strong>Title (название)</strong>&nbsp;<i>(50-80 знаков)</i></span>
                        <input type="text" id="item_seo_title" name="item_seo_title"
                               value="<?= $item->item_seo_title ?>" style="width:500px;"/>
                    </div>
                    <div class="seo_keywords">
                        <span><strong>Keywords (ключевые слова)</strong>&nbsp;<i>(до 250 знаков)</i></span>
                        <input type="text" id="item_seo_keywords" name="item_seo_keywords"
                               value="<?= $item->item_seo_keywords ?>" style="width:500px;"/>
                    </div>
                    <div class="seo_description">
                        <span><strong>Description (описание)</strong>&nbsp;<i>(150-200 знаков)</i></span>
                        <textarea type="text" id="item_seo_description" name="item_seo_description" cols="97"
                                  rows="7"><?= $item->item_seo_description ?></textarea>
                    </div>
                </div>
                <div>
                    <span><strong>Метки</strong>&nbsp;<i>(для быстрого поиска статей)</i></span><br/>
                    <input type="text" id="item_marks" name="item_marks" value="" style="width:500px;"/>
                </div>
            </div>
        </div>
    </div>
    <div
        style="float: right; margin-right: 5px; overflow-y: auto; height: 400px; overflow-x: hidden; width: 235px;padding-right:15px;">
        <div>
            <div style="margin: 0pt auto; width: 185px; float: left;">
                <span>Категория продукта</span>
            </div>
            <div id="chboxes" style="float: left;">
                <?php
                    $cat_str = '';
                    $level = null;
                    foreach ($categories as $index => $category) {
                        $margin = 10 * $category->level;
                        $style  = 'style="margin-left:' . $margin . 'px;"';
                        $cat_str .= '<div ' . $style . '><input type="checkbox" value="' . $category->category_id . '" />
							' . $category->category_title . '</div>';
                    }
                    echo $cat_str;
                ?>
            </div>
        </div>
    </div>
</div>
