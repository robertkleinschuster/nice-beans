<?php


namespace Niceshops\Bean\Finder;


interface BeanFinderAwareInterface
{
    /**
     * @return BeanFinderInterface
     */
    public function getBeanFinder(): BeanFinderInterface;

    /**
     * @param BeanFinderInterface $beanFinder
     *
     * @return $this
     */
    public function setBeanFinder(BeanFinderInterface $beanFinder);

    /**
     * @return bool
     */
    public function hasBeanFinder(): bool;
}
