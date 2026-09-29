<?php

namespace Omnistate\Registry;

use Omnistate\Model\Company;

/** A register of companies: France's, the UK's Companies House... */
interface CompanyRegistryInterface
{
    /** A short name, the Company's $source: "annuaire-entreprises". */
    public function name(): string;

    /** Whether it can answer for that identifier (a SIREN, a SIRET, a company number...). */
    public function supports(string $identifier): bool;

    /**
     * The company, with the establishment asked for among its establishments
     * when the identifier is one; null when the register does not know it.
     *
     * @throws \Omnistate\Exception\UnavailableException the register did not answer
     */
    public function company(string $identifier): ?Company;

    /**
     * Companies by name (or anything the register searches on).
     *
     * @return list<Company>
     *
     * @throws \Omnistate\Exception\UnavailableException
     */
    public function search(string $query, int $limit = 10): array;
}
