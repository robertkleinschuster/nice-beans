<?php


namespace Niceshops\Bean\Validator;


use Niceshops\Bean\Type\Base\BeanInterface;

class FieldNotEmptyBeanValidator implements BeanValidatorInterface
{
    private string $field;

    /**
     * FieldNotEmptyBeanValidator constructor.
     * @param string $field
     */
    public function __construct(string $field)
    {
        $this->field = $field;
    }


    public function validate(BeanInterface $bean): bool
    {
        return !$bean->empty($this->field);
    }

    public function code(): string
    {
        return 'not_empty_' . $this->field;
    }

}
