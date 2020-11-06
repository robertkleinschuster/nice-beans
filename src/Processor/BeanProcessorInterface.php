<?php

declare(strict_types=1);

namespace Niceshops\Bean\Processor;

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
}
