<?php

namespace Omnistate\Model;

/** Someone a registry names (a registrar, a registrant), as far as it shows them. */
final readonly class Contact
{
    public function __construct(
        public ?string $name = null,
        public ?string $organization = null,
        public ?string $email = null,
        public ?string $country = null,
        public ?string $handle = null,
        /** A registrar's IANA ID. */
        public ?string $ianaId = null,
        public ?string $url = null,
    ) {
    }

    public function __toString(): string
    {
        return (string) ($this->organization ?? $this->name ?? $this->handle);
    }
}
