<?php
namespace Dittto\DoctrineEntityFactories\Doctrine\ORM\Mapping;

use PHPUnit\Framework\TestCase;

class ClassMetadataWithEntityFactoriesTest extends TestCase
{
    public function testEntityFactoryIsUsedWhereEntityNameMatches()
    {
        $factory = new class implements EntityFactoryInterface {
            public function getEntity() {
                $class = new \StdClass();
                $class->isCalled = true;

                return $class;
            }
        };

        $metadata = new ClassMetadataWithEntityFactories('test_name', null, ['test_name' => $factory]);
        $result = $metadata->newInstance();

        $this->assertObjectHasAttribute('isCalled', $result);
    }

    public function testEntityFactoryNotUsedWhereEntityNameDoesntMatch()
    {
        $metadata = new ClassMetadataWithEntityFactories('\StdClass', null, []);
        $result = $metadata->newInstance();

        $this->assertObjectNotHasAttribute('isCalled', $result);


    }
}