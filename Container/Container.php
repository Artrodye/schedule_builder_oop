<?php

namespace app\Container;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $classObjects = [];
    public function get($id)
    {
        return
            isset($this->classObjects[$id])
                ? $this->classObjects[$id]()
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