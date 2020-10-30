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

    public function delete(): int;

    public function getSaver(): BeanSaverInterface;



}
