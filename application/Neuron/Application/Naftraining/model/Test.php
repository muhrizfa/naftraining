<?php
namespace Neuron\Application\Naftraining;

use Neuron\Generic\Result;

class Test {

    public function getName() {

        return 'Naftraining';
    }

    public function getTitle() {

        return 'NAF Training 01';
    }

    public function getTagline() {

        return 'Pembuatan NAF Training 01';
    }

    public function getResult() {

        $result = new Result(time(), Result::CODE_SUCCESS, 'Test success!');
        $result->data = array(
            'name' => $this->getName(),
            'title' => $this->getTitle(),
            'tagline' => $this->getTagline(),
        );
        return $result;
    }
}