<?php
$config = array(
           'users/register_step2' => array(
                                    array(
                                            'field' => 'username',
                                            'label' => 'Имя пользователя',
                                            'rules' => 'required|min_length[5]|callback_login_in_use_validation'
                                         ),
                                    array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'required|valid_email'
                                         )
                                    ),
           'users/authorize' => array(
                                    array(
                                            'field' => 'login',
                                            'label' => 'Логин',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'password',
                                            'label' => 'Пароль',
                                            'rules' => 'required'
                                         )
                                    ),
            'users/profile_save' => array(
                                    array (
                                            'field' => 'user_name',
                                            'label' => lang('user_login'),
                                            'rules' => 'required|min_length[5]'
                                           ),
                                    array (
                                            'field' => 'user_email',
                                            'label' => lang('user_email'),
                                            'rules' => 'required|valid_email'
                                         ),
                                    array (
                                            'field' => 'birth_year',
                                            'label' => lang('user_birthdate'),
                                            'rules' => 'required|is_natural_no_zero'
                                         ),
                                    array (
                                            'field' => 'birth_month',
                                            'label' => lang('user_birthdate'),
                                            'rules' => 'required|is_natural_no_zero'
                                         ),
                                    array (
                                            'field' => 'birth_day',
                                            'label' => lang('user_birthdate'),
                                            'rules' => 'required|is_natural_no_zero'
                                         ),
                                    array (
                                            'field' => 'country',
                                            'label' => lang('user_country'),
                                            'rules' => 'required'
                                         )
                                    ),
            'users/profile_password' => array (
                                            'new_psw'     => "required",
                                            'confirm_psw' => "required"
                                    )
               );

?>