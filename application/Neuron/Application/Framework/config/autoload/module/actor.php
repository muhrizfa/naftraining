<?php
return array(
    'naftraining' => array(
        'actor' => array(
            'formlayout' => 'neuron/actor/layout/form',
            'restlayout' => 'neuron/actor/layout/rest',
            'viewprefix' => 'neuron/actor/form',
            'user' => array(
                'commands' => array(),
                'languages' => array(
                    'id' => 'Bahasa',
                    'en' => 'English',
                ),
                'authentications' => array(
                    1 => 'Database',
                    2 => 'LDAP',
                    3 => 'Token',
                ),
                'facebook' => array(
                    'id' => '234448563876370',
                    'secret' => '24e20fe570391c88f50d315885048342',
                ),
                'google' => array(
                    'web' => array(
                        'client_id' => '763713885521-3i82ogutctr9siir02l0ip9e2ijqm2hh.apps.googleusercontent.com',
                        'project_id' => 'neuron-library',
                        'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
                        'token_uri' => 'https://www.googleapis.com/oauth2/v3/token',
                        'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
                        'client_secret' => 'wLyYR1YS1wolr3J25f58JMDM',
                        'redirect_uris' => array(
                            0 => 'http://localhost/git/actor/sample/public/index/google-verify',
                        ),
                    ),
                ),
                'activation' => 'none',
                'unique' => 'code, email',
                'password' => array(
                    'autogenerate' => false,
                    'hash' => 'password_hash',
                    'encrypted' => false,
                    'stored' => 0,
                    'age_min' => 0,
                    'age_max' => 999999,
                    'length' => 6,
                    'complex' => false,
                    'format' => '/^(?=[^A-Z]*[A-Z])(?=[^a-z]*[a-z])(?=[^\\d]*\\d)(?=[^!@#$%^&*()]*[!@#$%^&*()])|nousername|[a-zA-Z\\d!@#$%^&*()]{0,}$/',
                    'format_info' => 'Password harus mengandung minimal masing-masing 1 karakter uppercase, lowercase, angka, simbol dan tidak boleh mengandung code user',
                ),
                'attempts' => 0,
            ),
        ),
        'tables' => array(
            'users' => 'act_users',
            'user_status' => 'act_user_statuses',
            'password_history' => 'act_password_history',
        ),
    ),
);
