<?php

declare(strict_types=1);

namespace Pars\Bean\Factory;

/**
 * Interface BeanFactoryAwareInterface
 * @package Pars\Bean\Factory
 */
interface BeanFactoryAwareInterface
{
    /**
     * @return BeanFactoryInterface
     */
    public function getBeanFactory(): BeanFactoryInterface;

    /**
     * @param BeanFactoryInterface $beanFactory
     *
     * @return $this
     */
    public function setBeanFactory(BeanFactoryInterface $beanFactory);

    /**
     * @return bool
     */
    public function hasBeanFactory(): bool;
}
