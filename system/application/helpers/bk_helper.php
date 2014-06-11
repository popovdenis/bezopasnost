<?php
    /**
     * @param $parent_id
     * @param $list
     * @param $level
     *
     * @return array
     */
    function get_categories_tree($parent_id, $list, $level)
    {
        static $ci;
        if (!is_object($ci)) {
            $ci = & get_instance();
            $ci->load->model('category_mdl', 'category');
        }
        $categories = $ci->category->get_category(null, $parent_id, null, "category_position");
        if (!empty($categories)) {
            $level++;
        }
        foreach ($categories as $cat) {
            $cat->level = $level;
            $list[]     = $cat;
            $list       = get_categories_tree($cat->category_id, $list, $level);
        }
        return $list;
    }

    /**
     * @param $categoryParent
     * @param $item_id
     *
     * @return object
     */
    function getCategoryChildRecursive($categoryParent, $item_id)
    {
        static $ci;
        if (!is_object($ci)) {
            $ci = & get_instance();
            $ci->load->model('category_mdl', 'category');
        }
        $category = $ci->category->get_item_category($categoryParent->category_id, $item_id, null, false);
        if (!empty($category)) {
            $category   = array_shift($category);
            $categories = $ci->category->get_category(null, $category->category_id);
            {
                foreach ($categories as $catChild) {
                    getCategoryChildRecursive($catChild, $item_id);
                }
            }
        } else {
            return $categoryParent;
        }
    }

    /**
     * @param      $itemId
     * @param bool $asLink
     */
    function getItemUrl($itemId, $asLink = false)
    {
        static $ci;
        if (!is_object($ci)) {
            $ci = & get_instance();
        }
        // get category item
        // check category's parent_id: if not 0 - get category by parent_id, else - build link
        $ci->load->model('category_mdl', 'category');
        $ci->load->model('items_mdl', 'items');
        $itemCategories = $ci->items->get_item_category($itemId);
        if (!empty($itemCategories)) {
            // collect all categories which have paretn_id = 0
            $childCategories = array();
            foreach ($itemCategories as $category) {
                $childCategories[] = getCategoryChildRecursive($category, $itemId);
            }
            $childCategories = array_unique($childCategories);
            if (!empty($childCategories)) {
                $categoriesLinks = array();
                foreach ($childCategories as $child) {
                    $categoriesLinks[] = array_reverse(
                        get_categories_tree_reverse($child->category_id, array(), -1)
                    );
                }
                if ($asLink) {
                    // build links
                }
            }
        }
    }

    /**
     * @param null  $item
     * @param array $config
     *
     * @return string
     */
    function build_meta_tags($item = null, $config = array())
    {
        static $ci;
        if (!is_object($ci)) {
            $ci = & get_instance();
        }
        $metaTagsConfig = $ci->config->item('meta_tags');
        $metaTagsConfig = (!empty($config)) ? array_merge($metaTagsConfig, $config) : $metaTagsConfig;
        $ci->meta_tags->initialize($metaTagsConfig);
        if ($item) {
            $itemTitle = !empty($item->item_seo_title) ? $item->item_seo_title : $item->item_title;
            $ci->meta_tags->add_title($itemTitle);
            if (!empty($item->item_seo_keywords)) {
                $ci->meta_tags->add_keyword($item->item_seo_keywords);
            }
            if (!empty($item->item_seo_description)) {
                $ci->meta_tags->add_description($item->item_seo_description);
            }
        }
        return $ci->meta_tags->generate_meta_tags();
    }

    /**
     * @param $parent_id
     * @param $list
     * @param $level
     *
     * @return array
     */
    function get_categories_tree_reverse($parent_id, $list, $level)
    {
        static $ci;
        if (!is_object($ci)) {
            $ci = & get_instance();
        }
        $categories = $ci->category->get_category($parent_id);
        if (!empty($categories)) {
            $categories = $categories[0];
            $list[]     = $categories;
            $list       = get_categories_tree_reverse($categories->category_parent, $list, $level);
        }
        return $list;
    }

    /**
     *
     */
    function get_information_top()
    {
        static $ci;
        if (!is_object($ci)) {
            $ci = & get_instance();
        }
        $ci->load->model('items_mdl', 'items');
        $items              = $ci->items->get_item(null, 'information', true, null, 3, 1);
        $data               = array();
        $data['items_info'] = $items;
        return $ci->load->view('_information_block', $data, true);
    }

    /**
     *
     */
    function get_partners_random()
    {
        static $ci;
        if (!is_object($ci)) {
            $ci = & get_instance();
        }
        $ci->load->model('items_mdl', 'items');
        $partners         = $ci->items->get_item(null, 'partners');
        $data             = array();
        $data['partners'] = $partners;
        return $ci->load->view('_partners_block', $data, true);
    }

    /**
     * @return mixed
     */
    function get_contacts()
    {
        static $ci;
        if (!is_object($ci)) {
            $ci = & get_instance();
        }
        $ci->load->model('contacts_mdl', 'contacts');
        $contacts = $ci->contacts->get_contacts();
        if ($contacts && !empty($contacts)) {
            $phone1 = null;
            $phone2 = null;
            if (!empty($contacts->contact_phones)) {
                $contacts->contact_phones = json_decode($contacts->contact_phones, true);
                if (!empty($contacts->contact_phones)) {
                    foreach ($contacts->contact_phones as $index => &$phone) {
                        $kode       = strpos($phone, ")") + 1;
                        $phone_kode = substr($phone, 0, $kode);
                        $phone      = substr($phone, $kode);
                        $phone      = array(
                            'contact_kode'  => $phone_kode,
                            'contact_phone' => $phone
                        );
                    }
                }
            }
            if (!empty($contacts->contact_faxes)) {
                $contacts->contact_faxes = json_decode($contacts->contact_faxes, true);
            }
            if (!empty($contacts->contact_emails)) {
                $contacts->contact_emails = json_decode($contacts->contact_emails, true);
            }
        }
        return $contacts;
    }

    function image_watermark($main_img, $watermark_img, $attach_ext, $padding = 3, $opacity = 30)
    {
        $path = dirname(BASEPATH);
        if (!empty($attach_ext)) {
            $attach_ext = strtolower($attach_ext);
        }
        $watermark = imagecreatefrompng($watermark_img); // create watermark
        if ($attach_ext == ".jpg" || $attach_ext == ".jpeg") {
            $image = imagecreatefromjpeg(
                $main_img
            );
        } // create main graphic
        elseif ($attach_ext == ".png") {
            $image = imagecreatefrompng($main_img);
        } // create main graphic
        elseif ($attach_ext == ".gif") {
            $image = imagecreatefromgif($main_img);
        } // create main graphic
        if (!$image || !$watermark) {
            die("Error: main image or watermark could not be loaded!");
        }
        $watermark_size   = getimagesize($watermark_img);
        $watermark_width  = $watermark_size[0];
        $watermark_height = $watermark_size[1];
        $image_size       = getimagesize($main_img);
        $dest_x           = $image_size[0] - $watermark_width - $padding;
        $dest_y           = $image_size[1] - $watermark_height - $padding;
        imagecopymerge($image, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, $opacity);
        imagejpeg($image, $main_img);
        imagedestroy($image);
        imagedestroy($watermark);
    }

    /**
     * create watermark
     *
     * @param string $main_img    sourse image
     * @param string $text        text for watermark
     * @param string $font        path to font
     * @param int    $r           RGB color paramether, R
     * @param int    $g           RGB color paramether, G
     * @param int    $b           RGB color paramether, B
     * @param int    $alpha_level level of transparency
     */
    function create_watermark($main_img, $text, $font, $r = 128, $g = 128, $b = 128, $alpha_level = 100)
    {
        if (file_exists($main_img)) {
            $main_img_obj = imagecreatefromjpeg($main_img);
            $width        = imagesx($main_img_obj);
            $height       = imagesy($main_img_obj);
            $angle        = -rad2deg(atan2((-$height), ($width)));
            $text         = " " . $text . " ";
            $c            = imagecolorallocatealpha($main_img_obj, $r, $g, $b, $alpha_level);
            $size         = (($width + $height) / 2) * 2 / strlen($text);
            $box          = imagettfbbox($size, $angle, $font, $text);
            $x            = $width / 2 - abs($box[4] - $box[0]) / 2;
            $y            = $height / 2 + abs($box[5] - $box[1]) / 2;
            imagettftext($main_img_obj, $size, $angle, $x, $y, $c, $font, $text);
            imagejpeg($main_img_obj, $main_img);
            imagedestroy($main_img_obj);
        }
    }

    /**
     * @return mixed
     */
    function get_menu_categories()
    {
        static $ci;
        if (!is_object($ci)) {
            $ci = & get_instance();
            $ci->load->model('category_mdl', 'category');
        }
        $main = $ci->category->get_category(null, null, "root");
        $main_cats = [];
        if ($main) {
            if (is_array($main)) {
                $main = $main[0];
            }
            $main_cats = $ci->category->get_category(null, $main->category_id, null, "category_position");
            foreach ($main_cats as &$mcat) {
                $subcat       = $ci->category->get_category(null, $mcat->category_id, null, "category_position");
                $mcat->subcat = empty($subcat) ? null : $subcat;
            }
        }
        return $main_cats;
    }

?>
