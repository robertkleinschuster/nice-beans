<?php


namespace Pars\Bean\Validator;


use Pars\Bean\Processor\BeanProcessorInterface;
use Pars\Bean\Type\Base\BeanInterface;

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

    /**
     * @param BeanProcessorInterface $processor
     * @param BeanInterface $bean
     * @return bool
     */
    public function validate(BeanProcessorInterface $processor, BeanInterface $bean): bool
    {
        return !$bean->empty($this->field);
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return 'not_empty_' . $this->field;
    }

}
