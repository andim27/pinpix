<?php
$config = array(
           'users/register_full' => array(
                                    array(
                                            'field' => 'username',
                                            'label' => ' «'.lang('bibb_nik').'» ',
                                            'rules' => 'required|min_length[5]|callback_login_in_use_validation'
                                         ),
                                    array(
                                            'field' => 'email',
                                            'label' => ' «'.lang('bibb_email').'» ',
                                            'rules' => 'required|valid_email|callback_email_in_use_validation'
                                         ),
                                    array(
                                            'field' => 'pass1',
                                    		'label' => ' «'.lang('bibb_password').'» ',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'pass2',
                                    		'label' => ' «'.lang('bibb_password_confirm').'» ',
                                            'rules' => 'required|matches[pass1]'
                                         )
                                    ),
			'users/remember'       =>array(
                                     array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'required|valid_email'
										  )
                                   ),
			'users/register_step2' => array(
                                    array(
                                            'field' => 'username',
                                            'label' => 'Username',
                                            'rules' => 'required|min_length[5]|callback_login_in_use_validation'
                                         ),
                                    array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'required|valid_email|callback_email_in_use_validation'
                                         )
                                    ),
           'users/authorize' => array(
                                    array(
                                            'field' => 'login',
                                            'label' => 'Login',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'password',
                                            'label' => 'Password',
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
                                    ),
			'users/profile_login' => array(
                                    array(
                                            'field' => 'user_login',
                                            'label' => lang("user_nic-fio"),
                                            'rules' => 'required|min_length[5]|callback_login_in_use_validation'
                                         )
                                    
                                    ),
               );

?>