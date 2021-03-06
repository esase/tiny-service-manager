<?php

/*
 * This file is part of the Tiny package.
 *
 * (c) Alex Ermashev <alexermashev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tiny\ServiceManager\Exception;

use Psr\Container\ContainerExceptionInterface;

class ServiceNotCreatedException extends \RuntimeException
    implements ExceptionInterface, ContainerExceptionInterface
{

}
