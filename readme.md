# Broker [![Build Status](https://travis-ci.org/michaeljennings/broker.svg?branch=master)](https://travis-ci.org/michaeljennings/broker) [![Coverage Status](https://coveralls.io/repos/github/michaeljennings/broker/badge.svg?branch=master)](https://coveralls.io/github/michaeljennings/broker?branch=master)

A laravel package that allows you to cache items against an object. For example you may want to cache a user's navigation items against the user model.

```php
broker()->put($user, 'navigation', $navigationItems, 60);
```

## Installation

To install through composer simply run the following command:

```
composer require michaeljennings/broker
```

Or add the following to your `composer.json` file:

```
{
  "require": {
    "michaeljennings/broker": "^1.0"
  }
}
```

And then run `composer install`.

For Laravel 5.5 and upwards, the service provider and facade will be loaded automatically. For older versions of Laravel, you will need to add the broker service provider into your providers array in `config/app.php`.

```php
'providers' => [
  ...
  'Michaeljennings\Broker\BrokerServiceProvider'
  ...
];
```

The package also comes with a facade, to use it add it to your aliases array in config/app.php.

```php
'aliases' => [
  ...
  'Broker' => 'Michaeljennings\Broker\Facades\Broker',
  ...
];
```

## Cacheable Entities

To make a class cacheable you need to implement the `Michaeljennings\Broker\Contracts\Cacheable` interface and implement the `getCacheKey` method. This must return a unique key for this class. 

```php
class Dummy implements \Michaeljennings\Broker\Contracts\Cacheable
{
	public function getCacheKey()
	{
		return get_class($this);
	}
}
```

### Cacheable Models

If you want to make an eloquent model cacheable you can use the `Michaeljennings\Broker\Traits\Cacheable` trait. This automatically uses the table name as the cache key.

```php
use Illuminate\Database\Eloquent\Model;

class Dummy extends Model implements \Michaeljennings\Broker\Contracts\Cacheable
{
	use \Michaeljennings\Broker\Traits\Cacheable;
}
```

## Usage

Broker can be accessed either by its facade, its helper method, or through its IOC binding.

```php
// Facade
Michaeljennings\Broker\Facades\Broker::get($cachable, 'key');
// Helper
broker()->get($cachable, 'key');
// IOC Binding
app(Michaeljennings\Broker\Contracts\Broker)->get($cachable, 'key');
```

### Retrieving Items From Cache

The `get` method is used to retrieve an item for the cacheable entity. If the item does not exist `null` will be returned.

```php
broker()->get($cacheable, 'key');
```

#### Checking If An Item Exists

The `has` method will check if the key has been set for the cacheable entity.

```php
broker()->has($cacheable, 'key');
```

#### Retrieve/Store

Occasionally you may want to retrieve an item, but also set the value if it is not set. You can do this using the `remember` method.

```php
broker()->remember($cacheable, 'key', function() {
  return DB::table('users')->get();
});
```

### Storing Items In The Cache

The `put` method will add an item to the cache for the cacheable entity. By default items will be stored for 60 minutes, but you can specify the amount minutes.

```php
broker()->put($cacheable, 'key', 'value');
broker()->put($cacheable, 'key', 'value', $minutes);
```

#### Storing Items Forever

The `forever` method will store items in the cache indefinitely. These items will need to be removed manually with the `forget` method.

```php
broker()->forever($cacheable, 'key');
```

### Removing Items From The Cache

The `forget` method will remove a specific item from the cache.

```php
broker()->forget($cacheable, 'key');
```

Or you may remove all of the items for a cacheable entity with the `flush` method.

```php
broker()->flush($cacheable);
```

Occasionally changes in your application may require you to flush the cache for every entity of a cacheable type, for example you need to clear the cache for every user, but want the rest of the cache to remain.

To this you can use the `flushAll` method and pass it the class you want to flush.

```php
broker()->flushAll(App\User::class);
```