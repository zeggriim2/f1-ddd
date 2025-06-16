<?php

declare(strict_types=1);

namespace App\ValueObject;

use Webmozart\Assert\Assert;

class Money
{
    private int $amountInCents;
    private string $currency;

    public function __construct(float $amount, string $currency = 'USD')
    {
        Assert::greaterThanEq($amount, 0, 'Amount cannot be negative');

        $this->amountInCents = (int) round($amount * 100);
        $this->currency = strtoupper($currency);
    }

    public function getAmount(): float
    {
        return $this->amountInCents / 100;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function add(Money $other): self
    {
        Assert::same($this->currency, $other->currency, 'Cannot add different currencies');

        return new self(
            ($this->amountInCents + $other->amountInCents) / 100,
            $this->currency
        );
    }

    public function isGreaterThan(Money $other): bool
    {
        return $this->amountInCents > $other->amountInCents;
    }

    public function __toString(): string
    {
        return sprintf('%.2f %s', $this->getAmount(), $this->currency);
    }
}
