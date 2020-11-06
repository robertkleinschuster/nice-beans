<?php

declare(strict_types=1);

namespace Niceshops\Bean\Saver;

/**
 * Interface BeanSaverInterface
 * @package Niceshops\Library\Core
 */
interface BeanSaverInterface
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
