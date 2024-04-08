<?php

declare(strict_types=1);

namespace App\DataFixtures\CarDealer;

use App\Entity\Classified;
use App\Entity\PropertyGroupOption;
use App\Repository\PropertyGroupOptionRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class CarDealerClassifiedFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly PropertyGroupOptionRepository $propertyGroupOptionRepository
    ) {
    }

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
        $this->createClassified(
            'BMW 540i Luxury Line LCProf ACC LED DAB HiFi Leder',
            'This is a good car and has many features including warranty of 24 Months',
            8012300,
            'AB12345',
            $this->getClassifiedPropertyGroupOptions(
                'Neufahrzeug',
                'BMW',
                '540',
                '5er (alle)',
                'Limousine',
                'Automatik',
                '4/5',
                '5',
                '2023',
                '200 kw (272 PS)',
                'Benzin',
                '70205'
            ),
            $manager
        );

        $this->createClassified(
            'BMW 520i Sport Line LCProf ACC LED DAB HiFi Leder',
            'This is a good car and has many features including warranty of 24 Months',
            4012300,
            'AB02345',
            $this->getClassifiedPropertyGroupOptions(
                'Gebrauchtfahrzeug',
                'BMW',
                '520',
                '5er (alle)',
                'Limousine',
                'Automatik',
                '4/5',
                '4',
                '2020',
                '150 kw (204 PS)',
                'Diesel',
                '10560'
            ),
            $manager
        );

        $this->createClassified(
            'BMW 118i Sport Line LCProf ACC LED DAB HiFi Leder',
            'This is a good car and has many features including warranty of 24 Months',
            1012300,
            'AB52345',
            $this->getClassifiedPropertyGroupOptions(
                'Gebrauchtfahrzeug',
                'BMW',
                '118',
                '1er (alle)',
                'Sportwagen/Coupe',
                'Schaltgetriebe',
                '2/3',
                '3',
                '2018',
                '100 kw (136 PS)',
                'Benzin',
                '10560'
            ),
            $manager
        );

        $this->createClassified(
            'BMW 120i Premium Line LCProf ACC LED DAB HiFi Leder',
            'This is a good car and has many features including warranty of 24 Months',
            1212300,
            'AB92340',
            $this->getClassifiedPropertyGroupOptions(
                'Gebrauchtfahrzeug',
                'BMW',
                '120',
                '1er (alle)',
                'Sportwagen/Coupe',
                'Automatik',
                '2/3',
                '2',
                '2018',
                '200 kw (272 PS)',
                'Elektro',
                '70205'
            ),
            $manager
        );

        $this->createClassified(
            'BMW 550i Premium Line LCProf ACC LED DAB HiFi Leder',
            'This is a good car and has many features including warranty of 24 Months',
            9912348,
            'AB92345',
            $this->getClassifiedPropertyGroupOptions(
                'Vorführfahrzeug',
                'BMW',
                '550',
                '5er (alle)',
                'Limousine',
                'Automatik',
                '6/7',
                '7',
                '2021',
                '600 kw (816 PS)',
                'Benzin',
                '70205'
            ),
            $manager
        );

        $this->createClassified(
            'Audi A6 Sport Line',
            'This is a good car and has many features including warranty of 24 Months',
            5012300,
            'A6123123',
            $this->getClassifiedPropertyGroupOptions(
                'Gebrauchtfahrzeug',
                'Audi',
                'A6',
                'Audi',
                'Limousine',
                'Automatik',
                '4/5',
                '5',
                '2024',
                '150 kw (204 PS)',
                'Benzin',
                '10560'
            ),
            $manager
        );

        $this->createClassified(
            'Audi A6 Sport Line Ultra',
            'This is a good car and has many features including warranty of 24 Months',
            6012300,
            'A6U123124',
            $this->getClassifiedPropertyGroupOptions(
                'Gebrauchtfahrzeug',
                'Audi',
                'A6',
                'Audi',
                'Limousine',
                'Automatik',
                '4/5',
                '5',
                '2024',
                '300 kw (408 PS)',
                'Benzin',
                '10560'
            ),
            $manager
        );

        $this->createClassified(
            'Audi RS3 Soft Close Self Parking',
            'This is a good car and has many features including warranty of 24 Months',
            9000000,
            'RS3S123123',
            $this->getClassifiedPropertyGroupOptions(
                'Gebrauchtfahrzeug',
                'Audi',
                'RS 3',
                'RS (alle)',
                'Sportwagen/Coupe',
                'Schaltgetriebe',
                '4/5',
                '5',
                '2024',
                '400 kw (544 PS)',
                'Benzin',
                '70205'
            ),
            $manager
        );
    }

    private function getClassifiedPropertyGroupOptions(
        string $vehicleCondition,
        string $brand,
        string $model,
        string $parentModel,
        string $vehicleType,
        string $transmission,
        string $doorCount,
        string $seatCount,
        string $firstRegistrationYear,
        string $horsePower,
        string $fuelType,
        string $mileage
    ): array
    {
        $propertyGroupOptions = [];

        $propertyGroupOption = $this->getPropertyGroupOption('Fahrzeugzustand', $vehicleCondition, null);
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $propertyGroupOption = $this->getPropertyGroupOption('Marke, Modell, Variante', $brand, 'Marke');
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $propertyGroupOption = $this->getPropertyGroupOption('Marke, Modell, Variante', $model, $parentModel);
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $propertyGroupOption = $this->getPropertyGroupOption('Fahrzeugtyp', $vehicleType, null);
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $propertyGroupOption = $this->getPropertyGroupOption('Motor', $transmission, 'Getriebe');
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $propertyGroupOption = $this->getPropertyGroupOption( 'Fahrzeugtyp', $doorCount, 'Anzahl Türen');
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $propertyGroupOption = $this->getPropertyGroupOption( 'Fahrzeugtyp', $seatCount, 'Anzahl Sitzplätze');
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $propertyGroupOption = $this->getPropertyGroupOption('Basisdaten', $firstRegistrationYear, 'Erstzulassung');
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $propertyGroupOption = $this->getPropertyGroupOption('Basisdaten', $horsePower, 'Leistung (in kw)');
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $propertyGroupOption = $this->getPropertyGroupOption('Motor', $fuelType, 'Kraftstoffart');
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $propertyGroupOption = $this->getPropertyGroupOption('Basisdaten', $mileage, 'Kilometer');
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        return $propertyGroupOptions;
    }

    private function getPropertyGroupOption(string $propertyGroupName, string $groupOptionName, ?string $parentGroupOptionName): ?PropertyGroupOption
    {
        try {
            $propertyGroupOptionQuery = $this->propertyGroupOptionRepository->createQueryBuilder('pgo')
                ->join('pgo.propertyGroup', 'pg')
                ->where('pg.name = :propertyGroupName')
                ->andWhere('pgo.name = :groupOptionName')
                ->setParameter('propertyGroupName', $propertyGroupName)
                ->setParameter('groupOptionName', $groupOptionName);

            if ($parentGroupOptionName !== null) {
                $propertyGroupOptionQuery->join('pgo.parent', 'pgop');
                $propertyGroupOptionQuery->andWhere('pgop.name = :parentGroupOptionName');
                $propertyGroupOptionQuery->setParameter('parentGroupOptionName', $parentGroupOptionName);
            }

            $propertyGroupOption = $propertyGroupOptionQuery->getQuery()->getOneOrNullResult();

            if (!$propertyGroupOption instanceof PropertyGroupOption) {
                return null;
            }

            return $propertyGroupOption;
        } catch (\Throwable) {
            return null;
        }
    }

    private function createClassified(
        string $name,
        string $description,
        int $price,
        string $offerNumber,
        array $propertyGroupOptions,
        ObjectManager $manager
    ): void {
        $classified = new Classified();
        $classified->setUuid(Uuid::v4());
        $classified->setName($name);
        $classified->setDescription($description);
        $classified->setPrice($price);
        $classified->setOfferNumber($offerNumber);
        $classified->setCreatedAt(new \DateTimeImmutable());

        foreach ($propertyGroupOptions as $propertyGroupOption) {
            if ($propertyGroupOption instanceof PropertyGroupOption) {
                $classified->getPropertyGroupOptions()->add($propertyGroupOption);
            }
        }

        $manager->persist($classified);
        $manager->flush();
    }
}