<?php declare(strict_types=1);

namespace Igni\Terminal\Command;

use ArrayIterator;
use Igni\Application\Controller\ControllerAggregate;
use Igni\Container\DependencyResolver;
use Igni\Exception\InvalidArgumentException;
use Igni\Terminal\Command;
use IteratorAggregate;
use Psr\Container\ContainerInterface;

class CommandAggregate implements ControllerAggregate, IteratorAggregate
{
    private $dependencyResolver;
    private $controllers;

    public function __construct(ContainerInterface $container)
    {
        $this->dependencyResolver = new DependencyResolver($container);
        $this->controllers = [];
    }

    public function add($controller, Path $path = null): void
    {
        if (is_subclass_of($controller, Command::class, true)) {
            $this->controllers[(string) $controller::getPath()] = $controller;
            return;
        }

        if ($controller instanceof \Closure && $path) {
            $this->controllers[(string) $path] = $controller;
            return;
        }

        throw new InvalidArgumentException(
            'Command should be callable or instance of ' . Command::class . ', passed value: ' . var_export($controller, true)
        );
    }

    public function getIterator()
    {
        return new ArrayIterator($this->controllers);
    }
}
