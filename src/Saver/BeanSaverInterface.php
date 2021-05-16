<?php

declare(strict_types=1);

namespace Pars\Bean\Saver;

/**
 * Interface BeanSaverInterface
 * @package Pars\Library\Patterns
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
