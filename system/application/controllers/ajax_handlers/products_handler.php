<?php

/**
 * Class Products_handler
 *
 * products handler
 *
 * @author   Popov
 * @access   public
 * @package  Comment.class.php
 * @created  Fri Sep 25 12:41:38 EEST 2009
 */
class Products_handler extends Controller
{
    private $slug = 'products';
    private $category_title = 'Продукция';
    private $per_page = 15;
    private $num_links = 1;
    private $cur_page = 1;
    private $uri_segment = 1;

    /**
     * Constructor of Comment
     *
     * @access  public
     */
    public function Products_handler()
    {
        parent::Controller();
    }

    public function ajax_actions()
    {
        $action = $this->input->post('action');
        $data = '';
        switch ($action) {
            case "paginate_items":
                $cur_page    = $this->input->post('page');
                $category_id = $this->input->post('category_id');
                $data = (Object)$this->get_items_block($cur_page, $category_id);
                $data = json_encode($data);
                break;
            case "filter_products":
                $filter_name = $this->input->post('filter_name');
                $filter_size = $this->input->post('filter_size');
                $category_id = $this->input->post('category_id');
                $order_by = " order by i.item_added desc ";
                if ($filter_name == "za") {
                    $order_by = " order by i.item_content desc ";
                } elseif ($filter_name == "az") {
                    $order_by = " order by i.item_content asc ";
                }
                if ($filter_size != 'all') {
                    $this->per_page = $filter_size;
                } else {
                    $this->per_page = 0;
                }
                $items_block = $this->get_items_block(1, $category_id, true, '', $order_by);
                $data           = array();
                $item_main      = $items_block['item_main'];
                $page_container = $items_block['page_container'];
                $data = (Object)array('items_block' => $item_main, 'page_container' => $page_container);
                $data = json_encode($data);
                break;
            case "compare":
                $item_id = $this->input->post('item_id');
                $compare = $this->db_session->userdata('compare');
                if (empty($compare)) {
                    $compare = array();
                }
                if (!in_array($item_id, $compare)) {
                    $compare[] = $this->input->post('item_id');
                }
                $this->db_session->set_userdata('compare', $compare);
                $data = 1;
                break;
        }
        $this->output->set_output($data);
    }

    function get_items_block(
        $cur_page,
        $category_id,
        $item_mode = true,
        $extras = '',
        $order_by = 'order by i.item_added desc'
    ) {
        $this->load->model('items_mdl', 'items');
        $this->load->model('attachment');
        $page      = $cur_page;
        $items     = $this->items->get_item(
            null,
            $this->slug,
            $item_mode,
            $category_id,
            $this->per_page,
            $page,
            true,
            $extras,
            $order_by
        );
        $items_all = $items['count'];
        unset($items['count']);
        if ($items) {
            foreach ($items as $item) {
                $title = $this->attachment->get_attach_item($item->item_id, 'product_title');
                if ($title && is_array($title)) {
                    $item->attach = $title[0];
                } else {
                    $item->attach = null;
                }
            }
        }
        $data                = array();
        $data['products']    = $items;
        $data['category_id'] = $category_id;
        $items_str           = $this->load->view('_all_products', $data, true);
        $page_container = array(
            'total_rows'  => $items_all,
            'per_page'    => $this->per_page,
            'num_links'   => $this->num_links,
            'cur_page'    => $cur_page,
            'uri_segment' => $this->uri_segment,
            'base_url'    => base_url() . index_page() . 'products/page/'
        );
        $page_container = paginate_ajax($page_container);
        $info = array('item_main' => $items_str, 'page_container' => $page_container);
        return $info;
    }
}

?>
