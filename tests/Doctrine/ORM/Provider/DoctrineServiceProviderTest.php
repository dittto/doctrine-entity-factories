<?php
namespace Dittto\DoctrineEntityFactories\Doctrine\ORM\Provider;

use Dittto\DoctrineEntityFactories\Doctrine\ORM\Configuration\YamlWithEntityFactories;
use Illuminate\Contracts\Container\Container;
use PHPUnit\Framework\TestCase;

class DoctrineServiceProviderTest extends TestCase
{
    public function testMetadataUsesYamlWithEntityFactories()
    {
        $call = [];
        $app = \Mockery::mock(implode(',', [Container::class, \ArrayAccess::class]));
        $app->shouldReceive('singleton')->andReturnUsing(function ($name, $closure) use (&$call) {
            $call = [$name, $closure];
        });

        $testProvider = new class($app) extends DoctrineServiceProvider {
            public function testForSetupMetaData() {
                $this->setupMetaData();
            }
        };
        $testProvider->testForSetupMetaData();

        $this->assertInstanceOf(YamlWithEntityFactories::class, $call[1]($app)->driver('yaml_with_entity_factories'));
    }
}