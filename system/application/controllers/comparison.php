<?php
	/**
	 * Class Comparison
	 *
	 * class_definition
	 *
	 * @author   Евген
	 * @access   public
	 * @package  Comparison.class.php
	 * @created  Sat Apr 17 20:12:03 EEST 2010
	 */
	class Comparison extends Controller 
	{
		/**
		 * Constructor of Comparison
		 *
		 * @access  public
		 */
		function Comparison() {
			parent::Controller();
		}
		
		function index(){
			$this->load->model('items_mdl','items');
			$this->load->model('attachment');
			
			$compares = $this->db_session->userdata('compare');
			$items = array();
			foreach ($compares as $key=>$item) {
				$item = $this->items->get_item($item);
				if($item && is_array($item)) $item = $item[0];
				$items[] = $item;
				
				$title = $this->attachment->get_attach_item($item->item_id, 'product_title');
				if($title && is_array($title))$item->attach = $title[0];
				else $item->attach = null;
			}
			$data['items'] = $items;
			$this->load->view('comparison', $data);
		}
	
		/**
		 * Destructor of Comparison 
		 *
		 * @access  public
		 */
		 function _Comparison() {
		 	
		 }
		
	}
?>