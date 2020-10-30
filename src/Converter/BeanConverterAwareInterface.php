<?php
declare(strict_types=1);

namespace Niceshops\Bean\Converter;


interface BeanConverterAwareInterface
{
    /**
     * @return BeanConverterInterface
     */
    public function getBeanConverter(): BeanConverterInterface;

    /**
     * @param BeanConverterInterface $beanConverter
     *
     * @return $this
     */
    public function setBeanConverter(BeanConverterInterface $beanConverter);

    /**
     * @return bool
     */
    public function hasBeanConverter(): bool;
}
