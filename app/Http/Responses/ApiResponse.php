<?php
namespace App\Http\Responses;

class ApiResponse
{
    public $code;
    public $message;
    public $data;
    public function __construct($code, $message, $data)
    {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
    }

}