<?php declare(strict_types=1);

namespace Igni\Terminal;

class Keyboard
{
    public const SPACE = ' ';
    public const TAB = "\t";
    public const RETURN = "\r";
    public const LINE_FEED = "\n";
    public const ESCAPE = "\e";
    public const UP_ARROW = "\e[A";
    public const DOWN_ARROW = "\e[B";
    public const RIGHT_ARROW = "\e[C";
    public const LEFT_ARROW = "\e[D";
    public const BACKSPACE = "\177";
    public const DELETE = "\e[3~";
}
