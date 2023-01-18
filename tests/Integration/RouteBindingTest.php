<?php

namespace Netsells\HashModelIds\Tests\Integration;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Netsells\HashModelIds\Tests\Integration\fixtures\Models\Foo;

class RouteBindingTest extends AbstractIntegrationTest
{
    public function testRouteBindingWithIdKey(): void
    {
        Route::middleware(SubstituteBindings::class)
            ->get('foos/{foo:id}', function (Foo $foo) {
                return $foo;
            });

        $foo = Foo::create();

        $this->json('GET', url("foos/$foo->id"))->assertOk();
        $this->json('GET', url("foos/$foo->hashed_id"))->assertNotFound();
    }

    public function testRouteBindingWithHashedIdKey(): void
    {
        Route::middleware(SubstituteBindings::class)
            ->get('foos/{foo:hashed_id}', function (Foo $foo) {
                return $foo;
            });

        $foo = Foo::create();

        $this->json('GET', url("foos/$foo->id"))->assertNotFound();
        $this->json('GET', url("foos/$foo->hashed_id"))->assertOk();
    }

    public function testRouteBindingWithoutKey(): void
    {
        Route::middleware(SubstituteBindings::class)
            ->get('foos/{foo}', function (Foo $foo) {
                return $foo;
            });

        $foo = Foo::create();

        $this->json('GET', url("foos/$foo->id"))->assertNotFound();
        $this->json('GET', url("foos/$foo->hashed_id"))->assertOk();
    }
}
