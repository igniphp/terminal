<?php declare(strict_types=1);

namespace Igni\Terminal\Canvas\Widget;

use Igni\Terminal\Keyboard;
use Igni\Terminal\Canvas;
use Igni\Terminal\Canvas\Style;
use Igni\Utils\ArrayUtil;

class SelectList extends InputWidget
{
    private $options = [];
    private $current = 0;
    private $selected = [];
    private $label = '';
    private $hoverState;
    private $idleState;
    private $multiple = false;
    private $unchecked = '[ ]';
    private $checked = '[▪]';

    public function __construct(string $label, string ...$options)
    {
        $this->label = $label;
        $this->options = $options;
        $this->hoverState = Style::create();
        $this->idleState = Style::create()->dim();
    }

    public function label(string $label): SelectList
    {
        $this->label = $label;

        return $this;
    }

    public function multiple(bool $multiple = false): SelectList
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function style(
        Style $idleItemStyle,
        Style $hoverItemStyle = null,
        string $unchecked = '[ ]',
        string $checked = '[▪]'
    ): SelectList {
        $this->idleState = $idleItemStyle;
        if ($hoverItemStyle) {
            $this->hoverState = $hoverItemStyle;
        }
        $this->unchecked = $unchecked;
        $this->checked = $checked;

        return $this;
    }

    public function options(string ...$options): SelectList
    {
        $this->options = $options;

        return $this;
    }

    public function value(Canvas $canvas)
    {
        while ($char = $canvas->read()->char()) {
            switch ($char) {
                case Keyboard::RETURN:
                    break 2;

                case Keyboard::DOWN_ARROW:
                case Keyboard::TAB:
                    $this->current++;
                    break;

                case Keyboard::SPACE:
                    if (in_array($this->current, $this->selected, true)) {
                        ArrayUtil::remove($this->selected, $this->current);
                    } else {
                        $this->selected[] = $this->current;
                    }
                    break;

                case Keyboard::UP_ARROW:
                    $this->current--;
                    break;
                default:
                    continue 2;
            }
            if ($this->current < 0) {
                $this->current = 0;
            }
            if ($this->current >= count($this->options)) {
                $this->current = count($this->options) - 1;
            }
            $canvas->redraw($this);
        }

        if ($this->multiple) {
            return $this->selected;
        }

        return $this->current;
    }

    public function render(): string
    {
        $output = $this->label;
        $output .= PHP_EOL;
        $output .= PHP_EOL;
        foreach ($this->options as $index => $label) {
            $checkbox = $this->unchecked;
            if ((!$this->multiple && $index === $this->current) ||
                ($this->multiple && in_array($index, $this->selected, true))) {
                $checkbox = $this->checked;
            }

            if ($this->current === $index) {
                $label = $this->hoverState . ' ' . $label . "\e[0m";
            } else {
                $label = $this->idleState . ' ' . $label . "\e[0m";
            }

            $output .= "\t" . $checkbox . $label . "\n";
        }

        return $output;
    }

    public function height(): int
    {
        return count($this->options) + 2;
    }

    public function remove(Canvas $canvas): void
    {
        $this->clear($canvas, $this->height());
    }
}
