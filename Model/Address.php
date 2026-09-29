<?php

namespace Omnistate\Model;

/** Where a company or an establishment is, as its register writes it. */
final readonly class Address
{
    public function __construct(
        public ?string $street = null,
        public ?string $postalCode = null,
        public ?string $city = null,
        /** ISO 3166-1 alpha-2. */
        public ?string $country = null,
        /** The register's own one-line form. */
        public ?string $full = null,
        public ?float $latitude = null,
        public ?float $longitude = null,
    ) {
    }

    public function __toString(): string
    {
        return $this->full ?? trim(implode(' ', array_filter([$this->street, $this->postalCode, $this->city])));
    }
}
