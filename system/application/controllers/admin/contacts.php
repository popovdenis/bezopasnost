<?php
require_once ('adminAbstract.php');
/**
 * User: Denis
 * Date: 02.05.14
 * Time: 19:55
 */
class Contacts extends AdminAbstract
{
    protected $itemType = 'contacts';
    protected $itemName = 'Контакты';

    public function index()
    {
        $this->load->model('contacts_mdl', $this->itemType);
        $contacts = $this->contacts->get_contacts();
        $this->config->load('upload');
        $allowed_types = $this->config->item('allowed_types');

        if (isset($contacts) && is_array($contacts) && !empty($contacts)) {
            $contacts = $contacts[0];
        }
        $val = array();
        $val['contacts'] = $contacts;
        $val['allowed_types'] = $allowed_types;
        $val['item_type'] = $this->itemType;

        $this->load->view('admin/contacts', $val);
    }

    public function update()
    {
        $contact_address_1 = $this->input->post('contact_address_1');
        $contact_time_1_f_h    = $this->input->post('contact_time_1_f_h');
        $contact_time_1_f_m    = $this->input->post('contact_time_1_f_m');
        $contact_time_1_t_h    = $this->input->post('contact_time_1_t_h');
        $contact_time_1_t_m    = $this->input->post('contact_time_1_t_m');
        $contact_time_1_tm_f_h = $this->input->post('contact_time_1_tm_f_h');
        $contact_time_1_tm_f_m = $this->input->post('contact_time_1_tm_f_m');
        $contact_time_1_tm_t_h = $this->input->post('contact_time_1_tm_t_h');
        $contact_time_1_tm_t_m = $this->input->post('contact_time_1_tm_t_m');
        $contact_address_2     = $this->input->post('contact_address_2');
        $contact_time_2_f_h    = $this->input->post('contact_time_2_f_h');
        $contact_time_2_f_m    = $this->input->post('contact_time_2_f_m');
        $contact_time_2_t_h    = $this->input->post('contact_time_2_t_h');
        $contact_time_2_t_m    = $this->input->post('contact_time_2_t_m');
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
                "contact_address" => empty($contact_address_1) ?: $contact_address_1
            ),
            1 => array(
                "contact_address" => empty($contact_address_2) ?: $contact_address_2
            )
        );
        $contacts_data_address = json_encode($contacts_data_address);
        $contacts_data_time = array(
            0 => array(
                'time_from_h'    => empty($contact_time_1_f_h) ?: $contact_time_1_f_h,
                'time_from_m'    => empty($contact_time_1_f_m) ?: $contact_time_1_f_m,
                'time_to_h'      => empty($contact_time_1_t_h) ?: $contact_time_1_t_h,
                'time_to_m'      => empty($contact_time_1_t_m) ?: $contact_time_1_t_m,
                'time_tm_from_h' => empty($contact_time_1_tm_f_h) ?: $contact_time_1_tm_f_h,
                'time_tm_from_m' => empty($contact_time_1_tm_f_m) ?: $contact_time_1_tm_f_m,
                'time_tm_to_h'   => empty($contact_time_1_tm_t_h) ?: $contact_time_1_tm_t_h,
                'time_tm_to_m'   => empty($contact_time_1_tm_t_m) ?: $contact_time_1_tm_t_m
            ),
            1 => array(
                'time_from_h'    => empty($contact_time_2_f_h) ?: $contact_time_2_f_h,
                'time_from_m'    => empty($contact_time_2_f_m) ?: $contact_time_2_f_m,
                'time_to_h'      => empty($contact_time_2_t_h) ?: $contact_time_2_t_h,
                'time_to_m'      => empty($contact_time_2_t_m) ?: $contact_time_2_t_m,
                'time_tm_from_h' => empty($contact_time_2_tm_f_h) ?: $contact_time_2_tm_f_h,
                'time_tm_from_m' => empty($contact_time_2_tm_f_m) ?: $contact_time_2_tm_f_m,
                'time_tm_to_h'   => empty($contact_time_2_tm_t_h) ?: $contact_time_2_tm_t_h,
                'time_tm_to_m'   => empty($contact_time_2_tm_t_m) ?: $contact_time_2_tm_t_m
            )
        );
        $contacts_data_time = json_encode($contacts_data_time);
        $phones = array();
        $faxes  = array();
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
            "contact_times"   => $contacts_data_time,
            "contact_phones"  => json_encode($phones),
            "contact_emails"  => json_encode($emails),
            "contact_faxes"   => json_encode($faxes)
        );

        $this->load->model('contacts_mdl', 'contacts');
        $this->contacts->update_contacts($contacts_data);

        redirect('/admin/contacts', 'refresh');
    }
}