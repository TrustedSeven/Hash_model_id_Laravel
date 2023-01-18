<?php

namespace Netsells\HashModelIds;

use Illuminate\Database\Eloquent\Model;

interface ModelIdHasherInterface
{
    public function encode(Model $model, $id): string;

    public function decode(Model $model, $hash): string;
}
