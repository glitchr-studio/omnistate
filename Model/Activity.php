<?php

namespace Omnistate\Model;

/** What a company does, in its classification (France: NAF rev. 2, e.g. 62.02A). */
final readonly class Activity
{
    public function __construct(
        public string $code,
        public string $classification,
        public ?string $label = null,
        /** The classification's section (the NAF's letter, J for IT). */
        public ?string $section = null,
    ) {
    }

    public function __toString(): string
    {
        return $this->label ? $this->code.' '.$this->label : $this->code;
    }
}
