<?php

namespace App\DataFixtures;

use App\Entity\Bank;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class BankFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('en_US');

        for ($i = 0; $i < 3; $i++) {
            $bank = new Bank();
            $bank->setName($faker->word);
            $manager->persist($bank);
            $this->addReference('bank_' . $i, $bank);
        }

        $manager->flush();
    }
}
