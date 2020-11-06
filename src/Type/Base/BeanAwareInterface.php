<?php

declare(strict_types=1);

namespace Niceshops\Bean\Type\Base;

interface BeanAwareInterface
{
    public function getBean(): BeanInterface;
    /**
     * @param BeanInterface $bean
     *
     * @return $this
     */
    public function setBean(BeanInterface $bean);

    /**
     * @return bool
     */
    public function hasBean(): bool;
}
