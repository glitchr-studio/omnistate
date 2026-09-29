<?php

namespace Omnistate\Model;

/** A year's accounts as the register publishes them, in the currency's major unit. */
final readonly class FinancialYear
{
    public function __construct(
        public int $year,
        public ?float $revenue = null,
        public ?float $netIncome = null,
        public string $currency = 'EUR',
    ) {
    }
}
