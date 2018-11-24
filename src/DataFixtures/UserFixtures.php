<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('en_US');

        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->setPassword('$2y$13$TVYGaBVdCJcEfRKhncRGre9TvZobwmmOE88QHaRiqXK3CCeaWcOLq');  // 12
            $user->setEmail($faker->email);
            $user->setUsername('t' . $i);
            $user->setCompany($this->getReference('company_' . $i));
            if($i === 1) {
                $user->setRole('ROLE_ADMIN');
            } else {
                $user->setRole($faker->randomElement(User::ROLES));
            }
            $manager->persist($user);
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
