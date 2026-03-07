<?php

namespace App\Infrastructure\Shared\Generator;

use Illuminate\Support\Str;
use App\Domain\Shared\Generator\IdGenerator;

final class UuidGenerator implements IdGenerator
{
    public function generate(): string
    {
        return Str::uuid()->toString();
    }
}
