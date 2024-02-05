<?php

namespace App\DataFixtures\CarDealer;

use App\Entity\PropertyGroup;
use App\Entity\PropertyGroupOption;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class CarDealerPropertyGroupFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->createPropertyGroup('Fahrzeugzustand', [
            [
                'name' => 'Neufahrzeug',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Gebrauchtfahrzeug',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Dienstfahrzeug',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Jahresfahrzeug',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Vorführfahrzeug',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ]
        ], $manager);

        $this->createPropertyGroup('Marke, Modell, Variante', [
            [
                'name' => 'Marke',
                'type' => PropertyGroupOption::TYPE_SELECT,
                'values' => ['BMW', 'Audi']
            ],
            [
                'name' => 'Modell',
                'type' => PropertyGroupOption::TYPE_SELECT,
                'values' => []
            ],
            [
                'name' => 'Variante',
                'type' => PropertyGroupOption::TYPE_TEXT_FIELD,
                'values' => []
            ],
        ], $manager);

        $brandWithModels = [
            [
                'brand' => 'BMW',
                'options' => [
                    [
                        'name' => '1er (alle)',
                        'childOptions' => [
                            '118',
                            '120',
                        ],
                    ],
                    [
                        'name' => '5er (alle)',
                        'childOptions' => [
                            '520',
                            '530',
                            '540',
                            '550'
                        ],
                    ]
                ]
            ],
            [
                'brand' => 'Audi',
                'options' => [
                    [
                        'name' => 'A5',
                    ],
                    [
                        'name' => 'A6',
                    ],
                    [
                        'name' => 'RS (alle)',
                        'childOptions' => [
                            'RS 3',
                            'RS 4',
                            'RS 5',
                            'RS 6',
                        ],
                    ],
                ]
            ],
        ];
        $this->createBrandWithModels($brandWithModels, $manager);

        $this->createPropertyGroup('Fahrzeugtyp', [
            [
                'name' => 'Limousine',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Kombi',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Gelaendewagen/Pickup',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Cabrio/Roadster',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Sportwagen/Coupe',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Van/Kleinbus',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
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

        $mileageValues = $this->getMileageValues();

        /**
         * Custom values that will not show in search page
         */
        $mileageValues[] = '10560';
        $mileageValues[] = '70205';

        $this->createPropertyGroup('Basisdaten', [
            [
                'name' => 'Preis (€)',
                'type' => PropertyGroupOption::TYPE_SELECT_RANGE,
                'values' => [1000, 5000, 10000, 15000, 20000, 30000, 45000, 60000, 75000, 100000, 200000, 300000, 400000, 500000]
            ],
            [
                'name' => 'MwSt',
                'type' => PropertyGroupOption::TYPE_SELECT,
                'values' => ['MwSt. ausweisbar', 'MwSt. nicht ausweisbar']
            ],
            [
                'name' => 'Erstzulassung',
                'type' => PropertyGroupOption::TYPE_SELECT_RANGE,
                'values' => $this->getFirstRegistrationYearValues()
            ],
            [
                'name' => 'Kilometer',
                'type' => PropertyGroupOption::TYPE_SELECT_RANGE,
                'values' => $mileageValues
            ],
            [
                'name' => 'Leistung (in kw)',
                'type' => PropertyGroupOption::TYPE_SELECT_RANGE,
                'values' => $this->getHorsePowerValues()
            ],
        ], $manager);

        $this->createPropertyGroup('Motor', [
            [
                'name' => 'Kraftstoffart',
                'type' => PropertyGroupOption::TYPE_CHECKBOX_GROUP,
                'values' => ['Benzin', 'Diesel', 'PlugIn Hybrid-Benzin', 'Elektro']
            ],
            [
                'name' => 'Getriebe',
                'type' => PropertyGroupOption::TYPE_CHECKBOX_GROUP,
                'values' => ['Automatik', 'Schaltgetriebe']
            ],
        ], $manager);

        $this->createPropertyGroup('Außenfarbe', [
            [
                'name' => 'Schwarz',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Grau',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Weiss',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Blau',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Rot',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Grün',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Silber',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Orange',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Gelb',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Violett',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Metallic',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
        ], $manager);

        $this->createPropertyGroup('Innenausstattung', [
            [
                'name' => 'Schwarz',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Braun',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Grau',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Beige',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Andere',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Anthrazit',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Weiss',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Blau',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Hellgrau',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Material der Innenausstattung',
                'type' => PropertyGroupOption::TYPE_SELECT,
                'values' => ['Alcantara', 'Kunstleder', 'Leder', 'Leder nappa', 'Stoff', 'Teilleder']
            ],
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
                    'Luftfederung',
                    'Niveauregulierung',
                    'Partikelfilter',
                    'Schaltwippen',
                    'Sperrdifferential',
                    'Sportabgasanlage',
                    'Start-Stop-Automatik'
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
                    'Elektr. Fensterheber',
                    'Elektr. Fondsitzverstellung',
                    'Elektr. Heckklappe',
                    'Elektr. Sitze',
                    'Elektr. Verdeck',
                    'Funkfernbedienung',
                    'Geschwindigkeitsbegrenzer',
                    'Heckrollo',
                    'Innenraumfilter',
                    'Keyless Entry',
                    'Keyless Go',
                    'Klimaanlage',
                    'Klimaautomatik',
                    'Klimaautomatik-2-Zonen',
                    'Klimaautomatik-3-Zonen',
                    'Klimaautomatik-4-Zonen',
                    'Klimasitze',
                    'Lederausstattung',
                    'Lederlenkrad',
                    'Lenkradheizung',
                    'Lordosenstütze',
                    'Massagesitze',
                    'Mehrzonenklimaautomatik',
                    'Memory Sitze',
                    'Mittelarmlehne',
                    'Multifunktionslenkrad',
                    'Park Distance Control',
                    'Park Distance Control vo.&hi.',
                    'Seitenrollo',
                    'Servolenkung',
                    'Sitzheizung',
                    'Sitzheizung hinten',
                    'Sportsitze',
                    'Standheizung',
                    'Teilbare Rücksitzlehne',
                    'Tempomat',
                    'Umklappbarer Beifahrersitz',
                    'Zentralverriegelung',
                ]
            ],
            [
                'name' => 'Sicht',
                'type' => PropertyGroupOption::TYPE_MULTI_SELECT,
                'values' => [
                    '360 Grad Kamera',
                    'Adaptives Kurvenlicht',
                    'Autom. abblend. Innenspiegel',
                    'Beheizbare Außenspiegel',
                    'Bi-Xenon Scheinwerfer',
                    'Blendfreies Fernlicht',
                    'Colorverglasung',
                    'Dynamisches Kurvenlicht',
                    'HEAD-UP-Display',
                    'Laserlicht',
                    'LED-Hauptscheinwerfer',
                    'LED-Rückleuchten',
                    'LED-Tagfahrlicht',
                    'Nebelscheinwerfer',
                    'Privacyverglasung',
                    'Rückfahrkamera',
                    'Scheinwerferregulierung',
                    'Scheinwerferreinigung',
                    'Tagfahrlicht',
                    'Xenon Scheinwerfer',
                ]
            ],
            [
                'name' => 'Sicherheit',
                'type' => PropertyGroupOption::TYPE_MULTI_SELECT,
                'values' => [
                    'ABS',
                    'Airbag',
                    'Alarmanlage',
                    'Anhängerstabilisierung',
                    'Antriebsschlupfregelung',
                    'Beifahrerairbag',
                    'ESP',
                    'Isofix Beifahrersitz',
                    'ISOFIX Kindersitzbefestigung',
                    'Knieairbag',
                    'Kopfairbag',
                    'Notrad',
                    'Notrufsystem',
                    'Pannenkit',
                    'Reifendruckkontrolle',
                    'Seitenairbags',
                    'Traktionskontrolle',
                    'Wegfahrsperre',
                ]
            ],
            [
                'name' => 'Assistenzsysteme',
                'type' => PropertyGroupOption::TYPE_MULTI_SELECT,
                'values' => [
                    'Abstandsregeltempomat',
                    'Ausparkassistent',
                    'Bergabfahrassistent',
                    'Berganfahrassistent',
                    'Fernlichtassistent',
                    'Kollisionswarner',
                    'Lichtsensor',
                    'Müdigkeitserkennung',
                    'Notbremsassistent',
                    'Parklenkassistent',
                    'Regensensor',
                    'Spurhalteassistent',
                    'Spurwechselassistent',
                    'Totwinkelassistent',
                    'Verkehrszeichenerkennung',
                ]
            ],
            [
                'name' => 'Entertainment',
                'type' => PropertyGroupOption::TYPE_MULTI_SELECT,
                'values' => [
                    'Android Auto',
                    'Apple CarPlay',
                    'AUX-In',
                    'Bluetooth',
                    'Bowers & Wilkins',
                    'CD',
                    'DAB',
                    'Freisprecheinrichtung',
                    'Harman Kardon',
                    'Induktionsladen',
                    'Monitore in Kopfstützen',
                    'MP3',
                    'Musikstreaming',
                    'Navigationssystem',
                    'Radio',
                    'Soundsystem',
                    'Sprachsteuerung',
                    'Telefonvorbereitung',
                    'Touchscreen',
                    'TV-Funktion',
                    'USB-Anschluss',
                    'WLAN',
                ]
            ],
            [
                'name' => 'Qualität',
                'type' => PropertyGroupOption::TYPE_MULTI_SELECT,
                'values' => [
                    'Dekra Siegel',
                    'Garantie',
                    'GW Plus',
                    'HU/AU neu',
                    'Scheckheftgepflegt',
                ]
            ],
            [
                'name' => 'Sonstige',
                'type' => PropertyGroupOption::TYPE_MULTI_SELECT,
                'values' => [
                    'Abnehmbare Anhängerkupplung',
                    'Allwetterreifen',
                    'Alufelgen',
                    'Anhängerkupplung',
                    'Dachreling',
                    'Dachspoiler',
                    'Dachträger',
                    'El. klappbare Anhängerkupplung',
                    'Elektr. Panoramadach',
                    'Elektrische Parkbremse',
                    'Fahrerprofilauswahl',
                    'Gepäckraumabdeckung',
                    'Gepäckraumabtrennung',
                    'Holzausstattung',
                    'Katalysator',
                    'Klappbare Anhängerkupplung',
                    'Panoramadach',
                    'Raucherpaket',
                    'Schiebedach',
                    'Skisack',
                    'Sommerreifen',
                    'Spoiler',
                    'Sportausstattung',
                    'Sportfahrwerk',
                    'Sportpaket',
                    'Stoßfänger Wagenfarbe',
                    'Sturzbügel',
                    'Trittbretter',
                    'Weisse Blinker',
                    'Windschott',
                    'Winterpaket',
                    'Winterreifen',
                ]
            ],
        ], $manager, true);

        $this->createPropertyGroup('Angebotsdetails', [
            [
                'name' => 'Inserate mit Bildern',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Nichtraucher-Fahrzeug',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Unfallfahrzeug',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'EU-Import',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Reduzierter Preis',
                'type' => PropertyGroupOption::TYPE_CHECKBOX,
                'values' => []
            ],
            [
                'name' => 'Qualitätssiegel',
                'type' => PropertyGroupOption::TYPE_SELECT,
                'values' => ['BMW Premium Selection', 'MINI NEXT']
            ],
            [
                'name' => 'Angebotsnummer',
                'type' => PropertyGroupOption::TYPE_TEXT_FIELD,
                'values' => []
            ],
        ], $manager);

        $this->createPropertyGroup('Umwelt', [
            [
                'name' => 'Emissionsklasse',
                'type' => PropertyGroupOption::TYPE_SELECT,
                'values' => ['Emissionsklasse wählen', 'Euro6', 'Euro6d', 'Euro6dtemp', 'unbekannt']
            ],
            [
                'name' => 'Umweltplakette',
                'type' => PropertyGroupOption::TYPE_SELECT,
                'values' => ['Umweltplakette wählen', 'Grün', 'keine Plakette']
            ],
            [
                'name' => 'Effizienzklasse',
                'type' => PropertyGroupOption::TYPE_SELECT,
                'values' => ['Effizienzklasse wählen', 'A', 'A+', 'A++', 'A+++', 'B', 'C', 'D', 'E']
            ],
        ], $manager);
    }

    private function createPropertyGroup(string $name, array $groupOptions, ObjectManager $manager, bool $isEquipmentGroup = false): void
    {
        $createdPropertyGroup = new PropertyGroup();
        $createdPropertyGroup->setUuid(Uuid::v4());
        $createdPropertyGroup->setName($name);
        $createdPropertyGroup->setIsEquipmentGroup($isEquipmentGroup);
        $createdPropertyGroup->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($createdPropertyGroup);
        $manager->flush();

        foreach ($groupOptions as $groupOption) {
            $createdGroupOption = new PropertyGroupOption();
            $createdGroupOption->setUuid(Uuid::v4());
            $createdGroupOption->setPropertyGroup($createdPropertyGroup);
            $createdGroupOption->setName($groupOption['name']);
            $createdGroupOption->setType($groupOption['type']);
            $createdGroupOption->setShowInDetailPage(true);
            $createdGroupOption->setShowInSearchList(true);
            $createdGroupOption->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($createdGroupOption);
            $manager->flush();

            foreach ($groupOption['values'] as $groupOptionValue) {
                $createdGroupOptionValue = new PropertyGroupOption();
                $createdGroupOptionValue->setUuid(Uuid::v4());
                $createdGroupOptionValue->setParent($createdGroupOption);
                $createdGroupOptionValue->setPropertyGroup($createdPropertyGroup);
                $createdGroupOptionValue->setName($groupOptionValue);
                $createdGroupOptionValue->setType($createdGroupOption->getType());
                $createdGroupOptionValue->setShowInDetailPage(true);
                $createdGroupOptionValue->setShowInSearchList(!in_array($groupOptionValue, ['10560', '70205'], true));
                $createdGroupOptionValue->setCreatedAt(new \DateTimeImmutable());
                $manager->persist($createdGroupOptionValue);
                $manager->flush();
            }
        }
    }

    private function createBrandWithModels(array $brandWithModels, ObjectManager $manager): void
    {
        $propertyGroupOptionRepository = $manager->getRepository(PropertyGroupOption::class);
        $propertyGroupRepository = $manager->getRepository(PropertyGroup::class);
        /** @var PropertyGroup $existingPropertyGroup */
        $existingPropertyGroup = $propertyGroupRepository->findOneBy(['name' => 'Marke, Modell, Variante']);

        foreach ($brandWithModels as $brand) {
            foreach ($brand['options'] as $option) {
                /** @var PropertyGroupOption $existingBrand */
                $existingBrand = $propertyGroupOptionRepository->findOneBy(['name' => $brand['brand']]);

                $propertyGroupOption = new PropertyGroupOption();
                $propertyGroupOption->setUuid(Uuid::v4());
                $propertyGroupOption->setPropertyGroup($existingPropertyGroup);
                $propertyGroupOption->setParent($existingBrand);
                $propertyGroupOption->setName($option['name']);
                $propertyGroupOption->setType(PropertyGroupOption::TYPE_SELECT);
                $propertyGroupOption->setShowInDetailPage(true);
                $propertyGroupOption->setShowInSearchList(true);
                $propertyGroupOption->setCreatedAt(new \DateTimeImmutable());
                $manager->persist($propertyGroupOption);
                $manager->flush();

                foreach ($option['childOptions'] ?? [] as $childOption) {
                    $childPropertyGroupOption = new PropertyGroupOption();
                    $childPropertyGroupOption->setUuid(Uuid::v4());
                    $childPropertyGroupOption->setPropertyGroup($existingPropertyGroup);
                    $childPropertyGroupOption->setParent($propertyGroupOption);
                    $childPropertyGroupOption->setName($childOption);
                    $childPropertyGroupOption->setType(PropertyGroupOption::TYPE_SELECT);
                    $childPropertyGroupOption->setShowInDetailPage(true);
                    $childPropertyGroupOption->setShowInSearchList(true);
                    $childPropertyGroupOption->setCreatedAt(new \DateTimeImmutable());
                    $manager->persist($childPropertyGroupOption);
                    $manager->flush();
                }
            }
        }
    }

    private function getFirstRegistrationYearValues(): array
    {
        $data = [];

        for ($i = 1900; $i <= 1990; $i += 10) {
            $data[] = $i;
        }

        for ($i = 2000; $i <= date('Y'); ++$i) {
            $data[] = $i;
        }

        return $data;
    }

    private function getMileageValues(): array
    {
        $data = [];

        for ($i = 0; $i <= 9000; $i += 1000) {
            $data[] = $i;
        }

        for ($i = 10000; $i <= 200000; $i += 10000) {
            $data[] = $i;
        }

        return $data;
    }

    private function getHorsePowerValues(): array
    {
        return [
            '40 kw (54 PS)',
            '60 kw (82 PS)',
            '80 kw (109 PS)',
            '100 kw (136 PS)',
            '150 kw (204 PS)',
            '200 kw (272 PS)',
            '300 kw (408 PS)',
            '400 kw (544 PS)',
            '500 kw (680 PS)',
            '600 kw (816 PS)',
        ];
    }
}
