<?php
namespace Dittto\DoctrineEntityFactories\Doctrine\ORM\Mapping;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataFactory;

class ClassMetadataFactoryWithEntityFactories extends ClassMetadataFactory implements EntityFactoryAware
{
    /** @var EntityFactoryInterface[] */
    private $entityFactories = [];

    /** @var EntityManagerInterface */
    private $em;

    public function setEntityManager(EntityManagerInterface $em)
    {
        parent::setEntityManager($em);
        $this->em = $em;
    }

    protected function newClassMetadataInstance($className): ClassMetadata
    {
        return new ClassMetadataWithEntityFactories($className, $this->em->getConfiguration()->getNamingStrategy(), $this->entityFactories);
    }

    public function addEntityFactory(string $name, EntityFactoryInterface $entityFactory): void
    {
        $this->entityFactories[$name] = $entityFactory;
    }

    /**
     * @return EntityFactoryInterface[]
     */
    public function getEntityFactories(): array
    {
        return $this->entityFactories;
    }
}
