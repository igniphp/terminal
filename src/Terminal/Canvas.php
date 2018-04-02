<?php declare(strict_types=1);

namespace Igni\Terminal;

use Igni\Terminal\Canvas\Cursor;
use Igni\Terminal\Canvas\Widget;

class Canvas implements Writer
{
    private $input;
    private $output;
    private $error;
    private $cursor;
    private $lastOutput;

    public function __construct(Reader $input, Writer $output, Writer $error = null)
    {
        $this->input = $input;
        $this->output = $output;
        $this->error = $error;
        $this->cursor = new Cursor($output);
    }

    public function write(string $output): void
    {
        $this->output->write($output);
    }

    public function draw(Widget $widget): void
    {
        $this->output->write($widget->render());
        $this->lastOutput = $widget;
    }

    public function redraw(Widget $widget): void
    {
        $widget->remove($this);
        $this->output->write($widget->render());
    }

    public function isActive(Widget $widget): bool
    {
        return $this->lastOutput === $widget;
    }

    public function read(): Reader
    {
        return $this->input;
    }

    public function error($output): void
    {
        if ($this->error) {
            $this->error->write((string) $output);
        }
    }

    public function getWidth(): int
    {
        return (int) exec('tput cols');
    }

    public function getHeight(): int
    {
        return (int) exec('tput lines');
    }

    public function cursor(): Cursor
    {
        return $this->cursor;
    }

    public function disable(): void
    {
        readline_callback_handler_install('', function() {});
    }

    public function enable(): void
    {
        readline_callback_handler_remove();
    }
}
