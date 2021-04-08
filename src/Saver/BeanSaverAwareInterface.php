<?php

declare(strict_types=1);

namespace Pars\Bean\Saver;

/**
 * Interface BeanSaverAwareInterface
 * @package Pars\Bean\Saver
 */
interface BeanSaverAwareInterface
{
    public function getBeanSaver(): BeanSaverInterface;
    /**
     * @param BeanSaverInterface $beanSaver
     *
     * @return $this
     */
    public function setBeanSaver(BeanSaverInterface $beanSaver);

    /**
     * @return bool
     */
    public function hasBeanSaver(): bool;
}
