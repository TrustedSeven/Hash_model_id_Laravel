<?php

namespace Netsells\HashModelIds\Tests\Integration\fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Netsells\HashModelIds\HashesModelIdsTrait;

class Trashed extends Model
{
    use HashesModelIdsTrait;
    use SoftDeletes;

    public function newQuery()
    {
        return parent::newQuery()->withTrashed();
    }
}
