# Entity factories for Doctrine

## How to use

- Create entity factory
```php
<?php
namespace App\Entities\Factories;

use App\Entities\TestEntity;
use Dittto\DoctrineEntityFactories\Doctrine\ORM\Mapping\EntityFactoryInterface;
use Illuminate\Contracts\Validation\Validator;

class TestEntityFactory implements EntityFactoryInterface
{
    private $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function getEntity()
    {
        return new TestEntity($this->validator);
    }
}
```

### With Laravel

- Install Laravel-Doctrine
```bash
composer require laravel-doctrine/orm:1.3.*
```

- Create provider
```php
<?php
namespace App\Providers;

use App\Entities\Factories\TestEntityFactory;
use App\Entities\TestEntity;
use Dittto\DoctrineEntityFactories\Doctrine\ORM\Mapping\EntityFactoryAware;
use Dittto\DoctrineEntityFactories\Doctrine\ORM\Provider\AbstractDoctrineServiceProvider;

class DoctrineServiceProvider extends AbstractDoctrineServiceProvider
{
    public function registerEntityFactories(EntityFactoryAware $entityFactoryRegister)
    {
        $entityFactoryRegister->addEntityFactory(TestEntity::class, new TestEntityFactory($this->app->make('validator')));
    }
}
```

- Add to config
```php
<?php
return [
'providers' => [
        App\Providers\DoctrineServiceProvider::class,
    ],
];
```

- Update doctrine config to use new setup - currently only supports yaml
```php
<?php
return [
    'managers' => [
        'default' => [
            'meta' => env('DOCTRINE_METADATA', 'yaml_with_entity_factories'),
        ]
    ]
];
```

### With Symfony