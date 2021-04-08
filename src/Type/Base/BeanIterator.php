<?php


namespace Pars\Bean\Type\Base;


class BeanIterator extends \IteratorIterator
{
    /**
     * @return BeanInterface
     */
    public function current(): BeanInterface
    {
        return parent::current();
    }
}
