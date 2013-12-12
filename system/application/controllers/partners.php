<?php
	class Partners extends Controller {
		
		function __construct() {
			parent::Controller();
			$this->benchmark->mark('code_start');
		}
	    
        function index()
        {
            $data = array();
            $this->load->model('items_mdl','items');
            $items = $this->items->get_item(null, 'partners');

            $config['meta_tags']['title'] = 'Продукция';

            $data['partners'] = $items;
            $data['meta_tags'] = build_meta_tags( null, $config['meta_tags'] );

            $this->load->view('_partners', $data);
        }
				
		function about($partner_id=null){
			$this->load->helper('url');
			
			if(!$partner_id) $partner_id = $this->uri->segment(3);			
			
			$this->load->model('items_mdl','items');
	    	$item = $this->items->get_item($partner_id);
	    	$items = $this->items->get_item(null, 'partners');
			
	    	if($item && is_array($item)) {
	    		$item = $item[0];
	    		
	    		$this->load->model('attachment');
				$title = $this->attachment->get_attach_item($item->item_id, 'product_title');
				if($title && is_array($title))$item->attach = $title[0];
				else $item->attach = null;
	    	}
	    	
			$data = array();
			$data['partners'] = $items;
			$data['partner'] = $item;
            $data['meta_tags'] = build_meta_tags( $item ) ;

			$this->load->view('_partner', $data);
		}
		
		function category(){
			$this->load->helper('url');
			$partner_id = $this->uri->segment(3);
			
			$this->load->model('category_mdl','category');
			$cat_partners = $this->category->get_category_partner($partner_id);
			if(!empty($cat_partners)) {
				if(is_array($cat_partners)) $cat_partners = $cat_partners[0];
				$this->about($cat_partners->item_id);
			}
		}
	}
?>