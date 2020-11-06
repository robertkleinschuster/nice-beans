<?php

declare(strict_types=1);

namespace Niceshops\Bean\Finder;

use Niceshops\Bean\Type\Base\BeanInterface;
use Niceshops\Bean\Type\Base\BeanListInterface;

/**
 * Interface BeanFinderInterface
 * @package Niceshops\Library\Core
 */
interface BeanFinderInterface
{
    /**
     * count without limit
     *
     * @return int
     */
    public function count(): int;

    /**
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function limit(int $limit, int $offset);

    /**
     * @param string $search
     * @param array|null $field_List
     * @return mixed
     */
    public function search(string $search, array $field_List = null);

    /**
     * @param array $field_List
     * @return mixed
     */
    public function order(array $field_List);

    /**
     * @param array $data_Map
     * @return mixed
     */
    public function filter(array $data_Map);

    /**
     * @param BeanFinderInterface $beanFinder
     * @param string $field
     * @param string $linkFieldSelf
     * @param string $linkFieldRemote
     * @return BeanFinderInterface
     */
    public function addLinkedFinder(
        BeanFinderInterface $beanFinder,
        string $field,
        string $linkFieldSelf,
        string $linkFieldRemote
    ): BeanFinderInterface;

    /**
     * filter by key value pairs
     *
     * @param string $field
     * @param array $list
     * @return $this
     */
    public function initByValueList(string $field, array $list);

    /**
     */
    public function getBeanListDecorator(): FinderBeanListDecorator;

    /**
     * @param bool $fetchAllData
     * @return BeanListInterface
     */
    public function getBeanList(bool $fetchAllData = false): BeanListInterface;

    /**
     * @param bool $fetchAllData
     * @return BeanInterface
     */
    public function getBean(bool $fetchAllData = false): BeanInterface;

    /**
     * @return int
     */
    public function getLimit(): int;

    /**
     * @return bool
     */
    public function hasLimit(): bool;

    /**
     * @return int
     */
    public function getOffset(): int;

    /**
     * @return bool
     */
    public function hasOffset(): bool;
}
