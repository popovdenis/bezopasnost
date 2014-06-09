<?php

/**
 * Class Search_handler
 *
 * handler of Search
 *
 * @author   Home
 * @access   public
 * @package  Search_handler.class.php
 * @created  Sun Nov 29 12:01:25 EET 2009
 */
class Search_handler extends Controller
{
    private $per_page = 25;
    private $num_links = 1;
    private $uri_segment = 1;

    /**
     * Constructor of Search_handler
     *
     * @access  public
     */
    function Search_handler()
    {
        parent::Controller();
    }

    function ajax_actions()
    {
        $keywords = $this->input->post('keywords');
        if (empty($keywords)) {
            if ($this->db_session->flashdata('keywords')) {
                $keywords = $this->db_session->flashdata('keywords');
            } elseif ($this->db_session->userdata('keywords')) {
                $keywords = $this->db_session->userdata('keywords');
            }
        }
        $action = $this->input->post('action');
        $data   = '';
        switch ($action) {
            case "paginate_items":
                $cur_page    = $this->input->post('page');
                $category_id = $this->input->post('category_id');
                $data = (Object)$this->_get_items_block($cur_page, $keywords, $category_id, null);
                $data = json_encode($data);
                break;
            case "search_by_tag":
                $tag = $this->input->post('tag');
                $this->db_session->set_userdata('keywords', $this->input->xss_clean($tag));
                redirect('/search/');

                /*$items_block    = $this->_get_items_block(1, $tag, '');
                $page_container = array(
                    'total_rows'  => count($items_block['items_all']),
                    'per_page'    => $this->per_page,
                    'num_links'   => $this->num_links,
                    'cur_page'    => 1,
                    'uri_segment' => $this->uri_segment,
                    'base_url'    => base_url() . 'information/page/'
                );
                $item_main      = $items_block['items'];
                $page_container = paginate_ajax($page_container);
                $data = (Object)array('items_block' => $item_main, 'page_container' => $page_container);
                $data = json_encode($data);*/
                break;
            case "quick_search":
                //log_message('error', 'quick_search');
                $category_id = $this->input->post('category_id');
                $data        = (Object)$this->_get_items_block(1, $keywords, $category_id, null);
                $data        = json_encode($data);
                break;
            case "main_quick_search":
                $items_str = "";
                if (!empty($keywords)) {
                    $this->load->model('category_mdl', 'category');
                    $info_cat     = $this->category->get_category(null, null, 'Информация');
                    $info_cat_ids = array();
                    if ($info_cat) {
                        if (is_array($info_cat)) {
                            $info_cat = $info_cat[0];
                        }
                        $info_categories = get_categories_tree($info_cat->category_id, array(), -1);
                        if ($info_categories) {
                            foreach ($info_categories as $info_cat) {
                                $info_cat_ids[] = $info_cat->category_id;
                            }
                        }
                    }
                    $this->load->model('items_mdl', 'items');
                    $items     = $this->items->get_item_search($keywords, 10, 1, true);
                    $items_all = $items['count'];
                    unset($items['count']);
                    $count = count($items);
                    if ($items) {
                        $items_str = "<ul>";
                        foreach ($items as $index => $item) {
                            $style = '';
                            if ($index == $count) {
                                if ($items_all > 10) {
                                    $style = 'style="border: medium none ;"';
                                }
                            }
                            if ($item->category_title == 'Бренды' || in_array($item->category_id, $info_cat_ids)) {
                                $items_str .= '<li ' . $style . '><a href="' . base_url(
                                    ) . $item->item_type . '/category/' . $item->category_id . '">' . $item->item_title . '</a></li>';
                            } else {
                                $items_str .= '<li ' . $style . '><a href="' . base_url(
                                    ) . $item->item_type . '/subcat/' . $item->category_id . '/about/' . $item->item_id . '">' . $item->item_title . '</a></li>';
                            }
                        }
                    }
                    if ($items_all > 10) {
                        $items_str .= '<li style="border: medium none ;"><a href="' . base_url(
                            ) . 'search#' . urlencode(
                                $keywords
                            ) . '" onclick="javascript:go_to_search();return false;">Подробнее</a></li>';
                    }
                }
                $data = $items_str;
                $this->db_session->set_flashdata('keywords', $keywords);
                break;
        }
        $this->output->set_output($data);
    }

    function _get_items_block($page = 1, $keywords = "", $category_id = null, $type = 'main')
    {
        $this->load->model('items_mdl', 'items');
        $this->load->model('category_mdl', 'category');
        if (empty($category_id)) {
            $category_products = $this->category->get_category(null, null, 'Продукция');
        } else {
            $category_products = $this->category->get_category($category_id);
        }
        if ($category_products && is_array($category_products)) {
            $category_products = $category_products[0];
        }
        if ($type == 'main') {
            $items = $this->items->get_item_search_common($keywords, $category_id, $this->per_page, $page, true);
        } else {
            $items = $this->items->get_item_search_category($keywords, $category_id, $this->per_page, $page, true);
        }
        $items_all = $items['count_common'];
        unset($items['count_common']);
        $data['items']    = $items;
        $data['type']     = $type;
        $data['per_page'] = $this->per_page * $page;
        $search_result    = $this->load->view('_search_common', $data, true);
        $paginate_args = array(
            'total_rows'  => $items_all,
            'per_page'    => $this->per_page,
            'num_links'   => $this->num_links,
            'cur_page'    => $page,
            'uri_segment' => $this->uri_segment,
            'base_url'    => base_url() . 'information/page/'
        );
        $search_category = "";
        if (!empty($items)) {
            $search_category .= '<li class="selected">';
            $search_category .= '<a class="selected" id="all-categories-trigger"
            href="#results-all" onclick="sort_search_result(\'all\');
            return false;">Все категории</a>';

            $search_category .= '</li>';
            foreach ($items as $index => $item) {
                if (!isset($item->category_title)) {
                    continue;
                }
                $class   = '';
                $trigger = $item->category_id;
                $search_category .= '<li ' . $class . '>';
                $search_category .= '<a ' . $class . '
                id="' . $trigger . '-categories-trigger"
                href="#results-' . $trigger . '"
                onclick="sort_search_result(\'' . $trigger . '\');
                return false;">' . $item->category_title . '</a>';

                $search_category .= '</li>';
            }
        }
        $info = array(
            'template'      => $search_result,
            'count'         => $items_all,
            'items_all'     => $items_all,
            'paginate_args' => paginate_ajax($paginate_args),
            'main_category' => $search_category,
            'page'          => $page,
            'per_page'      => $this->per_page,
            'items'         => $items
        );
        return $info;
    }
}
