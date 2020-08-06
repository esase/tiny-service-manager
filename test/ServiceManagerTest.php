<?php

/*
 * This file is part of the Tiny package.
 *
 * (c) Alex Ermashev <alexermashevn@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TinyTest\ServiceManager;

use Tiny\ServiceManager\Exception\ServiceNotCreatedException;
use Tiny\ServiceManager\Exception\ServiceNotFoundException;
use Tiny\ServiceManager\ServiceManager;
use Exception;
use PHPUnit\Framework\TestCase;
use stdClass;

class ServiceManagerTest extends TestCase
{

        public function testHasMethodUsingSharedServices()
        {
            $serviceName = 'test';
            $serviceFactory = 'test';
            $serviceManager = new ServiceManager(
                [$serviceName => $serviceFactory], []
            );
            $this->assertTrue(
                $serviceManager->has(
                    $serviceName
                )
            );
        }

        public function testHasMethodUsingDiscreteServices()
        {
            $serviceName = 'test';
            $serviceFactory = 'test';
            $serviceManager = new ServiceManager(
                [], [$serviceName => $serviceFactory]
            );
            $this->assertTrue(
                $serviceManager->has(
                    $serviceName
                )
            );
        }

        public function testHasMethodUsingEmptyServices()
        {
            $serviceName = 'test';
            $serviceManager = new ServiceManager(
                [], []
            );
            $this->assertFalse(
                $serviceManager->has(
                    $serviceName
                )
            );
        }

        public function testHasMethodUsingNotRegisteredService()
        {
            $serviceName = 'test';
            $serviceFactory = 'test';
            $serviceManager = new ServiceManager(
                [$serviceName => $serviceFactory], []
            );
            $this->assertFalse(
                $serviceManager->has(
                    'notExistingService'
                )
            );
        }

        public function testGetMethodUsingNotRegisteredService()
        {
            $service = 'testService';
            $this->expectException(ServiceNotFoundException::class);
            $this->expectExceptionMessage(
                sprintf(
                    'Service `%s` is not registered',
                    $service
                )
            );

            /** @var ServiceManager $serviceManagerMock */
            $serviceManagerMock = $this->getMockBuilder(ServiceManager::class)
                ->disableOriginalConstructor()
                ->setMethodsExcept(
                    [ // don't mock these methods
                      'get'
                    ]
                )
                ->getMock();

            $serviceManagerMock->expects($this->once())
                ->method('has')
                ->will($this->returnValue(false));

            $serviceManagerMock->get($service);
        }

        public function testGetMethodUsingSharedService()
        {
            $serviceName = 'testService';

            // register a factory
            $serviceFactory = new class {
                public function __invoke()
                {
                    return new stdClass();
                }
            };

            $serviceManager = new ServiceManager(
                [$serviceName => $serviceFactory], []
            );

            $instance1 = $serviceManager->get($serviceName);
            $instance2 = $serviceManager->get($serviceName);

            $this->assertSame(
                $instance1,
                $instance2
            );
        }

    public function testGetMethodUsingDiscreteService()
    {
        $serviceName = 'testService';

        // register a factory
        $serviceFactory = function () {
            return new stdClass();
        };

        $serviceManager = new ServiceManager(
            [], [$serviceName => $serviceFactory]
        );

        $instance1 = $serviceManager->get($serviceName);
        $instance2 = $serviceManager->get($serviceName);

        $this->assertNotSame(
            $instance1,
            $instance2
        );
    }

    public function testGetMethodUsingException()
    {
        $serviceName = 'testService';

        // register a factory
        $serviceFactory = function () {
            throw new Exception('Something went wrong');
        };

        $serviceManager = new ServiceManager(
            [], [$serviceName => $serviceFactory]
        );

        $this->expectException(ServiceNotCreatedException::class);
        $this->expectExceptionMessage('Something went wrong');

        $serviceManager->get($serviceName);
    }

}
