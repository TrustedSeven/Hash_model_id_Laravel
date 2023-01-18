<?php

namespace Netsells\HashModelIds\Tests\Integration;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Netsells\HashModelIds\Tests\Integration\fixtures\Models\Foo;
use Netsells\HashModelIds\Tests\Integration\fixtures\Models\FooRelation;

class ScopedRouteBindingTest extends AbstractIntegrationTest
{
    public function testScopedRouteBindingWithoutKeys(): void
    {
        Route::middleware(SubstituteBindings::class)
            ->get(
                'foos/{foo}/foo-relations/{fooRelation}',
                function (Foo $foo, FooRelation $fooRelation) {
                    return [$foo, $fooRelation];
                }
            )->scopeBindings();

        $foo = Foo::create();
        $foo->fooRelations()->attach($fooRelation = FooRelation::create());

        $this->json('GET', url("foos/$foo->id/foo-relations/$fooRelation->id"))
            ->assertNotFound();

        $this->json('GET', url("foos/$foo->hashed_id/foo-relations/$fooRelation->hashed_id"))
            ->assertOk();
    }

    public function testScopedRouteBindingWithIdKey(): void
    {
        Route::middleware(SubstituteBindings::class)
            ->get(
                'foos/{foo}/foo-relations/{fooRelation:id}',
                function (Foo $foo, FooRelation $fooRelation) {
                    return [$foo, $fooRelation];
                }
            )->scopeBindings();

        $foo = Foo::create();
        $foo->fooRelations()->attach($fooRelation = FooRelation::create());

        $this->json('GET', url("foos/$foo->hashed_id/foo-relations/$fooRelation->id"))
            ->assertOk();

        $this->json('GET', url("foos/$foo->hashed_id/foo-relations/$fooRelation->hashed_id"))
            ->assertNotFound();
    }

    public function testScopedRouteBindingWithHashedIdKey(): void
    {
        Route::middleware(SubstituteBindings::class)
            ->get(
                'foos/{foo}/foo-relations/{fooRelation:hashed_id}',
                function (Foo $foo, FooRelation $fooRelation) {
                    return [$foo, $fooRelation];
                }
            )->scopeBindings();

        $foo = Foo::create();
        $foo->fooRelations()->attach($fooRelation = FooRelation::create());

        $this->json('GET', url("foos/$foo->hashed_id/foo-relations/$fooRelation->id"))
            ->assertNotFound();

        $this->json('GET', url("foos/$foo->hashed_id/foo-relations/$fooRelation->hashed_id"))
            ->assertOk();
    }

    public function testScopedRouteBindingWithMixedKeys(): void
    {
        Route::middleware(SubstituteBindings::class)
            ->get(
                'foos/{foo:id}/foo-relations/{fooRelation:hashed_id}',
                function (Foo $foo, FooRelation $fooRelation) {
                    return [$foo, $fooRelation];
                }
            )->scopeBindings();

        $foo = Foo::create();
        $foo->fooRelations()->attach($fooRelation = FooRelation::create());

        $this->json('GET', url("foos/$foo->id/foo-relations/$fooRelation->id"))
            ->assertNotFound();

        $this->json('GET', url("foos/$foo->id/foo-relations/$fooRelation->hashed_id"))
            ->assertOk();
    }
}
