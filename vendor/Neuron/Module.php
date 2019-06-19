<?php

namespace Neuron;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Module { //implements ServiceLocatorAwareInterface {

    protected $_module_dir;
    protected $_module_dirs;
    protected $_source_dir;
    protected $_source_dirs;
    protected $_separator = '/';

    public function __construct() {

        /* separator */
        $this->_separator = strstr(__DIR__, '/') ? '/' : '\\';

        /* get module dir */
        $this->_module_dir = __DIR__;

        /* get source dir */
        $part = explode($this->_separator, $this->_module_dir);
        $count = count($part);
        if ($count > 3) {
            $name = end($part);
            unset($part[$count - 1]);
            unset($part[$count - 2]);
            unset($part[$count - 3]);
            $dir = implode($this->_separator, $part);
        } else {
            $name = 'Neuron';
        }
        $this->_source_dir = $dir . $this->_separator . 'src' . $this->_separator . $name;

        /* scan module dirs */
        $this->_module_dirs = scandir($this->_module_dir);

        /* scan source dirs */
        if (is_dir($this->_source_dir)) {
            $this->_source_dirs = scandir($this->_source_dir);
        } else {
            $this->_source_dirs = array();
        }
    }

    public function onBootstrap($e) {

        /* ???
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e) {

            /* set module layout khusus engine CRM neuron * /
            $config = $e->getApplication()->getServiceManager()->get('config');
            $controller = $e->getTarget();
            if (isset($config['view_manager']['template_name'])) {
                $list = $config['view_manager']['template_name'];
                $class = get_class($controller);
                foreach ($list as $namespace => $name) {
                    echo("$class $namespace");
                    if (strpos($class, $namespace) === 0) {
                        $controller->layout($name);
                        break;
                    }
                }
            }
        }, 100);
        */
    }

    public function getConfig() {

        /* init config object */
        $config = new \Zend\Config\Config(include('Module.config.php'));

        /* init module and src dirs */
        $dirs = array(
            $this->_module_dir => $this->_module_dirs,
            $this->_source_dir => $this->_source_dirs,
        );

        /* autoload configs from module and src */
        foreach ($dirs as $dir => $list) {
            foreach ($list as $item) {
                if (is_dir($dir . '/' . $item) && ($item != '.') && ($item != '..')) {

                    /* merge all config from modules */
                    $file = $dir . '/' . $item . '/Config.php';
                    if (file_exists($file)) {
                        $config->merge(new \Zend\Config\Config(include $file));
                    }

                } //dircheck
            } //loop module dir
        } //loop dir

        //echo('<pre>');
        //print_r($config->toArray());
        //die('wow');

        /* return merged module config */
        return $config->toArray();
    }

    public function getAutoloaderConfig() {

        $namespaces = array();

        /* init module and src dirs */
        $dirs = array(
            $this->_module_dir => $this->_module_dirs,
            $this->_source_dir => $this->_source_dirs,
        );

        /* load modules and src namespaces */
        foreach ($dirs as $dir => $list) {
            foreach ($list as $item) {
                if (is_dir($dir . '/' . $item) && ($item != '.') && ($item != '..')) {
                    $namespaces[__NAMESPACE__ . '\\' . $item] = $dir . '/' . $item;
                }
            }
        }

        /* return namespaces */
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => $namespaces,
            ),
        );
    }

}
