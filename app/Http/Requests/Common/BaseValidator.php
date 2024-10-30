<?php
namespace App\Http\Requests\Common;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Contracts\Validation\Validator as ValidationContract;

abstract class BaseValidator
{
    /**
     * @var ValidationContract
     */
    protected $validation;

    /**
     * @var ValidatorFactory
     */
    protected $validator;

    /**
     * @param ValidatorFactory $validator
     */
    public function __construct(ValidatorFactory $validator)
    {
        $this->validator = $validator;
    }

    public function validate(array $formData)
    {
        $this->validation = $this->validator->make($formData, $this->rules(), $this->messages());

        if ($this->validation->fails()) {
            throw new FormValidationException("Validation Failed", $this->validation);
        }

        return true;
    }

    protected function getValidationErrors()
    {
        return $this->validation->errors();
    }

    /**
     * Define validation rules in child class
     * @return array
     */
    abstract protected function rules();
    abstract protected function messages();
}
