<?php

namespace Omnistate\Identifier;

/** The Luhn check (ISO/IEC 7812): every second digit from the right doubled. */
final class Luhn
{
    public static function isValid(string $digits): bool
    {
        if (!ctype_digit($digits)) {
            return false;
        }
        $sum = 0;
        foreach (array_reverse(str_split($digits)) as $i => $digit) {
            $digit = (int) $digit;
            if (1 === $i % 2) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
        }

        return 0 === $sum % 10;
    }
}
