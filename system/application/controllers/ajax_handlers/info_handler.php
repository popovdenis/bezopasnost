<?php
	/**
	 * Class Admin_handler
	 *
	 * admin handler
	 *
	 * @author   Popov
	 * @access   public
	 * @package  Comment.class.php
	 * @created  Fri Sep 25 12:41:38 EEST 2009
	 */
	class Info_handler extends Controller
	{
		private $per_page = 12;
		private $num_links = 1;
		private $cur_page = 1;
		private $uri_segment = 1;

		/**
		 * Constructor of Comment
		 *
		 * @access  public
		 */
		function Info_handler() {
			parent::Controller();
		}

		function ajax_actions(){
			$action = $this->input->post('action');

			$data = '';
			switch ($action) {
				case "paginate_items":
					$cur_page = $this->input->post('page');

					$items_block = $this->get_items_block($cur_page);

					$data = array();
			    	$page_container = array(
						'total_rows' => count($items_block['items_all']),
						'per_page' => $this->per_page,
						'num_links' => $this->num_links,
						'cur_page' => $cur_page,
						'uri_segment' => $this->uri_segment,
						'base_url' => base_url() . index_page() .'information/page/'
					);
					$item_main = $items_block['item_main'];
					$page_container = paginate_ajax($page_container);

					$data = (Object)array('items_block' => $item_main, 'page_container' => $page_container);
					$data = json_encode($data);

					break;
			}
			$this->output->set_output($data);
		}

		function get_items_block($cur_page = 1, $item_mode = true){
			$this->load->model('items_mdl','items');

			$per_page=3;
			$page = $cur_page;

			$items = $this->items->get_item(null, 'information', $item_mode, null, $per_page, $page);
			$items_all = $this->items->get_item(null, 'information');
			$item_category = $this->items->get_item_category($items[0]->item_id);

			$data = array();
			$data['items'] = $items;
			if($item_category)
				$data['category'] = $item_category[0];
			$items_str = $this->load->view('_all_items', $data, true);

			$info = array('item_main' => $items_str, 'items_all' => $items_all);
			return $info;
		}
	}
?>
