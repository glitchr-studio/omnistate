<?php

namespace Omnistate\Model;

/** Who runs a company: a person, or a company of its own (then its identifier). */
final readonly class Manager
{
    public function __construct(
        public string $name,
        public ?string $role = null,
        public bool $person = true,
        public ?string $firstNames = null,
        /** A company that manages: its identifier (France: its SIREN). */
        public ?string $identifier = null,
        public ?int $birthYear = null,
        public ?string $nationality = null,
    ) {
    }

    public function __toString(): string
    {
        return trim(($this->firstNames ? $this->firstNames.' ' : '').$this->name);
    }
}
