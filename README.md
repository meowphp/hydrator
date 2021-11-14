# Hydrator

__namespace:__ `Meow\Hydrator`

Library that can hydrate (fill object with data from array) and extract data from
object back to array.

## Installation

To install this library into your project use composer script:

```bash
composer require meow/hydrator
```

## Usage

### Hydrate

Filling object from array

```php
protected array $testModelData = [
    "name" => "May",
    'email' => 'may@locahlost.tld'
];
// ...
$hydrator = new Hydrator();
$testModel = $hydrator->hydrate(TestModel::class, $this->testModelData);
```

### Extract

Extraction properties and their values back to the array.

```php
$testModelDataArray = $hydrator->extract($testModel, ['name', 'email']);
```

__License: MIT__