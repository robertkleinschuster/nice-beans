<?php

declare(strict_types=1);

namespace Niceshops\Bean\Processor;

use Niceshops\Bean\Finder\BeanFinderInterface;
use Niceshops\Bean\Type\Base\BeanInterface;

/**
 * Interface ProcessorInterface
 * @package Niceshops\Library\Core
 */
interface BeanProcessorInterface
{

    /**
     * @return int
     */
    public function save(): int;

    /**
     * @return int
     */
    public function delete(): int;

    /**
     * @param BeanFinderInterface $finder
     * @param BeanInterface $bean
     * @param string $orderField
     * @param string $orderReferenceField
     * @param $orderReferenceValue
     * @param int $steps
     * @return mixed
     */
    public function move(
        BeanFinderInterface $finder,
        BeanInterface $bean,
        string $orderField,
        int $steps,
        string $orderReferenceField = null,
        $orderReferenceValue = null
    );

}
