# Broker

A laravel package that allows you to cache items against an object. For example you may want to cache a user's navigation items against the user model.

```php
broker()->put($user, 'navigation', $navigationItems, 60);
```

More docs coming soon...