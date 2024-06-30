<?php

namespace app\Container;

use app\routerProvider\Router;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $classObjects = [];
//    public function __construct()
//    {
//        $this->classObjects = [
//            Router::class => fn() => new Router(),
//        ];
//    }
    public function get($id)
    {
        return
            isset($this->objects[$id])
                ? $this->objects[$id]()
                : $this->prepareObject($id);
    }

    public function has(string $id): bool
    {
        return isset($this->classObjects[$id]);
    }

    private function prepareObject(string $class): object
    {
        $classReflector = new \ReflectionClass($class);

        $constructReflector = $classReflector->getConstructor();
        if (empty($constructReflector)) {
            return new $class;
        }

        $constructArguments = $constructReflector->getParameters();
        if (empty($constructArguments)) {
            return new $class;
        }

        $args = [];
        foreach ($constructArguments as $argument) {
            $argumentType = $argument->getType()->getName();
            $args[$argument->getName()] = $this->get($argumentType);
        }

        return new $class(...$args);
    }
}