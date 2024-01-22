<?php

declare(strict_types=1);

namespace App\Tests\Controller\CustomerFrontendApi;

use App\Dto\ClassifiedDto;
use App\Dto\ClassifiedPropertyGroupOptionDto;
use App\Entity\Classified;
use App\Entity\PropertyGroup;
use App\Entity\PropertyGroupOption;
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

    private PropertyGroup $propertyGroupEquipment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->classifiedService = static::getContainer()->get(ClassifiedService::class);
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $this->createPropertyGroups();
    }

    public function testSearchPropertyOptions(): void
    {
        $this->client->request('GET', '/customer-frontend-api/search/property/options');

        static::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

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
                            'id' => (string)$this->propertyGroupBrandWithModel->getGroupOptions()->get(1)->getUuid(),
                            'name' => 'Marke',
                            'type' => 'select',
                            'optionValues' => [
                                [
                                    'id' => (string)$this->propertyGroupBrandWithModel->getGroupOptions()->get(0)->getUuid(),
                                    'value' => 'Test Brand'
                                ],
                            ]
                        ],
                        [
                            'id' => (string)$this->propertyGroupBrandWithModel->getGroupOptions()->get(4)->getUuid(),
                            'name' => 'Modell',
                            'type' => 'select',
                            'optionValues' => [
                                [
                                    'id' => (string)$this->propertyGroupBrandWithModel->getGroupOptions()->get(2)->getUuid(),
                                    'value' => 'Test Brand 1 Series'
                                ],
                                [
                                    'id' => (string)$this->propertyGroupBrandWithModel->getGroupOptions()->get(3)->getUuid(),
                                    'value' => 'Test Brand 2 Series'
                                ],
                            ]
                        ],
                        [
                            'id' => (string)$this->propertyGroupBrandWithModel->getGroupOptions()->get(5)->getUuid(),
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
                            'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(18)->getUuid(),
                            'name' => 'Leistung (in kw)',
                            'type' => 'selectRange',
                            'optionValues' => [
                                [
                                    'id' => (string)$this->propertyGroupBasicData->getGroupOptions()->get(17)->getUuid(),
                                    'value' => '200 kw (272 PS)',
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
        static::assertCount(8, $response['data']);

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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 2 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 2 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 2 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
        static::assertCount(6, $response['data']);

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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
        static::assertCount(6, $response['data']);

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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 2 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 2 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
        static::assertCount(3, $response['data']);

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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
        static::assertCount(5, $response['data']);

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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                            'value' => 'Test Brand 1 Series',
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
                            'optionName' => 'Leistung (in kw)',
                            'value' => '200 kw (272 PS)',
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
                'values' => ['Test Brand']
            ],
            [
                'name' => 'Modell',
                'type' => PropertyGroupOption::TYPE_SELECT,
                'values' => ['Test Brand 1 Series', 'Test Brand 2 Series']
            ],
            [
                'name' => 'Variante',
                'type' => PropertyGroupOption::TYPE_TEXT_FIELD,
                'values' => []
            ],
        ]);

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
                'name' => 'Leistung (in kw)',
                'type' => PropertyGroupOption::TYPE_SELECT_RANGE,
                'values' => ['200 kw (272 PS)']
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
        $this->propertyGroupEquipment = $propertyGroupEquipment;
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
        $classifieds = [
            [
                'name' => 'Test Classified',
                'description' => 'testClassifiedDescription',
                'price' => 12345,
                'offerNumber' => 'testOfferNumber',
                'options' => [
                    // Gebrauchtfahrzeug
                    $this->propertyGroupVehicleCondition->getGroupOptions()->get(1),

                    // Test Brand
                    $this->propertyGroupBrandWithModel->getGroupOptions()->first(),

                    // Test Brand 1 Series
                    $this->propertyGroupBrandWithModel->getGroupOptions()->get(2),

                    // Limousine
                    $this->propertyGroupVehicleType->getGroupOptions()->first(),

                    // Anzahl Sitzplätze 5
                    $this->propertyGroupVehicleType->getGroupOptions()->get(10),

                    // Anzahl Türen 2/3
                    $this->propertyGroupVehicleType->getGroupOptions()->get(12),

                    // Erstzulassung 2023
                    $this->propertyGroupBasicData->getGroupOptions()->get(12),

                    // Kilometer 10560
                    $this->propertyGroupBasicData->getGroupOptions()->get(14),

                    // Leistung (in kw) 200 kw (272 PS)
                    $this->propertyGroupBasicData->getGroupOptions()->get(17),

                    // Benzin
                    $this->propertyGroupEngine->getGroupOptions()->first(),

                    // Automatik
                    $this->propertyGroupEngine->getGroupOptions()->get(5),
                ],
            ],
            [
                'name' => 'testClassified2',
                'description' => 'testClassifiedDescription2',
                'price' => 22345,
                'offerNumber' => 'testOfferNumber2',
                'options' => [
                    // Gebrauchtfahrzeug
                    $this->propertyGroupVehicleCondition->getGroupOptions()->get(1),

                    // Test Brand
                    $this->propertyGroupBrandWithModel->getGroupOptions()->first(),

                    // Test Brand 1 Series
                    $this->propertyGroupBrandWithModel->getGroupOptions()->get(2),

                    // Sportwagen/Coupe
                    $this->propertyGroupVehicleType->getGroupOptions()->get(4),

                    // Anzahl Sitzplätze 5
                    $this->propertyGroupVehicleType->getGroupOptions()->get(10),

                    // Anzahl Türen 2/3
                    $this->propertyGroupVehicleType->getGroupOptions()->get(12),

                    // Erstzulassung 2023
                    $this->propertyGroupBasicData->getGroupOptions()->get(12),

                    // Kilometer 10560
                    $this->propertyGroupBasicData->getGroupOptions()->get(14),

                    // Leistung (in kw) 200 kw (272 PS)
                    $this->propertyGroupBasicData->getGroupOptions()->get(17),

                    // Benzin
                    $this->propertyGroupEngine->getGroupOptions()->first(),

                    // Automatik
                    $this->propertyGroupEngine->getGroupOptions()->get(5),
                ],
            ],
            [
                'name' => 'testClassified3',
                'description' => 'testClassifiedDescription3',
                'price' => 22123,
                'offerNumber' => 'testOfferNumber3',
                'options' => [
                    // Neufahrzeug
                    $this->propertyGroupVehicleCondition->getGroupOptions()->first(),

                    // Test Brand
                    $this->propertyGroupBrandWithModel->getGroupOptions()->first(),

                    // Test Brand 2 Series
                    $this->propertyGroupBrandWithModel->getGroupOptions()->get(3),

                    // Kombi
                    $this->propertyGroupVehicleType->getGroupOptions()->get(1),

                    // Anzahl Sitzplätze 5
                    $this->propertyGroupVehicleType->getGroupOptions()->get(10),

                    // Anzahl Türen 4/5
                    $this->propertyGroupVehicleType->getGroupOptions()->get(13),

                    // Erstzulassung 2023
                    $this->propertyGroupBasicData->getGroupOptions()->get(12),

                    // Kilometer 70205
                    $this->propertyGroupBasicData->getGroupOptions()->get(15),

                    // Leistung (in kw) 200 kw (272 PS)
                    $this->propertyGroupBasicData->getGroupOptions()->get(17),

                    // Diesel
                    $this->propertyGroupEngine->getGroupOptions()->get(1),

                    // Automatik
                    $this->propertyGroupEngine->getGroupOptions()->get(5),
                ],
            ],
            [
                'name' => 'testClassified4',
                'description' => 'testClassifiedDescription4',
                'price' => 55125,
                'offerNumber' => 'testOfferNumber4',
                'options' => [
                    // Neufahrzeug
                    $this->propertyGroupVehicleCondition->getGroupOptions()->first(),

                    // Test Brand
                    $this->propertyGroupBrandWithModel->getGroupOptions()->first(),

                    // Test Brand 2 Series
                    $this->propertyGroupBrandWithModel->getGroupOptions()->get(3),

                    // Kombi
                    $this->propertyGroupVehicleType->getGroupOptions()->get(1),

                    // Anzahl Sitzplätze 5
                    $this->propertyGroupVehicleType->getGroupOptions()->get(10),

                    // Anzahl Türen 4/5
                    $this->propertyGroupVehicleType->getGroupOptions()->get(13),

                    // Erstzulassung 2023
                    $this->propertyGroupBasicData->getGroupOptions()->get(12),

                    // Kilometer 70205
                    $this->propertyGroupBasicData->getGroupOptions()->get(15),

                    // Leistung (in kw) 200 kw (272 PS)
                    $this->propertyGroupBasicData->getGroupOptions()->get(17),

                    // Diesel
                    $this->propertyGroupEngine->getGroupOptions()->get(1),

                    // Schaltgetriebe
                    $this->propertyGroupEngine->getGroupOptions()->get(6),
                ],
            ],
            [
                'name' => 'testClassified5',
                'description' => 'testClassifiedDescription5',
                'price' => 82345,
                'offerNumber' => 'testOfferNumber5',
                'options' => [
                    // Neufahrzeug
                    $this->propertyGroupVehicleCondition->getGroupOptions()->first(),

                    // Test Brand
                    $this->propertyGroupBrandWithModel->getGroupOptions()->first(),

                    // Test Brand 1 Series
                    $this->propertyGroupBrandWithModel->getGroupOptions()->get(2),

                    // Limousine
                    $this->propertyGroupVehicleType->getGroupOptions()->first(),

                    // Anzahl Sitzplätze 5
                    $this->propertyGroupVehicleType->getGroupOptions()->get(10),

                    // Anzahl Türen 2/3
                    $this->propertyGroupVehicleType->getGroupOptions()->get(12),

                    // Erstzulassung 2023
                    $this->propertyGroupBasicData->getGroupOptions()->get(12),

                    // Kilometer 10560
                    $this->propertyGroupBasicData->getGroupOptions()->get(14),

                    // Leistung (in kw) 200 kw (272 PS)
                    $this->propertyGroupBasicData->getGroupOptions()->get(17),

                    // Benzin
                    $this->propertyGroupEngine->getGroupOptions()->first(),

                    // Schaltgetriebe
                    $this->propertyGroupEngine->getGroupOptions()->get(6),
                ],
            ],
            [
                'name' => 'testClassified6',
                'description' => 'testClassifiedDescription6',
                'price' => 21346,
                'offerNumber' => 'testOfferNumber6',
                'options' => [
                    // Neufahrzeug
                    $this->propertyGroupVehicleCondition->getGroupOptions()->first(),

                    // Test Brand
                    $this->propertyGroupBrandWithModel->getGroupOptions()->first(),

                    // Test Brand 1 Series
                    $this->propertyGroupBrandWithModel->getGroupOptions()->get(2),

                    // Limousine
                    $this->propertyGroupVehicleType->getGroupOptions()->first(),

                    // Anzahl Sitzplätze 5
                    $this->propertyGroupVehicleType->getGroupOptions()->get(10),

                    // Anzahl Türen 2/3
                    $this->propertyGroupVehicleType->getGroupOptions()->get(12),

                    // Erstzulassung 2018
                    $this->propertyGroupBasicData->getGroupOptions()->get(7),

                    // Kilometer 10560
                    $this->propertyGroupBasicData->getGroupOptions()->get(14),

                    // Leistung (in kw) 200 kw (272 PS)
                    $this->propertyGroupBasicData->getGroupOptions()->get(17),

                    // Benzin
                    $this->propertyGroupEngine->getGroupOptions()->first(),

                    // Schaltgetriebe
                    $this->propertyGroupEngine->getGroupOptions()->get(6),
                ],
            ],
            [
                'name' => 'testClassified7',
                'description' => 'testClassifiedDescription7',
                'price' => 11345,
                'offerNumber' => 'testOfferNumber7',
                'options' => [
                    // Neufahrzeug
                    $this->propertyGroupVehicleCondition->getGroupOptions()->first(),

                    // Test Brand
                    $this->propertyGroupBrandWithModel->getGroupOptions()->first(),

                    // Test Brand 1 Series
                    $this->propertyGroupBrandWithModel->getGroupOptions()->get(2),

                    // Limousine
                    $this->propertyGroupVehicleType->getGroupOptions()->first(),

                    // Anzahl Sitzplätze 5
                    $this->propertyGroupVehicleType->getGroupOptions()->get(10),

                    // Anzahl Türen 2/3
                    $this->propertyGroupVehicleType->getGroupOptions()->get(12),

                    // Erstzulassung 2019
                    $this->propertyGroupBasicData->getGroupOptions()->get(8),

                    // Kilometer 10560
                    $this->propertyGroupBasicData->getGroupOptions()->get(14),

                    // Leistung (in kw) 200 kw (272 PS)
                    $this->propertyGroupBasicData->getGroupOptions()->get(17),

                    // Benzin
                    $this->propertyGroupEngine->getGroupOptions()->first(),

                    // Schaltgetriebe
                    $this->propertyGroupEngine->getGroupOptions()->get(6),
                ],
            ],
            [
                'name' => 'testClassified8',
                'description' => 'testClassifiedDescription8',
                'price' => 11548,
                'offerNumber' => 'testOfferNumber8',
                'options' => [
                    // Neufahrzeug
                    $this->propertyGroupVehicleCondition->getGroupOptions()->first(),

                    // Test Brand
                    $this->propertyGroupBrandWithModel->getGroupOptions()->first(),

                    // Test Brand 1 Series
                    $this->propertyGroupBrandWithModel->getGroupOptions()->get(2),

                    // Limousine
                    $this->propertyGroupVehicleType->getGroupOptions()->first(),

                    // Anzahl Sitzplätze 5
                    $this->propertyGroupVehicleType->getGroupOptions()->get(10),

                    // Anzahl Türen 2/3
                    $this->propertyGroupVehicleType->getGroupOptions()->get(12),

                    // Erstzulassung 2020
                    $this->propertyGroupBasicData->getGroupOptions()->get(9),

                    // Kilometer 10560
                    $this->propertyGroupBasicData->getGroupOptions()->get(14),

                    // Leistung (in kw) 200 kw (272 PS)
                    $this->propertyGroupBasicData->getGroupOptions()->get(17),

                    // Benzin
                    $this->propertyGroupEngine->getGroupOptions()->first(),

                    // Schaltgetriebe
                    $this->propertyGroupEngine->getGroupOptions()->get(6),
                ],
            ],
        ];

        $createdClassifieds = [];
        foreach ($classifieds as $classified) {
            $createdClassifieds[] = $this->createClassified(
                $classified['name'],
                $classified['description'],
                $classified['price'],
                $classified['offerNumber'],
                $classified['options']
            );
        }

        return $createdClassifieds;
    }
}
