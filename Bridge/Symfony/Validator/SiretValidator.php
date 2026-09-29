<?php

namespace Omnistate\Bridge\Symfony\Validator;

use Omnistate\Identifier\Siret as SiretNumber;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class SiretValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof Siret) {
            throw new UnexpectedTypeException($constraint, Siret::class);
        }
        if (null === $value || '' === $value) {
            return;
        }
        if (!SiretNumber::isValid((string) $value)) {
            $this->context->buildViolation($constraint->message)->setParameter('{{ value }}', $this->formatValue($value))->addViolation();
        }
    }
}
