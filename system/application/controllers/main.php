<?php
class Main extends Controller
{
    function __construct()
    {
        parent::Controller();
        $this->benchmark->mark('code_start');
        if ($this->db_session->userdata('keywords')) {
            $this->db_session->unset_userdata('keywords');
        }
        $this->output->cache(60);
    }

    function index()
    {
        $this->load->model('category_mdl', 'category');
        $this->load->model('items_mdl', 'items');
        $this->load->model('attachment');

        $mainItem = $this->items->get_item(null, 'main');
        $mainItem = empty($mainItem) ? null : array_shift($mainItem);

        $about = $this->items->get_item(null, 'about');
        $about = empty($about) ? null : array_shift($about);

        $cat_ad = $this->category->get_category(null, null, 'Ad');
        $cat_id = null;
        if ($cat_ad && is_array($cat_ad)) {
            $cat_ad = $cat_ad[0];
            $cat_id = $cat_ad->category_id;
        }

        $productsAdd = $this->items->get_item(null, 'products', true, $cat_id, 2);
        if (empty($productsAdd) || count($productsAdd) == 1) {
            $countItems  = empty($productsAdd) ? 2 : 1;
            $productsAdd = array_merge(
                $productsAdd,
                $this->items->get_item(null, 'products', true, null, $countItems)
            );
        }

        foreach ($productsAdd as &$product) {
            $itemCategories = $this->items->get_item_category($product->item_id);
            if (! empty($itemCategories)) {
                if (is_array($itemCategories)) {
                    $itemCategories = array_shift($itemCategories);
                }
                $product->category = $itemCategories;
            }
        }
        $information = $this->items->get_item(null, 'information', true, null, 4);
        $partners    = $this->items->get_item(null, 'partners');

        $contacts    = get_contacts();

        $doors     = $this->category->get_category(null, null, 'Двери');
        $locks     = $this->category->get_category(null, null, 'Замки');
        $safes     = $this->category->get_category(null, null, 'Сейфы');
        $skd       = $this->category->get_category(null, null, 'Системы контроля доступа');
        $cylinders = $this->category->get_category(null, null, 'Цилиндры');
        $other     = $this->category->get_category(null, null, 'Комплектующие и аксессуары');

        $data = [
            'main'        => $mainItem,
            'products'    => $productsAdd,
            'information' => $information,
            'partners'    => $partners,
            'about'       => $about,
            'contacts'    => $contacts,
            'links_cat'   => [
                'doors'     => $doors,
                'locks'     => $locks,
                'safes'     => $safes,
                'skd'       => $skd,
                'cylinders' => $cylinders,
                'other'     => $other
            ]
        ];

        $data['meta_tags'] = build_meta_tags($mainItem);

        $this->load->view('_main', $data);
    }
}
