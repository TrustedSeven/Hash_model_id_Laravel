Hash Model Ids
==============

Dynamically create a hash of an Eloquent model id value to avoid exposing a record's actual database id.


## Features

```php
use Netsells\HashModelIds\HashesModelIdsTrait;

class Model extends BaseModel
{
    use HashesModelIdsTrait;
}
```

Use the `HashesModelIdsTrait` in an Eloquent model to enable the following functionality:

 * Access a model's hashed id value: `$model->hashed_id`
 * Filter a model by an array of or a single hashed id:
   * `Model::whereHashedId($hashedId)`
   * `Model::whereHashedIds($hashedIds)`
 * Define routes with models bound by their hashed id:
    ```php
    Route::get('models/{model}', function (Model $model) {
        return $model;
    });

    
    $url = url("models/$model->hashed_id");
    ```
 * Check for model existence in form request classes using a bespoke rule, optionally including additional chained constraints:
    ```php
    public function rules()
    {
        return [
            'hashed_id' => [
                ExistsWithHashedIdRule::make(Model::class)
                    ->where(function ($query) {
                        $query->where('type', 'test');
                    })
                    ->where('foo', 'bar'),
            ],
        ];
    }
    ```


## Installation

Install the package with:

`composer require netsells/hash-model-ids`

Publish the package config file:

`php artisan vendor:publish --tag=hash-model-ids-config`

Optionally set a `HASH_MODEL_IDS_SALT` in `.env`.


## Translations

Publish the package translations file:

`php artisan vendor:publish --tag=hash-model-ids-lang`


## Testing

`./vendor/bin/phpunit`
