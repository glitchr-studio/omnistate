<?php

namespace Omnistate\Identifier;

/**
 * Intra-EU VAT numbers, as VIES writes them: a country prefix (Greece is EL,
 * Northern Ireland XI) and the number, in each country's own shape. No
 * register is asked here - a number can be well written and belong to no
 * one: omnistate/vies says whether it exists.
 */
final class VatNumber
{
    /** Each member state's shape, after its prefix. */
    public const FORMATS = [
        'AT' => 'U\d{8}',
        'BE' => '[01]\d{9}',
        'BG' => '\d{9,10}',
        'CY' => '\d{8}[A-Z]',
        'CZ' => '\d{8,10}',
        'DE' => '\d{9}',
        'DK' => '\d{8}',
        'EE' => '\d{9}',
        'EL' => '\d{9}',
        'ES' => '[A-Z0-9]\d{7}[A-Z0-9]',
        'FI' => '\d{8}',
        'FR' => '[0-9A-HJ-NP-Z]{2}\d{9}',
        'HR' => '\d{11}',
        'HU' => '\d{8}',
        'IE' => '\d{7}[A-W][A-I]?|\d[A-Z+*]\d{5}[A-W]',
        'IT' => '\d{11}',
        'LT' => '\d{9}|\d{12}',
        'LU' => '\d{8}',
        'LV' => '\d{11}',
        'MT' => '\d{8}',
        'NL' => '\d{9}B\d{2}',
        'PL' => '\d{10}',
        'PT' => '\d{9}',
        'RO' => '\d{2,10}',
        'SE' => '\d{12}',
        'SI' => '\d{8}',
        'SK' => '\d{10}',
        'XI' => '\d{9}|\d{12}|GD\d{3}|HA\d{3}',
    ];

    /**
     * "de 123 456-789" -> "DE123456789", "GR..." -> "EL...", and a SIREN or
     * SIRET -> its French number; null when it has no member state's shape.
     */
    public static function normalize(?string $number): ?string
    {
        $number = strtoupper((string) preg_replace('/[\s.\-]/', '', (string) $number));
        if (null !== $siren = Siren::normalize($number) ?? (Siret::normalize($number) ? substr($number, 0, 9) : null)) {
            return Siren::toVatNumber($siren);
        }
        if (str_starts_with($number, 'GR')) {
            $number = 'EL'.substr($number, 2);
        }
        $format = self::FORMATS[substr($number, 0, 2)] ?? null;

        return null !== $format && preg_match('/^(?:'.$format.')$/', substr($number, 2)) ? $number : null;
    }

    /** Well written, and for France its key agrees with its SIREN. */
    public static function isWellFormed(?string $number): bool
    {
        $number = self::normalize($number);
        if (null === $number) {
            return false;
        }
        if ('FR' === self::country($number) && ctype_digit(substr($number, 2, 2))) {
            return Siren::toVatNumber(substr($number, 4)) === $number;
        }

        return true;
    }

    public static function country(string $number): string
    {
        return substr($number, 0, 2);
    }
}
