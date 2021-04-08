<?php

declare(strict_types=1);

namespace Pars\Bean\Finder;

/**
 * Class BeanFinderLink
 * @package Pars\Bean\Finder
 */
class BeanFinderLink implements BeanFinderAwareInterface
{
    use BeanFinderAwareTrait;

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $linkFieldSelf;

    /**
     * @var string
     */
    private $linkFieldRemote;

    /**
     * BeanFinderLink constructor.
     * @param BeanFinderInterface $beanFinder
     * @param string $field
     * @param string $linkFieldSelf
     * @param string $linkFieldRemote
     */
    public function __construct(BeanFinderInterface $beanFinder, string $field, string $linkFieldSelf, string $linkFieldRemote)
    {
        $this->setBeanFinder($beanFinder);
        $this->field = $field;
        $this->linkFieldSelf = $linkFieldSelf;
        $this->linkFieldRemote = $linkFieldRemote;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getLinkFieldSelf(): string
    {
        return $this->linkFieldSelf;
    }

    /**
     * @return string
     */
    public function getLinkFieldRemote(): string
    {
        return $this->linkFieldRemote;
    }
}
