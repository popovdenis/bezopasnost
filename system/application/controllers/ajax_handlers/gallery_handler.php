<?php
	/**
	 * Class Gallery_handler
	 *
	 * handler of gallery
	 *
	 * @author   Home
	 * @access   public
	 * @package  Gallery_handler.class.php
	 * @created  Sun Nov 29 12:01:25 EET 2009
	 */
	class Gallery_handler extends Controller {
		
		/**
		 * Constructor of Gallery_handler
		 *
		 * @access  public
		 */
		function Gallery_handler() {
			parent::Controller();	
		}
		
		function ajax_actions(){
			$this->load->model('attachment');
			$this->load->library('gallery');
			$this->pollObj = new Gallery();
			
			$action = $this->input->post('action');
			
			$data = '';
			switch ($action) {
				case "delete_img":
					$attach_id = $this->input->post('attach_id');
					$item_id = $this->input->post('item_id');
					$from_gallery = $this->input->post('from_gallery');
					
					if(!empty($from_gallery) && $from_gallery != 'undefined' && $from_gallery == false) {
						if(!empty($item_id) && $item_id != 'undefined'){
							$data = $this->attachment->delete_attach_item($attach_id, $item_id);
						}
					} else {					
						$data = $this->attachment->delete_attach($attach_id);
					}
					
					break;
			}
			$this->output->set_output($data);
		}
		
		/**
		 * Destructor of Gallery_handler 
		 *
		 * @access  public
		 */
		function _Gallery_handler() {}		
	}
?>