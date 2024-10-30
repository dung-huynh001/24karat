<?php
namespace App\Http\Requests\Common;

use Exception;
use Illuminate\Support\MessageBag;
use Illuminate\Contracts\Validation\Validator;

class FormValidationException extends Exception
{
    protected $errors;
    public function __construct($message = "", Validator $validator = null, Exception $previous = null)
    {
        $this->errors = $validator ? $validator->errors() : new MessageBag();
        parent::__construct($message, 0, $previous);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}