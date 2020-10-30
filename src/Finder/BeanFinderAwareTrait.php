<?php


namespace Niceshops\Bean\Finder;


trait BeanFinderAwareTrait
{
    /**
     * @var BeanFinderInterface
     */
    private $beanFinder = null;

    /**
    * @return BeanFinderInterface
    */
    public function getBeanFinder(): BeanFinderInterface
    {
        return $this->beanFinder;
    }

    /**
    * @param BeanFinderInterface $beanFinder
    *
    * @return $this
    */
    public function setBeanFinder(BeanFinderInterface $beanFinder)
    {
        $this->beanFinder = $beanFinder;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasBeanFinder(): bool
    {
        return $this->beanFinder !== null;
    }
}
