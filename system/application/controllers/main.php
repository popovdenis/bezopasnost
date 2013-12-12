<?php
class Main extends Controller
{

    function __construct()
    {
        parent::Controller();
        $this->benchmark->mark( 'code_start' );
        if ( $this->db_session->userdata( 'keywords' ) ) {
            $this->db_session->unset_userdata( 'keywords' );
        }
    }

    function index()
    {
        $this->load->model( 'category_mdl', 'category' );
        $this->load->model( 'items_mdl', 'items' );
        $this->load->model( 'attachment' );

        $about = $this->items->get_item( null, 'about' );
        if ( $about && is_array( $about ) ) {
            $about = $about[0];
        }

        $cat_ad = $this->category->get_category( null, null, 'Ad' );
        $cat_id = null;
        if ( $cat_ad && is_array( $cat_ad ) )
        {
            $cat_ad = $cat_ad[0];
            $cat_id = $cat_ad->category_id;
        }
        $productsAdd = $this->items->get_item( null, 'products', true, $cat_id, 2 );

        if( empty( $productsAdd ) || count( $productsAdd ) == 1 )
        {
            $countItems = empty( $productsAdd ) ? 2 : 1;
            $productsAdd = array_merge( $productsAdd, $this->items->get_item( null, 'products', true, null, $countItems ) );
        }

        foreach( $productsAdd as &$product )
        {
            $itemCategories = $this->items->get_item_category( $product->item_id );
            if( !empty( $itemCategories ) )
            {
                if( is_array( $itemCategories ) ) $itemCategories = array_shift( $itemCategories );
                $product->category = $itemCategories;
            }
        }

        $information = $this->items->get_item( null, 'information', true, null, 4 );
        $partners = $this->items->get_item( null, 'partners' );
        $contacts = get_contacts();

        $doors = $this->category->get_category( null, null, 'Двери' );
        $locks = $this->category->get_category( null, null, 'Замки' );
        $safes = $this->category->get_category( null, null, 'Сейфы' );
        $skd = $this->category->get_category( null, null, 'Системы контроля доступа' );
        $cylinders = $this->category->get_category( null, null, 'Цилиндры' );
        $other = $this->category->get_category( null, null, 'Комплектующие и аксессуары' );

        $config['meta_tags']['title'] = 'Bezopasnost.ua';

        $data = array();
        $data['products'] = $productsAdd;
        $data['information'] = $information;
        $data['partners'] = $partners;
        $data['about'] = $about;
        $data['contacts'] = $contacts;
        $data['links_cat'] = array( 'doors' => $doors, 'locks' => $locks, 'safes' => $safes, 'skd' => $skd, 'cylinders' => $cylinders, 'other' => $other );
        $data['meta_tags'] = build_meta_tags( null, $config['meta_tags'] );

        $this->load->view( '_main', $data );
    }
}

?>