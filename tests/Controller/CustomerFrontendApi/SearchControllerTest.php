<?php

declare(strict_types=1);

namespace App\Tests\Controller\CustomerFrontendApi;

use App\Dto\ClassifiedDto;
use App\Dto\ClassifiedPropertyGroupOptionDto;
use App\Entity\Classified;
use App\Entity\PropertyGroup;
use App\Entity\PropertyGroupOption;
use App\Repository\PropertyGroupOptionRepository;
use App\Service\ClassifiedService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

class SearchControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    private ClassifiedService $classifiedService;

    private EntityManagerInterface $entityManager;

    private PropertyGroup $propertyGroupVehicleCondition;

    private PropertyGroup $propertyGroupBrandWithModel;

    private PropertyGroup $propertyGroupVehicleType;

    private PropertyGroup $propertyGroupBasicData;

    private PropertyGroup $propertyGroupEngine;

    private PropertyGroup $propertyGroupExteriorColor;

    private PropertyGroup $propertyGroupInteriorColor;

    private PropertyGroup $propertyGroupEquipment;

    private PropertyGroupOptionRepository $propertyGroupOptionRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->classifiedService = static::getContainer()->get(ClassifiedService::class);
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->propertyGroupOptionRepository = $this->entityManager->getRepository(PropertyGroupOption::class);

        $this->createPropertyGroups();
    }

    public function testSearchPropertyOptions(): void
    {
        $this->client->request('GET', '/customer-frontend-api/search/property/options');

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        /** @var array<PropertyGroupOption> $brandModels */
        $brandModels = $this->entityManager->getRepository(PropertyGroupOption::class)->findBy(['isModel' => true]);

        static::assertSame([
            'data' => [
                [
                    'name' => 'Fahrzeugzustand',
                    'isEquipmentGroup' => false,
                    'groupOptions' => [
                        [
                            'id' => (string)$this->propertyGroupVehicleCondition->getGroupOptions()->first()->getUuid(),
                            'name' => 'Neufahrzeug',
                            'type' => 'checkbox',
                            'optionValues' => [],
                        ],
                        [
                            'id' => (string)$this->propertyGroupVehicleCondition->getGroupOptions()->get(1)->getUuid(),
                            'name' => 'Gebrauchtfahrzeug',
                            'type' => 'checkbox',
                            'optionValues' => [],
                        ]
                    ]
                ],
                [
                    'name' => 'Marke, Modell, Variante',
                    'isEquipmentGroup' => false,
                    'groupOptions' => [
                        [
                            'id' => (string)$this->propertyGroupBrandWithModel->getGroupOptions()->get(2)->getUuid(),
                            'name' => 'Marke',
                            'type' => 'select',
                            'optionValues' => [
                                [
                                    'id' => (string)$this->propertyGroupBrandWithModel->getGroupOptions()->get(0)->getUuid(),
                                    'value' => 'Test Brand'
                                ],
                                [
                                    'id' => (string)$this->propertyGroupBrandWithModel->getGroupOptions()->get(1)->getUuid(),
                                    'value' => 'Another Test Brand'
                                ],
                            ]
                        ],
                        [
                            'id' => (string)$this->propertyGroupBrandWithModel->getGroupOptions()->get(3)->getUuid(),
                            'name' => 'Modell',
                            'type' => 'select',
                            'optionValues' => [
                                [
                                    'id' => (string)$brandModels[0]->getUuid(),
                                    'parentName' => 'Test Brand',
                                    'childName' => 'Test Brand with child options',
                                    'values' => [
                                        [
                                            'id' => (string)$brandModels[1]->getUuid(),
                                            'value' => 'Test Brand Model child option one',
                                        ],
                                        [
                                            'id' => (string)$brandModels[2]->getUuid(),
                                            'value' => 'Test Brand Model child option two',
                                        ],
                                    ]
                                ],
                                [
                                    'id' => (string)$brandModels[3]->getUuid(),
                                    'parentName' => 'Another Test Brand',
                                    'value' => 'Another Test Brand Model without child options',
                                ],
                                [
                                    'id' => (string)$brandModels[4]->getUuid(),
                                    'parentName' => 'Another Test Brand',
                                    'childName' => 'Another Test Brand Model with child options',
                                    'values' => [
                                        [
                                            'id' => (string)$brandModels[5]->getUuid(),
                                            'value' => 'Another Test Brand Model child option one',
                                        ],
                                        [
                                            'id' => (string)$brandModels[6]->getUuid(),
                                            'value' => 'Another Test Brand Model child option two',
                                        ],
                                    ]
                                ],
                            ]
                        ],
                        [
                            'id' => (string)$this->propertyGroupBrandWithModel->getGroupOptions()->get(4)->getUuid(),
                            'name' => 'Variante',
                            'type' => 'textField',
                            'optionValues' => [],
                        ],
                    ]
                ],
                [
                    'name' => 'Fahrzeugtyp',
                    'isEquipmentGroup' => false,
                    'groupOptions' => [
                        [
                            'id' => (string)$this->propertyGroupVehicleType->getGroupOptions()->get(0)->getUuid(),
                            'name' => 'Limousine',
                            'type' => 'checkbox',
                            'optionValues' => [],
                        ],
                        [
                            'id' => (string)$this->propertyGroupVehicleType->getGroupOptions()->get(1)->getUuid(),
                            'name' => 'Kombi',
                            'type' => 'checkbox',
                            'optionValues' => [],
                        ],
                        [
                            'id' => (string)$this->propertyGroupVehicleType->getGroupOptions()->get(2)->getUuid(),
                            'name' => 'Gelaendewagen/Pickup',
                            'type' => 'checkbox',
                            'optionValues' => [],
                        ],
                        [
                            'id' => (string)$this->propertyGroupVehicleType->getGroupOptions()->get(3)->getUuid(),
                            'name' => 'Cabrio/Roadster',
                            'type' => 'checkbox',
                            'optionValues' => [],
                        ],
                        [
                            'id' => (string)$this->propertyGroupVehicleType->getGroupOptions()->get(4)->getUuid(),
                            'name' => 'Sportwagen/Coupe',
                            'type' => 'checkbox',
                            'optionValues' => [],
                        ],
                        [
                            'id' => (string)$this->propertyGroupVehicleType->getGroupOptions()->get(5)->getUuid(),
                            'name' => 'Van/Kleinbus',
                            'type' => 'checkbox',
                            'optionValues' => [],
                        ],
                        [
                            'id' => (string)$this->propertyGroupVehicleType->getGroupOptions()->get(11)->getUuid(),
                            'name' => 'Anzahl Sitzplätze',
                            'type' => 'selectRange',
                            'optionValues' => [
                                [
                                    'id' => (string)$this->propertyGroupVehicleType->getGroupOptions()->get(6)->getUuid(),
                                    'value' => '1'
                                ],
                                [
                                    'id' => (string)$this->propertyGroupVehicleType->getGroupOptions()->get(7)->getUuid(),
                                    'value' => '2'
                                ],
                                [
                                    'id' => (string)$this->propertyGroupVehicleType->getGroupOptions()->get(8)->getUuid(),
                                    'value' => '3'
                                ],
                                [
                                    'id' => (string)$this->propertyGroupVehicleType->getGroupOptions()->get(9)->getUuid(),
                                    'value' => '4'
                                ],
                                [
                                    'id' => (string)$this->propertyGroupVehicleType->getGroupOptions()->get(10)->getUuid(),
                                    'value' => '5'
                                ],
                            ]
                        ],
                        [
                            'id' => (string)$this->propertyGroupVehicleType->getGroupOptions()->get(15)->getUuid(),
                            'name' => 'Anzahl Türen',
                            'type' => 'select',
                            'optionValues' => [
                                [
                                    'id' => (string)$this->propertyGroupVehicleType->getGroupOptions()->get(12)->getUuid(),
                                    'value' => '2/3'
                                ],
                                [
                                    'id' => (string)$this->propertyGroupVehicleType->getGroupOptions()->get(13)->getUuid(),
                                    'value' => '4/5'
                                ],
                                [
                                    'id' => (string)$this->propertyGroupVehicleType->getGroupOptions()->get(14)->getUuid(),
                                    'value' => '6/7'
                                ],
                            ]
                        ],
                    ]
                ],
                [
                    'name' => 'Basisdaten',
                    'isEquipmentGroup' => false,
                    'groupOptions' => [
                        [
                            'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(3)->getUuid(),
                            'name' => 'Preis (€)',
                            'type' => 'selectRange',
                            'optionValues' => [
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(0)->getUuid(),
                                    'value' => '1000',
                                ],
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(1)->getUuid(),
                                    'value' => '5000',
                                ],
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(2)->getUuid(),
                                    'value' => '10000',
                                ],
                            ]
                        ],
                        [
                            'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(6)->getUuid(),
                            'name' => 'MwSt',
                            'type' => 'select',
                            'optionValues' => [
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(4)->getUuid(),
                                    'value' => 'MwSt. ausweisbar',
                                ],
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(5)->getUuid(),
                                    'value' => 'MwSt. nicht ausweisbar',
                                ],
                            ]
                        ],
                        [
                            'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(13)->getUuid(),
                            'name' => 'Erstzulassung',
                            'type' => 'selectRange',
                            'optionValues' => [
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(7)->getUuid(),
                                    'value' => '2018',
                                ],
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(8)->getUuid(),
                                    'value' => '2019',
                                ],
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(9)->getUuid(),
                                    'value' => '2020',
                                ],
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(10)->getUuid(),
                                    'value' => '2021',
                                ],
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(11)->getUuid(),
                                    'value' => '2022',
                                ],
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(12)->getUuid(),
                                    'value' => '2023',
                                ],
                            ]
                        ],
                        [
                            'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(16)->getUuid(),
                            'name' => 'Kilometer',
                            'type' => 'selectRange',
                            'optionValues' => [
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(14)->getUuid(),
                                    'value' => '10560',
                                ],
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(15)->getUuid(),
                                    'value' => '70205',
                                ],
                            ]
                        ],
                        [
                            'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(25)->getUuid(),
                            'name' => 'Leistung',
                            'type' => 'selectRange',
                            'optionValues' => [
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(17)->getUuid(),
                                    'value' => '250',
                                ],
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(18)->getUuid(),
                                    'value' => '350',
                                ],
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(19)->getUuid(),
                                    'value' => '450',
                                ],
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(20)->getUuid(),
                                    'value' => '550',
                                ],
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(21)->getUuid(),
                                    'value' => '650',
                                ],
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(22)->getUuid(),
                                    'value' => '750',
                                ],
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(23)->getUuid(),
                                    'value' => '850',
                                ],
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(24)->getUuid(),
                                    'value' => '950',
                                ],
                            ]
                        ],
                    ],
                ],
                [
                    'name' => 'Motor',
                    'isEquipmentGroup' => false,
                    'groupOptions' => [
                        [
                            'id' => (string)$this->propertyGroupEngine->getGroupOptions()->get(4)->getUuid(),
                            'name' => 'Kraftstoffart',
                            'type' => 'checkboxGroup',
                            'optionValues' => [
                                [
                                    'id' => (string)$this->propertyGroupEngine->getGroupOptions()->get(0)->getUuid(),
                                    'value' => 'Benzin'
                                ],
                                [
                                    'id' => (string)$this->propertyGroupEngine->getGroupOptions()->get(1)->getUuid(),
                                    'value' => 'Diesel'
                                ],
                                [
                                    'id' => (string)$this->propertyGroupEngine->getGroupOptions()->get(2)->getUuid(),
                                    'value' => 'PlugIn Hybrid-Benzin'
                                ],
                                [
                                    'id' => (string)$this->propertyGroupEngine->getGroupOptions()->get(3)->getUuid(),
                                    'value' => 'Elektro'
                                ],
                            ]
                        ],
                        [
                            'id' => (string)$this->propertyGroupEngine->getGroupOptions()->get(7)->getUuid(),
                            'name' => 'Getriebe',
                            'type' => 'checkboxGroup',
                            'optionValues' => [
                                [
                                    'id' => (string)$this->propertyGroupEngine->getGroupOptions()->get(5)->getUuid(),
                                    'value' => 'Automatik'
                                ],
                                [
                                    'id' => (string)$this->propertyGroupEngine->getGroupOptions()->get(6)->getUuid(),
                                    'value' => 'Schaltgetriebe'
                                ],
                            ]
                        ],
                    ]
                ],
                [
                    'name' => 'Außenfarbe',
                    'isEquipmentGroup' => false,
                    'groupOptions' => [
                        [
                            'id' => (string)$this->propertyGroupExteriorColor->getGroupOptions()->get(0)->getUuid(),
                            'name' => 'Schwarz',
                            'type' => 'checkbox',
                            'optionValues' => [],
                        ],
                        [
                            'id' => (string)$this->propertyGroupExteriorColor->getGroupOptions()->get(1)->getUuid(),
                            'name' => 'Grau',
                            'type' => 'checkbox',
                            'optionValues' => [],
                        ],
                        [
                            'id' => (string)$this->propertyGroupExteriorColor->getGroupOptions()->get(2)->getUuid(),
                            'name' => 'Weiss',
                            'type' => 'checkbox',
                            'optionValues' => [],
                        ],
                    ]
                ],
                [
                    'name' => 'Innenausstattung',
                    'isEquipmentGroup' => false,
                    'groupOptions' => [
                        [
                            'id' => (string)$this->propertyGroupInteriorColor->getGroupOptions()->get(0)->getUuid(),
                            'name' => 'Anthrazit',
                            'type' => 'checkbox',
                            'optionValues' => [],
                        ],
                        [
                            'id' => (string)$this->propertyGroupInteriorColor->getGroupOptions()->get(1)->getUuid(),
                            'name' => 'Blau',
                            'type' => 'checkbox',
                            'optionValues' => [],
                        ],
                        [
                            'id' => (string)$this->propertyGroupInteriorColor->getGroupOptions()->get(2)->getUuid(),
                            'name' => 'Hellgrau',
                            'type' => 'checkbox',
                            'optionValues' => [],
                        ],
                    ]
                ],
                [
                    'name' => 'Ausstattung',
                    'isEquipmentGroup' => true,
                    'groupOptions' => [
                        [
                            'id' => (string)$this->propertyGroupEquipment->getGroupOptions()->get(2)->getUuid(),
                            'name' => 'Technik',
                            'type' => 'multiSelect',
                            'optionValues' => [
                                [
                                    'id' => (string)$this->propertyGroupEquipment->getGroupOptions()->get(0)->getUuid(),
                                    'value' => 'Adaptives Dämpfungssystem'
                                ],
                                [
                                    'id' => (string)$this->propertyGroupEquipment->getGroupOptions()->get(1)->getUuid(),
                                    'value' => 'Allrad'
                                ],
                            ]
                        ],
                        [
                            'id' => (string)$this->propertyGroupEquipment->getGroupOptions()->get(5)->getUuid(),
                            'name' => 'Komfort',
                            'type' => 'multiSelect',
                            'optionValues' => [
                                [
                                    'id' => (string)$this->propertyGroupEquipment->getGroupOptions()->get(3)->getUuid(),
                                    'value' => 'Ambiente Beleuchtung'
                                ],
                                [
                                    'id' => (string)$this->propertyGroupEquipment->getGroupOptions()->get(4)->getUuid(),
                                    'value' => 'Beheizbare Frontscheibe'
                                ],
                            ]
                        ],
                    ]
                ],
            ]
        ], $response);
    }

    public function testSearchClassifiedWithoutSearchCriteria(): void
    {
        $this->createClassifieds();

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(['page' => 1])
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(10, $response['data']);

        // TODO: assert classifieds i was too lazy
    }

    public function testSearchClassifiedWithVehicleCondition(): void
    {
        $createdClassifieds = $this->createClassifieds();

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'propertyGroupOptionIds' => [
                        // Gebrauchtfahrzeug
                        (string)$this->propertyGroupVehicleCondition->getGroupOptions()->get(1)->getUuid(),
                    ]
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(2, $response['data']);

        static::assertSame([
            'data' => [
                [
                    'id' => (string)$createdClassifieds[0]->getUuid(),
                    'name' => 'Test Classified',
                    'description' => 'testClassifiedDescription',
                    'price' => '123,45',
                    'offerNumber' => 'testOfferNumber',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Gebrauchtfahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '250',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[1]->getUuid(),
                    'name' => 'testClassified2',
                    'description' => 'testClassifiedDescription2',
                    'price' => '223,45',
                    'offerNumber' => 'testOfferNumber2',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Gebrauchtfahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Sportwagen/Coupe',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '250',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
            ]
        ], $response);
    }

    public function testSearchClassifiedWithVehicleType(): void
    {
        $createdClassifieds = $this->createClassifieds();

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'propertyGroupOptionIds' => [
                        // Sportwagen/Coupe
                        (string)$this->propertyGroupVehicleType->getGroupOptions()->get(4)->getUuid(),
                    ]
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(1, $response['data']);

        static::assertSame([
            'data' => [
                [
                    'id' => (string)$createdClassifieds[1]->getUuid(),
                    'name' => 'testClassified2',
                    'description' => 'testClassifiedDescription2',
                    'price' => '223,45',
                    'offerNumber' => 'testOfferNumber2',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Gebrauchtfahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Sportwagen/Coupe',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '250',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ]
            ]
        ], $response);
    }

    public function testSearchClassifiedWithFuelType(): void
    {
        $createdClassifieds = $this->createClassifieds();

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'propertyGroupOptionIds' => [
                        // Diesel
                        (string)$this->propertyGroupEngine->getGroupOptions()->get(1)->getUuid(),
                    ]
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(2, $response['data']);

        static::assertSame([
            'data' => [
                [
                    'id' => (string)$createdClassifieds[2]->getUuid(),
                    'name' => 'testClassified3',
                    'description' => 'testClassifiedDescription3',
                    'price' => '221,23',
                    'offerNumber' => 'testOfferNumber3',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option two',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Kombi',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '4/5',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '70205',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '350',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Diesel',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[3]->getUuid(),
                    'name' => 'testClassified4',
                    'description' => 'testClassifiedDescription4',
                    'price' => '551,25',
                    'offerNumber' => 'testOfferNumber4',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option two',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Kombi',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '4/5',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '70205',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '350',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Diesel',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ]
            ]
        ], $response);
    }

    public function testSearchClassifiedWithTransmission(): void
    {
        $createdClassifieds = $this->createClassifieds();

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'propertyGroupOptionIds' => [
                        // Schaltgetriebe
                        (string)$this->propertyGroupEngine->getGroupOptions()->get(6)->getUuid(),
                    ]
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(5, $response['data']);

        static::assertSame([
            'data' => [
                [
                    'id' => (string)$createdClassifieds[3]->getUuid(),
                    'name' => 'testClassified4',
                    'description' => 'testClassifiedDescription4',
                    'price' => '551,25',
                    'offerNumber' => 'testOfferNumber4',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option two',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Kombi',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '4/5',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '70205',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '350',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Diesel',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[4]->getUuid(),
                    'name' => 'testClassified5',
                    'description' => 'testClassifiedDescription5',
                    'price' => '823,45',
                    'offerNumber' => 'testOfferNumber5',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '450',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[5]->getUuid(),
                    'name' => 'testClassified6',
                    'description' => 'testClassifiedDescription6',
                    'price' => '213,46',
                    'offerNumber' => 'testOfferNumber6',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2018',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '450',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[6]->getUuid(),
                    'name' => 'testClassified7',
                    'description' => 'testClassifiedDescription7',
                    'price' => '113,45',
                    'offerNumber' => 'testOfferNumber7',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '550',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[7]->getUuid(),
                    'name' => 'testClassified8',
                    'description' => 'testClassifiedDescription8',
                    'price' => '115,48',
                    'offerNumber' => 'testOfferNumber8',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2020',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '650',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
            ]
        ], $response);
    }

    public function testSearchClassifiedWithDoorCount(): void
    {
        $createdClassifieds = $this->createClassifieds();

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'propertyGroupOptionIdsSelectFrom' => [
                        // Anzahl Türen 2/3
                        (string)$this->propertyGroupVehicleType->getGroupOptions()->get(12)->getUuid(),
                    ]
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(8, $response['data']);

        static::assertSame([
            'data' => [
                [
                    'id' => (string)$createdClassifieds[0]->getUuid(),
                    'name' => 'Test Classified',
                    'description' => 'testClassifiedDescription',
                    'price' => '123,45',
                    'offerNumber' => 'testOfferNumber',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Gebrauchtfahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '250',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[1]->getUuid(),
                    'name' => 'testClassified2',
                    'description' => 'testClassifiedDescription2',
                    'price' => '223,45',
                    'offerNumber' => 'testOfferNumber2',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Gebrauchtfahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Sportwagen/Coupe',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '250',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[4]->getUuid(),
                    'name' => 'testClassified5',
                    'description' => 'testClassifiedDescription5',
                    'price' => '823,45',
                    'offerNumber' => 'testOfferNumber5',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '450',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[5]->getUuid(),
                    'name' => 'testClassified6',
                    'description' => 'testClassifiedDescription6',
                    'price' => '213,46',
                    'offerNumber' => 'testOfferNumber6',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2018',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '450',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[6]->getUuid(),
                    'name' => 'testClassified7',
                    'description' => 'testClassifiedDescription7',
                    'price' => '113,45',
                    'offerNumber' => 'testOfferNumber7',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '550',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[7]->getUuid(),
                    'name' => 'testClassified8',
                    'description' => 'testClassifiedDescription8',
                    'price' => '115,48',
                    'offerNumber' => 'testOfferNumber8',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2020',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '650',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[8]->getUuid(),
                    'name' => 'testClassified9',
                    'description' => 'testClassifiedDescription9',
                    'price' => '15,48',
                    'offerNumber' => 'testOfferNumber9',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Another Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Another Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '650',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[9]->getUuid(),
                    'name' => 'testClassified10',
                    'description' => 'testClassifiedDescription10',
                    'price' => '20,00',
                    'offerNumber' => 'testOfferNumber10',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Another Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Another Test Brand Model child option two',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '750',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
            ]
        ], $response);
    }

    public function testSearchClassifiedWithRegistrationYearFromFilter()
    {
        $createdClassifieds = $this->createClassifieds();

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'propertyGroupOptionIdsSelectFrom' => [
                        // Erstzulassung 2020
                        (string)$this->propertyGroupBasicData->getGroupOptions()->get(9)->getUuid(),
                    ]
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(8, $response['data']);

        static::assertSame([
            'data' => [
                [
                    'id' => (string)$createdClassifieds[0]->getUuid(),
                    'name' => 'Test Classified',
                    'description' => 'testClassifiedDescription',
                    'price' => '123,45',
                    'offerNumber' => 'testOfferNumber',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Gebrauchtfahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '250',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[1]->getUuid(),
                    'name' => 'testClassified2',
                    'description' => 'testClassifiedDescription2',
                    'price' => '223,45',
                    'offerNumber' => 'testOfferNumber2',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Gebrauchtfahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Sportwagen/Coupe',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '250',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[2]->getUuid(),
                    'name' => 'testClassified3',
                    'description' => 'testClassifiedDescription3',
                    'price' => '221,23',
                    'offerNumber' => 'testOfferNumber3',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option two',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Kombi',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '4/5',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '70205',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '350',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Diesel',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[3]->getUuid(),
                    'name' => 'testClassified4',
                    'description' => 'testClassifiedDescription4',
                    'price' => '551,25',
                    'offerNumber' => 'testOfferNumber4',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option two',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Kombi',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '4/5',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '70205',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '350',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Diesel',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[4]->getUuid(),
                    'name' => 'testClassified5',
                    'description' => 'testClassifiedDescription5',
                    'price' => '823,45',
                    'offerNumber' => 'testOfferNumber5',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '450',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[7]->getUuid(),
                    'name' => 'testClassified8',
                    'description' => 'testClassifiedDescription8',
                    'price' => '115,48',
                    'offerNumber' => 'testOfferNumber8',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2020',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '650',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[8]->getUuid(),
                    'name' => 'testClassified9',
                    'description' => 'testClassifiedDescription9',
                    'price' => '15,48',
                    'offerNumber' => 'testOfferNumber9',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Another Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Another Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '650',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[9]->getUuid(),
                    'name' => 'testClassified10',
                    'description' => 'testClassifiedDescription10',
                    'price' => '20,00',
                    'offerNumber' => 'testOfferNumber10',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Another Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Another Test Brand Model child option two',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '750',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
            ]
        ], $response);
    }

    public function testSearchClassifiedWithRegistrationYearWithBothFilters()
    {
        $createdClassifieds = $this->createClassifieds();

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'propertyGroupOptionIdsSelectFrom' => [
                        // Erstzulassung 2018
                        (string)$this->propertyGroupBasicData->getGroupOptions()->get(7)->getUuid(),
                    ],
                    'propertyGroupOptionIdsSelectTo' => [
                        // Erstzulassung 2020
                        (string)$this->propertyGroupBasicData->getGroupOptions()->get(9)->getUuid(),
                    ]
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(9, $response['data']);

        static::assertSame([
            'data' => [
                [
                    'id' => (string)$createdClassifieds[5]->getUuid(),
                    'name' => 'testClassified6',
                    'description' => 'testClassifiedDescription6',
                    'price' => '213,46',
                    'offerNumber' => 'testOfferNumber6',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2018',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '450',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[6]->getUuid(),
                    'name' => 'testClassified7',
                    'description' => 'testClassifiedDescription7',
                    'price' => '113,45',
                    'offerNumber' => 'testOfferNumber7',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '550',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[7]->getUuid(),
                    'name' => 'testClassified8',
                    'description' => 'testClassifiedDescription8',
                    'price' => '115,48',
                    'offerNumber' => 'testOfferNumber8',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2020',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '650',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[10]->getUuid(),
                    'name' => 'testClassified11',
                    'description' => 'testClassifiedDescription11',
                    'price' => '30,00',
                    'offerNumber' => 'testOfferNumber11',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '750',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Technik',
                            'value' => 'Adaptives Dämpfungssystem',
                        ],
                        [
                            'optionName' => 'Komfort',
                            'value' => 'Ambiente Beleuchtung',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[11]->getUuid(),
                    'name' => 'testClassified12',
                    'description' => 'testClassifiedDescription12',
                    'price' => '60,00',
                    'offerNumber' => 'testOfferNumber12',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Komfort',
                            'value' => 'Ambiente Beleuchtung',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[12]->getUuid(),
                    'name' => 'testClassified13',
                    'description' => 'testClassifiedDescription13',
                    'price' => '90.000,00',
                    'offerNumber' => 'testOfferNumber13',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Außenfarbe',
                            'value' => 'Schwarz',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[13]->getUuid(),
                    'name' => 'testClassified14',
                    'description' => 'testClassifiedDescription14',
                    'price' => '50.123,00',
                    'offerNumber' => 'testOfferNumber14',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Außenfarbe',
                            'value' => 'Grau',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[14]->getUuid(),
                    'name' => 'testClassified15',
                    'description' => 'testClassifiedDescription15',
                    'price' => '99.123,48',
                    'offerNumber' => 'testOfferNumber15',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Innenausstattung',
                            'value' => 'Anthrazit',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[15]->getUuid(),
                    'name' => 'testClassified16',
                    'description' => 'testClassifiedDescription16',
                    'price' => '60.123,00',
                    'offerNumber' => 'testOfferNumber16',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Innenausstattung',
                            'value' => 'Blau',
                        ],
                    ]
                ],
            ]
        ], $response);
    }

    public function testSearchClassifiedWithMultipleFilters(): void
    {
        $createdClassifieds = $this->createClassifieds();

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'propertyGroupOptionIds' => [
                        // Neufahrzeug
                        (string)$this->propertyGroupVehicleCondition->getGroupOptions()->first()->getUuid(),
                        // Gebrauchtfahrzeug
                        (string)$this->propertyGroupVehicleCondition->getGroupOptions()->get(1)->getUuid(),
                        // Limousine
                        (string)$this->propertyGroupVehicleType->getGroupOptions()->first()->getUuid(),
                        // Benzin
                        (string)$this->propertyGroupEngine->getGroupOptions()->first()->getUuid(),
                        // Automatik
                        (string)$this->propertyGroupEngine->getGroupOptions()->get(5)->getUuid(),
                        // Schaltgetriebe
                        (string)$this->propertyGroupEngine->getGroupOptions()->get(6)->getUuid(),
                    ],
                    'propertyGroupOptionIdsSelectFrom' => [
                        // Anzahl Türen 2/3
                        (string)$this->propertyGroupVehicleType->getGroupOptions()->get(12)->getUuid(),
                    ]
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(7, $response['data']);

        static::assertSame([
            'data' => [
                [
                    'id' => (string)$createdClassifieds[0]->getUuid(),
                    'name' => 'Test Classified',
                    'description' => 'testClassifiedDescription',
                    'price' => '123,45',
                    'offerNumber' => 'testOfferNumber',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Gebrauchtfahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '250',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[4]->getUuid(),
                    'name' => 'testClassified5',
                    'description' => 'testClassifiedDescription5',
                    'price' => '823,45',
                    'offerNumber' => 'testOfferNumber5',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '450',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[5]->getUuid(),
                    'name' => 'testClassified6',
                    'description' => 'testClassifiedDescription6',
                    'price' => '213,46',
                    'offerNumber' => 'testOfferNumber6',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2018',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '450',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[6]->getUuid(),
                    'name' => 'testClassified7',
                    'description' => 'testClassifiedDescription7',
                    'price' => '113,45',
                    'offerNumber' => 'testOfferNumber7',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '550',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[7]->getUuid(),
                    'name' => 'testClassified8',
                    'description' => 'testClassifiedDescription8',
                    'price' => '115,48',
                    'offerNumber' => 'testOfferNumber8',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2020',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '650',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[8]->getUuid(),
                    'name' => 'testClassified9',
                    'description' => 'testClassifiedDescription9',
                    'price' => '15,48',
                    'offerNumber' => 'testOfferNumber9',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Another Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Another Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '650',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[9]->getUuid(),
                    'name' => 'testClassified10',
                    'description' => 'testClassifiedDescription10',
                    'price' => '20,00',
                    'offerNumber' => 'testOfferNumber10',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Another Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Another Test Brand Model child option two',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '750',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
            ]
        ], $response);
    }

    public function testSearchClassifiedWithBrandFilter(): void
    {
        $createdClassifieds = $this->createClassifieds();

        $propertyGroupOption = $this->getPropertyGroupOption(
            'Marke, Modell, Variante',
            'Another Test Brand',
            'Marke'
        );

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'brand' => (string)$propertyGroupOption->getUuid()
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(2, $response['data']);

        static::assertSame([
            'data' => [
                [
                    'id' => (string)$createdClassifieds[8]->getUuid(),
                    'name' => 'testClassified9',
                    'description' => 'testClassifiedDescription9',
                    'price' => '15,48',
                    'offerNumber' => 'testOfferNumber9',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Another Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Another Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '650',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[9]->getUuid(),
                    'name' => 'testClassified10',
                    'description' => 'testClassifiedDescription10',
                    'price' => '20,00',
                    'offerNumber' => 'testOfferNumber10',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Another Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Another Test Brand Model child option two',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '750',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
            ]
        ], $response);
    }

    public function testSearchClassifiedWithBrandAndModelFilter(): void
    {
        $createdClassifieds = $this->createClassifieds();

        $propertyGroupOptionBrand = $this->getPropertyGroupOption(
            'Marke, Modell, Variante',
            'Another Test Brand',
            'Marke'
        );

        $propertyGroupOptionModel = $this->getPropertyGroupOption(
            'Marke, Modell, Variante',
            'Another Test Brand Model child option two',
            'Another Test Brand Model with child options'
        );

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'brand' => (string)$propertyGroupOptionBrand->getUuid(),
                    'model' => (string)$propertyGroupOptionModel->getUuid(),
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(1, $response['data']);

        static::assertSame([
            'data' => [
                [
                    'id' => (string)$createdClassifieds[9]->getUuid(),
                    'name' => 'testClassified10',
                    'description' => 'testClassifiedDescription10',
                    'price' => '20,00',
                    'offerNumber' => 'testOfferNumber10',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Another Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Another Test Brand Model child option two',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '750',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
            ]
        ], $response);
    }

    public function testSearchClassifiedWithModelFilterForAllChildOptions(): void
    {
        $createdClassifieds = $this->createClassifieds();

        $propertyGroupOptionBrand = $this->getPropertyGroupOption(
            'Marke, Modell, Variante',
            'Another Test Brand',
            'Marke'
        );

        $propertyGroupOptionModel = $this->getPropertyGroupOption(
            'Marke, Modell, Variante',
            'Another Test Brand Model with child options',
            'Another Test Brand'
        );

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'brand' => (string)$propertyGroupOptionBrand->getUuid(),
                    'model' => (string)$propertyGroupOptionModel->getUuid(),
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(2, $response['data']);

        static::assertSame([
            'data' => [
                [
                    'id' => (string)$createdClassifieds[8]->getUuid(),
                    'name' => 'testClassified9',
                    'description' => 'testClassifiedDescription9',
                    'price' => '15,48',
                    'offerNumber' => 'testOfferNumber9',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Another Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Another Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '650',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[9]->getUuid(),
                    'name' => 'testClassified10',
                    'description' => 'testClassifiedDescription10',
                    'price' => '20,00',
                    'offerNumber' => 'testOfferNumber10',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Another Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Another Test Brand Model child option two',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '750',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
            ]
        ], $response);
    }

    public function testSearchClassifiedWithEquipmentGroup(): void
    {
        $createdClassifieds = $this->createClassifieds();

        $propertyGroupOptionOne = $this->getPropertyGroupOption(
            'Ausstattung',
            'Adaptives Dämpfungssystem',
            'Technik'
        );

        $propertyGroupOptionTwo = $this->getPropertyGroupOption(
            'Ausstattung',
            'Ambiente Beleuchtung',
            'Komfort'
        );

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'propertyGroupOptionIds' => [
                        (string)$propertyGroupOptionOne->getUuid(),
                        (string)$propertyGroupOptionTwo->getUuid(),
                    ]
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(2, $response['data']);

        static::assertSame([
            'data' => [
                [
                    'id' => (string)$createdClassifieds[10]->getUuid(),
                    'name' => 'testClassified11',
                    'description' => 'testClassifiedDescription11',
                    'price' => '30,00',
                    'offerNumber' => 'testOfferNumber11',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '750',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Technik',
                            'value' => 'Adaptives Dämpfungssystem',
                        ],
                        [
                            'optionName' => 'Komfort',
                            'value' => 'Ambiente Beleuchtung',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[11]->getUuid(),
                    'name' => 'testClassified12',
                    'description' => 'testClassifiedDescription12',
                    'price' => '60,00',
                    'offerNumber' => 'testOfferNumber12',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Komfort',
                            'value' => 'Ambiente Beleuchtung',
                        ],
                    ]
                ],
            ]
        ], $response);
    }

    public function testSearchClassifiedWithExteriorColor(): void
    {
        $createdClassifieds = $this->createClassifieds();

        $propertyGroupOptionOne = $this->getPropertyGroupOption(
            'Außenfarbe',
            'Schwarz',
            null
        );

        $propertyGroupOptionTwo = $this->getPropertyGroupOption(
            'Außenfarbe',
            'Grau',
            null
        );

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'propertyGroupOptionIds' => [
                        (string)$propertyGroupOptionOne->getUuid(),
                        (string)$propertyGroupOptionTwo->getUuid(),
                    ]
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(2, $response['data']);

        static::assertSame([
            'data' => [
                [
                    'id' => (string)$createdClassifieds[12]->getUuid(),
                    'name' => 'testClassified13',
                    'description' => 'testClassifiedDescription13',
                    'price' => '90.000,00',
                    'offerNumber' => 'testOfferNumber13',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Außenfarbe',
                            'value' => 'Schwarz',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[13]->getUuid(),
                    'name' => 'testClassified14',
                    'description' => 'testClassifiedDescription14',
                    'price' => '50.123,00',
                    'offerNumber' => 'testOfferNumber14',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Außenfarbe',
                            'value' => 'Grau',
                        ],
                    ]
                ],
            ]
        ], $response);
    }

    public function testSearchClassifiedWithExteriorColorContainsNoResults(): void
    {
        $this->createClassifieds();

        $propertyGroupOptionOne = $this->getPropertyGroupOption(
            'Außenfarbe',
            'Weiß',
            null
        );

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'propertyGroupOptionIds' => [
                        (string)$propertyGroupOptionOne->getUuid(),
                    ]
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(0, $response['data']);

        static::assertSame([
            'data' => []
        ], $response);
    }

    public function testSearchClassifiedWithInteriorColor(): void
    {
        $createdClassifieds = $this->createClassifieds();

        $propertyGroupOptionOne = $this->getPropertyGroupOption(
            'Innenausstattung',
            'Anthrazit',
            null
        );

        $propertyGroupOptionTwo = $this->getPropertyGroupOption(
            'Innenausstattung',
            'Blau',
            null
        );

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'propertyGroupOptionIds' => [
                        (string)$propertyGroupOptionOne->getUuid(),
                        (string)$propertyGroupOptionTwo->getUuid(),
                    ]
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(2, $response['data']);

        static::assertSame([
            'data' => [
                [
                    'id' => (string)$createdClassifieds[14]->getUuid(),
                    'name' => 'testClassified15',
                    'description' => 'testClassifiedDescription15',
                    'price' => '99.123,48',
                    'offerNumber' => 'testOfferNumber15',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Innenausstattung',
                            'value' => 'Anthrazit',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[15]->getUuid(),
                    'name' => 'testClassified16',
                    'description' => 'testClassifiedDescription16',
                    'price' => '60.123,00',
                    'offerNumber' => 'testOfferNumber16',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Innenausstattung',
                            'value' => 'Blau',
                        ],
                    ]
                ],
            ]
        ], $response);
    }

    public function testSearchClassifiedWithInteriorColorContainsNoResults(): void
    {
        $this->createClassifieds();

        $propertyGroupOptionOne = $this->getPropertyGroupOption(
            'Innenausstattung',
            'Hellgrau',
            null
        );

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'propertyGroupOptionIds' => [
                        (string)$propertyGroupOptionOne->getUuid(),
                    ]
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(0, $response['data']);

        static::assertSame([
            'data' => []
        ], $response);
    }

    public function testSearchClassifiedWithEquipmentGroupContainsNoResults(): void
    {
        $this->createClassifieds();

        $propertyGroupOptionOne = $this->getPropertyGroupOption(
            'Ausstattung',
            'Allrad',
            'Technik'
        );

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'propertyGroupOptionIds' => [
                        (string)$propertyGroupOptionOne->getUuid(),
                    ]
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(0, $response['data']);

        static::assertSame([
            'data' => []
        ], $response);
    }

    public function testSearchClassifiedWithHorsePowerFromFilter()
    {
        $createdClassifieds = $this->createClassifieds();

        $propertyGroupOptionOne = $this->getPropertyGroupOption(
            'Basisdaten',
            '850',
            'Leistung'
        );

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'propertyGroupOptionIdsSelectFrom' => [
                        (string)$propertyGroupOptionOne->getUuid(),
                    ]
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(5, $response['data']);

        static::assertSame([
            'data' => [
                [
                    'id' => (string)$createdClassifieds[11]->getUuid(),
                    'name' => 'testClassified12',
                    'description' => 'testClassifiedDescription12',
                    'price' => '60,00',
                    'offerNumber' => 'testOfferNumber12',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Komfort',
                            'value' => 'Ambiente Beleuchtung',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[12]->getUuid(),
                    'name' => 'testClassified13',
                    'description' => 'testClassifiedDescription13',
                    'price' => '90.000,00',
                    'offerNumber' => 'testOfferNumber13',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Außenfarbe',
                            'value' => 'Schwarz',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[13]->getUuid(),
                    'name' => 'testClassified14',
                    'description' => 'testClassifiedDescription14',
                    'price' => '50.123,00',
                    'offerNumber' => 'testOfferNumber14',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Außenfarbe',
                            'value' => 'Grau',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[14]->getUuid(),
                    'name' => 'testClassified15',
                    'description' => 'testClassifiedDescription15',
                    'price' => '99.123,48',
                    'offerNumber' => 'testOfferNumber15',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Innenausstattung',
                            'value' => 'Anthrazit',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[15]->getUuid(),
                    'name' => 'testClassified16',
                    'description' => 'testClassifiedDescription16',
                    'price' => '60.123,00',
                    'offerNumber' => 'testOfferNumber16',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Innenausstattung',
                            'value' => 'Blau',
                        ],
                    ]
                ],
            ]
        ], $response);
    }

    public function testSearchClassifiedWithHorsePowerWithBothFilters()
    {
        $createdClassifieds = $this->createClassifieds();

        $propertyGroupOptionOne = $this->getPropertyGroupOption(
            'Basisdaten',
            '650',
            'Leistung'
        );

        $propertyGroupOptionTwo = $this->getPropertyGroupOption(
            'Basisdaten',
            '850',
            'Leistung'
        );

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'propertyGroupOptionIdsSelectFrom' => [
                        (string)$propertyGroupOptionOne->getUuid(),
                    ],
                    'propertyGroupOptionIdsSelectTo' => [
                        (string)$propertyGroupOptionTwo->getUuid(),
                    ]
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(9, $response['data']);

        static::assertSame([
            'data' => [
                [
                    'id' => (string)$createdClassifieds[7]->getUuid(),
                    'name' => 'testClassified8',
                    'description' => 'testClassifiedDescription8',
                    'price' => '115,48',
                    'offerNumber' => 'testOfferNumber8',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2020',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '650',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Schaltgetriebe',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[8]->getUuid(),
                    'name' => 'testClassified9',
                    'description' => 'testClassifiedDescription9',
                    'price' => '15,48',
                    'offerNumber' => 'testOfferNumber9',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Another Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Another Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '650',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[9]->getUuid(),
                    'name' => 'testClassified10',
                    'description' => 'testClassifiedDescription10',
                    'price' => '20,00',
                    'offerNumber' => 'testOfferNumber10',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Another Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Another Test Brand Model child option two',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '2/3',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2023',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '750',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[10]->getUuid(),
                    'name' => 'testClassified11',
                    'description' => 'testClassifiedDescription11',
                    'price' => '30,00',
                    'offerNumber' => 'testOfferNumber11',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '750',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Technik',
                            'value' => 'Adaptives Dämpfungssystem',
                        ],
                        [
                            'optionName' => 'Komfort',
                            'value' => 'Ambiente Beleuchtung',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[11]->getUuid(),
                    'name' => 'testClassified12',
                    'description' => 'testClassifiedDescription12',
                    'price' => '60,00',
                    'offerNumber' => 'testOfferNumber12',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Komfort',
                            'value' => 'Ambiente Beleuchtung',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[12]->getUuid(),
                    'name' => 'testClassified13',
                    'description' => 'testClassifiedDescription13',
                    'price' => '90.000,00',
                    'offerNumber' => 'testOfferNumber13',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Außenfarbe',
                            'value' => 'Schwarz',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[13]->getUuid(),
                    'name' => 'testClassified14',
                    'description' => 'testClassifiedDescription14',
                    'price' => '50.123,00',
                    'offerNumber' => 'testOfferNumber14',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Außenfarbe',
                            'value' => 'Grau',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[14]->getUuid(),
                    'name' => 'testClassified15',
                    'description' => 'testClassifiedDescription15',
                    'price' => '99.123,48',
                    'offerNumber' => 'testOfferNumber15',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Innenausstattung',
                            'value' => 'Anthrazit',
                        ],
                    ]
                ],
                [
                    'id' => (string)$createdClassifieds[15]->getUuid(),
                    'name' => 'testClassified16',
                    'description' => 'testClassifiedDescription16',
                    'price' => '60.123,00',
                    'offerNumber' => 'testOfferNumber16',
                    'options' => [
                        [
                            'optionName' => 'Fahrzeugzustand',
                            'value' => 'Neufahrzeug',
                        ],
                        [
                            'optionName' => 'Marke',
                            'value' => 'Test Brand',
                        ],
                        [
                            'optionName' => 'Modell',
                            'value' => 'Test Brand Model child option one',
                        ],
                        [
                            'optionName' => 'Fahrzeugtyp',
                            'value' => 'Limousine',
                        ],
                        [
                            'optionName' => 'Anzahl Sitzplätze',
                            'value' => '5',
                        ],
                        [
                            'optionName' => 'Anzahl Türen',
                            'value' => '6/7',
                        ],
                        [
                            'optionName' => 'Erstzulassung',
                            'value' => '2019',
                        ],
                        [
                            'optionName' => 'Kilometer',
                            'value' => '10560',
                        ],
                        [
                            'optionName' => 'Leistung',
                            'value' => '850',
                        ],
                        [
                            'optionName' => 'Kraftstoffart',
                            'value' => 'Benzin',
                        ],
                        [
                            'optionName' => 'Getriebe',
                            'value' => 'Automatik',
                        ],
                        [
                            'optionName' => 'Innenausstattung',
                            'value' => 'Blau',
                        ],
                    ]
                ],
            ]
        ], $response);
    }

    public function testSearchClassifiedWithHorsePowerContainsNoResults(): void
    {
        $this->createClassifieds();

        $propertyGroupOptionOne = $this->getPropertyGroupOption(
            'Basisdaten',
            '950',
            'Leistung'
        );

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'propertyGroupOptionIdsSelectFrom' => [
                        (string)$propertyGroupOptionOne->getUuid(),
                    ]
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(0, $response['data']);

        static::assertSame([
            'data' => []
        ], $response);
    }

    public function testSearchClassifiedContainsNoResults(): void
    {
        $this->createClassifieds();

        $this->client->request(
            'POST',
            '/customer-frontend-api/search/classified',
            [],
            [],
            [],
            json_encode(
                [
                    'page' => 1,
                    'propertyGroupOptionIds' => [
                        // Limousine
                        (string)$this->propertyGroupVehicleType->getGroupOptions()->first()->getUuid(),
                        // Diesel
                        (string)$this->propertyGroupEngine->getGroupOptions()->get(1)->getUuid(),
                        // Automatik
                        (string)$this->propertyGroupEngine->getGroupOptions()->get(5)->getUuid(),
                    ]
                ]
            )
        );

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        static::assertArrayHasKey('data', $response);
        static::assertCount(0, $response['data']);

        static::assertSame([
            'data' => []
        ], $response);
    }

    private function createPropertyGroup(string $name, array $groupOptions, bool $isEquipmentGroup = false): PropertyGroup
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

    private function createBrandWithModels(array $brandWithModels): void
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

    private function createPropertyGroups(): void
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
    ): Classified {
        $classifiedDto = new ClassifiedDto();
        $classifiedDto->setId((string)Uuid::v4());
        $classifiedDto->setName($name);
        $classifiedDto->setDescription($description);
        $classifiedDto->setPrice($price);
        $classifiedDto->setOfferNumber($offerNumber);

        foreach ($propertyGroupOptions as $propertyGroupOption) {
            if ($propertyGroupOption instanceof PropertyGroupOption) {
                $propertyGroupOptionDto = new ClassifiedPropertyGroupOptionDto();
                $propertyGroupOptionDto->setPropertyGroupOptionId((string)$propertyGroupOption->getUuid());
                $classifiedDto->addPropertyGroupOption($propertyGroupOptionDto);
            }
        }

        return $this->classifiedService->createClassified($classifiedDto);
    }

    private function createClassifieds(): array
    {
        // TODO create more classifieds

        $createdClassifieds[] = $this->createClassified(
            'Test Classified',
            'testClassifiedDescription',
            12345,
            'testOfferNumber',
            $this->getClassifiedPropertyGroupOptions(
                'Gebrauchtfahrzeug',
                'Test Brand',
                'Test Brand Model child option one',
                'Test Brand with child options',
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
            22345,
            'testOfferNumber2',
            $this->getClassifiedPropertyGroupOptions(
                'Gebrauchtfahrzeug',
                'Test Brand',
                'Test Brand Model child option one',
                'Test Brand with child options',
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

        $createdClassifieds[] = $this->createClassified(
            'testClassified3',
            'testClassifiedDescription3',
            22123,
            'testOfferNumber3',
            $this->getClassifiedPropertyGroupOptions(
                'Neufahrzeug',
                'Test Brand',
                'Test Brand Model child option two',
                'Test Brand with child options',
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
            55125,
            'testOfferNumber4',
            $this->getClassifiedPropertyGroupOptions(
                'Neufahrzeug',
                'Test Brand',
                'Test Brand Model child option two',
                'Test Brand with child options',
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

        $createdClassifieds[] = $this->createClassified(
            'testClassified5',
            'testClassifiedDescription5',
            82345,
            'testOfferNumber5',
            $this->getClassifiedPropertyGroupOptions(
                'Neufahrzeug',
                'Test Brand',
                'Test Brand Model child option one',
                'Test Brand with child options',
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
            21346,
            'testOfferNumber6',
            $this->getClassifiedPropertyGroupOptions(
                'Neufahrzeug',
                'Test Brand',
                'Test Brand Model child option one',
                'Test Brand with child options',
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
            11345,
            'testOfferNumber7',
            $this->getClassifiedPropertyGroupOptions(
                'Neufahrzeug',
                'Test Brand',
                'Test Brand Model child option one',
                'Test Brand with child options',
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
            11548,
            'testOfferNumber8',
            $this->getClassifiedPropertyGroupOptions(
                'Neufahrzeug',
                'Test Brand',
                'Test Brand Model child option one',
                'Test Brand with child options',
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

        $createdClassifieds[] = $this->createClassified(
            'testClassified9',
            'testClassifiedDescription9',
            1548,
            'testOfferNumber9',
            $this->getClassifiedPropertyGroupOptions(
                'Neufahrzeug',
                'Another Test Brand',
                'Another Test Brand Model child option one',
                'Another Test Brand Model with child options',
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

        $createdClassifieds[] = $this->createClassified(
            'testClassified10',
            'testClassifiedDescription10',
            2000,
            'testOfferNumber10',
            $this->getClassifiedPropertyGroupOptions(
                'Neufahrzeug',
                'Another Test Brand',
                'Another Test Brand Model child option two',
                'Another Test Brand Model with child options',
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
            'Test Brand',
            'Test Brand Model child option one',
            'Test Brand with child options',
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

        $createdClassifieds[] = $this->createClassified(
            'testClassified11',
            'testClassifiedDescription11',
            3000,
            'testOfferNumber11',
            $propertyGroupOptions
        );

        $propertyGroupOptions = $this->getClassifiedPropertyGroupOptions(
            'Neufahrzeug',
            'Test Brand',
            'Test Brand Model child option one',
            'Test Brand with child options',
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
            6000,
            'testOfferNumber12',
            $propertyGroupOptions
        );

        $propertyGroupOptions = $this->getClassifiedPropertyGroupOptions(
            'Neufahrzeug',
            'Test Brand',
            'Test Brand Model child option one',
            'Test Brand with child options',
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
            9000000,
            'testOfferNumber13',
            $propertyGroupOptions
        );

        $propertyGroupOptions = $this->getClassifiedPropertyGroupOptions(
            'Neufahrzeug',
            'Test Brand',
            'Test Brand Model child option one',
            'Test Brand with child options',
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
            5012300,
            'testOfferNumber14',
            $propertyGroupOptions
        );

        $propertyGroupOptions = $this->getClassifiedPropertyGroupOptions(
            'Neufahrzeug',
            'Test Brand',
            'Test Brand Model child option one',
            'Test Brand with child options',
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
            9912348,
            'testOfferNumber15',
            $propertyGroupOptions
        );

        $propertyGroupOptions = $this->getClassifiedPropertyGroupOptions(
            'Neufahrzeug',
            'Test Brand',
            'Test Brand Model child option one',
            'Test Brand with child options',
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
            6012300,
            'testOfferNumber16',
            $propertyGroupOptions
        );

        return $createdClassifieds;
    }
}
