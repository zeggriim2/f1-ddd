<?php

declare(strict_types=1);

namespace App\ValueObject;


use InvalidArgumentException;
use Webmozart\Assert\Assert;

final readonly class LapTime
{
    private int $milliseconds;

    public function __construct(int $minutes, int $seconds, int $milliseconds)
    {
        $this->validate($minutes, $seconds, $milliseconds);
        $this->milliseconds = ($minutes * 60 * 1000) + ($seconds * 1000) + $milliseconds;
    }

    private function validate(int $minutes, int $seconds, int $milliseconds): void
    {
        if ($minutes < 0 || $seconds < 0 || $seconds >= 60 || $milliseconds < 0 || $milliseconds >= 1000) {
            throw new InvalidArgumentException('Invalid time components');
        }
    }

    public static function fromString(string $time): LapTime
    {
        // Format: "1:23.456" ou "1:23:456"
        $pattern = [
            '/^(\d+):(\d{2})\.(\d{3})$/',  // 1:23.456
            '/^(\d+):(\d{2}):(\d{3})$/',
        ];

        foreach ($pattern as $patternValue) {
            if (preg_match($patternValue, $time, $matches)) {
                return new self((int) $matches[1], (int) $matches[2], (int) $matches[3]);
            }
        }

        throw new InvalidArgumentException('Invalid time format. Expected: M:SS.mmm');
    }

    public static function fromMilliseconds(int $milliseconds): self
    {
        $totalSeconds = intval($milliseconds / 1000);
        $minutes = intval($totalSeconds / 60);
        $seconds = $totalSeconds % 60;
        $ms = $milliseconds % 1000;

        return new self($minutes, $seconds, $ms);
    }

    public function getMilliseconds(): int
    {
        return $this->milliseconds;
    }

    public function toString(): string
    {
        $totalSeconds = intval($this->milliseconds / 1000);
        $minutes = intval($totalSeconds / 60);
        $seconds = $totalSeconds % 60;
        $ms = $this->milliseconds % 1000;

        return sprintf('%d:%02d.%03d', $minutes, $seconds, $ms);
    }

    public function isFasterThan(LapTime $other): bool
    {
        return $this->milliseconds < $other->milliseconds;
    }

    public function getDifferenceWith(LapTime $other): int
    {
        return abs($this->milliseconds - $other->milliseconds);
    }

    public function add(LapTime $other): self
    {
        return self::fromMilliseconds($this->milliseconds + $other->milliseconds);
    }
}
