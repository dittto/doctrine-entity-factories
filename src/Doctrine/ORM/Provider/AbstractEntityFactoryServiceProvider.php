<?php
namespace Dittto\DoctrineEntityFactories\Doctrine\ORM\Provider;

use Dittto\DoctrineEntityFactories\Doctrine\ORM\Mapping\EntityFactoryAware;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Illuminate\Support\ServiceProvider;
use LaravelDoctrine\ORM\Configuration\MetaData\MetaDataManager;

abstract class AbstractEntityFactoryServiceProvider extends ServiceProvider
{
    public $defer = true;

    public function provides()
    {
        return [
            MetaDataManager::class,
        ];
    }

    public function register()
    {
        $classMetadataFactory = $this->app->make(ClassMetadataFactory::class);
        if ($classMetadataFactory instanceof EntityFactoryAware) {
            $this->registerEntityFactories($classMetadataFactory);
        }
    }

    abstract protected function registerEntityFactories(EntityFactoryAware $entityFactoryRegister);
}