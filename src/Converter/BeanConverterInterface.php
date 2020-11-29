<?php

declare(strict_types=1);

namespace Niceshops\Bean\Converter;

use Niceshops\Bean\Type\Base\BeanInterface;

/**
 * Interface BeanConverterInterface
 * @package Niceshops\Bean\Converter
 */
interface BeanConverterInterface
{
    public function convert(BeanInterface $bean, array $rawData = []): ConverterBeanDecorator;

    /**
     * @param BeanInterface $bean
     * @param string $name
     * @param $value
     * @return mixed
     */
    public function convertValueFromBean(BeanInterface $bean, string $name, $value);

    /**
     * @param BeanInterface $bean
     * @param string $name
     * @param $value
     * @return mixed
     */
    public function convertValueToBean(BeanInterface $bean, string $name, $value);

    /**
     * @param string $name
     * @return mixed
     */
    public function getRawData(string $name);

    /**
     * @return array
     */
    public function getRawDataMap(): array;

    /**
     * @param string $name
     * @return bool
     */
    public function issetRawData(string $name): bool;

    /**
     * @param string $name
     * @return bool
     */
    public function initializedRawData(string $name): bool;

    /**
     * @param string $name
     * @return bool
     */
    public function emptyRawData(string $name): bool;

    /**
     * @param string $name
     * @param $value
     * @return $this
     */
    public function setRawData(string $name, $value): self;

    /**
     * @param string $name
     * @return mixed
     */
    public function unsetRawData(string $name);

    /**
     * @return mixed
     */
    public function resetRawData();
}
