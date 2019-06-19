<?php
return array(

    /* konfigurasi service manager, belum digunakan */
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),

    /* konfigurasi translator bahasa */
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language', /* lokasi file-file bahasa */
                'pattern' => '%s.mo',
            ),
        ),
    ),

    /* konfigurasi view manager */
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'error/404' => __DIR__ . '/../../view/error/404.phtml',
            'error/index' => __DIR__ . '/../../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../../view',
        ),
    ),

);