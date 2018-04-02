<?php declare(strict_types=1);

namespace Igni\Terminal;

use Igni\Exception\RuntimeException;

class Arguments
{
    private $path = '';
    private $arguments;

    public function __construct()
    {
        $this->readCliArguments();
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function get(string $name, $default = null)
    {
        return $this->arguments[$name] ?? $default;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    private function readCliArguments(): void
    {
        $rawArguments = $this->retrieveCliArguments();
        array_shift($rawArguments);
        $path = true;
        foreach ($rawArguments as $argument) {
            if (0 === strpos($argument, '--')) {
                $eq = strpos($argument, '=');
                if ($eq !== false) {
                    $this->arguments[substr($argument, 2, $eq - 2)] = substr($argument, $eq + 1);
                } else {
                    $k = substr($argument, 2);
                    if (!isset($this->arguments[$k])) {
                        $this->arguments[$k] = true;
                    }
                }
                $path = false;
            } elseif (0 === strpos($argument, '-')) {
                if ($argument[2] === '=') {
                    $this->arguments[$argument[1]] = substr($argument, 3);
                } else {
                    foreach (str_split(substr($argument, 1)) as $k) {
                        if (!isset($this->arguments[$k])) {
                            $this->arguments[$k] = true;
                        }
                    }
                }
                $path = false;
            } elseif ($path) {
                $this->path .= ' ' . $argument;
            }
        }

        $this->path = trim($this->path);
    }

    private function retrieveCliArguments(): array
    {
        global $argv;
        static $commandLineArguments;

        if ($commandLineArguments) {
            return $commandLineArguments;
        }

        if (is_array($argv)) {
            return $argv;
        }

        if (isset($_SERVER['argv']) && is_array($_SERVER['argv'])) {
            return $_SERVER['argv'];
        }

        if (isset($GLOBALS['HTTP_SERVER_VARS']['argv']) && is_array($GLOBALS['HTTP_SERVER_VARS']['argv'])) {
            return $GLOBALS['HTTP_SERVER_VARS']['argv'];
        }

        throw new RuntimeException('Could not read command line arguments (register_argc_argv=Off?)');
    }
}
