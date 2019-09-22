<?php

namespace App\DataFixtures;

use App\Entity\Invoice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class InvoiceFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('en_US');

        for ($i = 0; $i < 30; $i++) {
            $invoice = new Invoice();
            $invoice->setSeller($this->getReference('company_' . $faker->numberBetween(0,19)));
            $invoice->setBuyer($this->getReference('company_' . $faker->numberBetween(0,19)));
            $invoice->setAttachedDocument($faker->words(2, true));
            $invoice->setCarrier($invoice->getBuyer());
            $invoice->setDeliveryDate($faker->dateTimeBetween('-30 days', 'now'));
            $invoice->setOrderDate($faker->dateTimeBetween('-30 days', 'now'));
            $invoice->setLoadingPoint($faker->randomElement($invoice->getSeller()->getAddresses()->toArray()));
            $invoice->setUnloadingPoint($faker->randomElement($invoice->getBuyer()->getAddresses()->toArray()));
            $invoice->setApprovedByEmployee($faker->randomElement($invoice->getSeller()->getEmployees()->toArray()));
            $invoice->setProcessedByEmployee($faker->randomElement($invoice->getSeller()->getEmployees()->toArray()));
            $invoice->setRecipientName($faker->name);

            $manager->persist($invoice);
            $this->addReference('invoice_' . $i, $invoice);
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
            CompanyAddressFixtures::class,
            CompanyEmployeeFixtures::class,
        ];
    }
}
