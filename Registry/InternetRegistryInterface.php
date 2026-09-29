<?php

namespace Omnistate\Registry;

use Omnistate\Model\Domain;
use Omnistate\Model\Network;

/** The Internet's registries: domains, IP ranges, autonomous systems. */
interface InternetRegistryInterface
{
    public function name(): string;

    /** @throws \Omnistate\Exception\UnavailableException */
    public function domain(string $name): ?Domain;

    /**
     * An IP address's range, or an AS number's ("AS3215", "3215").
     *
     * @throws \Omnistate\Exception\UnavailableException
     */
    public function network(string $address): ?Network;
}
