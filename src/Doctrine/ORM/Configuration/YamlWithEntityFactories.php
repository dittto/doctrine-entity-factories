<?php
namespace Dittto\DoctrineEntityFactories\Doctrine\ORM\Configuration;

use Dittto\DoctrineEntityFactories\Doctrine\ORM\Mapping\ClassMetadataFactoryWithEntityFactories;
use LaravelDoctrine\ORM\Configuration\MetaData\Yaml;

class YamlWithEntityFactories extends Yaml
{
    public function getClassMetadataFactoryName(): string
    {
        return ClassMetadataFactoryWithEntityFactories::class;
    }
}