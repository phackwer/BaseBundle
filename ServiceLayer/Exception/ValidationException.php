<?php

namespace SanSIS\Core\BaseBundle\ServiceLayer\Exception;

class ValidationException extends \Exception
{
    private $validations = array();

    public function __construct($validations = array(), $message = "", $code = 0, Exception $previous = null) {
        $this->setValidations($validations);
    }

    public function getValidations(){
        return $this->validations;
    }

    public function setValidations ($validations) {
        $this->validations = $validations;
        return $this;
    }
}