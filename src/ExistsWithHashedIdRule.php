<?php

namespace Netsells\HashModelIds;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\ForwardsCalls;
use InvalidArgumentException;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ExistsWithHashedIdRule implements Rule
{
    use ForwardsCalls;

    private string $class;

    private array $constraints = [];

    /**
     * Make a new rule instance fluently.
     *
     * @param string $class
     *
     * @return self
     */
    public static function make(string $class): self
    {
        return new self($class);
    }

    /**
     * Create a new rule instance.
     *
     * @param class-string<Model> $class
     *
     * @return void
     */
    public function __construct(string $class)
    {
        if (! is_a($class, Model::class, true)) {
            throw new InvalidArgumentException('The given class must be an instance of '.Model::class);
        }

        if (! in_array(HashesModelIdsTrait::class, class_uses_recursive($class))) {
            throw new InvalidArgumentException('The given class must use the '.HashesModelIdsTrait::class.' trait');
        }

        $this->class = $class;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (! is_string($value) || empty($value)) {
            return false;
        }

        return $this->class::whereHashedId($value)
            ->tap(function (Builder $query) {
                $this->applyConstraints($query);
            })
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('hashModelIds::validation.model_not_exist_for_hashed_id', [
            'name' => class_basename($this->class),
        ]);
    }

    /**
     * Add query builder constraints to the rule.
     *
     * @param string $method
     * @param mixed $parameters
     *
     * @return self
     */
    public function __call(string $method, array $parameters): self
    {
        $this->constraints[$method][] = $parameters;

        return $this;
    }

    private function applyConstraints(Builder $query): void
    {
        foreach ($this->constraints as $method => $constraint) {
            foreach ($constraint as $parameters) {
                $this->forwardCallTo($query, $method, $parameters);
            }
        }
    }
}
