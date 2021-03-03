<?php

declare(strict_types=1);

namespace Niceshops\Bean\Finder;

use Niceshops\Bean\Factory\BeanFactoryAwareInterface;
use Niceshops\Bean\Factory\BeanFactoryAwareTrait;
use Niceshops\Bean\Factory\BeanFactoryInterface;
use Niceshops\Bean\Loader\BeanLoaderAwareInterface;
use Niceshops\Bean\Loader\BeanLoaderAwareTrait;
use Niceshops\Bean\Loader\BeanLoaderInterface;
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
abstract class AbstractBeanFinder implements
    BeanFinderInterface,
    BeanLoaderAwareInterface,
    BeanFactoryAwareInterface,
    OptionAwareInterface,
    AttributeAwareInterface
{
    use BeanLoaderAwareTrait;
    use BeanFactoryAwareTrait;
    use OptionAwareTrait;
    use AttributeAwareTrait;

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
     * @return $this
     */
    public function reset(): self
    {
        $this->getBeanLoader()->reset();
        return $this;
    }

    /**
     *
     * @param BeanFinderInterface $beanFinder
     * @param string $field
     * @param string $linkFieldSelf
     * @param string $linkFieldRemote
     * @return $this|BeanFinderInterface
     */
    public function addLinkedFinder(
        BeanFinderInterface $beanFinder,
        string $field,
        string $linkFieldSelf,
        string $linkFieldRemote
    ): BeanFinderInterface {
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
                $link->getBeanFinder()->initByValueList(
                    $link->getLinkFieldRemote(),
                    $this->preloadValueList($link->getLinkFieldSelf())
                );
            }
        }
    }

    /**
     * @param string $field
     * @return array
     */
    public function preloadValueList(string $field): array
    {
        if ($this->hasBeanLoader()) {
            return $this->getBeanLoader()->preloadValueList($field);
        }
        return [];
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
        if ($this->hasBeanLoader()) {
            $this->getBeanLoader()->filter([$field => $valueList], self::FILTER_MODE_AND);
        }
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
        if ($this->hasBeanLoader()) {
            return $this->getBeanLoader()->count();
        }
        return 0;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function limit(int $limit, int $offset)
    {
        if ($this->hasBeanLoader()) {
            $this->getBeanLoader()->limit($limit, $offset);
        }
        return $this;
    }

    /**
     * @param string $search
     * @param array|null $field_List
     * @return $this|mixed
     */
    public function search(string $search, array $field_List = null)
    {
        if ($this->hasBeanLoader()) {
            $this->getBeanLoader()->search($search, $field_List);
        }
        return $this;
    }

    /**
     * @param  array $field_List
     * @return $this|mixed
     */
    public function order(array $field_List)
    {
        if ($this->hasBeanLoader()) {
            $this->getBeanLoader()->order($field_List);
        }
        return $this;
    }

    /**
     * @param array $data_Map
     * @param string $mode
     * @return $this|mixed
     */
    public function filter(array $data_Map, string $mode = self::FILTER_MODE_AND)
    {
        if ($this->hasBeanLoader()) {
            $this->getBeanLoader()->filter($data_Map, $mode);
        }
        return $this;
    }

    /**
     * @param array $data_Map
     * @return $this|mixed
     */
    public function exclude(array $data_Map)
    {
        if ($this->hasBeanLoader()) {
            $this->getBeanLoader()->exclude($data_Map);
        }
        return $this;
    }

    /**
     *
     */
    public function __clone()
    {
        $this->setBeanLoader(clone $this->getBeanLoader());
    }


}
