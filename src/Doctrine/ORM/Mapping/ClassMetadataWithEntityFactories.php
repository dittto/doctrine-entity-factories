<?php
namespace Dittto\DoctrineEntityFactories\Doctrine\ORM\Mapping;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\NamingStrategy;

class ClassMetadataWithEntityFactories extends ClassMetadata
{
    /** @var EntityFactoryInterface[]  */
    private $entityFactories;

    public function __construct($entityName, NamingStrategy $namingStrategy = null, array $entityFactories = [])
    {
        parent::__construct($entityName, $namingStrategy);
        $this->entityFactories = $entityFactories;
    }

    public function newInstance()
    {
        if (isset($this->entityFactories[$this->name])) {
            return $this->entityFactories[$this->name]->getEntity();
        }

        return parent::newInstance();
    }

    public function setFactories(array $entityFactories): void
    {
        $this->entityFactories = $entityFactories;
    }
}
