<?php

namespace Meow\Hydrator\Test;

use Meow\Hydrator\Hydrator;
use Meow\Hydrator\Test\Model\TestModel;
use PHPUnit\Framework\TestCase;

class HydratorTest extends TestCase
{
    protected array $testModelData = [
        "name" => "May",
        'email' => 'may@locahlost.tld'
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

        $this->assertEquals($this->testModelData, $hydrator->extract($testModel, ['name', 'email']));
    }
}