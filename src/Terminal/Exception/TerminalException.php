<?php declare(strict_types=1);

namespace Igni\Http\Exception;

use Exception;
use Igni\Exception\RuntimeException;

class TerminalException extends RuntimeException
{
    public function __construct(string $message, Exception $previous = null)
    {
        parent::__construct($message, $previous);
    }
}
