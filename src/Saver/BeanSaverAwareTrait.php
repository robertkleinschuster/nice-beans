<?php

declare(strict_types=1);

namespace Pars\Bean\Saver;

/**
 * Trait BeanSaverAwareTrait
 * @package Pars\Bean\Saver
 */
trait BeanSaverAwareTrait
{

    private $beanSaver = null;

    /**
    * @return BeanSaverInterface
    */
    public function getBeanSaver(): BeanSaverInterface
    {
        return $this->beanSaver;
    }

    /**
    * @param BeanSaverInterface $beanSaver
    *
    * @return $this
    */
    public function setBeanSaver(BeanSaverInterface $beanSaver)
    {
        $this->beanSaver = $beanSaver;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasBeanSaver(): bool
    {
        return $this->beanSaver !== null;
    }
}
