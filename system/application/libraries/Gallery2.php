<?php
	if (!defined('BASEPATH')) exit('Нет доступа к скрипту');
	
	/**
	 * Class Gallery
	 *
	 * gallery library
	 *
	 * @author   Home
	 * @access   public
	 * @package  Gallery.class.php
	 * @created  Sun Nov 29 11:58:43 EET 2009
	 */
	class Gallery extends Controller {
		
		/**
		 * Constructor of Gallery
		 *
		 * @access  public
		 */
		function Gallery() {
			parent::Controller();
		}
		
		function get_gallery($item_id = null, $is_admin = false, $attach_type = 'item_gallery'){
			static $ci;
			if (!is_object($ci)) $ci = &get_instance();
			
			// выбрать все приложения, которые имеют тип item_gallery в таблице atachment_item
			$ci->load->model('attachment');
			$attachments = $ci->attachment->get_attach_item($item_id, $attach_type);
			$val = array();
			
			$val['gallery'] = $attachments;
			$val['is_admin'] = $is_admin;
			$val['attach_type'] = $attach_type;
			if($is_admin) return $ci->load->view('admin/_gallery', $val, true);
			return $ci->load->view('_gallery', $val, true);
		}
	
		/**
		 * Destructor of Gallery 
		 *
		 * @access  public
		 */
		function _Gallery() {
		 	
		}
		
	}
?>