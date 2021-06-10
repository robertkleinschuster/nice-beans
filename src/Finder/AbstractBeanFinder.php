<?php

declare(strict_types=1);

namespace Pars\Bean\Finder;

use Pars\Bean\Factory\BeanFactoryAwareTrait;
use Pars\Bean\Factory\BeanFactoryInterface;
use Pars\Bean\Loader\BeanLoaderAwareTrait;
use Pars\Bean\Loader\BeanLoaderInterface;
use Pars\Bean\Type\Base\BeanException;
use Pars\Bean\Type\Base\BeanInterface;
use Pars\Bean\Type\Base\BeanListInterface;
use Pars\Pattern\Attribute\AttributeAwareTrait;
use Pars\Pattern\Option\OptionAwareTrait;

/**
 * Class AbstractBeanFinderFactory
 * @package Pars\Library\Patterns
 */
abstract class AbstractBeanFinder implements
    BeanFinderInterface
{
    use BeanLoaderAwareTrait;
    use BeanFactoryAwareTrait;
    use OptionAwareTrait;
    use AttributeAwareTrait;

    /**
     * @var BeanFinderLink[]
     */
    private $beanFinderLink_List = [];
    private $decorator = null;

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
    ): BeanFinderInterface
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
        $this->handleLinkedFinder();
        return new FinderBeanListDecorator($this);
    }

    /**
     *
     */
    public function handleLinkedFinder()
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
    public function limit(int $limit, int $offset = 0)
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
     * @param array $field_List
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
     * @param string $key
     * @param $value
     * @param string $mode
     * @return $this
     */
    public function filterValue(string $key, $value, string $mode = self::FILTER_MODE_AND)
    {
        return $this->filter([$key => $value], $mode);
    }

    /**
     * @param FilterExpression $expression
     * @param string $mode
     * @return $this|mixed
     */
    public function filterExpression(FilterExpression $expression, string $mode = self::FILTER_MODE_AND)
    {
        return $this->filter([$expression], $mode);
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
     * @param string $key
     * @param $value
     * @return $this
     */
    public function excludeValue(string $key, $value)
    {
        return $this->exclude([$key => $value]);
    }

    /**
     *
     */
    public function __clone()
    {
        $this->setBeanLoader(clone $this->getBeanLoader());
    }

    public function lock()
    {
        if ($this->hasBeanLoader()) {
            $this->getBeanLoader()->lock();
        }
    }

}
