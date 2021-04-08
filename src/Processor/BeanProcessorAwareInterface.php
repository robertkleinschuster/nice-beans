<?php

declare(strict_types=1);

namespace Pars\Bean\Processor;

/**
 * Interface BeanProcessorAwareInterface
 * @package Pars\Bean\Processor
 */
interface BeanProcessorAwareInterface
{
    /**
     * @return BeanProcessorInterface
     */
    public function getBeanProcessor(): BeanProcessorInterface;

    /**
     * @param BeanProcessorInterface $beanProcessor
     *
     * @return $this
     */
    public function setBeanProcessor(BeanProcessorInterface $beanProcessor);

    /**
     * @return bool
     */
    public function hasBeanProcessor(): bool;
}
