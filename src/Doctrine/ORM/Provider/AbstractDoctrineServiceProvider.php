<?php
namespace Dittto\DoctrineEntityFactories\Doctrine\ORM\Provider;

use Dittto\DoctrineEntityFactories\Doctrine\ORM\Configuration\YamlWithEntityFactories;
use Dittto\DoctrineEntityFactories\Doctrine\ORM\Mapping\EntityFactoryAware;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Illuminate\Contracts\Container\Container;
use LaravelDoctrine\ORM\Configuration\MetaData\MetaDataManager;
use LaravelDoctrine\ORM\DoctrineServiceProvider;

abstract class AbstractDoctrineServiceProvider extends DoctrineServiceProvider
{
    public $defer = true;

    public function provides()
    {
        return [
            'em',
            'registry',
            MetaDataManager::class,
        ];
    }

    public function register()
    {
        parent::register();

        $classMetadataFactory = $this->app->make(ClassMetadataFactory::class);
        if ($classMetadataFactory instanceof EntityFactoryAware) {
            $this->registerEntityFactories($classMetadataFactory);
        }
    }

    protected function setupMetaData()
    {
        $this->app->singleton(MetaDataManager::class, function (Container $app) {
            return (new MetaDataManager($app))
                ->extend('yaml_with_entity_factories', function () {
                    return new YamlWithEntityFactories();
                });
        });
    }

    abstract protected function registerEntityFactories(EntityFactoryAware $entityFactoryRegister);
}