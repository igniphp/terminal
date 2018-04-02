<?php declare(strict_types=1);

namespace Igni\Terminal;

use Igni\Exception\InvalidArgumentException;

class Output implements Writer
{
    private $source;

    public function __construct($source = STDOUT)
    {
        if (!is_resource($source)) {
            throw new InvalidArgumentException('$source must be valid resource handler.');
        }

        $this->source = $source;
    }

    public function write(string $output): void
    {
        fwrite($this->source, $output);
    }

    public function __destruct()
    {
        fclose($this->source);
    }
}

