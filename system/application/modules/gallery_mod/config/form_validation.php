<?php
$config = array(
           'users/register_step2' => array(
                                    array(
                                            'field' => 'username',
                                            'label' => 'Username',
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
                                            'label' => 'Login',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'password',
                                            'label' => 'Password',
                                            'rules' => 'required'
                                         )
                                    )
               );

?>