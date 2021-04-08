<?php

declare(strict_types=1);

namespace Pars\Bean\Type\Base;

/**
 * Interface BeanListAwareInterface
 * @package Pars\Bean\BeanList
 */
interface BeanListAwareInterface
{

    /**
     * @param BeanListInterface $beanList
     * @return $this
     */
    public function setBeanList(BeanListInterface $beanList);
}
