<?php

namespace Omnistate\Tests\Bridge;

use Omnistate\Bridge\Symfony\CompanySearch;
use Omnistate\Bridge\Symfony\Controller\CompanySearchController;
use Omnistate\Bridge\Symfony\Form\CompanySearchType;
use Omnistate\Exception\UnavailableException;
use Omnistate\Model\Address;
use Omnistate\Model\Company;
use Omnistate\Model\CompanyStatus;
use Omnistate\Model\Establishment;
use Omnistate\Omnistate;
use Omnistate\Registry\CompanyRegistryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;

final class CompanySearchTest extends TestCase
{
    public function testACompanyFoundByNameComesWithItsVatNumberAndHeadOffice(): void
    {
        $search = new CompanySearch(new Omnistate([$this->registry([$this->laToucheOriginale()])]));

        $rows = $search->search('la touche originale');

        self::assertCount(1, $rows);
        self::assertSame('917631715', $rows[0]['siren']);
        self::assertSame('SARL LA TOUCHE ORIGINALE', $rows[0]['name']);
        self::assertSame('FR43917631715', $rows[0]['vatNumber'], 'derived from the SIREN');
        self::assertSame('91763171500011', $rows[0]['siret']);
        self::assertSame('1 RUE DU LINDEBUCKEL 67300 SCHILTIGHEIM', $rows[0]['address']);
        self::assertTrue($rows[0]['active']);
    }

    public function testTooShortAQueryAsksNobody(): void
    {
        $registry = $this->registry([$this->laToucheOriginale()]);
        $search = new CompanySearch(new Omnistate([$registry]));

        self::assertSame([], $search->search('la'));
        self::assertSame(0, $registry->asked, 'no request for two letters');
    }

    public function testTheEndpointAnswersTheRowsOr503(): void
    {
        $found = (new CompanySearchController(new CompanySearch(new Omnistate([$this->registry([$this->laToucheOriginale()])]))))(new Request(['q' => 'touche originale']));
        self::assertSame(200, $found->getStatusCode());
        self::assertSame('FR43917631715', json_decode((string) $found->getContent(), true)['results'][0]['vatNumber']);

        $down = (new CompanySearchController(new CompanySearch(new Omnistate([$this->registry([], new UnavailableException('429'))]))))(new Request(['q' => 'touche originale']));
        self::assertSame(503, $down->getStatusCode());
    }

    public function testTheFieldPointsItsSiblingsOutToTheScript(): void
    {
        $factory = Forms::createFormFactoryBuilder()->addType(new CompanySearchType())->getFormFactory();
        $form = $factory->createNamedBuilder('store', FormType::class)
            ->add('company', CompanySearchType::class, ['fill' => ['vatNumber' => 'vatNumber', 'missing' => 'name']])
            ->add('vatNumber', TextType::class)
            ->getForm();

        $attr = $form->createView()['company']->vars['attr'];

        self::assertSame('/omnistate/company/search', $attr['data-omnistate-company-search'], 'no router: the default path');
        self::assertSame(['store_vatNumber' => 'vatNumber'], json_decode($attr['data-omnistate-fill'], true), 'only the siblings that exist');
        self::assertFalse($form->get('company')->getConfig()->getMapped(), 'it only helps fill the others');
    }

    private function laToucheOriginale(): Company
    {
        return new Company(
            identifier: '917631715',
            name: 'LA TOUCHE ORIGINALE',
            status: CompanyStatus::ACTIVE,
            country: 'FR',
            source: 'annuaire-entreprises',
            legalName: 'SARL LA TOUCHE ORIGINALE',
            headOffice: new Establishment('91763171500011', true, CompanyStatus::ACTIVE, new Address('1 RUE DU LINDEBUCKEL', '67300', 'SCHILTIGHEIM')),
        );
    }

    /** @param list<Company> $companies */
    private function registry(array $companies, ?\Throwable $failure = null): CompanyRegistryInterface
    {
        return new class($companies, $failure) implements CompanyRegistryInterface {
            public int $asked = 0;

            public function __construct(private readonly array $companies, private readonly ?\Throwable $failure)
            {
            }

            public function name(): string
            {
                return 'fake';
            }

            public function supports(string $identifier): bool
            {
                return true;
            }

            public function company(string $identifier): ?Company
            {
                return $this->companies[0] ?? null;
            }

            public function search(string $query, int $limit = 10): array
            {
                ++$this->asked;
                if (null !== $this->failure) {
                    throw $this->failure;
                }

                return \array_slice($this->companies, 0, $limit);
            }
        };
    }
}
