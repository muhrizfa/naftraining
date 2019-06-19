<?php
namespace Neuron\Application\Naftraining;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module {

    public function onBootstrap(MvcEvent $e) {

        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        /* trigger onstart */
        if (class_exists('Neuron\Application\Framework\Hook\Manager')) {
            if ($manager = \Neuron\Application\Framework\Hook\Manager::get()) {
                $manager->trigger()->onStart();
            }
        }
    }

    public function getConfig() {

        /* load global config */
        $file = __DIR__ . '/config/autoload/global.php';
        if (file_exists($file)) {
            $global = include($file);
        } else {
            $global = array();
        }

        /* load module - route config */
        $module = include(__DIR__ . '/config/module.config.php');

        /* have home config on global? */
        if (array_key_exists('application', $global)) {
            $temp = $global['application'];
            if (array_key_exists('home', $temp)) {
                $temp = $temp['home'];
                if (is_array($temp)) {
                    $defaults = $module['router']['routes']['home']['options']['defaults'];
                    $module['router']['routes']['home']['options']['defaults'] = array_merge($defaults, $temp);
                }
            }
        }

        /* test home route */
        //print_r($module['router']['routes']['home']['options']['defaults']);
        //die();

        /* return config */
        if (is_array($global)) {
            return array_merge_recursive($global, $module);
        } else {
            return $module;
        }
    }

    public function getAutoloaderConfig() {

        /* Definisikan autoloader untuk controller spesifik aplikasi dan model aplikasi */
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ . '\Controller' => __DIR__ . '/controller',
                    __NAMESPACE__ => __DIR__ . '/model',
                ),
            ),
        );
    }

}