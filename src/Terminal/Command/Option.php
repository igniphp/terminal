<?php declare(strict_types=1);

namespace Igni\Terminal\Command;

class Option
{
    public const VALUE_OPTIONAL = 1;
    public const VALUE_REQUIRED = 2;

    private $name;
    private $help;
    private $validator;
    private $alias;
    private $required;

    public function __construct(string $name, string $help = '', callable $validator = null)
    {
        $this->name = $name;
        $this->help = $help;
        $this->validator = $validator;
    }

    public function required(bool $required = true): void
    {
        $this->required = $required;
    }

    public function setHelp(string $help): self
    {
        $this->help = $help;

        return $this;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function validate($value): bool
    {
        if (!$this->validator) {
            return true;
        }

        return (bool) ($this->validator)($value);
    }
}
