<?php

namespace App\Domain\Shared\Generator;

interface IdGenerator
{
    public function generate(): string;
}
