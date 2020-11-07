# tiny-service-manager

[![Build Status](https://travis-ci.com/esase/tiny-service-manager.svg?branch=master)](https://travis-ci.com/github/esase/tiny-service-manager/builds)
[![Coverage Status](https://coveralls.io/repos/github/esase/tiny-service-manager/badge.svg?branch=master)](https://coveralls.io/github/esase/tiny-service-manager?branch=master)

**Tiny/Service Manager** - it's a very simple realization of [DI](https://en.wikipedia.org/wiki/Dependency_injection) (dependency injection) 
container with a clean and understandable Api. 
(There are no any extra dependencies and it's very small).

`DI containers` are essential part of any modern `framework` or `CMS`. 
Differently speaking it's the one of the most important part in web applications,
which stores and produces any kind of services for you (`controllers`, `services`, `utilities`, etc).

Furthermore it follows to  a one of the [SOLID](https://en.wikipedia.org/wiki/SOLID). principle (dependency injection or dependency inversion).
Which stands for - you should not create objects directly in other objects, because of some 
difficulties in unit testing and maintaining  of embedded classes. 
Lets check a look a couple of examples:

**a wrong way:**

```php

<?php

class A {
    private B $embedded;

    public function __construct() {
        // Issues:
        // 1. we cannot test it because we cannot mimic it
        // 2. If we decide to use another implementation we will have to find and replace all its references
        $this->embedded = new B();
    }
}

```

**the best way** -  is to inject any dependencies in `constructor` or `setters` (if dependencies are optional).

```php

<?php

class A {
    private B $embedded;

    public function __construct(B $embedded) {
        $this->embedded = $embedded;
    }
}

```

**service manager** in action

```php
    use Tiny\ServiceManager\ServiceManager;

    // The Service Manager is a service/object locator, tasked with retrieving other objects.
    $serviceManager = new ServiceManager([
        B::class => function(ServiceManager $serviceManager) {
            return new B();
        },
        A::class => function(ServiceManager $serviceManager) {
            return new A($serviceManager->get(B::class));
        }
    ]);

    // now whenever we get an instance of "A" class we get it with injected instance of "B" class
    $serviceA = $serviceManager->get(A::class);

```

now we can easily test the `A` class injecting a mocked version of the `B`

```php
$serviceA = new A(new MockedB());
```

For more details please check a look the documentation link below.


## Installation

Run the following to install this library:

```bash
$ composer require esase/tiny-service-manager
```

## Documentation

https://tiny-docs.readthedocs.io/en/latest/tiny-service-manager/docs/index.html
