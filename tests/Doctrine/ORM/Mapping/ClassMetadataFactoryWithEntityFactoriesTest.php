<?php
namespace Dittto\DoctrineEntityFactories\Doctrine\ORM\Mapping;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;

class ClassMetadataFactoryWithEntityFactoriesTest extends TestCase
{
    private $factory;

    public function setUp()
    {
        $this->factory = new class() extends ClassMetadataFactoryWithEntityFactories {
            public function testNewClassMetadataInstance($className): ClassMetadata {
                return $this->newClassMetadataInstance($className);
            }
        };

        $configuration = \Mockery::mock(\Doctrine\DBAL\Configuration::class);
        $configuration->shouldReceive('getNamingStrategy');

        $entityManager = \Mockery::mock(EntityManagerInterface::class);
        $entityManager->shouldReceive('getConfiguration')->andReturn($configuration);

        $this->factory->setEntityManager($entityManager);
    }

    public function testMetadataInstanceHasWithEntityFactories()
    {
        $this->assertInstanceOf(ClassMetadataWithEntityFactories::class, $this->factory->testNewClassMetadataInstance('test_class_name'));
    }

    public function testAddingEntityFactories()
    {
        $this->factory->addEntityFactory('test_one', \Mockery::mock(EntityFactoryInterface::class));
        $this->factory->addEntityFactory('test_two', \Mockery::mock(EntityFactoryInterface::class));

        $factories = $this->factory->getEntityFactories();

        $this->assertArrayHasKey('test_one', $factories);
        $this->assertArrayHasKey('test_two', $factories);
    }
}
