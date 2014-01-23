<?php
    class Information extends Controller
    {
        private $slug = 'information';
        private $category_title = 'Информация';
        private $per_page = 12;
        private $num_links = 1;
        private $cur_page = 1;
        private $uri_segment = 1;

        function __construct()
        {
            parent::Controller();
            $this->benchmark->mark('code_start');
            $this->load->helper('tagclouds');
            $this->output->cache(60);
        }

        function index()
        {
            $this->category();
        }

        function category()
        {
            $this->load->model('category_mdl', 'category');
            $this->load->helper('url');
            $this->load->helper('bk');
            $config['meta_tags']['title'] = 'Информация';
            $val       = array();
            $main      = $this->category->get_category(null, null, $this->category_title);
            $main_cats = $this->category->get_category(null, $main[0]->category_id, null, 'category_position');
            if ($main_cats && ! empty($main_cats)) {
                foreach ($main_cats as $category) {
                    $attachments = $this->category->get_category_attachment(
                                                  $category->category_id,
                                                      null,
                                                      'category_title'
                    );
                    if ($attachments && is_array($attachments)) {
                        $attachments = $attachments[0];
                    }
                    $category->attach = $attachments;
                }
            }
            $val['main']      = $main;
            $val['cats']      = $main_cats;
            $val['main_slug'] = $this->slug;
            $val['tagclouds'] = get_tag_clouds();
            $val['meta_tags'] = build_meta_tags(null, $config['meta_tags']);
            $category_id = $this->uri->segment(3);
            if (! empty($category_id)) {
                $item_id = $this->uri->segment(5);
                if (! empty($item_id)) {
                    $this->about();
                } else {
                    $current_cat = $this->category->get_category($category_id);
                    if ($current_cat && is_array($current_cat)) {
                        $current_cat = $current_cat[0];
                    }
                    $items_block = $this->get_items_block($category_id);
                    $head_links  = $this->get_head_links($category_id);
                    $val['paginate_args'] = array(
                        'total_rows'  => $items_block['items_count'],
                        'per_page'    => $this->per_page,
                        'num_links'   => $this->num_links,
                        'cur_page'    => $this->cur_page,
                        'uri_segment' => $this->uri_segment,
                        'base_url'    => base_url() . 'information/page/'
                    );
                    $val['items_block']   = $items_block['items_block'];
                    $val['current_cat']   = $current_cat;
                    $val['head_links']    = $head_links;
                    if ($current_cat->category_title == 'Прайсы') {
                        $this->load->view('_information_price', $val);
                    } else {
                        $this->load->view('_information_subcat', $val);
                    }
                }
            } else {
                $this->load->view('_information_subcat_all', $val);
            }
        }

        function about()
        {
            $this->load->model('items_mdl', 'items');
            $this->load->model('category_mdl', 'category');
            $this->load->helper('url');
            //			$this->load->library('gallery');
            $category_id = $this->uri->segment(3);
            $item_id     = $this->uri->segment(5);
            $item = $this->items->get_item($item_id, 'information');
            if ($item && is_array($item)) {
                $item = $item[0];
            }
            $item->item_content = html_entity_decode($item->item_content, ENT_QUOTES, 'UTF-8');
            $item->item_content = str_replace("quot;", '"', $item->item_content);
            $item->item_content = str_replace("nbsp;", '', $item->item_content);
            $item->item_content = str_replace("amp;", '&', $item->item_content);
            $main      = $this->category->get_category(null, null, $this->category_title);
            $main_cats = $this->category->get_category(null, $main[0]->category_id, null, 'category_position');
            if ($main_cats && ! empty($main_cats)) {
                foreach ($main_cats as $category) {
                    $attachments = $this->category->get_category_attachment(
                                                  $category->category_id,
                                                      null,
                                                      'category_title'
                    );
                    if ($attachments && is_array($attachments)) {
                        $attachments = $attachments[0];
                    }
                    $category->attach = $attachments;
                }
            }
            $current_cat = $this->category->get_category($category_id);
            if ($current_cat && is_array($current_cat)) {
                $current_cat = $current_cat[0];
            }
            $head_links = $this->get_head_links($current_cat->category_id);
            $data                = array();
            $data['head_links']  = $head_links;
            $data['current_cat'] = $current_cat;
            $data['main']        = $main;
            $data['cats']        = $main_cats;
            $data['main_slug']   = $this->slug;
            $data['item']        = $item;
            $data['tagclouds']   = get_tag_clouds();
            $this->load->model('gallery_mdl', 'gallery');
            $gallery = $this->gallery->get_item_gallery(null, $item->item_id);
            $gallery = $this->gallery->get_item_gallery(null, $item->item_id);
            if (! empty($gallery)) {
                foreach ($gallery as &$attach) {
                    $attach->attach_is_image = true;
                    if (empty($attach->attach_preview_path)) {
                        $attach->attach_is_image = false;
                        // check extention
                        // excel
                        if (in_array($attach->attach_ext, array('.csv', '.xls', '.xlsx',))) {
                            $attach->attach_preview_path = 'images/icons/excel128.png';
                        } // doc
                        elseif (in_array($attach->attach_ext, array('.word', '.docx', '.doc'))) {
                            $attach->attach_preview_path = 'images/icons/doc.png';
                        } // pdf
                        elseif (in_array($attach->attach_ext, array('.pdf'))) {
                            $attach->attach_preview_path = 'images/icons/pdf128.png';
                        } // zip
                        elseif (in_array($attach->attach_ext, array('.zip'))) {
                            $attach->attach_preview_path = 'images/icons/zip.png';
                        } // doc
                        elseif (in_array($attach->attach_ext, array('.doc', '.docx'))) {
                            $attach->attach_preview_path = 'images/icons/doc128.png';
                        } // no image
                        else {
                            $attach->attach_preview_path = 'images/icons/no-image.png';
                        }
                    }
                }
            }
            $data['gallery'] = $gallery;
            //			$data['gallery'] = $this->gallery->get_gallery($item->item_id);
            $data['gallery_price'] = $this->gallery->get_gallery($item->item_id, false, 'gallery_price');
            $data['meta_tags']     = build_meta_tags($item);
            $this->load->view('_information_single', $data);
        }

        function get_items_block($category_id = null)
        {
            $this->load->model('items_mdl', 'items');
            $this->load->model('attachment');
            $items       = $this->items->get_item(
                                       null,
                                           $this->slug,
                                           true,
                                           $category_id,
                                           $this->per_page,
                                           $this->cur_page,
                                           true
            );
            $items_count = $items['count'];
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
            $category = $this->category->get_category($category_id);
            if ($category && is_array($category)) {
                $category = $category[0];
            }
            $data                = array();
            $data['items']       = $items;
            $data['category_id'] = $category_id;
            $data['category']    = $category;
            if ($category->category_title == 'Прайсы') {
                $items_str = $this->load->view(
                                        'all_items_price',
                                            $data,
                                            true
                );
            } else {
                $items_str = $this->load->view('_all_items', $data, true);
            }
            $info = array('items_block' => $items_str, 'items_count' => $items_count);
            return $info;
        }

        function get_map_tree($partner_id = null)
        {
            $cats       = array();
            $categories = null;
            $this->load->model('category_mdl', 'category');
            if (! $partner_id) {
                $main       = $this->category->get_category(null, null, $this->category_title);
                $categories = $this->category->get_category(null, $main[0]->category_id);
            } else {
                $categories = $this->category->get_category_partner(null, $partner_id);
            }
            if ($categories) {
                foreach ($categories as $index => $category) {
                    $subcategories   = $this->category->get_category(null, $category->category_id);
                    $category->items = count($this->category->get_category_item($category->category_id));
                    foreach ($subcategories as $subcategory) {
                        $sub2cats             = $this->category->get_category(null, $subcategory->category_id);
                        $subcategory->items   = count($this->category->get_category_item($subcategory->category_id));
                        $subcategory->subcats = $sub2cats;
                        foreach ($sub2cats as $sub2cat) {
                            $sub2cat->items = count($this->category->get_category_item($sub2cat->category_id));
                        }
                    }
                    $cats[$index]['cat']    = $category;
                    $cats[$index]['subcat'] = $subcategories;
                }
            }
            return $cats;
        }

        function get_head_links($category_id, $product_id = null)
        {
            $main = $this->category->get_category(null, null, $this->category_title);
            if ($main && is_array($main)) {
                $main = $main[0];
            }
            $this->load->helper('bk');
            $categories = get_categories_tree_reverse($category_id, array(), - 1);
            $categories = array_reverse($categories);
            $links_str  = '<div class="pathbar">';
            $end        = end($categories);
            $link = '';
            foreach ($categories as $category) {
                if ($category->category_title == 'root') {
                    continue;
                }
                if ($category->category_id == $main->category_id) {
                    $link = base_url() . $this->slug . '/';
                } elseif ($category->category_parent == $main->category_id) {
                    $link = base_url() . $this->slug . '/category/' . $category->category_id;
                } else {
                    $link = base_url() . $this->slug . '/subcat/' . $category->category_id;
                }
                if ($end->category_id != $category->category_id) {
                    $links_str .= '<a href="' . $link . '">' . $category->category_title . '</a>';
                    $links_str .= '<img src="' . base_url() . 'images/path_separator.png">';
                } elseif (! $product_id) {
                    $links_str .= $category->category_title;
                }
                else {
                    $this->load->model('items_mdl', 'items');
                    $product = $this->items->get_item($product_id);
                    if ($product && is_array($product)) {
                        $product = $product[0];
                    }
                    $links_str .= '<a href="' . $link . '">' . $category->category_title . '</a>';
                    $links_str .= '<img src="' . base_url() . 'images/path_separator.png">';
                    $links_str .= $product->item_title;
                }
            }
            $links_str .= '</div>';
            return $links_str;
        }

        function get_item_gallery()
        {
            $val['gallery']       = $this->gallery->get_gallery($item->item_id);
            $val['gallery_price'] = $this->gallery->get_gallery($item->item_id, false, 'gallery_price');
        }

        function download()
        {
            $this->load->helper('download');
            $item_id     = $this->uri->segment(3);
            $category_id = $this->uri->segment(5);
            $item_id     = intval($item_id);
            $category_id = intval($category_id);
            if ($item_id) {
                $this->load->model('attachment');
                $attaches = $this->attachment->get_attach_item($item_id, 'gallery_price');
                if (! empty($attaches) && is_array($attaches)) {
                    $attaches = $attaches[0];
                }
                $data = file_get_contents(base_url() . $attaches->attach_path);
                force_download($attaches->attach_path, $data);
            }
            if ($category_id) {
                redirect('information/category/' . $category_id);
            } else {
                redirect('information');
            }
        }
    }

?>
