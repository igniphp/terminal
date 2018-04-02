<?php declare(strict_types=1);

namespace Igni\Terminal\Canvas\Widget;

use Igni\Terminal\Canvas\Style;
use Igni\Terminal\Canvas;
use Igni\Terminal\Canvas\Widget;

class ProgressBar extends Widget
{
    /** @var int */
    private $total;

    /** @var int */
    private $progress;

    /** @var int */
    private $length = 20;

    /** @var string */
    private $label = '${value} %';

    /** @var string */
    private $filled = '█';

    /** @var string */
    private $blank = '░';

    /** @var Style */
    private $barStyle;

    /** @var Style */
    private $labelStyle;

    /** @var bool */
    private $draw = false;

    public function __construct(int $total)
    {
        $this->total = $total;
        $this->barStyle = Style::create()
            ->prefix('│')
            ->suffix('│');
        $this->labelStyle = Style::create();
    }

    public function style(
        Style $barStyle = null,
        Style $labelStyle = null,
        string $filled = '█',
        string $blank = '░'
    ): ProgressBar {
        if (null !== $barStyle) {
            $this->barStyle = $barStyle;
        }
        if (null !== $labelStyle) {
            $this->labelStyle = $labelStyle;
        }

        $this->filled = $filled;
        $this->blank = $blank;

        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function length(int $length = 20): ProgressBar
    {
        $this->length = $length;

        return $this;
    }

    public function label(string $label = ':value', Style $style = null): ProgressBar
    {
        $this->label = $label;
        if ($style) {
            $this->labelStyle = $style;
        }

        return $this;
    }

    public function value(): int
    {
        if (!$this->progress) {
            return 0;
        }

        return (int) ceil($this->progress / $this->total * 100);
    }

    public function progress(int $progress, Canvas $canvas = null): ProgressBar
    {
        $this->progress = $progress;
        if ($this->draw) {
            $canvas->redraw($this);
        } else {
            $canvas->draw($this);
        }

        return $this;
    }

    public function render(): string
    {
        $string = "\n";
        $progress = (string) $this->value();
        $barLength = 0;
        if ($progress) {
            $barLength = floor($this->progress / $this->total * $this->length);
        }

        // Label.
        if (strlen($progress) < 2) {
            $progress = ' ' . $progress;
        }
        $label = str_replace('${value}', $progress, $this->label);
        $string .= $this->labelStyle . $label . Style::CLEAN . "\t";

        // Bar.
        $string .= $this->barStyle . $this->barStyle->getPrefix();

        for ($i = 0; $i < $this->length; $i++) {
            $char = $this->blank;
            if ($i < $barLength) {
                $char = $this->filled;
            }
            if ($this->barStyle) {
                $string .= $char;
            } else {
                $string .= $char;
            }
        }
        $string .= $this->barStyle->getSuffix() . Style::CLEAN . "\r\n";
        $this->draw = true;

        return $string;
    }

    public function remove(Canvas $canvas): void
    {
        $this->clear($canvas, 2);
    }
}
