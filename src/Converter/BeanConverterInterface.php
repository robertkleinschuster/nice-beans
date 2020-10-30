<?php
declare(strict_types=1);

namespace Niceshops\Bean\Converter;


use Niceshops\Bean\Type\Base\BeanInterface;

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


    public function getRawData(string $name);

    /**
     * @param string $name
     * @return bool
     */
    public function hasRawData(string $name): bool;

    /**
     * @param string $name
     * @param $value
     * @return $this
     */
    public function setRawData(string $name, $value): self;

    public function removeRawData(string $name);

    public function resetRawData();
}
