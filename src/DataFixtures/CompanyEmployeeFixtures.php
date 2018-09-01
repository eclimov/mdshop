<?php

namespace App\DataFixtures;

use App\Entity\CompanyEmployee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class CompanyEmployeeFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('en_US');
        for ($i = 0; $i < 20; $i++) {
            $companyEmployee = new CompanyEmployee();
            $companyEmployee->setName($faker->name);
            $companyEmployee->setPosition($faker->randomElement(['Director',null]));
            $companyEmployee->setCompany($this->getReference('company_' . $i));

            $manager->persist($companyEmployee);
            $this->addReference('companyEmployee_' . $i, $companyEmployee);
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
