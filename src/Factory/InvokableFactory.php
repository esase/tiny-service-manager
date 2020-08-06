<?php

/*
 * This file is part of the Tiny package.
 *
 * (c) Alex Ermashev <alexermashevn@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tiny\ServiceManager\Factory;

use Tiny\ServiceManager\ServiceManager;

class InvokableFactory
{

    /**
     * @param  ServiceManager  $serviceManager
     * @param  string          $targetClass
     *
     * @return object
     */
    public function __invoke(
        ServiceManager $serviceManager,
        string $targetClass
    ) {
        return new $targetClass();
    }

}
