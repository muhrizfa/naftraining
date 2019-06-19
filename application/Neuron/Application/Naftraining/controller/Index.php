<?php
namespace Neuron\Application\Naftraining\Controller;

use Zend\View\Model\ViewModel;
use Neuron\Application\Naftraining\Test;
use Neuron\Application\Framework\Entity;

class Index extends Controller {

    /* sample action with view */
    public function indexAction() {

        /* allow access without login */
        Entity::publish();

        $test = new Test();
        $view = new ViewModel();
        $view->setVariables(array(
            'name' => $test->getName(),
            'title' => $test->getTitle(),
            'tagline' => $test->getTagline(),
        ));

        /* change content to login form when not logged in */
        $entity = Entity::get();
        if (!$entity->isInside()) $view->setTemplate('neuron/naftraining/controller/index/login');

        return $view;
    }

    /* sample action with JSON output */
    public function jsonAction() {

        /* allow access without login */
        Entity::publish();

        $test = new Test();
        $result = $test->getResult();

        return $this->getREST()->getResponse($result);
    }
}