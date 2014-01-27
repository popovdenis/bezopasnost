<?php
    class Users extends Controller
    {
        const PRIVATE_KEY = 'Popov';

        function login()
        {
            $login    = $this->input->post('login');
            $password = $this->input->post('password');
            if (!empty($login) && !empty($password)) {
                $this->authorize($login, $password);
                redirect('start');
            } else {
                $user_id   = $this->db_session->userdata('user_id');
                $user_role = $this->db_session->userdata('user_role');
                if (empty($user_id) || empty($user_role)) {
                    $this->load->view('admin/_login_usual', null);
                } else {
                    $data = array();
                    $this->load->view('admin/_home', $data);
                }
            }
        }

        function authorize($login, $password)
        {
            $this->load->library('form_validation');
            $this->load->config('form_validation');
            $cfg = $this->config->item('users/authorize', 'form_validation');
            $this->form_validation->set_rules($cfg);
            if ($this->form_validation->run() == true) {
                $this->load->model('user_mdl', 'user');
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
                $auth_user = $this->user->authorize($login, $password);
                if ($auth_user) {
                    $this->db_session->set_userdata('user_id', $auth_user->user_id);
                    $this->db_session->set_userdata('user_login', $auth_user->user_login);
                    $this->db_session->set_userdata('user_role', $auth_user->user_role);
                    $this->db_session->set_userdata('user_first', $auth_user->first_name);
                    $this->db_session->set_userdata('user_last', $auth_user->last_name);
                    $this->db_session->set_userdata(
                        'user_last_login',
                        ($auth_user->last_login_date) ? $auth_user->last_login_date : null
                    );
                    $auth_success_path = "/main";
                    if ($auth_user->last_login_date) {
                        $landingUrl        = base_url() . 'start';
                        $auth_success_path = $landingUrl;
                        $data              = "{'status' : '1', 'login_err':'', 'password_err':'', 'auth_success_path':'" . $auth_success_path . "' }";
                    } else {
                        $data = "{'status' : '2', 'login_err':'', 'password_err':'', 'auth_success_path':'" . $auth_success_path . "' }";
                    }
                } else {
                    $data = "{'status' : '0', 'login_err':'Логин не верный', 'password_err':'Пароль не верный' }";
                }
            } else {
                $data = "{'status' : '-1',
                      'login_err':'" . $this->form_validation->error('login', '<span>', '</span>') . "',
                      'password_err':'" . $this->form_validation->error('password', '<span>', '</span>') . "'
                      }";
            }
            return $data;
        }

        function authorize_step2()
        {
            $user_id         = $this->db_session->userdata('user_id');
            $user_last_login = $this->db_session->userdata('user_last_login');
            if ($user_id && !$user_last_login) {
                $this->load->model('user_mdl', 'user');
                $data_user = array('last_login_date' => date("Y-m-d H:i:s"));
                $this->user->update_user($user_id, $data_user);
            }
        }

        function forgot_password($username)
        {
            if (empty($username)) {
                return false;
            }
            $this->load->model('user_mdl', 'user');
            $user = $this->user->get_user_by_email($username);
            $error_msg = "";
            if ($user) {
                $user_email = null;
                if (!empty($user->user_email)) {
                    $user_email = $user->user_email;
                } else {
                    return $error_msg = '<span style="font-size:12px;color:#FF0000;">Error - we do not have an email associated with that account. Please call 617-313-3020 to reset your password</span>';
                }
                $new_password      =
                    chr(
                        rand(
                            ord('a'),
                            ord('z')
                        )
                    ) . rand(1, 9) . rand(ord('a'), ord('z')) .
                    rand(1, 9) . rand(1, 9) .
                    rand(
                        ord('a'),
                        ord('z')
                    );
                $new_password_rand = md5($new_password . self::PRIVATE_KEY);
                $data_user = array(
                    'user_password' => $new_password_rand
                );
                $this->user->update_user($user->user_id, $data_user);
                $this->load->helper('email');
                $email   = $user_email;
                $subject = "Bezonasnost.com.ua";
                $message = "Ваш пароль был изменен: " . $new_password;
                $message .= ". Вы можете залогиниться на сайте снова: " . base_url();
                $email_cfg = array('mailtype' => 'html', 'wordwrap' => false);
                if (send_email($email, $subject, $message, $email_cfg)) {
                    $error_msg = '<span style="font-size:12px">Новый пароль отправлен вам на почту</span>';
                }
            } else {
                $error_msg = '<span style="font-size:12px;color:#FF0000;">Ошибка - пользователь с таким email не существует.</span>';
            }
            return $error_msg;
        }

        function ajax_actions()
        {
            $action = $this->input->post('action');
            $data = '';
            switch ($action) {
                case "authorize":
                    $login    = $this->input->post('login');
                    $password = $this->input->post('password');
                    $data     = $this->authorize($login, $password);
                    break;
                case "authorize_step2":
                    $this->authorize_step2();
                    $data = true;
                    break;
                case "upgrade_account":
                    $this->load->helper('email');
                    $this->load->model('dcontent_mdl', 'dcontent');
                    $this->config->load('clickfuel');
                    $dcId       = $this->input->post('id');
                    $user_login = $this->db_session->userdata('user_login');
                    $dc         = $this->dcontent->get_dc($dcId);
                    $email      = $this->config->item('dc_email');;
                    $subject = $dc->dc_title;
                    $message = $dc->dc_text;
                    $message .= "<br/>Client $user_login is interested in this offer";
                    $email_cfg = array('mailtype' => 'html', 'wordwrap' => false);
                    send_email($email, $subject, $message, $email_cfg);
                    break;
                case "submit_feedback":
                    $message = $this->input->post('feedback_note');
                    $this->load->helper('email');
                    $email   = "bf@clickfuel.com";
                    $subject = "Beta Feedback";
                    if (send_email($email, $subject, $message)) {
                        $data = "&nbsp;<b>Thank you</b> for your feeback.";
                    }
                    break;
                case "forgot_password":
                    $username = $this->input->post('username');
                    $data     = $this->forgot_password($username);
                    break;
            }
            $this->output->set_output($data);
        }

        function logout()
        {
            if ($this->db_session) {
                $_user = $this->db_session->userdata('user_id');
                if ($_user != false) {
                    $this->db_session->unset_userdata('user_id');
                    $this->db_session->unset_userdata('acc_user_id');
                    $this->db_session->unset_userdata('user_login');
                    $this->db_session->unset_userdata('user_role');
                    $this->db_session->unset_userdata('user_first');
                    $this->db_session->unset_userdata('user_last');
                    $this->db_session->unset_userdata('user_last_login');
                    $this->db_session->unset_userdata('user_agency_id');
                    $keep   = array('session_id', 'ip_address', 'user_agent', 'last_activity', 'impersonate');
                    $remove = array();
                    $sess   = $this->db_session->userdata();
                    foreach ($sess as $k => $v) {
                        if (!in_array($k, $keep)) {
                            $remove[$k] = true;
                        }
                    }
                    $this->db_session->unset_userdata($remove);
                }
            }
            redirect('admin');
        }
    }
    /* end of file */