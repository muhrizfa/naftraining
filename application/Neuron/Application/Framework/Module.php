<?php
namespace Neuron\Application\Framework;   //namespace

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Neuron\Application\Framework\Hook;

class Module {

    protected $_primitive = false;

    public function __construct() {

        /* set primitive mode based on entity existence */
        if (!class_exists('Neuron\Application\Framework\Entity')) {
            $this->_primitive = true;
        }
        if (!defined('NAF_PRIMITIVE')) define('NAF_PRIMITIVE', $this->_primitive);
    }

    public function onBootstrap(MvcEvent $e) {

        /* init event manager & route listener */
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        /* bootstrap naf if not primitive mode */
        if (!$this->_primitive) Entity::bootstrap(__DIR__, $e->getApplication()->getServiceManager()->get('Config'));
    }

    public function getConfig() {

        /* load global and naf config */
        $files = array(
            __DIR__ . '/config/autoload/global.php',
            __DIR__ . '/config/autoload/naf.php',
            __DIR__ . '/config/autoload/module.php',
            __DIR__ . '/config/autoload/theme.php',
        );
        $autoload = array();
        /* scan module config first */
        $moduleDir = __DIR__ . '/config/autoload/module';
        $modules = file_exists($moduleDir) ? scandir($moduleDir) : array();
        foreach ($modules as $module) {
            if ($module != '.' && $module != '..') {
                if(is_file($moduleDir . '/' . $module)){
                    $temp = include($moduleDir . '/' .$module);
                    if (is_array($temp)) $autoload = array_merge_recursive($autoload, $temp);
                }
            }
        }
        /* override config with global */
        foreach ($files as $file) {
            if (file_exists($file)) {
                $temp = include($file);
                if (is_array($temp)) $autoload = array_merge_recursive($autoload, $temp);
            }
        }

        /* get config file */
        $main = $this->_primitive ? 'primitive.config.php' : 'module.config.php';

        /* return config */
        if (count($autoload) > 0) {
            return array_merge_recursive(include(__DIR__ . '/config/' . $main), $autoload);
        } else {
            return include(__DIR__ . '/config/' . $main);
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

/* end Module class */