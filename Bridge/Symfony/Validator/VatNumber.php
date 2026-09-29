<?php

namespace Omnistate\Bridge\Symfony\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * An intra-EU VAT number: in its member state's shape (and, for France, its
 * key agreeing with its SIREN) - and, with $checkExistence, known to VIES.
 * VIES down is not a wrong number: the value then passes.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class VatNumber extends Constraint
{
    public string $message = 'This is not a valid EU VAT number.';
    public string $unknownMessage = 'No EU member state knows this VAT number.';

    public function __construct(
        public bool $checkExistence = false,
        ?string $message = null,
        ?string $unknownMessage = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct(null, $groups, $payload);
        $this->message = $message ?? $this->message;
        $this->unknownMessage = $unknownMessage ?? $this->unknownMessage;
    }
}
