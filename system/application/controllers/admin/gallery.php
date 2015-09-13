<?php
/**
 * User: Denis
 * Date: 02.05.14
 * Time: 19:55
 */
class Gallery extends Controller
{
    public function index()
    {
        $this->load->model('gallery_mdl', 'gallery');

        $galleries = $this->gallery->get_gallery();
        $val = array();
        $val['galleries'] = $galleries;
        $val['item_type'] = 'gallery';

        $this->load->view('admin/gallery', $val);
    }
}