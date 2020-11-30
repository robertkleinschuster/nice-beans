<?php


namespace Niceshops\Bean\Processor;


class OrderProcessorAwareTrait
{
    private ?BeanOrderProcessor $beanOrderProcessor = null;

    /**
     * @return BeanOrderProcessor
     */
    public function getBeanOrderProcessor(): BeanOrderProcessor
    {
        return $this->beanOrderProcessor;
    }

    /**
     * @param BeanOrderProcessor $beanOrderProcessor
     *
     * @return $this
     */
    public function setBeanOrderProcessor(BeanOrderProcessor $beanOrderProcessor)
    {
        $this->beanOrderProcessor = $beanOrderProcessor;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasBeanOrderProcessor(): bool
    {
        return isset($this->beanOrderProcessor);
    }


}
