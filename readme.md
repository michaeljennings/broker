# Broker [![Build Status](https://travis-ci.org/michaeljennings/broker.svg?branch=master)](https://travis-ci.org/michaeljennings/broker) [![Coverage Status](https://coveralls.io/repos/github/michaeljennings/broker/badge.svg?branch=master)](https://coveralls.io/github/michaeljennings/broker?branch=master)

A laravel package that allows you to cache items against an object. For example you may want to cache a user's navigation items against the user model.

```php
broker()->put($user, 'navigation', $navigationItems, 60);
```

More docs coming soon...