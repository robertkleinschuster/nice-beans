<?php


namespace Pars\Bean\Validator;


use Pars\Bean\Processor\BeanProcessorInterface;
use Pars\Bean\Type\Base\BeanInterface;

interface BeanValidatorInterface
{
    /**
     * @param BeanProcessorInterface $processor
     * @param BeanInterface $bean
     * @return bool
     */
    public function validate(BeanProcessorInterface $processor, BeanInterface $bean): bool;

    /**
     * unique identification
     * @return string
     */
    public function code(): string;
}
