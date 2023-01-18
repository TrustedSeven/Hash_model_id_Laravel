<?php

namespace Netsells\HashModelIds\Tests\Integration;

use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;
use Netsells\HashModelIds\ExistsWithHashedIdRule;
use Netsells\HashModelIds\Tests\Integration\fixtures\Models;

class ExistsWithHashedIdRuleTest extends AbstractIntegrationTest
{
    public function testRuleExpectsEloquentModels(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ExistsWithHashedIdRule('AbstractClass');
    }

    public function testRuleExpectsHashingModels(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ExistsWithHashedIdRule(Models\Bar::class);
    }

    public function testRuleHandlesExpectedValueTypes(): void
    {
        $rule = new ExistsWithHashedIdRule(Models\Foo::class);

        $this->assertFalse($rule->passes(null, null));
        $this->assertFalse($rule->passes(null, []));
        $this->assertFalse($rule->passes(null, ''));
    }

    public function testRulePassesForExistingHashedId(): void
    {
        $foo = Models\Foo::create();

        $rule = new ExistsWithHashedIdRule(Models\Foo::class);

        $this->assertTrue($rule->passes(null, $foo->hashed_id));
    }

    public function testRuleFailsForNonExistentHashedId(): void
    {
        $foo = Models\Foo::create();

        $rule = new ExistsWithHashedIdRule(Models\Foo::class);

        $this->assertFalse($rule->passes(null, $foo->id));
    }

    public function testRuleHandlesConstraintsFluently(): void
    {
        $foo = Models\Foo::create([]);

        $rule = new ExistsWithHashedIdRule(Models\Foo::class);

        $this->assertFalse(
            $rule
                ->where(function (Builder $query) use ($foo) {
                    $query->whereId($foo->hashed_id);
                })
                ->passes(null, $foo->hashed_id)
        );

        $rule = new ExistsWithHashedIdRule(Models\Foo::class);

        $this->assertTrue(
            $rule
                ->where(function (Builder $query) use ($foo) {
                    $query->whereId($foo->id);
                })
                ->whereNotNull('created_at')
                ->passes(null, $foo->hashed_id)
        );
    }
}
