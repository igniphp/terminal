<?php declare(strict_types=1);

namespace Igni\Terminal\Canvas\Widget;

use Igni\Terminal\Canvas;
use Igni\Terminal\Canvas\Style;
use Igni\Terminal\Canvas\Widget;
use Igni\Utils\StringUtils;

class Text extends Widget
{
    private $lines = [];
    private $height = 0;
    private $style;

    public function __construct(string $text, Style $style = null)
    {
        $this->text($text, $style);
        $this->style = $style ?? Style::default();
    }

    public function style(Style $style): Text
    {
        $this->style = $style;
    }

    public function text(string $text = '', Style $style = null): void
    {
        foreach (explode(PHP_EOL, $text) as $line) {
            $this->lines[] = [$line, $style ?? Style::default()];
            $this->height++;
        }
    }

    public function line(int $height = 1)
    {
        $i = 0;
        while ($i < $height) {
            $this->lines[] = ['', Style::default()];
            ++$i;
        }
        $this->height += $height;
    }

    public function render(): string
    {
        $output = '';
        $this->height = 0;

        foreach ($this->lines as $line) {
            $style = $line[1] ?? $this->style;
            $line = $line[0];
            if ($style->getWidth() > 0) {

                $length = StringUtils::length($line);
                if (!$style->isCentered()) {
                    $length += $style->getPaddingLeft() + $style->getPaddingRight();
                    $trim = $length - $style->getWidth();

                    if ($trim > 0) {
                        $line =
                            str_repeat(' ', $this->style->getPaddingLeft()) .
                            StringUtils::substring($line, 0, -($trim+1)) . '…' .
                            str_repeat(' ', $this->style->getPaddingRight());
                    } else {
                        $line =
                            str_repeat(' ', $this->style->getPaddingLeft()) .
                            $line .
                            str_repeat(' ', $this->style->getPaddingRight() + abs($trim));
                    }
                } else {
                    if ($length > $style->getWidth()) {
                        $line = ' ' . StringUtils::substring($line, 0, $style->getWidth() - 3) . '… ';
                    } else {
                        $padding = ($style->getWidth() - $length) / 2;
                        $line =
                            str_repeat(' ', (int) floor($padding)) .
                            $line .
                            str_repeat(' ', (int) ceil($padding));
                    }
                }
            } else {
                $line =
                    str_repeat(' ', $this->style->getPaddingLeft()) .
                    $line .
                    str_repeat(' ', $this->style->getPaddingRight());
            }

            $styledLine = (string) $style;
            $styledLine .= $style->getPrefix();
            $styledLine .= $line;
            $styledLine .= $style->getSuffix();
            $styledLine .= Style::reset();

            $output .= $styledLine . PHP_EOL;

            $this->height++;
        }

        return $output;
    }

    public function remove(Canvas $canvas): void
    {
        $this->clear($canvas, $this->height);
    }
}
