<?php

declare(strict_types=1);

namespace App;

final class Calculator
{
    public static function calculate(int $a, int $b): int
    {
        return $a + $b;
    }
}
