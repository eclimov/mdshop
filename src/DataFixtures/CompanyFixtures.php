<?php

namespace App\DataFixtures;

use App\Entity\BankAffiliate;
use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class CompanyFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('en_US');

        for ($i = 0; $i < 20; $i++) {
            $company = new Company();
            $company->setName($faker->company);
            $company->setBankAffiliate($this->getReference('bankAffiliate_' . $faker->numberBetween(0,9)));
            $company->setFiscalCode($faker->randomAscii);
            $company->setIban($faker->iban(373));
            $company->setVat($faker->randomAscii);

            $manager->persist($company);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            BankAffiliateFixtures::class,
        ];
    }
}
