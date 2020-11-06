<?php

declare(strict_types=1);

namespace Niceshops\Bean\Type\Base;

/**
 * Trait BeanListAwareTrait
 * @package Niceshops\Bean\BeanList
 */
trait BeanListAwareTrait
{
    /**
     * @var BeanListInterface
     */
    private $beanList = null;

    /**
     * @return BeanListInterface
     */
    public function getBeanList(): BeanListInterface
    {
        return $this->beanList;
    }

    /**
     * @param mixed $beanList
     * @return $this
     */
    public function setBeanList(BeanListInterface $beanList)
    {
        $this->beanList = $beanList;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasBeanList(): bool
    {
        return null !== $this->beanList;
    }
}
