<?php
    $links = array(
        'contacts'    => 'Контакты',
        'information' => 'Информация',
        'partners'    => 'Бренды',
        'products'    => 'Продукция',
        'about'       => 'О компании',
        'main'        => 'Главная',
        ''            => ''
    );
    $main_cats = get_menu_categories();

    $this->load->helper('url');
    $_link = $this->uri->segment(1);
    if (empty($_link)) {
        $_link = 'main';
    }
?>

<script language="JavaScript" type="text/javascript">
    $(document).ready(function(){
        $("#products_menuitems, #partners_menuitems, #information_menuitems").mouseover(function () {
            $('#sub_' + $(this).attr('id')).show();
        }).mouseout(function () {
            $('#sub_' + $(this).attr('id')).hide();
        });

        var $window         = $(window),
            $headerMenu     = $('.header_thread');

        $window.scroll(function() {
            if (!$headerMenu.hasClass("fixed") && ($window.scrollTop() > 226)) {
                $headerMenu.addClass("fixed");
            }
            else if ($headerMenu.hasClass("fixed") && ($window.scrollTop() < 226)) {
                $headerMenu.removeClass("fixed");
            }
        });
    });
</script>
<div class="header">
    <div class="header_thread">
        <div id="search_menuitems" class="menu_item">
            <form id="quickSearch" action="<?= base_url() ?>search" method="post"
                  enctype="application/x-www-form-urlencoded">
                <input id="main_keywords" name="main_keywords" type="text" onkeyup="main_quick_search();" value="Быстрый поиск">
                <img src="<?= base_url() ?>images/search.png"
                     style="cursor:pointer;position: relative; top: 7px; width: 30px; height: 30px;"
                     onclick="$('#quickSearch').submit();">
            </form>
            <div class="menuitems_block" id="sub_search_menuitems" style="display:block;top:35px;width:195px;"></div>
        </div>
        <div class="menu">
            <?php
                foreach ($main_cats as $cat) {
                    $class = "menu_item";
                    $key   = array_search($cat->category_title, $links);
                    if ($key && $key == $_link) {
                        $class = 'menu_item_selected';
                    }
                    ?>
                    <div id="<?= $key ?>_menuitems" class="<?= $class ?>">
                        <a href="<?= base_url() . index_page() . $key ?>"><?= $cat->category_title ?></a>
                <?php
                    if (! empty($cat->subcat)) {
                ?>
                        <div id="sub_<?= $key ?>_menuitems" class="menuitems_block">
                            <ul>
                <?php
                        $count = count($cat->subcat) - 1;
                        foreach ($cat->subcat as $index => $subCat) {
                            $slug = $key . '/category/';
                ?>
                                <li <?= ($index == $count) ? 'style="border:none;"' : '' ?>>
                                    <a href="<?= base_url() . $slug . $subCat->category_id ?>"><?= $subCat->category_title ?></a>
                                </li>
                <?php
                        }
                ?>
                            </ul>
                        </div>
                <?php
                    }
                    echo '</div>';
                }
                ?>
            <div class="menu_item">
                <a href="<?= base_url() ?>"></a>
            </div>
        </div>
    </div>
</div>
<div class="header_shadow">&nbsp;</div>
