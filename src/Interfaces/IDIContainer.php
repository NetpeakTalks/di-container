<?php
/**
 * @author Doctor <doctor.netpeak@gmail.com>
 */

namespace App\Interfaces;


use App\Exceptions\ServiceNotFoundException;

interface IDIContainer
{
    /**
     * @param string $name
     * @return object
     * @throws ContainerException
     * @throws ServiceNotFoundException
     * @throws \ReflectionException
     */
    public function get(string $name) :object;

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name) :bool;

    /**
     * @param string $tag
     * @return array
     * @throws ContainerException
     * @throws ServiceNotFoundException
     * @throws \ReflectionException
     */
    public function getByTag(string $tag): array;

    /**
     * @param string $name
     * @return array|mixed
     * @throws ParameterNotFoundException
     */
    public function getParameter(string $name);

}