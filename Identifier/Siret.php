<?php

namespace Omnistate\Identifier;

/**
 * A French establishment's SIRET: its company's SIREN and a five-digit NIC,
 * fourteen digits under a Luhn check - except La Poste's (SIREN 356 000 000),
 * whose many establishments are checked by the sum of their digits, a
 * multiple of 5.
 */
final class Siret
{
    private const LA_POSTE = '356000000';

    /** "901 821 074 00019" -> "90182107400019"; null when it is not fourteen digits. */
    public static function normalize(?string $siret): ?string
    {
        $siret = preg_replace('/[\s.\-]/', '', (string) $siret);

        return preg_match('/^\d{14}$/', (string) $siret) ? $siret : null;
    }

    public static function isValid(?string $siret): bool
    {
        $siret = self::normalize($siret);
        if (null === $siret || !Siren::isValid(substr($siret, 0, 9))) {
            return false;
        }
        if (self::LA_POSTE === substr($siret, 0, 9) && '00012' !== substr($siret, 9)) {
            return 0 === array_sum(str_split($siret)) % 5;
        }

        return Luhn::isValid($siret);
    }

    public static function siren(string $siret): string
    {
        return substr(self::normalize($siret) ?? throw new \InvalidArgumentException(sprintf('"%s" is not a SIRET.', $siret)), 0, 9);
    }
}
