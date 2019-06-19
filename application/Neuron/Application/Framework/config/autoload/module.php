<?php
return array(
    'modules' => array(
        'internal' => array(
            'zend' => array(
                'installed' => true,
                'mandatory' => true,
                'version' => 'v2.4.11',
                'folders' => array(),
                'name' => 'Zend',
                'id' => 42,
            ),
            'core' => array(
                'installed' => true,
                'mandatory' => true,
                'version' => 'v2.2.5',
                'folders' => array(
                    0 => 'Application',
                ),
                'name' => 'Core',
                'id' => 109,
            ),
            'generic' => array(
                'installed' => true,
                'mandatory' => true,
                'version' => 'v0.4.1',
                'folders' => array(
                    0 => 'Generic',
                ),
                'name' => 'Generic',
                'id' => 7,
            ),
            'mvc' => array(
                'installed' => true,
                'mandatory' => true,
                'version' => 'v1.1.0',
                'folders' => array(
                    0 => 'Mvc',
                ),
                'name' => 'MVC',
                'id' => 11,
                'dependency' => array(
                    0 => 'generic',
                ),
            ),
            'db' => array(
                'installed' => true,
                'mandatory' => true,
                'version' => 'v1.3.0',
                'folders' => array(
                    0 => 'Db',
                ),
                'name' => 'Database',
                'id' => 5,
                'dependency' => array(
                    0 => 'generic',
                ),
            ),
            'access' => array(
                'installed' => true,
                'mandatory' => true,
                'version' => 'v0.6.2',
                'folders' => array(
                    0 => 'Access',
                ),
                'name' => 'Access',
                'id' => 12,
                'dependency' => array(
                    0 => 'generic',
                    1 => 'mvc',
                ),
            ),
            'actor' => array(
                'installed' => true,
                'mandatory' => true,
                'version' => 'v0.7.0',
                'folders' => array(
                    0 => 'Actor',
                ),
                'name' => 'Actor',
                'id' => 8,
                'dependency' => array(
                    0 => 'generic',
                    1 => 'mvc',
                    2 => 'google',
                    3 => 'facebook',
                ),
            ),
            'composite' => array(
                'installed' => true,
                'mandatory' => true,
                'version' => 'v1.2.3',
                'folders' => array(
                    0 => 'Composite',
                    1 => 'Formatter',
                ),
                'name' => 'Composite',
                'id' => 32,
                'dependency' => array(
                    0 => 'generic',
                    1 => 'mvc',
                    2 => 'access',
                    3 => 'actor',
                ),
            ),
        ),
        'external' => array(
            'facebook' => array(
                'installed' => true,
                'mandatory' => false,
                'version' => 'v5.7.0',
                'folders' => array(
                    0 => 'Facebook',
                ),
                'name' => 'facebook',
                'id' => 'facebook',
                'url' => 'https://github.com/facebook/php-graph-sdk/archive/5.7.0.zip',
                'config' => array(
                    'dir' => 'php-graph-sdk-5.7.0\\src\\Facebook'
                )
            ),
            'google' => array(
                'installed' => true,
                'mandatory' => false,
                'version' => 'v2.2.2',
                'folders' => array(
                    0 => 'Google',
                ),
                'name' => 'google',
                'id' => 'google',
                'url' => 'https://github.com/googleapis/google-api-php-client/releases/download/v2.2.2/google-api-php-client-2.2.2.zip',
                'config' => array(
                    'dir' => 'google-api-php-client-2.2.2'
                )
            ),
        ),
    ),
);
