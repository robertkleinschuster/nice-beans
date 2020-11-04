<?php
declare(strict_types=1);

namespace Niceshops\Bean\Finder;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Niceshops\Bean\Factory\BeanFactoryAwareInterface;
use Niceshops\Bean\Factory\BeanFactoryAwareTrait;
use Niceshops\Bean\Loader\BeanLoaderAwareInterface;
use Niceshops\Bean\Loader\BeanLoaderAwareTrait;
use Niceshops\Bean\Type\Base\BeanException;
use Niceshops\Bean\Type\Base\BeanInterface;
use Niceshops\Bean\Type\Base\BeanListAwareInterface;
use Niceshops\Bean\Type\Base\BeanListAwareTrait;
use Niceshops\Bean\Type\Base\BeanListInterface;

/**
 * Class BeanLoaderDecorator
 * @package Niceshops\Bean\Finder
 */
class FinderBeanListDecorator implements BeanListInterface, IteratorAggregate, Countable, ArrayAccess, BeanListAwareInterface, BeanFinderAwareInterface
{
    use BeanListAwareTrait;
    use BeanFinderAwareTrait;
    use BeanLoaderAwareTrait;
    use BeanFactoryAwareTrait;

    /**
     * @var string
     */
    private $filterField = null;

    /**
     * @var string
     */
    private $filterValue = null;


    /**
     * BeanGenerator constructor.
     * @param BeanFinderInterface $finder
     * @throws BeanException
     */
    public function __construct(BeanFinderInterface $finder)
    {
        $this->setBeanFinder($finder);
        if ($finder instanceof BeanFactoryAwareInterface) {
            $this->setBeanList($finder->getBeanFactory()->getEmptyBeanList());
            $this->setBeanFactory($finder->getBeanFactory());
        } else {
            throw new BeanException('Could not get BeanList from BeanFinder!');
        }
        if ($finder instanceof BeanLoaderAwareInterface) {
            $this->setBeanLoader($finder->getBeanLoader());
        } else {
            throw new BeanException('Could not get BeanLoader from BeanFinder!');
        }
    }

    /**
     * @param string $field
     * @param $value
     * @return FinderBeanListDecorator
     */
    public function setFilter(string $field, $value): self
    {
        $this->filterField = $field;
        $this->filterValue = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasFilter(): bool
    {
        return !($this->filterField === null && $this->filterValue === null);
    }



    /**
     * Convert the BeanGenerator into a BeanList
     *
     * @param bool $recursive
     * @return BeanListInterface
     */
    public function toBeanList(bool $recursive = false): BeanListInterface
    {
        if ($this->getBeanList()->count() == 0) {
            foreach ($this as $bean) {
                if ($recursive) {
                    foreach ($bean as $key => $item) {
                        if ($item instanceof FinderBeanListDecorator) {
                            $bean->setData($key, $item->toBeanList($recursive));
                        }
                    }
                }
                $this->getBeanList()->addBean($bean);
            }
        }
        return $this->getBeanList();
    }

    /**
     * @return \Generator|\Traversable
     */
    public function getIterator()
    {
        $this->getBeanLoader()->execute();

        foreach ($this->getBeanLoader() as $data) {
            $bean = $this->getBeanLoader()->initializeBeanWithData($this->getBeanFactory()->getEmptyBean($data), $data);
            if (!$this->hasFilter() || $bean->getData($this->filterField) == $this->filterValue) {
                $this->getBeanFinder()->initializeBeanWithAdditionlData($bean);
                if ($this->getBeanFinder()->hasLinkedFinder()) {
                    foreach ($this->getBeanFinder()->getLinkedFinderList() as $link) {
                        $finder = $link->getBeanFinder();
                        $decorator = $finder->getBeanListDecorator()->setFilter($link->getLinkFieldRemote(), $bean->getData($link->getLinkFieldSelf()));
                        $bean->setData($link->getField(), $decorator);
                    }
                }
                yield $bean;
            }
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return BeanInterface
     */
    public function setData($name, $value)
    {
        return $this->toBeanList()->setData($name, $value);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getData($name)
    {
        return $this->toBeanList()->getData($name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasData($name)
    {
        return $this->toBeanList()->hasData($name);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function removeData($name)
    {
        return $this->toBeanList()->removeData($name);
    }

    /**
     * @return BeanInterface
     */
    public function resetData()
    {
        return $this->toBeanList()->resetData();
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getDataType($name)
    {
        return $this->toBeanList()->getDataType($name);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->toBeanList()->toArray();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function fromArray(array $data)
    {
        return $this->toBeanList()->fromArray($data);
    }

    /**
     * @param BeanInterface $bean
     * @return BeanListInterface
      */
    public function addBean(BeanInterface $bean)
    {
        return $this->toBeanList()->addBean($bean);
    }

    /**
     * @param $beans
     * @return BeanListInterface
     */
    public function addBeans($beans)
    {
        return $this->toBeanList()->addBeans($beans);
    }

    /**
     * @param BeanInterface $bean
     * @return BeanListInterface
     */
    public function removeBean(BeanInterface $bean)
    {
        return $this->toBeanList()->removeBean($bean);
    }

    /**
     * @param BeanInterface $bean
     * @return bool
     */
    public function hasBean(BeanInterface $bean)
    {
        return $this->toBeanList()->hasBean($bean);
    }

    /**
     * @param BeanInterface $bean
     * @return int
     */
    public function indexOfBean(BeanInterface $bean)
    {
        return $this->toBeanList()->indexOfBean($bean);
    }

    /**
     * @return array
     */
    public function getBeans(): array
    {
        return $this->toBeanList()->getBeans();
    }

    /**
     * @param $beans
     * @return BeanListInterface
     */
    public function setBeans($beans)
    {
        return $this->toBeanList()->setBeans($beans);
    }

    /**
     * @return BeanListInterface
     */
    public function resetBeans()
    {
        return $this->toBeanList()->resetBeans();
    }

    /**
     * @param int $offset
     * @param null $length
     * @param int $stepWidth
     * @return BeanListInterface
     */
    public function slice($offset = 0, $length = null, $stepWidth = 1)
    {
        return $this->toBeanList()->slice($offset, $length, $stepWidth);
    }

    /**
     * @param callable $callback
     * @return BeanListInterface
     */
    public function each(callable $callback)
    {
        return $this->toBeanList()->each($callback);
    }

    /**
     * @param callable $callback
     * @return BeanListInterface
     */
    public function every(callable $callback)
    {
        return $this->toBeanList()->every($callback);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->toBeanList()->isEmpty();
    }

    /**
     * @param callable $callback
     * @param bool $returnBeanList
     * @return BeanListInterface
     */
    public function some(callable $callback, $returnBeanList = false)
    {
        return $this->toBeanList()->some($callback, $returnBeanList);
    }

    /**
     * @param callable $callback
     * @return BeanListInterface
     */
    public function filter(callable $callback)
    {
        return $this->toBeanList()->filter($callback);
    }

    /**
     * @param callable $callback
     * @param bool $returnBean
     * @return BeanInterface
     */
    public function exclusive(callable $callback, $returnBean = false)
    {
        return $this->toBeanList()->exclusive($callback, $returnBean);
    }

    /**
     * @param callable $callback
     * @return array
     */
    public function map(callable $callback)
    {
        return $this->toBeanList()->map($callback);
    }

    /**
     * @param callable $callback
     * @return mixed
     */
    public function sort(callable $callback)
    {
        return $this->toBeanList()->sort($callback);
    }

    /**
     * @param $key1
     * @param int $order1
     * @param int $flags1
     * @return mixed
     */
    public function sortByData($key1, $order1 = SORT_ASC, $flags1 = SORT_REGULAR)
    {
        return $this->toBeanList()->sortByData($key1, $order1, $flags1);
    }

    /**
     * @param $key
     * @param int $flags
     * @return mixed
     */
    public function sortAscendingByKey($key, $flags = SORT_REGULAR)
    {
        return $this->toBeanList()->sortAscendingByKey($key, $flags);
    }

    /**
     * @param $key
     * @param int $flags
     * @return mixed
     */
    public function sortDescendingByKey($key, $flags = SORT_REGULAR)
    {
        return $this->toBeanList()->sortDescendingByKey($key, $flags);
    }

    /**
     * @return mixed
     */
    public function reverse()
    {
        return $this->toBeanList()->reverse();
    }

    /**
     * @param BeanInterface $bean
     * @return mixed
     */
    public function push(BeanInterface $bean)
    {
        return $this->toBeanList()->push($bean);
    }

    /**
     * @param BeanInterface $bean
     * @return mixed
     */
    public function unshift(BeanInterface $bean)
    {
        return $this->toBeanList()->unshift($bean);
    }

    /**
     * @return mixed
     */
    public function shift()
    {
        return $this->toBeanList()->shift();
    }

    /**
     * @return mixed
     */
    public function pop()
    {
        return $this->toBeanList()->pop();
    }

    /**
     * @param string $dataName
     * @return array
     */
    public function countValues_for_DataName(string $dataName): array
    {
        return $this->toBeanList()->pop();
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->toBeanList()->offsetExists($offset);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->toBeanList()->offsetGet($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->toBeanList()->offsetSet($offset, $value);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        $this->toBeanList()->offsetUnset($offset);
    }

    /**
     * @param bool $countData
     * @return int
     */
    public function count(bool $countData = false)
    {
        if ($countData) {
            return $this->toBeanList()->count();
        }
        return $this->getBeanLoader()->count();
    }
}
