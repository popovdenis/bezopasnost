<?php
require_once ('adminAbstract.php');
/**
 * User: Denis
 * Date: 02.05.14
 * Time: 18:09
 */
class Products extends adminAbstract
{
    const PER_PAGE = 50;
    const PAGE = 1;
    protected $itemType = 'products';
    protected $itemName = 'Продукция';

    function index()
    {
        $config = $this->load->config('upload');
        $val = array();

        $item = $this->get_item(null, $this->itemType);
        if ($item && is_array($item)) {
            $item = $item[0];
        }

        if ($item->item_production > date("Y-m-d H:i:s")) {
            $item->item_mode = 'draft';
        }

        $val['item'] = $item;
        $val['item_id'] = null;
        $val['item_type'] = $this->itemType;
        $val['allowed_types'] = $config['allowed_types'];

        $this->load->model('attachment');
        $title = $this->attachment->get_attach_item($item->item_id, 'product_title');

        if ($title && is_array($title)) {
            $item->attach = $title[0];
        } else {
            $item->attach = null;
        }

        $this->load->model('category_mdl', 'category');
        $this->load->helper('bk');
        $this->load->model('gallery_mdl');

        $val['galleries'] = $this->gallery_mdl->get_gallery();
        $val['gallery_item'] = $this->get_gallery_item(null);

        $this->_items_block();
    }

    public function about()
    {
        $config = $this->load->config('upload');
        $val = [];

        $item_id = $this->uri->segment(4);
        $item = $this->get_item($item_id, $this->itemType);
        if ($item && is_array($item)) {
            $item = $item[0];
        }

        if ($item->item_production > date("Y-m-d H:i:s")) {
            $item->item_mode = 'draft';
        }

        $val['item'] = $item;
        $val['item_id'] = null;
        $val['item_type'] = $this->itemType;
        $val['allowed_types'] = $config['allowed_types'];

        $this->load->model('attachment');
        $title = $this->attachment->get_attach_item($item->item_id, 'product_title');

        if ($title && is_array($title)) {
            $item->attach = $title[0];
        } else {
            $item->attach = null;
        }

        $this->load->model('category_mdl', 'category');
        $this->load->helper('bk');
        $this->load->model('gallery_mdl');

        $val['galleries'] = $this->gallery_mdl->get_gallery();
        $val['gallery_item'] = $this->get_gallery_item($item->item_id);

        $this->load->model('items_mdl', 'items');
        $this->load->model('currency_mdl', 'currency');

        $main = $this->category->get_category(null, null, 'Продукция');
        $parent_id = 0;
        if ($main && is_array($main)) {
            $main = $main[0];
            $parent_id = $main->category_id;
        }
        $val['categories'] = get_categories_tree($parent_id, array(), -1);
        $val['items_cats'] = $this->items->get_item_category($item->item_id);
        $val['currency_rate'] = $this->currency->get_currency_rate(1)[0];
        $val['currency_all'] = $this->currency->get_currency();


        $this->load->view('admin/about', $val);
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

    function get_gallery_item($item_id)
    {
        $this->load->model('gallery_mdl', 'gallery');
        $gallery = $this->gallery->get_item_gallery(null, $item_id);
        $val = array();
        $val['gallery'] = $gallery;
        $val['item_id'] = $item_id;
        return $this->load->view('admin/gallery_image_item', $val, true);
    }
}