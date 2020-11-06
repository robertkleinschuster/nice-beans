<?php

declare(strict_types=1);

namespace Niceshops\Bean\Loader;

/**
 * Interface BeanLoaderAwareInterface
 * @package Niceshops\Bean\Loader
 */
interface BeanLoaderAwareInterface
{
    /**
     * @return BeanLoaderInterface
     */
    public function getBeanLoader(): BeanLoaderInterface;

    /**
     * @param BeanLoaderInterface $beanLoader
     *
     * @return $this
     */
    public function setBeanLoader(BeanLoaderInterface $beanLoader);

    /**
     * @return bool
     */
    public function hasBeanLoader(): bool;
}
