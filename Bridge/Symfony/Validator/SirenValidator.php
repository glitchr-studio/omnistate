<?php

namespace Omnistate\Bridge\Symfony\Validator;

use Omnistate\Identifier\Siren as SirenNumber;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class SirenValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof Siren) {
            throw new UnexpectedTypeException($constraint, Siren::class);
        }
        if (null === $value || '' === $value) {
            return;
        }
        if (!SirenNumber::isValid((string) $value)) {
            $this->context->buildViolation($constraint->message)->setParameter('{{ value }}', $this->formatValue($value))->addViolation();
        }
    }
}
