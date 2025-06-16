<?php

declare(strict_types=1);

namespace App\Tests\ValueObject;

use App\ValueObject\LapTime;
use PHPUnit\Framework\TestCase;

class LapTimeTest extends TestCase
{
    public function testValidLapTimeCreation(): void
    {
        $lapTime = new LapTime(1, 23, 456);
        $this->assertEquals('1:23.456', $lapTime->toString());
    }

    public function testInvalidTimeThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new  LapTime(0, 70, 456);
    }

    public function testTimeComparison(): void
    {
        $fast = LapTime::fromString('1:23.456');
        $slow = LapTime::fromString('1:24.789');

        $this->assertTrue($fast->isFasterThan($slow));
        $this->assertFalse($slow->isFasterThan($fast));
    }

    public function testImmutability(): void
    {
        $original = LapTime::fromString('1:23.456');
        $added = $original->add(LapTime::fromString('0:01.000'));

        $this->assertEquals('1:23.456', $original->toString());
        $this->assertEquals('1:24.456', $added->toString());
    }
}
