<?php

namespace App\DataFixtures\CarDealer;

use App\Entity\PropertyGroup;
use App\Entity\PropertyGroupOption;
use App\Entity\PropertyGroupOptionValue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CarDealerPropertyGroupFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $this->createPropertyGroup('Fahrzeugzustand', [
            [
                'name' => 'Neufahrzeug',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => [true]
            ],
            [
                'name' => 'Gebrauchtfahrzeug',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => [true]
            ],
            [
                'name' => 'Dienstfahrzeug',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => [true]
            ],
            [
                'name' => 'Jahresfahrzeug',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => [true]
            ],
            [
                'name' => 'Vorführfahrzeug',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => [true]
            ]
        ], $manager);

        $this->createPropertyGroup('Marke, Modell, Variante', [
            [
                'name' => 'Marke',
                'type' => PropertyGroupOption::TYPE_SELECT,
                'values' => ['BMW']
            ],
            [
                'name' => 'Modell',
                'type' => PropertyGroupOption::TYPE_SELECT,
                'values' => ['1er', '2er', '3er', '4er', '5er']
            ],
            [
                'name' => 'Variante',
                'type' => PropertyGroupOption::TYPE_TEXT_FIELD,
                'values' => []
            ],
        ], $manager);

        $this->createPropertyGroup('Fahrzeugtyp', [
            [
                'name' => 'Limousine',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => [true]
            ],
            [
                'name' => 'Kombi',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => [true]
            ],
            [
                'name' => 'Gelaendewagen/Pickup',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => [true]
            ],
            [
                'name' => 'Cabrio/Roadster',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => [true]
            ],
            [
                'name' => 'Sportwagen/Coupe',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => [true]
            ],
            [
                'name' => 'Van/Kleinbus',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => [true]
            ],
            [
                'name' => 'Anzahl Sitzplätze',
                'type' => PropertyGroupOption::TYPE_SELECT_RANGE,
                'values' => [1, 2, 3, 4, 5, 6, 7, 8, 9]
            ],
            [
                'name' => 'Anzahl Türen',
                'type' => PropertyGroupOption::TYPE_SELECT,
                'values' => ['2/3', '4/5', '6/7']
            ]
        ], $manager);

        $this->createPropertyGroup('Ausstattung', [
            [
                'name' => 'Technik',
                'type' => PropertyGroupOption::TYPE_MULTI_SELECT,
                'values' => [
                    'Adaptives Dämpfungssystem',
                    'Allrad',
                    'Bordcomputer',
                    'digitales Kombiinstrument',
                ]
            ],
            [
                'name' => 'Komfort',
                'type' => PropertyGroupOption::TYPE_MULTI_SELECT,
                'values' => [
                    'Ambiente Beleuchtung',
                    'Beheizbare Frontscheibe',
                    'Einstellbare Lenksäule',
                    'Elektr. Aussenspiegel',
                ]
            ],
        ], $manager);
    }

    private function createPropertyGroup(string $name, array $groupOptions, ObjectManager $manager): void
    {
        $createdPropertyGroup = new PropertyGroup();
        $createdPropertyGroup->setName($name);
        $createdPropertyGroup->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($createdPropertyGroup);
        $manager->flush();

        foreach ($groupOptions as $groupOption) {
            $createdGroupOption = new PropertyGroupOption();
            $createdGroupOption->setPropertyGroup($createdPropertyGroup);
            $createdGroupOption->setName($groupOption['name']);
            $createdGroupOption->setType($groupOption['type']);
            $createdGroupOption->setShowInDetailPage(true);
            $createdGroupOption->setShowInSearchList(true);
            $createdGroupOption->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($createdGroupOption);
            $manager->flush();

            foreach ($groupOption['values'] as $groupOptionValue) {
                $createdGroupOptionValue = new PropertyGroupOptionValue();
                $createdGroupOptionValue->setGroupOption($createdGroupOption);
                $createdGroupOptionValue->setValue($groupOptionValue);
                $createdGroupOptionValue->setCreatedAt(new \DateTimeImmutable());
                $manager->persist($createdGroupOptionValue);
                $manager->flush();
            }
        }
    }
}
