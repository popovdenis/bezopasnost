<?php
/**
 * User: Denis
 * Date: 02.05.14
 * Time: 19:55
 */
class Contacts extends Controller
{
    public function index()
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
        $val['item_type'] = 'contacts';

        $this->load->view('admin/contacts', $val);
    }
}