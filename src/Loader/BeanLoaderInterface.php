<?php

declare(strict_types=1);

namespace Niceshops\Bean\Loader;

use Niceshops\Bean\Converter\ConverterBeanDecorator;
use Niceshops\Bean\Type\Base\BeanInterface;

/**
 * Interface BeanFinderLoaderInterface
 * @package Niceshops\Library\Core
 */
interface BeanLoaderInterface extends \Iterator, \Countable
{
    /**
     * @return int
     */
    public function count(): int;

    /**
     * @return int
     */
    public function execute(): int;

    /**
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function limit(int $limit, int $offset);

    /**
     * @param string $search
     * @param array|null $field_List
     * @return $this
     */
    public function search(string $search, array $field_List = null);

    /**
     * @param array $field_List
     * @return $this
     */
    public function order(array $field_List);

    /**
     * @param array $data_Map
     * @return $this
     */
    public function filter(array $data_Map);

    /**
     * @param string $field
     * @return array
     */
    public function preloadValueList(string $field): array;

    /**
     * @param BeanInterface $bean
     * @param array $data
     * @return ConverterBeanDecorator
     */
    public function initializeBeanWithData(BeanInterface $bean, array $data): BeanInterface;
}
