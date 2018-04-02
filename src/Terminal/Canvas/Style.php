<?php declare(strict_types=1);

namespace Igni\Terminal\Canvas;

use Igni\Exception\InvalidArgumentException;

class Style
{
    public const COLOR_DEFAULT = 39;
    public const COLOR_BLACK = 30;
    public const COLOR_RED = 31;
    public const COLOR_GREEN = 32;
    public const COLOR_YELLOW = 33;
    public const COLOR_BLUE = 34;
    public const COLOR_MAGENTA = 35;
    public const COLOR_CYAN = 36;
    public const COLOR_LIGHT_GRAY = 37;
    public const COLOR_DARK_GRAY = 90;
    public const COLOR_LIGHT_RED = 91;
    public const COLOR_LIGHT_GREEN = 92;
    public const COLOR_LIGHT_YELLOW = 93;
    public const COLOR_LIGHT_BLUE = 94;
    public const COLOR_LIGHT_MAGENTA = 95;
    public const COLOR_LIGHT_CYAN = 96;
    public const COLOR_WHITE = 97;

    public const FORMAT_BOLD = 1;
    public const FORMAT_DIM = 2;
    public const FORMAT_ITALIC = 3;
    public const FORMAT_UNDERLINE = 4;
    public const FORMAT_BLINK = 5;
    public const FORMAT_INVERT = 7;
    public const FORMAT_HIDDEN = 8;
    public const FORMAT_STRIKE = 9;

    public const CLEAN = "\e[0m";

    private static $colorMap = [
        'default' => self::COLOR_DEFAULT,
        'black' => self::COLOR_BLACK,
        'red' => self::COLOR_RED,
        'green' => self::COLOR_GREEN,
        'yellow' => self::COLOR_YELLOW,
        'blue' => self::COLOR_BLUE,
        'magenta' => self::COLOR_MAGENTA,
        'cyan' => self::COLOR_CYAN,
        'gray' => self::COLOR_LIGHT_GRAY,
        'lightgray' => self::COLOR_LIGHT_GRAY,
        'darkgray' => self::COLOR_DARK_GRAY,
        'lightred' => self::COLOR_LIGHT_RED,
        'lightgreen' => self::COLOR_LIGHT_GREEN,
        'lightyellow' => self::COLOR_LIGHT_YELLOW,
        'lightblue' => self::COLOR_LIGHT_BLUE,
        'lightmagenta' => self::COLOR_LIGHT_MAGENTA,
        'lighcyan' => self::COLOR_LIGHT_CYAN,
        'white' => self::COLOR_WHITE,
    ];
    private static $formatMap = [
        'bold' => self::FORMAT_BOLD,
        'dim' => self::FORMAT_DIM,
        'italic' => self::FORMAT_ITALIC,
        'underline' => self::FORMAT_UNDERLINE,
        'blink' => self::FORMAT_BLINK,
        'invert' => self::FORMAT_INVERT,
        'hidden' => self::FORMAT_HIDDEN,
        'strike' => self::FORMAT_STRIKE,
        'strikethrough' => self::FORMAT_STRIKE,
    ];
    private $backgroundColor = 0;
    private $fontColor = 0;
    private $format = [];
    private $prefix = '';
    private $suffix = '';
    private $paddingLeft = 0;
    private $paddingRight = 0;
    private $center = false;
    private $width = 0;

    public static function create($font = self::COLOR_DEFAULT, $background = self::COLOR_DEFAULT + 10): Style
    {
        $style = new self();

        return $style
            ->font($font)
            ->background($background);
    }

    public function isCentered(): bool
    {
        return $this->center;
    }

    public function center(bool $center = true): Style
    {
        $this->center = $center;

        return $this;
    }

    public function background($color): Style
    {
        $this->backgroundColor = $this->normalizeColor($color, true);

        return $this;
    }

    public function font($color): Style
    {
        $this->fontColor = $this->normalizeColor($color);

        return $this;
    }

    public function prefix(string $content): Style
    {
        $this->prefix = $content;

        return $this;
    }

    public function suffix(string $content): Style
    {
        $this->suffix = $content;

        return $this;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getSuffix(): string
    {
        return $this->suffix;
    }

    public function getPaddingLeft(): int
    {
        return $this->paddingLeft;
    }

    public function getPaddingRight(): int
    {
        return $this->paddingRight;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function format(string ...$format): Style
    {
        foreach ($format as $part) {
            if (!isset(self::$formatMap[$part])) {
                throw new InvalidArgumentException(
                    'Invalid style format passed, expected valid format: ' . implode(',', array_keys(self::$formatMap))
                );
            }
            $normalized = self::$formatMap[$part];
            if (in_array($normalized, $this->format, true)) {
                continue;
            }

            $this->format[] = $normalized;
        }

        return $this;
    }

    public function italic(): Style
    {
        return $this->format('italic');
    }

    public function bold(): Style
    {
        return $this->format('bold');
    }

    public function underline(): Style
    {
        return $this->format('underline');
    }

    public function strike(): Style
    {
        return $this->format('strike');
    }

    public function hidden(): Style
    {
        return $this->format('hidden');
    }

    public function padding(int $padding, int $right = null): Style
    {
        $this->paddingLeft = $padding;
        $this->paddingRight = $right;

        if ($right === null) {
            $this->paddingRight = $padding;
        }

        return $this;
    }

    public function paddingLeft(int $padding): Style
    {
        $this->paddingLeft = $padding;

        return $this;
    }

    public function paddingRight(int $padding): Style
    {
        $this->paddingRight = $padding;

        return $this;
    }

    public function dim(): Style
    {
        return $this->format('dim');
    }

    private function normalizeColor($color, bool $background = false): int
    {
        if (is_string($color)) {
            $color = str_replace([' ', '_', '-'], '', strtolower($color));
            if (!isset(self::$colorMap[$color])) {
                throw new InvalidArgumentException(
                    'Invalid color passed, expected valid color: ' . implode(',', array_keys(self::$colorMap))
                );
            }

            return self::$colorMap[$color] + ($background ? 10 : 0);
        }

        return (int) $color;
    }

    public function __toString(): string
    {
        $style = "\e[";
        if ($this->backgroundColor > 0) {
            $style .= $this->backgroundColor . ';';
        }
        if ($this->fontColor > 0) {
            $style .= $this->fontColor . ';';
        }
        if ($this->format) {
            $style .= implode(';', $this->format);
        }
        if (substr($style, -1) === ';') {
            $style = substr($style, 0, -1);
        }
        return $style . 'm';
    }

    public function width(int $width)
    {
        $this->width = $width;

        return $this;
    }

    public static function reset(): string
    {
        return self::CLEAN;
    }

    public static function default(): Style
    {
        return new self();
    }
}
