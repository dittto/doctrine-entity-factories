<?php
namespace Dittto\DoctrineEntityFactories\Doctrine\ORM\Decorator;

use Dittto\DoctrineEntityFactories\Doctrine\ORM\Mapping\EntityFactoryAware;
use Dittto\DoctrineEntityFactories\Doctrine\ORM\Mapping\EntityFactoryInterface;
use Doctrine\ORM\Decorator\EntityManagerDecorator;
use Doctrine\ORM\EntityManagerInterface;

class EntityFactoryManagerDecorator extends EntityManagerDecorator implements EntityFactoryAware
{
    public function __construct(EntityManagerInterface $wrapped)
    {
        parent::__construct($wrapped);
    }

    public function addEntityFactory(string $name, EntityFactoryInterface $entityFactory): void
    {
        $metadataFactory = $this->wrapped->getMetadataFactory();
        if ($metadataFactory instanceof EntityFactoryAware) {
            $metadataFactory->addEntityFactory($name, $entityFactory);
        }
    }
}