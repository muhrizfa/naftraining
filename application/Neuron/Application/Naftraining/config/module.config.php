<?php
return array(
    /* module route */
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/naftraining',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Neuron\Application\Naftraining\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
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
            'login' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Neuron\Application\Naftraining\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),
            'logout' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Neuron\Application\Naftraining\Controller',
                        'controller' => 'Index',
                        'action' => 'logout',
                    ),
                ),
                'may_terminate' => true,
            ),
        ),
    ),
    /* controller namespace mapping */
    'controllers' => array(
        'invokables' => array(
            'Neuron\Application\Naftraining\Controller\Index' => 'Neuron\Application\Naftraining\Controller\Index',
        ),
    ),
    /* view map */
    'view_manager' => array(
        'template_map' => array(
            'layout/inside' => __DIR__ . '/../layout/inside.phtml',
            'layout/outside' => __DIR__ . '/../layout/outside.phtml',
            'neuron/naftraining/controller/index/index' => __DIR__ . '/../view/index/index.phtml',
            'neuron/naftraining/controller/index/login' => __DIR__ . '/../view/index/login.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);