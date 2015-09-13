<?php
require_once ('adminAbstract.php');
/**
 * User: Denis
 * Date: 02.05.14
 * Time: 18:09
 */
class Partners extends adminAbstract
{
    protected $itemType = 'partners';
    protected $itemName = 'Партнетры';

    function index()
    {
        $config = $this->load->config('upload');
        $val = [];

        $item = $this->get_item(null, 'partners');
        if ($item && is_array($item)) {
            $item = $item[0];
        }

        if ($item->item_production > date("Y-m-d H:i:s")) {
            $item->item_mode = 'draft';
        }

        $val['item'] = $item;
        $val['item_id'] = null;
        $val['item_type'] = 'partners';
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
        //---------------
        $this->_items_block();
    }

    public function about()
    {
        $config = $this->load->config('upload');
        $val = [];

        $item_id = $this->uri->segment(4);
        $item = $this->get_item($item_id, 'partners');
        if ($item && is_array($item)) {
            $item = $item[0];
        }

        if ($item->item_production > date("Y-m-d H:i:s")) {
            $item->item_mode = 'draft';
        }

        $val['item'] = $item;
        $val['item_id'] = null;
        $val['item_type'] = 'partners';
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

        $main = $this->category->get_category(null, null, 'Партнеры');
        if ($main && is_array($main)) {
            $main[0]->level = 0;
        }
        $val['categories'] = $main;
        $val['items_cats'] = $this->items->get_item_category($item->item_id);

        $this->load->view('admin/about', $val);
    }
}