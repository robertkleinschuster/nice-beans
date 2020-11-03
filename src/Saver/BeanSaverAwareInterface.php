<?php
declare(strict_types=1);


namespace Niceshops\Bean\Saver;

/**
 * Interface BeanSaverAwareInterface
 * @package Niceshops\Bean\Saver
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
