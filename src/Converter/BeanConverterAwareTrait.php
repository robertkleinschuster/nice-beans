<?php

declare(strict_types=1);

namespace Pars\Bean\Converter;

/**
 * Trait BeanConverterAwareTrait
 * @package Pars\Bean\Converter
 */
trait BeanConverterAwareTrait
{
    /**
     * @var BeanConverterInterface
     */
    private $beanConverter = null;

    /**
    * @return BeanConverterInterface
    */
    public function getBeanConverter(): BeanConverterInterface
    {
        return $this->beanConverter;
    }

    /**
    * @param BeanConverterInterface $beanConverter
    *
    * @return $this
    */
    public function setBeanConverter(BeanConverterInterface $beanConverter)
    {
        $this->beanConverter = $beanConverter;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasBeanConverter(): bool
    {
        return $this->beanConverter !== null;
    }
}
