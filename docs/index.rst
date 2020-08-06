.. _index-service-manager-label:

Service manager
===============

The Service Manager is a **service/object locator**, tasked with retrieving other objects.
It fully compatible with the PSR's containers - https://www.php-fig.org/psr/psr-11/

Installation
------------

Run the following to install this library:


.. code-block:: bash

    $ composer require esase/tiny-service-manager

Shared services
---------------

The most frequently used type of services it's shared services or singletons.
The service manager always keeps and retrieves only the one instance of an object.
It means you may reduce consuming of the memory and keep the state in a one object.

--------------
Shared example
--------------

.. code-block:: php

    <?php

        use Tiny\ServiceManager\ServiceManager;
        use stdClass;

        $serviceManager = new ServiceManager([
            // pass a service name and it's factory
            'TestService' => function() {
                return new stdClass();
            }
        ]);

        var_dump($serviceManager->has('TestService')); // prints `true`

        $service1 = $serviceManager->get('TestService');
        $service2 = $serviceManager->get('TestService');

        var_dump($service1 === $service2); // prints `true`

Discrete services
-----------------

Some times we don't need singletons, we need to retrieve a new object instance whenever we get it from the service manager.

.. code-block:: php

    <?php

        use Tiny\ServiceManager\ServiceManager;
        use stdClass;

        // the constructor accepts  discrete services as a second parameter
        $serviceManager = new ServiceManager([], [
            // pass a service name and it's factory
            'TestService' => function() { // now this is not a shared service
                return new stdClass();
            }
        ]);

        var_dump($serviceManager->has('TestService')); // prints `true`

        $service1 = $serviceManager->get('TestService');
        $service2 = $serviceManager->get('TestService');

        var_dump($service1 === $service2); // prints `false` they are different

Factories
---------

There are two types of factories which you can use for building you objects: :code:`Closure` and :code:`Class factories`

---------------
Closure example
---------------

The closure it's just an `anonymous` (or `lambda`) function which is called for building an object:

.. code-block:: php

    <?php

        use Tiny\ServiceManager\ServiceManager;
        use stdClass;

        $serviceManager = new ServiceManager([
            TestService::class => function(
                ServiceManager $serviceManager,
                string $targetClass
            ) {
                return new stdClass();
            }
        ]);

It's a good practice to use a class name as key for services, :code:`TestService::class` in our case.
Also as you can see the service manager always passes two parameters inside factories:

- :code:`$serviceManager` - it's just a reference to it self which may be used for retrieving other dependencies.
- :code:`$targetClass` - a class name which we are trying to build.

---------------------
Class factory example
---------------------

Remember each factory class must include :code:`__invoke` method.

.. code-block:: php

    <?php

        use Tiny\ServiceManager\ServiceManager;

        $serviceManager = new ServiceManager([], [
            TestService::class => TestServiceFactory::class
        ]);

        class TestServiceFactory
        {
            public function __invoke(
                ServiceManager $serviceManager,
                string $targetClass
            ): TestService {
                // we even may inject different services
                return new TestService(
                    $serviceManager->get(OtherService::class)
                    ...
                );
            }
        }

If you don't need to provide extra dependencies in you service you may use a default factory class,
which just creates you service:

.. code-block:: php

    <?php

        use Tiny\ServiceManager\ServiceManager;
        use Tiny\ServiceManager\Factory\InvokableFactory;

        $serviceManager = new ServiceManager([], [
            TestService::class => InvokableFactory::class
        ]);

        ...