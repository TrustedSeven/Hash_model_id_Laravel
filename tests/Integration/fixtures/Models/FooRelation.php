<?php

namespace Netsells\HashModelIds\Tests\Integration\fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Netsells\HashModelIds\HashesModelIdsTrait;

class FooRelation extends Model
{
    use HashesModelIdsTrait;
}
