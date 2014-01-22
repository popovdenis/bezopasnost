<?php
class About extends Controller {

    function __construct() {
        parent::Controller();
        $this->benchmark->mark('code_start');
        $this->output->cache(60);
    }

    function index() {
        $this->load->model('attachment');
        $this->load->model('items_mdl','items');
        $item = $this->items->get_item(null, 'about');
        $this->load->model('gallery_mdl','gallery');

        $data = array();
        $item_id = null;
        if($item && is_array($item)) {
            $item = $item[0];
            $item_id = $item->item_id;
        }
        $item->item_content = html_entity_decode($item->item_content, ENT_QUOTES, 'UTF-8');
        $item->item_content = str_replace("quot;", '"', $item->item_content);
        $item->item_content = str_replace("nbsp;", '', $item->item_content);

        $data['item'] = $item;
        $data['contacts'] = get_contacts();
        $data['gallery'] = $this->gallery->get_item_gallery(null, $item_id);
        $data['meta_tags'] = build_meta_tags( $item );

        $this->load->view('_about', $data);
    }
}
?>
