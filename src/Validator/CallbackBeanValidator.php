<?php


namespace Pars\Bean\Validator;


use Pars\Bean\Processor\BeanProcessorInterface;
use Pars\Bean\Type\Base\BeanInterface;

class CallbackBeanValidator implements BeanValidatorInterface
{
    protected string $name;
    protected $callback;

    /**
     * CallbackBeanValidator constructor.
     * @param string $name
     */
    public function __construct(string $name, callable $callback)
    {
        $this->name = $name;
        $this->callback = $callback;
    }

    /**
     * @param BeanProcessorInterface $processor
     * @param BeanInterface $bean
     * @return bool
     */
    public function validate(BeanProcessorInterface $processor, BeanInterface $bean): bool
    {
        return ($this->callback)($bean, $processor) === true;
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return $this->name;
    }

}
