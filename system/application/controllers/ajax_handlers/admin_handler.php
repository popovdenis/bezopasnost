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
class Admin_handler extends Controller
{
    private $category_title = 'Продукция';
    private $per_page = 50;
    private $page = 1;

    const PRIVATE_KEY = 'Popov';

    function Admin_handler()
    {
        parent::Controller();

        $user_id = $this->db_session->userdata('user_id');
        $user_role = null;
        if (empty($user_id) || empty($user_role)) {
            $data = 5;
            $this->output->set_output($data);
        }
    }

    function ajax_actions()
    {
        $this->benchmark->mark('code_start');
        $user_id = $this->db_session->userdata('user_id');
        $user_role = $this->db_session->userdata('user_role');

        if (empty($user_id) || empty($user_role)) {
            $data = 5;
            $this->output->set_output($data);
            return;
        }

        $action = $this->input->post('action');

        $data = '';
        switch ($action) {
            case "get_new_page":
            case "get_page":
                $page = $this->input->post('page');
                $page_rus = $this->input->post('page_rus');
                $item_id = $this->input->post('item_id');
                $flag = $this->input->post('flag');

                if ($item_id == 'undefined') {
                    $item_id = null;
                }

                if ($flag != 'undefined' && $flag != '') {
                    if ($flag == 'exist') {
                        if ($page == 'about') {
                            $data = $this->_item_page($page, null);

                        } elseif ($page == 'contacts') {
                            $data = $this->_contacts_block();

                        } elseif ($page == 'gallery') {
                            $data = $this->_gallery_settings();
                        } elseif ($page == 'settings') {
                            $data = $this->_settings_main_page();
                        } else {
                            if ($item_id && $page != 'about') {
                                $data = $this->_item_page($page, $item_id);
                            } else {
                                $data = $this->_items_block($page, true, null, $page_rus);
                            }
                        }
                    } elseif ($flag == 'new') {
                        $data = $this->_new_item_page($page);
                    }
                }
                break;

            case "add_item":
            case "save_item":
                $item_title           = $this->input->post('item_title');
                $item_preview         = $this->input->post('item_preview');
                $item_marks           = $this->input->post('item_marks');
                $item_tags            = $this->input->post('item_tags');
                $item_type            = $this->input->post('item_type');
                $date_production      = $this->input->post('item_date_production');
                $minute_production    = $this->input->post('minute');
                $hour_production      = $this->input->post('hour');
                $item_mode            = $this->input->post('item_mode');
                $item_seo_title       = $this->input->post('item_seo_title');
                $item_seo_keywords    = $this->input->post('item_seo_keywords');
                $item_seo_description = $this->input->post('item_seo_description');
                $categories           = $this->input->post('categories');
                $content              = $this->input->post('content');
                $charecters           = $this->input->post('charecters');
                $itemId               = $this->input->post('item_id', null);

                $dateTimeProduction = new DateTime();
                if (!empty($date_production)) {
                    $dateTimeProduction = new DateTime($date_production);
                    if ($dateTimeProduction) {
                        $dateTimeProduction->setTime($hour_production, $minute_production);
                    }
                }

                $item_data = array(
                    'item_title'           => trim($item_title),
                    'item_preview'         => $this->input->xss_clean($item_preview),
                    'item_content'         => $this->input->xss_clean($content),
                    'item_characters'      => $this->input->xss_clean($charecters),
                    'item_added'           => date("Y-m-d H:i:s"),
                    'item_update'          => date("Y-m-d H:i:s"),
                    'item_production'      => $dateTimeProduction->format("Y-m-d H:i:s"),
                    'item_type'            => $item_type,
                    'item_tags'            => $item_tags,
                    'item_marks'           => $item_marks,
                    'item_mode'            => $item_mode,
                    'item_seo_title'       => $item_seo_title,
                    'item_seo_keywords'    => $item_seo_keywords,
                    'item_seo_description' => $item_seo_description,
                );

                $this->load->model('items_mdl', 'items');

                if (!empty($itemId)) {
                    $this->items->delete_item_category($itemId);
                }

                $itemId = $this->items->save_item($item_data, $itemId);

                if (!empty($categories) && is_array($categories)) {
                    foreach ($categories as $categoryId) {
                        $this->items->save_item_category(intval($categoryId), $itemId);
                    }
                }
                $data = json_encode(['success' => true]);
                break;

            case "delete_items_checked":
                $result = false;
                $items = $this->input->post('chb');
                if (!empty($items) && is_array($items)) {
                    $user_id   = $this->db_session->userdata('user_id');
                    $user_role = $this->db_session->userdata('user_role');
                    if (!empty($user_id) && !empty($user_role)) {
                        $this->load->model('items_mdl', 'items');
                        foreach ($items as $itemId) {
                            $this->items->delete_item(intval($itemId));
                        }
                        $result = true;
                    }
                }
                $data = json_encode(['success' => $result]);
                break;

            case "delete_item":
                $item_id = $this->input->post('item_id');

                $user_id = $this->db_session->userdata('user_id');
                $user_role = $this->db_session->userdata('user_role');

                if (empty($user_id) || empty($user_role)) {
                    $data = 5;
                } else {
                    $this->load->model('items_mdl', 'items');
                    $data = $this->items->delete_item($item_id);
                }

                break;

            case "paginate_items":
                $page_num = $this->input->post('page_num');
                $item_type = $this->input->post('item_type');

                $this->page = $page_num;
                $data = $this->_items_block($item_type, false);

                break;

            case "filter_items_category":
                $data = $this->_items_block(
                    $this->input->post('item_type'),
                    false,
                    $this->input->post('item_category')
                );

                break;

            /********  Категории  **********/

            case "add_category":
                $category_title = $this->input->post('category_title');
                $category_slug  = $this->input->post('category_slug');
                $category_desc = $this->input->post('category_desc');
                $category_parent = $this->input->post('category_parent');
                $item_id = $this->input->post('item_id');

                $this->load->model('category_mdl', 'category');
                $this->load->model('items_mdl', 'items');

                $category_data = array(
                    'category_title' => $category_title,
                    'category_slug'  => $category_slug,
                    'category_desc' => '',
                    'category_date_added' => date("Y-m-d H:i:s"),
                    'category_parent' => $category_parent
                );
                $category_id = $this->category->set_category($category_data);

                if (!$item_id) {
                    $content = $this->_get_category_page($category_id);
                    $data = (Object)array(
                        'header' => $content['header'],
                        'content' => $content['content'],
                        'category_id' => $category_id
                    );
                    $data = json_encode($data);

                } else {

                    $this->load->helper('bk');
                    $categories = get_categories_tree(0, array(), -1);
                    $items_cats = $this->items->get_item_category($item_id);

                    $cat_str = '';
                    $level = null;
                    foreach ($categories as $category) {
                        $checked = "";
                        $level = $category->level;
                        unset($category->level);

                        if (in_array($category, $items_cats)) {
                            $checked = "checked";
                        }
                        $margin = 10 * $level;
                        $style = 'style="margin-left:' . $margin . 'px;"';
                        $cat_str .= '<div ' . $style . '><input type="checkbox" id="ch_door" value="' . $category->category_id . '" ' . $checked . ' />' . $category->category_title . '</div>';
                    }
                    $data = $cat_str;
                }

                break;

            case "search_category":
                $category_id = $this->input->post('category_id');
                $data = $this->_get_category_page($category_id, 'category_title', false);
                $data = $data['content'];
                break;

            case "update_category":
                $category_id          = $this->input->post('category_id');
                $category_title       = $this->input->post('category_title');
                $category_slug        = $this->input->post('category_slug');
                $category_desc        = $this->input->post('category_desc');
                $category_parent      = $this->input->post('category_parent');
                $item_seo_title       = $this->input->post('item_seo_title', null);
                $item_seo_keywords    = $this->input->post('item_seo_keywords', null);
                $item_seo_description = $this->input->post('item_seo_description', null);

                $this->load->model('category_mdl', 'category');

                $category_data = array(
                    'category_title'       => $category_title,
                    'category_slug'        => $category_slug,
                    'category_desc'        => $category_desc,
                    'category_parent'      => $category_parent,
                    'item_seo_title'       => $item_seo_title,
                    'item_seo_keywords'    => $item_seo_keywords,
                    'item_seo_description' => $item_seo_description
                );
                $this->category->update_category($category_id, $category_data);
                $category_info = $this->_get_category_page($category_id, 'category_title');

                $data = (Object)array('header' => $category_info['header'], 'content' => $category_info['content']);
                $data = json_encode($data);

                break;

            case "delete_category":
                $category_id = $this->input->post('category_id');

                $this->load->model('category_mdl', 'category');

                $categories = get_categories_tree($category_id, array(), -1);
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        $this->category->delete_category($category->category_id);
                    }
                }
                $this->category->delete_category($category_id);
                $data = 1;

                break;

            case "add_category_partner":
                $category_id = $this->input->post('category_id');
                $partner_id = $this->input->post('partner_id');

                $this->load->model('category_mdl', 'category');
                $data = $this->category->set_category_partner($category_id, $partner_id);

                break;

            case "delete_category_partner":
                $category_id = $this->input->post('category_id');
                $partner_id = $this->input->post('partner_id');

                $this->load->model('category_mdl', 'category');
                $data = $this->category->delete_category_partner($category_id, $partner_id);

                break;

            case "reorder_categories":
                $category_id = $this->input->post('category_id');
                $cat_order = $this->input->post('cat_order');

                $cat_order = json_decode($cat_order, true);
                $this->load->model('category_mdl', 'category');
                $data = $this->category->reorder_categories($category_id, $cat_order);
                break;

            /********** Контакты  ************/
            case "add_contact":
                $contact_type = $this->input->post('contact_type');
                $contact_value = $this->input->post('contact_value');

                $this->load->model('contacts_mdl', 'contacts');
                $contacts = $this->contacts->get_contacts();

                $contacts_data = null;

                switch ($contact_type) {
                    case "phone":
                        if (!empty($contacts->contact_phones)) {
                            $contacts->contact_phones = json_decode($contacts->contact_phones, true);
                        } else {
                            $contacts->contact_phones = array();
                        }
                        array_push($contacts->contact_phones, $contact_value);
                        $contacts_data = array('contact_phones' => json_encode($contacts->contact_phones));

                        break;

                    case "fax":
                        if (!empty($contacts->contact_faxes)) {
                            $contacts->contact_faxes = json_decode($contacts->contact_faxes, true);
                        } else {
                            $contacts->contact_faxes = array();
                        }
                        array_push($contacts->contact_faxes, $contact_value);
                        $contacts_data = array('contact_faxes' => json_encode($contacts->contact_faxes));

                        break;

                    case "email":
                        if (!empty($contacts->contact_emails)) {
                            $contacts->contact_emails = json_decode($contacts->contact_emails, true);
                        } else {
                            $contacts->contact_emails = array();
                        }
                        array_push($contacts->contact_emails, $contact_value);
                        $contacts_data = array('contact_emails' => json_encode($contacts->contact_emails));

                        break;

                }
                $this->contacts->update_contacts($contacts_data);

                $data = $this->_contact_section();

                break;
            case "update_contacts":

                $contact_address_1 = $this->input->post('contact_address_1');

                $contact_time_1_f_h = $this->input->post('contact_time_1_f_h');
                $contact_time_1_f_m = $this->input->post('contact_time_1_f_m');
                $contact_time_1_t_h = $this->input->post('contact_time_1_t_h');
                $contact_time_1_t_m = $this->input->post('contact_time_1_t_m');
                $contact_time_1_tm_f_h = $this->input->post('contact_time_1_tm_f_h');
                $contact_time_1_tm_f_m = $this->input->post('contact_time_1_tm_f_m');
                $contact_time_1_tm_t_h = $this->input->post('contact_time_1_tm_t_h');
                $contact_time_1_tm_t_m = $this->input->post('contact_time_1_tm_t_m');

                $contact_address_2 = $this->input->post('contact_address_2');
                $contact_time_2_f_h = $this->input->post('contact_time_2_f_h');
                $contact_time_2_f_m = $this->input->post('contact_time_2_f_m');
                $contact_time_2_t_h = $this->input->post('contact_time_2_t_h');
                $contact_time_2_t_m = $this->input->post('contact_time_2_t_m');
                $contact_time_2_tm_f_h = $this->input->post('contact_time_2_tm_f_h');
                $contact_time_2_tm_f_m = $this->input->post('contact_time_2_tm_f_m');
                $contact_time_2_tm_t_h = $this->input->post('contact_time_2_tm_t_h');
                $contact_time_2_tm_t_m = $this->input->post('contact_time_2_tm_t_m');

                $contact_elements = $this->input->post('contact_obj');
                if (!empty($contact_elements)) {
                    $contact_elements = $contact_elements['elements'];
                }

                $contacts_data_address = array(
                    0 => array(
                        "contact_address" => empty($contact_address_1) ? ''
                                : $contact_address_1
                    ),
                    1 => array(
                        "contact_address" => empty($contact_address_2) ? ''
                                : $contact_address_2
                    )
                );
                $contacts_data_address = json_encode($contacts_data_address);

                $contacts_data_time = array(
                    0 => array(
                        'time_from_h' => empty($contact_time_1_f_h) ? ''
                                : $contact_time_1_f_h,
                        'time_from_m' => empty($contact_time_1_f_m) ? ''
                                : $contact_time_1_f_m,
                        'time_to_h' => empty($contact_time_1_t_h) ? ''
                                : $contact_time_1_t_h,
                        'time_to_m' => empty($contact_time_1_t_m) ? ''
                                : $contact_time_1_t_m,
                        'time_tm_from_h' => empty($contact_time_1_tm_f_h) ? ''
                                : $contact_time_1_tm_f_h,
                        'time_tm_from_m' => empty($contact_time_1_tm_f_m) ? ''
                                : $contact_time_1_tm_f_m,
                        'time_tm_to_h' => empty($contact_time_1_tm_t_h) ? ''
                                : $contact_time_1_tm_t_h,
                        'time_tm_to_m' => empty($contact_time_1_tm_t_m) ? ''
                                : $contact_time_1_tm_t_m
                    ),
                    1 => array(
                        'time_from_h' => empty($contact_time_2_f_h) ? ''
                                : $contact_time_2_f_h,
                        'time_from_m' => empty($contact_time_2_f_m) ? ''
                                : $contact_time_2_f_m,
                        'time_to_h' => empty($contact_time_2_t_h) ? ''
                                : $contact_time_2_t_h,
                        'time_to_m' => empty($contact_time_2_t_m) ? ''
                                : $contact_time_2_t_m,
                        'time_tm_from_h' => empty($contact_time_2_tm_f_h) ? ''
                                : $contact_time_2_tm_f_h,
                        'time_tm_from_m' => empty($contact_time_2_tm_f_m) ? ''
                                : $contact_time_2_tm_f_m,
                        'time_tm_to_h' => empty($contact_time_2_tm_t_h) ? ''
                                : $contact_time_2_tm_t_h,
                        'time_tm_to_m' => empty($contact_time_2_tm_t_m) ? ''
                                : $contact_time_2_tm_t_m
                    )
                );
                $contacts_data_time = json_encode($contacts_data_time);

                $phones = array();
                $faxes = array();
                $emails = array();
                if (!empty($contact_elements)) {
                    foreach ($contact_elements as &$element) {
                        if (empty($element['item_value'])) {
                            continue;
                        }
                        if ($element['item_type'] == 'phone') {
                            $phones[] = $element['item_value'];
                        } elseif ($element['item_type'] == 'fax') {
                            $faxes[] = $element['item_value'];
                        } elseif ($element['item_type'] == 'email') {
                            $emails[] = $element['item_value'];
                        }
                    }
                }
                $contacts_data = array(
                    "contact_address" => $contacts_data_address,
                    "contact_times" => $contacts_data_time,
                    "contact_phones" => json_encode($phones),
                    "contact_emails" => json_encode($emails),
                    "contact_faxes" => json_encode($faxes)
                );

                $this->load->model('contacts_mdl', 'contacts');
                $this->contacts->update_contacts($contacts_data);
                $data = $this->_contacts_block();

                break;

            /***** Пользователи ********/
            case "add_user":
                $login = $this->input->post('user_login');
                $password = $this->input->post('user_password');
                $firstname = $this->input->post('first_name');
                $lastname = $this->input->post('last_name');
                $user_email = $this->input->post('user_email');
                $user_role = $this->input->post('user_role');

                $data_user = array(
                    'user_login' => $this->input->xss_clean($login),
                    'user_password' => $this->input->xss_clean($password),
                    'first_name' => $this->input->xss_clean($firstname),
                    'last_name' => $this->input->xss_clean($lastname),
                    'user_email' => $this->input->xss_clean($user_email),
                    'user_role' => $this->input->xss_clean($user_role),
                    'registration_date' => date("Y-m-d"),
                    'user_role' => $user_role
                );

                $this->load->model('user_mdl', 'user');
                $user_id = $this->user->add_user($data_user);

                if ($user_id) {
                    $users_list = $this->_search_user_list();
                    $user_data = $this->get_found_user(null, $user_id);

                    $data = (Object)array('users_list' => $users_list, 'user_info' => $user_data);
                    $data = json_encode($data);

                } else {
                    $data = 4;
                }
                break;

            case "get_user":
                $user_id = $this->input->post('user_id');

                $data = $this->get_found_user(null, $user_id);

                break;

            case "update_user":
                $user_id = $this->input->post('user_id');
                $login = $this->input->post('user_login');
                $firstname = $this->input->post('first_name');
                $lastname = $this->input->post('last_name');
                $user_email = $this->input->post('user_email');
                $user_role = $this->input->post('user_role');

                $data_user = array(
                    'user_login' => $this->input->xss_clean($login),
                    'first_name' => $this->input->xss_clean($firstname),
                    'last_name' => $this->input->xss_clean($lastname),
                    'user_email' => $this->input->xss_clean($user_email),
                    'user_role' => $this->input->xss_clean($user_role),
                    'registration_date' => date("Y-m-d"),
                    'user_role' => $user_role
                );

                $this->load->model('user_mdl', 'user');
                $res = $this->user->update_user($user_id, $data_user);

                if ($res) {
                    $users_list = $this->_search_user_list();
                    $user_data = $this->get_found_user(null, $user_id);

                    $data = (Object)array('users_list' => $users_list, 'user_info' => $user_data);

                    $data = json_encode($data);

                } else {
                    $data = 4;
                }

                break;

            case "delete_user":
                $this->load->model('user_mdl', 'user');
                break;

            case "change_password":
                $this->load->model('user_mdl', 'user');

                $user_id = $this->input->post('user_id');
                $user_data = $this->user->get_users($user_id);
                if ($user_data && is_array($user_data) && is_object($user_data[0])) {
                    $data = 1;
                } else {
                    $data = 0;
                }

                break;

            case "save_password":
                $user_id = $this->input->post('user_id');
                $user_password = $this->input->post('user_password');

                $data_user = array('user_password' => md5($this->input->xss_clean($user_password . self::PRIVATE_KEY)));
                $this->load->model('user_mdl', 'user');
                $data = $this->user->update_user($user_id, $data_user);

                break;

            /**** Currency ***/

            case "add_currency":
                $carrency_id = $this->input->post('carrency_id');
                $item_id = $this->input->post('item_id');
                $currency_value = $this->input->post('currency_value');

                $this->load->model('currency_mdl', 'currency');

                $data = 0;
                if (!empty($currency_value)) {
                    $currency_id = $this->currency->add_currency($currency_value);
                    $currency = $this->currency->get_currency($currency_id);
                    if ($currency) {
                        if (is_array($currency)) {
                            $currency = $currency[0];
                        }
                        $data = array(
                            "currency_id" => $currency->currency_id,
                            "currency_value" => $currency->currency_value
                        );
                        $data = json_encode($data);
                    }
                }

                break;

            case "get_currency":
                $data = $this->_get_currency_block($this->input->post('currency_id'));
                break;

            case "update_currency_rate":
                $currency_id = $this->input->post('currency_id');
                $currency_names = $this->input->post('currency_names');

                $this->load->model('currency_mdl', 'currency');

                foreach ($currency_names as $name) {
                    $values = [
                        "name" => $name['name'],
                        "value" => $name['value']
                    ];
                    $this->currency->update_currency_rate($currency_id, $values);
                }

                $this->load->model('currency_mdl', 'currency');

                $currency_all = $this->currency->get_currency();
                $currency_uah = $this->currency->get_currency(null, 'UAH');
                if ($currency_uah) {
                    if (is_array($currency_uah)) {
                        $currency_uah = $currency_uah[0];
                    }
                    $currency_rate = $this->currency->get_currency_rate($currency_uah->currency_id);
                    if ($currency_rate && is_array($currency_rate)) {
                        $currency_rate = $currency_rate[0];
                    }
                    $this->db_session->set_userdata('currency_rate', $currency_rate);
                    $this->db_session->set_userdata('currency_all', $currency_all);
                }

                break;

            case "add_price":
                $carrency_id = $this->input->post('carrency_id');
                $item_id = $this->input->post('item_id');
                $price = $this->input->post('price');

                $this->load->model('currency_mdl', 'currency');
                $result = $this->currency->add_item_price($carrency_id, $item_id, $price);
                break;

            case "change_price":
                $price_uah = $this->input->post('price_uah');
                $item_id = $this->input->post('item_id');

                $this->load->model('items_mdl', 'items');
                $this->items->save_item(array("item_price" => $price_uah), $item_id);

                $data = json_encode(array('result' => true));
                break;

            case "add_gallery":
                $gallery_title = $this->input->post('gallery_title', true);
                $gallery_data = array('gallery_title' => $gallery_title, 'gallery_date' => date("Y-m-d H:i:s"));
                $this->load->model('gallery_mdl', 'gallery');
                $gallery_id = $this->gallery->add_gallery($gallery_data);
                $gallery_info = $this->get_gallery_info($gallery_id);
                $gallery_images = $this->get_gallery_images($gallery_id);
                $data = (Object)array(
                    "gallery_id" => $gallery_id,
                    "gallery_info" => $gallery_info,
                    "gallery_images" => $gallery_images
                );
                $data = json_encode($data);

                break;

            case "update_gallery":
                $gallery_id = $this->input->post('gallery_id', true);
                $gallery_title = $this->input->post('gallery_title', true);
                $gallery_data = array('gallery_title' => $gallery_title);
                $this->load->model('gallery_mdl', 'gallery');
                $data = $this->gallery->update_gallery($gallery_id, $gallery_data);

                break;

            case "get_gallery":
                $gallery_id = $this->input->post('gallery_id');
                $gallery_info = $this->get_gallery_info($gallery_id);
                $gallery_images = $this->get_gallery_images($gallery_id);
                $data = (Object)array("gallery_info" => $gallery_info, "gallery_images" => $gallery_images);
                $data = json_encode($data);
                break;

            case "delete_gallery":
                $gallery_id = $this->input->post('gallery_id');
                $this->load->model('gallery_mdl', 'gallery');
                $data = $this->gallery->delete_gallery($gallery_id);

                break;

            case "assign_gallery_to_item":
                $gallery_id = $this->input->post('gallery_id');
                $item_id = $this->input->post('item_id');

                $this->load->model('gallery_mdl', 'gallery');
                $gallery_id = $this->gallery->set_item_gallery($gallery_id, $item_id);
                $data = $this->get_gallery_item($item_id);
                break;

            case "delete_attach_gallery":
                $gallery_id = $this->input->post('gallery_id');
                $attach_id = $this->input->post('item_id');

                $this->load->model('attachment');
                $this->load->model('gallery_mdl', 'gallery');
                $this->gallery->delete_attach_gallery($gallery_id, $attach_id);
                $data = $this->attachment->delete_attach($attach_id);
                break;

            case "add_ann_item":
                $item_id = $this->input->post('item_id');
                $this->load->model('items_mdl', 'items');
                $this->load->model('category_mdl', 'category');

                $cat_ad = $this->category->get_category(null, null, 'Ad');
                $cat_id = null;
                if ($cat_ad && is_array($cat_ad)) {
                    $cat_ad = $cat_ad[0];
                    $cat_id = $cat_ad->category_id;
                }
                $data = $this->items->add_item_ad($item_id, $cat_id);
                break;

            case "delete_ann_item":
                $item_id = $this->input->post('item_id');
                $this->load->model('items_mdl', 'items');
                $this->load->model('category_mdl', 'category');

                $cat_ad = $this->category->get_category(null, null, 'Ad');
                $cat_id = null;
                if ($cat_ad && is_array($cat_ad)) {
                    $cat_ad = $cat_ad[0];
                    $cat_id = $cat_ad->category_id;
                }
                $data = $this->items->delete_item_ad($item_id, $cat_id);
                break;

            case "reorder_attach_gallery":
                $gallery_id = $this->input->post('gallery_id');
                $attach_order = $this->input->post('attach_order');

                $attach_order = json_decode($attach_order, true);

                $this->load->model('gallery_mdl', 'gallery');
                $data = $this->gallery->reorder_attach_gallery($gallery_id, $attach_order);
                break;

            case "delete_item_gallery":
                $gallery_id = $this->input->post('gallery_id');
                $item_id = $this->input->post('item_id');

                $this->load->model('gallery_mdl', 'gallery');
                $data = $this->gallery->delete_item_gallery($gallery_id, $item_id);
                break;
        }
        $this->output->set_output($data);
    }

    function _get_currency_block($carrency_id = null)
    {
        $this->load->model('currency_mdl', 'currency');
        $currency_all = $this->currency->get_currency();
        $currency = $this->currency->get_currency($carrency_id);
        $currency_rate = $this->currency->get_currency_rate($carrency_id);

        if ($currency && is_array($currency)) {
            $currency = $currency[0];
        }
        if ($currency_rate && is_array($currency_rate)) {
            $currency_rate = $currency_rate[0];
        }

        $data = array();
        $data['currency_all'] = $currency_all;
        $data['currency'] = $currency;
        $data['currency_rate'] = $currency_rate;
        return $this->load->view('admin/_currency_block', $data, true);
    }

    function get_item(
        $item_id,
        $item_type = null,
        $item_mode = false,
        $category = null,
        $per_page = 0,
        $page = 1,
        $with_count = false
    ) {
        $this->load->model('items_mdl', 'items');

        return $this->items->get_item($item_id, $item_type, $item_mode, $category, $per_page, $page, $with_count);
    }

    public function _items_block($item_type = 'partners', $with_page = true, $category_id = null, $page_rus = '')
    {
        mb_internal_encoding("UTF-8");

        $items_str = "";
        $val       = array();
        $val['item_type'] = $item_type;

        $items     = $this->get_item(null, $item_type, false, $category_id, $this->per_page, $this->page, true);
        $items_all = $items['count'];
        unset($items['count']);

        if ($items) {
            foreach ($items as &$item) {
                if (!empty($item->item_preview)) {
                    $item_desc = preg_replace("/<p><img(.*?)\/><\/p>/si", "", $item->item_preview);
                    $item_desc = str_replace(array('<p>', '</p>', '<h1>', '</h1>', '<h3>', '</h3>'), '', $item_desc);
                    if (strlen($item_desc) > 80) {
                        $item_desc = mb_substr($item_desc, 0, 75) . "...";
                    }
                    $item->item_preview = $item_desc;
                }
                $categories = $this->items->get_item_category($item->item_id);

                $cat_str = '&nbsp;';
                if ($categories && !empty($categories)) {
                    $end = end($categories);
                    foreach ($categories as &$category) {
                        $cat_str .= $category->category_title;
                        if ($end->category_title != $category->category_title) {
                            $cat_str .= ', ';
                        }
                    }
                }
                if ($item->item_production > date("Y-m-d H:i:s")) {
                    $item->item_mode = 'draft';
                }
                $item->cat_str = $cat_str;
            }

            $num_pages = (int)($items_all / $this->per_page);
            if ($num_pages <= 0) {
                $num_pages = 1;
            }

            $val['currency_rate'] = $this->db_session->userdata('currency_rate');
            $val['items']         = $items;
            $val['cur_page']      = $this->page;
            $val['num_pages']     = $num_pages;
            $val['paginate_args'] = array(
                'total_rows'  => $items_all,
                'per_page'    => $this->per_page,
                'num_links'   => 2,
                'cur_page'    => $this->page,
                'uri_segment' => 3,
                'js_function' => 'paginate_items',
                'base_url'    => base_url() . index_page() . 'admin/home/'
            );
            $items_str = $this->load->view('admin/_items_block', $val, true);
        }

        if ($with_page) {
            $this->load->model('category_mdl', 'category');
            $category_current = $this->category->get_category(null, null, $page_rus);

            if ($category_current) {
                if (is_array($category_current)) {
                    $category_current = $category_current[0];
                }
                $categories = $this->category->get_category(null, $category_current->category_id);
                array_push($categories, $category_current);
            }

            $val['categories']  = $categories;
            $val['items_block'] = $items_str;
            return $this->load->view('admin/_items', $val, true);
        } else {
            return $items_str;
        }
    }

    function _item_page($item_type, $item_id)
    {
        $config = $this->load->config('upload');
        $val = array();

        $item = $this->get_item($item_id, $item_type);
        if ($item && is_array($item)) {
            $item = $item[0];
        }

        if ($item->item_production > date("Y-m-d H:i:s")) {
            $item->item_mode = 'draft';
        }

        $val['item'] = $item;
        $val['item_id'] = $item_id;
        $val['item_type'] = $item_type;
        $val['allowed_types'] = $config['allowed_types'];

        $this->load->model('attachment');
        $title = $this->attachment->get_attach_item($item->item_id, 'product_title');

        if ($title && is_array($title)) {
            $item->attach = $title[0];
        } else {
            $item->attach = null;
        }

        $this->load->model('category_mdl', 'category');
        $this->load->helper('bk');
        $this->load->model('gallery_mdl');

        $val['galleries'] = $this->gallery_mdl->get_gallery();
        $val['gallery_item'] = $this->get_gallery_item($item_id);

        switch ($item_type) {
            case "about":
            case "partners":
                $main = $this->category->get_category(null, null, 'Партнеры');
                $parent_id = 0;
                if ($main && is_array($main)) {
                    $parent_id = $main[0]->category_id;
                    $main[0]->level = 0;
                }
                $val['categories'] = $main;
                $val['items_cats'] = $this->items->get_item_category($item->item_id);
            case "contacts":
            case "settings":
                break;

            case "information":
                $main = $this->category->get_category(null, null, 'Информация');
                $parent_id = 0;
                if ($main && is_array($main)) {
                    $main = $main[0];
                    $parent_id = $main->category_id;
                }
                $val['categories'] = get_categories_tree($parent_id, array(), -1);
                $val['items_cats'] = $this->items->get_item_category($item->item_id);
                $val['gallery_price'] = $this->gallery->get_gallery($item->item_id, true, 'gallery_price');
                break;

            case "products":
                $this->load->model('items_mdl', 'items');
                $this->load->model('currency_mdl', 'currency');

                $main = $this->category->get_category(null, null, 'Продукция');
                $parent_id = 0;
                if ($main && is_array($main)) {
                    $main = $main[0];
                    $parent_id = $main->category_id;
                }
                $val['categories'] = get_categories_tree($parent_id, array(), -1);
                $val['items_cats'] = $this->items->get_item_category($item->item_id);
                $val['currency_rate'] = $this->db_session->userdata('currency_rate');
                $val['currency_all'] = $this->currency->get_currency();
                break;
        }
        return $this->load->view('admin/_item_page', $val, true);
    }

    function _new_item_page($item_type)
    {
        $val = array();

        $val['item_type'] = $item_type;

        switch ($item_type) {
            case "about":
            case "contacts":
            case "partners":
            case "settings":
                break;

            case "information":
                $this->load->model('category_mdl', 'category');
                $this->load->helper('bk');

                $main = $this->category->get_category(null, null, 'Информация');
                $parent_id = 0;
                if ($main && is_array($main)) {
                    $main = $main[0];
                    $parent_id = $main->category_id;
                }
                $val['category_main'] = $main;
                $val['categories'] = get_categories_tree($parent_id, array(), -1);
                break;

            case "products":
                $this->load->model('category_mdl', 'category');
                $this->load->helper('bk');

                $main = $this->category->get_category(null, null, 'Продукция');
                $parent_id = 0;
                if ($main && is_array($main)) {
                    $main = $main[0];
                    $parent_id = $main->category_id;
                }
                $val['category_main'] = $main;
                $val['categories'] = get_categories_tree($parent_id, array(), -1);
                break;
        }
        return $this->load->view('admin/_new_item_page', $val, true);
    }

    function _contacts_block()
    {
        $this->load->model('contacts_mdl', 'contacts');
        $contacts = $this->contacts->get_contacts();
        $this->config->load('upload');
        $allowed_types = $this->config->item('allowed_types');

        if (isset($contacts) && is_array($contacts) && !empty($contacts)) {
            $contacts = $contacts[0];
        }
        $val = array();
        $val['contacts'] = $contacts;
        $val['allowed_types'] = $allowed_types;

        return $this->load->view('admin/_contacts_main', $val, true);
    }

    function _contact_section()
    {
        $this->load->model('contacts_mdl', 'contacts');
        $contacts = $this->contacts->get_contacts();

        if (isset($contacts) && is_array($contacts) && !empty($contacts)) {
            $contacts = $contacts[0];
        }
        $val = array();
        $val['contacts'] = $contacts;
        return $this->load->view('admin/_contacts_section', $val, true);
    }

    function _settings_main_page()
    {
        $this->load->model('category_mdl', 'category');
        $this->load->model('items_mdl', 'items');
        $this->load->helper('bk');

        $categories   = get_categories_tree(-1, array(), -1);
        $currencylist = $this->_search_currency_list();
        $userlist     = $this->_search_user_list();
        $items        = $this->get_item(null, 'products');
        $ann_items    = $this->get_ann_items();

        $cat_str = '';
        foreach ($categories as $category) {
            $indention = str_repeat("&nbsp;&nbsp;", $category->level);
            $cat_str .= '<option value="' . $category->category_id . '">' . $indention . $category->category_title . '</option>';
        }

        $val = [
            'items'        => $items,
            'ann_items'    => $ann_items,
            'categories'   => $cat_str,
            'userlist'     => $userlist,
            'currencylist' => $currencylist
        ];
        return $this->load->view('admin/_settings_main_page', $val, true);
    }

    function _get_category_page($category_id, $attach_type = null, $get_header = true)
    {
        if (!$category_id) {
            return false;
        }

        $this->load->model('items_mdl', 'items');
        $this->load->model('category_mdl', 'category');
        $this->load->helper('bk');

        $partners = $this->get_item(null, 'partners');
        $cat_partners = $this->category->get_category_partner($category_id);

        $categories = get_categories_tree(-1, array(), -1);
        $category = $this->category->get_category($category_id);
        if ($category && is_array($category)) {
            $category = $category[0];
        }
        if ($attach_type) {
            if (!is_array($attach_type)) {
                $attachments = $this->category->get_category_attachment($category->category_id, null, $attach_type);
                if ($attachments && is_array($attachments)) {
                    $attachments = $attachments[0];
                }

                $category->attach = $attachments;
            }
        }
        $sub_cats = $this->category->get_category(null, $category_id, null, "category_position");

        $cat_str = '';
        foreach ($categories as $cat) {
            $indention = '';
            $selected = '';
            if ($cat->category_id == $category->category_parent) {
                $selected = 'selected';
            }

            $indention = str_repeat("&nbsp;&nbsp;", $cat->level);
            $cat_str .= '<option value="' . $cat->category_id . '" ' . $selected . '>' . $indention . $cat->category_title . '</option>';
        }

        $val = [
            'categories'   => $cat_str,
            'category'     => $category,
            'partners'     => $partners,
            'cat_partners' => $cat_partners,
            'sub_cats'     => $sub_cats
        ];
        $header = ($get_header) ? $this->load->view('admin/_category_header', $val, true) : '';
        $content = $this->load->view('admin/_category_info', $val, true);

        return array('header' => $header, 'content' => $content);
    }

    function _search_user_list()
    {

        $this->load->model('user_mdl', 'user');
        $users = $this->user->get_users();

        $data = array();
        $data['users'] = $users;
        return $this->load->view('admin/_search_user_list', $data, true);
    }

    function get_found_user($login = null, $user_id = null, $result_type = '')
    {
        if (!$login && !$user_id) {
            return false;
        }

        $this->load->model('user_mdl', 'user');

        if ($login) {
            $user = $this->user->get_user_by_login($login);
        } elseif ($user_id) {
            $user = $this->user->get_users($user_id);
            if (!empty($user)) {
                $user = $user[0];
            }
        }
        if (!is_object($user)) {
            set_error('error [get_user_details]');
            log_message('error', 'USER is not object!');
        } else {
            $user_info = '';
            $data['user'] = $user;
            $user_info = $this->load->view('admin/user_info', $data, true);
        }
        if ($result_type == 'array') {
            return array('user_info' => $user_info, 'user' => $user);
        } else {
            return $user_info;
        }
    }

    function _search_currency_list()
    {
        $this->load->model('currency_mdl', 'currency');
        $currency = $this->currency->get_currency();

        $data = array();
        $data['currency'] = $currency;
        return $this->load->view('admin/_search_currency_list', $data, true);
    }

    function _gallery_settings()
    {
        $this->load->model('gallery_mdl', 'gallery');

        $galleries = $this->gallery->get_gallery();
        $val = array();
        $val['galleries'] = $galleries;
        return $this->load->view('admin/_gallery_settings', $val, true);
    }

    function get_gallery_info($gallery_id)
    {
        $this->load->model('gallery_mdl', 'gallery');

        $gallery = $this->gallery->get_gallery($gallery_id);
        if ($gallery && is_array($gallery)) {
            $gallery = $gallery[0];
        }
        $val = array();
        $val['gallery'] = $gallery;
        return $this->load->view('admin/_gallery_info', $val, true);
    }

    function get_gallery_images($gallery_id = null)
    {
        $this->load->model('gallery_mdl', 'gallery');

        $gallery = $this->gallery->get_gallery($gallery_id);
        if ($gallery) {
            if (is_array($gallery)) {
                $gallery = $gallery[0];
            }
            $gallery->attachments = $this->gallery->get_gallery_attachment($gallery_id);
        }

        $this->config->load('upload');
        $allowed_types = $this->config->item('allowed_types');

        $val = array();
        $val['gallery'] = $gallery;
        $val['allowed_types'] = $allowed_types;

        return $this->load->view('admin/gallery_images', $val, true);
    }

    function get_gallery_item($item_id)
    {
        $this->load->model('gallery_mdl', 'gallery');
        $gallery = $this->gallery->get_item_gallery(null, $item_id);
        $val = array();
        $val['gallery'] = $gallery;
        $val['item_id'] = $item_id;
        return $this->load->view('admin/gallery_image_item', $val, true);
    }

    function get_ann_items()
    {
        $this->load->model('category_mdl', 'category');

        $cat = $this->category->get_category(null, null, 'Ad');
        if ($cat && is_array($cat)) {
            $cat = $cat[0];
        }
        $items = $this->get_item(null, null, null, $cat->category_id);
        $val['items'] = $items;
        return $this->load->view('admin/ann_items', $val, true);
    }

    public function upload()
    {
        $url = realpath(BASEPATH . '../files') . '/' . time() . "_" . $_FILES['upload']['name'];

        //extensive suitability check before doing anything with the file…
        if (($_FILES['upload'] == "none") OR (empty($_FILES['upload']['name']))) {
            $message = "No file uploaded.";
        } else if ($_FILES['upload']["size"] == 0) {
            $message = "The file is of zero length.";
        } else if (($_FILES['upload']["type"] != "image/pjpeg") AND ($_FILES['upload']["type"] != "image/jpeg") AND ($_FILES['upload']["type"] != "image/png")) {
            $message = "The image must be in either JPG or PNG format. Please upload a JPG or PNG instead.";
        } else if (! is_uploaded_file($_FILES['upload']["tmp_name"])) {
            $message = "You may be attempting to hack our server. We're on to you; expect a knock on the door sometime soon.";
        } else {
            $message = "";
            $move    = @ move_uploaded_file($_FILES['upload']['tmp_name'], $url);
            if (! $move) {
                $message = "Error moving uploaded file. Check the script is granted Read/Write/Modify permissions.";
            }
            $url = "../" . $url;
        }

        $funcNum = "";

        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
    }
}

?>
