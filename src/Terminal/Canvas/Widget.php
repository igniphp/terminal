<?php declare(strict_types=1);

namespace Igni\Terminal\Canvas;

use Igni\Http\Exception\TerminalException;
use Igni\Terminal\Canvas;

abstract class Widget
{
    /**
     * @var Canvas
     */
    protected $canvas;

    abstract public function render(): string;

    abstract public function remove(Canvas $canvas): void;

    protected function clear(Canvas $canvas, int $lines = 1): void
    {
        if (!$canvas->isActive($this)) {
            throw new TerminalException('Cannot remove widget.');
        }

        $i = 0;
        while ($i < $lines) {
            $canvas->cursor()->moveUp()->deleteLine();
            $i++;
        }
    }
}
