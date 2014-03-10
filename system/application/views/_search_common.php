<div class="search_box">
    <div style="float:left;">Около
        <span id="count_result"><?= count($items) ?></span> результов найдено для &quot;
        <?=$keywords?>&quot;
    </div>
    <div style="float:right;">
        <form id="searchform" name="searchform" method="POST" action="<?=base_url()?>search" enctype="multipart/form-data">
            <input id="keywords" class="idleField search_input" type="text" value="" name="keywords"/>
            <img src="<?=base_url()?>images/search.png" class="search_lens" onclick="$('#searchform').submit();">
        </form>
    </div>
</div>
<div id="main" style="margin-top:15px;">
    <ul id="results-Продукция-ul" class="results" style="overflow: visible;">
    <?php
        if (!empty($items)) {
            foreach ($items as $index => $item) {
                $item_desc = (mb_strlen($item['item_preview']) > 575)
                    ? mb_substr(
                        $item['item_preview'],
                            0,
                            570
                        ) . "..."
                    : $item['item_preview'];
                $class = ($index < 5) ? 'top-results' : '';
                $category = explode(',', $item['categories'])[0];
    ?>
                <li class="<?= $class ?>">
                    <h4>
                        <a href="<?=base_url().$item['item_type'].'/subcat/'.$category.'/about/'.$item['item_id']?>">
                            <?= $item['item_title'] ?>
                        </a>
                    </h4>
                    <p class="desc"><?= $item_desc ?></p>
                </li>
    <?php
            }
        }
        ?>
    </ul>
</div>