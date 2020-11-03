<?php
declare(strict_types=1);


namespace Niceshops\Bean\Factory;

/**
 * Trait BeanFactoryAwareTrait
 * @package Niceshops\Bean\Factory
 */
trait BeanFactoryAwareTrait
{
    /**
     * @var BeanFactoryInterface
     */
    private $beanFactory = null;

    /**
    * @return BeanFactoryInterface
    */
    public function getBeanFactory(): BeanFactoryInterface
    {
        return $this->beanFactory;
    }

    /**
    * @param BeanFactoryInterface $beanFactory
    *
    * @return $this
    */
    public function setBeanFactory(BeanFactoryInterface $beanFactory)
    {
        $this->beanFactory = $beanFactory;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasBeanFactory(): bool
    {
        return $this->beanFactory !== null;
    }


}
