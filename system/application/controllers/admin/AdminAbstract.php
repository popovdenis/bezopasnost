<?php
/**
 * User: Denis
 * Date: 02.05.14
 * Time: 21:56
 */
abstract class AdminAbstract extends Controller
{
    const PER_PAGE = 50;
    const PAGE = 1;
    protected $itemType = null;
    protected $itemName = null;

    public function _items_block()
    {
        mb_internal_encoding("UTF-8");

        $items_str = "";
        $val       = array();
        $val['item_type'] = $this->itemType;

        $items     = $this->get_item(null, $this->itemType, false, null, self::PER_PAGE, self::PAGE, true);
        $items_all = $items['count'];
        unset($items['count']);

        if ($items) {
            foreach ($items as &$item) {
                if (!empty($item->item_preview)) {
                    $item_desc = preg_replace("/<p><img(.*?)\/><\/p>/si", "", $item->item_preview);
                    $item_desc = str_replace(array('<p>', '</p>', '<h1>', '</h1>', '<h3>', '</h3>'), '', $item_desc);
                    if (strlen($item_desc) > 80) {
                        $item_desc = mb_substr($item_desc, 0, 75) . "...";
                    }
                    $item->item_preview = $item_desc;
                }
                $categories = $this->items->get_item_category($item->item_id);

                $cat_str = '&nbsp;';
                if ($categories && !empty($categories)) {
                    $end = end($categories);
                    foreach ($categories as &$category) {
                        $cat_str .= $category->category_title;
                        if ($end->category_title != $category->category_title) {
                            $cat_str .= ', ';
                        }
                    }
                }
                if ($item->item_production > date("Y-m-d H:i:s")) {
                    $item->item_mode = 'draft';
                }
                $item->cat_str = $cat_str;
            }

            $num_pages = (int)($items_all / self::PER_PAGE);
            if ($num_pages <= 0) {
                $num_pages = 1;
            }

            $val['currency_rate'] = $this->db_session->userdata('currency_rate');
            $val['items']         = $items;
            $val['cur_page']      = self::PAGE;
            $val['num_pages']     = $num_pages;
            $val['paginate_args'] = array(
                'total_rows'  => $items_all,
                'per_page'    => self::PER_PAGE,
                'num_links'   => 2,
                'cur_page'    => self::PAGE,
                'uri_segment' => 3,
                'js_function' => 'paginate_items',
                'base_url'    => base_url() . index_page() . 'admin/home/'
            );
            $items_str = $this->load->view('admin/_items_block', $val, true);
        }

        $this->load->model('category_mdl', 'category');
        $category_current = $this->category->get_category(null, null, '');

        if ($category_current) {
            if (is_array($category_current)) {
                $category_current = $category_current[0];
            }
            $categories = $this->category->get_category(null, $category_current->category_id);
            array_push($categories, $category_current);
        }

        $val['categories']  = $categories;
        $val['items_block'] = $items_str;

        $this->load->view('admin/list', $val);
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