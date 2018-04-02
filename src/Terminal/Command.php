<?php declare(strict_types=1);

namespace Igni\Terminal;

use Igni\Application\Controller as ControllerInterface;
use Igni\Terminal\Command\Path;

interface Command extends ControllerInterface
{
    public function __invoke(Canvas $terminal);
    public static function getPath(): Path;
}
