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
use Niceshops\Bean\Type\Base\BeanListException;
use Niceshops\Bean\Type\Base\BeanListInterface;
use Traversable;

/**
 * Class BeanLoaderDecorator
 * @package Niceshops\Bean\Finder
 */
class FinderBeanListDecorator implements
    BeanListInterface,
    BeanListAwareInterface,
    BeanFinderAwareInterface
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
                            $bean->set($key, $item->toBeanList($recursive));
                        }
                    }
                }
                $this->getBeanList()->push($bean);
            }
        }
        return $this->getBeanList();
    }

    /**
     * @return \Generator|\Traversable
     */
    public function getIterator()
    {
        if ($this->hasBeanLoader()) {
            $this->getBeanLoader()->execute();
            foreach ($this->getBeanLoader() as $data) {
                $bean = $this->getBeanLoader()->initializeBeanWithData(
                    $this->getBeanFactory()->getEmptyBean($data),
                    $data
                );
                if (!$this->hasFilter() || $bean->get($this->filterField) == $this->filterValue) {
                    $this->getBeanFinder()->initializeBeanWithAdditionlData($bean);
                    if ($this->getBeanFinder()->hasLinkedFinder()) {
                        foreach ($this->getBeanFinder()->getLinkedFinderList() as $link) {
                            $finder = $link->getBeanFinder();
                            $decorator = $finder->getBeanListDecorator()->setFilter(
                                $link->getLinkFieldRemote(),
                                $bean->get($link->getLinkFieldSelf())
                            );
                            $bean->set($link->getField(), $decorator);
                        }
                    }
                    yield $bean;
                }
            }
        }
    }


    /**
     * @param int $offset
     * @param int $length
     * @return BeanListInterface
     */
    public function slice(int $offset = 0, int $length = null): BeanListInterface
    {
        return $this->toBeanList()->slice($offset, $length);
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
     * @return BeanListInterface
     */
    public function filter(callable $callback = null): BeanListInterface
    {
        return $this->toBeanList()->filter($callback);
    }

    /**
     * @param callable $callback
     */
    public function map(callable $callback = null): BeanListInterface
    {
        return $this->toBeanList()->map($callback);
    }

    /**
     * @param callable $callback
     * @return mixed
     */
    public function sort(callable $callback = null): BeanListInterface
    {
        return $this->toBeanList()->sort($callback);
    }

    /**
     * @return mixed
     */
    public function reverse(): BeanListInterface
    {
        return $this->toBeanList()->reverse();
    }

    /**
     * @param BeanInterface[] $values
     * @return mixed
     */
    public function push(...$values): BeanListInterface
    {
        return $this->toBeanList()->push(...$values);
    }

    /**
     * @param BeanInterface[] $values
     * @return mixed
     */
    public function unshift(...$values): BeanListInterface
    {
        return $this->toBeanList()->unshift(...$values);
    }

    /**
     * @return mixed
     */
    public function shift(): BeanInterface
    {
        return $this->toBeanList()->shift();
    }

    /**
     * @return mixed
     */
    public function pop(): BeanInterface
    {
        return $this->toBeanList()->pop();
    }


    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
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
     * @return BeanListInterface
     */
    public function offsetSet($offset, $value): BeanListInterface
    {
        return $this->toBeanList()->offsetSet($offset, $value);
    }

    /**
     * @param mixed $offset
     * @return BeanListInterface
     */
    public function offsetUnset($offset): BeanListInterface
    {
        return $this->toBeanList()->offsetUnset($offset);
    }

    /**
     * @param bool $countData
     * @return int
     */
    public function count(bool $countData = false): int
    {
        if ($countData) {
            return $this->toBeanList()->count();
        }
        return $this->getBeanLoader()->count();
    }

    public function clear(): BeanListInterface
    {
        return $this->toBeanList()->clear();
    }

    public function copy(bool $recurive = false): BeanListInterface
    {
        return $this->toBeanList()->copy($recurive);
    }

    public function toArray(bool $recursive = false): array
    {
        return $this->toBeanList()->toArray($recursive);
    }

    public function allocate(int $capacity): BeanListInterface
    {
        return $this->toBeanList()->allocate($capacity);
    }

    public function apply(callable $callback): BeanListInterface
    {
        return $this->toBeanList()->apply($callback);
    }

    public function capacity(): int
    {
        return $this->toBeanList()->capacity();
    }

    public function contains(...$values): bool
    {
        return $this->toBeanList()->contains(...$values);
    }

    public function find($value)
    {
        return $this->toBeanList()->find($value);
    }

    public function first()
    {
        return $this->toBeanList()->first();
    }

    public function get(int $index)
    {
        return $this->toBeanList()->get($index);
    }

    public function insert(int $index, ...$values): BeanListInterface
    {
        return $this->toBeanList()->insert($index, ...$values);
    }

    public function join(string $glue = null): string
    {
        return $this->toBeanList()->join($glue);
    }

    public function last()
    {
        return $this->toBeanList()->last();
    }

    public function merge($values): BeanListInterface
    {
        return $this->toBeanList()->merge($values);
    }

    public function reduce(callable $callback, $initial = null)
    {
        return $this->toBeanList()->reduce($callback, $initial);
    }

    public function remove(int $index): BeanListInterface
    {
        return $this->toBeanList()->remove($index);
    }

    public function reversed(): BeanListInterface
    {
        return $this->toBeanList()->reversed();
    }

    public function rotate(int $rotations): BeanListInterface
    {
        return $this->toBeanList()->rotate($rotations);
    }

    public function set(int $index, $value): BeanListInterface
    {
        return $this->toBeanList()->set($index, $value);
    }

    public function sorted(callable $comparator = null): BeanListInterface
    {
        return $this->toBeanList()->sorted($comparator);
    }

    /**
     * @return float|int|void
     * @throws BeanListException
     * @deprecated
     */
    public function sum()
    {
        return $this->toBeanList()->sum();
    }

    public function jsonSerialize(bool $recureive = false)
    {
        return $this->toBeanList()->jsonSerialize($recureive);
    }

    public function column(?string $name, ?string $index_name = null): array
    {
        return $this->toBeanList()->column($name, $index_name);
    }

    /**
     * @param array $data
     * @return BeanListInterface
     */
    public function fromArray(array $data): BeanListInterface
    {
        return $this->toBeanList()->fromArray($data);
    }
}
