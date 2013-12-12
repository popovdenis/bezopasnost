<?php
	class Map extends Controller {
		
		function __construct() {
			parent::Controller();
			$this->benchmark->mark('code_start');
		}
	    
	    function index() {
	    	$this->load->model('category_mdl','category');
	    	$categories = get_categories_tree(0, array(), -1);
	    	
	    	$main_cats = array();
	    	foreach ($categories as $category) {
	    		if($category->level != 0) continue;
	    		array_push($main_cats, $category);
	    	}
	    	
	    	$main_cats_new = array();
	    	$cats_arr = array('about' => 'О Компании', 'products' => 'Продукция', 'information' => 'Информация', 'partners' => 'Партнеры');
	    	foreach ($cats_arr as $link=>$cat) {
	    		foreach ($main_cats as $category) {
	    			if($cat == $category->category_title) {
	    				$category->link = $link;
	    				$main_cats_new[] = $category;
	    				break;
	    			}
	    		}	
	    	}
	    	
	    	$cats = array();
	    	foreach ($main_cats_new as $i=>$mcat) {
	    		$categories = $this->category->get_category(null, $mcat->category_id);
	    		$cats[$i]['main'] = $mcat;
	    		$cats[$i]['submain'] = array();
	    		if($categories) {
					foreach ($categories as $index=>$category) {					
						$subcategories = $this->category->get_category(null, $category->category_id);
						
						$category->items = count($this->category->get_category_item($category->category_id));
						foreach ($subcategories as $subcategory){
							$subcategory->items = count($this->category->get_category_item($subcategory->category_id));
						}
						
						$cats[$i]['submain'][$index]['cat'] = $category;
						$cats[$i]['submain'][$index]['subcat'] = $subcategories;
					}
				}
	    	}
	    	
	    	$data = array();
	    	$data['categories'] = $cats;

            $config['meta_tags']['title'] = 'Карта сайта';
            $data['meta_tags'] = build_meta_tags( null, $config['meta_tags'] );
			
	    	$this->load->view('_map', $data);
		}
		
		function get_map_tree($partner_id = null){
			$cats = array();
			$categories = null;
			
			$this->load->model('category_mdl','category');
			
			if(!$partner_id) {			
				$main = $this->category->get_category(null, 0, "Продукция");
				$categories = $this->category->get_category(null, $main[0]->category_id);
				
			} else {
				$categories = $this->category->get_category_partner(null, $partner_id);
			}
			if($categories) {
				foreach ($categories as $index=>$category) {					
					$subcategories = $this->category->get_category(null, $category->category_id);
					
					$category->items = count($this->category->get_category_item($category->category_id));
					foreach ($subcategories as $subcategory){
						$subcategory->items = count($this->category->get_category_item($subcategory->category_id));
					}
					
					$cats[$index]['cat'] = $category;
					$cats[$index]['subcat'] = $subcategories;
				}
			}
			return $cats;
		}
	}
?>