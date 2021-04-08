<?php


namespace Pars\Bean\Validator;


use Pars\Bean\Type\Base\BeanInterface;

class NotEmptyBeanValidator implements BeanValidatorInterface
{
    public const CODE = 'not_empty';

    public function validate(BeanInterface $bean): bool
    {
        return $bean->count() > 0;
    }

    public function code(): string
    {
        return self::CODE;
    }
}
