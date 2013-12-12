<?php
	/**
	 * get_tag_clouds
	 *
	 * get tag clouds by TagClouds library
	 *
	 * @author  Popov
	 * @access  public
	 * @param   array     $items 
	 * @return  string  $tag_clouds
	 */
	function get_tag_clouds() {		
		static $ci;
		if (!is_object($ci)) $ci = &get_instance();

		$ci->load->model('items_mdl','items');
		$items = $ci->items->get_item_search();	
		
		if(!$items) return null;
		
		$tags_array = array();
		
		foreach ($items as $item) {
			if(empty($item->item_marks)) continue;
			
			$marks = explode(",", $item->item_marks);
			foreach ($marks as $key=>$mark) {
				array_push($tags_array, $mark);
			}
		}
		if(empty($tags_array)) return null;
		
		$mycloud = new Tagclouds($tags_array);
		return $mycloud->get_cloud();
	}

?>