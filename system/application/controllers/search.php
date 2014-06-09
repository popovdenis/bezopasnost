<?php

/**
 * Class Search
 *
 * search controller
 *
 * @author   Home
 * @access   public
 * @package  Search.class.php
 * @created  Sat Nov 28 18:56:46 EET 2009
 */
class Search extends Controller
{
    /**
     * Constructor of Search
     *
     * @access  public
     */
    function __construct()
    {
        parent::Controller();
        $this->benchmark->mark('code_start');
    }

    /**
     * main index
     *
     * @return Controller
     */
    function index()
    {
        $this->load->helper('tagclouds');

        $keywords      = $this->input->post('keywords', true);
        $main_keywords = $this->input->post('main_keywords', true);

        if (!empty($main_keywords)) {
            $keywords = $main_keywords;
        } else {
            if ($this->db_session->flashdata('keywords')) {
                $keywords = $this->db_session->flashdata('keywords');
            } elseif ($this->db_session->userdata('keywords')) {
                $keywords = $this->db_session->userdata('keywords');
            }
        }

        $this->db_session->set_userdata('keywords', $keywords);
        $this->db_session->userdata('user_id');

        if (!empty($keywords)) {
            $items = $this->get_items_main_block(1, $keywords);
        } else {
            $items = [
                'template'      => '',
                'count'         => 0,
                'paginate_args' => '',
                'main_category' => ''
            ];
        }

        $config['meta_tags']['title'] = 'Поиск';
        $data                         = [];
        $data['search_result']        = $items;
        $data['keywords']             = $keywords;
        $data['tagclouds']            = get_tag_clouds();
        $data['meta_tags']            = build_meta_tags(null, $config['meta_tags']);

        $this->load->view('_search_main', $data);
    }

    /**
     * @param int    $page
     * @param string $keywords
     *
     * @return array
     */
    function get_items_main_block($page = 1, $keywords = "")
    {
        mb_internal_encoding("UTF-8");

        $this->load->model('items_mdl', 'items');
        $this->load->model('category_mdl', 'category');

        $categories = array();
        $category   = $this->category->get_category(null, null, 'Продукция');
        if ($category && is_array($category)) {
            $category = $category[0];
            $result   = $this->category->get_category(null, $category->category_id);
            array_push($categories, $result);
        }

        $category = $this->category->get_category(null, null, 'Бренды');
        if ($category && is_array($category)) {
            $category = $category[0];
            $category = $this->category->get_category($category->category_id);
            array_push($categories[0], $category[0]);
        }

        $category = $this->category->get_category(null, null, 'Информация');
        if ($category && is_array($category)) {
            $category = $category[0];
            $category = $this->category->get_category($category->category_id);
            array_push($categories[0], $category[0]);
        }

        $items = $this->items->get_item_search_common($keywords, $categories[0], 10, $page, true);
        $data['items']    = $items;
        $data['type']     = 'main';
        $data['per_page'] = 5;

        $search_template = $this->load->view('_search_common', $data, true);
        $search_category = "";
        if (!empty($items)) {
            $search_category .= '<li class="selected">';
            $search_category .= '<a class="selected" id="all-categories-trigger" href="#results-all" onclick="sort_search_result(\'all\'); return false;">Все категории</a>';
            $search_category .= '</li>';

            foreach ($items as $item) {
                if (!isset($item->category_title) || empty($item->search_count)) {
                    continue;
                }
                $trigger = $item->category_id;
                $search_category .= '<li>';
                $search_category .= '<a id="' . $trigger . '-categories-trigger" href="#results-' . $trigger . '" onclick="sort_search_result(\'' . $trigger . '\'); return false;">' . $item->category_title . '</a>';
                $search_category .= '</li>';
            }
        }
        return array('template'      => $search_template,
                     'count'         => $items['count_common'],
                     'paginate_args' => '',
                     'main_category' => $search_category
        );
    }

    public function tags()
    {
        $tag = $this->input->xss_clean($this->uri->segment(3));
        $this->db_session->set_userdata('keywords', $tag);
        redirect('/search/');
    }
}