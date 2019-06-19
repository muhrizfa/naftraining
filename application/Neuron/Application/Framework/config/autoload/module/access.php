<?php
return array(
    'naftraining' => array(
        'access' => array(
            'formlayout' => 'neuron/access/layout/form',
            'restlayout' => 'neuron/access/layout/rest',
            'viewprefix' => 'neuron/access/form',
            'permissions' => array(
                1 => 'view',
                2 => 'modify',
                3 => 'execute',
                4 => 'all',
            ),
            'session' => array(
                'mode' => 'default',
                'name' => 'NeuronApp',
                'regenerate' => false,
                'strict' => false,
                'table' => 'acc_sessions',
                'lifetime' => 3600,
                'remember' => 604800,
                'adapter' => null,
                'server' => '127.0.0.1:11211',
                'domain' => null,
                'secure' => null,
                'autostart' => false,
                'mellon' => false,
            ),
        ),
        'tables' => array(
            'access_grant_types' => 'acc_access_grant_types',
            'access_grants' => 'acc_access_grants',
            'access_object_types' => 'acc_access_object_types',
            'access_objects' => 'acc_access_objects',
            'access_permissions' => 'acc_access_permissions',
            'access_roles' => 'acc_access_roles',
            'access_levels' => 'acc_access_levels',
            'sessions' => 'acc_sessions',
        ),
    ),
);
