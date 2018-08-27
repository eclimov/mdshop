<?php

namespace App\DataFixtures;

use App\Entity\BankAffiliate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class BankAffiliateFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('en_US');
        for ($i = 0; $i < 10; $i++) {
            $bankAffiliate = new BankAffiliate();
            $bankAffiliate->setAffiliateNumber($faker->randomAscii . $faker->numberBetween(1111, 9999));
            $bankAffiliate->setBank($this->getReference('bank_' . $faker->numberBetween(0,2)));

            $manager->persist($bankAffiliate);
            $this->addReference('bankAffiliate_' . $i, $bankAffiliate);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            BankFixtures::class,
        ];
    }
}
