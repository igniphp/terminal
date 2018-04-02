<?php declare(strict_types=1);

namespace Igni\Terminal;

interface Reader
{
    public function line(): string;
    public function char(): string;
}
