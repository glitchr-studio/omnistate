<?php

namespace Omnistate\Model;

/**
 * What a VAT register answered about a number: valid or not, and whose. The
 * consultation number, when the check was made with the requester's own VAT
 * number, is the proof it was made that day - to keep with a reverse-charge
 * invoice.
 */
final readonly class VatCheck
{
    public function __construct(
        public string $number,
        public bool $valid,
        public \DateTimeImmutable $checkedAt,
        public string $source,
        public ?string $name = null,
        public ?string $address = null,
        public ?string $consultationNumber = null,
    ) {
    }

    public function country(): string
    {
        return substr($this->number, 0, 2);
    }
}
