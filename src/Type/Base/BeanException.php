<?php

declare(strict_types=1);

/**
 * @see       https://github.com/niceshops/nice-beans for the canonical source repository
 * @license   https://github.com/niceshops/nice-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Bean\Type\Base;

use Niceshops\Core\Exception\CoreException;

/**
 * Class BeanException
 * @package Niceshops\Bean
 */
class BeanException extends CoreException
{
    public const ERROR_CODE_DATA_NOT_FOUND = 1000;
    public const ERROR_CODE_INVALID_DATA_NAME = 1001;
}
