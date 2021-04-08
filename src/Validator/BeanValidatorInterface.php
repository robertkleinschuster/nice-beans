<?php


namespace Pars\Bean\Validator;


use Pars\Bean\Type\Base\BeanInterface;

interface BeanValidatorInterface
{
    /**
     * @param BeanInterface $bean
     * @return bool
     */
    public function validate(BeanInterface $bean): bool;

    /**
     * unique identification
     * @return string
     */
    public function code(): string;
}
