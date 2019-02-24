<?php declare(strict_types=1);

namespace App\Services\Validation;

use App\Exceptions\ValidationException;
use Rakit\Validation\Validator as RakitValidator;

/**
 * Class Validator
 * @package App\Services\Validation
 */
final class Validator
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $rules;

    /**
     * @var RakitValidator
     */
    private $validator;

    /**
     * @param RakitValidator $validator
     */
    public function __construct(RakitValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param array $rules
     * @return $this
     */
    public function setRules(array $rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @throws ValidationException
     */
    public function validate(): void
    {
        $validation = $this->validator->validate($this->data, $this->rules);

        if ($validation->fails()) {
            throw new ValidationException($validation->errors());
        }
    }

    /**
     * @return RakitValidator
     */
    public function getValidator(): RakitValidator
    {
        return $this->validator;
    }
}