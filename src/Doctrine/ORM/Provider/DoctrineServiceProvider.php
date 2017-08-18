<?php
namespace Dittto\DoctrineEntityFactories\Doctrine\ORM\Provider;

use Dittto\DoctrineEntityFactories\Doctrine\ORM\Configuration\YamlWithEntityFactories;
use Illuminate\Contracts\Container\Container;
use LaravelDoctrine\ORM\Configuration\MetaData\MetaDataManager;

class DoctrineServiceProvider extends \LaravelDoctrine\ORM\DoctrineServiceProvider
{
    protected function setupMetaData()
    {
        $this->app->singleton(MetaDataManager::class, function (Container $app) {
            return (new MetaDataManager($app))
                ->extend('yaml_with_entity_factories', function () {
                    return new YamlWithEntityFactories();
                });
        });
    }
}