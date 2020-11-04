<?php
declare(strict_types=1);

namespace Niceshops\Bean\Finder;

use Niceshops\Bean\Converter\ConverterBeanDecorator;
use Niceshops\Bean\Factory\BeanFactoryAwareInterface;
use Niceshops\Bean\Factory\BeanFactoryAwareTrait;
use Niceshops\Bean\Factory\BeanFactoryInterface;
use Niceshops\Bean\Loader\BeanLoaderAwareInterface;
use Niceshops\Bean\Loader\BeanLoaderAwareTrait;
use Niceshops\Bean\Loader\BeanLoaderInterface;
use Niceshops\Bean\Loader\LoaderBeanListDecorator;
use Niceshops\Bean\Type\Base\BeanException;
use Niceshops\Bean\Type\Base\BeanInterface;
use Niceshops\Bean\Type\Base\BeanListInterface;
use Niceshops\Core\Attribute\AttributeAwareInterface;
use Niceshops\Core\Attribute\AttributeAwareTrait;
use Niceshops\Core\Option\OptionAwareInterface;
use Niceshops\Core\Option\OptionAwareTrait;

/**
 * Class AbstractBeanFinderFactory
 * @package Niceshops\Library\Core
 */
abstract class AbstractBeanFinder implements BeanFinderInterface, BeanLoaderAwareInterface, BeanFactoryAwareInterface, OptionAwareInterface, AttributeAwareInterface
{
    use BeanLoaderAwareTrait;
    use BeanFactoryAwareTrait;
    use OptionAwareTrait;
    use AttributeAwareTrait;

    /**
     * @var int
     */
    private $limit = null;

    /**
     * @var int
     */
    private $offset = null;

    /**
     * @var BeanFinderLink[]
     */
    private $beanFinderLink_List = [];

    /**
     * AbstractBeanFinderFactory constructor.
     *
     * @param BeanLoaderInterface $loader
     * @param BeanFactoryInterface $factory
     */
    public function __construct(BeanLoaderInterface $loader, BeanFactoryInterface $factory)
    {
        $this->setBeanLoader($loader);
        $this->setBeanFactory($factory);
    }

    /**
     * @param BeanFinderInterface $beanFinder
     * @param string $field
     * @param string $linkFieldSelf
     * @param string $linkFieldRemote
     * @return $this|BeanFinderInterface
     */
    public function addLinkedFinder(BeanFinderInterface $beanFinder, string $field, string $linkFieldSelf, string $linkFieldRemote): BeanFinderInterface
    {
        $this->beanFinderLink_List[] = new BeanFinderLink($beanFinder, $field, $linkFieldSelf, $linkFieldRemote);
        return $this;
    }

    /**
     * @return BeanFinderLink[]
     */
    public function getLinkedFinderList(): array
    {
        return $this->beanFinderLink_List;
    }

    /**
     * @return bool
     */
    public function hasLinkedFinder(): bool
    {
        return is_array($this->beanFinderLink_List) && count($this->beanFinderLink_List) > 0;
    }

    /**
     * @return FinderBeanListDecorator
     * @throws BeanException
     */
    public function getBeanListDecorator(): FinderBeanListDecorator
    {
        $this->initLinkedFinder();
        return new FinderBeanListDecorator($this);
    }

    /**
     *
     */
    public function initLinkedFinder()
    {
        if ($this->hasLinkedFinder()) {
            foreach ($this->getLinkedFinderList() as $link) {
                $link->getBeanFinder()->initByValueList($link->getLinkFieldRemote(), $this->getBeanLoader()->preloadValueList($link->getLinkFieldSelf()));
            }
        }
    }

    /**
     * @param bool $fetchAllData
     * @return BeanListInterface
     */
    public function getBeanList(bool $fetchAllData = false): BeanListInterface
    {
        return $this->getBeanListDecorator()->toBeanList($fetchAllData);
    }

    /**
     * @param bool $fetchAllData
     * @return BeanInterface
     * @throws BeanException
     */
    public function getBean(bool $fetchAllData = false): BeanInterface
    {
        $beanList = $this->getBeanList($fetchAllData);
        $count = $beanList->count();
        if ($count !== 1) {
            throw new BeanException('Could not get single bean, bean list contains ' . $count . ' beans.');
        }
        return $beanList->offsetGet(0);
    }


    /**
     * @param string $field
     * @param array $valueList
     * @return $this
     */
    public function initByValueList(string $field, array $valueList)
    {
        $this->getBeanLoader()->initByValueList($field, $valueList);
        return $this;
    }

    /**
     * @param BeanInterface $bean
     * @return BeanInterface
     */
    public function initializeBeanWithAdditionlData(BeanInterface $bean): BeanInterface
    {
        return $bean;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->getBeanLoader()->count();
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function limit(int $limit, int $offset)
    {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return bool
     */
    public function hasLimit(): bool
    {
        return $this->limit !== null;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }


    /**
     * @return bool
     */
    public function hasOffset(): bool
    {
        return $this->offset !== null;
    }
}
