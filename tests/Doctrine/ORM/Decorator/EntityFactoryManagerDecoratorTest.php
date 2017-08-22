<?php
namespace Dittto\DoctrineEntityFactories\Doctrine\ORM\Decorator;

use Dittto\DoctrineEntityFactories\Doctrine\ORM\Mapping\EntityFactoryAware;
use Dittto\DoctrineEntityFactories\Doctrine\ORM\Mapping\EntityFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class EntityFactoryManagerDecoratorTest extends TestCase
{
    public function testAddingEntityFactory()
    {
        $call = [];

        $metadataFactory = \Mockery::mock(EntityFactoryAware::class);
        $metadataFactory->shouldReceive('addEntityFactory')
            ->andReturnUsing(function (string $name, EntityFactoryInterface $entityFactory) use (&$call) {
                $call = [$name, $entityFactory];
            });

        $manager = \Mockery::mock(EntityManagerInterface::class);
        $manager->shouldReceive('getMetadataFactory')->andReturn($metadataFactory);

        $decorator = new EntityFactoryManagerDecorator($manager);
        $decorator->addEntityFactory('test_name', \Mockery::mock(EntityFactoryInterface::class));

        $this->assertEquals('test_name', $call[0]);
    }
}