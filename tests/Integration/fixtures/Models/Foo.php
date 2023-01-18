<?php

namespace Netsells\HashModelIds\Tests\Integration\fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Netsells\HashModelIds\HashesModelIdsTrait;

class Foo extends Model
{
    use HashesModelIdsTrait;

    public function fooRelations(): BelongsToMany
    {
        return $this->belongsToMany(FooRelation::class);
    }
}
