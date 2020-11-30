<?php


namespace Niceshops\Bean\Processor;


use Niceshops\Bean\Type\Base\BeanInterface;

interface MetaFieldHandlerInterface
{
    /**
     * @param BeanInterface $bean
     * @return BeanInterface
     */
    public function handle(BeanInterface $bean): BeanInterface;

    /**
     * unique identification
     * @return string
     */
    public function code(): string;
}
