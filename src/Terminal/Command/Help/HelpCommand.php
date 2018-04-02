<?php declare(strict_types=1);

namespace Igni\Terminal\Command\Help;

use Igni\Terminal\Canvas;
use Igni\Terminal\Command;
use Igni\Terminal\Command\CommandAggregate;
use Igni\Terminal\Command\Path;

class HelpCommand implements Command
{
    private $controllerAggregate;

    public function __construct(CommandAggregate $aggregate)
    {
        $this->controllerAggregate = $aggregate;
    }

    public function __invoke(Canvas $terminal)
    {
        $view = new CommandListView($this->controllerAggregate);
        $view->display($terminal);
    }

    public static function getPath(): Path
    {
        return Path::on('help', 'Lists available commands.');
    }
}
