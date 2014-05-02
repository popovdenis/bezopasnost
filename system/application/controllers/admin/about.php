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

    public function save()
    {
        $item_title           = $this->input->post('item_title');
        $item_preview         = $this->input->post('item_preview');
        $item_marks           = $this->input->post('item_marks');
        $item_tags            = $this->input->post('item_tags');
        $item_type            = $this->input->post('item_type');
        $date_production      = $this->input->post('item_date_production');
        $minute_production    = $this->input->post('minute');
        $hour_production      = $this->input->post('hour');
        $item_mode            = $this->input->post('item_mode');
        $item_seo_title       = $this->input->post('item_seo_title');
        $item_seo_keywords    = $this->input->post('item_seo_keywords');
        $item_seo_description = $this->input->post('item_seo_description');
        $categories           = $this->input->post('categories');
        $content              = $this->input->post('content');
        $charecters           = $this->input->post('charecters');
        $itemId               = $this->input->post('item_id', null);

        $dateTimeProduction = new DateTime();
        if (!empty($date_production)) {
            $dateTimeProduction = new DateTime($date_production);
            if ($dateTimeProduction) {
                $dateTimeProduction->setTime($hour_production, $minute_production);
            }
        }

        $item_data = array(
            'item_title'           => trim($item_title),
            'item_preview'         => $this->input->xss_clean($item_preview),
            'item_content'         => $this->input->xss_clean($content),
            'item_characters'      => $this->input->xss_clean($charecters),
            'item_added'           => date("Y-m-d H:i:s"),
            'item_update'          => date("Y-m-d H:i:s"),
            'item_production'      => $dateTimeProduction->format("Y-m-d H:i:s"),
            'item_type'            => $item_type,
            'item_tags'            => $item_tags,
            'item_marks'           => $item_marks,
            'item_mode'            => $item_mode,
            'item_seo_title'       => $item_seo_title,
            'item_seo_keywords'    => $item_seo_keywords,
            'item_seo_description' => $item_seo_description,
        );

        $this->load->model('items_mdl', 'items');

        if (!empty($itemId)) {
            $this->items->delete_item_category($itemId);
        }

        $itemId = $this->items->save_item($item_data, $itemId);

        if (!empty($categories) && is_array($categories)) {
            foreach ($categories as $categoryId) {
                $this->items->save_item_category(intval($categoryId), $itemId);
            }
        }

        redirect('/admin/about', 'refresh');
    }

    function index()
    {
        $page = 'about';
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
        if ($main && is_array($main)) {
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