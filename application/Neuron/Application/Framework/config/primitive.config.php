<?php
return array(

    /* konfigurasi routing -- primitive */
    'router' => array(
        'routes' => array(
            'primitive' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Neuron\Application\Framework\Controller',
                        'controller' => 'Primitive',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    /* mapping namespace controller ke class controller, setiap controller harus didaftarkan disini */
    'controllers' => array(
        'invokables' => array(
            'Neuron\Application\Framework\Controller\Primitive' => 'Neuron\Application\Framework\Controller\Primitive',
        ),
    ),

    /* mapping action name ke file view, setiap action dan view harus didaftarkan disini */
    'view_manager' => array(
        'template_map' => array(
            'layout/primitive' => __DIR__ . '/../layout/primitive.phtml',
            'neuron/framework/controller/primitive/index' => __DIR__ . '/../view/primitive/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    /* git api config */
    'git' => array(
        'pac' => '7B5KPLDBgw8zTKUU54eB',
        'url' => 'https://git.neuron.id/api/v4',
    ),

);
