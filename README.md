# Entity factories for Doctrine

With it's usage of simple PHP objects for entities, Doctrine is a very easy-to-use ORM. It instantiates these entities using reflection. If you want to use entities that rely on another object via dependency injection, however, then yu're stuck with setter injection.
 
This code allows you to pull new entities from a factory instead, allowing constructor injection and cleaner code.

## How to use

First up, let's create a factory for our entity. These needs to implement `EntityFactoryInterface`.

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

The more-common framework to use Doctrine with is **Symfony**, but you can also use Doctrine with **Laravel**. The following are instructions for how to use it with both.

### With Laravel

To use Doctrine with Laravel, you can use this helpful plugin:

```bash
composer require laravel-doctrine/orm:1.3.*
php artisan vendor:publish --tag="config"
```

We're going to use a custom provider and one that exists within this plugin. The custom one is as follows:

```php
<?php
namespace App\Providers;

use App\Entities\Factories\TestEntityFactoryInterface;
use App\Entities\TestEntity;
use Dittto\DoctrineEntityFactories\Doctrine\ORM\Mapping\EntityFactoryAware;
use Dittto\DoctrineEntityFactories\Doctrine\ORM\Provider\AbstractEntityFactoryServiceProvider;

class EntityFactoryServiceProvider extends AbstractEntityFactoryServiceProvider
{
    public function registerEntityFactories(EntityFactoryAware $entityFactoryRegister)
    {
        $entityFactoryRegister->addEntityFactory(
            TestEntity::class,
            new TestEntityFactory($this->app->make('hash'))
        );
    }
}
```

This provider defers all objects. You can also convert that `new TestEntityFactory` into another object easily by extending `register()` and `provides()`. 

Next, we'll add the providers to the main app config:

```php
<?php
// config/app.php
return [
'providers' => [
        App\Providers\DoctrineServiceProvider::class,
        \Dittto\DoctrineEntityFactories\Doctrine\ORM\Provider\DoctrineServiceProvider::class,
    ],
];
```

Lastly, we'll need to alter the doctrine config to use our plugin:

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

TO DO