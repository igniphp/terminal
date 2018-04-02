<?php declare(strict_types=1);

namespace Igni\Terminal;

use Igni\Exception\InvalidArgumentException;

class Input implements Reader
{
    private $source;

    public function __construct($source = STDIN)
    {
        if (!is_resource($source)) {
            throw new InvalidArgumentException('$source must be valid resource handler.');
        }

        $this->source = $source;
    }

    public function read(int $length): string
    {
        return fread($this->source, $length);
    }

    public function line(): string
    {
        return trim($this->read(1024));
    }

    public function char(): string
    {
        readline_callback_handler_install('', function() {});
        $char = fread($this->source, 4);
        readline_callback_handler_remove();

        return $char;
    }

    public function __destruct()
    {
        fclose($this->source);
    }
}
