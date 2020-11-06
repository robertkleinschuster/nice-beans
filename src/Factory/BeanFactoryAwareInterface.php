<?php

declare(strict_types=1);

namespace Niceshops\Bean\Factory;

/**
 * Interface BeanFactoryAwareInterface
 * @package Niceshops\Bean\Factory
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
