<?php
declare(strict_types=1);

namespace Niceshops\Bean\Type\Base;



/**
 * Interface BeanListAwareInterface
 * @package Niceshops\Bean\BeanList
 */
interface BeanListAwareInterface
{

    /**
     * @param BeanListInterface $beanList
     * @return $this
     */
    public function setBeanList(BeanListInterface $beanList);

}
