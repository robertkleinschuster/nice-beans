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

    public const ORDER_MODE_ASC = 'asc';
    public const ORDER_MODE_DESC = 'desc';
    public const FILTER_MODE_AND = 'and';
    public const FILTER_MODE_OR = 'or';

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
     * @param string $mode
     * @return mixed
     */
    public function filter(array $data_Map, string $mode = self::FILTER_MODE_AND);

    /**
     * @param array $data_Map
     * @return mixed
     */
    public function exclude(array $data_Map);

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

}
