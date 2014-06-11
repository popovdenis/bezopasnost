<?php
require_once ('adminAbstract.php');
/**
 * User: Denis
 * Date: 09.06.14
 * Time: 17:42
 */
class NewItem extends AdminAbstract
{
    protected $itemType = 'new';
    protected $itemName = 'Новая запись';

    function index()
    {
        $config = $this->load->config('upload');
        $val = [];

        $val['item_id'] = null;
        $val['item_type'] = $this->itemType;
        $val['allowed_types'] = $config['allowed_types'];

        $this->load->model('category_mdl', 'category');
        $this->load->model('items_mdl', 'items');
        $this->load->helper('bk');
        $this->load->model('gallery_mdl');

        $val['galleries'] = $this->gallery_mdl->get_gallery();
        $val['gallery_item'] = $this->get_gallery_item(null);

        $root = $this->category->get_category(null, null, 'root');
        $mainCategories = $this->category->get_category(null, $root[0]->category_id, null, 'category_title');
        $main = $this->category->get_category(null, null, 'Продукция');
        $parent_id = 0;
        if ($main && is_array($main)) {
            $main = $main[0];
            $parent_id = $main->category_id;
        }
        $val['category_main'] = $main;
        $val['mainCategories'] = $mainCategories;
        $val['categories'] = get_categories_tree($parent_id, [], -1);
        $val['itemTypes'] = get_categories_tree($parent_id, [], -1);

        $this->load->view('admin/new-item', $val);
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

        $itemId = $this->items->save_item($item_data);

        if (!empty($categories) && is_array($categories)) {
            foreach ($categories as $categoryId) {
                $this->items->save_item_category(intval($categoryId), $itemId);
            }
        }

        echo json_encode([
            'success' => true,
            'item_id' => $itemId
        ]);
    }

    public function getCategories()
    {
        $categoryId = (int)$this->input->post('category_id');

        echo json_encode([
            'success' => true,
            'categories' => get_categories_tree($categoryId, array(), -1)
        ]);
    }
} 