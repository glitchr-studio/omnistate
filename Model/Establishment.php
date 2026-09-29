<?php

namespace Omnistate\Model;

/** One of a company's places of business (France: a SIRET). */
final readonly class Establishment
{
    public function __construct(
        public string $identifier,
        public bool $headOffice,
        public CompanyStatus $status,
        public ?Address $address = null,
        public ?string $name = null,
        /** @var list<string> the signs it trades under */
        public array $signs = [],
        public ?Activity $activity = null,
        public ?Headcount $headcount = null,
        public ?\DateTimeImmutable $createdOn = null,
        public ?\DateTimeImmutable $closedOn = null,
    ) {
    }

    public function isActive(): bool
    {
        return CompanyStatus::ACTIVE === $this->status;
    }
}
