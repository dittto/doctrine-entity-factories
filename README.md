# Entity factories for Doctrine

With it's usage of simple PHP objects for entities, Doctrine is a very easy-to-use ORM. It instantiates these entities using reflection. If you want to use entities that rely on another object via dependency injection, however, then you're stuck with setter injection.
 
This code allows you to pull new entities from a factory instead, allowing constructor injection and cleaner code.

## How to use

First up, install this plugin.

```bash
composer require dittto/doctrine-entity-factories
```

Next, let's create a factory for our entity. These needs to implement `EntityFactoryInterface`.

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

As with most things with Symfony, we can use config for pretty much most things.

To start with, we're going to want to tell Symfony to use our metadata factory instead of the default Doctrine one:

```yaml
# app/config/config.yml
doctrine:
    orm:
        entity_managers:
            default:
                class_metadata_factory_name: Dittto\DoctrineEntityFactories\Doctrine\ORM\Mapping\ClassMetadataFactoryWithEntityFactories
```

If you're using multiple entity managers, this may change a bit, but you'll probably also have the understanding to make this work for your code.

Laravel, above, allows us to easily load additional code via it's service providers. For Symfony, we're going to take a slightly different approach and decorate Doctrine's EntityManager. The aim of both of these approaches is the same - to add our entity factories to it before Doctrine starts creating entities.
 
For Symfony, we're going to add some new services:

```yaml
services:
    dittto.doctrine_entity_factories.entity_factory_manager_decorator:
        public: false
        class: Dittto\DoctrineEntityFactories\Doctrine\ORM\Decorator\EntityFactoryManagerDecorator
        decorates: doctrine.orm.default_entity_manager
        arguments: [ "@dittto.doctrine_entity_factories.entity_factory_manager_decorator.inner" ]
        calls:
            - [addEntityFactory, ['App\Entities\TestEntity', '@app.entities.factory.test']]

    app.entities.factory.test:
        class: App\Entities\Factories\Testfactory
        arguments: ['@validator']
```

It's here we'll use the `calls` definition to add in as many entity factories as we require. The first field is the full class name of the entity to be created by the factory.

The namespaces here look non-standard for Symfony, but that's purely to tie into the example above.

## Testing

This plugin comes with it's own tests. To run these, clone the code and navigate to the directory. Then run the following:

```bash
composer install
./vendor/bin/phpunit
```