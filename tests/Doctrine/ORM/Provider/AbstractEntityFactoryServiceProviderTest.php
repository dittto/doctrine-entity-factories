<?php
namespace Dittto\DoctrineEntityFactories\Doctrine\ORM\Provider;

use Dittto\DoctrineEntityFactories\Doctrine\ORM\Mapping\EntityFactoryAware;
use Illuminate\Contracts\Foundation\Application;
use LaravelDoctrine\ORM\Configuration\MetaData\MetaDataManager;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class AbstractEntityFactoryServiceProviderTest extends TestCase
{
    /** @var MockInterface */
    private $app;

    public function setUp()
    {
        $this->app = \Mockery::mock(Application::class);
    }

    public function testProvidesContainsMetadataManager()
    {
        $provider = new class($this->app) extends AbstractEntityFactoryServiceProvider {
            public function registerEntityFactories(EntityFactoryAware $entityFactoryRegister) {}
        };

        $this->assertContains(MetaDataManager::class, $provider->provides());
    }

    public function testRegisteringEntityFactoriesIfAware()
    {
        $this->app->shouldReceive('make')->andReturn(\Mockery::mock(EntityFactoryAware::class));

        $provider = new class($this->app) extends AbstractEntityFactoryServiceProvider {
            public $isCalled = false;
            public function registerEntityFactories(EntityFactoryAware $entityFactoryRegister) {
                $this->isCalled = true;
            }
        };

        $provider->register();
        $this->assertTrue($provider->isCalled);
    }

    public function testNotRegisteringEntityFactoriesWhenNotAware()
    {
        $this->app->shouldReceive('make')->andReturn(new \StdClass());

        $provider = new class($this->app) extends AbstractEntityFactoryServiceProvider {
            public $isCalled = false;
            public function registerEntityFactories(EntityFactoryAware $entityFactoryRegister) {
                $this->isCalled = true;
            }
        };

        $provider->register();
        $this->assertFalse($provider->isCalled);
    }
}