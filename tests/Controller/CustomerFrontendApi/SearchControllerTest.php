<?php

declare(strict_types=1);

namespace App\Tests\Controller\CustomerFrontendApi;

use App\Entity\PropertyGroupOption;
use App\Tests\ApiControllerTestCase;

class SearchControllerTest extends ApiControllerTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

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
                    'id' => (string)$this->propertyGroupVehicleCondition->getUuid(),
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
                    'id' => (string)$this->propertyGroupBrandWithModel->getUuid(),
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
                    'id' => (string)$this->propertyGroupVehicleType->getUuid(),
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
                    'id' => (string)$this->propertyGroupBasicData->getUuid(),
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
                    'id' => (string)$this->propertyGroupEngine->getUuid(),
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
                    'id' => (string)$this->propertyGroupExteriorColor->getUuid(),
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
                    'id' => (string)$this->propertyGroupInteriorColor->getUuid(),
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
                    'id' => (string)$this->propertyGroupEquipment->getUuid(),
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
                    'price' => '12.345,00',
                    'offerNumber' => 'testOfferNumber',
                    'thumbnailUrl' => null,
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
                    'price' => '22.345,00',
                    'offerNumber' => 'testOfferNumber2',
                    'thumbnailUrl' => null,
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
                    'price' => '22.345,00',
                    'offerNumber' => 'testOfferNumber2',
                    'thumbnailUrl' => null,
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
                    'price' => '22.123,00',
                    'offerNumber' => 'testOfferNumber3',
                    'thumbnailUrl' => null,
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
                    'price' => '55.125,00',
                    'offerNumber' => 'testOfferNumber4',
                    'thumbnailUrl' => null,
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
                    'price' => '55.125,00',
                    'offerNumber' => 'testOfferNumber4',
                    'thumbnailUrl' => null,
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
                    'price' => '82.345,00',
                    'offerNumber' => 'testOfferNumber5',
                    'thumbnailUrl' => null,
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
                    'price' => '21.346,00',
                    'offerNumber' => 'testOfferNumber6',
                    'thumbnailUrl' => null,
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
                    'price' => '11.345,00',
                    'offerNumber' => 'testOfferNumber7',
                    'thumbnailUrl' => null,
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
                    'price' => '11.548,00',
                    'offerNumber' => 'testOfferNumber8',
                    'thumbnailUrl' => null,
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
                    'price' => '12.345,00',
                    'offerNumber' => 'testOfferNumber',
                    'thumbnailUrl' => null,
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
                    'price' => '22.345,00',
                    'offerNumber' => 'testOfferNumber2',
                    'thumbnailUrl' => null,
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
                    'price' => '82.345,00',
                    'offerNumber' => 'testOfferNumber5',
                    'thumbnailUrl' => null,
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
                    'price' => '21.346,00',
                    'offerNumber' => 'testOfferNumber6',
                    'thumbnailUrl' => null,
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
                    'price' => '11.345,00',
                    'offerNumber' => 'testOfferNumber7',
                    'thumbnailUrl' => null,
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
                    'price' => '11.548,00',
                    'offerNumber' => 'testOfferNumber8',
                    'thumbnailUrl' => null,
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
                    'price' => '1.548,00',
                    'offerNumber' => 'testOfferNumber9',
                    'thumbnailUrl' => null,
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
                    'price' => '2.000,00',
                    'offerNumber' => 'testOfferNumber10',
                    'thumbnailUrl' => null,
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
                    'price' => '12.345,00',
                    'offerNumber' => 'testOfferNumber',
                    'thumbnailUrl' => null,
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
                    'price' => '22.345,00',
                    'offerNumber' => 'testOfferNumber2',
                    'thumbnailUrl' => null,
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
                    'price' => '22.123,00',
                    'offerNumber' => 'testOfferNumber3',
                    'thumbnailUrl' => null,
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
                    'price' => '55.125,00',
                    'offerNumber' => 'testOfferNumber4',
                    'thumbnailUrl' => null,
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
                    'price' => '82.345,00',
                    'offerNumber' => 'testOfferNumber5',
                    'thumbnailUrl' => null,
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
                    'price' => '11.548,00',
                    'offerNumber' => 'testOfferNumber8',
                    'thumbnailUrl' => null,
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
                    'price' => '1.548,00',
                    'offerNumber' => 'testOfferNumber9',
                    'thumbnailUrl' => null,
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
                    'price' => '2.000,00',
                    'offerNumber' => 'testOfferNumber10',
                    'thumbnailUrl' => null,
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
                    'price' => '21.346,00',
                    'offerNumber' => 'testOfferNumber6',
                    'thumbnailUrl' => null,
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
                    'price' => '11.345,00',
                    'offerNumber' => 'testOfferNumber7',
                    'thumbnailUrl' => null,
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
                    'price' => '11.548,00',
                    'offerNumber' => 'testOfferNumber8',
                    'thumbnailUrl' => null,
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
                    'price' => '3.000,00',
                    'offerNumber' => 'testOfferNumber11',
                    'thumbnailUrl' => null,
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
                    'price' => '6.000,00',
                    'offerNumber' => 'testOfferNumber12',
                    'thumbnailUrl' => null,
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
                    'price' => '9.000.000,00',
                    'offerNumber' => 'testOfferNumber13',
                    'thumbnailUrl' => null,
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
                    'price' => '5.012.300,00',
                    'offerNumber' => 'testOfferNumber14',
                    'thumbnailUrl' => null,
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
                    'price' => '9.912.348,00',
                    'offerNumber' => 'testOfferNumber15',
                    'thumbnailUrl' => null,
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
                    'price' => '6.012.300,00',
                    'offerNumber' => 'testOfferNumber16',
                    'thumbnailUrl' => null,
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
                    'price' => '12.345,00',
                    'offerNumber' => 'testOfferNumber',
                    'thumbnailUrl' => null,
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
                    'price' => '82.345,00',
                    'offerNumber' => 'testOfferNumber5',
                    'thumbnailUrl' => null,
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
                    'price' => '21.346,00',
                    'offerNumber' => 'testOfferNumber6',
                    'thumbnailUrl' => null,
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
                    'price' => '11.345,00',
                    'offerNumber' => 'testOfferNumber7',
                    'thumbnailUrl' => null,
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
                    'price' => '11.548,00',
                    'offerNumber' => 'testOfferNumber8',
                    'thumbnailUrl' => null,
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
                    'price' => '1.548,00',
                    'offerNumber' => 'testOfferNumber9',
                    'thumbnailUrl' => null,
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
                    'price' => '2.000,00',
                    'offerNumber' => 'testOfferNumber10',
                    'thumbnailUrl' => null,
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
                    'price' => '1.548,00',
                    'offerNumber' => 'testOfferNumber9',
                    'thumbnailUrl' => null,
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
                    'price' => '2.000,00',
                    'offerNumber' => 'testOfferNumber10',
                    'thumbnailUrl' => null,
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
                    'price' => '2.000,00',
                    'offerNumber' => 'testOfferNumber10',
                    'thumbnailUrl' => null,
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
                    'price' => '1.548,00',
                    'offerNumber' => 'testOfferNumber9',
                    'thumbnailUrl' => null,
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
                    'price' => '2.000,00',
                    'offerNumber' => 'testOfferNumber10',
                    'thumbnailUrl' => null,
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
                    'price' => '3.000,00',
                    'offerNumber' => 'testOfferNumber11',
                    'thumbnailUrl' => null,
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
                    'price' => '6.000,00',
                    'offerNumber' => 'testOfferNumber12',
                    'thumbnailUrl' => null,
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
                    'price' => '9.000.000,00',
                    'offerNumber' => 'testOfferNumber13',
                    'thumbnailUrl' => null,
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
                    'price' => '5.012.300,00',
                    'offerNumber' => 'testOfferNumber14',
                    'thumbnailUrl' => null,
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
                    'price' => '9.912.348,00',
                    'offerNumber' => 'testOfferNumber15',
                    'thumbnailUrl' => null,
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
                    'price' => '6.012.300,00',
                    'offerNumber' => 'testOfferNumber16',
                    'thumbnailUrl' => null,
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
                    'price' => '6.000,00',
                    'offerNumber' => 'testOfferNumber12',
                    'thumbnailUrl' => null,
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
                    'price' => '9.000.000,00',
                    'offerNumber' => 'testOfferNumber13',
                    'thumbnailUrl' => null,
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
                    'price' => '5.012.300,00',
                    'offerNumber' => 'testOfferNumber14',
                    'thumbnailUrl' => null,
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
                    'price' => '9.912.348,00',
                    'offerNumber' => 'testOfferNumber15',
                    'thumbnailUrl' => null,
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
                    'price' => '6.012.300,00',
                    'offerNumber' => 'testOfferNumber16',
                    'thumbnailUrl' => null,
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
                    'price' => '11.548,00',
                    'offerNumber' => 'testOfferNumber8',
                    'thumbnailUrl' => null,
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
                    'price' => '1.548,00',
                    'offerNumber' => 'testOfferNumber9',
                    'thumbnailUrl' => null,
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
                    'price' => '2.000,00',
                    'offerNumber' => 'testOfferNumber10',
                    'thumbnailUrl' => null,
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
                    'price' => '3.000,00',
                    'offerNumber' => 'testOfferNumber11',
                    'thumbnailUrl' => null,
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
                    'price' => '6.000,00',
                    'offerNumber' => 'testOfferNumber12',
                    'thumbnailUrl' => null,
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
                    'price' => '9.000.000,00',
                    'offerNumber' => 'testOfferNumber13',
                    'thumbnailUrl' => null,
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
                    'price' => '5.012.300,00',
                    'offerNumber' => 'testOfferNumber14',
                    'thumbnailUrl' => null,
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
                    'price' => '9.912.348,00',
                    'offerNumber' => 'testOfferNumber15',
                    'thumbnailUrl' => null,
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
                    'price' => '6.012.300,00',
                    'offerNumber' => 'testOfferNumber16',
                    'thumbnailUrl' => null,
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
}
