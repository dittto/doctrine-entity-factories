<?php
namespace Dittto\DoctrineEntityFactories\Doctrine\ORM\Configuration;

use Dittto\DoctrineEntityFactories\Doctrine\ORM\Mapping\ClassMetadataFactoryWithEntityFactories;
use PHPUnit\Framework\TestCase;

class YamlWithEntityFactoriesTest extends TestCase
{
    public function testMetadataFactoryNameIsOverridden()
    {
        $factory = new YamlWithEntityFactories();
        $this->assertEquals(ClassMetadataFactoryWithEntityFactories::class, $factory->getClassMetadataFactoryName());
    }
}