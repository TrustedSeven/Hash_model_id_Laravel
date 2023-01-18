<?php

namespace Netsells\HashModelIds\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Netsells\HashModelIds\ModelIdHasher;
use Netsells\HashModelIds\Tests\Unit\fixtures\Models;
use PHPUnit\Framework\TestCase;

class ModelIdHasherTest extends TestCase
{
    private const CONFIG = [
        'salt' => 'test-salt',
        'min_hash_length' => 10,
        'alphabet' => 'abcdefghijklmnopqrstuvwxyz0123456789',
    ];

    public function testInstanceRequiresConfiguration(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->getNewModelIdHasher([]);
    }

    public function testHasherCanEncodeAndDecode(): void
    {
        $idHasher = $this->getNewModelIdHasher();

        /** @var Model|MockObject $model */
        $model = $this->getMockForAbstractClass(Model::class);

        $hash = $idHasher->encode($model, 1);

        $this->assertEquals(1, $idHasher->decode($model, $hash));
    }

    public function testHashesDifferForModelsWithSameId(): void
    {
        $idHasher = $this->getNewModelIdHasher();

        /** @var Model|MockObject $foo */
        $foo = $this->getMockForAbstractClass(Models\Foo::class);

        /** @var Model|MockObject $bar */
        $bar = $this->getMockForAbstractClass(Models\Bar::class);

        $this->assertNotSame($idHasher->encode($foo, 1), $idHasher->encode($bar, 1));
    }

    private function getNewModelIdHasher(array $config = self::CONFIG): ModelIdHasher
    {
        return new ModelIdHasher($config);
    }
}
