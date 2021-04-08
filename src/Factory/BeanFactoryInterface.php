<?php

declare(strict_types=1);

namespace Pars\Bean\Factory;

use Pars\Bean\Type\Base\BeanInterface;
use Pars\Bean\Type\Base\BeanListInterface;

/**
 * Interface BeanFactoryInterface
 * @package Pars\Bean\BeanFactory
 */
interface BeanFactoryInterface
{


    /**
     * @param array $data
     * @return BeanInterface
     */
    public function getEmptyBean(array $data): BeanInterface;

    /**
     * @return BeanListInterface
     */
    public function getEmptyBeanList(): BeanListInterface;
}
