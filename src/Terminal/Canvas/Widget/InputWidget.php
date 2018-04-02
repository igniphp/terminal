<?php declare(strict_types=1);

namespace Igni\Terminal\Canvas\Widget;

use Igni\Terminal\Canvas;
use Igni\Terminal\Canvas\Widget;

abstract class InputWidget extends Widget
{
    abstract public function value(Canvas $canvas);
}
