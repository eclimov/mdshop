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
            $companyDirector = new CompanyEmployee();
            $companyDirector->setName($faker->name);
            $companyDirector->setPosition('Director');
            $companyDirector->setCompany($this->getReference('company_' . $i));
            $manager->persist($companyDirector);
            $this->addReference('companyEmployee_' . $i . '1', $companyDirector);

            $companyConsultant = new CompanyEmployee();
            $companyConsultant->setName($faker->name);
            $companyConsultant->setPosition('Consultant');
            $companyConsultant->setCompany($this->getReference('company_' . $i));
            $manager->persist($companyConsultant);
            $this->addReference('companyEmployee_' . $i . '2', $companyConsultant);
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
