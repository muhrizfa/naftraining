<?php
namespace Neuron\Application\Framework\Theme;

use Neuron\Application\Framework\Entity;

class Vanilla extends Module {

    public function onInit() {

        if ($entity = Entity::get()) {

            $entity->layout()->using('slidejs', 999);
        }
    }
}