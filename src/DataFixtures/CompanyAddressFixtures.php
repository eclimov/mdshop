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
            $companyAddress = new CompanyAddress();
            $companyAddress->setAddress($faker->address);
            $companyAddress->setCompany($this->getReference('company_' . $i));

            $manager->persist($companyAddress);
            $this->addReference('companyAddress_' . $i, $companyAddress);
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
