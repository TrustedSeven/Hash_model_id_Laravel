<?php

namespace Netsells\HashModelIds;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class ModelIdHasher implements ModelIdHasherInterface
{
    private array $config;

    private array $instances;

    public function __construct(array $config)
    {
        if (empty($config['salt'] ?? '')) {
            throw new InvalidArgumentException('A hashids salt must be set!');
        }

        $this->config = $config;
    }

    public function encode(Model $model, $id): string
    {
        return $this->getInstance($model)->encode($id);
    }

    public function decode(Model $model, $hash): string
    {
        return implode('', $this->getInstance($model)->decode($hash));
    }

    private function getInstance(Model $model): Hashids
    {
        $class = $model::class;

        if (! isset($this->instances[$class])) {
            $this->instances[$class] = $this->getNewInstance($class);
        }

        return $this->instances[$class];
    }

    private function getNewInstance(string $class): Hashids
    {
        return new Hashids(
            $class.$this->config['salt'],
            $this->config['min_hash_length'],
            $this->config['alphabet'],
        );
    }
}
