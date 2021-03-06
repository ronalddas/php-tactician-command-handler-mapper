<?php

namespace WyriHaximus\Tests\Tactician\CommandHandler;

use Doctrine\Common\Annotations\AnnotationReader;
use Phake;
use WyriHaximus\Tactician\CommandHandler\Annotations\Handler;
use WyriHaximus\Tactician\CommandHandler\Mapper;

class MapperTest extends \PHPUnit_Framework_TestCase
{
    public function testMapInstantiated()
    {
        $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'test-app' . DIRECTORY_SEPARATOR . 'Commands' . DIRECTORY_SEPARATOR;
        $namespace = 'Test\App\Commands';
        $map = Mapper::mapInstantiated($path, $namespace);

        $this->assertSame(1, count($map));
        $this->assertTrue(isset($map['Test\App\Commands\AwesomesauceCommand']));
        $this->assertInstanceOf('Test\App\Handlers\AwesomesauceHandler', $map['Test\App\Commands\AwesomesauceCommand']);
    }

    public function testMap()
    {
        $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'test-app' . DIRECTORY_SEPARATOR . 'Commands' . DIRECTORY_SEPARATOR;
        $namespace = 'Test\App\Commands';
        $map = Mapper::map($path, $namespace);

        $this->assertSame(
            [
                'Test\App\Commands\AwesomesauceCommand' => 'Test\App\Handlers\AwesomesauceHandler',
            ],
            $map
        );
    }

    public function testGetHandlerByCommand()
    {
        $handler = new Handler([
            'handdler',
        ]);
        $reader = Phake::mock(AnnotationReader::class);
        Phake::when($reader)->getClassAnnotation($this->isInstanceOf('ReflectionClass'), Handler::class)->thenReturn($handler);
        $result = Mapper::getHandlerByCommand('stdClass', $reader);
        $this->assertSame('handdler', $result);
    }

    public function testGetHandlerByCommandStdClass()
    {
        $result = Mapper::getHandlerByCommand('stdClass', new AnnotationReader());
        $this->assertSame('', $result);
    }
}
