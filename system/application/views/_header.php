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
    jQuery().ready(function ()
    {
        $("#products_menuitems, #partners_menuitems, #information_menuitems").mouseover(function () {
            $('#sub_' + $(this).attr('id')).show();
        }).mouseout(function () {
            $('#sub_' + $(this).attr('id')).hide();
        });
        $("main_keywords").click(function () {});
    });
</script>
<div class="header">
    <div>
        <div id="search_menuitems" class="menu_item">
            <form id="quickSearch" action="<?= base_url() ?>search" method="post"
                  enctype="application/x-www-form-urlencoded">
                <input id="main_keywords" name="main_keywords" type="text" onkeyup="javascript:main_quick_search();"
                       value="Быстрый поиск">
                <img src="<?= base_url() ?>images/search.png"
                     style="cursor:pointer;position: relative; top: 7px; width: 30px; height: 30px;"
                     onclick="javascript: jQuery('#quickSearch').submit();">
            </form>
            <div class="menuitems_block" id="sub_search_menuitems" style="display:block;top:35px;width:195px;"></div>
        </div>
        <div class="menu">
            <?php
                $link_str = "";
                foreach ($main_cats as $cat) {
                    $class = "menu_item";
                    $key   = array_search($cat->category_title, $links);
                    if ($key && $key == $_link) {
                        $class = 'menu_item_selected';
                    }
                    $link_str .= '<div id="' . $key . '_menuitems" class="' . $class . '"><a href="' . base_url() . index_page() . $key . '">' . $cat->category_title . '</a>';
                    if (! empty($cat->subcat)) {
                        $link_str .= '<div id="sub_' . $key . '_menuitems" class="menuitems_block"><ul>';
                        $count = count($cat->subcat) - 1;
                        foreach ($cat->subcat as $index => $cat2) {
                            $style = '';
                            if ($index == $count) {
                                $style = 'style="border:none;"';
                            }
                            $slug = $key . '/category/';
                            $link_str .= '<li ' . $style . '><a href="' . base_url() . $slug . $cat2->category_id . '">' . $cat2->category_title . '</a></li>';
                        }
                        $link_str .= '</ul></div>';
                    }
                    $link_str .= '</div>';
                }
                $link_str .= '<div class="menu_item"><a href="' . base_url() . '"></a></div>';
                echo $link_str;
            ?>
        </div>
    </div>
</div>
<div class="header_shadow">&nbsp;</div>
