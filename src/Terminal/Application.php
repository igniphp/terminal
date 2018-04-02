<?php declare(strict_types=1);

namespace Igni\Terminal;

use Igni\Application\Application as AbstractApplication;
use Igni\Application\Controller\ControllerAggregate as AbstractControllerAggregate;
use Igni\Exception\RuntimeException;
use Igni\Terminal\Canvas\Style;
use Igni\Terminal\Canvas\Widget\Text;
use Igni\Terminal\Command\CommandAggregate;
use Igni\Terminal\Command\Help\HelpCommand;
use Igni\Terminal\Command\Path;
use Psr\Container\ContainerInterface;

class Application extends AbstractApplication
{
    /**
     * @var CommandAggregate
     */
    private $commandAggregate;

    /**
     * @var Canvas
     */
    private $canvas;

    public function __construct(ContainerInterface $container = null)
    {
        parent::__construct($container);

        $this->commandAggregate = new CommandAggregate($this->serviceLocator);
        $this->commandAggregate->add(HelpCommand::class);
    }

    public function getCommandAggregate(): AbstractControllerAggregate
    {
        return $this->commandAggregate;
    }

    public function run(): void
    {
        if ('cli' !== php_sapi_name()) {
            throw new RuntimeException('This has to be run from the command line');
        }

        $this->process(
            new Canvas(new Input(STDIN), new Output(STDOUT), new Output(STDERR)),
            new Arguments()
        );
    }

    private function process(Canvas $canvas, Arguments $arguments = null): void
    {
        $this->canvas = $canvas;
        $this->handleOnBootListeners();
        $this->initialize();
        $this->executeCurrentCommand($canvas, $arguments);
        $this->handleOnShutDownListeners();
    }

    private function executeCurrentCommand(Canvas $canvas, Arguments $arguments): void
    {
        $this->handleOnRunListeners();
        $helpController = new HelpCommand($this->commandAggregate);
        $controller = null;

        foreach ($this->commandAggregate as $path => $controller) {

            if ($arguments->getPath() === $path) {
                break;
            }

            $controller = null;
        }

        if ($controller === null) {
            $controller = $helpController;
        }

        if ($controller === HelpCommand::class) {
            $controller = $helpController;
        }

        if (is_string($controller)) {
            $controller = $this->dependencyResolver->resolve($controller);
        }

        if ($controller instanceof Command) {
            if (!$controller::getPath()->validate($arguments)) {
                $canvas->draw(new Text(
                    " \nInvalid or missing parameter passed for command: {$arguments->getPath()}\n ",
                    Style::create('white', 'red')
                ));
                return;
            }
        }

        try {
            $controller($canvas, $arguments);
        } catch (\Throwable $e) {
            $canvas->draw(new Text(
                " \nThere was an error while executing the command ($path): {$e->getMessage()}\n ",
                Style::create('white', 'red')
            ));
        }
    }

    /**
     * @param string $path
     * @param callable $callback
     * @return Path
     *
     * @example
     * $app->command('do something', function(Canvas $canvas) {
     *
     * });
     */
    public function command(string $path, callable $callback): Path
    {
        $path = new Path($path);
        $this->commandAggregate->add($callback, $path);

        return $path;
    }
}
