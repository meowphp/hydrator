<?php

namespace Meow\Hydrator\Test;

use Meow\Hydrator\Hydrator;
use Meow\Hydrator\Test\Model\Character;
use Meow\Hydrator\Test\Model\TestModel;
use Meow\Hydrator\Test\Model\Weapon;
use PHPUnit\Framework\TestCase;

class HydratorTest extends TestCase
{
    protected array $testModelData = [
        "name" => "May",
        'email' => 'may@locahlost.tld',
        'not_existed_property' => 'property which is not exist in target class'
    ];

    public function testHydration(): void
    {
        $hydrator = new Hydrator();

        /** @var TestModel $testModel */
        $testModel = $hydrator->hydrate(TestModel::class, $this->testModelData);

        $this->assertEquals($this->testModelData['name'], $testModel->getName());
        $this->assertEquals($this->testModelData['email'], $testModel->getEmail());
    }

    public function testExtraction(): void
    {
        $hydrator = new Hydrator();

        $testModel = new TestModel($this->testModelData['name'], $this->testModelData['email']);

        unset($this->testModelData['not_existed_property']);
        $this->assertEquals($this->testModelData, $hydrator->extract($testModel, ['name', 'email']));
    }

    public function testHydrationWithNestedObjects(): void
    {
        $hydrator = new Hydrator();

        $testModelData = [
            'name' => 'Frodo',
            'characterClass' => 'varior',
            'equipment' => [
                'name' => 'Sting',
                'weapon' => [
                    'name' => 'Dagger',
                    'damage' => [
                        'min' => 1,
                        'max' => 2
                    ]
                ], 
            ],
            "inventory" => [
                [
                    'name' => 'Dagger+1',
                    'damage' => [
                        'min' => 1,
                        'max' => 2
                    ]
                ],
                [
                    'name' => 'Sword',
                    'damage' => [
                        'min' => 1,
                        'max' => 2
                    ]
                ]
            ]
        ];

        $model = $hydrator->hydrate(Character::class, $testModelData);

        $this->assertEquals($testModelData['name'], $model->getName());
        $this->assertEquals($testModelData['characterClass'], $model->getCharacterClass());
        $this->assertEquals($testModelData['equipment']['name'], $model->getEquipment()->getName());

        $i = 0;
        foreach ($model->getInventory() as $intentoryItem) {
            $this->assertNotInstanceOf(Weapon::class, $intentoryItem::class);

            $this->assertEquals($testModelData['inventory'][$i]['name'], $intentoryItem->getName());

            $i++;
        }

        //$this->assertEquals($testModelData, $hydrator->extract($model, []));
    }
}