<?php declare(strict_types=1);

namespace Igni\Terminal\Canvas;

use Igni\Terminal\Writer;

class Cursor
{
    private $writer;

    public function __construct(Writer $writer)
    {
        $this->writer = $writer;
    }

    public function moveUp(int $lines = 1): Cursor
    {
        $this->writer->write("\e[{$lines}A");

        return $this;
    }

    public function moveLeft(int $cols = 1): Cursor
    {
        $this->writer->write("\e[{$cols}D");

        return $this;
    }

    public function moveToStartOfLine(): Cursor
    {
        $this->writer->write("\r");

        return $this;
    }

    public function deleteLine(): Cursor
    {
        $this->writer->write("\e[K");

        return $this;
    }

    public function hide(): Cursor
    {
        $this->writer->write("\e[?25l");

        return $this;
    }

    public function applyDefaultStyle(): Cursor
    {
        $this->writer->write("\e[?25h'");

        return $this;
    }

    public function setStyle(Style $style): Cursor
    {
        $this->writer->write((string) $style);

        return $this;
    }

    public function resetStyle(): Cursor
    {
        $this->writer->write("\e[0m");

        return $this;
    }
}
