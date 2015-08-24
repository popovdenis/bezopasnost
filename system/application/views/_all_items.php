<div id="item_category"><h1><?= $category->category_title ?></h1></div>
<?php
mb_internal_encoding("UTF-8");

$item_str = '';
foreach ($items as $item) {
    $item_desc = "";
    if (! empty($item->item_content)) {
        $item->item_content = html_entity_decode($item->item_content, ENT_QUOTES, 'UTF-8');
        if (mb_strlen($item->item_content) > 575) {
            $item_desc = mb_substr($item->item_content, 0, 570) . "...";
        } else {
            $item_desc = $item->item_content;
        }
        $item_desc = preg_replace("/<p><img(.*?)\/><\/p>/si", "", $item_desc);
        $item_desc = str_replace(array('<p>', '</p>'), '', $item_desc);
    }
    $item_str .= '<div class="article_preview"><a href="' . base_url() . 'information/subcat/' . $category->category_id . '/about/' . $item->item_id . '">'
        . $item->item_title . '</a><p>' . $item_desc . '</p></div>';
}
echo $item_str;
?>
