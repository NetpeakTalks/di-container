<?php
/**
 * @author Doctor <doctor.netpeak@gmail.com>
 */

namespace App\DI;


use App\Exceptions\ContainerException;
use App\Exceptions\ParameterNotFoundException;
use App\Exceptions\ServiceNotFoundException;
use App\Interfaces\IDIContainer;

class Container implements IDIContainer
{
    /**
     * @var array
     */
    private $services;
    /**
     * @var array
     */
    private $parameters;

    /**
     * @var array
     */
    private $serviceStore;

    /**
     * @var array
     */
    private $tagsStore;

    /**
     * @var array
     */
    private $argumentsResolveMapping = [
        ServiceDIReference::class => "get",
        ParameterDIReference::class => "getParameter",
        TagDIReference::class => "getByTag",
    ];

    /**
     * Container constructor.
     * @param array $services
     * @param array $parameters
     */
    public function __construct(array $services = [], array $parameters = [])
    {
        $this->services = $services;
        $this->parameters = $parameters;
        $this->serviceStore = [];
        $this->checkTagsList();
    }

    /**
     * @param string $name
     * @return object
     * @throws ContainerException
     * @throws ServiceNotFoundException
     * @throws \ReflectionException
     */
    public function get(string $name): object
    {
        if (!$this->has($name)) {
            throw new ServiceNotFoundException('Service not found: ' . $name);
        }

        if (!isset($this->serviceStore[$name])) {
            $this->serviceStore[$name] = $this->createService($name);
        }

        return $this->serviceStore[$name];
    }

    /**
     * @param string $tag
     * @return array
     * @throws ContainerException
     * @throws ServiceNotFoundException
     * @throws \ReflectionException
     */
    public function getByTag(string $tag): array
    {
        if (!isset($this->tagsStore[$tag])) {
            throw new ServiceNotFoundException('Tag not found: ' . $tag);
        }

        $services = [];
        foreach ($this->tagsStore[$tag] as $serviceName) {
            $services[] = $this->get($serviceName);
        }

        return $services;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->services[$name]);
    }

    /**
     * @param string $name
     * @return array|mixed
     * @throws ParameterNotFoundException
     */
    public function getParameter(string $name)
    {
        $tokens = explode('.', $name);
        $context = $this->parameters;

        while (null !== ($token = array_shift($tokens))) {
            if (!isset($context[$token])) {
                throw new ParameterNotFoundException('Parameter not found: ' . $name);
            }

            $context = $context[$token];
        }

        return $context;
    }

    protected function checkTagsList() :void
    {
        foreach ($this->services as $name => $service) {
            if (isset($service['tags'])) {
                $this->addTagsToList($name, $service['tags']);
            }
        }
    }

    /**
     * @param $name
     * @param array $tags
     */
    protected function addTagsToList($name, array $tags) :void
    {
        foreach ($tags as $tag) {
            $this->tagsStore[$tag][] = $name;
        }

    }

    /**
     * @param string $name
     * @return object
     * @throws ContainerException
     * @throws \ReflectionException
     */
    protected function createService(string $name) :object
    {
        $entry = &$this->services[$name];

        if (!is_array($entry) || !isset($entry['class'])) {
            throw new ContainerException($name . ' service entry must be an array containing a \'class\' key');
        } elseif (!class_exists($entry['class'])) {
            throw new ContainerException($name . ' service class does not exist: ' . $entry['class']);
        } elseif (isset($entry['lock'])) {
            throw new ContainerException($name . ' service contains a circular reference');
        }

        $entry['lock'] = true;

        $arguments = isset($entry['arguments']) ? $this->resolveArguments($entry['arguments']) : [];

        $reflector = new \ReflectionClass($entry['class']);
        $service = $reflector->newInstanceArgs($arguments);

        if (isset($entry['calls'])) {
            $this->initializeService($service, $name, $entry['calls']);
        }

        return $service;
    }

    /**
     * @param array $argumentDefinitions
     * @return array
     */
    protected function resolveArguments(array $argumentDefinitions)
    {
        $arguments = [];

        foreach ($argumentDefinitions as $argumentDefinition) {

            if (isset($this->argumentsResolveMapping[get_class($argumentDefinition)])) {
                $method = $this->argumentsResolveMapping[get_class($argumentDefinition)];
                $argumentDefinitionName = $argumentDefinition->getName();
                $arguments[] = $this->{$method}($argumentDefinitionName);

            } else {
                $arguments[] = $argumentDefinition;
            }
        }

        return $arguments;
    }

    protected function resolveArgumentService($argumentDefinition)
    {
        if ($argumentDefinition instanceof ServiceDIReference) {
            $argumentServiceName = $argumentDefinition->getName();
            $arguments[] = $this->get($argumentServiceName);
        }
    }

    /**
     * @param object $service
     * @param string $name
     * @param array $callDefinitions
     * @throws ContainerException
     */
    protected function initializeService($service, $name, array $callDefinitions)
    {
        foreach ($callDefinitions as $callDefinition) {
            if (!is_array($callDefinition) || !isset($callDefinition['method'])) {
                throw new ContainerException($name.' service calls must be arrays containing a \'method\' key');
            } elseif (!is_callable([$service, $callDefinition['method']])) {
                throw new ContainerException($name.' service asks for call to uncallable method: '.$callDefinition['method']);
            }

            $arguments = isset($callDefinition['arguments']) ? $this->resolveArguments($callDefinition['arguments']) : [];

            call_user_func_array([$service, $callDefinition['method']], $arguments);
        }
    }
}