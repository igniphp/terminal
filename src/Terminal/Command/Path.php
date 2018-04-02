<?php declare(strict_types=1);

namespace Igni\Terminal\Command;

use Igni\Terminal\Arguments;

class Path
{
    private $path;
    private $help;
    private $options = [];
    private $lastFailedOption;

    public function __construct(string $name, string $help = '')
    {
        $this->path = $name;
        $this->help = $help;
    }

    public function getHelp(): string
    {
        return $this->help;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array|string $name
     * @param string $description
     * @param callable $validator
     * @return Path
     * @example
     * $path->argument('help', 'Shows help dialog') // Sets name and aliases
     * $path->argument('help', 'Shows help dialog'); // Sets only name
     */
    public function option($name, string $description, callable $validator = null): self
    {
        if (is_array($name)) {
            $option = new Option($name[0], $description, $validator);
            $option->setAlias($name[1]);
        } else {
            $option = new Option($name, $description, $validator);
        }

        $this->options[$option->getName()] = $option;

        return $this;
    }

    public function validate(Arguments $arguments): bool
    {
        $this->lastFailedOption = null;
        foreach ($this->options as $name => $ignore) {
            if (isset($this->validators[$name])) {
                $validator = $this->validators[$name];

                if (!$validator($options[$name])) {
                    $this->lastFailedOption = $name;
                    return false;
                }
            }
        }

        return true;
    }

    public function getFailedOption(): string
    {
        return $this->lastFailedOption;
    }

    public static function on(string $path, string $description = ''): Path
    {
        $path = new Path($path, $description);

        return $path;
    }

    public function __toString(): string
    {
        return $this->path;
    }
}
