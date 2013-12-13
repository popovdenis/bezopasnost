<?php
// Amperzand.ttf
// ERASBD.ttf
define( "WATERMANR_FONT_PATH", BASEPATH . "fonts/Amperzand.ttf" );
define( "WATERMANR_R_CHANNEL", 240 );
define( "WATERMANR_G_CHANNEL", 248 );
define( "WATERMANR_B_CHANNEL", 255 );
define( "WATERMANR_ALPHA_LEVEL", 110 );
//	128,128,128, 100

class Home extends Controller
{

    function __construct()
    {
        parent::Controller();
        $this->load->model( 'category_mdl', 'category' );
    }

    function index()
    {
        $user_id = $this->db_session->userdata( 'user_id' );
        $user_role = $this->db_session->userdata( 'user_role' );

        if ( empty( $user_id ) || empty( $user_role ) )
        {
            $this->load->view( 'admin/_login', null );
        }
        else
        {
            $data = array();
            $this->load->view( 'admin/_home', $data );
        }
    }

    function upload()
    {

        $attach_id = null;
        $config = $this->load->config( 'upload' );
        $dimensions = $config['dimensions'];

        if ( !empty( $_FILES ) )
        {

            $upload_type = $this->input->post( 'upload_type' );
            $file_type = $this->input->post( 'file_type' );
            $this->load->model( 'attachment', 'attachment' );

            $upload_data = $this->upload_attach( 'userfile' );

            if ( file_exists( $upload_data['full_path'] ) ) {
                rename( $upload_data['full_path'], "files/" . date( "YmdHis" ) . $upload_data['file_ext'] );
            }

            $upload_data['full_path'] = str_replace( $upload_data['file_name'], date( "YmdHis" ) . $upload_data['file_ext'], $upload_data['full_path'] );

            $upload_data['file_name'] = date( "YmdHis" ) . $upload_data['file_ext'];
            $upload_data['raw_name'] = date( "YmdHis" );

            $item_data = array( 'attach_name' => $upload_data['raw_name'],
                                'attach_path' => strstr( $upload_data['full_path'], "files/" ),
//                                'attach_preview_path' => "files/" . $upload_data['raw_name'] . "_thumb" . $upload_data['file_ext'],
                                'attach_type' => $upload_data['file_type'], 'attach_img_width' => 0,
                                'attach_img_height' => 0, 'attach_ext' => $upload_data['file_ext'],
                                'attach_size' => $upload_data['file_size'], 'attach_date' => date( "Y-m-d H:i:s" ),
                                'attach_is_image' => 0 );
            $data = array();
            if ( $upload_type != "contacts" )
            {
                $attach_id = $this->attachment->set( $item_data );

            }
            else
            {
                $field_name = $this->input->post( 'field_name' );

                $this->load->model( 'contacts_mdl', 'contacts' );
                $contacts = $this->contacts->get_contacts();

                $contacts_data = null;

                if ( $field_name == "contact_photo1" || $field_name == "contact_photo2" )
                {
                    if ( !empty( $contacts->contact_photos ) )
                    {
                        $contacts->contact_photos = json_decode( $contacts->contact_photos, true );
                    }
                    else
                    {
                        $contacts->contact_photos = array();
                    }
                    $contacts->contact_photos[$field_name] = $item_data['attach_path'];
                    $contacts->contact_photos = json_encode( $contacts->contact_photos );

                    $contacts_data = array( "contact_photos" => $contacts->contact_photos );
                }

                $this->contacts->update_contacts( $contacts_data );
            }
            $data["file_path"] = $item_data['attach_path'];
            $data["attach_id"] = $attach_id;

            $data = (Object)$data;
            $data = json_encode( $data );

            echo $data;
            exit;

        }
        elseif ( !empty( $_POST ) )
        {
            $attach_id = $this->input->post( 'attach_id' );
            $item_id = $this->input->post( 'item_id' );
            $gallery_id = $this->input->post( 'gallery_id' );
            $upload_type = $this->input->post( 'upload_type' );
            $file_type = $this->input->post( 'file_type' );
            $title = $this->input->post( 'new_gal_title' );
            $desc = $this->input->post( 'new_gal_desc' );

            $this->load->model( 'attachment', 'attachment' );

            $item_data['attach_title'] = $title;
            $item_data['attach_desc'] = $desc;

            if ( $file_type == 'price' )
            {
                $this->attachment->update_attachment( $attach_id, $item_data );

                $upload_type = "gallery_price";
                $this->attachment->update_attachment_item( $attach_id, $item_id, $upload_type, false );
                $attachment = $this->attachment->get_attach_item( $item_id, "gallery_price" );
            }
            else
            {
                $attachment = $this->attachment->get_attach( $attach_id );
                if ( is_array( $attachment ) ) {
                    $attachment = $attachment[0];
                }

                $file_path = $attachment->attach_path;
                $file_full_path = $attachment->attach_path;

                if ( $attachment )
                {
                    $imageTypes = array( '.bmp', '.gif', '.jpeg','.jpg','.jpe','.png' );
                    if( in_array( strtolower( $attachment->attach_ext ), $imageTypes ) )
                    {
                        $this->load->helper( 'bk' );
                        $this->load->library( 'image_lib' );
                        $config['image_library'] = 'gd2';
                        $config['source_image'] = $attachment->attach_path;
                        $config['create_thumb'] = TRUE;
                        $config['thumb_marker'] = '_thumb';
                        $config['maintain_ratio'] = TRUE;

                        $this->image_lib->initialize( $config );
                        $this->image_lib->resize();

                        $image_path = dirname( BASEPATH ) . "/files/";
                        $watermark_path = dirname( BASEPATH ) . "/images/watermark/watermark8.png";

                        // title to all cinemas page
                        $config['source_image'] = $attachment->attach_path;

                        if ( $upload_type == 'product_title' || $upload_type == 'category_title' )
                        {
                            $config['width'] = $dimensions['preview']['width'];
                            $config['height'] = $dimensions['preview']['height'];
                        }
                        elseif ( $upload_type == 'item_gallery' )
                        {
                            $config['width'] = $dimensions['item_gallery']['width'];
                            $config['height'] = $dimensions['item_gallery']['height'];
                        }
                        else
                        {
                            $config['width'] = $dimensions['other']['width'];
                            $config['height'] = $dimensions['other']['height'];
                        }
                        $this->image_lib->initialize( $config );
                        $this->image_lib->resize();

                        $old_file = $image_path . $attachment->attach_name . '_thumb' . $attachment->attach_ext;
                        $new_file = $image_path . $attachment->attach_name . '_preview' . $attachment->attach_ext;

                        if ( !file_exists( $new_file ) ) {
                            rename( $old_file, $new_file );
                        }
                        $item_data['attach_preview_path'] = strstr( $new_file, "files/" );

                        create_watermark( $new_file, "Bezopasnost.kh.ua", WATERMANR_FONT_PATH, WATERMANR_R_CHANNEL, WATERMANR_G_CHANNEL, WATERMANR_B_CHANNEL, WATERMANR_ALPHA_LEVEL );

                        if ( $upload_type == 'product_title' || $upload_type == 'category_title' )
                        {
                            $config['width'] = $dimensions['single']['width'];
                            $config['height'] = $dimensions['single']['height'];

                            $this->image_lib->initialize( $config );
                            $this->image_lib->resize();

                            $old_file = $image_path . $attachment->attach_name . '_thumb' . $attachment->attach_ext;
                            $new_file = $image_path . $attachment->attach_name . '_single' . $attachment->attach_ext;

                            if ( !file_exists( $new_file ) ) {
                                rename( $old_file, $new_file );
                            }
                            $item_data['attach_single_path'] = strstr( $new_file, "files/" );
                            create_watermark( $new_file, "Bezopasnost.kh.ua", WATERMANR_FONT_PATH, WATERMANR_R_CHANNEL, WATERMANR_G_CHANNEL, WATERMANR_B_CHANNEL, WATERMANR_ALPHA_LEVEL );

                            // preview_main
                            $config['width'] = $dimensions['preview_main']['width'];
                            $config['height'] = $dimensions['preview_main']['height'];

                            $this->image_lib->initialize( $config );
                            $this->image_lib->resize();

                            $old_file = $image_path . $attachment->attach_name . '_thumb' . $attachment->attach_ext;
                            $new_file = $image_path . $attachment->attach_name . '_preview_main' . $attachment->attach_ext;

                            if ( !file_exists( $new_file ) ) {
                                rename( $old_file, $new_file );
                            }
                            $item_data['attach_preview_main_path'] = strstr( $new_file, "files/" );
                            create_watermark( $new_file, "Bezopasnost.kh.ua", WATERMANR_FONT_PATH, WATERMANR_R_CHANNEL, WATERMANR_G_CHANNEL, WATERMANR_B_CHANNEL, WATERMANR_ALPHA_LEVEL );

                            $this->image_lib->clear();
                        }
                        $item_data['attach_path'] = 'files/' . $attachment->attach_name . $attachment->attach_ext;
                    }
//                    else
//                    {
//                        $item_data['attach_preview_path'] = $this->_getAttachPreviewByExt( $attachment->attach_ext );
////                        $item_data
//                    }
                    // set image to database
                    $this->attachment->update_attachment( $attach_id, $item_data );


                    if ( $upload_type == 'category_title' ) {
                        $this->attachment->update_attachment_category( $attach_id, $item_id, $upload_type );
                    }
                    elseif ( $upload_type == 'photo1' || $upload_type == 'photo2' )
                    {
                        if ( $upload_type == 'map1' )
                        {
                            $contacts_data = array( 'contact_map1' => $attachment->attach_path );
                        }
                        elseif ( $upload_type == 'map2' )
                        {
                            $contacts_data = array( 'contact_map2' => $attachment->attach_path );
                        }
                        elseif ( $upload_type == 'photo1' )
                        {
                            $contacts_data = array( 'contact_photo1' => $attachment->attach_path );
                        }
                        elseif ( $upload_type == 'photo2' )
                        {
                            $contacts_data = array( 'contact_photo2' => $attachment->attach_path );
                        }
                        $this->load->model( 'contacts_mdl', 'contacts' );
                        $this->contacts->update_contacts( $contacts_data );

                    }
                    elseif ( $upload_type == 'gallery' )
                    {
                        $this->load->model( 'gallery_mdl' );
                        $this->gallery_mdl->set_attach_gallery( $gallery_id, $attach_id );
                        $attach_data = array( 'attach_title' => $title, 'attach_desc' => $desc );
                        $this->attachment->update_attachment( $attach_id, $attach_data );
                    }
                    else
                    {
                        $res = $this->attachment->update_attachment_item( $attach_id, $item_id, $upload_type );
                        create_watermark( $image_path . $attachment->attach_name . $attachment->attach_ext, "Bezopasnost.kh.ua", WATERMANR_FONT_PATH, WATERMANR_R_CHANNEL, WATERMANR_G_CHANNEL, WATERMANR_B_CHANNEL, WATERMANR_ALPHA_LEVEL );

                        if ( !$res )
                        {
                            echo "Document with the same title for this client exists";
                            exit;
                        }
                        else
                        {
                            $file_path = 'files/' . $attachment->attach_name . '_preview' . $attachment->attach_ext;
                            $file_full_path = 'files/' . $attachment->attach_name . $attachment->attach_ext;
                        }
                    }
                    $data = (Object)array( "attach_id" => $attach_id, "item_id" => $item_id, "file_path" => $file_path,
                                           "file_full_path" => $file_full_path, "attach_title" => $title,
                                           "attach_desc" => $desc );
                    $data = json_encode( $data );
                    echo $data;
                }
            }
        }
    }

    /**
     * @param $extention
     * @return bool|string
     */
    private function _getAttachPreviewByExt( $extention )
    {
        if( empty( $extention ) ) return false;

        $image = '';
        // excel
        if( in_array( $extention, array( '.csv', '.xls', '.xlsx', ) ) )
        {
            $image = 'images/icons/excel.png';
        }
        // doc
        elseif( in_array( $extention, array( '.word', '.docx', '.doc' ) ) )
        {
            $image = 'images/icons/doc.png';
        }
        // pdf
        elseif( in_array( $extention, array( '.pdf' ) )  )
        {
            $image = 'images/icons/pdf64.png';
        }
        // zip
        elseif( '.zip' )
        {
            $image = 'images/icons/zip-2.png';
        }
        return $image;
    }

    function upload_attach( $fieldname )
    {
        if ( !$fieldname ) {
            return false;
        }

        $config = $this->load->config( 'upload' );

        if ( !is_dir( $config['upload_path'] ) )
        {
            mkdir( $config['upload_path'], 0755 );
        }

        $this->load->library( 'upload', $config );

        if ( $this->upload->do_upload( $fieldname ) )
        {
            return $this->upload->data();

        }
        else
        {
            set_error( $this->upload->display_errors() );
            log_message( 'error', var_export( $this->upload->display_errors(), true ) );
        }
        return false;
    }

    function authorize( $login, $password )
    {
        $this->load->library( 'form_validation' );
        $this->load->config( 'form_validation' );
        $cfg = $this->config->item( 'users/authorize', 'form_validation' );
        $this->form_validation->set_rules( $cfg );

        if ( $this->form_validation->run() == TRUE )
        {
            $this->load->model( 'user_mdl', 'user' );
            $this->load->model( 'currency_mdl', 'currency' );

            $currency_all = $this->currency->get_currency();
            $currency_uah = $this->currency->get_currency( null, 'UAH' );
            if ( $currency_uah )
            {
                if ( is_array( $currency_uah ) ) {
                    $currency_uah = $currency_uah[0];
                }
                $currency_rate = $this->currency->get_currency_rate( $currency_uah->currency_id );
                if ( $currency_rate && is_array( $currency_rate ) ) {
                    $currency_rate = $currency_rate[0];
                }
                $this->db_session->set_userdata( 'currency_rate', $currency_rate );
                $this->db_session->set_userdata( 'currency_all', $currency_all );
            }

            $auth_user = $this->user->authorize( $login, $password );
            if ( $auth_user )
            {
                $this->db_session->set_userdata( 'user_id', $auth_user->user_id );
                $this->db_session->set_userdata( 'user_login', $auth_user->user_login );
                $this->db_session->set_userdata( 'user_role', $auth_user->user_role );
                $this->db_session->set_userdata( 'user_first', $auth_user->first_name );
                $this->db_session->set_userdata( 'user_last', $auth_user->last_name );
                $this->db_session->set_userdata( 'user_last_login', ( $auth_user->last_login_date )
                            ? $auth_user->last_login_date : null );

                if ( $auth_user->last_login_date )
                {
                    redirect( 'start' );
                }
            }
        }
        return false;
    }
}

?>
