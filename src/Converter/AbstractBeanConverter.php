<?php
declare(strict_types=1);

namespace Niceshops\Bean\Converter;


use Niceshops\Bean\Type\Base\BeanInterface;

abstract class AbstractBeanConverter implements BeanConverterInterface
{
    /**
     * @param BeanInterface $bean
     * @return BeanDecorator
     */
    public function convert(BeanInterface $bean): BeanDecorator
    {
        return new BeanDecorator($bean, $this);
    }
}
