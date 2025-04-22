<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    protected $errors;

    public function __construct(array $errors)
    {
        parent::__construct("Errores de validación");
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}