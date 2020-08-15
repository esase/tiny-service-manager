<?php

/*
 * This file is part of the Tiny package.
 *
 * (c) Alex Ermashev <alexermashev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TinyTest\ServiceManager\Factory;

use PHPUnit\Framework\TestCase;
use Tiny\ServiceManager\Factory\InvokableFactory;
use Tiny\ServiceManager\ServiceManager;
use stdClass;

class invokableFactoryTest extends TestCase
{

    public function testInvokeMethod()
    {
        $factory = new InvokableFactory();
        $instance = $factory(
            $this->createMock(ServiceManager::class),
            stdClass::class
        );

        $this->assertInstanceOf(stdClass::class, $instance);
    }

}
