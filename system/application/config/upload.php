<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

$config['upload_path'] = dirname(BASEPATH).'/files/';
$config['allowed_types'] = 'flv|avi|jpeg|jpg|gif|bmp|png|FLV|AVI|JPEG|JPG|GIF|BMP|PNG|PDF|xlsx|xls|pdf|zip|rar|doc|docx';
$config['max_size'] = 50000000;
$config['remove_spaces'] = TRUE;
$config['overwrite'] = false;
$config['dimensions'] = array(
	'preview_main' => array('width' => 180, 'height' => 250),
	'preview' => array('width' => 130, 'height' => 250),
	'single' => array('width' => 200, 'height' => 401),
	'item_gallery' => array('width' => 170, 'height' => 170),
	'other' => array('width' => 112, 'height' => 117)
);

/* End of file */ 