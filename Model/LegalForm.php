<?php

namespace Omnistate\Model;

/** A legal form: the register's code, its name and the short name people use (SARL, SAS). */
final readonly class LegalForm
{
    public function __construct(
        public string $code,
        public ?string $label = null,
        public ?string $short = null,
    ) {
    }

    public function __toString(): string
    {
        return $this->short ?? $this->label ?? $this->code;
    }
}
