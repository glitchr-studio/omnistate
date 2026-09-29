<?php

namespace Omnistate\Exception;

/**
 * The register did not answer: down, overloaded, a member state's service
 * closed for the night (VIES), too many calls (then $retryAfter, in seconds).
 * Not an answer about the company or the number: ask again later.
 */
class UnavailableException extends OmnistateException
{
    public function __construct(string $message, public readonly ?int $retryAfter = null, ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
