<?php

declare(strict_types=1);

/**
 * @see       https://github.com/pars/pars-beans for the canonical source repository
 * @license   https://github.com/pars/pars-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Bean\Type\Base;

use Pars\Pattern\Exception\CoreException;

/**
 * Class BeanException
 * @package Pars\Bean
 */
class BeanException extends CoreException
{
    public const ERROR_CODE_DATA_NOT_FOUND = 1000;
    public const ERROR_CODE_INVALID_DATA_NAME = 1001;
}
