<?php


namespace Pars\Bean\Validator;


use Pars\Bean\Processor\BeanProcessorInterface;
use Pars\Bean\Type\Base\BeanInterface;

class NotEmptyBeanValidator implements BeanValidatorInterface
{
    public const CODE = 'not_empty';

    /**
     * @param BeanProcessorInterface $processor
     * @param BeanInterface $bean
     * @return bool
     */
    public function validate(BeanProcessorInterface $processor, BeanInterface $bean): bool
    {
        return $bean->count() > 0;
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return self::CODE;
    }
}
