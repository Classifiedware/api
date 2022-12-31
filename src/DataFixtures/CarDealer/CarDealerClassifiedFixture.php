<?php

namespace App\DataFixtures\CarDealer;

use App\Entity\Classified;
use App\Entity\PropertyGroup;
use App\Entity\PropertyGroupOption;
use App\Entity\PropertyGroupOptionValue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CarDealerClassifiedFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $this->createClassifieds($manager);
    }

    public function getDependencies(): array
    {
       return [
           CarDealerPropertyGroupFixture::class,
       ];
    }

    private function createClassifieds(ObjectManager $manager): void
    {
        $classified = new Classified();
        $classified->setName('Good Car');
        $classified->setDescription('This is a good car and has many features including warranty of 24 Months');
        $classified->setPrice(6000000);
        $classified->setOfferNumber('AB12345');
        $classified->setCreatedAt(new \DateTimeImmutable());

        $propertyGroupRepository = $manager->getRepository(PropertyGroup::class);
        $propertyGroupOptionRepository = $manager->getRepository(PropertyGroupOption::class);

        $propertyGroup = $propertyGroupRepository->findOneBy(['name' => 'Fahrzeugzustand']);
        $propertyGroupOption = $propertyGroupOptionRepository->findOneBy([
            'propertyGroup' => $propertyGroup->getId(),
            'name' => 'Neufahrzeug',
        ]);
        $propertyGroupOptionValue = $propertyGroupOption->getOptionValues()->first();

        if ($propertyGroupOptionValue instanceof PropertyGroupOptionValue) {
            $classified->getPropertyGroupOptionValues()->add($propertyGroupOptionValue);
        }

        $propertyGroup = $propertyGroupRepository->findOneBy(['name' => 'Marke, Modell, Variante']);
        $propertyGroupOption = $propertyGroupOptionRepository->findOneBy([
            'propertyGroup' => $propertyGroup->getId(),
            'name' => 'Marke',
        ]);
        $propertyGroupOptionValue = $propertyGroupOption->getOptionValues()->first();

        if ($propertyGroupOptionValue instanceof PropertyGroupOptionValue) {
            $classified->getPropertyGroupOptionValues()->add($propertyGroupOptionValue);
        }

        $propertyGroupOption = $propertyGroupOptionRepository->findOneBy([
            'propertyGroup' => $propertyGroup->getId(),
            'name' => 'Modell',
        ]);
        $propertyGroupOptionValue = $propertyGroupOption->getOptionValues()->first();

        if ($propertyGroupOptionValue instanceof PropertyGroupOptionValue) {
            $classified->getPropertyGroupOptionValues()->add($propertyGroupOptionValue);
        }

        $propertyGroup = $propertyGroupRepository->findOneBy(['name' => 'Fahrzeugtyp']);
        $propertyGroupOption = $propertyGroupOptionRepository->findOneBy([
            'propertyGroup' => $propertyGroup->getId(),
            'name' => 'Limousine',
        ]);
        $propertyGroupOptionValue = $propertyGroupOption->getOptionValues()->first();

        if ($propertyGroupOptionValue instanceof PropertyGroupOptionValue) {
            $classified->getPropertyGroupOptionValues()->add($propertyGroupOptionValue);
        }

        $manager->persist($classified);
        $manager->flush();
    }
}