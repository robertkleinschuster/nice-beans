<?php
declare(strict_types=1);

namespace Niceshops\Bean\Converter;


use Niceshops\Bean\Type\Base\BeanInterface;

interface BeanConverterInterface
{
    public function convert(BeanInterface $bean): BeanDecorator;

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
}
