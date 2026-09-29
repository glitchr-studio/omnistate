<?php

namespace Omnistate\Bridge\Symfony\Validator;

use Symfony\Component\Validator\Constraint;

/** A French SIRET: fourteen digits, its key right (La Poste's too). */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class Siret extends Constraint
{
    public string $message = 'This is not a valid SIRET.';

    public function __construct(?string $message = null, ?array $groups = null, mixed $payload = null)
    {
        parent::__construct(null, $groups, $payload);
        $this->message = $message ?? $this->message;
    }
}
