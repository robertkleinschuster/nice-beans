<?php
declare(strict_types=1);


namespace Niceshops\Bean\Processor;

/**
 * Trait BeanProcessorAwareTrait
 * @package Niceshops\Bean\Processor
 */
trait BeanProcessorAwareTrait
{
    private $beanProcessor = null;

    /**
     * @return BeanProcessorInterface
     */
    public function getBeanProcessor(): BeanProcessorInterface
    {
        return $this->beanProcessor;
    }

    /**
     * @param BeanProcessorInterface $beanProcessor
     *
     * @return $this
     */
    public function setBeanProcessor(BeanProcessorInterface $beanProcessor)
    {
        $this->beanProcessor = $beanProcessor;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasBeanProcessor(): bool
    {
        return $this->beanProcessor !== null;
    }

}
