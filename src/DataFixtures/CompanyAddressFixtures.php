<?php

namespace App\DataFixtures;

use App\Entity\CompanyAddress;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class CompanyAddressFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('en_US');
        for ($i = 0; $i < 20; $i++) {
            for ($j = 0; $j < 2; $j++) {
                $companyAddress = new CompanyAddress();
                $companyAddress->setAddress($faker->address);
                $companyAddress->setJuridic($j % 2 === 0);
                $companyAddress->setCompany($this->getReference('company_' . $i));

                $manager->persist($companyAddress);
                $this->addReference('companyAddress_' . $i . $j, $companyAddress);
            }
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            CompanyFixtures::class,
        ];
    }
}
