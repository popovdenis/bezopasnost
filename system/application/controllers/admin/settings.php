<?php
/**
 * User: Denis
 * Date: 02.05.14
 * Time: 20:14
 */
class Settings extends Controller
{
    const ITEM_TYPE = 'settings';
    const ITEM_NAME = 'Настройки';

    function index()
    {
        $this->load->model('category_mdl', 'category');
        $this->load->model('items_mdl', 'items');
        $this->load->helper('bk');

        $categories   = get_categories_tree(-1, array(), -1);
        $currencylist = $this->_search_currency_list();
        $userlist     = $this->_search_user_list();
        $items        = $this->get_item(null, 'products');
        $ann_items    = $this->get_ann_items();

        $cat_str = '';
        foreach ($categories as $category) {
            $indention = str_repeat("&nbsp;&nbsp;", $category->level);
            $cat_str .= '<option value="' . $category->category_id . '">' . $indention . $category->category_title . '</option>';
        }

        $val = [
            'items'        => $items,
            'ann_items'    => $ann_items,
            'categories'   => $cat_str,
            'userlist'     => $userlist,
            'currencylist' => $currencylist,
            'item_type'    => self::ITEM_TYPE
        ];

        $this->load->view('admin/settings', $val);
    }

    function _search_currency_list()
    {
        $this->load->model('currency_mdl', 'currency');
        return $this->load->view(
            'admin/_search_currency_list',
            [
                'currency' => $this->currency->get_currency()
            ],
            true
        );
    }

    function _search_user_list()
    {
        $this->load->model('user_mdl', 'user');
        return $this->load->view(
            'admin/_search_user_list',
            [
                'users' => $this->user->get_users()
            ],
            true
        );
    }

    function get_item(
        $item_id,
        $item_type = null,
        $item_mode = false,
        $category = null,
        $per_page = 0,
        $page = 1,
        $with_count = false
    ) {
        $this->load->model('items_mdl', 'items');

        return $this->items->get_item($item_id, $item_type, $item_mode, $category, $per_page, $page, $with_count);
    }

    function get_ann_items()
    {
        $this->load->model('category_mdl', 'category');

        $cat = $this->category->get_category(null, null, 'Ad');
        if ($cat && is_array($cat)) {
            $cat = $cat[0];
        }
        return $this->load->view(
            'admin/ann_items',
            [
                'items' => $this->get_item(null, null, null, $cat->category_id)
            ],
            true
        );
    }
}