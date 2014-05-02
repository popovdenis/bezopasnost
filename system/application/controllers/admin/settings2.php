<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Denis
 * Date: 09.05.11
 * Time: 15:38
 * To change this template use File | Settings | File Templates.
 */
 
class Settings extends Controller {

    function __construct() {
        parent::Controller();
    }

    public function index() {
        $this->load->model('category_mdl','category');
        $this->load->model('items_mdl','items');
        $this->load->helper('bk');

        $categories = get_categories_tree(-1, array(), -1);
        $currencylist = $this->_search_currency_list();
        $userlist = $this->_search_user_list();
        $items = $this->get_item(null, 'products');
        $ann_items = $this->get_ann_items();

        $cat_str = '';
        foreach ($categories as $category) {
            $indention = '';

            $indention = str_repeat("&nbsp;&nbsp;", $category->level);
            $cat_str .= '<option value="'.$category->category_id.'">'.$indention.$category->category_title.'</option>';
        }
        $val = array();
        $val['ann_items'] = $ann_items;
        $val['items'] = $items;
        $val['categories'] = $cat_str;
        $val['userlist'] = $userlist;
        $val['currencylist'] = $currencylist;

        $data = array();
        $data['page'] = $this->load->view('admin/_settings_main_page', $val, true);
        $this->load->view('admin/settings', $data);
    }

    function _search_currency_list(){
        $this->load->model('currency_mdl','currency');
        $currency = $this->currency->get_currency();

        $data = array();
        $data['currency'] = $currency;
        return $this->load->view('admin/_search_currency_list', $data, true);
    }

    function _search_user_list(){
        $this->load->model('user_mdl','user');
        $users = $this->user->get_users();

        $data = array();
        $data['users'] = $users;
        return $this->load->view('admin/_search_user_list', $data, true);
    }

    function get_item($item_id, $item_type = null, $item_mode = false, $category=null, $per_page=0, $page=1, $with_count = false){
        $this->load->model('items_mdl','items');

        return $this->items->get_item($item_id, $item_type, $item_mode, $category, $per_page, $page, $with_count);
    }

    function get_ann_items(){
        $this->load->model('category_mdl','category');

        $cat = $this->category->get_category(null, null, 'Ad');
        if($cat && is_array($cat)) $cat = $cat[0];
        $items = $this->get_item(null, null, null, $cat->category_id);
        $val['items'] = $items;
        return $this->load->view('admin/ann_items', $val, true);
    }
}
