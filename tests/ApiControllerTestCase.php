<?php

declare(strict_types=1);

namespace App\Tests;

use App\Dto\ClassifiedDto;
use App\Entity\Classified;
use App\Entity\PropertyGroup;
use App\Entity\PropertyGroupOption;
use App\Repository\PropertyGroupOptionRepository;
use App\Service\ClassifiedService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

abstract class ApiControllerTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected ClassifiedService $classifiedService;

    protected EntityManagerInterface $entityManager;

    protected PropertyGroup $propertyGroupVehicleCondition;

    protected PropertyGroup $propertyGroupBrandWithModel;

    protected PropertyGroup $propertyGroupVehicleType;

    protected PropertyGroup $propertyGroupBasicData;

    protected PropertyGroup $propertyGroupEngine;

    protected PropertyGroup $propertyGroupExteriorColor;

    protected PropertyGroup $propertyGroupInteriorColor;

    protected PropertyGroup $propertyGroupEquipment;

    protected PropertyGroupOptionRepository $propertyGroupOptionRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->classifiedService = static::getContainer()->get(ClassifiedService::class);
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->propertyGroupOptionRepository = $this->entityManager->getRepository(PropertyGroupOption::class);

    }

    protected function createPropertyGroup(string $name, array $groupOptions, bool $isEquipmentGroup = false): PropertyGroup
    {
        $createdPropertyGroup = new PropertyGroup();
        $createdPropertyGroup->setUuid(Uuid::v4());
        $createdPropertyGroup->setName($name);
        $createdPropertyGroup->setIsEquipmentGroup($isEquipmentGroup);
        $createdPropertyGroup->setCreatedAt(new \DateTimeImmutable());
        $this->entityManager->persist($createdPropertyGroup);
        $this->entityManager->flush();

        foreach ($groupOptions as $groupOption) {
            $createdGroupOption = new PropertyGroupOption();
            $createdGroupOption->setUuid(Uuid::v4());
            $createdGroupOption->setPropertyGroup($createdPropertyGroup);
            $createdGroupOption->setName($groupOption['name']);
            $createdGroupOption->setType($groupOption['type']);
            $createdGroupOption->setShowInDetailPage(true);
            $createdGroupOption->setShowInSearchList(true);
            $createdGroupOption->setCreatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($createdGroupOption);
            $this->entityManager->flush();

            foreach ($groupOption['values'] as $groupOptionValue) {
                $createdGroupOptionValue = new PropertyGroupOption();
                $createdGroupOptionValue->setUuid(Uuid::v4());
                $createdGroupOptionValue->setParent($createdGroupOption);
                $createdGroupOptionValue->setPropertyGroup($createdPropertyGroup);
                $createdGroupOptionValue->setName($groupOptionValue);
                $createdGroupOptionValue->setType($createdGroupOption->getType());
                $createdGroupOptionValue->setShowInDetailPage(true);
                $createdGroupOptionValue->setShowInSearchList(true);
                $createdGroupOptionValue->setCreatedAt(new \DateTimeImmutable());
                $this->entityManager->persist($createdGroupOptionValue);
                $this->entityManager->flush();

                $createdPropertyGroup->addGroupOption($createdGroupOptionValue);
            }

            $createdPropertyGroup->addGroupOption($createdGroupOption);
        }

        return $createdPropertyGroup;
    }

    protected function createBrandWithModels(array $brandWithModels): void
    {
        $propertyGroupOptionRepository = $this->entityManager->getRepository(PropertyGroupOption::class);
        $propertyGroupRepository = $this->entityManager->getRepository(PropertyGroup::class);
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
                $propertyGroupOption->setIsModel(true);
                $propertyGroupOption->setCreatedAt(new \DateTimeImmutable());
                $this->entityManager->persist($propertyGroupOption);
                $this->entityManager->flush();

                foreach ($option['childOptions'] ?? [] as $childOption) {
                    $childPropertyGroupOption = new PropertyGroupOption();
                    $childPropertyGroupOption->setUuid(Uuid::v4());
                    $childPropertyGroupOption->setPropertyGroup($existingPropertyGroup);
                    $childPropertyGroupOption->setParent($propertyGroupOption);
                    $childPropertyGroupOption->setName($childOption);
                    $childPropertyGroupOption->setType(PropertyGroupOption::TYPE_SELECT);
                    $childPropertyGroupOption->setShowInDetailPage(true);
                    $childPropertyGroupOption->setShowInSearchList(true);
                    $childPropertyGroupOption->setIsModel(true);
                    $childPropertyGroupOption->setCreatedAt(new \DateTimeImmutable());
                    $this->entityManager->persist($childPropertyGroupOption);
                    $this->entityManager->flush();
                }
            }
        }
    }

    protected function createPropertyGroups(): void
    {
        $propertyGroupVehicleCondition = $this->createPropertyGroup('Fahrzeugzustand', [
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
        ]);

        $propertyGroupBrandWithModel = $this->createPropertyGroup('Marke, Modell, Variante', [
            [
                'name' => 'Marke',
                'type' => PropertyGroupOption::TYPE_SELECT,
                'values' => ['Test Brand', 'Another Test Brand']
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
        ]);

        $brandWithModels = [
            [
                'brand' => 'Test Brand',
                'options' => [
                    [
                        'name' => 'Test Brand with child options',
                        'childOptions' => [
                            'Test Brand Model child option one',
                            'Test Brand Model child option two',
                        ],
                    ],
                ]
            ],
            [
                'brand' => 'Another Test Brand',
                'options' => [
                    [
                        'name' => 'Another Test Brand Model without child options',
                    ],
                    [
                        'name' => 'Another Test Brand Model with child options',
                        'childOptions' => [
                            'Another Test Brand Model child option one',
                            'Another Test Brand Model child option two',
                        ],
                    ],
                ]
            ],
        ];
        $this->createBrandWithModels($brandWithModels);

        $propertyGroupVehicleType = $this->createPropertyGroup('Fahrzeugtyp', [
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
                'values' => ['1', '2', '3', '4', '5']
            ],
            [
                'name' => 'Anzahl Türen',
                'type' => PropertyGroupOption::TYPE_SELECT,
                'values' => ['2/3', '4/5', '6/7']
            ]
        ]);

        $propertyGroupBasicData = $this->createPropertyGroup('Basisdaten', [
            [
                'name' => 'Preis (€)',
                'type' => PropertyGroupOption::TYPE_SELECT_RANGE,
                'values' => ['1000', '5000', '10000']
            ],
            [
                'name' => 'MwSt',
                'type' => PropertyGroupOption::TYPE_SELECT,
                'values' => ['MwSt. ausweisbar', 'MwSt. nicht ausweisbar']
            ],
            [
                'name' => 'Erstzulassung',
                'type' => PropertyGroupOption::TYPE_SELECT_RANGE,
                'values' => ['2018', '2019', '2020', '2021', '2022', '2023']
            ],
            [
                'name' => 'Kilometer',
                'type' => PropertyGroupOption::TYPE_SELECT_RANGE,
                'values' => ['10560', '70205']
            ],
            [
                'name' => 'Leistung',
                'type' => PropertyGroupOption::TYPE_SELECT_RANGE,
                'values' => ['250', '350', '450', '550', '650', '750', '850', '950']
            ],
        ]);

        $propertyGroupEngine = $this->createPropertyGroup('Motor', [
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
        ]);

        $propertyGroupExteriorColor = $this->createPropertyGroup('Außenfarbe', [
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
        ]);

        $propertyGroupInteriorColor = $this->createPropertyGroup('Innenausstattung', [
            [
                'name' => 'Anthrazit',
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
        ]);

        $propertyGroupEquipment = $this->createPropertyGroup('Ausstattung', [
            [
                'name' => 'Technik',
                'type' => PropertyGroupOption::TYPE_MULTI_SELECT,
                'values' => [
                    'Adaptives Dämpfungssystem',
                    'Allrad',
                ]
            ],
            [
                'name' => 'Komfort',
                'type' => PropertyGroupOption::TYPE_MULTI_SELECT,
                'values' => [
                    'Ambiente Beleuchtung',
                    'Beheizbare Frontscheibe',
                ]
            ],
        ], true);

        $this->propertyGroupVehicleCondition = $propertyGroupVehicleCondition;
        $this->propertyGroupBrandWithModel = $propertyGroupBrandWithModel;
        $this->propertyGroupVehicleType = $propertyGroupVehicleType;
        $this->propertyGroupBasicData = $propertyGroupBasicData;
        $this->propertyGroupEngine = $propertyGroupEngine;
        $this->propertyGroupExteriorColor = $propertyGroupExteriorColor;
        $this->propertyGroupInteriorColor = $propertyGroupInteriorColor;
        $this->propertyGroupEquipment = $propertyGroupEquipment;
    }

    protected function createClassified(
        string $name,
        string $description,
        string $price,
        string $offerNumber,
        string $brandId,
        string $modelId,
        array  $propertyGroupOptions,
    ): Classified
    {
        $classifiedDto = new ClassifiedDto();
        $classifiedDto->setId((string)Uuid::v4());
        $classifiedDto->setName($name);
        $classifiedDto->setDescription($description);
        $classifiedDto->setPrice($price);
        $classifiedDto->setOfferNumber($offerNumber);
        $classifiedDto->setSelectedBrandId($brandId);
        $classifiedDto->setSelectedModelId($modelId);

        $propertyGroupOptionIds = [];
        foreach ($propertyGroupOptions as $propertyGroupOption) {
            if ($propertyGroupOption instanceof PropertyGroupOption) {
                $propertyGroupOptionIds[] = (string)$propertyGroupOption->getUuid();
            }
        }

        $classifiedDto->setPropertyGroupOptionIds($propertyGroupOptionIds);

        return $this->classifiedService->createClassified($classifiedDto);
    }

    protected function createClassifieds(): array
    {
        // TODO create more classifieds

        $brandId = $this->getBrandId('Test Brand');
        $modelId = $this->getModelId('Test Brand Model child option one', 'Test Brand with child options');

        $createdClassifieds[] = $this->createClassified(
            'Test Classified',
            'testClassifiedDescription',
            '12345',
            'testOfferNumber',
            $brandId,
            $modelId,
            $this->getClassifiedPropertyGroupOptions(
                'Gebrauchtfahrzeug',
                'Limousine',
                'Automatik',
                '2/3',
                '5',
                '2023',
                '250',
                'Benzin',
                '10560'
            )
        );

        $createdClassifieds[] = $this->createClassified(
            'testClassified2',
            'testClassifiedDescription2',
            '22345',
            'testOfferNumber2',
            $brandId,
            $modelId,
            $this->getClassifiedPropertyGroupOptions(
                'Gebrauchtfahrzeug',
                'Sportwagen/Coupe',
                'Automatik',
                '2/3',
                '5',
                '2023',
                '250',
                'Benzin',
                '10560'
            )
        );

        $modelId = $this->getModelId('Test Brand Model child option two', 'Test Brand with child options');

        $createdClassifieds[] = $this->createClassified(
            'testClassified3',
            'testClassifiedDescription3',
            '22123',
            'testOfferNumber3',
            $brandId,
            $modelId,
            $this->getClassifiedPropertyGroupOptions(
                'Neufahrzeug',
                'Kombi',
                'Automatik',
                '4/5',
                '5',
                '2023',
                '350',
                'Diesel',
                '70205'
            )
        );

        $createdClassifieds[] = $this->createClassified(
            'testClassified4',
            'testClassifiedDescription4',
            '55125',
            'testOfferNumber4',
            $brandId,
            $modelId,
            $this->getClassifiedPropertyGroupOptions(
                'Neufahrzeug',
                'Kombi',
                'Schaltgetriebe',
                '4/5',
                '5',
                '2023',
                '350',
                'Diesel',
                '70205'
            )
        );

        $modelId = $this->getModelId('Test Brand Model child option one', 'Test Brand with child options');

        $createdClassifieds[] = $this->createClassified(
            'testClassified5',
            'testClassifiedDescription5',
            '82345',
            'testOfferNumber5',
            $brandId,
            $modelId,
            $this->getClassifiedPropertyGroupOptions(
                'Neufahrzeug',
                'Limousine',
                'Schaltgetriebe',
                '2/3',
                '5',
                '2023',
                '450',
                'Benzin',
                '10560'
            )
        );

        $createdClassifieds[] = $this->createClassified(
            'testClassified6',
            'testClassifiedDescription6',
            '21346',
            'testOfferNumber6',
            $brandId,
            $modelId,
            $this->getClassifiedPropertyGroupOptions(
                'Neufahrzeug',
                'Limousine',
                'Schaltgetriebe',
                '2/3',
                '5',
                '2018',
                '450',
                'Benzin',
                '10560'
            )
        );

        $createdClassifieds[] = $this->createClassified(
            'testClassified7',
            'testClassifiedDescription7',
            '11345',
            'testOfferNumber7',
            $brandId,
            $modelId,
            $this->getClassifiedPropertyGroupOptions(
                'Neufahrzeug',
                'Limousine',
                'Schaltgetriebe',
                '2/3',
                '5',
                '2019',
                '550',
                'Benzin',
                '10560'
            )
        );

        $createdClassifieds[] = $this->createClassified(
            'testClassified8',
            'testClassifiedDescription8',
            '11548',
            'testOfferNumber8',
            $brandId,
            $modelId,
            $this->getClassifiedPropertyGroupOptions(
                'Neufahrzeug',
                'Limousine',
                'Schaltgetriebe',
                '2/3',
                '5',
                '2020',
                '650',
                'Benzin',
                '10560'
            )
        );

        $brandId = $this->getBrandId('Another Test Brand');
        $modelId = $this->getModelId('Another Test Brand Model child option one', 'Another Test Brand Model with child options');

        $createdClassifieds[] = $this->createClassified(
            'testClassified9',
            'testClassifiedDescription9',
            '1548',
            'testOfferNumber9',
            $brandId,
            $modelId,
            $this->getClassifiedPropertyGroupOptions(
                'Neufahrzeug',
                'Limousine',
                'Automatik',
                '2/3',
                '5',
                '2023',
                '650',
                'Benzin',
                '10560'
            )
        );

        $modelId = $this->getModelId('Another Test Brand Model child option two', 'Another Test Brand Model with child options');

        $createdClassifieds[] = $this->createClassified(
            'testClassified10',
            'testClassifiedDescription10',
            '2000',
            'testOfferNumber10',
            $brandId,
            $modelId,
            $this->getClassifiedPropertyGroupOptions(
                'Neufahrzeug',
                'Limousine',
                'Automatik',
                '2/3',
                '5',
                '2023',
                '750',
                'Benzin',
                '10560'
            )
        );

        $propertyGroupOptions = $this->getClassifiedPropertyGroupOptions(
            'Neufahrzeug',
            'Limousine',
            'Automatik',
            '6/7',
            '5',
            '2019',
            '750',
            'Benzin',
            '10560'
        );

        $propertyGroupOption = $this->getPropertyGroupOption('Ausstattung', 'Adaptives Dämpfungssystem', 'Technik');
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $propertyGroupOption = $this->getPropertyGroupOption('Ausstattung', 'Ambiente Beleuchtung', 'Komfort');
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $brandId = $this->getBrandId('Test Brand');
        $modelId = $this->getModelId('Test Brand Model child option one', 'Test Brand with child options');

        $createdClassifieds[] = $this->createClassified(
            'testClassified11',
            'testClassifiedDescription11',
            '3000',
            'testOfferNumber11',
            $brandId,
            $modelId,
            $propertyGroupOptions
        );

        $propertyGroupOptions = $this->getClassifiedPropertyGroupOptions(
            'Neufahrzeug',
            'Limousine',
            'Automatik',
            '6/7',
            '5',
            '2019',
            '850',
            'Benzin',
            '10560'
        );

        $propertyGroupOption = $this->getPropertyGroupOption('Ausstattung', 'Ambiente Beleuchtung', 'Komfort');
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $createdClassifieds[] = $this->createClassified(
            'testClassified12',
            'testClassifiedDescription12',
            '6000',
            'testOfferNumber12',
            $brandId,
            $modelId,
            $propertyGroupOptions
        );

        $propertyGroupOptions = $this->getClassifiedPropertyGroupOptions(
            'Neufahrzeug',
            'Limousine',
            'Automatik',
            '6/7',
            '5',
            '2019',
            '850',
            'Benzin',
            '10560'
        );

        $propertyGroupOption = $this->getPropertyGroupOption('Außenfarbe', 'Schwarz', null);
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $createdClassifieds[] = $this->createClassified(
            'testClassified13',
            'testClassifiedDescription13',
            '9000000',
            'testOfferNumber13',
            $brandId,
            $modelId,
            $propertyGroupOptions
        );

        $propertyGroupOptions = $this->getClassifiedPropertyGroupOptions(
            'Neufahrzeug',
            'Limousine',
            'Automatik',
            '6/7',
            '5',
            '2019',
            '850',
            'Benzin',
            '10560'
        );

        $propertyGroupOption = $this->getPropertyGroupOption('Außenfarbe', 'Grau', null);
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $createdClassifieds[] = $this->createClassified(
            'testClassified14',
            'testClassifiedDescription14',
            '5012300',
            'testOfferNumber14',
            $brandId,
            $modelId,
            $propertyGroupOptions
        );

        $propertyGroupOptions = $this->getClassifiedPropertyGroupOptions(
            'Neufahrzeug',
            'Limousine',
            'Automatik',
            '6/7',
            '5',
            '2019',
            '850',
            'Benzin',
            '10560'
        );

        $propertyGroupOption = $this->getPropertyGroupOption('Innenausstattung', 'Anthrazit', null);
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $createdClassifieds[] = $this->createClassified(
            'testClassified15',
            'testClassifiedDescription15',
            '9912348',
            'testOfferNumber15',
            $brandId,
            $modelId,
            $propertyGroupOptions
        );

        $propertyGroupOptions = $this->getClassifiedPropertyGroupOptions(
            'Neufahrzeug',
            'Limousine',
            'Automatik',
            '6/7',
            '5',
            '2019',
            '850',
            'Benzin',
            '10560'
        );

        $propertyGroupOption = $this->getPropertyGroupOption('Innenausstattung', 'Blau', null);
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $createdClassifieds[] = $this->createClassified(
            'testClassified16',
            'testClassifiedDescription16',
            '6012300',
            'testOfferNumber16',
            $brandId,
            $modelId,
            $propertyGroupOptions
        );

        return $createdClassifieds;
    }

    protected function getBrandId(string $brand): ?string
    {
        $propertyGroupOption = $this->getPropertyGroupOption('Marke, Modell, Variante', $brand, 'Marke');
        if (!$propertyGroupOption instanceof PropertyGroupOption) {
            return null;
        }

        return (string)$propertyGroupOption->getUuid();
    }

    protected function getModelId(string $model, string $parentModel): ?string
    {
        $propertyGroupOption = $this->getPropertyGroupOption('Marke, Modell, Variante', $model, $parentModel);
        if (!$propertyGroupOption instanceof PropertyGroupOption) {
            return null;
        }

        return (string)$propertyGroupOption->getUuid();
    }

    protected function getClassifiedPropertyGroupOptions(
        string $vehicleCondition,
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

        $propertyGroupOption = $this->getPropertyGroupOption('Fahrzeugtyp', $vehicleType, null);
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $propertyGroupOption = $this->getPropertyGroupOption('Motor', $transmission, 'Getriebe');
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $propertyGroupOption = $this->getPropertyGroupOption('Fahrzeugtyp', $doorCount, 'Anzahl Türen');
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $propertyGroupOption = $this->getPropertyGroupOption('Fahrzeugtyp', $seatCount, 'Anzahl Sitzplätze');
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $propertyGroupOption = $this->getPropertyGroupOption('Basisdaten', $firstRegistrationYear, 'Erstzulassung');
        if ($propertyGroupOption instanceof PropertyGroupOption) {
            $propertyGroupOptions[] = $propertyGroupOption;
        }

        $propertyGroupOption = $this->getPropertyGroupOption('Basisdaten', $horsePower, 'Leistung');
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

    protected function getPropertyGroupOption(string $propertyGroupName, string $groupOptionName, ?string $parentGroupOptionName): ?PropertyGroupOption
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
}