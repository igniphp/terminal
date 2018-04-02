<?php declare(strict_types=1);

namespace Igni\Terminal\Command\Help;

use Igni\Terminal\Canvas\Style;
use Igni\Terminal\Canvas;
use Igni\Terminal\Command\CommandAggregate;
use Igni\Terminal\Command\Path;

class CommandListView
{
    private $controllerAggregate;

    public function __construct(CommandAggregate $controllerAggregate)
    {
        $this->controllerAggregate = $controllerAggregate;
    }

    public function display(Canvas $canvas): void
    {
        $text = new Canvas\Widget\Text('Command not found', Style::create('white','red'));
        $text->text('Available commands:', Style::default());

        foreach ($this->controllerAggregate as $path => $controller) {
            if ($controller instanceof \Closure) {
                $path = new Path($path);
            } else {
                $path = $controller::getPath();
            }
            $text->line();
            $help = $path->getHelp();
            $text->text($path->getPath(), Style::create()->bold()->padding(4, 0));
            if ($help) {
                $text->text($help, Style::create()->dim()->padding(4, 0));
            }
        }

        $text->line();

        $canvas->draw($text);
    }
}
