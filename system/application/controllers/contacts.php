<?php
/**
 *
 */
class Contacts extends Controller
{
    /**
     *
     */
    function __construct()
    {
        parent::Controller( );
        $this->benchmark->mark( 'code_start' );
    }

    /**
     */
    function index()
    {
        $config['meta_tags']['title'] = 'Контакты';
        $data = array();
        $data['contacts'] = get_contacts();
        $data['meta_tags'] = build_meta_tags( null, $config['meta_tags'] );

        $this->load->view( '_contacts', $data );
    }
}
?>