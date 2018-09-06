<?php

namespace App\DataFixtures;

use App\Entity\CompanyKind;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CompanyKindFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $companyKindSrl = new CompanyKind();
        $companyKindSrl->setName('SRL');
        $manager->persist($companyKindSrl);
        $this->addReference('companyKindSrl', $companyKindSrl);

        $companyKindSa = new CompanyKind();
        $companyKindSa->setName('SA');
        $manager->persist($companyKindSa);
        $this->addReference('companyKindSa', $companyKindSa);

        $manager->flush();
    }
}
