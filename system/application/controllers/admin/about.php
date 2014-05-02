<?php
/**
 * User: Denis
 * Date: 01.05.14
 * Time: 18:49
 */
class About extends Controller
{
    function __construct()
    {
        parent::Controller();
        $this->load->model( 'category_mdl', 'category' );
    }

    function index()
    {
        $user_id = $this->db_session->userdata('user_id');
        $user_role = $this->db_session->userdata('user_role');
        $page = 'about';
        $page_rus = ' Компании';
        $item_id = 0;
        $flag = 'exist';


        $config = $this->load->config('upload');
        $val = array();

        $item = $this->get_item(null, $page);
        if ($item && is_array($item)) {
            $item = $item[0];
        }

        if ($item->item_production > date("Y-m-d H:i:s")) {
            $item->item_mode = 'draft';
        }

        $val['item'] = $item;
        $val['item_id'] = null;
        $val['item_type'] = $page;
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

        $main = $this->category->get_category(null, null, 'Партнеры');
        $parent_id = 0;
        if ($main && is_array($main)) {
            $parent_id = $main[0]->category_id;
            $main[0]->level = 0;
        }
        $val['categories'] = $main;
        $val['items_cats'] = $this->items->get_item_category($item->item_id);

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