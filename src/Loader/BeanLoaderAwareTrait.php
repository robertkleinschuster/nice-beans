<?php

declare(strict_types=1);

namespace Pars\Bean\Loader;

/**
 * Trait BeanLoaderAwareTrait
 * @package Pars\Bean\Loader
 */
trait BeanLoaderAwareTrait
{
    /**
     * @var BeanLoaderInterface
     */
    private $beanLoader = null;

    /**
    * @return BeanLoaderInterface
    */
    public function getBeanLoader(): BeanLoaderInterface
    {
        return $this->beanLoader;
    }

    /**
    * @param BeanLoaderInterface $beanLoader
    *
    * @return $this
    */
    public function setBeanLoader(BeanLoaderInterface $beanLoader)
    {
        $this->beanLoader = $beanLoader;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasBeanLoader(): bool
    {
        return $this->beanLoader !== null;
    }
}
