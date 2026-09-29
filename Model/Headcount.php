<?php

namespace Omnistate\Model;

/** How many employees, as the register gives it: a range, and the year it was counted. */
final readonly class Headcount
{
    public function __construct(
        public ?int $min = null,
        public ?int $max = null,
        public ?int $year = null,
        /** The register's own code for the range (INSEE: "12" for 20 to 49). */
        public ?string $code = null,
    ) {
    }

    public function isEmployer(): bool
    {
        return null !== $this->max && $this->max > 0;
    }

    public function __toString(): string
    {
        return match (true) {
            null === $this->min => '',
            null === $this->max => $this->min.'+',
            $this->min === $this->max => (string) $this->min,
            default => $this->min.'-'.$this->max,
        };
    }
}
