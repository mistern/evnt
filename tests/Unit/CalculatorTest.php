<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Calculator;
use PHPUnit\Framework\TestCase;

final class CalculatorTest extends TestCase
{
    public function testOnePlusOneIsTwo(): void
    {
        self::assertSame(2, Calculator::calculate(1, 1));
    }
}
