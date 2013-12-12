<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

	/**
	 * Class JUploader
	 *
	 * uploader libraty
	 *
	 * @author   Popov
	 * @access   public
	 * @package  JUploader.class.php
	 * @created  Sun Sep 05 14:47:56 EEST 2010
	 */
	class JUploader 
	{
		var $upload_url = '';
		var $input_filename = 'filename';
		
		/**
		 * Constructor of JUploader
		 *
		 * @access  public
		 */
		function __constructor() 
		{
			
		}
		
		function init($config = array()){
			$defaults = array(
							'upload_url' => '',
							'input_filename' => 'filename'
						);	
		
			foreach ($defaults as $key => $val)
			{
				if (isset($config[$key]))
				{
					$method = 'set_'.$key;
					if (method_exists($this, $method))
					{
						$this->$method($config[$key]);
					}
					else
					{
						$this->$key = $config[$key];
					}
				}
				else
				{
					$this->$key = $val;
				}
			}
		}
		
		function upload_attach($fieldname) {
			if(!$fieldname) return false;
			
			static $ci;
			if (!is_object($ci)) $ci = &get_instance();
			
			$ci->load->library('upload');
			
			$config = $ci->load->config('upload');
			
			if(!is_dir($config['upload_path'])){
				mkdir($config['upload_path'], 0755);
			}
			
			if (isset($ci->load->_ci_classes['upload']) && ($ci->load->_ci_classes['upload'] == 'upload')) {
				$ci->upload->initialize($config);
			} else {
				$ci->load->library('upload', $config);
			}			
			
			if ( $ci->upload->do_upload($fieldname)) {
				return $ci->upload->data();				
				
			} else {
				set_error($ci->upload->display_errors());
				log_message('error', var_export($ci->upload->display_errors(), true));
			}
			return false;
		}
	
		function get_upload_form(){
			static $ci;
			if (!is_object($ci)) $ci = &get_instance();
			
			$val = array();			
			$val['upload_url'] = $this->upload_url;
			$val['input_filename'] = $this->input_filename;
			
			return $ci->load->view('uploader/upload_form', $val, true);
		}
		
		/**
		 * Destructor of JUploader 
		 *
		 * @access  public
		 */
		function __destructor()
		 {
		 	
		 }
		
	}

?>