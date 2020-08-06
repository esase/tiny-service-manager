<?php

/*
 * This file is part of the Tiny package.
 *
 * (c) Alex Ermashev <alexermashevn@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tiny\ServiceManager;

use Psr\Container\ContainerInterface;
use Tiny\ServiceManager\Exception\ServiceNotCreatedException;
use Tiny\ServiceManager\Exception\ServiceNotFoundException;
use Throwable;
use Closure;


class ServiceManager implements ContainerInterface
{

    /**
     * @var array
     */
    private $sharedServices = [];

    /**
     * @var array
     */
    private $discreteServices = [];

    /**
     * @var array
     */
    private $sharedInstances;

    /**
     * ServiceManager constructor.
     *
     * @param  array  $sharedServices
     * @param  array  $discreteServices
     */
    public function __construct(
        array $sharedServices,
        array $discreteServices
    ) {
        $this->sharedServices = $sharedServices;
        $this->discreteServices = $discreteServices;
    }

    /**
     * @param  string  $id
     *
     * @return object
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new ServiceNotFoundException(
                sprintf(
                    'Service `%s` is not registered',
                    $id
                )
            );
        }

        try {
            // return a shared service
            if (isset($this->sharedServices[$id])) {
                if (isset($this->sharedInstances[$id])) {
                    return $this->sharedInstances[$id];
                }

                $this->sharedInstances[$id] = $this->callFactory(
                    $this->sharedServices[$id],
                    $id
                );

                return $this->sharedInstances[$id];
            }

            // return a discrete service
            return $this->callFactory(
                $this->discreteServices[$id],
                $id
            );
        } catch (Throwable $e) {
            throw new ServiceNotCreatedException($e);
        }
    }

    /**
     * @param  string  $id
     *
     * @return bool
     */
    public function has($id): bool
    {
        if (isset($this->sharedServices[$id])
            || isset($this->discreteServices[$id])
        ) {

            return true;
        }

        return false;
    }

    /**
     * @param  string|Closure  $factory
     * @param  string          $id
     *
     * @return object
     */
    private function callFactory($factory, $id)
    {
        if ($factory instanceof Closure) {
            return $factory($this, $id);
        }

        $instance = new $factory();

        // get the "__invoke method"
        return $instance($this, $id);
    }

}
