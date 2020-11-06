<?php

declare(strict_types=1);

namespace Niceshops\Bean\Type\Base;

trait BeanAwareTrait
{

    /**
     * @var BeanInterface
     */
    private $bean = null;

    /**
    * @return BeanInterface
    */
    public function getBean(): BeanInterface
    {
        return $this->bean;
    }

    /**
    * @param BeanInterface $bean
    *
    * @return $this
    */
    public function setBean(BeanInterface $bean)
    {
        $this->bean = $bean;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasBean(): bool
    {
        return $this->bean !== null;
    }
}
