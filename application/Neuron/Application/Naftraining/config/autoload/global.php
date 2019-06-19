<?php
return array(
    /* PHP config, override php.ini */
    'php' => array(
        'display_errors' => true,
        'error_reporting' => E_ALL,
    ),
    /* application config key -- for getConfig */
    'application' => array(
        'config' => 'naftraining',
        'home' => array(
            '__NAMESPACE__' => 'Neuron\Application\naftraining\Controller',
            'controller' => 'Index',
            'action' => 'index',
        ),
    ),
    /* Application specific config */
    'naftraining' => array(
        /* db config */
        'databases' => array(
            'primary' => array(
                'driver' => 'Pdo',
                'host' =>  'localhost',
                'port' => '3306',
                'username' => 'training',
                'password' => 'training',
                'schema' => 'db_naf',
                'schemas' => array(
                    '\\' => 'db_naf',
                ),
            ),
        ),
        /* table map */
        'tables' => array(
            'config' => 'naf_config',
            'registers' => 'naf_registers',
        ),
        /* session config */
        'access' => array(
            'session' => array(
                'name' => 'naftraining',
                'mode' => 'default', //default, db, or cache
                'table' => 'acc_sessions',
            ),
        ),
        /* composite config */
        'composite' => array(
            'formlayout' => 'layout/inside',
            'restlayout' => 'neuron/composite/layout/rest',
            'viewprefix' => 'neuron/composite/form',
        ),
    ),
);