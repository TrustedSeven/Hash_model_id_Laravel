<?php

namespace Netsells\HashModelIds\Tests\Integration;

use Netsells\HashModelIds\Tests\Integration\fixtures\Models\Foo;

class HashedModelIdTest extends AbstractIntegrationTest
{
    private Foo $foo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->foo = Foo::create();
    }

    public function testModelHasHashedIdAttribute(): void
    {
        $this->assertNotNull($this->foo->hashed_id);
    }

    public function testScopeWhereHashedId(): void
    {
        $hash = $this->foo->hashed_id;

        $foo = Foo::whereHashedId($hash)->first();

        $this->assertTrue($foo->is($this->foo));
    }

    public function testScopeWhereHashedIds(): void
    {
        $hash = $this->foo->hashed_id;
        $hash2 = Foo::create()->hashed_id;

        $foos = Foo::whereHashedIds([$hash, $hash2])->get();

        $this->assertCount(2, $foos);
    }
}
